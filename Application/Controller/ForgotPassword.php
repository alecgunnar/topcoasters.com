<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class ForgotPassword extends \Maverick\Lib\Controller {
    public function main() {
        Output::setPageTitle('Password Reset');

        $forgotPassword = new \Application\Form\ForgotPassword;

        if($forgotPassword->isSubmissionValid()) {
            $forgotPassword->submit();

            \Maverick\Lib\Http::location('/sign-in', 'An email has been sent with instructions to reset your password.');
        }

        $this->setVariable('form', $forgotPassword->render());
    }
}