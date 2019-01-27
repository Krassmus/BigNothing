<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, 
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can 
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

//painless requirements
require_once __DIR__."/../lib/general_functions.php";
require_once __DIR__."/../lib/Template.php";
Template::setRootPath(__DIR__."/../");
require_once __DIR__."/../lib/Module.php";
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

require_once __DIR__."/../lib/DBManager.php";
require_once __DIR__ . "/../lib/ORM.php";
ORM::orm_setPDO(DBManager::getInstance());

//load plugins
require_once __DIR__."/../lib/ModuleManager.php";
$moduleManager = new ModuleManager(__DIR__."/../modules");
$moduleManager->loadModules();

$pluginManager = new ModuleManager(__DIR__."/../plugins");
$pluginManager->loadModules();


//Setting up the autoloader for core, modules and plugins
spl_autoload_register(function ($class) use ($moduleManager, $pluginManager) {
    $module_classes = $moduleManager->getModules();
    $plugin_classes = $pluginManager->getModules();
    if (strpos($class, "\\") !== false) {
        $class_array = preg_split("\\", $class, -1, PREG_SPLIT_NO_EMPTY);
        $modulename = array_shift($class_array);
        $possible_path = implode("/", $class_array);

        if (in_array($class[0], $module_classes)) {
            $path = __DIR__ . "/../" . $moduleManager->getModuleFolder()."/".$modulename."/lib/".$possible_path . ".php";
            if (file_exists($path)) {
                include_once $path;
            }
        }
        if (in_array($class[0], $plugin_classes)) {
            $path = __DIR__ . "/../" . $moduleManager->$pluginManager()."/".$modulename."/lib/".$possible_path . ".php";
            if (file_exists($path)) {
                include_once $path;
            }
        }
    } else {
        $folders = array("lib", "lib/hooks", "lib/models");
        foreach ($folders as $folder) {
            if (file_exists(__DIR__ . "/../" . $folder . "/" . $class . ".php")) {
                include_once __DIR__ . "/../" . $folder . "/" . $class . ".php";
            }
        }
    }
});

$moduleManager->setUpModuleHooks();
$pluginManager->setUpModuleHooks();

$route = $_SERVER['REQUEST_URI'];
if (isset($_SERVER['CONTEXT_PREFIX'])) {
    $route = substr($route, strlen($_SERVER['CONTEXT_PREFIX']) ?: "/");
}


//init session, login user
session_start();
if (isset($_SESSION['currentLoginId'])) {
    $GLOBALS['currentLogin'] = Login::get($_SESSION['currentLoginId']);
}

$router = RouterManager::getRouter($moduleManager, $pluginManager);

//init plugins
$moduleManager->initModules();
$pluginManager->initModules();

if ($route === "/") {
    $route = "/stream/everything/index";
}

$routed = $router->processRouting($route);

if (!$routed) {
    throw new Exception("404");
}

