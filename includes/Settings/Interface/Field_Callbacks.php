<?php

namespace Platonic\Framework\Settings\Interface;

interface Field_Callbacks {

	static function add_settings_field_callback( array $args );

	static function add_text_field_callback( array $args );

	static function add_textarea_field_callback( array $args );

	static function add_number_field_callback( array $args );

	static function add_email_field_callback( array $args );

	static function add_tel_field_callback( array $args );

	static function add_password_field_callback( array $args );

	static function add_url_field_callback( array $args );

	static function add_color_field_callback( array $args );

	static function add_checkbox_field_callback( array $args );

	static function add_file_field_callback( array $args );

	static function add_radio_field_callback( array $args );

	static function add_select_field_callback( array $args );

}