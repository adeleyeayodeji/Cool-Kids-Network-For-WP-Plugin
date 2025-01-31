<?php

//check for security
if (!defined('ABSPATH')) {
	exit("You are not allowed to access this file.");
}

/**
 * Cool Kid Role Based Template
 * A single detail for current logged in user
 * @package Cool Kids Network WP
 * @since 1.0.0
 */

//get user id
$user_id = get_current_user_id();

//get user meta
$first_name = get_user_meta($user_id, 'first_name', true);
$last_name = get_user_meta($user_id, 'last_name', true);
$country = get_user_meta($user_id, 'country', true);
//get user role
$user_role = wp_get_current_user()->roles[0];
//remove underscore from user role and capitalize first letter
$user_role = ucfirst(str_replace('_', ' ', $user_role));
?>
<div class="<?php echo COOL_KIDS_NETWORK_WP_PREFIX; ?>character-details">
	<p>
		<?php _e('First Name:', 'cool-kids-network-wp'); ?> <?php echo esc_html($first_name) ?: __('Not set', 'cool-kids-network-wp'); ?>
	</p>
	<p>
		<?php _e('Last Name:', 'cool-kids-network-wp'); ?> <?php echo esc_html($last_name) ?: __('Not set', 'cool-kids-network-wp'); ?>
	</p>
	<p>
		<?php _e('Country:', 'cool-kids-network-wp'); ?> <?php echo esc_html($country) ?: __('Not set', 'cool-kids-network-wp'); ?>
	</p>
	<p>
		<?php _e('Email:', 'cool-kids-network-wp'); ?> <?php echo wp_get_current_user()->user_email ?: __('Not set', 'cool-kids-network-wp'); ?>
	</p>
	<p>
		<?php _e('Role:', 'cool-kids-network-wp'); ?> <?php echo esc_html($user_role) ?: __('Not set', 'cool-kids-network-wp'); ?>
	</p>
</div>