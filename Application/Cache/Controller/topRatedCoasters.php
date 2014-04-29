<?php

namespace Application\Cache;

class Controller_TopRatedCoasters {
    public function cache() {
        $rollerCoasters = new \Application\Service\RollerCoasters;
        return $rollerCoasters->getHighestRated();
    }
}