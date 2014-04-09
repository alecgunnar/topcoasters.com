<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Admin extends \Maverick\Lib\Controller {
    public static function rootSetup() {
        \Application\Lib\Members::checkUserIsAdmin(true);

        Output::setPageTitle('Admin Control Panel');

        if(!array_key_exists('admin_key', $_SESSION)) {
            \Maverick\Lib\Router::loadController('Admin_SignIn');
        }
    }

    public function main() {
        $offlineCache  = new \Maverick\Lib\Cache('OfflineStatus');
        $offlineData   = $offlineCache->get();

        if($offlineData) {
            if($offlineData['status']) {
                $this->setVariable('isOffline', true);
            }
        }
    }
}