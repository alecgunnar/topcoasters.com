<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Exchange_Download extends \Maverick\Lib\Controller {
    public function main($fileId=0) {
        $exchangeFiles = new \Application\Service\Exchange;
        $file          = $exchangeFiles->get(intval($fileId));

        if(!$file) {
            \Maverick\Lib\Router::throw404();
        }

        \Application\Lib\Members::checkUserStatus(true);

        $fileUrl = \Maverick\Maverick::getConfig('Exchange')->get('paths')->get('files') . $file->get('file');

        if(!file_exists($fileUrl)) {
            \Application\Lib\Utility::showError('Unfortunately, this file is not currently available.');
        }

        $this->checkMemberDailyDownloads($file);

        $nameExp = explode('.', $file->get('file'));
        $type    = $nameExp[count($nameExp) - 1];

        header('Content-type: ' . \Maverick\Maverick::getConfig('Mimes')->get($type));
        header('Content-Transfer-Encoding: Binary'); 
        header('Content-disposition: attachment; filename="' . basename($fileUrl) . '"');
        readfile($fileUrl);

        exit;
    }

    private function checkMemberDailyDownloads($file) {
        $member          = \Application\Lib\Members::getMember();
        $maxDaily        = $member->get('exchange_max_daily_downloads');
        $todaysDownloads = $member->get('exchange_days_downloads');
        $unlimited       = ($maxDaily == -1) ? true : false;

        $expTodaysDownloads = explode(',', $todaysDownloads);
        $time               = new \Application\Lib\Time(null, true);

        foreach($expTodaysDownloads as $num => $downloadTime) {
            if(($time->format('U') - $downloadTime) > 86400) {
                array_shift($expTodaysDownloads);

                break;
            }

            $maxDaily--;
        }

        if($maxDaily <= 0 && !$unlimited) {
            $canDownload = new \Application\Lib\Time('@' . ($expTodaysDownloads[0] + 86400), true);

            \Application\Lib\Utility::showError('You have reached your maximum number of downloads for today. You can download more after: ' . $canDownload->switchToUsersTime()->format('F j, Y g:i a') . '.');
        } else {
            $todaysDownloads = implode(',', $expTodaysDownloads) . ',' . $time->format('U');

            $member->update('exchange_days_downloads', $todaysDownloads);

            if(strpos($member->get('exchange_downloads'), $file->get('file_id') . ',') === false) {
                $member->update('exchange_downloads', $member->get('exchange_downloads') . $file->get('file_id') . ',');

                $exchangeFiles = new \Application\Service\Exchange;
                $file->increase('num_downloads');
                $exchangeFiles->commitChanges($file);
            }

            $members = new \Application\Service\Members;
            $members->commitChanges($member);
        }
    }
}