<?php

namespace Application\Lib;

class RollerCoasters {
    private static $statuses = array('running'   => 'Operating',
                                     'sbno'      => 'SBNO',
                                     'defunc'    => 'Razed',
                                     'building'  => 'Under Construction',
                                     'relocated' => 'Relocated',
                                     'storage'   => 'In Storage',
                                     'rumored'   => 'Rumored');

    private static $trackTypes = array('steel' => 'Steel',
                                       'wood'  => 'Wood');

    private static $layoutTypes = array('full'    => 'Full Circuit',
                                        'shuttle' => 'Shuttle');

    private static $carTypes = array('sit'       => 'Sit Down',
                                     'hang'      => 'Inverted',
                                     'lay'       => 'Lay Down',
                                     'standup'   => 'Stand Up',
                                     'hangsit'   => 'Suspended',
                                     'floorless' => 'Floorless',
                                     'flying'    => 'Flying',
                                     '4thdim'    => '4th Dimension',
                                     'pipline'   => 'Pipeline',
                                     'bobsled'   => 'Bobsled',
                                     'spin'      => 'Spinning',
                                     'motorbike' => 'Motorbike',
                                     'wingrider' => 'Wingrider');

    private static $transportTypes = array('chain'      => 'Chain Lift',
                                           'cable'      => 'Cable Lift',
                                           'h_launch'   => 'Hydraulic Launch',
                                           'lim'        => 'Linear Motor Launch',
                                           'tire'       => 'Tire Drive',
                                           'tilt'       => 'Tilt Lift',
                                           'elevator'   => 'Elevator',
                                           'ferris'     => 'Ferris Wheel Lift',
                                           'air_launch' => 'Pneumatic Launch',
                                           'fly_wheel'  => 'Fly Wheel Launch',
                                           'weightdrop' => 'Weight Drop Launch',
                                           'powered'    => 'Powered Train');

    private static $restraints = array('shoulder' => 'Shoulder Harness',
                                       'lap'      => 'Lap Bar');

    public static function getStatuses() {
        return self::$statuses;
    }

    public static function getTrackTypes() {
        return self::$trackTypes;
    }

    public static function getLayoutTypes() {
        return self::$layoutTypes;
    }

    public static function getCarTypes() {
        return self::$carTypes;
    }

    public static function getTransportTypes() {
        return self::$transportTypes;
    }

    public static function getRestraints() {
        return self::$restraints;
    }
}