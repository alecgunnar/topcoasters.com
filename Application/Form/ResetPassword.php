<?php

namespace Application\Form;

class ResetPassword extends \Maverick\Lib\Form {
    /**
     * The member account which is having its password reset
     *
     * @var \Application\Model\Member|null
     */
    private $member = null;

    /**
     * The constructor
     */
    public function __construct(\Application\Model\Member $member) {
        $this->member = $member;

        parent::__construct();
    }

    /**
     * Builds the form to be rendered later
     *
     * @return null
     */
    public function build() {
        $this->setName('resetPassword');
        $this->setTpl('Standard');

        $this->renderFieldsWithFormTpl();
        
        $this->addField('Input_Email', 'email')
            ->setLabel('Email Address')
            ->setDescription('We require this here for security purposes. No emails will be sent from this form.')
            ->validate('IsEmail')
            ->required();

        $this->addField('Input_Password', 'password')
            ->setLabel('New Password')
            ->required();

        $this->addField('Input_Password', 'password_confirm')
            ->setLabel('Confirm Password')
            ->required();

        $this->addField('Input_Submit', 'submit')
            ->setValue('Continue');
    }

    /**
     * Validates the form
     */
    public function validate() {
        $input  = $this->getModel();
        $errors = false;

        if($input->get('email') != $this->member->get('email_address')) {
            $this->setFieldError('email', 'That email address is invalid.');

            $errors = true;
        }

        if($input->get('password') != $input->get('password_confirm')) {
            $this->setFieldError('password_confirm', 'This password must match the one you typed above.');

            $errors = true;
        }

        if($errors) {
            return false;
        }
    }

    /**
     * Submits the form
     */
    public function submit() {
        $passwordSalt = \Application\Lib\Members::getPasswordSalt();
        $passwordMd5  = \Application\Lib\Members::getPasswordMd5($this->getModel()->get('password'), $passwordSalt);

        $members = new \Application\Service\Members;

        $members->update($this->member, array('password_salt'   => $passwordSalt,
                                              'password_md5'    => $passwordMd5,
                                              'activation_key'  => '',
                                              'activation_code' => null));
    }
}