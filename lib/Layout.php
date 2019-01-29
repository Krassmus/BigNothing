<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

class Layout {

    static protected $scripts = array();
    static protected $styles = array();

    static public function get() {
        return Template::summon(__DIR__."/../templates/layout.php");
    }

    static public function addScript($url) {
        self::$scripts[] = $url;
    }

    static public function getScripts() {
        return self::$scripts;
    }

    static public function addStyle($url) {
        self::$styles[] = $url;
    }

    static public function getStyles() {
        return self::$styles;
    }


}