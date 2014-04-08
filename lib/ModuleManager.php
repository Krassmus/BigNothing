<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

class ModuleManager {

    protected $pluginFolder = null;
    protected $pluginClasses = null;
    protected $plugins = array();

    public function __construct($path) {
        $this->pluginFolder = $path;
    }

    public function loadPlugins() {
        if ($this->pluginClasses === null) {
            $this->getPlugins();
        }
        foreach ($this->pluginClasses as $plugin) {
            $plugin = strtolower($plugin);
            $pluginClass = ucfirst($plugin);
            include $this->pluginFolder."/".$plugin."/".$pluginClass.".php";
            if (class_exists($pluginClass)) {
                $this->plugins[] = new $pluginClass();
            }
        }
    }

    public function getPlugins() {
        //$this->pluginClasses = array_intersect(self::getDatabasePlugins(), self::getFileSystemPlugins());
        $this->pluginClasses = self::getDatabasePlugins() + self::getFileSystemPlugins();
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
            $plugin = ucfirst($plugin);
            if (class_exists($plugin)) {
                var_dump($plugin);
                $this->plugins[] = new $plugin();
            }
        }
    }

    public function getModule($name) {
        foreach ($this->plugins as $pluginobject) {
            if (get_class($pluginobject) === $name) {
                return $pluginobject;
            }
        }
        return false;
    }

    public function routeModule($module, $controller, $action, $vars) {
        $module = $this->getModule($module);
        if ($module) {
            return $module->perform($controller, $action, $vars);
        } else {
            return false;
        }
    }

    protected function getDatabasePlugins() {
        return array();
    }

    protected function getFileSystemPlugins() {
        $modules = array();
        $moduleDirectory = opendir($this->pluginFolder);
        if ($moduleDirectory !== false) {
            while (($folder = readdir($moduleDirectory)) !== false) {
                if (is_dir($this->pluginFolder."/".$folder)
                        && strpos($folder, ".") !== 0) {
                    if (file_exists($this->pluginFolder."/".$folder)."/$folder.php") {
                        $modules[] = $folder;
                    }
                }
            }
        }
        closedir($moduleDirectory);
        return $modules;
    }
}
