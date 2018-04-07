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

    private function compareWordpressDBVersions(array $array1, array $array2, array $keysToCompare = null) {
        $serialize = function (&$item, $idx, $keysToCompare) {
            if (is_array($item) && $keysToCompare) {
                $a = array();
                foreach ($keysToCompare as $k) {
                    if (array_key_exists($k, $item)) {
                        $a[$k] = $item[$k];
                    }
                }
                $item = $a;
            }
            $item = serialize($item);
        };
        $deserialize = function (&$item) {
            $item = unserialize($item);
        };
        array_walk($array1, $serialize, $keysToCompare);
        array_walk($array2, $serialize, $keysToCompare);
        // Items that are in the original array but not the new one
        $deletions = array_diff($array1, $array2);
        $insertions = array_diff($array2, $array1);
        array_walk($insertions, $deserialize);
        array_walk($deletions, $deserialize);
        return array('insertions' => $insertions, 'deletions' => $deletions);
    }

    private function getValueVersionsDB() {
        $first_version = $this->convertWordpressDbOjectsToArray(json_decode(file_get_contents(plugin_dir_path(__FILE__) . 'backups/datebase_test.json')));
        return $this->compareWordpressDBVersions( $this->getCurrentDBStatus(), $first_version);
    }

    public function wordpressGitPage()
    {
        $manage = new wpGitTemplateManage($this->getValueVersionsDB());

        return $manage;
    }

    public function setMenuManageDB()
    {
        add_menu_page('wpGitDB', 'wpGitDB', 'manage_options', 'wp-git-db', [$this, 'wordpressGitPage'], plugins_url('myplugin/images/icon.png'), 6);
    }
}

