<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Convert extends \Maverick\Lib\Controller {
    private $toDb   = null;
    private $fromDb = null;
    

    public function main($convert='') {
        $this->toDb   = new \Maverick\Lib\DataSource_MySql;
        $this->fromDb = new \Maverick\Lib\DataSource_MySql('old');

        switch($convert) {
            case 'coasters':
                //$this->convertCoasters();
                break;
            case 'parks':
                //$this->convertParks();
                break;
            //case 'lifts':
                $this->convertLifts();
                break;
            //case "favs":
                $this->convertFavs();
                break;
            default:
                \Application\Lib\Utility::showError('Uhhhhh...');
        }
    }

    private function convertCoasters() {
        $map = array('coaster_id' => 'coaster_id',
                     'park_id' => 'park_id',
                     'c_seo_title' => 'seo_title',
                     'c_manufacturer' => 'manufacturer',
                     'c_designer' => 'designer',
                     'c_name' => 'name',
                     'c_desc' => 'description',
                     'c_status' => 'status',
                     'c_status_year' => 'status_date',
                     'c_prev_id' => 'previous_id',
                     'c_cost' => 'cost',
                     'c_opened' => 'open_date',
                     'c_model' => 'track_model',
                     'c_layout' => 'layout_type',
                     'c_lifts' => 'transports',
                     'c_max_height' => 'height',
                     'c_track_length' => 'length',
                     'c_ride_time' => 'ride_time',
                     'c_top_speed' => 'speed',
                     'c_max_vert' => 'max_angle',
                     'c_inversions' => 'inversions',
                     'c_track' => 'track_type',
                     'c_twin_tracked' => 'twin_track',
                     'c_cars' => 'car_type',
                     'c_restraints' => 'restraints',
                     'c_trains' => 'num_trains',
                     'c_train_length' => 'num_cars',
                     'c_train_car_seats' => 'num_seats',
                     'c_capacity' => 'hourly_capacity',
                     'c_pov' => 'pov_videos');

        $data = $this->fromDb->get(array('select' => '*', 'from' => 'db_coasters'));

        foreach($data as $num => $coaster) {
            $insert = array();

            foreach($map as $from => $to) {
                $insert[$to] = $this->toDb->escape($coaster[$from]);
            }

            $expLayout             = explode(' ', $coaster['c_layout']);
            $insert['layout_type'] = strtolower($expLayout[0]);

            $this->toDb->put($insert, 'roller_coasters');
        }
    }

    private function convertParks() {
        $map = array('park_id' => 'park_id',
                     'p_seo_title' => 'seo_title',
                     'p_name' => 'name',
                     'p_tagline' => 'tag_line',
                     'p_street_addr' => 'address',
                     'p_opened' => 'opened',
                     'p_closed' => 'closed',
                     'p_website' => 'website',
                     'p_owner' => 'owner',
                     'p_operator' => 'operator',
                     'p_season_type' => 'season_type',
                     'p_season_start' => 'season_start',
                     'p_season_end' => 'season_end',
                     'p_season_dates_year' => 'season_year');

        $data = $this->fromDb->get(array('select' => '*', 'from' => 'db_parks'));

        foreach($data as $num => $park) {
            $insert = array();

            foreach($map as $from => $to) {
                $insert[$to] = $this->toDb->escape($park[$from]);
            }

            $expLocation = explode(',', $park['p_location']);

            if($count = count($expLocation)) {
                if($count == 2) {
                    $insert['region']  = trim($expLocation[0]);
                    $insert['country'] = trim($expLocation[1]);
                } else {
                    $insert['city']    = trim($expLocation[0]);
                    $insert['region']  = trim($expLocation[1]);
                    $insert['country'] = trim($expLocation[2]);
                }
            }

            $this->toDb->put($insert, 'amusement_parks');
        }
    }

    private function convertLifts() {
        $data = $this->fromDb->get(array('select' => '*', 'from' => 'db_coasters'));

        foreach($data as $num => $coaster) {
            if($coaster['c_lifts']) {
                $this->toDb->post(array('transports' => $this->toDb->escape($coaster['c_lifts'])), array('coaster_id' => $coaster['coaster_id']), 'roller_coasters');
            }
        }

        /*$rollerCoasters = new \Application\Service\RollerCoasters;
        $coasters       = $rollerCoasters->getAll();

        foreach($coasters as $num => $coaster) {
            $transports = '';

            if($coaster->get('transports')) {
                $transports = json_decode($coaster->get('transports'), true);
            }

            $serialized = serialize($transports);

            $coaster->update('transports', $serialized);

            $rollerCoasters->commitChanges($coaster);
        }*/
    }

    private function convertFavs() {
        $map = array('member_id'  => 'member_id',
                     'coaster_id' => 'coaster_id',
                     'rating'     => 'rating',
                     'ridden'     => 'times_ridden');

        $favs = $this->fromDb->get(array('select' => '*', 'from' => 'users_favorites'));

        foreach($favs as $num => $fav) {
            $insert = array();

            foreach($map as $from => $to) {
                $insert[$to] = $this->toDb->escape($fav[$from]);
            }

            $this->toDb->put($insert, 'favorites');
        }

        $favorites = new \Application\Service\Favorites;
        $coasters  = new \Application\Service\RollerCoasters;

        foreach($favorites->getAll() as $num => $fav) {
            $coaster = $fav->getCoaster();

            $rates  = $coaster->get('rates') + 1;
            $total  = $coaster->get('total_rates') + $fav->get('rating');
            $rating = $total / $rates;

            $coaster->update(array('rating'      => $rating,
                                   'total_rates' => $total,
                                   'rates'       => $rates));

            $coasters->commitChanges($coaster);
        }
    }
}