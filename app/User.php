<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Lang;
use SendGrid;
use SendGrid\Mail\Mail;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'phone_number',
        'password',
        'last_name',
        'first_name',
        'permissions',
        'remember_token',
        'user_organization',
        'role_id',
        'created_by',
        'status',
    ];
    public function roles()
    {
        return $this->belongsTo('App\Model\Role', 'role_id', 'id');
    }
    public function organizations()
    {
        return $this->belongsToMany('App\Model\Organization', 'organization_users', 'user_id', 'organization_recordid');
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * rewrite Auth forgot mail
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $from = env('MAIL_FROM_ADDRESS');
        $name = env('MAIL_FROM_NAME');

        $email = new Mail();
        $email->setFrom($from, $name);
        $subject = 'Reset Password Notification';
        $email->setSubject($subject);

        $url = url(route('password.reset', [
            'token' => $token,
            'email' => $this->email,
        ], false));

        // use system forgot template, need to convert string
        $html = (string)((new MailMessage)
            ->subject(Lang::get('Reset Password Notification'))
            ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
            ->action(Lang::get('Reset Password'), $url)
            ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
            ->line(Lang::get('If you did not request a password reset, no further action is required.'))->render());

        $html = '<html><body>
	<p>You are receiving this email because we received a password reset request for your account.</p> 
	<table class="action" width="100%" cellspacing="0" cellpadding="0" align="center"> <tbody> <tr> <td align="center"> 
	<table border="0" width="100%" cellspacing="0" cellpadding="0"> <tbody> <tr> <td align="center"> <table border="0" cellspacing="0" cellpadding="0"> <tbody> <tr>
	 <td><a class="button button-primary" href="' . $url . '" target="_blank" rel="noopener">Reset Password</a></td> </tr> </tbody> 
	 </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> <p>This password reset link will expire in 60 minutes.</p> 
     <p>If you did not request a password reset, no further action is required.</p>
     <p>Regards,</p>
     <p>Champaign County Resources</p>
</body></html>';
        $email->addContent("text/html", $html);
        $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));
        $email->addTo($this->email);

        // send result
        $response = $sendgrid->send($email);
        if ($response->statusCode() == 401) {
            // error log
            $error = json_decode($response->body());
        }
        //var_dump($response);exit;
        //$this->notify(new RestPasswordNotification($token));
    }
}
