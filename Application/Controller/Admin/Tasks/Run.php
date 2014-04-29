<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Admin_Tasks_Run extends \Maverick\Lib\Controller {
    public function main($toRun='') {
        $tasksFile = APPLICATION_PATH . 'Task/' . $toRun . PHP_EXT;

        if(file_exists($tasksFile)) {
            $class = '\Application\Task\\' . $toRun;
            $class::run();

            \Maverick\Lib\Http::location('/admin/tasks', 'The task has been run.');
        }

        \Application\Lib\Utility::showError('This task does not exist.');
    }
}