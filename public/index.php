<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, 
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can 
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

//painless requirements
require_once __DIR__."/../lib/EventCenter.php";
require_once __DIR__."/../lib/HookCenter.php";
require_once __DIR__."/../lib/Template.php";
Template::setRootPath(__DIR__."/../");
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
$pluginManager = new ModuleManager(__DIR__."/../plugins");
$pluginManager->loadPlugins();
$pluginManager->setUpPluginHooks();

//init session, login user
if (isset($_POST['login']) && isset($_POST['password'])) {
    include_once __DIR__."/../lib/hooks/LoginAuthenticationHook.php";
    $loginAuthentication = new LoginAuthenticationHook($_POST['login'], $_POST['password']);
    $loginAuthentication = HookCenter::run("LoginAuthenticationHook", $loginAuthentication);
}

require_once __DIR__.'/../lib/RouterManager.php';

//init core routes

//init plugins
$pluginManager->initPlugins();
throw new NotLoggedInException();
echo Template::summon(__DIR__."/../templates/test.php")
				->with(Template::summon(__DIR__."/../templates/layout.php"))
				->render();

