<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */


/**
 * Class ORM - the object relational mapper we use here.
 *
 * An object-relational-mapper is a class which is used to access database objects like lines in a table.
 * You can create a subclass like this
 *
 *     class Login extends ORM {
 *         static protected function orm_setup($configs = array()) {
 *             $configs['table'] = "logins";
 *             parent::orm_setup($configs);
 *         }
 *     }
 *
 * After that you have your own class to access date-lines in the 'logins' table. You can then check
 * objects like this
 *
 *     $login = new Login("my_username");
 *     if ($login['password_hash'] == "") {
 *         //do something
 *     }
 *
 * You can access the rows of the db-line like an associative array. Also you can alter the attributes and save
 * the data back to the database ...
 *
 *     $login = new Login("my_username");
 *     $login['password_hash'] = md5("unsafe password");
 *     $login->store();
 *
 * After that you have changed the password of 'my_username' to 'unsafe password'.
 *
 * When you use ORM in another project different to BigNothing you must setup the ORM-class and inject a
 * PDO object (PDO is the PHP class) to ORM so that it can access the database. Do it like this ...
 *
 *     ORM::orm_setPDO(new PDO("mysql:dbname=myproject;host=localhost", "username", "mysql-password"));
 *
 * After that you can use ORM for your own project.
 *
 */
class ORM implements ArrayAccess, Iterator {

    protected static $orm_db = null; //one singleton PDO object to access the database
    protected static $orm_configs = array(); //configs of the classes
    protected static $orm_tableData = array( //data of the database tables $orm_tableData[$tableName]
        /*
        'logins' => array(
            'primaryKeys' => array("xyz"),
            'fields' => array(
                'fieldname' => array(
                    'type' => "integer",
                    'null' => true,
                    'default' => null|string
                )
            )
        )
        */
    );

    protected $orm_data = array();
    protected $orm_db_data = array();
    protected $orm_iterator = 0;

    static public function orm_setPDO(PDO $pdo) {
        self::$orm_db = $pdo;
    }

    static protected function orm_setup($configs = array()) {
        $class = get_called_class();
        self::$orm_configs[$class] = $configs;
    }

    /**
     * Returns the db-table name of the called class
     * @return string
     */
    static public function orm_getTableName() {
        $class = get_called_class();
        if (!isset(self::$orm_configs[$class])) {
            static::orm_setup();
        }
        return self::$orm_configs[$class]['table'];
    }

    static public function orm_fetchTableData() {
        $db = self::$orm_db;
        $database = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
        $tableName = self::orm_getTableName();
        if (!isset(self::$orm_tableData[$tableName])) {
            switch ($database) {
                case "mysql":
                    $query = "SHOW COLUMNS FROM `" . $tableName . "` ";
                    $data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($data as $row) {
                        $success = preg_match("/(\w+)\((\d+)\)/", $row['Type'], $matches);
                        if ($success) {
                            $type = $matches[1];
                            $maxlength = $matches[2];
                        } else {
                            $type = $row['Type'];
                            $maxlength = null;
                        }
                        self::$orm_tableData[$tableName]['fields'][$row['Field']] = array(
                            'type' => $type,
                            'maxlength' => (int) $maxlength,
                            'null' => $row['Null'] === "YES" ? true : false,
                            'default' => $row['Default']
                        );
                        if ($row['Key'] === "PRI") {
                            self::$orm_tableData[$tableName]['primaryKeys'][] = $row['Field'];
                        }
                    }
                    break;
                case "sqlite";
                    break;
                case "postgre":
                    break;
            }
        }
    }

    public function __construct($id = null) {
        if (!count(self::$orm_tableData)) {
            self::orm_fetchTableData();
        }

    }

    /**
     * Fetches data from the database or initializes the values.
     */
    public function orm_fetch()
    {
        if ($this->orm_pk) {

        } else {
            $tableName = self::orm_getTableName();
            throw new Exception("DB-Table '$tableName' has no primary key.");
        }
    }

    /**
     * The main method to store the data of this object to database.
     */
    public function store()
    {

    }



    public function offsetExists($offset) {
        return isset(self::$orm_tableData['fields'][$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->orm_data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->orm_data[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        $this->orm_data[$offset] = null;
    }

    public function current () {
        return $this->orm_data[$this->orm_iterator];
    }
    public function key () {
        return $this->orm_iterator;
    }
    public function next () {}
    public function rewind () {}

    public function valid () {
        return isset($this->orm_data[$this->orm_iterator]);
    }
}