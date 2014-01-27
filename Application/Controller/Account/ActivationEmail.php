<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Account_ActivationEmail extends \Maverick\Lib\Controller {
    public function main() {
        Output::setPageTitle('Resend Activation Email');

        $member = \Application\Lib\Members::getMember();

        if(!$member) {
            \Application\Lib\Utility::showError('You must be logged in to view this page.');
        }

        if($member->get('activation_key') != 'reg') {
            \Application\Lib\Utility::location('/');
        }

        $time       = new \Application\Lib\Time;
        $goodToSend = new \Application\Lib\Time($member->get('last_activation_email_time'));

        $goodToSend->add(new \DateInterval('PT10M'));

        if($time < $goodToSend) {
            \Application\Lib\Utility::showError('Please wait at least ten minutes before requesting another email.');
        }

        if(!\Application\Lib\Members::sendActivationEmail($member)) {
            \Application\Lib\Utility::showError('We were unable to send another activation email, please try again later.');
        }

        $members = new \Application\Service\Members;
        $members->update($member, array('last_activation_email_time' => $time->now(true)));
    }
}