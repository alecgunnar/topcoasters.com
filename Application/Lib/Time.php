<?php

namespace Application\Lib;

class Time extends \DateTime {
    /**
     * The default timezone to use
     */
    private static $defaultTimezone = '';

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
        if($timezone === true) {
            $timezone = self::$defaultTimezone;
        }

        parent::__construct($time, new \DateTimezone(\Maverick\Maverick::getConfig('System')->get('timezone')));

        if($timezone) {
            $this->setTimezone(new \DateTimezone($timezone));
        }
    }

    /**
     * Sets the timezone
     *
     * @param string $timezone
     */
    public static function setDefaultTimezone($timezone) {
        self::$defaultTimezone = $timezone;
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
     *
     * @return self
     */
    public function switchToUsersTime() {
        $this->setTimeZone(new \DateTimeZone(self::$defaultTimezone ?: \Maverick\Maverick::getConfig('System')->get('timezone')));

        return $this;
    }

    /**
     * Switches the timezone to the default timezone
     *
     * @return self
     */
    public function switchToDefaultTime() {
        $this->setTimeZone(new \DateTimeZone(\Maverick\Maverick::getConfig('System')->get('timezone')));

        return $this;
    }

    /** 
     * Gets the shortest formatted time
     *
     * @param boolean $prefix=false
     */
    public function getShortTime($prefix=true) {
        $now  = new self;
        $diff = $this->diff($now);

        if($diff->y >= 1) {
            return ($prefix ? 'on ' : '') . $this->getStandardDateTime();
        } elseif($diff->days >= 1) {
            return ($prefix ? 'on ' : '') . $this->format('F jS \a\t g:i a');
        } else {
            if($now->format('j') == $this->format('j')) {
                if($diff->h < 1) {
                    if($diff->i >= 1) {
                        return $diff->format('%i') . ' minutes ago';
                    } else {
                        return 'seconds ago';
                    }
                } else {
                    return 'today at ' . $this->format('g:i a');
                }
            } else {
                return 'yesterday at ' . $this->format('g:i a');
            }
        }
    }
}