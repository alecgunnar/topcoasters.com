<?php

namespace Application\Form;

class CreateAnAccount extends \Maverick\Lib\Form {
    /**
     * Builds the form to be rendered later
     *
     * @return null
     */
    public function build() {
        $this->setName('createAnAccount');
        $this->setTpl('CreateAnAccount');

        $this->renderFieldsWithFormTpl();

        $this->addField('Input_Text', 'username')
            ->setLabel('Username')
            ->required('You must enter an username')
            ->setMaxLength(20)
            ->setDescription('Only letters, numbers, and periods are allowed in usernames.');

        $this->addField('Input_Email', 'email')
            ->setLabel('Email Address')
            ->required('An email address is required (<a href="/terms-of-use#privacy-policy" target="_blank">Privacy Policy</a>)')
            ->validate('IsEmail', 'That is not a valid email address');

        $this->addField('Input_Password', 'password')
            ->setLabel('Password');

        $this->addField('Input_Password', 'password_confirm')
            ->addAttribute('placeholder', 'Confirm Password');

        $this->addField('Input_Submit', 'submit')
            ->setValue('Create my Account');
    }

    /**
     * Validates the form
     */
    public function validate() {
        $input = $this->getModel();

        if($input->get('password') && $input->get('password_confirm')) {
            if($input->get('password') != $input->get('password_confirm')) {
                $this->setFieldError('password', 'Those passwords did not match');
            }
        } else {
            $this->setFieldError('password', 'You must enter a password');
        }

        $members = new \Application\Service\Members;

        if(\Application\Lib\Members::checkUsername($input->get('username'))) {
            if($members->get($input->get('username'), 'name')) {
                $this->setFieldError('username', 'That username is already taken');
            }
        } else {
            $this->setFieldError('username', 'This username contains characters that are not allowed');
        }

        if($members->get($input->get('email'), 'email_address')) {
            $this->setFieldError('email', 'That email address is already in use (<a href="/sign-in">Sign In</a>)');
        }
    }

    /**
     * Submits the form
     */
    public function submit() {
        $input   = $this->getModel();
        $members = new \Application\Service\Members;

        $activationCode = \Maverick\Lib\Utility::generateToken(25);

        $passwordSalt = \Application\Lib\Members::getPasswordSalt();
        $passwordMd5  = \Application\Lib\Members::getPasswordMd5($input->get('password'), $passwordSalt);

        $member = $members->create(array('name'                 => $input->get('username'),
                                         'email_address'        => $input->get('email'),
                                         'password_salt'        => $passwordSalt,
                                         'password_md5'         => $passwordMd5,
                                         'activation_code'      => $activationCode,
                                         'activation_key'       => 'reg',
                                         'profile_picture_type' => 'gravatar'));

        if($member instanceof \Application\Model\Member) {
            \Application\Lib\Members::createSession($member);
            \Application\Lib\Members::sendActivationEmail($member);

            \Application\Lib\Utility::location('/welcome');
        }

        return false;
    }
}