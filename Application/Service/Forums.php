<?php

namespace Application\Service;

class Forums extends Standard {
    protected function setUp() {
        $this->primaryKey = 'forum_id';
        $this->dbTable    = 'forums';
        $this->model      = 'Forum';
    }

    public function getAllInOrder() {
        $forums = $this->db->get(array('select' => '*',
                                       'from'   => 'forums',
                                       'where'  => 'parent_id IS NULL',
                                       'order'  => 'placement asc'), 'Forum');

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
                                    'order'  => 'placement asc'), 'Forum');
    }

    public function getForMove($currentForum) {
        $options         = array();
        $categoryOptions = array();

        foreach($this->getAllInOrder() as $category) {
            foreach($category[1] as $forum) {
                if($currentForum != $forum->get('forum_id')) {
                    $categoryOptions[$forum->get('forum_id')] = $forum->getName();
                }
            }

            $options[$category[0]->getName()] = $categoryOptions;
            $categoryOptions               = array();
        }

        return $options;
    }

    public function determineMostRecentPost($forum) {
        $topics = new \Application\Service\Topics;

        $forumTopics = $topics->getForForum($forum->get('forum_id'), null, null, true);

        $lastPost = null;

        if(array_key_exists(0, $forumTopics)) {
            $lastPost = $forumTopics[0]->get('last_post');
        }

        $forum->update('last_post', $lastPost);

        $this->commitChanges($forum);
    }
}