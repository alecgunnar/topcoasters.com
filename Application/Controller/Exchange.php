<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Exchange extends \Maverick\Lib\Controller {
    public static function rootSetup() {
        Output::setGlobalVariable('search_box_text', 'Search the exchange');
        Output::setGlobalVariable('search_box_what', 'exchange');
    }

    public function main($category=null, $page=1) {
        $categories  = array(0 => array(null, 'All Files'));
        $categories  = array_merge($categories, \Maverick\Maverick::getConfig('Exchange')->getAsArray('categories'));
        $active      = null;
        $activeTitle = '';

        if($category == 'all') {
            $category = '';
        }

        foreach($categories as $id => $data) {
            if($data[0] == $category) {
                $active      = $id;
                $activeTitle = $data[1];

                break;
            }
        }

        if(is_null($active)) {
            \Maverick\Lib\Router::throw404();
        }

        Output::setPageTitle('Track Exchange - ' . $activeTitle);

        $this->setVariable('active',      $active);
        $this->setVariable('activeTitle', $activeTitle);
        $this->setVariable('categories',  $categories);

        $catId  = '*';
        $column = null;

        if($active > 0) {
            $catId  = $active;
            $column = 'category';
        }

        $exchangeFiles = new \Application\Service\Exchange;

        $allFiles = $exchangeFiles->get($catId, $column, 0, 10, true);
        $limit    = 10;

        list($pages, $page, $start) = \Application\Lib\Utility::calculatePagination(count($allFiles), $limit, $page);

        $this->setVariable('paginationLinks', \Application\Lib\Utility::getPaginationLinks('/exchange/' . ($category ?: 'all') . '/%d', $page, $pages));
        $this->setVariable('files', $exchangeFiles->get($catId, $column, $start, $limit, true));
    }
}