<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

class Mapper {

    protected static $tableName = null;
    protected static $tableData = array(
        /*
        'primaryKeys' => array("xyz"),
        'fields' => array(
            'fieldname' => array(
                'type' => "integer",
                'null' => true,
                'default' => null|string
            )
        ),
        'foreignKeys' => array(
            array(
                'fields' => array("field1", "field2"),
                'table' => "tablename",
                'foreignfields' => array("field3", "field4")
            )
        )
        */
    );

    protected $data = array();
    protected $dbData = array();

    static protected function fetchTableData() {
        $database = $GLOBALS['databaseType'];
        $db = DBManager::getInstance();
        $tableName = self::$tableName;
        switch ($database) {
            case "mysql":
                $query = "SHOW COLUMNS FROM `".$tableName."` ";
                $data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
                foreach ($data as $row) {
                    self::$tableData[$tableName]['fields'][$data['Field']] = array(
                        'type' => $data['Field'],
                        'null' => $data['Null'] === "YES" ? true : false,
                        'default' => $data['Default']
                    );
                    if ($data['Key'] === "PRI") {
                        self::$tableData[$tableName]['primaryKeys'][] = $data['Field'];
                    }
                }
                break;
            case "sqlite";
                break;
            case "postgre":
                break;
        }
    }

    public function __construct($id = null) {
        if (!count(self::$tableData)) {
            self::fetchTableData();
        }
    }
}