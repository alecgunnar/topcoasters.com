<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class SignIn extends \Maverick\Lib\Controller {
    public function main() {
        Output::setPageTitle('Sign In');

        if(\Application\Lib\Members::getMember()) {
            \Maverick\Lib\Http::location('/');
        }

        $signIn = new \Application\Form\SignIn;

        if($signIn->getStatus()) {
            if($signIn->isSubmissionValid()) {
                if(($member = $signIn->submit()) instanceof \Application\Model\Member) {
                    \Application\Lib\Members::redirectToLast('Welcome back, ' . $member->getName() . '.');
                }
            } else {
                $this->setVariable('loginError', true);
            }
        }

        $this->setVariable('form', $signIn->render());
    }
}