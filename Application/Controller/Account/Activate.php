<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Account_Activate extends \Maverick\Lib\Controller {
    public function main() {
        Output::setPageTitle('Activate My Account');

        $input = new \Maverick\Lib\Model_Input($_GET);
        $code  = $input->get('token');

        if(!$code) {
            \Application\Lib\Utility::showError();
        }

        $members = new \Application\Service\Members;
        $member  = $members->get($code, 'activation_code');

        if($member) {
            if($member->get('activation_key') == 'reg') {
                $member->update(array('activation_key'  => '',
                                      'activation_code' => null,
                                      'activated'       => '1'));

                $members->commitChanges($member);

                \Maverick\Lib\Http::location('/', 'Your account has been activated');
            } elseif($member->get('activation_key') == 'eml') {
                $member->update(array('email_address'   => base64_decode(base64_decode($member->get('activation_code'))),
                                      'activation_key'  => '',
                                      'activation_code' => null));

                $members->commitChanges($member);

                \Maverick\Lib\Http::location('/', 'Your new email address has been activated');
            }
        }

        \Application\Lib\Utility::showError('The URL you have followed is invalid');
    }
}