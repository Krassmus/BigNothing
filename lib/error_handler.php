<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

require_once __DIR__."/exceptions/NotLoggedInException.php";

set_exception_handler(function ($exception) {
    if (is_a($exception, "NotLoggedInException")) {
        echo Template::summon(__DIR__."/../templates/login.php")->render();
        die();
    }
});