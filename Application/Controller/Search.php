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
        $totalResults = 0;

        Output::setGlobalVariable('search_query', $query);

        $rest = new \Maverick\Lib\Http_REST('https://www.googleapis.com/customsearch/v1');

        $rest->addParameters(array('key' => 'AIzaSyCmp0-0Aqp4v1H-tNhime9j2LmMn0L0OVw',
                                   'cx'  => '204133233658-vd4l11aqdvokj03dmjddqc7tr730kb7l.apps.googleusercontent.com',
                                   'q'   => urlencode($query)));

        if($rest->get()) {
            dump($rest->getResponse());
        }

        $this->setVariable('total_results', $totalResults);
    }

    private function trimItUp($query) {
        return preg_replace('~[^a-z0-9" ]~i', '', $query);
    }
}