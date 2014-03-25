<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

class UserAuthenticationHook implements Hook {
    static public function getHookDescription() {
        return "";
    }

    protected $isAuthenticated = false;
    protected $login = null;
    protected $password = null;

    public function __construct($login, $password) {
        $this->login = $login;
        $this->password = $password;
    }

    public function getLogin() {
        return $this->login;
    }

    public function getPassword() {
        return $this->password;
    }

    /**
     * This method is used to authenticate a user. If the interactor of the
     * hook verified loginname and password it should set authenticateLogin(true).
     * If it can't verify the login, it should do nothing.
     * @param $is boolean: true if login should be authenticated, else false
     */
    public function authenticateLogin($isAuthenticated = true) {
        $this->isAuthenticated = (bool) $isAuthenticated;
    }
}