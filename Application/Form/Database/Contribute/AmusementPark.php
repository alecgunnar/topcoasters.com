<?php

namespace Application\Form;

class Database_Contribute_AmusementPark extends \Maverick\Lib\Form {
    private $park     = null;
    private $fieldMap = array('generalInfo'  => array('name'     => 'name',
                                                      'tag_line' => 'tag_line',
                                                      'website'  => 'website',
                                                      'owner'    => 'owner',
                                                      'operator' => 'operator',
                                                      'opened'   => 'opened',
                                                      'closed'   => 'closed'),
                              'locationInfo' => array('city'    => 'city',
                                                      'region'  => 'region',
                                                      'country' => 'country',
                                                      'address' => 'address'),
                              'seasonInfo'   => array('season_type'  => 'season_type',
                                                      'season_year'  => 'season_year',
                                                      'season_start' => 'season_start',
                                                      'season_end'   => 'season_end'));

    public function __construct($park=null) {
        $this->park = $park ?: new \Application\Model\AmusementPark;

        parent::__construct();
    }

    protected function build() {
        $this->setName('amusementPark');
        $this->setTpl('Standard');

        $this->renderFieldsWithFormTpl();

        $generalInfo = $this->addFieldGroup('generalInfo')
            ->setLabel('About the Park');

        $generalInfo->addField('Input_Text', 'name')
            ->setLabel('Name of Amusement Park')
            ->setValue($this->park->get('isNoName') ? '' : $this->park->get('name'));

        $generalInfo->addField('Input_Text', 'tag_line')
            ->setLabel('Tag Line')
            ->setValue($this->park->get('tag_line'));

        $generalInfo->addField('Input_Text', 'website')
            ->setLabel('Website')
            ->setPlaceholder('http://')
            ->setValue($this->park->get('website'));

        $generalInfo->addField('Input_Text', 'owner')
            ->setLabel('Owner')
            ->setValue($this->park->get('owner'));

        $generalInfo->addField('Input_Text', 'operator')
            ->setLabel('Operated By')
            ->setValue($this->park->get('operator'));

        $generalInfo->addField('Input_Text', 'opened')
            ->setLabel('Date Opened')
            ->setValue($this->park->get('opened'));

        $generalInfo->addField('Input_Text', 'closed')
            ->setLabel('Date Closed')
            ->setValue($this->park->get('closed'));

        $locationInfo = $this->addFieldGroup('locationInfo')
            ->setLabel('Location of the Park');

        $locationInfo->addField('Input_Text', 'city')
            ->setLabel('City')
            ->setValue($this->park->get('city'));

        $locationInfo->addField('Input_Text', 'region')
            ->setLabel('State/Province')
            ->setValue($this->park->get('region'));

        $locationInfo->addField('Input_Text', 'country')
            ->setLabel('Country')
            ->setValue($this->park->get('country'));

        $locationInfo->addField('Input_Text', 'address')
            ->setLabel('Street Address')
            ->setPlaceholder('P. Sherman 42 Wallaby Way')
            ->setDescription('This field should only contain the address of the park, such as: "1 Cedar Point Drive" for Cedar Point.')
            ->setValue($this->park->get('address'));

        $seasonInfo = $this->addFieldGroup('seasonInfo')
            ->setLabel('Seasonal Operations');

        $seasonInfo->addField('Select', 'season_type')
            ->setLabel('Season Type')
            ->addOption(0, '-')
            ->addOptions(\Application\Lib\AmusementParks::getSeasonTypes())
            ->setValue($this->park->get('season_type'));

        $time        = new \Application\Lib\Time;
        $start       = $time->format('Y');
        $years       = range($start, $start + 5);
        $yearOptions = array();

        foreach($years as $n => $year) {
            $yearOptions[$year] = $year;
        }

        $seasonInfo->addField('Select', 'season_year')
            ->setLabel('Current Season')
            ->setDescription('This is the year the following opening and closing dates are for.')
            ->addOption(0, '-')
            ->addOptions($yearOptions)
            ->setValue($this->park->get('season_year'));

        $seasonInfo->addField('Input_Text', 'season_start')
            ->setLabel('Season Start Date')
            ->setValue($this->park->get('season_start'));

        $seasonInfo->addField('Input_Text', 'season_end')
            ->setLabel('Season End Date')
            ->setValue($this->park->get('season_end'));

        $this->addField('Input_Submit', 'submit')
            ->setValue('Save Amusement Park');
    }

    protected function validate() { }

    private function getValues($includeSeoTitle=false) {
        $input  = $this->getModel();
        $values = array();

        foreach($this->fieldMap as $field => $column) {
            $value = $input->get($field);

            if(is_array($column)) {
                foreach($column as $f => $c) {
                    $values[$c] = $value->get($f);
                }
            } else {
                $values[$column] = $value;
            }
        }

        $values['approved'] = 0;

        if($includeSeoTitle) {
            $values['seo_title'] = \Application\Lib\Utility::generateSeoTitle($input->get('generalInfo')->get('name'));
        }

        return $values;
    }

    public function submit() {
        $amusementParkCache = new \Maverick\Lib\Cache('amusementParks');
        $amusementParkCache->set(null);

        $amusementParkOptionsCache = new \Maverick\Lib\Cache('amusementParkOptions');
        $amusementParkOptionsCache->set(null);

        $amusementParkLocationsCache = new \Maverick\Lib\Cache('amusementParkLocations');
        $amusementParkLocationsCache->set(null);

        $topRatedParksCache = new \Maverick\Lib\Cache('topRatedParks');
        $topRatedParksCache->set(null);

        if($this->park->get('park_id')) {
            return $this->saveChanges();
        } else {
            return $this->addPark();
        }
    }

    private function addPark() {
        $amusementParks = new \Application\Service\AmusementParks;
        $insert         = $this->getValues();

        $insert['contributed_by'] = \Maverick\Lib\Output::getGlobalVariable('member')->get('member_id');

        $park = $amusementParks->put($insert);

        if($park instanceof \Application\Model\AmusementPark) {
            $park->update('seo_title', \Application\Lib\Utility::generateSeoTitle($park->get('name'), $park->get('park_id')));

            return $amusementParks->commitChanges($park);
        }

        return false;
    }

    private function saveChanges() {
        $amusementParks = new \Application\Service\AmusementParks;

        $this->park->update($this->getValues(true));

        return $amusementParks->commitChanges($this->park);
    }
}