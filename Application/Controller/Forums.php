<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Forums extends \Maverick\Lib\Controller {
    public function main($forumId=0, $forum='') {
        if($forumId) {
            \Maverick\Lib\Router::loadController('Forums_View', array($forumId, $forum));
        }

        Output::setPageTitle('Discussion Forums');

        $forums = new \Application\Service\Forums;

        $this->setVariable('forums', $forums->getAllInOrder());
    }
}