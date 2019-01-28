# Config-Files in BigNothing

Like in many web-applications we need some configurations. Where is the database, what is the support-mail-adress?

In this folder you have one main PHP-file `default_configs.php` which has the main configurations in it. The database connection is the most important one for example.

If you want to set up the database connection for your server **please do not change the `default_configs.php` file, but create a new file with the name `custom_configs.php`. You only insert all configurations that differ to the given config in the `default_configs.php` file.

So in most case the `custom_configs.php` file consists only of a PHP-block with the configuration of the database like this

    <?php
    $GLOBALS['DATABASE'] = array(
        'master' => array(
            'type' => "mysql",
            'name' => "bignothing",
            'user' => "db_username",
            'pass' => "db_password"
        )
    );
    
    