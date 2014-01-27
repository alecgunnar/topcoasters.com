<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Forums_Topic extends \Maverick\Lib\Controller {
    public function main($topicId=0, $topic='', $page=1) {
        $topics = new \Application\Service\Topics;
        $topic  = $topics->get(intval($topicId));

        if(!$topic) {
            \Maverick\Lib\Router::throw404();
        }

        Output::setPageTitle($topic->getName());

        $posts    = new \Application\Service\Posts;
        $allPosts = $posts->getForTopic($topic->get('topic_id'));

        $limit = \Maverick\Maverick::getConfig('Forums')->get('posts_per_page');

        list($pages, $page, $start) = \Application\Lib\Utility::calculatePagination(count($allPosts), $limit, $page);

        $pagePosts = $posts->getForTopic($topic->get('topic_id'), $start, $limit);

        $this->setVariable('topic', $topic);
        $this->setVariable('posts', $pagePosts);
        $this->setVariable('paginationLinks', \Application\Lib\Utility::getPaginationLinks($topic->getUrl() . '/%d', $page, $pages));
    }
}