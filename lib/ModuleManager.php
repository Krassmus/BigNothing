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
            $pluginFile = $this->pluginFolder."/".$plugin."/".$pluginClass.".php";
            require_once $pluginFile;
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
            $pluginClass = "\\".ucfirst($plugin)."\\".ucfirst($plugin);
            if (class_exists($pluginClass)) {
                $this->plugins[] = new $pluginClass();
            }
        }
    }

    /**
     * Returns the Module-object of the module or false if there is no such module.
     * @param string $name : name of the module.
     * @return bool|Module : returns the Module-object of the module or false if there is no such module.
     */
    public function getModule($name) {
        $name = ucfirst(strtolower($name));
        foreach ($this->plugins as $pluginobject) {
            if (get_class($pluginobject) === $name."\\".$name) {
                return $pluginobject;
            }
        }
        return false;
    }

    /**
     * Tells a module to perform a certain route. If this module is either nonexistent or has no controller or that controller has no such action, routing was unsuccessful and returns false.
     * @param string $module : name of the module
     * @param string $controller : name of the controller
     * @param string $action : name of the action
     * @param array $vars : associative array of variables like array('username' => $username)
     * @return bool : true if routing was successful, else false. Routing was only successful, if there is a module with the given name and that module has a fitting controller with the given action.
     */
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
