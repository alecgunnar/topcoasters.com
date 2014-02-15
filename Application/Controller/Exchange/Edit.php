<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Exchange_Edit extends \Maverick\Lib\Controller {
    public function main($fileId=0) {
        $exchangeFiles = new \Application\Service\Exchange;
        $file          = $exchangeFiles->get(intval($fileId));

        if(!$file) {
            \Maverick\Lib\Router::throw404();
        }

        Output::setPageTitle('Editing ' . $file->getName());

        $this->setVariable('file', $file);

        $categories = \Maverick\Maverick::getConfig('Exchange')->getAsArray('categories');

        $excahngeFileUploadForm = new \Application\Form\ExchangeFileUpload($categories, null, $file);

        if($excahngeFileUploadForm->isSubmissionValid()) {
            if(($newFile = $excahngeFileUploadForm->submit())) {
                \Maverick\Lib\Http::location($newFile->getUrl(), 'Your changes have been saved.');
            }
        }

        $this->setVariable('form', $excahngeFileUploadForm);
    }
}