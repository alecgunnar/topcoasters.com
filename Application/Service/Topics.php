<?php

namespace Application\Service;

class Topics extends Standard {
    protected function setUp() {
        $this->primaryKey = 'topic_id';
        $this->dbTable    = 'topics';
        $this->model      = 'topic';
    }

    public function create($title, \Application\Model\Forum $forum) {
        $time = new \Application\Lib\Time(null, true);

        $id = $this->db->put(array('name'      => $title,
                                   'forum_id'  => $forum->get('forum_id'),
                                   'member_id' => \Maverick\Lib\Output::getGlobalVariable('member')->get('member_id'),
                                   'post_date' => $time->getTimestamp()), 'topics');

        if($id) {
            $topic = $this->get($id);
            $topic->update('seo_title', \Application\Lib\Utility::generateSeoTitle($topic->getName(), $topic->get('topic_id')));

            return $this->commitChanges($topic);
        }

        return false;
    }

    public function getForForum($forumId, $start=null, $limit=null) {
        $query = array('select' => '*',
                       'from'   => 'topics',
                       'where'  => 'forum_id = ' . $forumId,
                       'order'  => 'last_post_date desc');

        if(!is_null($start) && !is_null($limit)) {
            $query['limit'] = $start . ', ' . $limit;
        }

        $topics = $this->db->get($query, 'topic');

        return $topics;
    }

    public function postTo(\Application\Model\Post $post, \Application\Model\Topic $topic, $first=false) {
        $forum = $topic->getForum();

        if(!$first) {
            $forum->increase('num_posts');
            $topic->increase('num_replies');
        } else {
            $forum->increase('num_topics');
        }

        $topic->update('last_post_date', $post->get('post_date'));
        $topic->update('last_post', $post->get('post_id'));
        $forum->update('last_post', $post->get('post_id'));

        $forums = new \Application\Service\Forums;
        $forums->commitChanges($forum);

        $this->commitChanges($topic);
    }
}