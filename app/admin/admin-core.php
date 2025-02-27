<?php

/**
 * Admin Core
 *
 * @package Cool_Kids_Network_WP
 */

namespace Cool_Kids_Network_WP\Admin;

use Cool_Kids_Network_WP\Base;
use Exception;
use WpOrg\Requests\Requests;

//check for security
if (!defined('ABSPATH')) {
	exit("You are not allowed to access this file.");
}

/**
 * Class Admin_Core
 *
 * @package Cool_Kids_Network_WP\Admin
 */
class Admin_Core extends Base
{
	/**
	 * API Version
	 *
	 * @var string
	 */
	private $api_version = 'v1';


	/**
	 * Init
	 *
	 * @return void
	 */
	public function init()
	{
		//add signup form
		add_action('wp_ajax_nopriv_' . COOL_KIDS_NETWORK_WP_PREFIX . 'signup', [$this, 'handle_signup']);
		//add login form
		add_action('wp_ajax_nopriv_' . COOL_KIDS_NETWORK_WP_PREFIX . 'login', [$this, 'handle_login']);
		//enqueue scripts
		add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
		//sign up shortcode [wp_ckn_signup]
		add_shortcode(COOL_KIDS_NETWORK_WP_PREFIX . 'signup', [$this, 'display_signup_form']);
		//login shortcode [wp_ckn_login]
		add_shortcode(COOL_KIDS_NETWORK_WP_PREFIX . 'login', [$this, 'display_login_form']);
		//character details shortcode [wp_ckn_character_details]
		add_shortcode(COOL_KIDS_NETWORK_WP_PREFIX . 'character_details', [$this, 'display_character_details']);
		// Register REST API routes
		add_action('rest_api_init', [$this, 'register_rest_routes']);
	}

	/**
	 * Register REST API routes
	 *
	 * @return void
	 */
	public function register_rest_routes()
	{
		/**
		 * Get Auth Nonce
		 *
		 * @param WP_REST_Request $request
		 * @return WP_REST_Response
		 */
		register_rest_route(COOL_KIDS_NETWORK_WP_PREFIX . $this->api_version, '/auth/nonce', [
			'methods' => 'GET',
			'callback' => [$this, 'get_auth_nonce'],
			'permission_callback' => '__return_true',
		]);

		/**
		 * Handle Role Change
		 *
		 * @param WP_REST_Request $request
		 * @return WP_REST_Response
		 */
		register_rest_route(COOL_KIDS_NETWORK_WP_PREFIX . $this->api_version, '/user/role', [
			'methods' => 'POST',
			'callback' => [$this, 'handle_role_change'],
			'args' => [
				'role' => [
					'required' => true,
					'type' => 'string',
					'description' => 'The role to assign to the user.',
				],
				'email' => [
					'required' => false,
					'type' => 'string',
					'description' => 'The email address of the user.',
				],
				'first_name' => [
					'required' => false,
					'type' => 'string',
					'description' => 'The first name of the user.',
				],
				'last_name' => [
					'required' => false,
					'type' => 'string',
					'description' => 'The last name of the user.',
				],
			],
			'permission_callback' => [$this, 'verify_api_auth'],
		]);
	}

	/**
	 * Get Auth Nonce
	 *
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response
	 */
	public function get_auth_nonce($request)
	{
		return new \WP_REST_Response(['nonce' => wp_create_nonce(COOL_KIDS_NETWORK_WP_PREFIX . 'auth_api')], 200);
	}

	/**
	 * Verify API authentication
	 *
	 * @param WP_REST_Request $request
	 * @return bool|WP_Error
	 */
	public function verify_api_auth($request)
	{
		//get auth header
		$auth_header = $request->get_header('Authorization');

		//check if auth header is empty
		if (empty($auth_header)) {
			return new \WP_Error(
				'rest_forbidden',
				'Authorization header is required.',
				['status' => 401]
			);
		}

		//get token
		$token = str_replace('Bearer ', '', $auth_header);

		//verify nonce
		if (!wp_verify_nonce($token, COOL_KIDS_NETWORK_WP_PREFIX . 'auth_api')) {
			return new \WP_Error(
				'rest_forbidden',
				'Invalid API key.',
				['status' => 401]
			);
		}

		//return true
		return true;
	}

	/**
	 * Handle role change request from 3rd party integration
	 *
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response
	 */
	public function handle_role_change($request)
	{
		try {
			// Validate role
			$valid_roles = ['cool_kid', 'cooler_kid', 'coolest_kid'];
			$role = sanitize_text_field($request->get_param('role'));

			if (!in_array($role, $valid_roles)) {
				return new \WP_REST_Response([
					'message' => 'Invalid role specified.',
				], 400);
			}

			// Find user by email or name
			$user = null;
			$email = $request->get_param('email');
			$first_name = $request->get_param('first_name');
			$last_name = $request->get_param('last_name');

			if (!empty($email)) {
				//get user by email
				$user = get_user_by('email', sanitize_email($email));
			} elseif (!empty($first_name) && !empty($last_name)) {
				//get first name and last name
				$first_name = sanitize_text_field($first_name);
				$last_name = sanitize_text_field($last_name);

				//get users by first name and last name
				$users = get_users([
					'meta_query' => [
						'relation' => 'AND',
						[
							'key' => 'first_name',
							'value' => $first_name,
							'compare' => '='
						],
						[
							'key' => 'last_name',
							'value' => $last_name,
							'compare' => '='
						]
					]
				]);

				//check if users are found
				if (!empty($users)) {
					$user = $users[0];
				}
			}

			//check if user is not set
			if (!isset($user) || empty($user)) {
				return new \WP_REST_Response([
					'message' => 'User not found, please check the email or first name and last name.',
				], 404);
			}

			// Update user role
			$user->set_role($role);

			//return success
			return new \WP_REST_Response([
				'message' => 'User role updated successfully.',
				'user_id' => $user->ID,
				'new_role' => $role
			], 200);
		} catch (Exception $e) {
			//log error
			error_log("Error: " . $e->getMessage() . " on line " . $e->getLine() . " in file " . $e->getFile());
			//return error
			return new \WP_REST_Response([
				'message' => 'Internal server error: ' . $e->getMessage(),
				'code' => 500,
			], 500);
		}
	}

	/**
	 * Enqueue scripts
	 *
	 * @return void
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script('jquery');
		//enqueue main style
		wp_enqueue_style(COOL_KIDS_NETWORK_WP_PREFIX . 'main-style', COOL_KIDS_NETWORK_WP_ASSETS_URL . 'css/coolkidsnetwork.min.css', [], COOL_KIDS_NETWORK_WP_VERSION);
		//enqueue main script
		wp_enqueue_script(COOL_KIDS_NETWORK_WP_PREFIX . 'main-script', COOL_KIDS_NETWORK_WP_ASSETS_URL . 'js/coolkidsnetwork.min.js', [], COOL_KIDS_NETWORK_WP_VERSION, true);
		//localize script
		wp_localize_script(COOL_KIDS_NETWORK_WP_PREFIX . 'main-script', COOL_KIDS_NETWORK_WP_PREFIX . 'object', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce(COOL_KIDS_NETWORK_WP_PREFIX . 'auth'),
			'logout_url' => wp_logout_url(),
		]);
	}

	/**
	 * Display character details
	 *
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	public function display_character_details($atts = [])
	{
		try {
			// Merge default attributes
			$atts = shortcode_atts([], $atts);
			//render template
			return render_wp_ckn_template('character-details', $atts);
		} catch (Exception $e) {
			//log error
			error_log("Error: " . $e->getMessage() . " on line " . $e->getLine() . " in file " . $e->getFile());
			//return error
			return '<div class="error">' . __('An error occurred while displaying the character details.', 'cool-kids-network-wp') . '</div>';
		}
	}

	/**
	 * Display signup form
	 *
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	public function display_signup_form($atts = [])
	{
		try {
			// Merge default attributes
			$atts = shortcode_atts([], $atts);
			//render template
			return render_wp_ckn_template('signup-form', $atts);
		} catch (Exception $e) {
			//log error
			error_log("Error: " . $e->getMessage() . " on line " . $e->getLine() . " in file " . $e->getFile());
			//return error
			return '<div class="error">' . __('An error occurred while displaying the signup form.', 'cool-kids-network-wp') . '</div>';
		}
	}

	/**
	 * Display login form
	 *
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	public function display_login_form($atts = [])
	{
		try {
			// Merge default attributes
			$atts = shortcode_atts([], $atts);

			//render template
			return render_wp_ckn_template('login-form', $atts);
		} catch (Exception $e) {
			//log error
			error_log("Error: " . $e->getMessage() . " on line " . $e->getLine() . " in file " . $e->getFile());
			//return error
			return '<div class="error">' . __('An error occurred while displaying the login form.', 'cool-kids-network-wp') . '</div>';
		}
	}

	/**
	 * Handle signup
	 *
	 * @return mixed
	 */
	public function handle_signup()
	{
		try {
			//verify nonce
			if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], COOL_KIDS_NETWORK_WP_PREFIX . 'auth')) {
				throw new Exception('Invalid request.');
			}

			if (!isset($_POST['email']) || !is_email($_POST['email'])) {
				throw new Exception('Invalid email address.');
			}

			$email = sanitize_email($_POST['email']);
			if (email_exists($email)) {
				throw new Exception('Email already registered.');
			}

			$request = Requests::get('https://randomuser.me/api/');

			//check if not success
			if (!$request->success) {
				throw new Exception('Failed to generate character.');
			}

			//decode response
			$data = json_decode($request->body, true);

			//get first user
			$user = array_shift($data['results']);

			//create user
			$user_data = [
				'user_email' => $email,
				'user_login' => $email,
				'role'       => 'cool_kid',
				'user_pass'  => wp_generate_uuid4(),
			];

			$user_id = wp_insert_user($user_data);
			if (is_wp_error($user_id)) {
				throw new Exception('Failed to create user.');
			}

			// Save character data
			update_user_meta($user_id, 'first_name', ucfirst($user['name']['first']));
			update_user_meta($user_id, 'last_name', ucfirst($user['name']['last']));
			update_user_meta($user_id, 'country', ucfirst($user['location']['country']));

			//return success
			wp_send_json_success([
				'message' => 'Signup successful. Please sign in to continue <a href="' . site_url('/sign-in') . '">here</a>.',
			]);
		} catch (Exception $e) {
			error_log("Error: " . $e->getMessage() . " on line " . $e->getLine() . " in file " . $e->getFile());
			//return error
			wp_send_json_error([
				'message' => $e->getMessage(),
				'code' => $e->getCode(),
				'details' => $e->getFile() . ' on line ' . $e->getLine(),
			]);
		}
	}

	/**
	 * Handle login
	 *
	 * @return mixed
	 */
	public function handle_login()
	{
		try {
			//verify nonce
			if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], COOL_KIDS_NETWORK_WP_PREFIX . 'auth')) {
				throw new Exception('Invalid request.');
			}

			$email = sanitize_email($_POST['email']);
			$user = get_user_by('email', $email);

			if (!$user) {
				throw new Exception('User not found.');
			}

			//set auth cookie
			wp_set_auth_cookie($user->ID);
			//redirect to my-character page
			wp_send_json_success(['message' => 'Login successful. Redirecting...', 'redirect_url' => site_url('/my-character')]);
		} catch (Exception $e) {
			error_log("Error: " . $e->getMessage() . " on line " . $e->getLine() . " in file " . $e->getFile());
			wp_send_json_error(['message' => $e->getMessage(), 'code' => $e->getCode(), 'details' => $e->getFile() . ' on line ' . $e->getLine()]);
		}
	}
}
