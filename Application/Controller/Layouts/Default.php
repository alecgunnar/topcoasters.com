<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Layouts_Default extends \Maverick\Lib\Controller {
    public function main($variables) {
        $this->setVariables($variables);

        $navigationLinksSet = 'public';
        $navigationLinks    = array();
        $urlPath            = '/' . trim(\Maverick\Lib\Router::getUri()->getPath(), '/');

        if(strpos($urlPath, '/admin') === 0) {
            $navigationLinksSet = 'admin';
        }

        $navigationLinksConfig = \Maverick\Maverick::getConfig('MainNavigation')->get($navigationLinksSet)->getAsArray();

        foreach($navigationLinksConfig as $label => $path) {
            $isActive = (strpos($urlPath, $path) === 0);

            if(($path == '/' && $urlPath != '/') || ($path == '/admin' && $urlPath != '/admin')) {
                $isActive = false;
            }

            $navigationLinks[$label] = array($path, $isActive);
        }

        $get = new \Maverick\Lib\Model_Input($_GET);

        $this->setVariables(array('navigationLinks'  => $navigationLinks,
                                  'search_box_value' => $get->get('q')));
    }
}