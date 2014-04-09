<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

abstract class Module {

    /**
     * This static method can be used by the module to register itself to various hooks in the system.
     * This method is called even before the module itself is invoked.
     */
    public static function setUpPluginHooks() {}

    /**
     * This method is called to render actions of the module.
     * @param string $controller : name of the controller in the module
     * @param string $action : name of the action that is to be rendered.
     * @param $vars
     */
    public function perform($controller, $action, $vars) {
        $controller = ucfirst(strtolower($controller));
        $moduleClass = get_class($this);
        $reflection = new ReflectionClass($moduleClass);
        $directory = dirname($reflection->getFileName());
        $namespace = $reflection->getNamespaceName();
        $controllerFile = $directory."/controller/".$controller.".php";
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controllerClass = '\\'.$namespace.'\\'.$controller;
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                $controller->renderAction($action, $vars);
            }
        }
    }
}