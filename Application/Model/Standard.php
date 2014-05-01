<?php

namespace Application\Model;

abstract class Standard {
    /**
     * The seo title for "this"
     *
     * @var string
     */
    protected $seo_title = '';

    /**
     * The name of "this"
     *
     * @var string | null
     */
    protected $name = null;

    /**
     * This is the name which is show publicly
     *
     * @var string
     */
    protected $showName = null;

    /**
     * Whether or not "this" has a name
     *
     * @var boolean
     */
    protected $isNoName = false;

    /**
     * The url for the "thing"
     *
     * @var string | null
     */
    protected $url = null;

    /**
     * The link code for "this"
     *
     * @var string | null
     */
    protected $link = null;

    /**
     * The coaster which is used by "this"
     *
     * @var \Application\Model\RollerCoaster | null
     */
    protected $coaster = null;

    /**
     * The park which is used by "this"
     *
     * @var \Application\Model\AmusementPark | null
     */
    protected $park = null;

    /**
     * Data which should be updated
     *
     * @var array
     */
    private $dataToUpdate = array();

    /**
     * The constructor - sets all of the data to the model
     *
     * @param array $data
     */
    public function __construct(array $data=null) {
        if($data) {
            $this->set($data);
        }

        $this->setUp();
    }

    /**
     * Sets a value to this model
     *
     * @throws \Exception
     * @param  string | array $key
     * @param  string | null $value=''
     * @return self
     */
    public function set($key, $value='') {
        if(is_array($key)) {
            if(count($key)) {
                foreach($key as $k => $v) {
                    $this->set($k, $v);
                }
            }
        } else {
            if(!$key) {
                throw new \Exception('You cannot send an empty key value to ' . get_class($this) . '::set($key, $value)');
            }

            if($this->checkValueType($value)) {
                $this->$key = stripslashes($value);
            } else {
                $this->$key = $value;
            }
        }

        return $this;
    }

    /**
     * Gets a value from the model
     *
     * @param  string $key
     * @return string | boolean
     */
    public function get($key) {
        if(property_exists($this, $key)) {
            return $this->$key;
        }

        return false;
    }

    /**
     * Setup a field to be updated
     *
     * @throws \Exception
     * @param  string | array $key
     * @param  string $newValue=''
     * @param  boolean $force=false
     * @return self
     */
    public function update($key, $newValue='', $force=false) {
        if(is_array($key)) {
            if(count($key)) {
                foreach($key as $k => $v) {
                    $this->update($k, $v);
                }
            }
        } else {
            if(!$this->checkValueType($newValue)) {
                throw new \Exception('The new value for key: ' . $key . ' must be a string or null! A ' . gettype($newValue) . ' was received.');
            }

            if($newValue != $this->get($key) || $force) {
                $this->dataToUpdate[$key] = $newValue;
            }
        }

        return $this;
    }

    /**
     * Increases a value be a certain specified amount
     *
     * @throws \Exception
     * @param  string  $field
     * @param  integer $amount=1
     * @return self
     */
    public function increase($field, $amount=1) {
        if(!is_numeric($amount)) {
            throw new \Exception('Parameter 2 for ' . get_class($this) . '::increase must be an integer.');
        }

        if(!is_numeric($this->get($field))) {
            throw new \Exception('The field: ' . $field . ' is not a numeric field. It cannot be increased.');
        }

        $this->update($field, $this->get($field) + $amount);

        return $this;
    }

    /**
     * Toggles the value of a bool field
     *
     * @param string $field
     * @return self
     */
    public function toggle($field) {
        $value    = $this->get($field);
        $toggleTo = '1';

        if(!is_null($field)) {
            if($value == 1) {
                $toggleTo = '0';
            }
        }

        $this->update($field, $toggleTo);

        return $this;
    }

    /**
     * Checks the type of a value
     *
     * @param  mixed $value
     * @return boolean
     */
    private function checkValueType($value) {
        if(is_string($value) || is_numeric($value) || is_null($value)) {
            return true;
        }

        return false;
    }

    /**
     * Gets the fields which need to be updated
     *
     * @return array
     */
    public function getDataToUpdate() {
        return $this->dataToUpdate;
    }

    /**
     * Tells the model that the data to be updated has been commited
     */
    public function dataUpdated() {
        $this->set($this->dataToUpdate);

        $this->dataToUpdate = array();

        $this->setUp();
    }

    /**
     * Creates a new model of the same type that
     * only includes fields which are absolutely
     * neccesary.
     *
     * @param  array $fields
     * @return mixed
     */
    public function minimize(array $fields) {
        $type = get_class($this);
        $data = array();

        if(count($fields)) {
            foreach($fields as $field) {
                $data[$field] = $this->{$field};
            }
        }

        return new $type($data);
    }

    public function getSeoTitle() {
        return $this->seo_title;
    }

    protected function setName() {
        $this->showName = $this->name;

        if(!$this->name) {
            $this->showName     = 'Unknown';
            $this->isNoName = true;
        }
    }

    public function getName() {
        if(is_null($this->showName)) {
            $this->setName();
        }

        return $this->showName;
    }

    public function getIsNoName() {
        return $this->isNoName;
    }

    protected function setUrl() {
        throw new \Exception('Class ' . get_class($this) . ' must have setUrl() defined.');
    }

    public function getUrl() {
        if(is_null($this->url)) {
            $this->setUrl();
        }

        return $this->url;
    }

    protected function setLink() {
        $anchor = new \Maverick\Lib\Builder_Tag('a');

        $anchor->addAttribute('href', $this->getUrl())
            ->addContent(!$this->name ? ('<em>' . $this->getName() . '</em>') : $this->getName());

        $this->link = $anchor->render();
    }

    public function getLink($label='') {
        if(is_null($this->link)) {
            $this->setLink();
        }

        if($label) {
            $anchor = new \Maverick\Lib\Builder_Tag('a');

            $anchor->addAttribute('href', $this->getUrl())
                ->addContent($label);

            return $anchor->render();
        }

        return $this->link;
    }

    protected function setCoaster() {
        $rollerCoasters = new \Application\Service\RollerCoasters;
        $coaster        = $rollerCoasters->get($this->get('coaster_id'));

        if($coaster) {
            $this->coaster = $coaster;
        } else {
            $this->coaster = new RollerCoaster;
        }
    }

    public function getCoaster() {
        if(is_null($this->coaster)) {
            $this->setCoaster();
        }

        return $this->coaster;
    }

    protected function setPark() {
        $amusementParks = new \Application\Service\AmusementParks;
        $amusementPark  = $amusementParks->get($this->get('park_id'));

        if($amusementPark) {
            $this->park = $amusementPark;
        } else {
            $this->park = new AmusementPark;
        }
    }

    public function getPark() {
        if(is_null($this->park)) {
            $this->setPark();
        }

        return $this->park;
    }

    public function getDate($field, $timezone=null) {
        $time = new \Application\Lib\Time($this->get($field), $timezone);
        $time->switchToUsersTime();

        return $time;
    }

    public function getMember($field='member_id') {
        $members = new \Application\Service\Members;

        return $members->get($this->get($field), 'member_id');
    }

    public function getParsed($field) {
        $bbcode = new \Application\Lib\BBCode;

        return $bbcode->render($this->get($field));
    }

    /**
     * Does initial setup of the model when it is created and updated
     */
    protected abstract function setUp();
}