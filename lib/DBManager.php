<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

class DBManager {

    static protected $slaveConnections = array();
    static protected $masterConnection = null;

    static public function getInstance($connectionName = null) {
        if ($connectionName === null) {
            if (!self::$masterConnection) {
                self::$masterConnection = new PDO(
                    $GLOBALS['databaseType'].":dbname=".$GLOBALS['databaseName'],
                    $GLOBALS['databaseUser'],
                    $GLOBALS['databasePass']
                );
            }
            return self::$masterConnection;
        } else {
            if (!isset(self::$slaveConnections[$connectionName])) {

            }
            return self::$slaveConnections[$connectionName];
        }
    }
}