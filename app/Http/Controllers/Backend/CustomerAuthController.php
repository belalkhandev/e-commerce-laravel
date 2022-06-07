<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Subscriber;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class CustomerAuthController extends Controller
{	
    public function LoadLogin()
    {
        return view('frontend.login');
    }
	
    public function LoadRegister()
    {
        return view('frontend.register');
    }
	
    public function LoadReset()
    {
        return view('frontend.reset');
    }
	
    public function CustomerLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('/')->withSuccess('Signed in');
        }else{
			return redirect()->back()->withFail(__('Your email address and password incorrect.'));
		}
    }
	
    public function CustomerRegister(Request $request)
    {
		$gtext = gtext();

		$secretkey = $gtext['secretkey'];
		$recaptcha = $gtext['is_recaptcha'];
		if($recaptcha == 1){
			$request->validate([
				'g-recaptcha-response' => 'required',
				'name' => 'required',
				'email' => 'required|email|unique:users',
				'password' => 'required|confirmed|min:6',
			]);
			
			$captcha = $request->input('g-recaptcha-response');

			$ip = $_SERVER['REMOTE_ADDR'];
			$url = 'https://www.google.com/recaptcha/api/siteverify?secret='.urlencode($secretkey).'&response='.urlencode($captcha).'&remoteip'.$ip;
			$response = file_get_contents($url);
			$responseKeys = json_decode($response, true);
			if($responseKeys["success"] == false) {
				return redirect("user/register")->withFail(__('The recaptcha field is required'));
			}
		}else{
			$request->validate([
				'name' => 'required',
				'email' => 'required|email|unique:users',
				'password' => 'required|confirmed|min:6',
			]);
		}
		
		$data = array(
			'name' => $request->input('name'),
			'email' => $request->input('email'),
			'password' => Hash::make($request->input('password')),
			'bactive' => base64_encode($request->input('password')),
			'status_id' => 1,
			'role_id' => 2
		);
		
		$response = User::create($data);
		
		if($response){

			if($gtext['is_mailchimp'] == 1){
				$name = $request->input('name');
				$email_address = $request->input('email');

				$HTTP_Status = self::MailChimpSubscriber($name, $email_address);
				if($HTTP_Status == 200){
					$SubscriberCount = Subscriber::where('email_address', '=', $email_address)->count();
					if($SubscriberCount == 0){
						$data = array(
							'email_address' => $email_address,
							'first_name' => $name,
							'last_name' => $name,
							'status' => 'subscribed'
						);
						Subscriber::create($data);
					}
				}
			}
			
			return redirect()->back()->withSuccess(__('Thanks! You have register successfully. Please login.'));
		}else{
			return redirect()->back()->withFail(__('Oops! You are failed registration. Please try again.'));
		}
    }

	//Save data for user
    protected function resetPassword(Request $request){
		$gtext = gtext();

		$email = $request->input('email');

		$secretkey = $gtext['secretkey'];
		$recaptcha = $gtext['is_recaptcha'];
		if($recaptcha == 1){
			$request->validate([
				'g-recaptcha-response' => 'required',
				'email' => 'required'
			]);
			
			$captcha = $request->input('g-recaptcha-response');

			$ip = $_SERVER['REMOTE_ADDR'];
			$url = 'https://www.google.com/recaptcha/api/siteverify?secret='.urlencode($secretkey).'&response='.urlencode($captcha).'&remoteip'.$ip;
			$response = file_get_contents($url);
			$responseKeys = json_decode($response, true);
			if($responseKeys["success"] == false) {
				return redirect()->back()->withFail(__('The recaptcha field is required'));
			}
		}else{
			$request->validate([
				'email' => 'required'
			]);
		}
		
		//You can add validation login here
		$user = DB::table('users')->where('email', '=', $email)->get();
		$userCount = $user->count();
		
		//Check if the user exists
		if($userCount < 1) {
			return redirect()->back()->withFail( __('We can not find a user with that email address'));
		}
		
		//Create Password Reset Token
		DB::table('password_resets')->insert([
			'email' => $email,
			'token' => Str::random(60),
			'created_at' => Carbon::now()
		]);
		
		$tokenData = DB::table('password_resets')->where('email', $email)->first();

		$sendResetEmail = self::sendResetEmail($email, $tokenData->token);
		
		if ($sendResetEmail == 1) {
			return redirect()->back()->withSuccess(__('We have emailed your password reset link!'));
		} else {
			return redirect()->back()->withFail(__('Oops! You are failed change password request. Please try again'));
		}
	}
	
	public function sendResetEmail($email, $token){
		$gtext = gtext();

		//Retrieve the user from the database
		$UserObj = User::where('email', $email)->first();
		$user = $UserObj->toArray();
		
		$base_url = url('/');
		
		//Generate the password reset link.
		$link = $base_url . '/password/reset/' . $token . '?email=' . urlencode($user['email']);
		
		if($gtext['ismail'] == 1){
			try {

				require 'vendor/autoload.php';
				$mail = new PHPMailer(true);
				$mail->CharSet = "UTF-8";

				if($gtext['mailer'] == 'smtp'){
					$mail->SMTPDebug = 0; //0 = off (for production use), 1 = client messages, 2 = client and server messages
					$mail->isSMTP();
					$mail->Host       = $gtext['smtp_host'];
					$mail->SMTPAuth   = true;
					$mail->Username   = $gtext['smtp_username'];
					$mail->Password   = $gtext['smtp_password'];
					$mail->SMTPSecure = $gtext['smtp_security'];
					$mail->Port       = $gtext['smtp_port'];
				}

				//Get mail
				$mail->setFrom($gtext['from_mail'], $gtext['from_name']);
				$mail->addAddress($user['email'], $user['name']);
				$mail->isHTML(true);
				$mail->CharSet = "utf-8";
				$mail->Subject = __('Forgot your password').' - '.$user['name'];
				$mail->Body = "<table style='background-color:#f0f0f0;color:#444;padding:40px 0px;line-height:24px;font-size:16px;' border='0' cellpadding='0' cellspacing='0' width='100%'>	
								<tr>
									<td>
										<table style='background-color:#fff;max-width:600px;margin:0 auto;padding:30px;' border='0' cellpadding='0' cellspacing='0' width='100%'>
											<tr><td style='font-size:30px;border-bottom:1px solid #ddd;padding-bottom:15px;font-weight:bold;text-align:center;'>".$gtext['site_name']."</td></tr>
											<tr><td style='font-size:20px;font-weight:bold;padding:30px 0px 5px 0px;'>Hi ".$user['name']."</td></tr>
											<tr><td>".__('Need to forgot your password? No problem! Just click the button below and you will be on way. If you did not make this request, please ignore this email.')."</td></tr>
											<tr><td style='padding-top:30px;padding-bottom:50px;'><a href='".$link."' target='_blank' style='background:".$gtext['theme_color'].";display:block;text-align:center;padding:10px;border-radius:3px;text-decoration:none;color:#fff;'>".__('Forgot your password?')."</a></td></tr>
											<tr><td style='padding-top:10px;border-top:1px solid #ddd;'>Thank you!</td></tr>
											<tr><td style='padding-top:5px;'><strong>".$gtext['site_name']."</strong></td></tr>
										</table>
									</td>
								</tr>
							</table>";
				$mail->send();
				
				return 1;
			} catch (Exception $e) {
				return 0;
			}
		}
	}
	
	public function resetPasswordUpdate(Request $request){
		$gtext = gtext();
		
		$email = $request->input('email');
		$password = $request->input('password');
		$token = $request->input('token');

		$secretkey = $gtext['secretkey'];
		$recaptcha = $gtext['is_recaptcha'];
		if($recaptcha == 1){
			//Validate input
			$validator = $request->validate([
				'email' => 'required|email|exists:users,email',
				'password' => 'required|confirmed',
				'token' => 'required',
				'g-recaptcha-response' => 'required'
			]);
			
			$captcha = $request->input('g-recaptcha-response');

			$ip = $_SERVER['REMOTE_ADDR'];
			$url = 'https://www.google.com/recaptcha/api/siteverify?secret='.urlencode($secretkey).'&response='.urlencode($captcha).'&remoteip'.$ip;
			$response = file_get_contents($url);
			$responseKeys = json_decode($response, true);
			if($responseKeys["success"] == false) {
				return redirect()->back()->withFail(__('The recaptcha field is required'));
			}
		}else{
			//Validate input
			$validator = $request->validate([
				'email' => 'required|email|exists:users,email',
				'password' => 'required|confirmed',
				'token' => 'required',
			]);
		}
	
		//Validate the token
		$tokenData = DB::table('password_resets')->where('token', $token)->get();
		$tokenCount = $tokenData->count();

		//Check the token is invalid
		if($tokenCount == 0) {
			return redirect()->back()->withFail(__('This password reset token is invalid'));
		}
		
		//Validate the Email
		$EmailCount = DB::table('password_resets')->where('email', $email)->count();

		//Check the token is invalid
		if($EmailCount == 0) {
			return redirect()->back()->withFail(__('This password reset email is invalid'));
		}
		
		$tokenEmail = $tokenData[0]->email;
		$userCount = User::where('email', $tokenEmail)->count();

		//Redirect the user back if the email is invalid
		if ($userCount == 0){
			return redirect()->back()->withFail(__('We can not find a user with that email address'));
		}else{
			
			$data = array(
				'password' => Hash::make($password),
				'bactive' => base64_encode($password)
			);
			
			$response = User::where('email', $tokenEmail)->update($data);

			if($response){
				//Delete the token
				DB::table('password_resets')->where('email', $tokenEmail)->delete();
				
				return redirect()->back()->withSuccess(__('Your password changed successfully'));
				
			}else{
				return redirect()->back()->withFail(__('Oops! You are failed change password. Please try again'));
			}
		}
	}
	
	//MailChimp Subscriber
    public function MailChimpSubscriber($name, $email){
		$gtext = gtext();

		$apiKey = $gtext['mailchimp_api_key'];
		$listId = $gtext['audience_id'];
		
        //Create mailchimp API url
        $memberId = md5(strtolower($email));
        $dataCenter = substr($apiKey, strpos($apiKey, '-')+1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId; 

        //Member info
        $data = array(
            'email_address' => $email,
            'status' => 'subscribed',
            'merge_fields'  => [
                'FNAME'     => $name,
                'LNAME'     => $name
            ]
        );

        $jsonString = json_encode($data);

        // send a HTTP POST request with curl
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonString);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
		
		return $httpCode;
    }	
}
