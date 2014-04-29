<?php

namespace Application\Cache;

class Controller_FeaturedTopics {
    public function cache() {
        $topics = new \Application\Service\Topics;
        return $topics->getRecentNews();
    }
}