<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Search extends \Maverick\Lib\Controller {
    private $searchAreas = array(''         => array('Forums',   '(SELECT topic_id, forum_id, 0 as post_id, seo_title, name FROM topics WHERE MATCH(name) AGAINST("%1$s")) UNION (SELECT topic_id, 0 as forum_id, post_id, 0 as seo_title, 0 as name FROM posts WHERE MATCH(message) AGAINST("%1$s"))'),
                                 'database' => array('Database', '(SELECT park_id, 0 as coaster_id, seo_title, name, city, region, country FROM amusement_parks WHERE MATCH(name) AGAINST("%1$s")) UNION (SELECT park_id, coaster_id, seo_title, name, NULL as city, NULL as region, NULL as country FROM roller_coasters WHERE MATCH(name) AGAINST("%1$s"))'),
                                 'exchange' => array('Exchange', 'SELECT file_id, name, category, screenshot FROM exchange_files WHERE MATCH(name,description) AGAINST("%1$s")'),
                                 'members'  => array('Members',  'SELECT member_id, seo_title, name, profile_picture_type FROM members WHERE name LIKE "%s"'));

    private $db = null;

    public function main($what='') {
        Output::setPageTitle('Search');

        $get = new \Maverick\Lib\Model_Input($_GET);

        if(!$get->get('q')) {
            \Application\Lib\Utility::showError('You must enter something to search for!');
        }

        if(strlen($get->get('q')) < 4) {
            \Application\Lib\Utility::showError('Please enter a bigger search string.');
        }

        if(!array_key_exists($what, $this->searchAreas)) {
            $what = '';
        }

        Output::setGlobalVariable('search_box_what', $what);

        $active   = $this->searchAreas[$what];
        $this->db = new \Maverick\Lib\DataSource_MySql;

        $query   = sprintf($active[1], $get->get('q'));
        $result  = $this->db->query($query . ' LIMIT 20');
        $results = '';
        
        if($result->num_rows) {
            $parseMethod = 'parse' . $active[0] . 'Results';

            if(method_exists($this, $parseMethod)) {
                $results = Output::getTplEngine()->getTemplate('SearchResults/' . $active[0], array('results' => $this->$parseMethod($result)));
            }
        }

        $this->setVariables(array('areas'         => $this->searchAreas,
                                  'what'          => $what,
                                  'query'         => $get->get('q'),
                                  'total_results' => $result->num_rows,
                                  'results'       => $results));
    }

    private function parseForumsResults($result) {
        $results = array();

        while($r = $this->db->fetch($result)) {
            if($r['post_id'] > 0) {
                $results[] = new \Application\Model\Post($r);
            } else {
                $results[] = new \Application\Model\Topic($r);
            }
        }

        return $results;
    }

    private function parseDatabaseResults($result) {
        $results = array();

        while($r = $this->db->fetch($result)) {
            if($r['coaster_id'] > 0) {
                $results[] = new \Application\Model\RollerCoaster($r);
            } else {
                $results[] = new \Application\Model\AmusementPark($r);
            }
        }

        return $results;
    }

    private function parseMembersResults($result) {
        $results = array();

        while($r = $this->db->fetch($result)) {
            $results[] = new \Application\Model\Member($r);
        }

        return $results;
    }

    private function parseExchangeResults($result) {
        $results = array();

        while($r = $this->db->fetch($result)) {
            $results[] = new \Application\Model\ExchangeFile($r);
        }

        return $results;
    }
}