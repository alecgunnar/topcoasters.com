<?php

namespace Application\Lib;

class Time extends \DateTime {
    /**
     * The timezone to use
     */
    private static $timezone = '';

    /**
     * The format for timestamps
     *
     * @var string
     */
    private $timestamp = 'Y-m-d H:i:s';

    /**
     * The constructor
     *
     * @param string $time=null
     * @param string $timezone=null
     */
    public function __construct($time=null, $timezone=null) {
        $timezone = $timezone ?: (self::$timezone ?: \Maverick\Maverick::getConfig('System')->get('timezone'));

        if($timezone === true) {
            $timezone = \Maverick\Maverick::getConfig('System')->get('timezone');
        } elseif(!$timezone) {
            $timezone = self::$timezone;
        }

        parent::__construct($time, new \DateTimezone($timezone));
    }

    /**
     * Sets the timezone
     *
     * @param string $timezone
     */
    public static function setDefaultTimezone($timezone) {
        self::$timezone = $timezone;
    }

    /**
     * Get the current time
     *
     * @param  string $format=''
     * @return string
     */
    public function now($format='') {
        if($format === true) {
            $format = $this->timestamp;
        } else {
            $format = $format ?: 'U';
        }

        return $this->format($format);
    }

    /**
     * Gets the timestamp of the current time
     *
     * @return string
     */
    public function getTimestamp() {
        return $this->format($this->timestamp);
    }

    /**
     * Gets the datetime in the standard format
     *
     * @return string
     */
    public function getStandardDateTime() {
        return $this->format('F j, Y \a\t g:i a');
    }

    /**
     * Switches the timezone to the user's timezone
     */
    public function switchToUsersTime() {
        $timezone = 'US/Eastern';

        if(\Application\Lib\Members::checkUserStatus()) {
            $timezone = \Maverick\Lib\Output::getGlobalVariable('member')->get('timezone');
        }

        $this->setTimeZone(new \DateTimeZone($timezone ?: \Maverick\Maverick::getConfig('System')->get('timezone')));
    }

    /** 
     * Gets the shortest formatted time
     */
    public function getShortTime() {
        $now  = new self;
        $diff = $this->diff($now);

        $h = intval($now->format('G'));

        if($diff->format('%y') < 1) {
            if($diff->format('%d') < 2) {
                if($diff->format('%d') < 1 && $h - $diff->format('%h') >= 0) {
                    if($diff->format('%h') < 1) {
                        if($diff->format('%i') < 1) {
                            return 'seconds ago';
                        } else {
                            return $diff->format('%i') . ' minutes ago';
                        }
                    } else {
                        return 'today at ' . $this->format('g:i a');
                    }
                } else {
                    return 'yesterday at ' . $this->format('g:i a');
                }
            } else {
                return 'on ' . $this->format('F j \a\t g:i a');
            }
        } else {
            return 'on ' . $this->getStandardDateTime();
        }
    }
}