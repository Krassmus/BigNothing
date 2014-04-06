<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

class Router {

    protected $registeredRoutes = array();

    public function registerRoute($route, $callback) {
        $this->registeredRoutes[$route] = $callback;
    }

    public function processRouting($currentRoute) {
        foreach ($this->registeredRoutes as $route => $callback) {
            $vars = $this->routesMatch($currentRoute, $route);
            if ($vars !== false) {
                call_user_func_array($callback, $vars);
            }
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