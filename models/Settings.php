<?php

	/**
	 * Class FlamingPasswordResetSettings
	 *
	 * @author Project Caruso
	 * @version 1.0.0
	 * @package FlamingPasswordReset
	 *
	 * This class is responsible for loading, accessing and saving the settings/options to the database.
	 *
	 * @Note
	 * Please use FlamingPasswordResetSettings::GetInstance() when accessing this class.
	 * This will help to ensure that the settings are only loading once.
	 */
	class FlamingPasswordResetSettings
	{
		/**
		 * Flag that tells whether administrators can reset user passwords
		 *
		 * @var boolean
		 */
		public $EnableAdminResets = 1;

		/**
		 * The email address that the reset email will be sent from.
		 *
		 * @var string
		 */
		public $SendFromAddress = null;

		/**
		 * The subject line of the reset email.
		 *
		 * @var string
		 */
		public $ResetEmailSubject = 'Password Reset';

		/**
		 * The text that will be sent to the end-user when the admin resets their password.
		 *
		 * @var string
		 */
		public $ResetEmailTemplate = '';

		/**
		 * A singleton instance of this class.
		 *
		 * @var FlamingPasswordResetSettings
		 */
		protected static $_Instance;

		/**
		 * Returns a singleton instance of the FlamingPasswordResetSettings.
		 *
		 * @Note
		 * This is the preferred method for getting the FlamingPasswordResetSettings class.
		 * Using this method will help to make sure that the settings are only loaded once.
		 *
		 * @return FlamingPasswordResetSettings
		 */
		public static function &GetInstance()
		{
			if (self::$_Instance == null || get_class(self::$_Instance) != 'FlamingPasswordResetSettings') {
				self::$_Instance = new FlamingPasswordResetSettings();
			}

			return self::$_Instance;
		}

		/**
		 * Loads all of the options from the database and if the options do not exist,
		 * then it creates the default values.
		 */
		public function __construct()
		{
			$Settings = get_option('FlamingPasswordReset', $this->GetAllOptions());
			if (is_array($Settings)) {
				foreach ($Settings as $Name => $Value) {
					$this->$Name = $Value;
				}
			}
		}

		/**
		 * Saves all of the options and their current values to the database.
		 *
		 * @return boolean
		 */
		public function Save()
		{
			/**
			 * When the email template hasn't been set yet, this will buffer the default template
			 * and then put the contents into the settings.
			 */
			if ($this->ResetEmailTemplate === null) {
				ob_start();
				include(FLAMING_PASSWORDRESET_VIEWS.'EmailTemplate.php');
				$this->ResetEmailTemplate = ob_get_contents();
				ob_end_clean();

			}

			if ($this->SendFromAddress === null) {
				$this->SendFromAddress = get_option('admin_email');
			}

			if ($this->GetAllOptions() === get_option('FlamingPasswordReset')) {
				return true;
			}

			return update_option('FlamingPasswordReset', $this->GetAllOptions());
		}

		/**
		 * Deletes all of the options from the database.
		 * This will also reset all of the options to their default values.
		 *
		 * @return boolean
		 */
		public function Delete()
		{
			return delete_option('FlamingPasswordReset');
		}

		/**
		 * Returns a list of all the options with their current values.
		 * The index of the array is the options name and the value of the array is the options current value.
		 *
		 * @return array
		 */
		protected function GetAllOptions()
		{
			return array(
				'EnableAdminResets'  => $this->EnableAdminResets,
				'ResetEmailTemplate' => $this->ResetEmailTemplate,
				'ResetEmailSubject'  => $this->ResetEmailSubject,
				'SendFromAddress'    => $this->SendFromAddress
			);
		}

		/**
		 * Set the settings data from the $_POST global.
		 *
		 * We use this method because when there is an empty checkbox,
		 * it won't show up in the posted values.
		 *
		 * @param array $Options
		 */
		public function SetPostValues(array $Options)
		{
			foreach ($Options as $Name => $Value) {
				$this->$Name = $Value;
			}

			$this->EnableAdminResets = isset($Options['EnableAdminResets']) ? 1 : 0;
		}

		/**
		 * Reset all of the settings to their default values.
		 */
		public function ResetToDefaultValues()
		{
			$this->EnableAdminResets  = 1;
			$this->SendFromAddress    = get_option('admin_email');
			$this->ResetEmailSubject  = 'Password Reset';
			$this->ResetEmailTemplate = null;
		}
	}