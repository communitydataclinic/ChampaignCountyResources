<?php

namespace App\Http\Controllers\frontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Map;
use App\Model\Organization;
use App\Model\Service;
use App\Model\Error;
use App\Model\Suggest;
use App\Model\Email;
use App\Model\Layout;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use SendGrid;
use SendGrid\Mail\Mail;

class ErrorReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $map = Map::find(1);
        $organizations = Organization::pluck('organization_name', "organization_recordid");

        return view('frontEnd.error-report.create', compact('map', 'organizations'));
    }

    public function add_new_error(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'error_organization' => 'required',
            'error_service' => 'required',
            'error_name' => 'required',
            'error_email' => 'required',
        ]);
        try {
            $layout = Layout::find(1);

            $site_name = '';
            if ($layout) {
                $site_name = $layout->site_name;
            }
            $error = new Error;

            $new_recordid = Error::max('error_recordid') + 1;
            $error->error_recordid = $new_recordid;
            $error->error_organization = $request->error_organization;
            $organization_info = Organization::where('organization_recordid', '=', $request->error_organization)->first();
            $user_info = User::where('user_organization', '=', $request->error_organization)->get();
            $error->error_service = $request->error_service;
            $service_info = Service::where('service_recordid', '=', $request->error_service)->first();
            $error->error_service_name = $service_info->service_name;
            $error->error_content = $request->error_content;
            $error->error_username = $request->error_name;
            $error->error_user_email = $request->error_email;

            $error->error_user_phone = $request->error_phone;

            $from = env('MAIL_FROM_ADDRESS');
            $name = env('MAIL_FROM_NAME');
            // $from_phone = env('MAIL_FROM_PHONE');

            $email = new Mail();
            $email->setFrom($from, $name);
            $subject = 'A Error Report was Submitted at ' . $site_name;
            $email->setSubject($subject);

            $body = $request->error_content;

            $message = '<html><body>';
            $message .= '<h1 style="color:#424242;">Thanks for your report!</h1>';
            $message .= '<p style="color:#424242;font-size:18px;">The following change was reported at  ' . $site_name . ' website.</p>';
            $message .= '<p style="color:#424242;font-size:12px;">ID: ' . $new_recordid . '</p>';
            $message .= '<p style="color:#424242;font-size:12px;">Timestamp: ' . Carbon::now() . '</p>';
            $message .= '<p style="color:#424242;font-size:12px;">Organization: ' . $organization_info->organization_name . '</p>';
            $message .= '<p style="color:#424242;font-size:12px;">Service: ' . $service_info->service_name . '</p>';
            $message .= '<p style="color:#424242;font-size:12px;">Body: ' . $body . '</p>';
            $message .= '<p style="color:#424242;font-size:12px;">From: ' . $request->error_name . '</p>';
            $message .= '<p style="color:#424242;font-size:12px;">Email: ' . $request->error_email . '</p>';
            $message .= '<p style="color:#424242;font-size:12px;">Phone: ' . $request->error_phone . '</p>';
            // $message .= '<p style="color:#424242;font-size:12px;">Phone: '. $from_phone .'</p>';
            $message .= '</body></html>';

            $email->addContent("text/html", $message);
            $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));

            // $error = '';

            $username = 'Champaign County 211 Resource Team';
            $contact_email_list = Email::select('email_info')->pluck('email_info')->toArray();

            foreach ($contact_email_list as $key => $contact_email) {
                $email->addTo($contact_email, $username);
            }
            $email->addTo($request->error_email, $username);

            if($user_info != NULL){
                foreach ($user_info as $key => $user_info_list){
                    $email->addTo($user_info_list->email, $username);
                }
            }
            
            
            $response = $sendgrid->send($email);
            if ($response->statusCode() == 401) {
                $error = json_decode($response->body());
            }
            $error->save();
            Session::flash('message', 'Your report has been received.');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {

            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_error(Request $request)
    {
        try {
            $from = env('MAIL_FROM_ADDRESS');
            $name = env('MAIL_FROM_NAME');
            // $from_phone = env('MAIL_FROM_PHONE');
            $layout = Layout::find(1);

            $site_name = '';
            if ($layout) {
                $site_name = $layout->site_name;
            }
            $email = new Mail();
            $email->setFrom($from, $name);
            $subject = 'An update was made for the error you reported at ' . $site_name;
            $email->setSubject($subject);
            
            $body = 'Thank you for helping us improve! Check out the service page to see the new update.';

            $error_info = Error::where('error_recordid', '=', $request->error_recordid)->first();
            $service_info = Service::where('service_recordid', '=', $error_info->error_service)->first();
            $organization_info = Organization::where('organization_recordid', '=', $error_info->error_organization)->first();
            $message = '<html><body>';
            $message .= '<h1 style="color:#424242;">Thanks for your suggestion!</h1>';
            $message .= '<p style="color:#424242;font-size:18px;">Errors are fixed at ' . $site_name . ' website.</p>';
            $message .= '<p style="color:#424242;font-size:12px;">Timestamp: ' . Carbon::now() . '</p>';
            $message .= '<p style="color:#424242;font-size:12px;">Organization: ' . $organization_info->organization_name . '</p>';
            $message .= '<p style="color:#424242;font-size:12px;">Service: ' . $service_info->service_name  . '</p>';
            $message .= '<p style="color:#424242;font-size:12px;">Body: ' . $body . '</p>';
            $message .= '</body></html>';
            $email->addContent("text/html", $message);
            $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));


            $username = 'Champaign County 211 Resource Team';
            $contact_email_list = Email::select('email_info')->pluck('email_info')->toArray();

            foreach ($contact_email_list as $key => $contact_email) {
                $email->addTo($contact_email, $username);
            }
            // $email->addTo($request->email, $username);
            $email->addTo($service_info->service_email, $username);
            $response = $sendgrid->send($email);

            Error::where('error_recordid', $request->error_recordid)->delete();
            Session::flash('message', 'Error deleted successfully!');
            Session::flash('status', 'success');
            return back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
        
    }
}
