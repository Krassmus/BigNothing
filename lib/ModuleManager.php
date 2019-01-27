<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

class ModuleManager {

    protected $moduleFolder = null;
    protected $moduleClasses = null;
    protected $modules = null;

    public function __construct($path) {
        $this->moduleFolder = $path;
    }

    public function getModuleFolder() {
        return $this->moduleFolder;
    }

    public function loadModules() {
        if ($this->moduleClasses === null) {
            $this->getModules();
        }
        foreach ($this->moduleClasses as $module) {
            $module = strtolower($module);
            $moduleClass = ucfirst($module);
            $moduleFile = $this->moduleFolder."/".$module."/".$moduleClass.".php";
            require_once $moduleFile;
        }
    }

    public function getModules($force_reload = false) {
        if (($this->modules === null) || $force_reload) {
            //$this->moduleClasses = array_intersect(self::getDatabaseModules(), self::getFileSystemModules());
            $this->moduleClasses = self::getDatabaseModules() + self::getFileSystemModules();
        }
        return $this->moduleClasses;
    }

    public function setUpModuleHooks() {
        foreach ($this->moduleClasses as $module) {
            $module_class = "\\".ucfirst($module)."\\".ucfirst($module);
            if (class_exists($module_class)) {
                $module_class::setUpModuleHooks();
            }
        }
    }

    public function initModules() {
        foreach ($this->moduleClasses as $module) {
            $moduleClass = "\\".ucfirst($module)."\\".ucfirst($module);
            if (class_exists($moduleClass)) {
                $this->modules[] = new $moduleClass();
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
        foreach ($this->modules as $moduleobject) {
            if (get_class($moduleobject) === $name."\\".$name) {
                return $moduleobject;
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

    protected function getDatabaseModules() {
        return array();
    }

    protected function getFileSystemModules() {
        $modules = array();
        $moduleDirectory = opendir($this->moduleFolder);
        if ($moduleDirectory !== false) {
            while (($folder = readdir($moduleDirectory)) !== false) {
                if (is_dir($this->moduleFolder."/".$folder)
                        && strpos($folder, ".") !== 0) {
                    if (file_exists($this->moduleFolder."/".$folder)."/$folder.php") {
                        $modules[] = $folder;
                    }
                }
            }
        }
        closedir($moduleDirectory);
        return $modules;
    }
}
