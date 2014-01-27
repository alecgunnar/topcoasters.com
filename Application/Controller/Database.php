<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Database extends \Maverick\Lib\Controller {
    public function main() {
        Output::setPageTitle('Database');

        Output::setGlobalVariable('search_box_text', 'Search the database');

        $topCoastersCache = new \Maverick\Lib\Cache('topRatedCoasters', 3600);
        $topCoasters      = $topCoastersCache->get();

        if(!$topCoasters) {
            $rollerCoasters = new \Application\Service\RollerCoasters;
            $topCoasters    = $rollerCoasters->getHighestRated();

            $topCoastersCache->set($topCoasters);
        }

        $this->setVariable('topCoasters', $topCoasters);

        $topParksCache = new \Maverick\Lib\Cache('topRatedParks', 3600);
        $topParks      = $topParksCache->get();

        if(!$topParks) {
            $amusementParks = new \Application\Service\AmusementParks;
            $topParks    = $amusementParks->getHighestRated();

            $topParksCache->set($topParks);
        }

        $this->setVariable('topParks', $topParks);
    }
}