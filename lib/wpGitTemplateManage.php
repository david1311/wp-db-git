<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 7/04/18
 * Time: 21:22
 */

namespace WordpressDB;

class wpGitTemplateManage {
    private $values;

    public function __construct($values)
    {
        $this->values = $values;

        $this->getTemplate();
    }

    public function getTemplate() {

    }

    private function getInsertions() {
        return $this->values['insertions'];
    }

    private function getDeletions() {
        return $this->values['deletions'];
    }

}


