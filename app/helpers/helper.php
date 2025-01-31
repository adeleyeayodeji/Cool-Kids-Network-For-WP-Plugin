<?php

/**
 * File Description:
 * Helpers
 *
 * @since   1.0.0
 *
 * @author  Biggidroid (https://www.biggidroid.com)
 * @package download-installed-extension
 *
 * @copyright (c) 2024, Biggidroid (https://www.biggidroid.com)
 */

// If this file is called directly, abort.
defined('WPINC') || die;

/**
 * Helpers
 * write all the helper functions here
 *
 * @since 1.0.0
 */


/**
 * Render template
 *
 * @param string $template Template name
 * @return string
 */
function render_wp_ckn_template($template, $args = [])
{
	try {
		//extract args
		extract($args);

		ob_start();

		//check if file exists
		if (file_exists(COOL_KIDS_NETWORK_WP_TEMPLATE_PATH . $template . '.php')) {
			include COOL_KIDS_NETWORK_WP_TEMPLATE_PATH . $template . '.php';
		} else {
			throw new Exception('Template not found.');
		}

		//return the output
		return ob_get_clean();
	} catch (Exception $e) {
		//log error
		error_log("Error: " . $e->getMessage() . " on line " . $e->getLine() . " in file " . $e->getFile());
		//return error
		return '<div class="error">' . __('An error occurred while displaying the template.', 'cool-kids-network-wp') . '</div>';
	}
}
