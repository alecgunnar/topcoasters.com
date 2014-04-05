<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Database_AmusementPark extends \Maverick\Lib\Controller {
    public function main($parkId=0, $park='', $approve='') {
        $amusementParks = new \Application\Service\AmusementParks;
        $park          = $amusementParks->get(intval($parkId));

        if(!$park) {
            \Maverick\Lib\Router::throw404();
        }

        if($approve === 'approve' && Output::getGlobalVariable('member')->get('is_admin') && !$park->get('approved')) {
            $park->update('approved', '1');

            $amusementParks->commitChanges($park);

            \Maverick\Lib\Http::location($park->getUrl(), 'This amusement park has been approved.');
        }

        $parkLocation = '';

        if($park->get('city') && $park->get('region')) {
            $parkLocation = $park->get('city') . ', ' . $park->get('region') . ($park->get('country') ? ', ' . $park->get('country') : '');
        }

        Output::setPageTitle($park->get('name') . ($parkLocation ? ' (' . $parkLocation . ')' : ''));

        $parkIsClosed = false;
        $time         = new \Application\Lib\Time;
        $start        = $park->get('season_start') ? $park->getDate('season_start') : null;
        $end          = $park->get('season_end')   ? $park->getDate('season_end')   : null;

        if($start && $end) {
            if($time < $start && $time->format('Y') <= $park->get('season_year')) {
                $parkIsClosed = true;
            }
        } elseif($start && !$end) {
            if($time < $start) {
                $parkIsClosed = true;
            }
        }

        $data = array('Opened'   => $park->getOpenedDate(),
                      'Closed'   => $park->getClosedDate(),
                      'Address'  => $park->get('address'),
                      'Location' => $park->getLocation(),
                      'Website'  => $park->getWebsiteLink(),
                      'Owner'    => $park->get('owner'),
                      'Operator' => $park->get('operator'));

        $rollerCoasters = new \Application\Service\RollerCoasters;
        $coasters       = $rollerCoasters->getForPark($park->get('park_id'));

        $futureCoasters  = array();
        $currentCoasters = array();
        $pastCoasters    = array();
        $tabs            = array();

        if(count($coasters)) {
            foreach($coasters as $num => $coaster) {
                switch($coaster->get('status')) {
                    case "defunc":
                    case "relocated":
                        $pastCoasters[] = $coaster;
                        break;
                    case "building":
                    case "rumored":
                        $futureCoasters[] = $coaster;
                        break;
                   default:
                        $currentCoasters[] = $coaster;
                }
            }
        }

        if(count($futureCoasters) || count($currentCoasters) || count($pastCoasters)) {
            $futureCoastersTpl  = Output::getTplEngine()->getTemplate('Blocks/ParkCoasterList', array('when' => 1,  'coasters' => $futureCoasters));
            $currentCoastersTpl = Output::getTplEngine()->getTemplate('Blocks/ParkCoasterList', array('when' => 0,  'coasters' => $currentCoasters));
            $pastCoastersTpl    = Output::getTplEngine()->getTemplate('Blocks/ParkCoasterList', array('when' => -1, 'coasters' => $pastCoasters));
    
            $tabs = array('Future Coasters'  => array(false, $futureCoastersTpl),
                          'Current Coasters' => array(true, $currentCoastersTpl),
                          'Past Coasters  '  => array(false, $pastCoastersTpl));
        }

        $favorite = false;

        if(\Application\Lib\Members::checkUserStatus()) {
            $favorites = new \Application\Service\Favorites;
            $favorite  = $favorites->getParksForMember(Output::getGlobalVariable('member')->get('member_id'), $park->get('park_id')) ?: null;
        }

        $this->setVariables(array('park'         => $park,
                                  'parkIsClosed' => $parkIsClosed,
                                  'data'         => $data,
                                  'coasterTabs'  => $tabs,
                                  'favorite'     => $favorite));
    }
}