<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Authentication;

class Login extends \Controller {

    public function defaultAction() {
        \Layout::addScript(\URL::create("authentication/assets/login.js"));
        \HookCenter::run("\\Scss\\ScssHook")->activateScssPackage("outside");
        $this->renderView();
    }

    public function registerAction() {
        \Layout::addScript(\URL::create("authentication/assets/login.js"));
        \HookCenter::run("\\Scss\\ScssHook")->activateScssPackage("outside");
        $this->renderView();
    }

    public function authenticateAction() { //Should we move these two methods to another module ?


    }

    public function logoutAction() {
        if (isset($_POST['logout'])) {
            unset($_SESSION);
        }
        redirect("/authentication/login");
    }

    public function troubleshootingAction() {
        \Layout::addScript(\URL::create("authentication/assets/login.js"));
        \HookCenter::run("\\Scss\\ScssHook")->activateScssPackage("outside");
        $this->renderView();
    }

    public function username_existsAction() {
        $login = \Login::oneBy("username", $_GET['login']);
        echo json_encode(array(
            'exists' => $login ? 1 : 0
        ));
    }
}