<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Exchange_Upload extends \Maverick\Lib\Controller {
    public function main($category='') {
        $categories = \Maverick\Maverick::getConfig('Exchange')->getAsArray('categories');

        if(!array_key_exists($category, $categories)) {
            \Application\Lib\Utility::showError('The URL you have followed is invalid.');
        }

        Output::setPageTitle('Upload a File');

        $excahngeFileUploadForm = new \Application\Form\ExchangeFileUpload($categories, $category);

        if($excahngeFileUploadForm->isSubmissionValid()) {
            if(($newFile = $excahngeFileUploadForm->submit())) {
                \Maverick\Lib\Http::location($newFile->getUrl(), 'Your file has been posted.');
            }
        }

        $this->setVariable('form', $excahngeFileUploadForm);
    }
}