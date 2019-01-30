<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */
namespace Sass;

/**
 * Class SassHook is a hook-class to collect SASS/SCSS files and hand them to a compiler, so
 * that they get displayed in the layout.
 *
 *     \HookCenter::register(
 *         "\\Sass\\SassHook",
 *         "\\Authentication\\Authentication::addSassFile"
 *     );
 *
 * SASS/SCSS-files are here aggregated to packages. We have a global package called "global". If you want
 * to have you own package, see here:
 *
 * A nice package is "admin" that will be set globally for all admins accounts.
 *
 * If you want to create your own package for your module/plugin, go ahead. But keep in mind that you need to activate
 * the package in order to see it. Also you shouldn't activate it globally. In that case please you the "global"
 * package. You should activate your own package only in your Controller.
 *
 * @package Sass
 */
class SassHook implements \Hook {

    protected $sassPackages = array();
    protected $activeSassPackages = array();

    static public function getHookDescription() {
        return "Collects SASS/SCSS files in packages to the compiler.";
    }

    public function __construct() {
        $this->activeSassPackages[] = "global";
    }

    /**
     * Add a file to a given package. The file should be a CSS or SCSS file in absolute path (not URL).
     * @param string $path : absolute path to the file
     * @param string $package : "global" or any other string (without /-slashes). Other packages than "global" should only be activated in a Controller and not globally.
     */
    public function addSassFile($path, $package = "global") {
        $this->sassPackages[$package][] = $path;
    }

    /**
     * The files may be collected. But in order to see your package on the current page you need to activate the package.
     * @param string $package : the name of the package.
     */
    public function activateSassPackage($package) {
        $this->activeSassPackages[] = $package;
    }

    /**
     * Called by the Sass-Controller to hand the active packages to the layout.
     * You do not need to call this method in your module.
     * @return array
     */
    public function getActiveSassPackages() {
        return $this->activeSassPackages;
    }

    /**
     * Gets all files for a certain package. This method is called by the Sass\Package Controller.
     * You do not need to call this method in your module.
     * @param string $package
     * @return array of absolute filenames
     */
    public function getSassPackageFiles($package = "global") {
        return $this->sassPackages[$package];
    }
}