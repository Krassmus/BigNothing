<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

class Router {

    protected $registeredRoutes = array();
    protected $moduleManager = null;
    protected $pluginManager = null;

    public function __construct($moduleManager, $pluginManager) {
        $this->moduleManager = $moduleManager;
        $this->pluginManager = $pluginManager;
    }

    public function registerRoute($route, $callback) {
        $this->registeredRoutes[$route] = $callback;
    }

    public function processRouting($currentRoute) {
        foreach ($this->registeredRoutes as $route => $callback) {
            $vars = $this->routesMatch($currentRoute, $route);
            if ($vars !== false) {
                call_user_func_array($callback, $vars);
                return true;
            }
        }
        $currentRoute = explode("/", $currentRoute);
        while (count($currentRoute) > 0 && !trim($currentRoute[0])) {
            array_shift($currentRoute);
        }
        $vars = array_slice($currentRoute, 3);

        if ($currentRoute[0] === "plugins") {
            array_shift($currentRoute);
            if (!isset($currentRoute[2])) {
                return false;
            }
            return $this->pluginManager->routeModule($currentRoute[0], $currentRoute[1], $currentRoute[2], $vars);
        } else {
            if (!isset($currentRoute[2])) {
                return false;
            }
            return $this->moduleManager->routeModule($currentRoute[0], $currentRoute[1], $currentRoute[2], $vars);
        }
    }

    protected function routesMatch($currentRoute, $route) {
        $currentRoute = explode("/", $currentRoute);
        $route = explode("/", $route);
        $vars = array();
        while ($part = array_shift($currentRoute)) {
            $routepart = array_shift($route);
            if ($part[0] === ":") {
                $vars[] = $routepart;
            } else {
                if ($part !== $routepart) {
                    return false;
                }
            }
        }
        return $vars;
    }
}
