<?php

namespace Application\Service;

class AmusementParks extends Standard {
    protected function setUp() {
        $this->dbTable    = 'amusement_parks';
        $this->primaryKey = 'park_id';
        $this->model      = 'AmusementPark';
    }

    /**
     * Gets the highest rated roller coasters
     *
     * @param  mixed  $value
     * @param  string $column=''
     * @return array
     */
    public function getHighestRated($limit=7) {
        return $this->db->get(array('select' => 'park_id, seo_title, name, city, region, country, rating',
                                    'from'   => 'amusement_parks',
                                    'order'  => 'rating desc, name asc',
                                    'limit'  => $limit), 'AmusementPark');
    }

    /**
     * Gets all of the filtered results
     *
     * @param  array $filters
     * @return array
     */
    public function getFiltered($filters) {
        if(count($filters)) {
            $where = '';

            foreach($filters as $country => $regions) {
                foreach($regions as $region => $count) {
                    if($where) {
                        $where .= ' || ';
                    }

                    $where .= '( `country` = "' . $country . '" && `region` = "' . $region . '")';
                }
            }

            return $this->db->get(array('select' => 'park_id, seo_title, name, city, region, country, rating',
                                    'from'   => 'amusement_parks',
                                    'where'  => $where,
                                    'order'  => 'name asc'), 'AmusementPark');
        } else {
            return $this->getAll();
        }
    }

    /**
     * Gets an array of parks for a select field
     *
     * @return array
     */
    public function getOptions() {
        $parkOptionsCache = new \Maverick\Lib\Cache('amusementParkOptions');
        $options          = $parkOptionsCache->get();

        if(!$options) {
            $parks = $this->db->get(array('select' => '*',
                                          'from'   => 'amusement_parks',
                                          'order'  => 'country asc, name asc, region asc'));
    
            $options = array();
    
            if(count($parks)) {
                foreach($parks as $num => $park) {
                    $options[$park['country']][$park['park_id']] = $park['name'] . ($park['region'] ? ' (' . $park['region'] . ')' : '');
                }
            }

            $parkOptionsCache->set($options);
        }

        return $options;
    }
}