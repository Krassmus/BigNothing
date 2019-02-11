<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Oauth2;

class Oauth2 extends \Module
{
    public static function setUpModuleHooks()
    {
        \HookCenter::register(
            "LoginAuthenticationHook",
            "\\Oauth2\\Oauth2::authenticateUser"
        );
    }

    /**
     * Authenticates the user by Bearer token of OAuth2.
     * @param LoginAuthenticationHook $hook
     */
    public static function authenticateUser($hook)
    {
        if (isset($_SERVER['HTTP_AUTHORIZATION'])
                && preg_match("/Bearer\s+(.+)/", $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            $token = $matches[1];
            if ($token) {
                $token = Accesstoken::get($token);
                if ($token['expiration_time'] >= time()) {
                    $login = \Login::get($token['user_id']);
                    $hook->setLogin($login);
                    $hook->authenticateLogin(true);
                } else {
                    //request refresh_token
                }
            }
        }
    }
}