<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Layouts_Default extends \Maverick\Lib\Controller {
    public function main($variables) {
        $this->setVariables($variables);

        $navigationLinksConfig = \Maverick\Maverick::getConfig('MainNavigation')->getAsArray();
        $navigationLinks       = array();
        $urlPath               = '/' . trim(\Maverick\Lib\Router::getUri()->getResourcePath(), '/');

        foreach($navigationLinksConfig as $label => $path) {
            $isActive = (strpos($urlPath, $path) === 0);

            if($path == '/' && $urlPath != '/') {
                $isActive = false;
            }

            $navigationLinks[$label] = array($path, $isActive);
        }

        $this->setVariable('navigationLinks', $navigationLinks);

        $searchForm = new \Application\Form\Search(Output::getGlobalVariable('search_box_text'), Output::getGlobalVariable('search_box_what'));

        $this->setVariable('searchForm', $searchForm->render());
    }
}