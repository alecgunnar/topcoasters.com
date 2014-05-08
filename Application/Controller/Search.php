<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Search extends \Maverick\Lib\Controller {
    public function main($what='') {
        Output::setPageTitle('Search');

        if(!array_key_exists('q', $_GET) || !$_GET['q']) {
            \Application\Lib\Utility::showError('You must enter something to search for!');
        }

        if(strlen($_GET['q']) < 4) {
            \Application\Lib\Utility::showError('Please enter a bigger search string.');
        }

        $query        = $this->trimItUp($_GET['q']);
        $results      = array('items' => array());
        $totalResults = 0;

        Output::setGlobalVariable('search_query', $query);

        $rest = new \Maverick\Lib\Http_REST('https://www.googleapis.com/customsearch/v1');

        $rest->addParameters(array('key' => 'AIzaSyBxnjKgID_bosz-GDzUWPfDDr443fL577g',
                                   'cx'  => '012240979130840958263:5p4r96bw5uk',
                                   'q'   => urlencode($query)));

        if($rest->get()) {
            $results = json_decode($rest->getResponse(), true);
        }

        $this->setVariables(array('total_results' => count(array_key_exists('items', $results) ? $results['items'] : array()),
                                  'results'       => array_key_exists('items', $results) ? $results['items'] : array()));
    }

    private function trimItUp($query) {
        return preg_replace('~[^a-z0-9" ]~i', '', $query);
    }
}