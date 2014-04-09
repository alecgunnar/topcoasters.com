<?php

namespace Application\Model;

class AmusementPark extends Standard {
    protected $locationHuman   = null;
    protected $openedDateHuman = null;
    protected $closedDateHuman = null;
    protected $websiteLink     = null;
    protected $daysUntilOpen   = null;

    protected function setUp() { }

    protected function setUrl() {
        $this->url = '/database/amusement-park/' . $this->get('park_id') . '/' . $this->getSeoTitle();
    }

    private function setLocation() {
        $this->locationHuman = '';

        if($this->get('region') && $this->get('country')) {
            if($this->get('city')) {
                $this->locationHuman = $this->get('city') . ', ';
            }

            $this->locationHuman .= $this->get('region') . ', ' . $this->get('country');
        }
    }

    public function getLocation() {
        if(is_null($this->locationHuman)) {
            $this->setLocation();
        }

        return $this->locationHuman;
    }
    
    private function setOpenedDate() {
        $this->openedDateHuman = '';

        if($this->get('opened')) {
            $this->openedDateHuman = \Application\Lib\Database::formatDate($this->get('opened'));
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

        if($this->get('closed')) {
            $this->closedDateHuman = \Application\Lib\Database::formatDate($this->get('closed'));
        }
    }

    public function getClosedDate() {
        if(is_null($this->closedDateHuman)) {
            $this->setClosedDate();
        }

        return $this->closedDateHuman;
    }

    private function setWebsiteLink() {
        $this->websiteLink = '';

        if($this->get('website')) {
            $anchor = new \Maverick\Lib\Builder_Tag('a');
            $anchor->addAttributes(array('href'   => $this->get('website'),
                                         'target' => '_blank'));

            $anchor->addContent($this->get('website'));

            $this->websiteLink = $anchor->render();
        }
    }

    public function getWebsiteLink() {
        if(is_null($this->websiteLink)) {
            $this->setWebsiteLink();
        }

        return $this->websiteLink;
    }

    private function setDaysUntilOpen() {
        $this->daysUntilOpen = '';

        if($this->get('season_start')) {
            $time  = new \Application\Lib\Time(null, true);
            $start = $this->getDate('season_start');

            if($start > $time) {
                $diff = $time->diff($start);

                if($diff->days >= 1) {
                    $this->daysUntilOpen = $diff->days;
                } elseif($diff->s || $diff->m) {
                    $this->daysUntilOpen = -1;
                }
            }
        }
    }

    public function getDaysUntilOpen() {
        if(is_null($this->daysUntilOpen)) {
            $this->setDaysUntilOpen();
        }

        return $this->daysUntilOpen;
    }
}