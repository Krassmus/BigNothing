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

    protected $orm_data = array(); //the data of this object
    protected $orm_db_data = array(); //the data that is considered to be in the database
    protected $orm_iterator = 0;
    protected $orm_object_is_new = false; //indicates if this object is already in database (false) or a new object (true).

    /**
     * Injects a PDO object into the ORM class so that all ORM classes have access to the database.
     * @param PDO $pdo
     */
    static public function orm_setPDO(PDO $pdo) {
        self::$orm_db = $pdo;
    }

    /**
     * The static setup method for each ORM class. Each ORM subclass needs to override this
     * method and say parent::orm_setup($configs); at the end so that the class is
     * configured. Each class needs to at least declare a database-table upon that it
     * operates.
     * @param array $configs
     */
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

    /**
     * Gets an array with all rows that are part of the primary key.
     * @return array of strings
     */
    static public function orm_getPK() {
        $tableName = self::orm_getTableName();
        return self::$orm_tableData[$tableName]['primaryKeys'];
    }

    /**
     * Finds all objects matching the sql-condition and parameters.
     *
     * For example Login::manyBy("email = ?", array("me@somemail.com")); finds all logins with the given email adress.
     *
     * You can also use the shorthand syntax Login::manyBy("email", "me@somemail");
     *
     * @param string $sql : an sql where condition like 'username = ?' or "username = :uname" in prepared statements
     * @param mixed $params : associative array of parameters. In short syntax this is not an array, but will be used as the first ?-parameter
     * @return array of called class-objects
     * @throws Exception if there is an sql-error.
     */
    static public function manyBy($sql, $params = array()) {
        self::orm_fetchTableData();
        $tableName = self::orm_getTableName();
        $class = get_called_class();
        if (!is_array($params)) {
            $params = array($params);
        }
        if (isset(self::$orm_tableData[$tableName]['fields'][$sql])) {
            //this is the short syntax. We transform it into the real syntax
            $sql = "`".$sql."` = ? ";
        }
        $sql = "SELECT `".$tableName."`.* FROM `".$tableName."` WHERE ".$sql;
        $statement = self::$orm_db->prepare($sql);
        $statement->execute($params);
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        $result = array();
        foreach ($data as $d) {
            $result[] = $class::getExisting($d);
        }
        return $result;
    }

    /**
     * Finds one object matching the sql-condition and parameters.
     *
     * For example Login::oneBy("email = ?", array("me@somemail.com")); finds one logins with the given email adress.
     *
     * You can also use the shorthand syntax Login::oneBy("email", "me@somemail");
     *
     * @param string $sql : an sql where condition like 'username = ?' or "username = :uname" in prepared statements
     * @param mixed $params : associative array of parameters. In short syntax this is not an array, but will be used as the first ?-parameter
     * @return null|ORM : object of called class or null if no object fits the sql-clause.
     * @throws Exception if there is an sql-error.
     */
    static public function oneBy($sql, $params = array()) {
        self::orm_fetchTableData();
        $tableName = self::orm_getTableName();
        $class = get_called_class();
        if (isset(self::$orm_tableData[$tableName]['fields'][$sql])) {
            //this is the short syntax. We transform it into the real syntax
            $sql = "`".$sql."` = ? ";
        }
        if (!is_array($params)) {
            $params = array($params);
        }

        $sql = "SELECT `".$tableName."`.* FROM `".$tableName."` WHERE ".$sql." LIMIT 1";
        $statement = self::$orm_db->prepare($sql);
        $statement->execute($params);
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        return $data ? $class::getExisting($data) : null;
    }

    /**
     * Fetches the important information about the database-table.
     * @throws Exception if the table has no primary key.
     */
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
        if (!isset(self::$orm_tableData[$tableName]['primaryKeys']) || !count(self::$orm_tableData[$tableName]['primaryKeys'])) {
            throw new Exception("DB-Table '$tableName' has no primary key.");
        }
    }

    /**
     * Static constructor method for this who like to get an object in a static way.
     * @param mixed $id : null for new objects, string or integer for objects or array if the primary key has more than one row.
     * @return object of this called class.
     */
    static public function get($id = null) {
        $class = get_called_class();
        $object = new $class($id);
        return $object;
    }

    /**
     * Creates an object of this class with the given data of the associative array. The data have to be
     * corresponding to an existing database-object.
     * @param array $data : associative array like from ->fetch(PDO::FETCH_ASSOC)
     * @return ORM : object of called class.
     */
    static public function getExisting($data = array()) {
        $class = get_called_class();
        $object = new $class();
        foreach ($data as $index => $value) {
            $object[$index] = $value;
        }
        $object->orm_resetDBData();
        return $object;
    }

    /**
     * Constructor to initiate new or existing objects of this class.
     * @param mixed $id : null for new objects, string or integer for objects or array if the primary key has more than one row.
     * @throws Exception if class has no correct orm_setup method like a missing table attribute.
     */
    public function __construct($id = null) {
        self::orm_fetchTableData();
        $pk = self::orm_getPK();
        if (is_string($id) && strpos($id, "-") !== false) {
            $id = explode("-", $id);
        }
        if (is_array($id)) {
            foreach ($id as $index => $value) {
                if (is_numeric($index)) {
                    $this[$pk[$index]] = $value;
                } else {
                    $this[$index] = $value;
                }
            }
        } else {
            if (count($pk) === 1) {
                $this[$pk[0]] = $id;
            }
        }
        $this->orm_fetch();
    }

    /**
     * Fetches data from the database or initializes the values.
     * @param bool $restore : if true all data in $this->orm_data will be overwritten by the database
     */
    public function orm_fetch($restore = false)
    {
        $pk = self::orm_getPK();
        $tableName = self::orm_getTableName();
        foreach ($pk as $index => $pk_row) {
            if ($this->orm_data[$pk_row] === null) {
                $is_new = true;
                break;
            }
        }
        if (!$this->orm_object_is_new) {
            $sql = "SELECT * 
                    FROM `" . $tableName . "`
                    WHERE ";
            $params = array();
            foreach ($pk as $index => $pk_row) {
                if ($index > 0) {
                    $sql .= "AND ";
                }
                $sql .= "`" . $pk_row . "` = ? ";
                $params[] = $this->orm_data[$pk_row];
            }
            $statement = self::$orm_db->prepare($sql);
            $statement->execute($params);
            $this->orm_db_data = $statement->fetch(PDO::FETCH_ASSOC);

            if ($restore) {
                $this->orm_data = $this->orm_db_data;
            } else {
                foreach ((array) $this->orm_db_data as $key => $value) {
                    if (!isset($this[$key])) {
                        $this[$key] = $value;
                    }
                }
            }
        }
        if (!$this->orm_db_data) {
            $this->orm_object_is_new = true;
            foreach (self::$orm_tableData[$tableName]['fields'] as $field => $fielddata) {
                $this->orm_db_data[$field] = null;
            }
        }
        //setting the default values for a new object
        foreach (self::$orm_tableData[$tableName]['fields'] as $field => $fielddata) {
            if (!isset($this->orm_data[$field])) {
                $this[$field] = isset($fielddata['default']) ? $fielddata['default'] : null;
            }
        }
    }

    /**
     * Resets the internal $orm_db_data data with the internal $orm_data variable.
     * Do this only if you are sure that you know what's in the database.
     */
    public function orm_resetDBData() {
        $this->orm_db_data = $this->orm_data;
        $this->orm_object_is_new = false;
    }

    /**
     * The main method to store the data of this object to database.
     */
    public function store()
    {
        $pk = self::orm_getPK();
        $is_new = $this->orm_object_is_new;
        $tableName = self::orm_getTableName();
        if ($is_new) {
            if ((count($pk) === 1) && ($this[$pk[0]] === null)) {
                //set the primary key if necessary
                if (in_array(self::$orm_tableData[$tableName]['fields'][$pk[0]]['type'], array("varchar", "char"))) {
                    $id = substr(md5(uniqid()), 0, self::$orm_tableData[$tableName]['fields'][$pk[0]]['maxlength']);
                    $this[$pk[0]] = $id;
                }
            }
            $sql = "INSERT INTO `".$tableName."`
                    SET ";
            $params = array();
            $i = 0;
            foreach (self::$orm_tableData[$tableName]['fields'] as $field => $fielddata) {
                if ($i > 0) {
                    $sql .= ", ";
                }
                $sql .= "`".$field."` = :".md5($field)." ";
                $params[md5($field)] = $this[$field];
                $i++;
            }
            $statement = self::$orm_db->prepare($sql);
            $statement->execute($params);
            if ($this[$pk[0]] === null) {
                $this[$pk[0]] = $statement->get_last_insert_id;
            }
            $this->orm_object_is_new = false;
        } else {
            $sql = "UPDATE `".$tableName."` SET ";
            $i = 0;
            $params = array();
            foreach (self::$orm_tableData[$tableName]['fields'] as $field => $fielddata) {
                if (!in_array($field, $pk) && ($this->orm_data[$field] !== $this->orm_db_data[$field])) {
                    if ($i > 0) {
                        $sql .= ", ";
                    }
                    $sql .= "`" . $field . "` = :" . md5($field);
                    $params[md5($field)] = $this[$field];
                    $i++;
                }
            }
            $sql = "WHERE ";
            foreach ($pk as $i => $pkrow) {
                if ($i > 0) {
                    $sql .= "AND ";
                }
                $sql .= "`".$pkrow."` = :".md5($pkrow)." ";
                $params[md5($pkrow)] = $this[$pkrow];
            }
        }

    }

    public function getId() {
        $id = array();
        foreach (self::orm_getPK() as $pk_row) {
            $id[] = $this[$pk_row];
        }
        return implode("-", $id);
    }

    /**
     * Fetches all data from the database and overwrites the existing data in this object.
     */
    public function restore() {
        $this->orm_fetch(true);
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

    public function asArray() {
        return $this->orm_data;
    }
}