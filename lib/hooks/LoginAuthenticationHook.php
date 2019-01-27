<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

class LoginAuthenticationHook implements Hook {
    static public function getHookDescription() {
        return "";
    }

    protected $isAuthenticated = false;     //Must be set to true to log the user in
    protected $login = null;                //A Login class object or null if none is found
    protected $username = null;             //The username as from $_POST['username']
    protected $password = null;             //The cleartext password as from $_POST['password']
    protected $url = null;                  //URL to redirect to after successful Login-process

    public function __construct($login, $username, $password) {
        $this->login = $login;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Returns the Login object as it is in the database. If no matching login in database was found, this returns null.
     * For this case ask for the username.
     * @return null|Login object
     */
    public function getLogin() {
        return $this->login;
    }

    /**
     * @return username
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @return string $password : cleartext password of user.
     */
    public function getPassword() {
        return $this->password;
    }

    public function isAuthenticated() {
        return $this->isAuthenticated;
    }

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