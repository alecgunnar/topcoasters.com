<?php

namespace Application\Form;

class Database_AmusementParkFilters extends \Maverick\Lib\Form {
    /**
     * Builds the form to be rendered later
     *
     * @return null
     */
    public function build() {
        $this->setName('amusementParkFilters');
        $this->setTpl('Database/AmusementParkFilters');

        $this->renderFieldsWithFormTpl();

        $amusementParkLocationsCache = new \Maverick\Lib\Cache('amusementParksLocations');

        if($amusementParkLocationsCache->get()) {
            foreach($amusementParkLocationsCache->get() as $country => $regions) {
                $group = $this->addFieldGroup(base64_encode($country))
                    ->setLabel($country);

                foreach($regions as $region => $count) {
                    $group->addField('Input_CheckBox', base64_encode($region))
                        ->setLabel($region)
                        ->setValue(1);
                }
            }
        }

        $this->addField('Input_Submit', 'submit')
            ->setValue('Apply Filters');
    }

    /**
     * Validates the form
     */
    public function validate() { }

    /**
     * Submits the form
     */
    public function submit() {
        $input   = $this->getModel();
        $filters = array();

        $amusementParkLocationsCache = new \Maverick\Lib\Cache('amusementParksLocations');

        if($amusementParkLocationsCache->get()) {
            foreach($amusementParkLocationsCache->get() as $country => $regions) {
                $group = base64_encode($country);

                $countryRegions = array();

                foreach($regions as $region => $count) {
                    $state = base64_encode($region);

                    if($input->get($group)->get($state)) {
                        $countryRegions[$region] = $count;
                    }
                }

                if(count($countryRegions)) {
                    $filters[$country] = $countryRegions;
                }
            }
        }

        return $filters;
    }
}