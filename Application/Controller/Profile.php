<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Profile extends \Maverick\Lib\Controller {
    private $member = null;

    private $panes = array(''               => 'About',
                           'track-record'   => 'Track Record',
                           'topics'         => 'Forum Topics',
                           'track-exchange' => 'Track Exchange');

    private $paneTplVars = array();

    public function main($seoTitle='', $pane='') {
        Output::setGlobalVariable('search_box_text', 'Search the members');

        $members = new \Application\Service\Members;
        $member  = $members->get($seoTitle, 'seo_title');

        if(!$member) {
            \Maverick\Lib\Router::throw404();
        }

        $this->member = $member;

        Output::setPageTitle($this->member->getName());

        $activePane = '';
        $activeName = $this->panes[$activePane];

        foreach($this->panes as $link => $label) {
            if($pane == $link && $pane) {
                $activePane = $link;
                $activeName = str_replace(' ', '', $label);
            }
        }

        $method = 'show' . $activeName . 'Pane';

        $this->setPaneTplVar('showMember', $this->member);

        $args = func_get_args();
        array_splice($args, 0, 2);

        if(!method_exists($this, $method)) {
            $method     = 'showAboutPane';
            $activeName = 'About';
        }

        call_user_func_array(array($this, $method), $args);

        $tpl = Output::getTplEngine()->getTemplate('ProfilePanes/' . $activeName, $this->paneTplVars);

        $sessions = new \Application\Service\Sessions;

        $this->setVariables(array('showMember'    => $this->member,
                                  'activeSession' => $sessions->getMostRecent($this->member),
                                  'activePane'    => $activePane,
                                  'panes'         => $this->panes,
                                  'paneTpl'       => $tpl));
    }

    private function setPaneTplVar($name, $value) {
        $this->paneTplVars[$name] = $value;
    }

    private function showAboutPane() {
        $data = array();

        $data['General Info'] = array();
        $generalInfo          =& $data['General Information'];

        if(\Application\Lib\Members::checkUserStatus()) {
            $generalInfo['Location']   = $this->member->get('location');
            $generalInfo['Education']  = $this->member->get('education');
            $generalInfo['Occupation'] = $this->member->get('occupation');
            $generalInfo['Interests']  = $this->member->get('interests');
            $generalInfo['Website']    = $this->member->getWebsiteLink();
        }

        $generalInfo['Joined']      = ucfirst($this->member->getDate('reg_date')->getShortTime());
        $generalInfo['Last Active'] = ucfirst($this->member->getDate('last_active')->getShortTime());

        $data['Top Amusement Parks'] = array();
        $favParks                    =& $data['Top Amusement Parks'];

        $amusementParks = new \Application\Service\AmusementParks;

        $getParkName = function($parkId) use($amusementParks) {
            $park = $amusementParks->get($parkId);

            if($park) {
                return $park->getLink() . ($park->getLocation() ? ' in ' . $park->getLocation() : '');
            }
        };

        $favParks['First Park']    = $getParkName($this->member->get('first_park'));
        $favParks['Favorite Park'] = $getParkName($this->member->get('favorite_park'));
        $favParks['Home Park']     = $getParkName($this->member->get('home_park'));

        $data['Top Roller Coasters'] = array();
        $favCoasters                 =& $data['Top Roller Coasters'];

        $rollerCoasters = new \Application\Service\RollerCoasters;

        $getCoasterName = function($coasterId) use($rollerCoasters) {
            $coaster = $rollerCoasters->get($coasterId);

            if($coaster) {
                return $coaster->getLink() . ' at ' . $coaster->getPark()->getLink();
            }
        };

        $favCoasters['First Coaster']                 = $getCoasterName($this->member->get('first_coaster'));
        $favCoasters['Favorite Coaster']              = $getCoasterName($this->member->get('overall_fav_coaster'));
        $favCoasters['Favorite Steel Coaster']        = $getCoasterName($this->member->get('fav_steel_coaster'));
        $favCoasters['Favorite Wooden Coaster']       = $getCoasterName($this->member->get('fav_wooden_coaster'));
        $favCoasters['Favorite Twisted Coaster']      = $getCoasterName($this->member->get('fav_twisted_coaster'));
        $favCoasters['Favorite Out and Back Coaster'] = $getCoasterName($this->member->get('fav_out_and_back_coaster'));

        $this->setPaneTplVar('data', $data);
    }

    private function showTrackRecordPane($page=1) {
        $favorites = new \Application\Service\Favorites;
        $allFavs   = $favorites->getCoastersForMember($this->member->get('member_id'));
        $totalFavs = count($allFavs);

        if($totalFavs) {
            $limit = 15;

            list($pages, $page, $start) = \Application\Lib\Utility::calculatePagination($totalFavs, $limit, $page);

            $memberFavs = $favorites->getCoastersForMember($this->member->get('member_id'), null, $start, $limit);

            $this->setPaneTplVar('favorites', $memberFavs);
            $this->setPaneTplVar('paginationLinks', \Application\Lib\Utility::getPaginationLinks('/profile/' . $this->member->get('seo_title') . '/track-record/%d', $page, $pages));
        }
    }

    private function showForumTopicsPane($page=1) {
        $topics    = new \Application\Service\Topics;
        $allTopics = $topics->getForMember($this->member->get('member_id'));

        if($allTopics) {
            $totalTopics = count($allTopics);
            $limit       = 15;

            list($pages, $page, $start) = \Application\Lib\Utility::calculatePagination($totalTopics, $limit, $page);

            $memberTopics = $topics->getForMember($this->member->get('member_id'), $start, $limit);

            $this->setPaneTplVar('topics', $memberTopics);
            $this->setPaneTplVar('paginationLinks', \Application\Lib\Utility::getPaginationLinks('/profile/' . $this->member->get('seo_title') . '/topics/%d', $page, $pages));
        }
    }

    private function showTrackExchangePane($page=1) {
        $exchangeFiles = new \Application\Service\Exchange;

        $allFiles = $exchangeFiles->get($this->member->get('member_id'), 'member_id', null, null, true);

        if($allFiles) {
            $limit = \Maverick\Maverick::getConfig('Exchange')->get('files_per_page');
    
            list($pages, $page, $start) = \Application\Lib\Utility::calculatePagination(count($allFiles), $limit, $page);
    
            $this->setPaneTplVar('paginationLinks', \Application\Lib\Utility::getPaginationLinks('/profile/' . $this->member->get('seo_title') . '/track-exchange/%d', $page, $pages));
            $this->setPaneTplVar('files', $exchangeFiles->get($this->member->get('member_id'), 'member_id', $start, $limit, true));
        }
    }
}