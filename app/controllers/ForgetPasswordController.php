<?php 

	class ForgetPasswordController extends Controller
	{
		public function __construct() {
			parent::__construct();

			$this->userModel = model('UserModel');
		}

		public function index() {
			if(isSubmitted()) {
				$post = request()->posts();

				if(!is_email($post['email'])) {
					Flash::set("Invalid Email");
					return request()->return();
				}

				$user = $this->userModel->single([
					'email' => trim($post['email'])
				]);

				if($user) {
					$payload = $this->generateResetPassword($user->id, $user->email);
				}
			}
			return $this->view('forget_password/index', $this->data);
		}

		/*
		*required parameters
		*expiry and token
		*/
		public function resetPassword() {
			$req = request()->inputs();

			if(isSubmitted()) {
				$post = request()->posts();
				if(!isEqual($post['new_password'], $post['confirm_password'])) {
					Flash::set("Password not matched", 'danger');
					return request()->return();
				}
				$isOkay = $this->userModel->changePassword(trim($post['new_password']), $post['userId']);

				if($isOkay) {
					Flash::set("Change password success, login to your account now.");
					return redirect(_route('auth:login'));
				}else{
					Flash::set("Change password failed", "danger");
					return request()->return();
				}
			}


			if(!isset($req['expiry'], $req['token'])) {
				echo die("Invalid Reset Password Request");
			}

			$expiry = unseal($req['expiry']);
			//returns an array
			$token = unseal($req['token']);
			$timeDifference = ceil(abs(timeInMinutesToHours(timeDifference($expiry, now()))));

			if($timeDifference >= 12) {
				echo die("Reset Password Expired");
			}

			$user = $this->userModel->get($token['userId']);

			if(!isEqual($user->email, $token['emailUsed'])) {
				Flash::set("There something wrong with this request password, reset password failed");
				return request()->return();
			}
			$this->data['userId'] = $user->id;

			return $this->view('forget_password/reset_password', $this->data);
		}

		private function generateResetPassword($userID, $emailUsed) {
			$date = now();

			/**
			 * use userId and email and seal it
			 * use current date for expiry 24 hours only
			 * */

			if(isSubmitted()) {
				$post = request()->posts();

				$preparePayload = [
					'expiry' => seal($date),
					'token' => seal([
						'userId' => $userID,
						'emailUsed'  => $emailUsed
					]) 
				];

				$href = URL.DS._route('forget-pw:resetPassword');

				$link = wLinkDefault($href, null, [
					'expiry' => $preparePayload['expiry'],
					'token' => $preparePayload['token'],
				], 'Reset password link');

				$html = "<div> You have requested to reset your password </div>";
				$html .= "<div> Please click the link below to continue, if this is not your please ignore the change password request";
				$html .= "<div>{$link}</div>";
				_mail($emailUsed,"Reset password", $html);

				echo die("Reset password link has been sent to your email, you can close this page now.");

				return $preparePayload;
			}
		}
	}