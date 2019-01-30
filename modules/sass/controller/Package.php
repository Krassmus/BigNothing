<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Sass;

require __DIR__."/../vendor/scssphp/scss.inc.php";

class Package extends \Controller {

    public function providerAction($package) {
        if (strrpos($package, ".") !== false) {
            $package = substr($package, 0, strrpos($package, "."));
        }
        $last_update = 0;
        $hook = \HookCenter::run("\\Sass\\SassHook");
        foreach ($hook->getSassPackageFiles($package) as $file) {
            if (file_exists($file)) {
                $last_update = max($last_update, filemtime($file));
            }
        }
        $sass_file = $GLOBALS['PATH_DATA'] . "/cache/sass-" . $package . "-" . $last_update . ".css";
        if (file_exists($sass_file)) {
            //we already have that file in cache
            echo file_get_contents($sass_file);
            return;
        } else {
            //we need to recompile the package
            $concat_files = "";
            foreach ($hook->getSassPackageFiles($package) as $file) {
                if (file_exists($file)) {
                    $concat_files .= file_get_contents($file);
                }
            }
            $scss = new \Leafo\ScssPhp\Compiler();
            $scss->setFormatter("Leafo\ScssPhp\Formatter\Compressed");
            try {
                $output = $scss->compile($concat_files);

                //remove old package files
                foreach (scandir($GLOBALS['PATH_DATA'] . "/cache") as $file) {
                    if (strpos($file, "sass-" . $package) === 0) {
                        @unlink($GLOBALS['PATH_DATA'] . "/cache/" . $file);
                    }
                }

                file_put_contents($sass_file, $output);
            } catch (Exception $e) {
                //save message to database?
                $output = "/* SASS-error by parser: ".$e->getMessage()."*/\n\n";

                foreach (scandir($GLOBALS['PATH_DATA'] . "/cache") as $file) {
                    //and output the old css file we have in cache
                    if (strpos($file, "sass-" . $package) === 0) {
                        $output .= file_get_contents($GLOBALS['PATH_DATA'] . "/cache/".$file);
                        break;
                    }
                }
            }
            echo $output;
        }
    }
}