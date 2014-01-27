<?php

namespace Application\Form;

class SignIn extends \Maverick\Lib\Form {
    /**
     * The member which will be logged in
     *
     * @var \Application\Model\Member
     */
    private $member = null;

    /**
     * Builds the form to be rendered later
     *
     * @return null
     */
    public function build() {
        $this->setName('signIn');
        $this->setTpl('SignIn');

        $this->renderFieldsWithFormTpl();

        $this->addField('Input_Email', 'email')
            ->setLabel('Email Address')
            ->validate('IsEmail', 'That is not a valid email address')
            ->required();

        $this->addField('Input_Password', 'password')
            ->setLabel('Password')
            ->required();

        $this->addField('Input_Submit', 'submit')
            ->setValue('Sign In');
    }

    /**
     * Validates the form
     */
    public function validate() {
        $input        = $this->getModel();
        $this->member = \Application\Lib\Members::checkLogin($input->get('email'), $input->get('password'));

        if(!$this->member) {
            return false;
        }
    }

    /**
     * Submits the form
     */
    public function submit() {
        \Application\Lib\Members::createSession($this->member);

        return $this->member;
    }
}