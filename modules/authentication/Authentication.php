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
    }

    public static function authenticateUser($hook)
    {
        if ($hook->getLogin() && $hook->getPassword()) {

            $db = \DBManager::getInstance();
            $statement = $db->prepare("
                SELECT * 
                FROM logins 
                WHERE username = :login
            ");
            $statement->execute(array('login' => $hook->getLogin()));
            $data = $statement->fetch(\PDO::FETCH_ASSOC);
            if (verifyPassword($hook->getPassword(), $data['password_hash'])) {
                $hook->authenticateLogin(true);
            } elseif($data['password_hash'] === md5($hook->getPassword())) {
                $hook->authenticateLogin(true);

                //change md5-password
                $newpassword = hashPassword($hook->getPassword());
                $statement = $db->prepare("
                    UPDATE logins
                    SET password_hash = :newpassword 
                    WHERE username = :login
                ");
                $statement->execute(array(
                    'login' => $hook->getLogin(),
                    'newpassword' => $newpassword
                ));
            }
        }
    }
}