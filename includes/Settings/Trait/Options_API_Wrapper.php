<?php

namespace Platonic\Framework\Settings\Trait;

trait Options_API_Wrapper {

	/**
	 * Adds the defined option.
	 *
	 * @param mixed $value Option value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
	 * @param string $deprecated Description. Not used anymore.
	 * @param string|bool $autoload Whether to load the option when WordPress starts up.
	 *
	 * @return bool True if the option was added, false otherwise.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_option/
	 */
	final static function add_option( mixed $value = '', string $deprecated = '', string|bool $autoload = 'yes' ): bool {
		return add_option( static::OPTION_NAME, $value, $deprecated, $autoload );
	}

	/**
	 * Adds the defined option for the current network.
	 *
	 * @param mixed $value Option value, can be anything. Expected to not be SQL-escaped.
	 *
	 * @return bool True if the option was added, false otherwise.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_site_option/
	 */
	final static function add_site_option( mixed $value ): bool {
		return add_site_option( static::OPTION_NAME, $value );
	}

	/**
	 * Retrieves the value for the defined option name.
	 *
	 * @param mixed|false $default Default value to return if the option does not exist.
	 *
	 * @return mixed Value of the option. If there is no option in the database, $default is returned.
	 *
	 * @see https://developer.wordpress.org/reference/functions/get_option/
	 */
	final static function get_option( mixed $default = false ): mixed {
		return get_option( static::OPTION_NAME, $default );
	}

	/**
	 * Retrieve the value for the current network for the defined option.
	 *
	 * @param mixed $default_value Default value to return if the option does not exist.
	 * @param bool $deprecated Whether to use cache. Multisite only. Always set to true.
	 *
	 * @return mixed Value of the option. If the option does not exist, $default_value is returned.
	 *
	 * @see https://developer.wordpress.org/reference/functions/get_site_option/
	 */
	final static function get_site_option( mixed $default_value = false, bool $deprecated = true ): mixed {
		return get_site_option( static::OPTION_NAME, $default_value, $deprecated );
	}

	/**
	 * Updates the value of the defined option that was already added.
	 *
	 * @param mixed $value Option value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
	 * @param string|bool $autoload Optional. Whether to load the option when WordPress starts up.
	 *
	 * @return bool True if the value was updated, false otherwise.
	 *
	 * @see https://developer.wordpress.org/reference/functions/update_option/
	 */
	final static function update_option( mixed $value, string|bool $autoload = null ): bool {
		return update_option( static::OPTION_NAME, $value, $autoload );
	}

	/**
	 * Updates the value of the defined option that was already added for the current network.
	 *
	 * @param mixed $value Option value. Expected to not be SQL-escaped.
	 *
	 * @return bool True if the value was updated, false otherwise.
	 *
	 * @see https://developer.wordpress.org/reference/functions/update_site_option/
	 */
	final static function update_site_option( mixed $value ): bool {
		return update_site_option( static::OPTION_NAME, $value );
	}

	/**
	 * Removes the defined option. Prevents removal of protected WordPress options.
	 *
	 * @return bool True if the option was deleted, false otherwise.
	 *
	 * @see https://developer.wordpress.org/reference/functions/delete_option/
	 */
	final static function delete_option(): bool {
		return delete_option( static::OPTION_NAME );
	}

	/**
	 * Removes the defined option for the current network.
	 *
	 * @return bool True if the option was deleted, false otherwise.
	 *
	 * @see https://developer.wordpress.org/reference/functions/delete_site_option/
	 */
	final static function delete_site_option(): bool {
		return delete_site_option( static::OPTION_NAME );
	}

}