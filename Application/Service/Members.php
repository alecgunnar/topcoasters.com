<?php

namespace Application\Service;

class Members extends Standard {
    protected function setUp() {
        $this->dbTable    = 'members';
        $this->primaryKey = 'member_id';
        $this->model      = 'Memebr';
    }

    /**
     * Creates a new account
     *
     * @throws \Exception
     * @param  array $values
     * @return boolen
     */
    public function create($values) {
        if(!is_array($values)) {
            throw new \Exception('Function \Application\Service\Member::create expects an array to be supplied.');
        }

        if(!array_key_exists('seo_title', $values)) {
            $values['seo_title'] = \Application\Lib\Utility::generateSeoTitle($values['name']);
        }

        if(!array_key_exists('reg_date', $values)) {
            $time = new \Application\Lib\Time;
            $values['reg_date'] = $time->now(true);
        }

        if(!array_key_exists('ip_address', $values)) {
            $values['ip_address'] = $_SERVER['REMOTE_ADDR'];
        }

        $id = $this->db->put($values, 'members');

        if($id) {
            $values['member_id'] = $id;

            return new \Application\Model\Member($values);
        }

        return false;
    }

    /**
     * Updates a member's account
     *
     * @param \Application\Model\Member $member
     * @param array $update
     */
    public function update(\Application\Model\Member $member, $update) {
        $this->db->post($update, array('member_id' => $member->get('member_id')), 'members');
    }
}