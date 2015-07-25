<?php

	class FlamingPasswordResetAdminController
	{
		/**
		 * The settings for the Flaming Password Reset plugin.
		 *
		 * @var FlamingPasswordResetSettings
		 */
		protected $Settings;

		/**
		 * The user which is having their password reset.
		 *
		 * @var WP_User
		 */
		protected $User;

		/**
		 * The new password that will be assigned to the user.
		 *
		 * @var string
		 */
		protected $NewPassword;

		/**
		 * Success message that will be displayed on the page.
		 *
		 * @var string
		 */
		protected $Success = null;

		/**
		 * Error message that will be displayed on the page.
		 *
		 * @var string
		 */
		protected $Error = null;

		/**
		 * Instantiate a new instance of the Admin Controller.
		 * This will load the settings for the Flaming Password Reset plugin.
		 */
		public function __construct()
		{
			if (class_exists('FlamingPasswordResetSettings')) {
				$this->Settings = FlamingPasswordResetSettings::GetInstance();
			}
		}

		/**
		 * Shows the admin settings page for the Flaming Password Reset plugin.
		 */
		public function ShowSettings()
		{
			if (isset($_POST['_flaming_passwordreset_form'])) {

				$Settings = FlamingPasswordResetSettings::GetInstance();
				$Settings->SetPostValues($_POST);

				if ($_POST['action'] == 'Reset Settings') {
					$Settings->ResetToDefaultValues();
				}

				if ($Settings->Save()) {
					$this->Success = 'Successfully updated the settings.';
				} else {
					$this->Error = 'There was an error while updating the settings.';
				}
			}

			wp_enqueue_script('flaming_passwordreset_admin_js', FLAMING_PASSWORDRESET_URL.'includes/javascript/admin.js');
			wp_enqueue_style('flaming_passwordreset_admin_css', FLAMING_PASSWORDRESET_URL.'includes/stylesheets/admin.css');

			include_once(FLAMING_PASSWORDRESET_VIEWS.'Settings.php');
		}

		/**
		 * Shows the admin settings page for the Flaming Password Reset plugin.
		 */
		public function ShowResetPage()
		{
			if (!isset($_REQUEST['id'])) {
				header('Location: ./users.php');
				exit;
			}

			$this->User = new WP_User($_REQUEST['id']);
			if ($this->User->ID < 1) {
				header('Location: ./users.php');
				exit;
			}

			$this->NewPassword = $this->GenerateNewPassword();

			if (isset($_POST['_flaming_passwordreset_form']) && $_POST['action'] == 'Reset Password') {

				$this->NewPassword     = $_POST['NewPassword'];
				$UserData              = $this->User->to_array();
				$UserData['user_pass'] = $this->NewPassword;

				$User = wp_update_user($UserData);
				if (!is_wp_error($User)) {

					$this->User = new WP_User($_REQUEST['id']);

					if ($this->SendResetEmail($this->User, $this->NewPassword)) {
						$this->Success = "Successfully changed the users password and sent them an email.";
					} else {
						$this->Error = "Password was changed, but the email was not sent.";
					}

				} else {
					$this->Error = "Unable to update the users password.";
				}
			}

			wp_enqueue_script('flaming_passwordreset_admin_js', FLAMING_PASSWORDRESET_URL.'includes/javascript/admin.js');
			wp_enqueue_style('flaming_passwordreset_admin_css', FLAMING_PASSWORDRESET_URL.'includes/stylesheets/admin.css');

			include_once(FLAMING_PASSWORDRESET_VIEWS.'ResetUser.php');
		}

		/**
		 * Generates a new random password for the user.
		 *
		 * @param integer $Length
		 *
		 * @return string
		 */
		protected function GenerateNewPassword($Length = 16)
		{
			$Chars     = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*_';
			$CharCount = strlen($Chars) - 1;

			$NewPass = '';
			for ($i = 0; $i < $Length; $i++) {
				$NewPass .= $Chars[rand(0, $CharCount)];
			}

			return $NewPass;
		}

		/**
		 * Sends an email to the user, notifying them that their password changed.
		 *
		 * @param WP_User $User
		 * @param string  $NewPassword
		 *
		 * @return bool
		 */
		public function SendResetEmail(WP_User $User, $NewPassword)
		{
			$EmailContent  = $this->Settings->ResetEmailTemplate;

			$EmailContent = str_replace('[Username]', $User->user_nicename, $EmailContent);
			$EmailContent = str_replace('[Password]', $NewPassword,         $EmailContent);
			$EmailContent = str_replace('[Email]',    $User->user_email,    $EmailContent);

			$Headers[] = 'Content-Type: text/html; charset=UTF-8';
			$Headers[] = "From: {$this->Settings->SendFromAddress}";

			return wp_mail($User->user_email, $this->Settings->ResetEmailSubject, $EmailContent, $Headers);
		}
	}

?>