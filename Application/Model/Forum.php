<?php

namespace Application\Model;

class Forum extends Standard {
    protected $lastPost = null;

    protected function setUp() { }

    protected function setUrl() {
        $this->url = '/forums/' . $this->get('forum_id') . '/' . $this->seo_title;
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