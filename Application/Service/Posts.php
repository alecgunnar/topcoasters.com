<?php

namespace Application\Service;

class Posts extends Standard {
    protected function setUp() {
        $this->primaryKey = 'post_id';
        $this->dbTable    = 'posts';
        $this->model      = 'post';
    }

    public function create($message, \Application\Model\Topic $topic, $postAsFirst=false) {
        $time = new \Application\Lib\Time(null, true);

        $id = $this->db->put(array('topic_id'      => $topic->get('topic_id'),
                                   'member_id'     => \Maverick\Lib\Output::getGlobalVariable('member')->get('member_id'),
                                   'message'       => $message,
                                   'post_date'     => $time->getTimestamp(),
                                   'is_first_post' => $postAsFirst ? '1' : '0'), 'posts');

        if($id) {
            $post = $this->get($id);

            $topics = new \Application\Service\Topics;
            $topics->postTo($post, $topic, $postAsFirst);

            return $post;
        }

        return false;
    }

    public function getForTopic($topicId, $start=null, $limit=null) {
        $query = array('select' => '*',
                       'from'   => 'posts',
                       'where'  => 'topic_id = ' . $topicId,
                       'order'  => 'post_date asc');

        if(!is_null($start) && !is_null($limit)) {
            $query['limit'] = $start . ', ' . $limit;
        }

        $posts = $this->db->get($query, 'post');

        return $posts;
    }
}