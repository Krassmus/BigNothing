<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, 
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can 
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * Class Icon to display an icon in a template or elsewhere.
 * Usage:
 *
 *     echo Icon::linkIntern();
 *
 * Should echo something like '<span class="icon">&#59226;</span>'
 * which is a nice icon colored like the text around the icon.
 *
 * You can also add HTML-attributes to it like this
 *
 *     echo Icon::linkIntern(array('onclick' => 'return window.confirm("Are you sure to leave this page?");'));
 */
class Icon {
    static protected $mappings = array(
        'link' => "&#8627;",
        'linkExtern' => "&#8627;",
        'linkIntern' => "&#59226;",
        'security' => "&#128274;"
    );

    protected $symbol = null;
    protected $attributes = array();

    static public function __callStatic($name, $arguments) {
        $icon = new Icon($name, isset($arguments[0]) && is_array($arguments[0]) ? $arguments[0] : array());
        return $icon;
    }

    public function __construct($name, $attributes = array()) {
        $this->symbol = $name;
        if (is_array($attributes)) {
            $this->attributes = array_merge($this->attributes, $attributes);
        }
    }

    public function addClass($value) {
        if (isset($this->attributes["class"])) {
            $this->attributes["class"] .= " ".$value;
        } else {
            $this->attributes["class"] = $value;
        }
    }

    public function render() {
        $this->addClass("icon");

        $attributes_text = "";
        foreach ($this->attributes as $name => $value) {
            $attributes_text .= " ".$name.'="'.escapeHtml($value).'"';
        }

        return '<span'.$attributes_text.'>'.(self::$mappings[$this->symbol] ?: "&#9889;").'</span>';
    }

    public function __toString() {
        return $this->render();
    }
}