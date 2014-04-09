<?php

namespace Application\Form;

class Admin_SignIn extends \Maverick\Lib\Form {
    /**
     * Builds the form to be rendered later
     *
     * @return null
     */
    public function build() {
        $this->setName('admin_signin');
        $this->setTpl('Standard');

        $this->renderFieldsWithFormTpl();

        $this->addField('Input_Text', 'admin_email')
            ->setLabel('Email Address')
            ->addAttribute('autofill', 'false')
            ->required();

        $this->addField('Input_Password', 'admin_password')
            ->setLabel('Password')
            ->required();

        $this->addField('Input_Submit', 'submit')
            ->setValue('Sign In');
    }

    /**
     * Validates the form
     */
    public function validate() { }

    /**
     * Submits the form
     */
    public function submit() {
        $member = \Application\Lib\Members::getMember();
        $input  = $this->getModel();

        if(\Application\Lib\Members::checkLogin($input->get('admin_email'), $input->get('admin_password')) == $member) {
            return true;
        }

        $this->setFieldError('admin_email', 'Invalid email address or password');

        return false;
    }
}