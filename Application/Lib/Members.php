<?php

namespace Application\Lib;

class Members {
    /**
     * The current session
     *
     * @var \Application\Model\Session
     */
    private static $session = null;

    /**
     * The current member
     *
     * @var \Application\Model\Member
     */
    private static $member = null;
    
    /**
     * Profile picture types
     *
     * @var array
     */
    private static $profilePictureTypes = array('gravatar' => 'http://www.gravatar.com/avatar/%s?size=200&d=identicon',
                                                'facebook' => 'https://graph.facebook.com/%d/picture?width=200&height=200',
                                                'uploaded' => 'assets/uploads/profile_pictures/%d.png',
                                                'default'  => 'assets/images/default_profile_picture.png');

    /**
     * Checks the login information
     *
     * @param  string $email
     * @param  string $password
     * @return \Application\Model\Member | boolean
     */
    public static function checkLogin($email, $password) {
        $members = new \Application\Service\Members;
        $member  = $members->get($email, 'email_address');

        if($member) {
            if(self::getPasswordMd5($password, $member->get('password_salt')) == $member->get('password_md5')) {
                return $member;
            }
        }

        return false;
    }

    /**
     * Check the username
     *
     * @param  string $username
     * @return boolean
     */
    public static function checkUsername($username) {
        if(preg_match('~[^A-Za-z0-9\.]~', $username)) {
            return false;
        }

        return true;
    }

    /**
     * Generates a password salt
     *
     * @return string
     */
    public static function getPasswordSalt() {
        return \Maverick\Lib\Utility::generateToken(25);
    }

    /**
     * Generates a password md5
     *
     * @param  string $password
     * @param  string $salt
     * @return string
     */
    public static function getPasswordMd5($password, $salt) {
        return md5(($password) . md5($salt));
    }

    /**
     * Gets the currently logged in member
     *
     * @return \Application\Model\Member | null
     */
    public static function getMember() {
        if(self::$member) {
            return self::$member;
        }

        self::getSession();

        return self::$member;
    }

    /**
     * Creates a new session for the member
     *
     * @throws \Exception
     * @param \Application\Model\Member $member
     */
    public static function createSession(\Application\Model\Member $member) {
        $sessions = new \Application\Service\Sessions;

        $sessions->create($member);

        return self::getSession();
    }

    /**
     * Gets the current session
     *
     * @return \Application\Model\Session | boolean
     */
    public static function getSession() {
        if(self::$session) {
            return self::$session;
        }

        $sessions = new \Application\Service\Sessions;
        $session  = new \Maverick\Lib\Session;

        $sess = $sessions->get($session->getCookies()->get('session_id'));

        if($sess) {
            $now    = new \Application\Lib\Time;
            $expire = new \Application\Lib\Time($sess->get('expire'));

            if($now > $expire) {
                self::endSession();

                return false;
            }

            $members = new \Application\Service\Members;
            $member  = $members->get($sess->get('member_id'));

            if($member) {
                $time = new \Application\Lib\Time;

                $member->update('last_active', $time->now(true));

                $members->commitChanges($member);

                \Application\Lib\Time::setDefaultTimezone($member->get('timezone'));

                self::$member = $member;
            } else {
                self::endSession();

                return false;
            }

            $sessions->updateLastActive($sess);

            self::$session = $sess;

            return $sess;
        }
    }

    /**
     * Ends the current session
     */
    public static function endSession() {
        $sessions = new \Application\Service\Sessions;
        $session  = new \Maverick\Lib\Session;

        $sessions->delete($session->getCookies()->get('session_id'));
        $session->setCookie('session_id', '', -1);

        self::$session = '';
        self::$member  = '';
    }

    /**
     * Sends an activation email
     *
     * @param \Application\Model\Member
     */
    public static function sendActivationEmail($member) {
        $message = \Maverick\Lib\Output::getTplEngine()->getTemplate('Emails/Activation', array('code' => $member->get('activation_code')));
        $emailer = new \Application\Lib\Email;

        return $emailer->sendIt($member->get('name'), $member->get('email_address'), 'Activate Your Account', $message);
    }

    /**
     * Check the session status of the user, shows an error if they are not logged in
     *
     * @param  boolean $showError=false
     * @param  string  $customErrorMessage
     * @return boolean|null
     */
    public static function checkUserStatus($showError=false, $customErrorMessage='') {
        if(is_null(self::$member)) {
            $member = self::getMember();

            if(is_null(self::$member)) {
                if($showError) {
                    if(\Maverick\Lib\Output::getGlobalVariable('ajaxRequest')) {
                        \Maverick\Lib\Output::printJson(array('status' => 'signin'));
                    }

                    \Application\Lib\Utility::showError($customErrorMessage ?: 'You must be signed in to view this page.');
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Checks to see if the user is a moderator or not
     *
     * @param  boolean $showError=false
     * @param  string  $customErrorMessage
     * @return boolean|null
     */
    public static function checkUserIsMod($showError=false, $customErrorMessage='') {
        if(self::$member instanceof \Application\Model\Member) {
            if(self::$member->get('is_mod')) {
                return true;
            }
        }

        if($showError) {
            if(\Maverick\Lib\Output::getGlobalVariable('ajaxRequest')) {
                \Maverick\Lib\Output::printJson(array('status' => 'signin'));
            }

            \Application\Lib\Utility::showError($customErrorMessage ?: 'You do not have permission to view this page.');
        }

        return false;
    }

    /**
     * Checks to see if the user is an administrator or not
     *
     * @param  boolean $showError=false
     * @param  string  $customErrorMessage
     * @return boolean|null
     */
    public static function checkUserIsAdmin($showError=false, $customErrorMessage='') {
        if(self::$member instanceof \Application\Model\Member) {
            if(self::$member->get('is_admin')) {
                return true;
            }
        }

        if($showError) {
            if(\Maverick\Lib\Output::getGlobalVariable('ajaxRequest')) {
                \Maverick\Lib\Output::printJson(array('status' => 'signin'));
            }

            \Application\Lib\Utility::showError($customErrorMessage ?: 'You do not have permission to view this page.');
        }

        return false;
    }

    /**
     * Gets the profile picture types
     *
     * @return array
     */
    public static function getProfilePictureTypes() {
        return self::$profilePictureTypes;
    }

    /**
     * Checks and (maybe) created activation codes
     *
     * @param  string $code=''
     * @param  string $generateNew=false
     * @return string|boolean
     */
    public static function checkActivationCode($code='', $generateNew=false) {
        $members   = new \Application\Service\Members;
        $keyExists = true;

        $code = $code ?: \Maverick\Lib\Utility::generateToken(25);

        do {
            if(!$members->get($code, 'activation_code')) {
                $keyExists = false;

                break;
            }

            $code = \Maverick\Lib\Utility::generateToken(25);
        } while($keyExists && $generateNew);

        if(!$generateNew && $keyExists) {
            return false;
        }

        return $code;
    }

    /**
     * Redirects the user to their last page
     *
     * @param  string $msg=''
     */
    public static function redirectToLast($msg='') {
        $goBackTo = '/';

        if(array_key_exists('go_back_to', $_SESSION)) {
            $goBackTo = $_SESSION['go_back_to'];
        }

        \Maverick\Lib\Http::location($goBackTo, $msg);
    }
}