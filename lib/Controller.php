<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

abstract class Controller {

    protected $currentAction = null;
    protected $currentLayout = null;

    public function renderAction($action, $vars) {
        $this->currentAction = $action;
        $this->currentLayout = Template::summon(__DIR__."/../templates/layout.php");
        $methodName = $action."Action";
        call_user_func_array(array($this, $methodName), $vars);
    }

    public function renderView() {
        $reflection = new ReflectionClass(get_class($this));
        $viewFile = dirname($reflection->getFileName())."/../views/".strtolower($reflection->getShortName())."/".$this->currentAction.".php";
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