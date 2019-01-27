<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

require_once __DIR__."/Controller.php";

abstract class AuthenticatedController extends Controller {

    /**
     * Lets the controller perform an action. But unlike the parent-method from Controller
     * here we want the user to be logged in. If the user is not logged in, he/she will
     * get to the login-screen and be redirected to this controller/controlleraction after the
     * successful login.
     * This method expects the action to echo output as a sideeffect.
     * In the action you can write $this->variable to fill the view with variables. To render the view
     * corresponding to the action just call $this->renderView() in the action-method. The
     * controller will then try to render the view that should be placed in
     * ./views/$controllername/$action.php with . as the root-directory of the module.
     * @param string $action : name of the action the controller should perform. The method for the action is $action."Action"
     * @param array $vars : an associative array with variables in the form array('user' => $user)
     */
    public function performAction($action, $vars = array()) {
        if (!isset($_SESSION) || !$_SESSION['currentLoginId']) {
            throw new NotLoggedInException();
        }
        parent::performAction($action, $vars);
    }


}