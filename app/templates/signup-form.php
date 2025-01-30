<?php
//check for security
if (!defined('ABSPATH')) {
	exit("You are not allowed to access this file.");
}


//check if user is logged in
if (is_user_logged_in()) {
?>
	<div class="<?php echo COOL_KIDS_NETWORK_WP_PREFIX; ?>notice-message">
		<?php _e('You are already logged in.', 'cool-kids-network-wp'); ?>
	</div>
<?php
	return;
}

?>

<div class="<?php echo COOL_KIDS_NETWORK_WP_PREFIX; ?>container">
	<form class="<?php echo COOL_KIDS_NETWORK_WP_PREFIX; ?>signup-form">
		<label>
			<?php _e('Email:', 'cool-kids-network-wp'); ?>
		</label>
		<input type="email" name="email" required placeholder="<?php _e('Enter your email', 'cool-kids-network-wp'); ?>">

		<button type="submit">
			<?php _e('Sign Up', 'cool-kids-network-wp'); ?>
		</button>

		<div class="<?php echo COOL_KIDS_NETWORK_WP_PREFIX; ?>notice-message" style="display: none;"></div>
	</form>
</div>