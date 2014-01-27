<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Database_AmusementParks extends \Maverick\Lib\Controller {
    private $minimizedData = array('park_id', 'seo_title', 'name', 'city', 'region', 'country', 'isNoName');

    public function main() {
        Output::setPageTitle('Amusement Parks');

        $sortedParks = array();

        $this->checkLocationsCache();

        $amusementParkFiltersForm = new \Application\Form\Database_AmusementParkFilters;

        $this->setVariable('filter_form', $amusementParkFiltersForm->render());

        if($amusementParkFiltersForm->isSubmissionValid()) {
            $sortedParks = $this->getFilteredResults($amusementParkFiltersForm);

            $this->setVariable('filtered_results', true);
        } else {
            $amusementParkCache = new \Maverick\Lib\Cache('amusementParks');
            $sortedParks        = $amusementParkCache->get();

            if(!$sortedParks) {
                $amusementParks = new \Application\Service\AmusementParks;
                $amusementParkCache->set($amusementParks->getAll());
            }
        }

        $this->setVariable('amusementParks', $sortedParks);
    }

    private function checkLocationsCache() {
        $amusementParkLocationCache = new \Maverick\Lib\Cache('amusementParksLocations');

        if(!$amusementParkLocationCache->get()) {
            $amusementParks = new \Application\Service\AmusementParks;
            $parks          = $amusementParks->getAll();
            $sortedParks    = \Application\Lib\Database::sortResults($parks, $this->minimizedData);

            $locations = array();

            foreach($parks as $num => $park) {
                if($park->get('country')) {
                    if(!array_key_exists($park->get('country'), $locations)) {
                        $locations[$park->get('country')] = array();
                    }

                    if($park->get('region')) {
                        if(!array_key_exists($park->get('region'), $locations[$park->get('country')])) {
                            $locations[$park->get('country')][$park->get('region')] = 1;
                        }

                        $locations[$park->get('country')][$park->get('region')]++;
                    }

                    ksort($locations[$park->get('country')]);
                }
            }

            ksort($locations);

            $amusementParkLocationCache->set($locations);
        }
    }

    private function getFilteredResults($form) {
        $amusementParks = new \Application\Service\AmusementParks;

        return \Application\Lib\Database::sortResults($amusementParks->getFiltered($form->submit()), $this->minimizedData);
    }
}