<?php

	/**
	 * This template is for resetting the users password.
	 *
	 * @var FlamingPasswordResetAdminController $this
	 */

?>

<div class="wrap">
	<h2>Flaming Password Reset <span><?php echo FLAMING_PASSWORDRESET_VER; ?></span></h2>

	<form id="password_reset_form" class="flaming_form" method="post" action="?page=<?php echo FLAMING_PASSWORD_RESET_PAGE; ?>&id=<?php echo $this->User->ID; ?>">

		<div class="flaming_button_list">

			<div class="flaming_notices">
				<?php if (isset($this->Error) && is_string($this->Error)): ?>
					<p class="flaming_error"><?php echo $this->Error; ?></p>
				<?php endif; ?>
			</div>

			<div class="flaming_buttons">
				<?php if (isset($this->Success) && is_string($this->Success)): ?>
					<a href="./users.php" class="button button-primary">Back</a>
				<?php else: ?>
					<input class="button" type="submit" name="action" value="Generate New Password" />
					<input class="button button-primary" type="submit" name="action" value="Reset Password" />
				<?php endif; ?>
			</div>
			<br style="clear: both" />
		</div>

		<input type="hidden" name="_flaming_passwordreset_form" value="1" />

		<table class="table table-bordered table-confirm">
			<tr>
				<th>
					<?php echo get_avatar($this->User->ID, '128'); ?>
				</th>
				<td rowspan="3">
					<?php if (isset($this->Success) && is_string($this->Success)): ?>

						<div class="flaming_notices">
							<p class="flaming_success"><?php echo $this->Success; ?></p>
						</div>

					<?php else: ?>

						<p><strong>Are you sure that you want to reset this users password?</strong></p>
						<p>New Password: <input type="text" name="NewPassword" value="<?php echo $this->NewPassword; ?>" /></p>

					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th>
					<?php echo $this->User->user_nicename; ?><br />
					<a href="mailto:<?php echo $this->User->user_email; ?>"><?php echo $this->User->user_email; ?></a>
				</th>
			</tr>
		</table>

		<div class="flaming_button_list">
			<div class="flaming_buttons">
				<?php if (isset($this->Success) && is_string($this->Success)): ?>
					<a href="./users.php" class="button button-primary">Back</a>
				<?php else: ?>
					<input class="button" type="submit" name="action" value="Generate New Password" />
					<input class="button button-primary" type="submit" name="action" value="Reset Password" />
				<?php endif; ?>
			</div>
			<br style="clear: both" />
		</div>
	</form>
</div>