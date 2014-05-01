<?php

namespace Application\Service;

class Topics extends Standard {
    protected function setUp() {
        $this->primaryKey = 'topic_id';
        $this->dbTable    = 'topics';
        $this->model      = 'Topic';
    }

    public function create($title, \Application\Model\Forum $forum) {
        $time = new \Application\Lib\Time;

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

    public function getForForum($forumId, $start=null, $limit=null, $noPinned=false) {
        $query = array('select' => '*',
                       'from'   => 'topics',
                       'where'  => 'forum_id = ' . $forumId . ($noPinned ? ' && moved_to IS NULL' : ''),
                       'order'  => ($noPinned ? '' : 'pinned desc, ') . 'last_post_date desc');

        if(!is_null($start) && !is_null($limit)) {
            $query['limit'] = $start . ', ' . $limit;
        }

        return $this->db->get($query, 'Topic');
    }

    public function getForMember($memberId, $start=null, $limit=null) {
        $query = array('order' => 'post_date desc');
        return $this->get($memberId, 'member_id', $start, $limit, true, $query);
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

    public function getRecentNews($start=0, $limit=7) {
        $query = array('select' => '*',
                       'from'   => 'topics',
                       'where'  => '`featured` = "1"',
                       'order'  => 'post_date desc');

        if(is_numeric($limit)) {
            $query['limit'] = $start . ', ' . $limit;
        }

        $topics = $this->db->get($query, 'Topic');

        return $topics;
    }

    public function clearOldMovedLinks($topic) {
        $this->db->delete(array('moved_to' => $topic->get('topic_id')), 'topics');
    }

    public function determineMostRecentPost($topic) {
        $posts = new \Application\Service\Posts;

        $topicPosts = $posts->getForTopic($topic->get('topic_id'));

        $lastPost = $topicPosts[count($topicPosts) - 1];

        $topic->update('last_post_date', $lastPost->getDate('post_date')->getTimestamp());
        $topic->update('last_post', $lastPost->get('post_id'));

        $this->commitChanges($topic);
    }
}