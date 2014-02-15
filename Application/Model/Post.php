<?php

namespace Application\Model;

class Post extends Standard {
    protected $topic = null;
    protected $parsedMessage = null;

    protected function setUp() { }

    public function setUrl() {
        $posts         = new \Application\Service\Posts;
        $postsForTopic = $posts->getForTopic($this->getTopic()->get('topic_id'));
        $page          = 1;
        $number        = 0;

        foreach($postsForTopic as $index => $post) {
            if($post->get('post_id') == $this->post_id) {
                $number = $index + 1;
                break;
            }
        }

        $page = ceil(($number / \Maverick\Maverick::getConfig('Forums')->get('posts_per_page')));

        $this->url = $this->getTopic()->getUrl() . '/' . $page . '#' . $number;
    }

    private function setTopic() {
        $topics = new \Application\Service\Topics;
        $topic  = $topics->get($this->get('topic_id'));

        if(count($topic) == 1) {
            $this->topic = $topic;
        } else {
            $this->topic = false;
        }
    }

    public function getTopic() {
        if(is_null($this->topic)) {
            $this->setTopic();
        }

        return $this->topic;
    }

    private function setParsedMessage() {
        $bbcode = new \Application\Lib\BBCode;

        $this->parsedMessage = $bbcode->render($this->get('message'));
    }

    public function getParsedMessage() {
        if(is_null($this->parsedMessage)) {
            $this->setParsedMessage();
        }

        return $this->parsedMessage;
    }
}