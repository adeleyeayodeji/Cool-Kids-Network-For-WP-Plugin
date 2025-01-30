<?php

/**
 * File Description:
 * Singleton abstract class to be inherited by other classes
 *
 * @link    https://www.adeleyeayodeji.com/
 * @since   1.0.0
 *
 * @author  Adeleye Ayodeji (https://www.adeleyeayodeji.com)
 * @package Cool_Kids_Network_WP
 *
 * @copyright (c) 2025, Adeleye Ayodeji (https://www.adeleyeayodeji.com)
 */

namespace Cool_Kids_Network_WP;

// Abort if called directly.
defined('WPINC') || die;

/**
 * Class Singleton
 *
 * @package Cool_Kids_Network_WP
 */
abstract class Singleton
{

	/**
	 * Singleton constructor.
	 *
	 * Protect the class from being initiated multiple times.
	 *
	 * @param array $props Optional properties array.
	 *
	 * @since 1.0.0
	 */
	protected function __construct($props = array())
	{
		// Protect class from being initiated multiple times.
	}

	/**
	 * Instance obtaining method.
	 *
	 * @return static Called class instance.
	 * @since 1.0.0
	 */
	public static function instance()
	{
		static $instances = array();

		// @codingStandardsIgnoreLine Plugin-backported
		$called_class_name = get_called_class();

		if (! isset($instances[$called_class_name])) {
			$instances[$called_class_name] = new $called_class_name();
		}

		return $instances[$called_class_name];
	}
}
