<?php

namespace Application\Model;

class Topic extends Standard {
    protected $forum    = null;
    protected $lastPost = null;

    protected function setUp() { }

    private function setForum() {
        $forums = new \Application\Service\Forums;
        $forum  = $forums->get($this->get('forum_id'));

        if(count($forum) == 1) {
            $this->forum = $forum;
        } else {
            $this->forum = false;
        }
    }

    public function getForum() {
        if(is_null($this->forum)) {
            $this->setForum();
        }

        return $this->forum;
    }

    public function setUrl() {
        $this->url = '/forums/topic/' . $this->get('topic_id') . '/' . $this->get('seo_title');
    }

    private function setLastPost() {
        $this->lastPost = false;

        $posts = new \Application\Service\Posts;
        $post  = $posts->get($this->get('last_post'));

        if($post) {
            $this->lastPost = $post;
        }
    }

    public function getLastPost() {
        if(is_null($this->lastPost)) {
            $this->setLastPost();
        }

        return $this->lastPost;
    }
}