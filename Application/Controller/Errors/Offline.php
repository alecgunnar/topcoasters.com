<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Errors_Offline extends \Maverick\Lib\Controller {
    public function main($msg='') {
        Output::setPageTitle('Top Coasters is Offline');

        $this->setVariable('message', $msg);
    }
}