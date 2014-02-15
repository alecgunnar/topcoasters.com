<?php

namespace Application\Model;

class RollerCoaster extends Standard {
    protected $previousCoaster = null;
    protected $newLocation = null;

    protected $povVideos = null;

    // Human readable version of various fields
    protected $openedDateHuman     = null;
    protected $closedDateHuman     = null;
    protected $statusHuman         = null;
    protected $trackTypeHuman      = null;
    protected $layoutTypeHuman     = null;
    protected $heightHuman         = null;
    protected $lengthHuman         = null;
    protected $angleHuman          = null;
    protected $transportsHuman     = null;
    protected $speedHuman          = null;
    protected $trainTypeHuman      = null;
    protected $hourlyCapacityHuman = null;
    protected $restraintsHuman     = null;
    protected $rideTimeHuman       = null;
    protected $costHuman           = null;

    protected function setUp() { }

    protected function setUrl() {
        $this->url = '/database/roller-coaster/' . $this->get('coaster_id') . '/' . $this->getSeoTitle();
    }

    private function setPrevious() {
        $rollerCoasters = new \Application\Service\RollerCoasters;
        $coaster        = $rollerCoasters->get($this->get('previous_id'));

        if($coaster) {
            $this->previousCoaster = $coaster;
        }
    }

    public function getPrevious() {
        if(is_null($this->previousCoaster) && $this->get('previous_id')) {
            $this->setPrevious();
        }

        return $this->previousCoaster;
    }

    private function setNewLocation() {
        $rollerCoasters = new \Application\Service\RollerCoasters;
        $coaster        = $rollerCoasters->get($this->get('coaster_id'), 'previous_id');

        if($coaster) {
            $this->newLocation = $coaster;
        }
    }

    public function getNewLocation() {
        if(is_null($this->newLocation)) {
            $this->setNewLocation();
        }

        return $this->newLocation;
    }

    private function setOpenedDate() {
        $this->openedDateHuman = '';

        if($this->get('open_date')) {
            $this->openedDateHuman = \Application\Lib\Database::formatDate($this->get('open_date'));
        }
    }

    public function getOpenedDate() {
        if(is_null($this->openedDateHuman)) {
            $this->setOpenedDate();
        }

        return $this->openedDateHuman;
    }

    private function setClosedDate() {
        $this->closedDateHuman = '';

        if(array_key_exists($this->get('status'), array('defunc' => 0, 'relocated' => 1)) && $this->get('status_date')) {
            $this->closedDateHuman = \Application\Lib\Database::formatDate($this->get('status_date'));
        }
    }

    public function getClosedDate() {
        if(is_null($this->closedDateHuman)) {
            $this->setClosedDate();
        }

        return $this->closedDateHuman;
    }

    private function setStatus() {
        $this->statusHuman = '';

        if($this->get('status')) {
            $this->statusHuman = \Application\Lib\RollerCoasters::getStatuses()[$this->get('status')];
        }
    }

    public function getStatus() {
        if(is_null($this->statusHuman)) {
            $this->setStatus();
        }

        return $this->statusHuman;
    }

    private function setTrackType() {
        $this->trackTypeHuman = '';

        if($this->get('track_type')) {
            $this->trackTypeHuman = \Application\Lib\RollerCoasters::getTrackTypes()[$this->get('track_type')];
        }
    }

    public function getTrackType() {
        if(is_null($this->trackTypeHuman)) {
            $this->setTrackType();
        }

        return $this->trackTypeHuman;
    }

    private function setLayoutType() {
        $this->layoutTypeHuman = '';

        if($this->get('layout_type')) {
            $layoutTypes           = \Application\Lib\RollerCoasters::getLayoutTypes();
            $this->layoutTypeHuman = $layoutTypes[$this->get('layout_type')];
        }
    }

    public function getLayoutType() {
        if(is_null($this->layoutTypeHuman)) {
            $this->setLayoutType();
        }

        return $this->layoutTypeHuman;
    }

    private function setTransports() {
        $this->transportsHuman = array();

        if($transports = json_decode($this->get('transports'), true)) {
            $this->transportsHuman = $transports;
        }
    }

    public function getTransports($asList=false) {
        if(is_null($this->transportsHuman)) {
            $this->setTransports();
        }

        if($asList) {
            $list = '';

            if(count($this->transportsHuman)) {
                $orderedList = new \Maverick\Lib\Builder_Tag('ol');

                foreach($this->transportsHuman as $data) {
                    if($data['type']) {
                        $direction = '';

                        if(array_key_exists('direction', $data)) {
                            $direction = (($data['direction'] == 'b') ? 'Reverse ' : '' );
                        }

                        $li = new \Maverick\Lib\Builder_Tag('li');
                        $li->addContent($direction . \Application\Lib\RollerCoasters::getTransportTypes()[$data['type']] . ($data['speed'] ? ' at ' . \Application\Lib\Database::appendUnitsSpeed($data['speed']) : ''));
                        $orderedList->addContent($li->render());
                    }
                }

                $list = $orderedList->render();
            }

            return $list;
        }

        return $this->transportsHuman;
    }

    private function setHeight() {
        $this->heightHuman = '';

        if($this->get('height')) {
            $this->heightHuman = \Application\Lib\Database::appendUnitsLength($this->get('height'));
        }
    }

    public function getHeight() {
        if(is_null($this->heightHuman)) {
            $this->setHeight();
        }

        return $this->heightHuman;
    }

    private function setLength() {
        $this->lengthHuman = '';

        if($this->get('length')) {
            $this->lengthHuman = \Application\Lib\Database::appendUnitsLength($this->get('length'));
        }
    }

    public function getLength() {
        if(is_null($this->lengthHuman)) {
            $this->setLength();
        }

        return $this->lengthHuman;
    }

    private function setSpeed() {
        $this->speedHuman = '';

        if($this->get('speed')) {
            $this->speedHuman = \Application\Lib\Database::appendUnitsSpeed($this->get('speed'));
        }
    }

    public function getSpeed() {
        if(is_null($this->speedHuman)) {
            $this->setSpeed();
        }

        return $this->speedHuman;
    }

    private function setMaximumVertical() {
        $this->angleHuman = '';

        if($this->get('max_angle')) {
            $this->angleHuman = $this->get('max_angle') . '&deg;';
        }
    }

    public function getMaximumVertical() {
        if(is_null($this->angleHuman)) {
            $this->setMaximumVertical();
        }

        return $this->angleHuman;
    }

    private function setTrainType() {
        $this->trainTypeHuman = '';

        if($this->get('car_type')) {
            $this->trainTypeHuman = \Application\Lib\RollerCoasters::getCarTypes()[$this->get('car_type')];
        }
    }

    public function getTrainType() {
        if(is_null($this->trainTypeHuman)) {
            $this->setTrainType();
        }

        return $this->trainTypeHuman;
    }

    private function setHourlyCapacity() {
        $this->hourlyCapacityHuman = '';

        if($this->get('hourly_capacity')) {
            $this->hourlyCapacityHuman = $this->get('hourly_capacity') . ' pph';
        }
    }

    public function getHourlyCapacity() {
        if(is_null($this->hourlyCapacityHuman)) {
            $this->setHourlyCapacity();
        }

        return $this->hourlyCapacityHuman;
    }

    private function setRestraints() {
        $this->restraintsHuman = '';

        if($this->get('restraints')) {
            $this->restraintsHuman = \Application\Lib\RollerCoasters::getRestraints()[$this->get('restraints')];
        }
    }

    public function getRestraints() {
        if(is_null($this->restraintsHuman)) {
            $this->setRestraints();
        }

        return $this->restraintsHuman;
    }

    private function setRideTime() {
        $this->rideTimeHuman = '';

        if($this->get('ride_time')) {
            $seconds = $this->get('ride_time') % 60;
            $minutes = ($this->get('ride_time') - $seconds) / 60;

            if($minutes) {
                if($seconds < 10) {
                    $seconds = '0' . $seconds;
                }

                $this->rideTimeHuman = $minutes . ':' . $seconds;
            } else {
                $this->rideTimeHuman = $seconds . ' seconds';
            }
        }
    }

    public function getRideTime() {
        if(is_null($this->rideTimeHuman)) {
            $this->setRideTime();
        }

        return $this->rideTimeHuman;
    }

    private function setPovVideos() {
        $this->povVideos = array();

        if($this->get('pov_videos')) {
            $this->povVideos = explode(',', $this->get('pov_videos'));
        }
    }

    public function getPovVideos() {
        if(is_null($this->povVideos)) {
            $this->setPovVideos();
        }

        return $this->povVideos;
    }

    private function setCost() {
        $this->costHuman = '';

        if($this->get('cost')) {
            $this->costHuman = '$' . number_format($this->get('cost')) . ' USD';
        }
    }

    public function getCost() {
        if(is_null($this->costHuman)) {
            $this->setCost();
        }

        return $this->costHuman;
    }
}