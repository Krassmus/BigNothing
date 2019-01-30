<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

$GLOBALS['DATABASE'] = array(
    'master' => array(
        'type' => "mysql",
        'name' => "bignothing",
        'user' => "root",
        'pass' => ""
    )
);


//add an email-adress here that catches all support-requests by the user.
$GLOBALS['SUPPORT_MAIL'] = null;

$GLOBALS['PATH_DATA'] = realpath(__DIR__."/../data");