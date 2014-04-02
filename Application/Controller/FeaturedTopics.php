<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class FeaturedTopics extends \Maverick\Lib\Controller {
    public function main($page=1) {
        Output::setPageTitle('News and Updates');

        $topics    = new \Application\Service\Topics;
        $allTopics = $topics->getRecentNews(false, false);
        
        $limit = \Maverick\Maverick::getConfig('Forums')->get('topics_per_page');
        
        list($pages, $page, $start) = \Application\Lib\Utility::calculatePagination(count($allTopics), $limit, $page);

        $this->setVariable('articles', $topics->getRecentNews($start, $limit));
        $this->setVariable('paginationLinks', \Application\Lib\Utility::getPaginationLinks('/featured-topics/%d', $page, $pages));
    }
}