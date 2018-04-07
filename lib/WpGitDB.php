<?php

namespace WordpressDB;

class wpGitDB
{
    public function __construct()
    {
        $this->registerCallBacks();
    }

    public function registerCallBacks()
    {
        //add_action('init', [$this, 'setupWordpressDB']);
        add_action('admin_menu', [$this, 'setMenuManageDB']);
    }

    public static function setupWordpressDB()
    {
        wpGitBackupDB::getBaseJsonDB(true);
    }

    private function getCurrentDBStatus()
    {
        return $this->convertWordpressDbOjectsToArray(wpGitBackupDB::getBaseJsonDB());
    }


    private function convertWordpressDbOjectsToArray($obj)
    {
        return json_decode(json_encode($obj), true);
    }

    public function compareWordpressDBVersions($array1, $array2)
    {
        $result = array();
        foreach ($array1 as $key => $value) {
            if (!is_array($array2) || !array_key_exists($key, $array2)) {
                $result[$key] = $value;
                continue;
            }
            if (is_array($value)) {
                $recursiveArrayDiff = $this->compareWordpressDBVersions($value, $array2[$key]);
                if (count($recursiveArrayDiff)) {
                    $result[$key] = $recursiveArrayDiff;
                }
                continue;
            }
            if ($value != $array2[$key]) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    private function getValueVersionsDB() {
        $first_version = $this->convertWordpressDbOjectsToArray(json_decode(file_get_contents(plugin_dir_path(__FILE__) . 'backups/datebase_test.json')));

        return $this->compareWordpressDBVersions( $this->getCurrentDBStatus(), $first_version);
    }

    public function wordpressGitPage()
    {
        var_dump($this->getValueVersionsDB());die();
    }

    public function setMenuManageDB()
    {
        add_menu_page('wpGitDB', 'wpGitDB', 'manage_options', 'wp-git-db', [$this, 'wordpressGitPage'], plugins_url('myplugin/images/icon.png'), 6);
    }
}

