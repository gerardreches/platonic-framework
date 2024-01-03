<?php

namespace Platonic\Api\Settings\Interface;

interface PluginSettingsPageRules extends SettingsPageRules {
	public function on_plugin_activation();

	public function on_plugin_deactivation();

	public function on_plugin_uninstall();
}