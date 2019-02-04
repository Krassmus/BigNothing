<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Sass;

class Sass extends \Module {

    protected $hook = null;

    public static function setUpModuleHooks() {
        \HookCenter::register(
            "LayoutRenderHook",
            "\\Sass\\Sass::addCSSFiles"
        );
        \HookCenter::register(
            "\\Sass\\SassHook",
            function ($hook) {
                $hook->addSassFile(__DIR__."/../../public/assets/stylesheets/bignothing.scss", "global");
            }
        );
    }

    public function addCSSFiles() {
        $sasshook = \HookCenter::run("\\Sass\\SassHook");
        foreach ($sasshook->getActiveSassPackages() as $package) {
            if (count($sasshook->getSassPackageFiles($package))) {
                \Layout::addStyle(\URL::create("sass/package/provider/" . $package . ".css"));
            }
        }
    }
}