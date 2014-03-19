<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, 
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can 
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

require_once __DIR__."/../vendor/Klein/Klein/Klein.php";

/**
 * This class serves a router as a singleton that can be accessed by all plugins and modules.
 */
class RouterManager {

	static $router = null;

	static public function getRouter() {
		if (self::$router === null) {
			self::$router = new \Klein\Klein();
		}
		return self::$router;
	}
}