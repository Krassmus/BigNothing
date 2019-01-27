<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Authentication;

class Login extends \Controller {

    public function defaultAction() {
        $this->renderView();
    }

    public function registerAction() {
        $this->renderView();
    }

    public function authenticateAction() { //Should we move these two methods to another module ?
        if (isset($_POST['login']) && isset($_POST['password'])) {
            $loginAuthentication = new LoginAuthenticationHook(
                Login::oneBy("username", $_POST['login']),
                $_POST['login'],
                $_POST['password']
            );
            $loginAuthentication = HookCenter::run("LoginAuthenticationHook", $loginAuthentication);

            if ($loginAuthentication->isAuthenticated()) {
                $_SESSION['currentLoginId'] = $loginAuthentication->getLogin()->getId();
                if (isAjax()) {
                    $output = array(
                        'login' => $loginAuthentication->getLogin()->asArray()
                    );
                    $this->renderJSON($output);
                } else {
                    redirect("/stream/everything/index");
                }
            } else {
                unset($_SESSION);
            }
        }
    }

    public function logoutAction() {
        if (isset($_POST['logout'])) {
            unset($_SESSION);
        }
        redirect("/authentication/login");
    }
}