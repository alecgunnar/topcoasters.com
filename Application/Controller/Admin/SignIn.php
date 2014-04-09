<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Admin_SignIn extends \Maverick\Lib\Controller {
    public function main() {
        $adminSignInForm = new \Application\Form\Admin_SignIn;

        if($adminSignInForm->isSubmissionValid() && $adminSignInForm->submit()) {
            $_SESSION['admin_key'] = md5(\Maverick\Lib\Utility::generateToken(32));

            \Maverick\Lib\Http::location('/admin', 'You have been signed in.');
        }

        $this->setVariable('form', $adminSignInForm->render());

        $this->printOut();
    }
}