<?php

	/**
	 * This template is for the settings page of the Flaming Password Reset plugin.
	 *
	 * @var FlamingPasswordResetAdminController $this
	 */

?>

<div class="wrap">
	<h2>Flaming Password Reset <span><?php echo FLAMING_PASSWORDRESET_VER; ?></span></h2>

	<form id="password_reset_form" class="flaming_form" method="post" action="?page=FlamingPasswordReset">

		<div class="flaming_button_list">

			<div class="flaming_notices">
				<?php if (isset($this->Success) && is_string($this->Success)): ?>
					<p class="flaming_success"><?php echo $this->Success; ?></p>
				<?php endif; ?>

				<?php if (isset($this->Error) && is_string($this->Error)): ?>
					<p class="flaming_error"><?php echo $this->Error; ?></p>
				<?php endif; ?>

				<p class="flaming_warning">You've made changes that require you to save the settings.</p>
			</div>

			<div class="flaming_buttons">
				<input class="button" onclick="return DisplayResetWarning()" type="submit" name="action" value="Reset Settings" />
				<input class="button button-primary" type="submit" name="action" value="Save Settings" />
			</div>
			<br style="clear: both" />
		</div>

		<input type="hidden" name="_flaming_passwordreset_form" value="1" />

		<table class="table table-bordered table-options">
			<tr>
				<th>
					<label for="EnableAdminResets">
						Enable Admin Resets
						<span>Check this to allow administrators the ability to reset user passwords.</span>
					</label>
				</th>
				<td colspan="2"><input type="checkbox" id="EnableAdminResets" name="EnableAdminResets" value="1" <?php if ($this->Settings->EnableAdminResets == 1): ?> checked <?php endif; ?> /></td>
			</tr>
			<tr>
				<th>
					<label for="SendFromAddress">
						Send From Address
						<span>The email address that will send the reset password email.</span>
					</label>
				</th>
				<td colspan="2"><input type="text" id="SendFromAddress" name="SendFromAddress" value="<?php echo $this->Settings->SendFromAddress; ?>" /></td>
			</tr>
			<tr>
				<th>
					<label for="ResetEmailSubject">
						Reset Email Subject
						<span>This is the subject of the email that will be sent when the user's password is reset by the admin.</span>
					</label>
				</th>
				<td colspan="2"><input type="text" id="ResetEmailSubject" name="ResetEmailSubject" value="<?php echo $this->Settings->ResetEmailSubject; ?>" /></td>
			</tr>
			<tr>
				<th>
					<label for="ResetEmailTemplate">
						Reset Email Template
						<span>This is the email content that will be sent to the user when their password is reset by the admin.</span>
						<span>Click on a shortcode to the right in order to add it to the message. These shortcodes represent the users account information. The editor must be in 'Visual' mode for the placeholders to work.</span>
					</label>
				</th>
				<td>
					<?php
						wp_editor($this->Settings->ResetEmailTemplate, 'ResetEmailTemplate', array(
							'wpautop' => false,
							'media_buttons' => false,
							'teeny' => true
						));
					?>
				</td>
				<td class="padding-left-20">
					<h4>Shortcodes</h4>
					<ul>
						<li><a href="javascript:void(0)" onclick="AddPlaceholder('[Username]', 'ResetEmailTemplate')">Username</a></li>
						<li><a href="javascript:void(0)" onclick="AddPlaceholder('[Email]', 'ResetEmailTemplate')">Email</a></li>
						<li><a href="javascript:void(0)" onclick="AddPlaceholder('[Password]', 'ResetEmailTemplate')">Password</a></li>
					</ul>
				</td>
			</tr>
		</table>

		<div class="flaming_button_list">
			<div class="flaming_buttons">
				<input class="button" type="submit" name="action" value="Reset Settings" />
				<input class="button button-primary" type="submit" name="action" value="Save Settings" />
			</div>
			<br style="clear: both" />
		</div>
	</form>
</div>