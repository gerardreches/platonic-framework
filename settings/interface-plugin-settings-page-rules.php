<?php

namespace Platonic\API;

interface Plugin_Settings_Page_Rules extends Settings_Page_Rules
{
	public function on_plugin_activation();
	public function on_plugin_deactivation();
	public function on_plugin_uninstall();
}