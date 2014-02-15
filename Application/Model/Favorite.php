<?php

namespace Application\Model;

class Favorite extends Standard {
    protected $coaster = null;
    protected $url     = null;

    protected function setUp() { }

    protected function setUrl() {
        $type = 'coasters';

        if(is_numeric($this->get('park_id'))) {
            $type = 'parks';
        }

        $this->url = '/track-record/' . $type . '/edit/' . $this->get('favorite_id') . '#' . $this->get('favorite_id');
    }

    public function getUrl() {
        if(is_null($this->url)) {
            $this->setUrl();
        }

        return $this->url;
    }
}