<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Scss;

require __DIR__."/../vendor/scssphp/scss.inc.php";

class Package extends \Controller {

    public function providerAction($package) {
        header("Content-Type: text/css");
        if (strrpos($package, ".") !== false) {
            $package = substr($package, 0, strrpos($package, "."));
        }
        $last_update = 0;
        $broken_files = "";
        $hook = \HookCenter::run("\\Scss\\ScssHook");
        foreach ((array) $hook->getScssPackageFiles($package) as $file) {
            if (file_exists($file)) {
                $last_update = max($last_update, filemtime($file));
            } else {
                $broken_files .= $file." | ";
            }
        }
        $scss_file = $GLOBALS['PATH_DATA'] . "/cache/scss-" . $package . "-" . $last_update . ".css";
        if (file_exists($scss_file)) {
            //we already have that file in cache
            if ($broken_files) {
                echo "/* Broken SCCS files: " . $broken_files . " */\n";
            }
            echo file_get_contents($scss_file);
            return;
        } else {
            //we need to recompile the package
            $concat_files = "";
            foreach ($hook->getScssPackageFiles($package) as $file) {
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
                    if (strpos($file, "scss-" . $package) === 0) {
                        @unlink($GLOBALS['PATH_DATA'] . "/cache/" . $file);
                    }
                }

                $success = @file_put_contents($scss_file, $output);
            } catch (Exception $e) {
                //save message to database?
                $output = "/* SCSS-error by parser: ".$e->getMessage()."*/\n\n";

                foreach (scandir($GLOBALS['PATH_DATA'] . "/cache") as $file) {
                    //and output the old css file we have in cache
                    if (strpos($file, "scss-" . $package) === 0) {
                        $output .= file_get_contents($GLOBALS['PATH_DATA'] . "/cache/".$file);
                        break;
                    }
                }
            }
            if ($broken_files) {
                echo "/* Broken SCCS files: " . $broken_files . " */\n";
            }
            echo $output;
        }
    }
}