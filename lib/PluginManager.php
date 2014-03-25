<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

class PluginManager {

    protected $plugins = array();

    static public function loadPlugins() {

    }


    static protected function getDatabasePlugins() {
        return array();
    }

    static protected function getFileSystemPlugins() {
        return array();
    }
}