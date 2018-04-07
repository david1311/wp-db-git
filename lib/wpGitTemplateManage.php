<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 7/04/18
 * Time: 21:22
 */

namespace WordpressDB;


class wpGitBackupDB
{
    public static function getBaseJsonDB($save = false) {
        global $wpdb;

        $tables = $wpdb->get_results('SHOW TABLES');
        $wordpressDB = [];
        foreach ($tables as $table)
        {
            $array = (array)$table;
            $tableName = array_shift($array);

            $wordpressDB[$tableName] = $wpdb->get_results('SELECT * FROM ' . $tableName);
        }

        return $save == true ? self::saveJsonFileDB($wordpressDB) : $wordpressDB;
    }

    private static function saveJsonFileDB($wordpressDB) {
        $fp = fopen(plugin_dir_path(__FILE__) . "backups/firstVersion.json", 'w');
        fwrite($fp, json_encode($wordpressDB));
        fclose($fp);
    }
}