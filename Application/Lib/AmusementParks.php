<?php

namespace Application\Lib;

class AmusementParks {
    private static $seasonTypes = array('seasonal' => 'Seasonal',
                                        'allyear'  => 'All Year');

    public static function getSeasonTypes() {
        return self::$seasonTypes;
    }
}