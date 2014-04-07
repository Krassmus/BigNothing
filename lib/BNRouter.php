<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

class BNRouter {

    protected $registeredRoutes = array();

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

        if ($currentRoute[0] === "plugins") {
            array_shift($currentRoute);
            $folder = __DIR__."/../plugins/";
            return $this->tryControllerRoute(implode("/", $currentRoute), $folder);
        } else {
            $folder = __DIR__."/../modules/";
            return $this->tryControllerRoute(implode("/", $currentRoute), $folder);
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

    /**
     * Tries to find the given route in the directory as a controller plus action of the controller.
     * This is made so that modules and plugins don't need to register hundreds of routes at the
     * router - they can only implement the controller and the action and it all works fine.
     * @param string $route : the route. But if it is a plugin-route we left the first part of the route away to be able to match the route just like we match module-routes.
     * @param string $folder : the absolute path of a folder where modules or plugins should be in.
     * @return bool : true if routing was successful, else false.
     */
    protected function tryControllerRoute($route, $folder) {
        $route = explode("/", $route);
        $possibleController = $folder."/".$route[0]."/controller/".$route[1].".controller.php";
        if (file_exists($possibleController) && class_exists($route[0])) {
            $vars = array_slice($route, 3);
            $controller = new {$route[0]}();
            $controller->{$route[2]."Action"}($vars);
            return true;
        }
        return false;
    }
}