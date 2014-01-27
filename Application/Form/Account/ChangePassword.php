<?php

namespace Application\Form;

class Account_ChangePassword extends \Maverick\Lib\Form {
    /**
     * The account that is currently being worked with.
     *
     * @var \Application\Model\Member | null
     */
    private $member = null;

    public function __construct($member) {
        $this->member = $member;

        parent::__construct();
    }

    protected function build() {
        $this->setName('changeEmail');
        $this->setTpl('Standard');

        $this->renderFieldsWithFormTpl();

        $this->addField('Input_Password', 'new_password')
            ->setLabel('New Password')
            ->addAttribute('autocomplete', 'off')
            ->required('You must enter a new password');

        $this->addField('Input_Password', 'confirm_password')
            ->setLabel('Confirm New Password')
            ->addAttribute('autocomplete', 'off')
            ->required('You must confirm your new password');

        $currentPassword = $this->addFieldGroup('current_password');

        $currentPassword->addField('Input_Password', 'password')
            ->setLabel('Current Password')
            ->required('You must enter your current password')
            ->addAttribute('autocomplete', 'off')
            ->setDescription('This is required for security purposes; to prevent any unauthorized changes to your account.');

        $this->addField('Input_Submit', 'saveEmail')
            ->setValue('Save Password');
    }

    protected function validate() {
        $input = $this->getModel();
        $md5   = \Application\Lib\Members::getPasswordMD5($input->get('current_password')->get('password'), $this->member->get('password_salt'));

        if($md5 != $this->member->get('password_md5')) {
            $this->setFieldError('password', 'That password was invalid');

            return;
        }

        if($input->get('confirm_password') != $input->get('new_password')) {
            $this->setFieldError('confirm_password', 'This password did not match the one you typed above.');
        }
    }

    public function submit() {
        $input   = $this->getModel();
        $members = new \Application\Service\Members;

        $passwordSalt = \Application\Lib\Members::getPasswordSalt();
        $passwordMd5  = \Application\Lib\Members::getPasswordMd5($input->get('new_password'), $passwordSalt);

        $this->member->update(array('password_salt' => $passwordSalt,
                                    'password_md5'  => $passwordMd5));

        $members->commitChanges($this->member);
    }
}