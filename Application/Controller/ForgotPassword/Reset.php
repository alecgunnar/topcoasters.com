<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class ForgotPassword_Reset extends \Maverick\Lib\Controller {
    public function main() {
        Output::setPageTitle('Resetting Password');

        $input = new \Maverick\Lib\Model_Input($_GET);

        $members = new \Application\Service\Members;
        $member  = $members->get($input->get('token'), 'activation_code');

        if(!$member) {
            \Application\Lib\Utility::showError('The URL you have followed is invalid. If you have forgotten your password, <a href="/forgot-password">request a new one</a>.');
        }

        if($member->get('activation_key') != 'pwd') {
            \Application\Lib\Utility::showError('You have followed an invalid URL.');
        }

        $resetPassword = new \Application\Form\ResetPassword($member);

        if($resetPassword->isSubmissionValid()) {
            $resetPassword->submit();

            \Maverick\Lib\Http::location('/sign-in', 'Your password has been updated, you may now sign in.');
        }

        $this->setVariable('form', $resetPassword->render());
    }
}