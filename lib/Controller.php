<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

abstract class Controller {
    public function renderAction($action, $vars) {
        $methodName = $action."Action";
        call_user_func_array(array($this, $methodName), $vars);
    }
}