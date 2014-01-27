<?php

namespace Application\Model;

class Post extends Standard {
    protected $topic = null;
    protected $parsedMessage = null;

    protected function setUp() { }

    public function setUrl() {
        $this->url = $this->getTopic()->getUrl();
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

        $this->parsedMessage = $bbcode->parse($this->get('message'));
    }

    public function getParsedMessage() {
        if(is_null($this->parsedMessage)) {
            $this->setParsedMessage();
        }

        return $this->parsedMessage;
    }
}