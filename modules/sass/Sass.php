<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Sass;

class Sass extends \Module {

    public function __construct() {
        $hook = \HookCenter::run("\\Sass\\SassHook");
        foreach ($hook->getActiveSassPackages() as $package) {
            \Layout::addStyle(\URL::create("sass/package/provider/" . $package . ".css"));
        }
    }
}