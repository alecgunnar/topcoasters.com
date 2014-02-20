<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Forums_View extends \Maverick\Lib\Controller {
    public function main($forumId=0, $forum='', $page=1) {
        $forums = new \Application\Service\Forums;
        $forum  = $forums->get(intval($forumId));

        if(!$forum) {
            \Maverick\Lib\Router::throw404();
        }

        if(!is_numeric($forum->get('parent_id'))) {
            \Application\Lib\Utility::showError('You cannot view this forum.');
        }

        if($forum->get('staff_view') && !\Application\Lib\Members::checkUserIsMod()) {
            \Application\Lib\Utility::showError('You do not have permission to view this forum.');
        }

        Output::setPageTitle($forum->getName());

        $topics    = new \Application\Service\Topics;
        $allTopics = $topics->getForForum($forum->get('forum_id'));
        $limit     = \Maverick\Maverick::getConfig('Forums')->get('topics_per_page');

        list($pages, $page, $start) = \Application\Lib\Utility::calculatePagination(count($allTopics), $limit, $page);

        $pageTopics = $topics->getForForum($forum->get('forum_id'), $start, $limit);

        $this->setVariable('forum', $forum);
        $this->setVariable('topics', $pageTopics);
        $this->setVariable('paginationLinks', \Application\Lib\Utility::getPaginationLinks($forum->getUrl() . '/%d', $page, $pages));
    }
}