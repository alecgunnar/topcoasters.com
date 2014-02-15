<?php

namespace Application\Service;

class RollerCoasters extends Standard {
    protected function setUp() {
        $this->dbTable    = 'roller_coasters';
        $this->primaryKey = 'coaster_id';
        $this->model      = 'RollerCoaster';
    }

    /**
     * Gets a roller coaster for the flex slider
     *
     * @param  string $coaster
     * @param  string $park
     * @return \Application\Model\RollerCoaster | null
     */
    public function getForFlex($coaster, $park) {
        $coasters = $this->get($coaster, 'name', null, null, true);

        if(!is_null($coasters)) {
            foreach($coasters as $c) {
                if($c->getPark()->getName() == $park) {
                    return $c;
                }
            }
        }

        return null;
    }

    /**
     * Gets the highest rated roller coasters
     *
     * @param  mixed  $value
     * @param  string $column=''
     * @return array
     */
    public function getHighestRated($limit=7) {
        return $this->db->get(array('select' => 'coaster_id, seo_title, name, park_id, rating',
                                    'from'   => 'roller_coasters',
                                    'order'  => 'rating desc, name asc',
                                    'limit'  => $limit), 'RollerCoaster');
    }

    /**
     * Gets the coasters from filters
     *
     * @param  array $filters
     * @return array
     */
    public function getFiltered($filters) {
        $where = '';

        foreach($filters as $db => $values) {
            $addToWhere = '';

            if(is_array($values)) {
                if(count($values)) {
                    $addToWhere .= '(';

                    foreach($values as $n => $value) {
                        if($n) {
                            $addToWhere .= ' || ';
                        }
    
                        $addToWhere .= '`' . $db . '` LIKE "%' . $value . '%"';
                    }

                    $addToWhere .= ')';
                }
            } else {
                $addToWhere .= '`' . $db . '` LIKE "' . $values . '"';
            }

            if($addToWhere) {
                if($where) {
                    $where .= ' && ';
                }

                $where .= $addToWhere;
            }
        }

        if($where) {
            return $this->db->get(array('select' => 'coaster_id, seo_title, name, park_id, rating',
                                        'from'   => 'roller_coasters',
                                        'where'  => $where,
                                        'order'  => 'name asc'), 'RollerCoaster');
        } else {
            return $this->getAll();
        }
    }

    public function getForPark($parkId) {
        return $this->db->get(array('select' => '*',
                                    'from'   => 'roller_coasters',
                                    'where'  => 'park_id = "' . $parkId . '"',
                                    'order'  => 'name asc, rating desc'), 'RollerCoaster');
    }

    public function getOptions() {
        $amusementParks = new AmusementParks;

        $parks = $amusementParks->getAll('name', 'asc');

        $options = array();

        if(count($parks)) {
            foreach($parks as $numPark => $park) {
                $coasters = $this->getForPark($park->get('park_id'));

                if(count($coasters)) {
                    foreach($coasters as $numCoaster => $coaster) {
                        $options[$park->get('name')][$coaster->get('coaster_id')] = $coaster->get('name');
                    }
                }
            }
        }

        return $options;
    }
}