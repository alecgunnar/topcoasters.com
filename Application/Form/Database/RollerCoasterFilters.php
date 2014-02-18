<?php

namespace Application\Form;

class Database_RollerCoasterFilters extends \Maverick\Lib\Form {
    /**
     * Filter sets
     *
     * @var array
     */
    private $filters = array();

    public function __construct() {
        $this->filters = array('status'      => \Application\Lib\RollerCoasters::getStatuses(),
                               'track_type'  => \Application\Lib\RollerCoasters::getTrackTypes(),
                               'layout_type' => \Application\Lib\RollerCoasters::getLayoutTypes(),
                               'car_type'    => \Application\Lib\RollerCoasters::getCarTypes(),
                               'transports'  => \Application\Lib\RollerCoasters::getTransportTypes(),
                               'restraints'  => \Application\Lib\RollerCoasters::getRestraints());

        parent::__construct();
    }

    /**
     * Builds the form to be rendered later
     *
     * @return null
     */
    public function build() {
        $this->setName('rollerCoasterFilters');
        $this->setTpl('Database/RollerCoasterFilters');

        $this->renderFieldsWithFormTpl();

        foreach($this->filters as $column => $values) {
            $group = $this->addFieldGroup($column)
                ->setLabel(ucwords(str_replace('_', ' ', $column)));

            foreach($values as $db => $label) {
                $group->addField('Input_CheckBox', $db)
                    ->setLabel($label)
                    ->setValue(1);
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

        foreach($this->filters as $column => $values) {
            $filters[$column] = array();

            foreach($values as $db => $label) {
                if(!is_null($input->get($column)->get($db))) {
                    $filters[$column][] = $db;
                }
            }
        }

        return $filters;
    }
}