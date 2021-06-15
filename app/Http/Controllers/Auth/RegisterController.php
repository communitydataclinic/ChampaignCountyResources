<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\Layout;
use App\Model\Organization;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Model\Email;
use SendGrid;
use SendGrid\Mail\Mail;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    // use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
    public function showRegistrationForm()
    {
        $layout = Layout::first();
        $organization_info_list = Organization::select("organization_name", "organization_recordid")->distinct()->orderBy('organization_name', 'ASC')->get();
        return view('auth.register', compact('layout', 'organization_info_list'));
    }
    public function register(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'organization' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        // dd($request);
        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_organization' => $request->organization,
                'role_id' => '3', // Registered user always should be organization manager
            ]);

            if ($user) {
                //$user->user_organization = join(',', $request->organization);
                $user->save();

                // Add the user in the organization
                $user->organizations()->sync($request->organization);
                //$user->roles()->sync([2]); // 2 = client
                
                Session::flash('message', 'Your registration was completed. We will contact you to verify your information and activate your user.');
                Session::flash('status', 'success');
            }
            $layout = Layout::find(1);

            $site_name = '';
            if ($layout) {
                $site_name = $layout->site_name;
            }
            $organization_info = Organization::where('organization_recordid', '=', $request->organization)->first();
            $from = env('MAIL_FROM_ADDRESS');
            $name = env('MAIL_FROM_NAME');
            // $from_phone = env('MAIL_FROM_PHONE');

            $email = new Mail();
            $email->setFrom($from, $name);
            $subject = 'You have registered at ' . $site_name;
            $email->setSubject($subject);

            $message = '<html><body>';
            $message .= '<h1 style="color:#424242;">You have registered at  ' . $site_name . ' website.</h1>';
            $message .= '<p style="color:#424242;font-size:12px;">Your Account will be reviewed shortly for activation</p>';
            $message .= '<p style="color:#424242;font-size:12px;">Timestamp: ' . Carbon::now() . '</p>';
            $message .= '<p style="color:#424242;font-size:12px;">First Name: ' . $request->first_name . '</p>';
            $message .= '<p style="color:#424242;font-size:12px;">Last Name: ' . $request->last_name . '</p>';
            $message .= '<p style="color:#424242;font-size:12px;">Email: ' . $request->email . '</p>';
            $message .= '<p style="color:#424242;font-size:12px;">Organization: ' . $organization_info->organization_name . '</p>';
            $message .= '</body></html>';

            $email->addContent("text/html", $message);
            $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));

            // $error = '';

            $username = 'Champaign County 211 Resource Team';

            if ($request->email) {
                $email->addTo($request->email, $username);
            }
            
            
            $response = $sendgrid->send($email);
            if ($response->statusCode() == 401) {
                $error = json_decode($response->body());
            }
            //return redirect('/');
            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th);
            Session::flash('message', 'There was an error with the registration');
            Session::flash('status', 'error');
            return Redirect::back();
        }
    }
}
