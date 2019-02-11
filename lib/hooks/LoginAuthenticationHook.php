<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

class LoginAuthenticationHook implements Hook {
    static public function getHookDescription() {
        return "This hook is needed to log a user in. It is always called in the index.php if a user is not logged in yet. It could be logged in by Standard-Auth (username, password as GET-parameters) or by OAuth2 or any other plugin (OpenID, LDAP, HTTP-Basic-Auth, maybe?).";
    }

    protected $isAuthenticated = false;     //Must be set to true to log the user in
    protected $login = null;                //A Login class object or null if none is found
    protected $url = null;                  //URL to redirect to after successful Login-process

    /**
     * Returns the Login object as it is in the database. If no matching login in database was found, this returns null.
     * For this case ask for the username.
     * @return null|Login object
     */
    public function getLogin() {
        return $this->login;
    }

    public function isAuthenticated() {
        return $this->isAuthenticated;
    }

    /**
     * When your module decides to log a user in, it MUST set the login with this method.
     * @param Login $login
     */
    public function setLogin(Login $login) {
        $this->login = $login;
    }

    /**
     * This method is used to authenticate a user. If the interactor of the
     * hook verified loginname and password it should set authenticateLogin(true).
     * If it can't verify the login, it should do nothing.
     * @param boolean $isAuthenticated: true if login should be authenticated, else false
     */
    public function authenticateLogin($isAuthenticated = true) {
        $this->isAuthenticated = (bool) $isAuthenticated;
    }
}