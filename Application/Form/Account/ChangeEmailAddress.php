<?php

namespace Application\Form;

class Account_ChangeEmailAddress extends \Maverick\Lib\Form {
    /**
     * The account that is currently being worked with.
     *
     * @var \Application\Model\Member | null
     */
    private $member = null;

    /**
     * The activation code for the email address
     *
     * @var string
     */
    private $activationCode = '';

    public function __construct($member) {
        $this->member = $member;

        parent::__construct();
    }

    protected function build() {
        $this->setName('changeEmail');
        $this->setTpl('Standard');

        $this->renderFieldsWithFormTpl();

        $this->addField('Input_Email', 'email_address')
            ->setLabel('New Email Address')
            ->required('You must enter an email address')
            ->validate('IsEmail')
            ->addAttribute('autocomplete', 'off');

        $this->addField('Input_Email', 'confirm_email_address')
            ->setLabel('Confirm Email Address')
            ->required('You must confirm your new email address')
            ->validate('IsEmail')
            ->addAttribute('autocomplete', 'off');

        $currentPassword = $this->addFieldGroup('current_password');

        $currentPassword->addField('Input_Password', 'password')
            ->setLabel('Password')
            ->required('You must enter your current password')
            ->addAttribute('autocomplete', 'off')
            ->setDescription('This is required for security purposes; to prevent any unauthorized changes to your account.');

        $this->addField('Input_Submit', 'saveEmail')
            ->setValue('Save Email Address');
    }

    protected function validate() {
        $input   = $this->getModel();
        $members = new \Application\Service\Members;

        $md5 = \Application\Lib\Members::getPasswordMD5($input->get('current_password')->get('password'), $this->member->get('password_salt'));

        if($md5 != $this->member->get('password_md5')) {
            $this->setFieldError('password', 'That password was invalid');

            return;
        }

        if($input->get('confirm_email_address') != $input->get('email_address')) {
            $this->setFieldError('confirm_email_address', 'This email address does not match the one you typed above.');

            return;
        }

        $member               = $members->get($input->get('email_address'), 'email_address');
        $this->activationCode = base64_encode(base64_encode($input->get('email_address')));

        if($member || !\Application\Lib\Members::checkActivationCode($this->activationCode)) {
            $this->setFieldError('email_address', 'That email address is already in use.');
        }
    }

    public function submit() {
        $input   = $this->getModel();
        $members = new \Application\Service\Members;

        if($this->member->get('activation_key') == 'reg') {
            $this->member->update('activation_code', \Maverick\Lib\Utility::generateToken(25));
            $this->member->update('email_address', $input->get('email_address'));

            $members->commitChanges($this->member);

            \Application\Lib\Members::sendActivationEmail($this->member);
        } else {
            $this->member->update(array('activation_key'  => 'eml',
                                        'activation_code' => $this->activationCode));

            $members->commitChanges($this->member);

            return true;
        }

        return false;
    }
}