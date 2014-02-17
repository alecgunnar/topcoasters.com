<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Account extends \Maverick\Lib\Controller {
    /**
     * Available settings panes
     *
     * @var array
     */
    private $settingsPanes = array(''                     => 'Dashboard',
                                   'account-settings'     => 'Account Settings',
                                   'profile-picture'      => 'Change my Profile Picture',
                                   'change-email'         => 'Change my Email Address',
                                   'change-password'      => 'Change my Password',
                                   'log-in-with-facebook' => 'Log in with Facebook');

    /**
     * Template variables for the current pane
     *
     * @var array
     */
    private $paneTplVars = array();

    /**
     * The current member account being worked with
     *
     * @var \Application\Model\Member|null
     */
    private $member = null;

    public function main($pane='') {
        Output::setPageTitle('Account Settings');

        \Application\Lib\Members::checkUserStatus(true);

        $this->member = \Application\Lib\Members::getMember();

        $showPane = '';

        if(array_key_exists($pane, $this->settingsPanes)) {
            $showPane = $pane;
        }

        $paneName         = str_replace(' ', '', $this->settingsPanes[$showPane]);
        $standardPaneName = lcfirst(implode('', explode(' ', ucwords($this->settingsPanes[$showPane]))));

        $paneMethod = 'showPane_' . $standardPaneName;
        $args       = func_get_args();

        array_shift($args);

        if(!method_exists($this, $paneMethod)) {
            $paneName   = 'dashboard';
            $paneMethod = 'showPane_dashboard';
        }

        call_user_func_array(array($this, $paneMethod), $args);

        $paneView = Output::getTplEngine()->getTemplate('AccountSettingsPanes/' . $paneName, $this->paneTplVars);

        $this->setVariable('activePane', $showPane);
        $this->setVariable('panes', $this->settingsPanes);
        $this->setVariable('paneId', $standardPaneName);
        $this->setVariable('paneView', $paneView);
    }

    private function showPane_dashboard() {
        $this->paneTplVars['memberSince'] = 'n/a';

        if($this->member->get('reg_date')) {
            $time = new \Application\Lib\Time($this->member->get('reg_date'));

            $this->paneTplVars['memberSince'] = $time->format('F j, Y g:i a');
        }
    }

    private function showPane_accountSettings() {
        $profileInfo = new \Application\Form\Account_ProfileInformation($this->member);

        if($profileInfo->isSubmissionValid()) {
            $profileInfo->submit();

            \Maverick\Lib\Http::location('/account/account-settings', 'Your settings have been saved.');
        }

        $this->paneTplVars['form'] = $profileInfo->render();
    }

    private function showPane_changeMyProfilePicture($do='', $switchTo='') {
        $profilePictureTypes = \Application\Lib\Members::getProfilePictureTypes();

        $members = new \Application\Service\Members;

        $uploadForm = new \Application\Form\Account_ProfilePicture($this->member);

        if($do == 'upload') {
            if($uploadForm->isSubmissionValid()) {
                if($uploadForm->submit() !== false) {
                    $do       = 'switch-to';
                    $switchTo = 'uploaded';
                }
            }
        }

        $this->paneTplVars['upload_form'] = $uploadForm->render();

        if(!$this->member->get('facebook_id')) {
            unset($profilePictureTypes['facebook']);
        } else {
            $this->paneTplVars['facebook_url'] = $this->member->getProfilePicture(false, 'facebook');
        }

        if(\Application\Lib\Utility::checkFileAtUrl($this->member->getProfilePicture(false, 'uploaded'))) {
            $this->paneTplVars['topcoasters_url'] = $this->member->getProfilePicture(false, 'uploaded');
        } else {
            unset($profilePictureTypes['uploaded']);
        }

        if($do == 'switch-to') {
            $url = '';

            if(!array_key_exists($switchTo, $profilePictureTypes)) {
                $this->paneTplVars['picUnavailable'] = true;
            } else {
                $members->update($this->member, array('profile_picture_type' => $switchTo));

                \Maverick\Lib\Http::location('/account/profile-picture', 'Your profile picture has been updated.');
            }
        } elseif ($do == 'remove') {
            $members->update($this->member, array('profile_picture_type' => ''));

            \Maverick\Lib\Http::location('/account/profile-picture', 'Your profile picture has been removed.');
        }

        $this->paneTplVars['gravatar_url'] = $this->member->getProfilePicture(false, 'gravatar');
    }

    private function showPane_changeMyEmailAddress($do='') {
        $sendActivationEmail = null;

        if($do == 'resend') {
            $sendActivationEmail = true;
        } elseif ($do == 'cancel' && $this->member->get('activation_key') == 'eml') {
            $members = new \Application\Service\Members;

            $this->member->update(array('activation_key'  => '',
                                        'activation_code' => null));

            $members->commitChanges($this->member);

            \Maverick\Lib\Http::location('/account/change-email', 'You no longer have a pending email change request.');
        }

        $changeEmail = new \Application\Form\Account_ChangeEmailAddress($this->member);

        if($changeEmail->isSubmissionValid()) {
            $sendActivationEmail = $changeEmail->submit();
        }

        $newEmailAddress = base64_decode(base64_decode($this->member->get('activation_code')));

        if($sendActivationEmail) {
            $message = \Maverick\Lib\Output::getTplEngine()->getTemplate('Emails/EmailActivation', array('code' => $this->member->get('activation_code')));
            $emailer = new \Application\Lib\Email;

            $emailer->sendIt($this->member->get('name'), $newEmailAddress, 'Email Address Confirmation', $message);
        }

        if(!is_null($sendActivationEmail)) {
            \Maverick\Lib\Http::location('/account/change-email', 'An email has been sent to activate your new email address.');
        }

        if($this->member->get('activation_key') == 'eml') {
            $this->paneTplVars['newEmailAddress'] = $newEmailAddress;
        }

        $this->paneTplVars['form'] = $changeEmail->render();
    }

    private function showPane_changeMyPassword() {
        $changePassword = new \Application\Form\Account_ChangePassword($this->member);

        if($changePassword->isSubmissionValid()) {
            $changePassword->submit();

            \Maverick\Lib\Http::location('/account/change-password', 'Your password has been updated.');
        }

        $this->paneTplVars['form'] = $changePassword->render();
    }

    private function showPane_logInWithFacebook($disable=false) {
        if($this->member->get('facebook_id')) {
            $this->paneTplVars['connected'] = true;

            if($disable == 'disable') {
                $facebook = new \Application\Service\Facebook;

                $facebook->getApi()->api($this->member->get('facebook_id') . '/permissions', 'DELETE');

                $members = new \Application\Service\Members;

                $picType = $this->member->get('profile_picture_type');

                if($picType == 'facebook') {
                    $picType = '';
                }

                $members->update($this->member, array('facebook_id'    => '',
                                                      'facebook_token' => '',
                                                      'profile_picture_type' => $picType));

                \Maverick\Lib\Http::location('/account/log-in-with-facebook', 'Log in with Facebook has been disabled.');
            }
        } else {
            $facebook = new \Application\Service\Facebook;

            if(($user = $facebook->getUser())) {
                $members = new \Application\Service\Members;

                $connectedAccount = $members->get($user->get('id'), 'facebook_id');

                if($connectedAccount) {
                    $this->paneTplVars['accountConnected'] = true;

                    return;
                }

                $update  = array('facebook_id'    => $user->get('id'),
                                 'facebook_token' => $facebook->getAccessToken());

                if($user->get('email') == $this->member->get('email_address')) {
                    $update['email_address'] = $user->get('email');
                }

                if(!$this->member->get('profile_picture_type')) {
                    $update['profile_picture_type'] = 'facebook';
                }

                $members->update($this->member, $update);
                
                \Maverick\Lib\Http::location('/account/log-in-with-facebook', 'Log in with Facebook has been enabled.');
            }
        }
    }
}