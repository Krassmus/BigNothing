<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

abstract class Controller {

    protected $currentAction = null;
    protected $currentLayout = null;

    /**
     * Lets the controller perform an action. This expects the action to echo output as a sideeffect.
     * In the action you can write $this->variable to fill the view with variables. To render the view
     * corresponding to the action just call $this->renderView() in the action-method. The
     * controller will then try to render the view that should be placed in
     * ./views/$controllername/$action.php with . as the root-directory of the module.
     * @param string $action : name of the action the controller should perform. The method for the action is $action."Action"
     * @param array $vars : an associative array with variables in the form array('user' => $user)
     */
    public function performAction($action, $vars = array()) {
        $this->currentAction = $action;
        $this->currentLayout = Template::summon(__DIR__."/../templates/layout.php");
        $methodName = $action."Action";
        call_user_func_array(array($this, $methodName), $vars);
    }

    public function renderView($action = null) {
        $reflection = new ReflectionClass(get_class($this));
        $action || $action = $this->currentAction;
        $controller = $reflection->getShortName();
        $viewFile = dirname($reflection->getFileName())."/../views/".strtolower($controller)."/".$this->currentAction.".php";
        if (file_exists($viewFile)) {
            $view = Template::summon($viewFile);
            if ($this->currentLayout) {
                $view->with($this->currentLayout);
            }
            $classVars = get_class_vars(get_class($this));
            $objectVars = get_object_vars($this);
            $view->with(array_diff_key($objectVars, $classVars));

            echo $view->render();
        }

    }
}