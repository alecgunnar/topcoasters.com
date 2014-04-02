<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Forums extends \Maverick\Lib\Controller {
    public static function rootSetup() {
        Output::setGlobalVariable('search_box_text', 'Search the forums');
        Output::setGlobalVariable('search_box_what', 'forum');
    }

    public function main($forumId=0, $forum='', $page=1) {
        if($forumId) {
            \Maverick\Lib\Router::loadController('Forums_View', array($forumId, $forum, $page));
            
            return;
        }

        Output::setPageTitle('Discussion Forums');

        $forums = new \Application\Service\Forums;

        $this->setVariable('forums', $forums->getAllInOrder());
    }
}