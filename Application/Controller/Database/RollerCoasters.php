<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Database_RollerCoasters extends \Maverick\Lib\Controller {
    private $minimizedData = array('coaster_id', 'seo_title', 'name', 'park_id');

    public function main() {
        Output::setPageTitle('Roller Coasters');

        $GET = new \Maverick\Lib\Model_Input($_GET);

        $sortedCoasters = array();

        $rollerCoasterFilterForm = new \Application\Form\Database_RollerCoasterFilters;
        $this->setVariable('filterForm', $rollerCoasterFilterForm->render());

        $filters = ($rollerCoasterFilterForm->isSubmissionValid() || is_numeric($GET->get('park'))) ? true : false;

        if($filters) {
            $sortedCoasters = $this->getFilteredResults($rollerCoasterFilterForm, $GET);

            $this->setVariable('filtered_results', true);
        } else {
            $rollerCoasterCache = new \Maverick\Lib\Cache('rollerCoasters');
            $sortedCoasters     = $rollerCoasterCache->get();
    
            if(!$sortedCoasters) {
                $rollerCoasters = new \Application\Service\RollerCoasters;
                $coasters       = $rollerCoasters->getAll('name', 'asc');

                $sortedCoasters = \Application\Lib\Database::sortResults($coasters, $this->minimizedData);

                $rollerCoasterCache->set($sortedCoasters);
            }
        }

        $this->setVariable('rollerCoasters', $sortedCoasters);
    }

    private function getFilteredResults($form, $get) {
        $where = array();

        if($get->get('park')) {
            $parkId = intval($get->get('park'));

            $amusementParks = new \Application\Service\AmusementParks;
            $park           = $amusementParks->get($parkId);

            if(count($park) == 1) {
                $where['park_id'] = $parkId;

                $this->setVariable('filter_park', $park[0]);
            }
        }

        if($form->isSubmissionValid()) {
            $where = array_merge($where, $form->submit());
        }

        $rollerCoasters = new \Application\Service\RollerCoasters;

        return \Application\Lib\Database::sortResults($rollerCoasters->getFiltered($where), $this->minimizedData);
    }
}