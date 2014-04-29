<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Admin_Tasks extends \Maverick\Lib\Controller {
    public function main() {
        Output::setPageTitle('Available Tasks');

        $tasks    = array();
        $tasksDir = APPLICATION_PATH . 'Task/';
        $openDir  = opendir($tasksDir);

        while(($file = readdir($openDir)) !== false) {
            if($file[0] != '.') {
                $expName = explode('.', $file);

                if($expName[0] != 'Standard') {
                    $className = '\Application\Task\\' . $expName[0];
                    $tasks[]   = array_merge(array('key' => $expName[0]), $className::getData());
                }
            }
        }
  
        $this->setVariable('tasks', $tasks);
    }
}