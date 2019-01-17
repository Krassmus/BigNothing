<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

class ORMapper implements ArrayAccess, Iterator {

    protected static $db = null;
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
    protected $ormapper_db_data = array();
    protected $ormapper_iterator = 0;

    static public function ormapper_setPDO(PDO $pdo)
    {
        self::$ormapper_db = $pdo;
    }

    static protected function ormapper_fetchTableData() {
        $database = $GLOBALS['databaseType'];
        $db = self::$ormapper_db;
        $tableName = self::$tableName;
        switch ($database) {
            case "mysql":
                $query = "SHOW COLUMNS FROM `".$tableName."` ";
                $data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
                foreach ($data as $row) {
                    self::$tableData[$tableName]['fields'][$row['Field']] = array(
                        'type' => $row['Field'],
                        'null' => $row['Null'] === "YES" ? true : false,
                        'default' => $data['Default']
                    );
                    if ($data['Key'] === "PRI") {
                        self::$tableData[$tableName]['primaryKeys'][] = $row['Field'];
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
            self::ormapper_fetchTableData();
        }

    }

    /**
     * Fetches data from the database or initializes the values.
     */
    public function ormapper_fetch()
    {
        if ($this->ormapper_pk) {

        } else {
            
        }
    }

    public function save()
    {

    }



    public function offsetExists($offset) {
        return isset(self::$tableData['fields'][$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        $this->data[$offset] = null;
    }

    public function current () {
        return $this->data[$this->ormapper_iterator];
    }
    public function key () {
        return $this->ormapper_iterator;
    }
    public function next () {}
    public function rewind () {}

    public function valid () {
        return isset($this->data[$this->ormapper_iterator]);
    }
}