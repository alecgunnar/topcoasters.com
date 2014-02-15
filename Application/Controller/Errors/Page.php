<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Errors_Page extends \Maverick\Lib\Controller {
    public function main($msg='') {
        Output::setPageTitle('There was an Error!');

        $msg = urldecode($msg) ?: 'An unknown error has occurred.';

        $this->setVariable('message', $msg);
    }
}