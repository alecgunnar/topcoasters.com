<?php

namespace Application\Service;

class Sessions extends Standard {
    protected function setUp() {
        $this->dbTable    = 'sessions';
        $this->primaryKey = 'session_id';
        $this->model      = 'Session';
    }

    /**
     * Creates a new session
     *
     * @param  \Maverick\Model\Member $member
     * @return boolen
     */
    public function create(\Application\Model\Member $member) {
        $session = new \Maverick\Lib\Session;
        $time    = new \Application\Lib\Time;

        $now = $time->now(true);

        $time->add(new \DateInterval('P7D'));

        $this->delete($member->get('member_id'));

        $insert = array('session_id'  => $session->getCookies()->get('PHPSESSID'),
                        'member_id'   => $member->get('member_id'),
                        'last_active' => $now,
                        'expire'      => $time->getTimestamp());

        $this->db->put($insert, 'sessions');
    }

    /**
     * Ends the session
     *
     * @throws \Exception
     * @param mixed $value
     */
    public function delete($value) {
        if(is_numeric($value)) {
            $field = 'member_id';
        } elseif(is_string($value)) {
            $field = 'session_id';
        } else {
            throw new \Exception(__NAMESPACE__ . 'Sessions::delete expects either a string or integer.');
        }

        $this->db->delete(array($field => $value), 'sessions');
    }

    /**
     * Update the last active time for the session
     *
     * @param \Application\Lib\Session $session
     */
    public function updateLastActive(\Application\Model\Session $session) {
        $time = new \Application\Lib\Time;

        $this->db->post(array('last_active' => $time->now(true)), array('session_id' => $session->get('session_id')), 'sessions');
    }

    /**
     * Gets the most recent session for a member
     *
     * @param  \Application\Model\Member $member
     * @return \Application\Model\Session | boolean
     */
    public function getMostRecent(\Application\Model\Member $member) {
        $sessions = $this->get($member->get('member_id'), 'member_id');
        $time     = new \Application\Lib\Time;

        $mostRecent = null;

        if(is_array($sessions)) {
            foreach($sessions as $num => $session) {
                $mostRecent = $session;
            }
        } elseif($sessions instanceof \Application\Model\Session) {
            $mostRecent = $sessions;
        }

        if(!is_null($mostRecent)) {
            $lastActive = new \Application\Lib\Time($mostRecent->get('last_active'));

            if($time->diff($lastActive)->format('%s') <= (15 * 60)) {
                return $mostRecent;
            }
        }

        return false;
    }
}