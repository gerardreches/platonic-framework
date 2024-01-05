<?php

namespace Platonic\Framework\Settings\Interface;

interface Plugin_Settings_Page_Rules extends Settings_Page_Rules {
	public function on_plugin_activation();

	public function on_plugin_deactivation();

	public static function on_plugin_uninstall();
}