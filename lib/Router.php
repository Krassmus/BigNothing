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
        $currentRoute = preg_split("/\//", $currentRoute, -1, PREG_SPLIT_NO_EMPTY);
        $vars = array_slice($currentRoute, 3);

        if ($currentRoute[0] === "plugin") {
            array_shift($currentRoute);
            if (!isset($currentRoute[1])) {
                return false;
            }
            return $this->pluginManager->routeModule(
                $currentRoute[0],
                $currentRoute[1],
                isset($currentRoute[2]) ? $currentRoute[2] : null,
                $vars
            );
        } else {
            if (!isset($currentRoute[1])) {
                return false;
            }
            return $this->moduleManager->routeModule(
                $currentRoute[0],
                $currentRoute[1],
                isset($currentRoute[2]) ? $currentRoute[2] : null,
                $vars
            );
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
