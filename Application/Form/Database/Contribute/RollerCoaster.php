<?php

namespace Application\Form;

class Database_Contribute_RollerCoaster extends \Maverick\Lib\Form {
    /**
     * The roller coaster which is being edited
     *
     * @var \Application\Model\RollerCoaster | null
     */
    private $coaster = null;

    /**
     * The park to add this coaster to
     *
     * @var integer
     */
    private $parkId = 0;

    /**
     * A map of the form fields to their database columns
     *
     * @var array
     */
    private $fieldMap = array('generalInfo'    => array('name'         => 'name',
                                                        'park'         => 'park_id',
                                                        'opened'       => 'open_date',
                                                        'status'       => 'status',
                                                        'status_date'  => 'status_date',
                                                        'manufacturer' => 'manufacturer',
                                                        'designer'     => 'designer',
                                                        'cost'         => 'cost'),
                              'locationInfo'   => array('previousLocaton' => 'previous_id'),
                              'trackAndLayout' => array('model'           => 'track_model',
                                                        'trackType'       => 'track_type',
                                                        'layout'          => 'layout_type'),
                              'statistics'     => array('height'          => 'height',
                                                        'length'          => 'length',
                                                        'verticalAngle'   => 'max_angle',
                                                        'speed'           => 'speed',
                                                        'rideTime'        => 'ride_time',
                                                        'inversions'      => 'inversions'),
                              'trainsAndCars'  => array('trainType'       => 'car_type',
                                                        'numTrains'       => 'num_trains',
                                                        'numCars'         => 'num_cars',
                                                        'numSeats'        => 'num_seats',
                                                        'restraints'      => 'restraints',
                                                        'hourly_capacity' => 'hourly_capacity'),
                              'description'    => 'description',
                              'pov_videos'     => 'pov_videos');

    public function __construct($coaster, $park) {
        $this->coaster = $coaster ?: new \Application\Model\RollerCoaster(array());
        $this->parkId  = $park;

        parent::__construct();
    }

    /**
     * Builds the form to be rendered later
     *
     * @return null
     */
    public function build() {
        $this->setName('rollerCoaster');
        $this->setTpl('Database/Contribute/RollerCoaster');
        $this->renderFieldsWithFormTpl();

        $amusementParks = new \Application\Service\AmusementParks;
        $rollerCoasters = new \Application\Service\RollerCoasters;

        $generalInfo = $this->addFieldGroup('generalInfo')
            ->setLabel('General Information');

        $generalInfo->addField('Input_Text', 'name')
            ->setLabel('Name of Roller Coaster')
            ->setDescription('This can be left blank if the name of the roller coaster is not yet known.')
            ->setValue($this->coaster->get('name'));

        $generalInfo->addField('Select', 'park')
            ->setLabel('Amusement Park')
            ->setValue($this->coaster->get('park_id') ?: $this->parkId)
            ->addOption(0, 'Choose an Amusement Park')
            ->addOptions($amusementParks->getOptions())
            ->required('You must choose an amusement park for this roller coaster.');

        $generalInfo->addField('Input_Text', 'opened')
            ->setLabel('Opened/Opening')
            ->setValue($this->coaster->get('open_date'));

        $generalInfo->addField('Select', 'status')
            ->setLabel('Status')
            ->addOptions(\Application\Lib\RollerCoasters::getStatuses())
            ->setValue($this->coaster->get('status'));

        $generalInfo->addField('Input_Text', 'status_date')
            ->setLabel('Status Date')
            ->setValue($this->coaster->get('status_date'));

        $generalInfo->addField('Input_Text', 'manufacturer')
            ->setLabel('Manufacturer')
            ->setValue($this->coaster->get('manufacturer'));

        $generalInfo->addField('Input_Text', 'designer')
            ->setLabel('Designer')
            ->setValue($this->coaster->get('designer'));

        $generalInfo->addField('Input_Text', 'cost')
            ->setLabel('Cost')
            ->validate('IsNumber')
            ->addAttribute('style', 'width: 85px;')
            ->prepend('$')
            ->append('U.S. Dollars')
            ->setValue($this->coaster->get('cost'));

        $locationInfo = $this->addFieldGroup('locationInfo')
            ->setLabel('Location Information');

        $locationInfo->addField('select', 'previousLocation')
            ->setLabel('Previous Location')
            ->addOption(0, 'Choose a Roller Coaster')
            ->addOptions($rollerCoasters->getOptions())
            ->setValue($this->coaster->get('previous_id'));

        $trackAndLayout = $this->addFieldGroup('trackAndLayout')
            ->setLabel('Track and Layout');

        $trackAndLayout->addField('Input_Text', 'model')
            ->setLabel('Model')
            ->setValue($this->coaster->get('track_model'));

        $trackAndLayout->addField('Select', 'trackType')
            ->setLabel('Track Type')
            ->addOption(0, '-')
            ->addOptions(\Application\Lib\RollerCoasters::getTrackTypes())
            ->setValue($this->coaster->get('track_type'));

        $trackAndLayout->addField('Select', 'layout')
            ->setLabel('Layout Type')
            ->addOption(0, '-')
            ->addOptions(\Application\Lib\RollerCoasters::getLayoutTypes())
            ->setValue($this->coaster->get('layout_type'));

        $transports = $trackAndLayout->addFieldGroup('transports')
            ->setLabel('Launches and Lifts')
            ->addAttribute('id', 'transportsGroup');

        $transportValues = $this->getTransportValues();
        $trans           = count($transportValues) ?: 1;

        for($i = 1; $i <= $trans; $i++) {
            $typeValue    = '';
            $speedValue   = '';
            $reverseValue = '';

            if(is_array($transportValues)) {
                if(array_key_exists($i - 1, $transportValues)) {
                    $typeValue    = $transportValues[$i - 1]['type'];
                    $speedValue   = $transportValues[$i - 1]['speed'];

                    if(array_key_exists('direction', $transportValues[$i - 1])) {
                        $reverseValue = $transportValues[$i - 1]['direction'];
                    }
                }
            }

            $transports->addField('Select', 'transport_type_' . $i)
                ->setLabel('Launch / Lift')
                ->setValue($typeValue)
                ->toggleAutoFill()
                ->addOption(0, '-')
                ->addOptions(\Application\Lib\RollerCoasters::getTransportTypes())
                ->append('at ')
                ->attach('Input_Number', 'transport_speed_' . $i)
                    ->setValue($speedValue)
                    ->toggleAutoFill()
                    ->addAttribute('style', 'width: 60px;')
                    ->append('mph. ')
                    ->attach('Select', 'transport_reverse_' . $i)
                        ->setValue($reverseValue)
                        ->addOptions(array('' => '', 'f' => 'Forward', 'b' => 'Backward'))
                        ->prepend('Direction ')
                        ->append('.');
        }

        $statistics = $this->addFieldGroup('statistics')
            ->setLabel('Statistics');

        $statistics->addField('Input_Number', 'height')
            ->setLabel('Maximum Height')
            ->addAttribute('style', 'width: 70px;')
            ->append('feet')
            ->setValue($this->coaster->get('height'));

        $statistics->addField('Input_Number', 'length')
            ->setLabel('Track Length')
            ->addAttribute('style', 'width: 70px;')
            ->append('feet')
            ->setValue($this->coaster->get('length'));

        $statistics->addField('Input_Number', 'verticalAngle')
            ->setLabel('Maximum Vertical Angle')
            ->addAttribute('style', 'width: 70px;')
            ->append('degrees')
            ->setValue($this->coaster->get('max_angle'));

        $statistics->addField('Input_Number', 'speed')
            ->setLabel('Top Speed')
            ->addAttribute('style', 'width: 70px;')
            ->append('miles per hour')
            ->setValue($this->coaster->get('speed'));

        $statistics->addField('Input_Number', 'rideTime')
            ->setLabel('Ride Time')
            ->addAttribute('style', 'width: 70px;')
            ->append('seconds')
            ->setValue($this->coaster->get('ride_time'));

        $statistics->addField('Input_Number', 'inversions')
            ->setLabel('Number of Inversions')
            ->addAttribute('style', 'width: 70px;')
            ->setValue($this->coaster->get('inversions'));

        $trainsAndCars = $this->addFieldGroup('trainsAndCars')
            ->setLabel('Trains and Cars');

        $trainsAndCars->addField('Select', 'trainType')
            ->setLabel('Train Type')
            ->addOption(0, '-')
            ->addOptions(\Application\Lib\RollerCoasters::getCarTypes())
            ->setValue($this->coaster->get('car_type'));

        $trainsAndCars->addField('Input_Number', 'numTrains')
            ->setLabel('Number of Trains')
            ->addAttribute('style', 'width: 70px;')
            ->setValue($this->coaster->get('num_trains'));

        $trainsAndCars->addField('Input_Number', 'numCars')
            ->setLabel('Cars per Train')
            ->addAttribute('style', 'width: 70px;')
            ->setValue($this->coaster->get('num_cars'));

        $trainsAndCars->addField('Input_Number', 'numSeats')
            ->setLabel('Seats per Car')
            ->addAttribute('style', 'width: 70px;')
            ->setValue($this->coaster->get('num_seats'));

        $trainsAndCars->addField('Input_Number', 'hourlyCapacity')
            ->setLabel('Hourly Capacity')
            ->addAttribute('style', 'width: 70px;')
            ->append('people per hour')
            ->setValue($this->coaster->get('hourly_capacity'));

        $trainsAndCars->addField('Select', 'restraints')
            ->setLabel('Restraints')
            ->addOption(0, '-')
            ->addOptions(\Application\Lib\RollerCoasters::getRestraints())
            ->setValue($this->coaster->get('restraints'));

        $this->addField('Textarea', 'description')
            ->setLabel('Additional Information')
            ->setDescription('Add any additional information here, that may not have been covered in one of the fields above.')
            ->setValue($this->coaster->get('description'));

        $this->addField('Input_Text', 'pov_videos')
            ->setLabel('POV Videos')
            ->setDescription('You may enter up to two comma separated YouTube video id\'s.')
            ->setValue($this->coaster->get('pov_videos'));

        $this->addField('Input_Submit', 'submit')
            ->setValue('Save Roller Coaster');
    }

    /**
     * Validates the form
     */
    public function validate() { }

    /**
     * Submits the form
     */
    public function submit() {
        if($this->coaster->get('coaster_id')) {
            return $this->saveChanges();
        } else {
            return $this->saveNewCoaster();
        }
    }

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

        $values['transports'] = json_encode($this->getTransportValues());
        $values['approved']   = 0;

        if($includeSeoTitle) {
            $values['seo_title'] = \Application\Lib\Utility::generateSeoTitle($input->get('generalInfo')->get('name'));
        }

        return $values;
    }

    private function saveChanges() {
        $rollerCoasters = new \Application\Service\RollerCoasters;
        $this->coaster->update($this->getValues(true));

        return $rollerCoasters->commitChanges($this->coaster);
    }

    private function saveNewCoaster() {
        $rollerCoasters = new \Application\Service\RollerCoasters;
        $insert         = $this->getValues();

        $insert['contributed_by'] = \Maverick\Lib\Output::getGlobalVariable('member')->get('member_id');

        $coaster = $rollerCoasters->put($insert);

        if($coaster instanceof \Application\Model\RollerCoaster) {
            $coaster->update('seo_title', \Application\Lib\Utility::generateSeoTitle($coaster->get('name')));

            $rollerCoasterCache = new \Maverick\Lib\Cache('rollerCoasters');
            $rollerCoasterCache->set(null);

            return $rollerCoasters->commitChanges($coaster);
        }

        return false;
    }

    private function getTransportValues() {
        $model           = $this->getModel();
        $transportValues = array();
        $trans           = 0;

        if(!is_null($model->get('trackAndLayout'))) {
            $loop  = 1;

            foreach($model->get('trackAndLayout')->get('transports')->getAsArray() as $field => $value) {
                $part = $loop % 3;

                if(!$part) {
                    if(array_key_exists($trans, $transportValues)) {
                        $transportValues[$trans]['direction'] = $value;

                        $trans++;
                    }
                } elseif($part == 2) {
                    if(array_key_exists($trans, $transportValues)) {
                        $transportValues[$trans]['speed'] = $value;
                    }
                } elseif($part == 1) {
                    if($value !== '0') {
                        $transportValues[$trans]['type'] = $value;
                    }
                }

                $loop++;
            }
        } elseif($this->coaster->get('coaster_id')) {
            $transportValues = json_decode($this->coaster->get('transports'), true);
        }

        return $transportValues;
    }
}