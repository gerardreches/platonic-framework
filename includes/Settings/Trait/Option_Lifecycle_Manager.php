<?php

namespace Platonic\Framework\Settings\Trait;

/**
 * Trait providing hooks for handling WordPress options.
 *
 * This trait defines methods and hooks related to the lifecycle of WordPress options. The methods are intended
 * to be implemented by the class using this trait, providing a structured way to handle various option-related
 * actions such as adding, updating, and deleting options.
 *
 * @package Platonic\Framework\Settings\Trait
 */
trait Option_Lifecycle_Manager {

	/**
	 * Add hooks for various option-related actions.
	 *
	 * This method sets up hooks for different stages of option handling:
	 * - Before updating an option value.
	 * - After adding an option.
	 * - After updating an option.
	 * - After deleting an option.
	 * - Sanitizing an option value.
	 *
	 * @param string $option_name The name of the option for which hooks should be added.
	 *
	 * @return void
	 */
	static function manage_option_lifecycle( string $option_name ): void {
		add_filter( 'pre_update_option_' . $option_name, array( static::class, 'pre_update_option' ), 10, 3 );
		add_action( 'add_option_' . $option_name, array( static::class, 'after_add_option' ), 10, 2 );
		add_action( 'update_option_' . $option_name, array( static::class, 'after_update_option' ), 10, 3 );
		add_action( 'delete_option_' . $option_name, array( static::class, 'after_delete_option' ), 10, 1 );
		add_filter( 'sanitize_option_' . $option_name, array( static::class, 'sanitize_option' ), 20, 3 );
	}

	/**
	 * Handle logic before updating the option value.
	 *
	 * @param mixed $value The new option value.
	 * @param mixed $old_value The old option value.
	 * @param string $option The option name.
	 *
	 * @return mixed The modified option value.
	 *
	 * @note To be implemented by the class using this trait.
	 */
	static function pre_update_option( mixed $value, mixed $old_value, string $option ): mixed {
		// Logic specific to the class using this trait.
		return $value;
	}

	/**
	 * Handle logic after adding an option.
	 *
	 * @param string $option The name of the added option.
	 * @param mixed $value The value of the added option.
	 *
	 * @return void
	 *
	 * @note To be implemented by the class using this trait.
	 */
	static function after_add_option( string $option, mixed $value ): void {
		// Logic specific to the class using this trait.
	}

	/**
	 * Handle logic after updating an option.
	 *
	 * @param mixed $old_value The old value of the option.
	 * @param mixed $value The new value of the option.
	 * @param string $option The option name.
	 *
	 * @return void
	 *
	 * @note To be implemented by the class using this trait.
	 */
	static function after_update_option( mixed $old_value, mixed $value, string $option ): void {
		// Logic specific to the class using this trait.
	}

	/**
	 * Handle logic after deleting an option.
	 *
	 * @param string $option The name of the deleted option.
	 *
	 * @return void
	 *
	 * @note To be implemented by the class using this trait.
	 */
	static function after_delete_option( string $option ): void {
		// Logic specific to the class using this trait.
	}

	/**
	 * Sanitize the option value.
	 *
	 * @param mixed $value The option value to be sanitized.
	 * @param string $option The option name.
	 * @param mixed $original_value The original, unmodified value of the option.
	 *
	 * @return mixed                The sanitized option value.
	 *
	 * @note To be implemented by the class using this trait. The hook is set to trigger after the setting sanitize_callback.
	 */
	static function sanitize_option( mixed $value, string $option, mixed $original_value ): mixed {
		// Sanitization logic specific to the class using this trait.
		return $value;
	}
}
