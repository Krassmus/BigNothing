<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

require_once __DIR__."/exceptions/NotLoggedInException.php";

set_exception_handler(function ($exception) {
    if (is_a($exception, "NotLoggedInException")) {
        header("Location: " . URL::create("authentication/login"));
    } elseif (is_a($exception, "PageNotFoundException")) {
        http_response_code(404);
        ob_clean();
        if (isAjax()) {
            echo "404 - This page does not exist.";
        } else {
            echo Template::summon(__DIR__ . "/../templates/pagenotfound.php")
                ->with(Template::summon(__DIR__ . "/../templates/layout.php"))
                ->with("exception", $exception)
                ->render();
        }
        die();
    } else {
        ob_clean();
        echo Template::summon(__DIR__."/../templates/exception.php")
            ->with(Template::summon(__DIR__."/../templates/layout.php"))
            ->with("exception", $exception)
            ->render();
        die();
    }
});