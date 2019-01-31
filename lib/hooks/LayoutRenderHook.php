<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

class LayoutRenderHook implements Hook {

    protected $layout = null;

    static public function getHookDescription() {
        return "This hook gets called when the layout will be rendered.";
    }

    public function __construct(Template $layout) {
        $this->layout = $layout;
    }

    public function getLayout() {
        return $this->layout;
    }

    public function isStandardLayout() {
        $template_path = $this->layout->getTemplatePath();
        return (realpath(__DIR__."/../../templates/layout.php") === $template_path);
    }
}