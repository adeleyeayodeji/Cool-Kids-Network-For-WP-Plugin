<?php

//check for security
if (!defined('ABSPATH')) {
	exit("You are not allowed to access this file.");
}

/**
 * Coolest Cooler Kid Role Based Template
 * Multiple details for all registered users
 * @package Cool Kids Network WP
 * @since 1.0.0
 */

//get user role
$user_role = wp_get_current_user()->roles;
//roles to check
$roles_to_check = array('cooler_kid', 'coolest_kid');

// Check if user has Cooler Kid or Coolest Kid role
$show_all_users = array_intersect($roles_to_check, $user_role);

?>
<div class="<?php echo COOL_KIDS_NETWORK_WP_PREFIX; ?>character-details">

	<?php if ($show_all_users) :
	?>
		<div class="all-users-list">
			<h3><?php _e('All Users', 'cool-kids-network-wp'); ?></h3>
			<?php
			$all_users = get_users();
			foreach ($all_users as $user) {
				$user_first_name = get_user_meta($user->ID, 'first_name', true);
				$user_last_name = get_user_meta($user->ID, 'last_name', true);
				$user_country = get_user_meta($user->ID, 'country', true);
				//get current list user role
				$user_list_role = "";
				//check if current logged in user role is coolest_kid
				if (in_array('coolest_kid', $user_role)) {
					//include coolest_cooler_kid template
					$user_list_role = implode(', ', $user->roles);
				}
			?>
				<div class="user-item">
					<p>
						<?php _e('Name:', 'cool-kids-network-wp'); ?>
						<?php echo esc_html($user_first_name ?: __('Not set', 'cool-kids-network-wp')); ?>
						<?php echo esc_html($user_last_name ?: ''); ?>
					</p>
					<p>
						<?php _e('Country:', 'cool-kids-network-wp'); ?>
						<?php echo esc_html($user_country ?: __('Not set', 'cool-kids-network-wp')); ?>
					</p>
					<?php
					//check if user list role is not empty
					if (!empty($user_list_role)) {
						echo '<p>' . __('Role:', 'cool-kids-network-wp') . ' ' . esc_html($user_list_role) . '</p>';
					}
					?>
				</div>
			<?php } ?>
		</div>
	<?php else :
	?>
		<div class="no-users-list">
			<p><?php _e('You are not authorized to view this page.', 'cool-kids-network-wp'); ?></p>
		</div>
	<?php endif;
	?>
</div>