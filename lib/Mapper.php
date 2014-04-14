<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

class Mapper {

    protected static $tableName = null;
    protected static $tableData = array();

    protected $data = array();
    protected $dbData = array();

    static protected function fetchTableData() {

    }

    public function __construct($id = null) {
        if (!count(self::$tableData)) {
            self::fetchTableData();
        }
    }
}