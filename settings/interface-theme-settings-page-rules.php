<?php

namespace Platonic\API;

interface Theme_Settings_Page_Rules extends Settings_Page_Rules
{
	function on_theme_activation( $new_theme );
	function on_theme_deactivation();
}