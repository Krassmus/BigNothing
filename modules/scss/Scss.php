<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Scss;

class Scss extends \Module {

    protected $hook = null;

    public static function setUpModuleHooks() {
        \HookCenter::register(
            "LayoutRenderHook",
            "\\Scss\\Scss::addCSSFiles"
        );
        \HookCenter::register(
            "\\Scss\\ScssHook",
            function ($hook) {
                $hook->addScssFile(__DIR__."/../../public/assets/stylesheets/bignothing.scss", "global");
                $hook->addScssFile(__DIR__."/../../public/assets/stylesheets/form.scss", "global");
            }
        );
    }

    public function addCSSFiles() {
        $scsshook = \HookCenter::run("\\Scss\\ScssHook");
        foreach ($scsshook->getActiveScssPackages() as $package) {
            if (count($scsshook->getScssPackageFiles($package))) {
                \Layout::addStyle(\URL::create("scss/package/provider/" . $package . ".css"));
            }
        }
    }
}