<?php

//check for security
if (!defined('ABSPATH')) {
	exit("You are not allowed to access this file.");
}

//check if user is logged in
if (!is_user_logged_in()) {
?>
	<div class="<?php echo COOL_KIDS_NETWORK_WP_PREFIX; ?>notice-message">
		<?php _e('You must be logged in to view this page.', 'cool-kids-network-wp'); ?>
	</div>
<?php
	return;
}

//get user id
$user_id = get_current_user_id();

//get user meta
$first_name = get_user_meta($user_id, 'first_name', true);
$last_name = get_user_meta($user_id, 'last_name', true);
$country = get_user_meta($user_id, 'country', true);

?>
<div class="<?php echo COOL_KIDS_NETWORK_WP_PREFIX; ?>character-details">
	<p>
		<?php _e('First Name:', 'cool-kids-network-wp'); ?> <?php echo $first_name ?: __('Not set', 'cool-kids-network-wp'); ?>
	</p>
	<p>
		<?php _e('Last Name:', 'cool-kids-network-wp'); ?> <?php echo $last_name ?: __('Not set', 'cool-kids-network-wp'); ?>
	</p>
	<p>
		<?php _e('Country:', 'cool-kids-network-wp'); ?> <?php echo $country ?: __('Not set', 'cool-kids-network-wp'); ?>
	</p>
	<p>
		<?php _e('Email:', 'cool-kids-network-wp'); ?> <?php echo wp_get_current_user()->user_email ?: __('Not set', 'cool-kids-network-wp'); ?>
	</p>
</div>