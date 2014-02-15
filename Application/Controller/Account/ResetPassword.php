<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Account_ResetPassword extends \Maverick\Lib\Controller {
    public function main($code='') {
        Output::setPageTitle('Reset Your Password');

        if(!ctype_alnum($code) || !$code) {
            \Application\Lib\Utility::showError();
        }

        $members = new \Application\Service\Members;
        $member  = $members->get($code, 'activation_code');

        if($member) {
            $changePassword = new \Application\Form\ChangePassword($member);

            if($changePassword->isSubmissionValid()) {
                if($changePassword->submit()) {
                    if($member->get('activation_key') == 'pwd') {
                        $members->update($member, array('activation_key'  => '',
                                                        'activation_code' => null));
        
                        \Application\Lib\Utility::location('/sign-in');
                    }
                }
            }
        } else {
            \Application\Lib\Utility::showError('The URL you have followed is invalid.');
        }

        $this->setVariable('form', $changePassword->render());
    }
}