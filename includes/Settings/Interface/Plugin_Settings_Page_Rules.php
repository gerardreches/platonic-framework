<?php

namespace Platonic\Framework\Settings\Interface;

interface Plugin_Settings_Page_Rules extends Settings_Page_Rules {

	public function on_plugin_activation(): void;

	public function on_plugin_deactivation(): void;

	public static function on_plugin_uninstall(): void;

}