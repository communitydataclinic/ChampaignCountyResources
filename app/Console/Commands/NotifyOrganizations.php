<?php

namespace App\Console\Commands;

use App\Model\Error;
use App\Model\Organization;
use App\Model\Service;
use App\Model\Layout;
use App\Model\Email;
use App\Model\User;
use SendGrid;
use SendGrid\Mail\Mail;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; 

class NotifyOrganizations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:organizations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends emails to organizations after 3 days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();

        $errors = Error::all();
        if ($errors !== null) {
            foreach($errors as &$error) {

                $org = Organization::where('organization_recordid', (int) $error -> error_organization) -> get();
                $org_users = [];
                if (count($org) > 0) {
                    $org_users = User::where('user_organization', $org[0] -> organization_recordid) -> get();
                } 
                
                $diff = $error -> created_at -> diffInWeekDays($now);

                if (count($org_users) > 0 && ($error->error_email_sent !== null && $error->error_email_sent != TRUE) && $diff > 3) {
                    try { 
                        $layout = Layout::find(1);

                        $site_name = '';
                        if ($layout) {
                            $site_name = $layout->site_name;
                        }

                        $from = env('MAIL_FROM_ADDRESS');
                        $name = env('MAIL_FROM_NAME');

                        $email = new Mail();
                        $email->setFrom($from, $name);
                        $subject = 'Three Business Days Have Passed Since Error Report Was Submitted' . $site_name;
                        $email->setSubject($subject);

                        $body = $error->error_content;

                        $message = '<html><body>';
                        $message .= '<h1 style="color:#424242;">The following error report has not been resolved yet. Users will see that there is potentially incorrect information if this is not resolved.</h1>';
                        $message .= '<p style="color:#424242;font-size:18px;">The following change was reported at  ' . $site_name . ' website.</p>';
                        $message .= '<p style="color:#424242;font-size:12px;">ID: ' . $error -> error_recordid . '</p>';
                        $message .= '<p style="color:#424242;font-size:12px;">Timestamp: ' . $error -> created_at . '</p>';
                        if (count($org) > 0) {
                            $message .= '<p style="color:#424242;font-size:12px;">Organization: ' . $org[0]->organization_name . '</p>';
                        }
                        $message .= '<p style="color:#424242;font-size:12px;">Service: ' . $error->error_service_name . '</p>';
                        $message .= '<p style="color:#424242;font-size:12px;">Body: ' . $error->error_content . '</p>';
                        $message .= '<p style="color:#424242;font-size:12px;">From: ' . $error->error_name . '</p>';
                        $message .= '<p style="color:#424242;font-size:12px;">Email: ' . $error->error_email . '</p>';
                        $message .= '<p style="color:#424242;font-size:12px;">Phone: ' . $error->error_phone . '</p>';
                        $message .= '</body></html>';

                        $email->addContent("text/html", $message);
                        $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));
                        if (count($org) > 0) {
                            $org_users = User::where('user_organization', $org[0] -> organization_recordid) -> get();
                        }

                        $org_has_active_user = False;
                        foreach($org_users as &$user) {
                            if ($user -> role_id === 3 && $user -> status == 0) {
                                $name = $user -> last_name . ', ' . $user -> first_name;
                                $org_has_active_user = True;
                                $email -> addTo($user -> email, $name);
                            }
                        }

                        if (!$org_has_active_user) {
                            $email -> addTo('champaigncountyresources@gmail.com', 'Champaign County Resources');
                        }
                        
                        $response = $sendgrid->send($email);
                        $error->error_email_sent = TRUE;
                        $error->save();
                        if ($response->statusCode() == 401) {
                            $error = json_decode($response->body());
                        }
                    } catch (\Throwable $th) {
                        Log::error($th);
                        Log::error('Email did not send correctly');
                    }
                }   
            }
        }
    }
}
