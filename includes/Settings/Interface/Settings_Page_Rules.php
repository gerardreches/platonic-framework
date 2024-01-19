<?php

namespace Platonic\Framework\Settings\Interface;

interface Settings_Page_Rules extends Settings_Rules {
	static function add_admin_menu(): void;
}