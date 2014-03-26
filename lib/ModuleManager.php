<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

class ModuleManager {

    protected $pluginPath = null;
    protected $pluginClasses = null;
    protected $plugins = null;

    public function __construct($path) {
        $this->pluginFolder = $path;
    }

    public function loadPlugins() {
        if ($this->pluginClasses === null) {
            $this->getPlugins();
        }
        foreach ($this->pluginClasses as $plugin) {
            include_once $this->pluginFolder."/$plugin/$plugin.plugin.php";
        }
    }

    public function getPlugins() {
        $this->pluginClasses = array_intersect(self::getDatabasePlugins(), self::getFileSystemPlugins());
    }

    public function setUpPluginHooks() {
        foreach ($this->pluginClasses as $plugin) {
            if (class_exists($plugin)) {
                $plugin::setUpPluginHooks();
            }
        }
    }

    public function initPlugins() {
        foreach ($this->pluginClasses as $plugin) {
            if (class_exists($plugin)) {
                $this->plugins[] = new $plugin();
            }
        }
    }

    protected function getDatabasePlugins() {
        return array();
    }

    protected function getFileSystemPlugins() {
        $plugins = array();
        $pluginDirectory = opendir($this->pluginFolder);
        while (($folder = readdir($pluginDirectory)) !== false) {
            if (is_dir($this->pluginFolder."/".$folder)
                && strpos($folder, ".") !== 0) {
                if (file_exists($this->pluginFolder."/".$folder)."/$folder.plugin.php") {
                    $plugins[] = $folder;
                }
            }
        }
        closedir($pluginDirectory);
        return $plugins;
    }
}