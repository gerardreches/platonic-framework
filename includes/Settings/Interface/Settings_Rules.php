<?php

namespace Platonic\Framework\Settings\Interface;

interface Settings_Rules {

	public function __construct();

	static function register(): void;

}