<?php

namespace Platonic\Framework\Settings\Interface;

interface PluginSettingsPageRules extends SettingsPageRules {
	public function on_plugin_activation();

	public function on_plugin_deactivation();

	public static function on_plugin_uninstall();
}