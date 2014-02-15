<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Exchange_File extends \Maverick\Lib\Controller {
    public function main($fileId=0) {
        $exchangeFiles = new \Application\Service\Exchange;
        $file          = $exchangeFiles->get(intval($fileId));

        if(!$file) {
            \Maverick\Lib\Router::throw404();
        }

        Output::setPageTitle($file->getName());

        $this->setVariable('file', $file);
    }
}