<?php

namespace Application\Form;

class ForgotPassword extends \Maverick\Lib\Form {
    /**
     * The member account being worked with
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
        $this->setName('forgotPassword');
        $this->setTpl('Standard');

        $this->renderFieldsWithFormTpl();
        
        $this->addField('Input_Email', 'email')
            ->setLabel('Email Address');

        $this->addField('Input_Submit', 'submit')
            ->setValue('Continue');
    }

    /**
     * Validates the form
     */
    public function validate() {
        $members = new \Application\Service\Members;
        $member  = $members->get($this->getModel()->get('email'), 'email_address');

        if(count($member) == 1) {
            if($member->get('activation_key') == 'reg') {
                \Application\Lib\Utility::showError('Please activate your account before attempting to reset your password.');
            }

            $this->member = $member;

            return true;
        }

        $this->setFieldError('email', 'No accounts were found with that email address');
    }

    /**
     * Submits the form
     */
    public function submit() {
        $members        = new \Application\Service\Members;
        $activationCode = \Maverick\Lib\Utility::generateToken(25);

        $members->update($this->member, array('activation_key'  => 'pwd',
                                              'activation_code' => $activationCode));

        $message = \Maverick\Lib\Output::getTplEngine()->getTemplate('Emails/PasswordReset', array('code' => $activationCode));

        $emailer = new \Application\Lib\Email;

        $emailer->sendIt($this->member->get('username'), $this->member->get('email_address'), 'Password Reset', $message);
    }
}