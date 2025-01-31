<?php

/**
 * Plugin Name:     Cool Kids Network
 * Plugin URI:      https://www.adeleyeayodeji.com/
 * Description:     A WordPress user management system with role-based access.
 * Author:          Adeleye Ayodeji
 * Author URI:      https://www.adeleyeayodeji.com/
 * Text Domain:     cool-kids-network-wp
 * Version:         0.1.0
 * License:         GPLv2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package  Cool_Kids_Network_WP
 * @category Plugin
 * @author Adeleye Ayodeji
 * @copyright 2025 Adeleye Ayodeji
 * @license GPLv2 or later
 * @link https://www.adeleyeayodeji.com/
 */

//check for security
if (!defined('ABSPATH')) {
	exit("You can't access file directly");
}

//define constants
define('COOL_KIDS_NETWORK_WP_VERSION', time());
define('COOL_KIDS_NETWORK_WP_FILE', __FILE__);
define('COOL_KIDS_NETWORK_WP_DIR', __DIR__);
define('COOL_KIDS_NETWORK_WP_URL', plugin_dir_url(__FILE__));
define('COOL_KIDS_NETWORK_WP_DIR_PATH', plugin_dir_path(__FILE__));
//method prefix
define('COOL_KIDS_NETWORK_WP_PREFIX', 'wp_ckn_');
//template path
define('COOL_KIDS_NETWORK_WP_TEMPLATE_PATH', COOL_KIDS_NETWORK_WP_DIR_PATH . 'app/templates/');
//assets url
define('COOL_KIDS_NETWORK_WP_ASSETS_URL', COOL_KIDS_NETWORK_WP_URL . '/assets/');

//load the plugin
require_once __DIR__ . '/vendor/autoload.php';

//init the plugin
Cool_Kids_Network_WP\Loader::instance();
