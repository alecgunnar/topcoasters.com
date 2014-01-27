<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class CreateAnAccount extends \Maverick\Lib\Controller {
    public function main() {
        Output::setPageTitle('Create an Account');

        if(\Application\Lib\Members::getMember()) {
            \Maverick\Lib\Http::location('/');
        }

        $createAccount = new \Application\Form\CreateAnAccount;

        if($createAccount->isSubmissionValid()) {
            $createAccount->submit();
        }

        $this->setVariable('form', $createAccount->render());
    }
}