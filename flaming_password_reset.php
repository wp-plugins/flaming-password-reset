<?php

	/**
	 * Plugin Name: Flaming Password Reset
	 * Plugin URI: http://websitesby.projectcaruso.com
	 * Description: Adds the ability for admins to initiate the password reset process for their users.
	 * Version: 1.0.1
	 * Author: Project Caruso
	 * Author: Chris Butcher
	 * Author URI: http://websitesby.projectcaruso.com
	 */

	// The current version of this plugin.
	define('FLAMING_PASSWORDRESET_VER', '1.0.1');

	// Location of the plugin on the filesystem.
	define('FLAMING_PASSWORDRESET_DIR', rtrim(dirname(__FILE__), '\\/').'/');

	// URL of where the plugins folder is located.
	define('FLAMING_PASSWORDRESET_URL', rtrim(plugins_url('', __FILE__), '\\/').'/');

	// Location of the HTML views on the file system.
	define('FLAMING_PASSWORDRESET_VIEWS', FLAMING_PASSWORDRESET_DIR.'views/');

	// The page where the users password can be reset
	define('FLAMING_PASSWORD_RESET_PAGE', 'flaming_password_reset_user');

	/**
	 * Class FlamingPasswordReset
	 *
	 * @author Project Caruso
	 * @version 1.0.1
	 * @package FlamingPasswordReset
	 */
	class FlamingPasswordReset
	{
		/**
		 * Load all of the required files and hook our plugin into the Wordpress CMS.
		 */
		public function Initialize()
		{
			if (is_admin()) {
				require_once(FLAMING_PASSWORDRESET_DIR.'models/Settings.php');
				require_once(FLAMING_PASSWORDRESET_DIR.'controllers/AdminController.php');

				add_action('admin_menu', array($this, 'CreateAdminMenus'));

				$Settings = FlamingPasswordResetSettings::GetInstance();
				if ($Settings->EnableAdminResets) {

					$User = wp_get_current_user();
					if ($User->has_cap('edit_users')) {
						add_filter('user_row_actions', array($this, 'CreateUserMenus'), 10, 2);
					}
				}

				// This is so we can use PHPs header() function
				ob_start();
				add_action('wp_footer', array($this, 'OutputBuffer'));
			}
		}

		/**
		 * Configures the plugin for it's first time being used.
		 */
		public function Install()
		{
			require_once(FLAMING_PASSWORDRESET_DIR.'models/Settings.php');

			$Settings = FlamingPasswordResetSettings::GetInstance();
			if (!$Settings->Save()) {
				die("Unable to save the password reset settings.");
			}
		}

		/**
		 * Cleans up the system when the plugin is uninstalled.
		 */
		public function Uninstall()
		{
			require_once(FLAMING_PASSWORDRESET_DIR.'models/Settings.php');

			$Settings = FlamingPasswordResetSettings::GetInstance();
			if (!$Settings->Delete()) {
				die("Unable to delete the password reset settings.");
			}
		}

		/**
		 * Adds extra pages to the admin interface.
		 */
		public function CreateAdminMenus()
		{
			$PasswordResetController = new FlamingPasswordResetAdminController();

			add_options_page(
				'Flaming Password Reset',
				'Flaming Password Reset',
				'manage_options',
				'FlamingPasswordReset',
				array($PasswordResetController, 'ShowSettings')
			);

			$Settings  = FlamingPasswordResetSettings::GetInstance();
			if ($Settings->EnableAdminResets) {
				add_submenu_page(
					'flaming_password_reset',
					'Reset User Password',
					'Reset User Password',
					'manage_options',
					FLAMING_PASSWORD_RESET_PAGE,
					array($PasswordResetController, 'ShowResetPage')
				);
			}
		}

		/**
		 * Adds extra user action links to the Users list page.
		 * These links will show up underneath the username on the User list page.
		 *
		 * @param string[]  $Actions
		 * @param WP_User   $User
		 *
		 * @return mixed
		 */
		public function CreateUserMenus($Actions, $User)
		{
			$Actions['Reset Password'] = '<a href="admin.php?page='.FLAMING_PASSWORD_RESET_PAGE.'&id='.$User->ID.'">Reset Password</a>';
			return $Actions;
		}

		/**
		 * Outputs the contents of the buffer.
		 * This allows us to use PHP's header() function for redirects.
		 */
		public function OutputBuffer()
		{
			ob_end_flush();
		}
	}

	/**
	 * We are adding our actions/hooks outside of the FlamingPasswordReset class in-case someone tries
	 * to instantiate it twice, which would duplicate the actions/hooks and execute everything twice.
	 */
	$FlamingPasswordReset = new FlamingPasswordReset();
	register_activation_hook(__FILE__, array($FlamingPasswordReset, 'Install'));
	register_uninstall_hook(__FILE__, array($FlamingPasswordReset, 'Uninstall'));
	add_action('init', array($FlamingPasswordReset, 'Initialize'));

?>