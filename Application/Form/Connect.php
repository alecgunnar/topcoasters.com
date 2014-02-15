<?php

namespace Application\Form;

class Connect extends \Maverick\Lib\Form {
    /**
     * The default username to use
     *
     * @var string
     */
    private $username = '';

    /**
     * The constructor
     *
     * @param string $username
     */
    public function __construct($username) {
        $this->username = $username;

        parent::__construct();
    }

    /**
     * Builds the form to be rendered later
     *
     * @return null
     */
    public function build() {
        $this->setName('connect');
        $this->setTpl('Standard');

        $this->renderFieldsWithFormTpl();

        $this->addField('Input_Text', 'username')
            ->setLabel('Username')
            ->setValue($this->username)
            ->required();

        $this->addField('Input_Password', 'password')
            ->setLabel('Password')
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
        $input = $this->getModel();

        if($input->get('password_confirm') != $input->get('password')) {
            $this->setFieldError('password_confirm', 'This password must match the one above!');

            return false;
        }
    }

    /**
     * Submits the form
     */
    public function submit($user=null, $token='') {
        if(is_null($user) || !$token) {
            throw new \InvalidParameterException('\Application\Form\Connect::submit expects two parameters, \Application\Model\FacebookUser $user, String $token!');
        }

        $input   = $this->getModel();
        $members = new \Application\Service\Members;

        $passwordSalt = \Application\Lib\Members::getPasswordSalt();
        $passwordMd5  = \Application\Lib\Members::getPasswordMd5($input->get('password'), $passwordSalt);

        return $members->create(array('username'             => $input->get('username'),
                                      'email_address'        => $user->get('email'),
                                      'password_salt'        => $passwordSalt,
                                      'password_md5'         => $passwordMd5,
                                      'facebook_token'       => $token,
                                      'facebook_id'          => $user->get('id'),
                                      'profile_picture_type' => 'facebook'));
    }
}