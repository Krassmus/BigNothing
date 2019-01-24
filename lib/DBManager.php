<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */


/**
 * Class DBManager the manager of the main database connections. Holds PDO-objects as singletons.
 */
class DBManager {

    static protected $slaveConnections = array();
    static protected $masterConnection = null;

    /**
     * Returns a PDO-object with the given connection or null if it failed.
     * @param string|null $connectionName : name of the connection or null if we want the master.
     * @return PDO|null
     */
    static public function getInstance($connectionName = null) {
        if ($connectionName === null) {
            if (!self::$masterConnection) {
                self::$masterConnection = self::openConnectionFromConfig($connectionName);
            }
            return self::$masterConnection;
        } else {
            if (!isset(self::$slaveConnections[$connectionName])) {
                self::$slaveConnections[$connectionName] = self::openConnectionFromConfig($connectionName);
            }
            if (!isset(self::$slaveConnections[$connectionName])) {
                return self::getInstance();
            }
            return self::$slaveConnections[$connectionName];
        }
    }

    static protected function openConnectionFromConfig($connectionName = "master") {
        if (!isset($GLOBALS['DATABASE'][$connectionName])) {
            $connectionName = "master";
        }
        $connection = null;
        switch ($GLOBALS['DATABASE'][$connectionName]['type']) {
            case "mysql":
                $connection = new PDO(
                    "mysql:dbname=".$GLOBALS['DATABASE'][$connectionName]['name'],
                    $GLOBALS['DATABASE'][$connectionName]['user'],
                    $GLOBALS['DATABASE'][$connectionName]['pass']
                );
                break;
            case "sqlite":
                $connection = new PDO(
                    "sqlite:".$GLOBALS['DATABASE'][$connectionName]['path']
                );
                break;
            case "postgre":
            case "postgres":
            case "postgresql":
            case "pgsql":
                $connection = new PDO(
                    "pgsql:dbname=".$GLOBALS['DATABASE'][$connectionName]['name'].";user=".$GLOBALS['DATABASE'][$connectionName]['user'].";password".$GLOBALS['DATABASE'][$connectionName]['pass']
                );
                break;
        }
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    }
}