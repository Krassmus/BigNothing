<?php

/**
 * Here you can put some databases-information. You must have one "master" database
 * and can have as many slaves as you want. You can use MySQL, PostgreSQL and SQLite
 * database types. Here come the examples:
 *
 * $GLOBALS['DATABASE'] = array(
        'master' => array(
            'type' => "mysql",
            'name' => "bignothing_bn1",
            'user' => "root",
            'pass' => ""
        )
    );
 *
 * or
 *
 * $GLOBALS['DATABASE'] = array(
        'master' => array(
            'type' => "sqlite",
            'path' => "/home/userx/bignothing_db/bignothing.sqlite"
        )
    );
 */


$GLOBALS['DATABASE'] = array(
    'master' => array(
        'type' => "mysql",
        'name' => "bignothing",
        'user' => "root",
        'pass' => ""
    )
);

