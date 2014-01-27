<?php

namespace Application\Service;

class Forums extends Standard {
    protected function setUp() {
        $this->primaryKey = 'forum_id';
        $this->dbTable    = 'forums';
        $this->model      = 'forum';
    }

    public function getAllInOrder() {
        $forums = $this->db->get(array('select' => '*',
                                       'from'   => 'forums',
                                       'where'  => 'parent_id IS NULL',
                                       'order'  => 'placement asc'), 'forum');

        $categories = array();

        if(count($forums)) {
            foreach($forums as $num => $forum) {
                if(count(($children = $this->getChildren($forum->get('forum_id'))))) {
                    $categories[] = array($forum, $children);
                }
            }

            if(count($categories)) {
                return $categories;
            }
        }

        return false;
    }

    public function getChildren($forumId) {
        return $this->db->get(array('select' => '*',
                                    'from'   => 'forums',
                                    'where'  => 'parent_id = ' . $forumId,
                                    'order'  => 'placement asc'), 'forum');
    }
}