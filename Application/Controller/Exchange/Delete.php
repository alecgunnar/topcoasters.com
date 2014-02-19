<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Exchange_Delete extends \Maverick\Lib\Controller {
    public function main($fileId=0) {
        $exchangeFiles = new \Application\Service\Exchange;
        $file          = $exchangeFiles->get(intval($fileId));

        if(!$file) {
            \Maverick\Lib\Router::throw404();
        }

        \Application\Lib\Members::checkUserStatus(true);

        if(!\Application\Lib\Members::checkUserIsMod() && (\Application\Lib\Members::getMember()->get('member_id') != $file->getMember()->get('member_id'))) {
            \Application\Lib\Utility::showError('You do not have permission to view this page.');
        }

        $trackFile = \Maverick\Maverick::getConfig('Exchange')->get('paths')->get('files') . $file->get('file');
        $imageFile = \Maverick\Maverick::getConfig('Exchange')->get('paths')->get('screenshot') . $file->get('screenshot');

        if(file_exists($trackFile) && $file->get('file')) {
            unlink($trackFile);
        }

        if(file_exists($imageFile) && $file->get('screenshot')) {
            unlink($imageFile);
        }

        $exchangeFiles->delete($file);

        \Maverick\Lib\Http::location('/exchange', 'Exchange file deleted.');
    }
}