<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Authentication;

class Authentication extends \Module
{
    public static function setUpModuleHooks()
    {
        \HookCenter::register(
            "LoginAuthenticationHook",
            "\\Authentication\\Authentication::authenticateUser"
        );
        \HookCenter::register(
            "\\Sass\\SassHook",
            "\\Authentication\\Authentication::addSassFile"
        );
    }

    public function addSassFile($hook) {
        //$hook->addSassFile(__DIR__."/assets/login.scss", "outside");
    }

    /**
     * Authenticates the user against the database with username=login and password, which should match the hash.
     * As a hash bcrypt and md5 are possible, though bcrypt is preferred.
     * @param LoginAuthenticationHook $hook : The hook object with login and cleartext password as transferred by use REQUEST variable.
     */
    public static function authenticateUser($hook)
    {
        if ($hook->getLogin() && $hook->getPassword()) {

            $login = $hook->getLogin();
            if (verifyPassword($hook->getPassword(), $login['password_hash'])) {
                $hook->authenticateLogin(true);
            } elseif($login['password_hash'] === md5($hook->getPassword())) {
                //this is for developers - we can insert md5-hashes as passwords into the users table.
                //With the first login-attempt we change it to a bcrypt-password hash.
                $hook->authenticateLogin(true);

                //change md5-password
                $newpassword = hashPassword($hook->getPassword());
                $login['password_hash'] = $newpassword;
                $login->store();
            }
        }
    }
}