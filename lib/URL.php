<?php

class URL {

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

    static public function link($path, $parameters = array()) {
        return htmlentities(self::create($path, $parameters));
    }
}