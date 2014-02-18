<?php

namespace Application\Service;

class Favorites extends Standard {
    protected function setUp() {
        $this->dbTable    = 'favorites';
        $this->primaryKey = 'favorite_id';
        $this->model      = 'Favorite';
    }

    public function getCoastersForMember($memberId, $coasterId=0, $start=null, $end=null) {
        $where = 'member_id = ' . $memberId . ' AND coaster_id ';

        if($coasterId) {
            $where .= '= ' . $coasterId;
        } else {
            $where .= 'IS NOT NULL';
        }

        $query = array('select' => '*',
                       'from'   => 'favorites',
                       'where'  => $where,
                       'order'  => 'rating desc, times_ridden desc');

        if(!is_null($start)) {
            $query['limit'] = $start . (!is_null($end) ? ', ' . $end : '');
        }

        $favorites = $this->db->get($query, $this->model);

        if($coasterId) {
            if(count($favorites) == 1) {
                return $favorites[0];
            }
        } else {
            return $favorites;
        }

        return false;
    }

    public function getParksForMember($memberId, $parkId=0) {
        $where = 'member_id = ' . $memberId . ' AND park_id ';

        if($parkId) {
            $where .= '= ' . $parkId;
        } else {
            $where .= 'IS NOT NULL';
        }

        $favorites = $this->db->get(array('select' => '*',
                                          'from'   => 'favorites',
                                          'where'  => $where,
                                          'order'  => 'rating desc'), $this->model);

        if($parkId) {
            if(count($favorites) == 1) {
                return $favorites[0];
            }
        } else {
            return $favorites;
        }

        return false;
    }

    public function getCoasterFavoritesOptions($memberId) {
        $favs    = $this->getCoastersForMember($memberId);
        $options = array();

        foreach($favs as $num => $fav) {
            $options[$fav->getCoaster()->get('coaster_id')] = $fav->getCoaster()->getName() . ' (' . $fav->getCoaster()->getPark()->getName() . ')';
        }

        return $options;
    }

    public function getParkFavoritesOptions($memberId) {
        $favs    = $this->getParksForMember($memberId);
        $options = array();

        foreach($favs as $num => $fav) {
            $options[$fav->getPark()->get('park_id')] = $fav->getPark()->getName() . ' (' . $fav->getPark()->getLocation() . ')';
        }

        return $options;
    }
}