<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * Class URL to create URLs and links with packed parameters.
 *
 * Use it like URL::create("authentication/login", array('login' => "myname"));
 * or URL::link("authentication/login", array('login' => "myname"));
 */
class URL {

    /**
     * Creates an URL
     * @param $path : the path to the controller like "authentication/login"
     * @param array $parameters : associative array of paramaters
     * @return string : an URL
     */
    static public function create($path, $parameters = array()) {
        $base = $GLOBALS['URL'];
        $url = $base . $path;
        if (count($parameters)) {
            foreach ($parameters as $key => $value) {
                $parameters[$key] = urlencode($key) . "=" . urlencode($value);
            }
            $url .= "?" . implode("&", array_values($parameters));
        }
        return $url;
    }

    /**
     * Creates a link which is an html-encoded URL
     * @param $path : the path to the controller like "authentication/login"
     * @param array $parameters : associative array of paramaters
     * @return string : an URL
     */
    static public function link($path, $parameters = array()) {
        return htmlentities(self::create($path, $parameters));
    }
}