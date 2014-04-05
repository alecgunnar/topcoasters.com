<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Database_RollerCoaster extends \Maverick\Lib\Controller {
    public function main($coasterId='', $coaster='', $approve='') {
        $rollerCoasters = new \Application\Service\RollerCoasters;
        $coaster        = $rollerCoasters->get(intval($coasterId));

        if(!$coaster) {
            \Maverick\Lib\Router::throw404();
        }

        $park = $coaster->getPark();

        $parkLocation = '';

        if($park->get('city') && $park->get('region')) {
            $parkLocation = $park->get('city') . ', ' . $park->get('region') . ($park->get('country') ? ', ' . $park->get('country') : '');
        }

        Output::setPageTitle($coaster->get('name') . ' at ' . $park->get('name') . ($parkLocation ? ' (' . $parkLocation . ')' : ''));

        if($approve === 'approve' && Output::getGlobalVariable('member')->get('is_admin') && !$coaster->get('approved')) {
            $coaster->update('approved', '1');

            $rollerCoasters->commitChanges($coaster);

            \Maverick\Lib\Http::location($coaster->getUrl(), 'This roller coaster has been approved.');
        }

        $newLocationData = function($previous) {
            return $previous ? ($previous->getPark()->getLink() . ' as ' . $previous->getLink()) : '';
        };

        $openedText = 'Opened';

        if($coaster->get('status') == 'building') {
            $date    = $coaster->get('open_date');
            $expDate = explode('/', $date);

            if(count($expDate) == 2) {
                $date = \DateTime::createFromFormat('n/Y', $date)->format('n/t/Y');
            }

            $time     = new \Application\Lib\Time(null, true);
            $openTime = new \Application\Lib\Time($date, true);

            if($time >= $openTime) {
                $coaster->update('status', 'running');

                $rollerCoasters->commitChanges($coaster);
            } else {
                $openedText = 'Opening';
            }
        }

        $data = array('General Information' => array('Roller Coaster' => $coaster->getName(),
                                                     'Amusement Park' => $park->getLink(),
                                                     $openedText      => $coaster->getOpenedDate(),
                                                     'Status'         => $coaster->getStatus(),
                                                     'Manufacturer'   => $coaster->get('manufacturer'),
                                                     'Designer'       => $coaster->get('designer'),
                                                     'Cost'           => $coaster->getCost()),
                     'Location Information' => array('Moved From' => $newLocationData($coaster->getPrevious()),
                                                     'Moved To'   => $newLocationData($coaster->getNewLocation())),
                     'Track and Layout'     => array('Model'            => $coaster->get('track_model'),
                                                     'Track Type'       => $coaster->getTrackType(),
                                                     'Layout Type'      => $coaster->getLayoutType(),
                                                     'Launches & Lifts' => $coaster->getTransports(true)),
                     'Statistics'           => array('Maximum Height'         => $coaster->getHeight(),
                                                     'Track Length'           => $coaster->getLength(),
                                                     'Maximum Vertical Angle' => $coaster->getMaximumVertical(),
                                                     'Top Speed'              => $coaster->getSpeed(),
                                                     'Ride Time'              => $coaster->getRideTime(),
                                                     'Inversions'             => $coaster->get('inversions') ?: ''),
                     'Trains and Cars'      => array('Train Type'       => $coaster->getTrainType(),
                                                     'Number of Trains' => $coaster->get('num_trains') ?: '',
                                                     'Cars per Train'   => $coaster->get('num_cars') ?: '',
                                                     'Seats per Car'    => $coaster->get('num_seats') ?: '',
                                                     'Restraints'       => $coaster->getRestraints(),
                                                     'Hourly Capacity'  => $coaster->getHourlyCapacity()));

        $favorite = false;

        if(\Application\Lib\Members::checkUserStatus()) {
            $favorites = new \Application\Service\Favorites;
            $favorite  = $favorites->getCoastersForMember(Output::getGlobalVariable('member')->get('member_id'), $coaster->get('coaster_id')) ?: null;
        }

        $this->setVariables(array('coaster'  => $coaster,
                                  'park'     => $park,
                                  'data'     => $data,
                                  'favorite' => $favorite));
    }
}