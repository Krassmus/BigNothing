<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

if (!function_exists("hashPassword")) {
    function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, array(
            'cost' => 12
        ));
    }
}
if (!function_exists("verifyPassword")) {
    function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}

function escapeHtml($text) {
    return htmlentities($text, ENT_COMPAT, "UTF-8");
}

function isAjax() {
    return (
        isset($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
    );
}

function redirect($route) {
    ob_clean();
    header("Location: ".URL::create($route));
    exit;
}

//setup global variable URL as the base-URL of bignothing
$GLOBALS['URL'] = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$GLOBALS['URL'] .= "://".$_SERVER['SERVER_NAME'];
if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' && $_SERVER['SERVER_PORT'] != 443) ||
    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'on' && $_SERVER['SERVER_PORT'] != 80)) {
    $GLOBALS['URL'] .= ':'.$_SERVER['SERVER_PORT'];
}
$GLOBALS['URL'] .= dirname($_SERVER['PHP_SELF'])."/";