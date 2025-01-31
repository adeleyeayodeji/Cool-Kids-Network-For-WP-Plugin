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

//get user role
$user_role = wp_get_current_user()->roles;

//roles to check
$advanced_roles_to_check = array('cooler_kid', 'coolest_kid');

//check if user has cool_kid role
if (in_array('cool_kid', $user_role)) {
	//include cool_kid template
	echo render_wp_ckn_template('character-details/cool-kid');
}

//check if user has coolest_cooler_kid role
if (array_intersect($advanced_roles_to_check, $user_role)) {
	//include coolest_cooler_kid template
	echo render_wp_ckn_template('character-details/coolest-cooler-kid');
}
?>

<p style="margin-top: 20px;">
	<a href="<?php echo wp_logout_url(site_url('/sign-in')); ?>" class="<?php echo COOL_KIDS_NETWORK_WP_PREFIX; ?>button">
		<?php _e('Logout', 'cool-kids-network-wp'); ?>
	</a>
</p>