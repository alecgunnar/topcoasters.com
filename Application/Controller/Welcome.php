<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Welcome extends \Maverick\Lib\Controller {
    public function main() {
        Output::setPageTitle('Welcome');

        $member = \Application\Lib\Members::getMember();

        if(!$member || $member->get('activation_key') != 'reg' || $member->get('reg_ip') != $_SERVER['REMOTE_ADDR']) {
            \Maverick\Lib\Http::location('/');
        }
    }
}