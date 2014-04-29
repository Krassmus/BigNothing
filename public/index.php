<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, 
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can 
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

//painless requirements
require_once __DIR__."/../lib/general_functions.php";
require_once __DIR__."/../lib/EventCenter.php";
require_once __DIR__."/../lib/HookCenter.php";
require_once __DIR__."/../lib/Template.php";
require_once __DIR__."/../lib/Module.php";
require_once __DIR__."/../lib/Controller.php";
require_once __DIR__."/../lib/AuthenticatedController.php";
Template::setRootPath(__DIR__."/../");
require_once __DIR__."/../lib/Icon.php";
require_once __DIR__."/../lib/error_handler.php";

//configs
$configdir_path = __DIR__."/../configs";
$configdir = opendir($configdir_path);
while (($file = readdir($configdir)) !== false) {
    if (!is_dir($configdir_path."/".$file)
            && strpos($file, ".") !== 0  //no .ht... or other hidden files
            && strpos($file, ".php") !== false) {
        include_once $configdir_path."/".$file;
    }
}
closedir($configdir);

//load plugins
require_once __DIR__."/../lib/ModuleManager.php";
$moduleManager = new ModuleManager(__DIR__."/../modules");
$moduleManager->loadModules();

$pluginManager = new ModuleManager(__DIR__."/../plugins");
$pluginManager->loadModules();

$moduleManager->setUpModuleHooks();
$pluginManager->setUpModuleHooks();

//init session, login user
if (isset($_POST['login']) && isset($_POST['password'])) {
    include_once __DIR__."/../lib/hooks/LoginAuthenticationHook.php";
    $loginAuthentication = new LoginAuthenticationHook($_POST['login'], $_POST['password']);
    $loginAuthentication = HookCenter::run("LoginAuthenticationHook", $loginAuthentication);
    if ($loginAuthentication->isAuthentivated()) {
        session_start();
        $_SESSION['currentLogin'] = true;
    } else {
        unset($_SESSION);
    }
}

require_once __DIR__.'/../lib/RouterManager.php';
$router = RouterManager::getRouter($moduleManager, $pluginManager);

//init plugins
$moduleManager->initModules();
$pluginManager->initModules();

$route = $_SERVER['REQUEST_URI'];
if (isset($_SERVER['CONTEXT_PREFIX'])) {
    $route = substr($route, strlen($_SERVER['CONTEXT_PREFIX']) ?: "/");
}
if ($route === "/") {
    $route = "/stream/everything/index";
}

$routed = $router->processRouting($route);

if (!$routed) {
    throw new Exception("404");
}

