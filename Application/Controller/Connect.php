<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Connect extends \Maverick\Lib\Controller {
    public function main() {
        Output::setPageTitle('Log in with Facebook');

        if(\Application\Lib\Members::getMember()) {
            \Maverick\Lib\Http::location('/');
        }

        $facebook = new \Application\Service\Facebook;
        $user     = $facebook->getUser();

        if($user) {
            $this->signInUser($user, $facebook->getAccessToken());
        } else {
            \Maverick\Lib\Http::location($facebook->getApi()->getLoginUrl());
        }
    }

    private function signInUser($user, $token) {
        if(!$user->get('email')) {
            \Application\Lib\Utility::showError('Without your email address, it is not possible for us to create an account for you, and sign you into it.');
        }

        $members = new \Application\Service\Members;
        $member  = $members->get($user->get('email'), 'email_Address');

        if($member) {
            \Application\Lib\Members::createSession($member);

            $members->update($member, array('facebook_id'    => $user->get('id'),
                                            'facebook_token' => $token));

            \Application\Lib\Members::redirectToLast('Welcome back, ' . $member->getName() . '.');
        } else {
            $username = $this->generateUsername($user->get('username') ?: $user->get('first_name') . '.' . $user->get('last_name'));

            $connectForm = new \Application\Form\Connect($username, $user);

            if($connectForm->isSubmissionValid()) {
                if(($member = $connectForm->submit($user, $token))) {
                    \Application\Lib\Members::createSession($member);
                    \Application\Lib\Members::redirectToLast('Welcome, ' . $user->get('first_name') . ', you have been signed in!');
                }
            }

            $this->setVariable('name', $user->get('first_name'));
            $this->setVariable('form', $connectForm->render());
        }
    }

    /**
     * Generates a username
     *
     * @param  string $username
     * @return string
     */
    private function generateUsername($username) {
        $members = new \Application\Service\Members;

        $appendUn = '';
        $unExists = true;
        $count    = 0;

        do {
            if($count) {
                $appendUn .= '.' . $count;
            }

            $checkUn = $members->get($username . $appendUn, 'username');

            if(!count($checkUn)) {
                $unExists = false;
            }

            $count++;
        } while($unExists);

        return $username;
    }
}