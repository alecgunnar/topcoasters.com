<?php

namespace Application\Form;

class Account_ProfileInformation extends \Maverick\Lib\Form {
    /**
     * The member account being edited
     *
     * @var \Application\Model\Member
     */
    private $member = null;

    /**
     * Timezones
     *
     * @var array
     */
    private $timezones = array(
        'Pacific/Midway'       => "(GMT-11:00) Midway Island",
        'US/Samoa'             => "(GMT-11:00) Samoa",
        'US/Hawaii'            => "(GMT-10:00) Hawaii",
        'US/Alaska'            => "(GMT-09:00) Alaska",
        'US/Pacific'           => "(GMT-08:00) Pacific Time (US &amp; Canada)",
        'America/Tijuana'      => "(GMT-08:00) Tijuana",
        'US/Arizona'           => "(GMT-07:00) Arizona",
        'US/Mountain'          => "(GMT-07:00) Mountain Time (US &amp; Canada)",
        'America/Chihuahua'    => "(GMT-07:00) Chihuahua",
        'America/Mazatlan'     => "(GMT-07:00) Mazatlan",
        'America/Mexico_City'  => "(GMT-06:00) Mexico City",
        'America/Monterrey'    => "(GMT-06:00) Monterrey",
        'Canada/Saskatchewan'  => "(GMT-06:00) Saskatchewan",
        'US/Central'           => "(GMT-06:00) Central Time (US &amp; Canada)",
        'US/Eastern'           => "(GMT-05:00) Eastern Time (US &amp; Canada)",
        'US/East-Indiana'      => "(GMT-05:00) Indiana (East)",
        'America/Bogota'       => "(GMT-05:00) Bogota",
        'America/Lima'         => "(GMT-05:00) Lima",
        'America/Caracas'      => "(GMT-04:30) Caracas",
        'Canada/Atlantic'      => "(GMT-04:00) Atlantic Time (Canada)",
        'America/La_Paz'       => "(GMT-04:00) La Paz",
        'America/Santiago'     => "(GMT-04:00) Santiago",
        'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland",
        'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
        'Greenland'            => "(GMT-03:00) Greenland",
        'Atlantic/Stanley'     => "(GMT-02:00) Stanley",
        'Atlantic/Azores'      => "(GMT-01:00) Azores",
        'Atlantic/Cape_Verde'  => "(GMT-01:00) Cape Verde Is.",
        'Africa/Casablanca'    => "(GMT) Casablanca",
        'Europe/Dublin'        => "(GMT) Dublin",
        'Europe/Lisbon'        => "(GMT) Lisbon",
        'Europe/London'        => "(GMT) London",
        'Africa/Monrovia'      => "(GMT) Monrovia",
        'Europe/Amsterdam'     => "(GMT+01:00) Amsterdam",
        'Europe/Belgrade'      => "(GMT+01:00) Belgrade",
        'Europe/Berlin'        => "(GMT+01:00) Berlin",
        'Europe/Bratislava'    => "(GMT+01:00) Bratislava",
        'Europe/Brussels'      => "(GMT+01:00) Brussels",
        'Europe/Budapest'      => "(GMT+01:00) Budapest",
        'Europe/Copenhagen'    => "(GMT+01:00) Copenhagen",
        'Europe/Ljubljana'     => "(GMT+01:00) Ljubljana",
        'Europe/Madrid'        => "(GMT+01:00) Madrid",
        'Europe/Paris'         => "(GMT+01:00) Paris",
        'Europe/Prague'        => "(GMT+01:00) Prague",
        'Europe/Rome'          => "(GMT+01:00) Rome",
        'Europe/Sarajevo'      => "(GMT+01:00) Sarajevo",
        'Europe/Skopje'        => "(GMT+01:00) Skopje",
        'Europe/Stockholm'     => "(GMT+01:00) Stockholm",
        'Europe/Vienna'        => "(GMT+01:00) Vienna",
        'Europe/Warsaw'        => "(GMT+01:00) Warsaw",
        'Europe/Zagreb'        => "(GMT+01:00) Zagreb",
        'Europe/Athens'        => "(GMT+02:00) Athens",
        'Europe/Bucharest'     => "(GMT+02:00) Bucharest",
        'Africa/Cairo'         => "(GMT+02:00) Cairo",
        'Africa/Harare'        => "(GMT+02:00) Harare",
        'Europe/Helsinki'      => "(GMT+02:00) Helsinki",
        'Europe/Istanbul'      => "(GMT+02:00) Istanbul",
        'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem",
        'Europe/Kiev'          => "(GMT+02:00) Kyiv",
        'Europe/Minsk'         => "(GMT+02:00) Minsk",
        'Europe/Riga'          => "(GMT+02:00) Riga",
        'Europe/Sofia'         => "(GMT+02:00) Sofia",
        'Europe/Tallinn'       => "(GMT+02:00) Tallinn",
        'Europe/Vilnius'       => "(GMT+02:00) Vilnius",
        'Asia/Baghdad'         => "(GMT+03:00) Baghdad",
        'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
        'Africa/Nairobi'       => "(GMT+03:00) Nairobi",
        'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
        'Asia/Tehran'          => "(GMT+03:30) Tehran",
        'Europe/Moscow'        => "(GMT+04:00) Moscow",
        'Asia/Baku'            => "(GMT+04:00) Baku",
        'Europe/Volgograd'     => "(GMT+04:00) Volgograd",
        'Asia/Muscat'          => "(GMT+04:00) Muscat",
        'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
        'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
        'Asia/Kabul'           => "(GMT+04:30) Kabul",
        'Asia/Karachi'         => "(GMT+05:00) Karachi",
        'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
        'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
        'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
        'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg",
        'Asia/Almaty'          => "(GMT+06:00) Almaty",
        'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
        'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
        'Asia/Bangkok'         => "(GMT+07:00) Bangkok",
        'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
        'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk",
        'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
        'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
        'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
        'Australia/Perth'      => "(GMT+08:00) Perth",
        'Asia/Singapore'       => "(GMT+08:00) Singapore",
        'Asia/Taipei'          => "(GMT+08:00) Taipei",
        'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
        'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
        'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk",
        'Asia/Seoul'           => "(GMT+09:00) Seoul",
        'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
        'Australia/Adelaide'   => "(GMT+09:30) Adelaide",
        'Australia/Darwin'     => "(GMT+09:30) Darwin",
        'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk",
        'Australia/Brisbane'   => "(GMT+10:00) Brisbane",
        'Australia/Canberra'   => "(GMT+10:00) Canberra",
        'Pacific/Guam'         => "(GMT+10:00) Guam",
        'Australia/Hobart'     => "(GMT+10:00) Hobart",
        'Australia/Melbourne'  => "(GMT+10:00) Melbourne",
        'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
        'Australia/Sydney'     => "(GMT+10:00) Sydney",
        'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
        'Asia/Magadan'         => "(GMT+12:00) Magadan",
        'Pacific/Auckland'     => "(GMT+12:00) Auckland",
        'Pacific/Fiji'         => "(GMT+12:00) Fiji",
    );

    /**
     * A map for the fields and their coresponding database values
     *
     * @var array
     */
    private $fieldMap = array('username'        => 'name',
                              'measures'        => 'measures',
                              'timezone'        => 'timezone',
                              'location'        => 'location',
                              'education'       => 'education',
                              'occupation'      => 'occupation',
                              'interests'       => 'interests',
                              'website'         => 'website',
                              'homePark'        => 'home_park',
                              'firstPark'       => 'first_park',
                              'favPark'         => 'favorite_park',
                              'firstCoaster'    => 'first_coaster',
                              'favoriteCoaster' => 'overall_fav_coaster',
                              'oabCoaster'      => 'fav_out_and_back_coaster',
                              'twistedCoaster'  => 'fav_twisted_coaster',
                              'steelCoaster'    => 'fav_steel_coaster',
                              'woodCoaster'     => 'fav_wooden_coaster');

    /**
     * The constructor
     *
     * @param \Application\Model\Member $member
     */
    public function __construct($member) {
        $this->member = $member;

        parent::__construct();
    }

    /**
     * Builds the form to be rendered later
     *
     * @return null
     */
    public function build() {
        $this->setTpl('Standard');
        $this->setName('ProfileInformation');

        $this->renderFieldsWithFormTpl();

        $favorites   = new \Application\Service\Favorites;
        $coasterFavs = $favorites->getCoasterFavoritesOptions($this->member->get('member_id'));
        $parkFavs    = $favorites->getParkFavoritesOptions($this->member->get('member_id'));

        $preferences = $this->addFieldGroup('preferences')
            ->setLabel('Account Settings');

        $preferences->addField('Input_Text', 'username')
            ->setLabel('Username')
            ->setValue($this->member->get('name'))
            ->setDescription('Only letters, numbers, and periods are allowed in usernames. (max. 20 characters)')
            ->setMaxLength(20)
            ->required('You must enter an username!');

        $preferences->addField('Select', 'measures')
            ->setLabel('Units of Measure')
            ->addOptions(array('english' => 'English',
                               'metric'  => 'Metric'))
            ->setValue($this->member->get('measures'));

        $time = new \Application\Lib\Time;

        $preferences->addField('Select', 'timezone')
            ->setLabel('Timezone')
            ->addOptions($this->timezones)
            ->setValue($this->member->get('timezone'))
            ->setDescription('Current date and time: ' . $time->now('F j, Y g:i a'));

        $profileInfo = $this->addFieldGroup('profileInfo')
            ->setLabel('Profile Information');

        $profileInfo->addField('Input_Text', 'location')
            ->setLabel('Location')
            ->setValue($this->member->get('location'));

        $profileInfo->addField('Input_Text', 'education')
            ->setLabel('Education')
            ->setValue($this->member->get('education'));

        $profileInfo->addField('Input_Text', 'occupation')
            ->setLabel('Occupation')
            ->setValue($this->member->get('occupation'));

        $profileInfo->addField('Input_Text', 'interests')
            ->setLabel('Interests')
            ->setValue($this->member->get('interests'));

        $profileInfo->addField('Input_Text', 'website')
            ->setLabel('Website')
            ->setValue($this->member->get('website'));

        $favParks = $this->addFieldGroup('favoriteParks')
            ->setLabel('Favorite Amusement Parks');

        $favParks->addField('Select', 'homePark')
            ->setLabel('Home Park')
            ->addOption(0, 'Choose an Amusement Park')
            ->addOptions($parkFavs)
            ->setValue($this->member->get('home_park'));

        $favParks->addField('Select', 'firstPark')
            ->setLabel('First Park')
            ->addOption(0, 'Choose an Amusement Park')
            ->addOptions($parkFavs)
            ->setValue($this->member->get('first_park'));

        $favParks->addField('Select', 'favPark')
            ->setLabel('Favorite Park')
            ->addOption(0, 'Choose an Amusement Park')
            ->addOptions($parkFavs)
            ->setValue($this->member->get('favorite_park'));

        $favCoasters = $this->addFieldGroup('favoriteCoasters')
            ->setLabel('Favorite Roller Coasters');

        $favCoasters->addField('Select', 'firstCoaster')
            ->setLabel('First Coaster')
            ->addOption(0, 'Choose a Roller Coaster')
            ->addOptions($coasterFavs)
            ->setValue($this->member->get('first_coaster'));

        $favCoasters->addField('Select', 'favoriteCoaster')
            ->setLabel('Overall Favorite Coaster')
            ->addOption(0, 'Choose a Roller Coaster')
            ->addOptions($coasterFavs)
            ->setValue($this->member->get('overall_fav_coaster'));

        $favCoasters->addField('Select', 'oabCoaster')
            ->setLabel('Favorite Out-and-Back Coaster')
            ->addOption(0, 'Choose a Roller Coaster')
            ->addOptions($coasterFavs)
            ->setValue($this->member->get('fav_out_and_back_coaster'));

        $favCoasters->addField('Select', 'twistedCoaster')
            ->setLabel('Favorite Twisted Coaster')
            ->addOption(0, 'Choose a Roller Coaster')
            ->addOptions($coasterFavs)
            ->setValue($this->member->get('fav_twisted_coaster'));

        $favCoasters->addField('Select', 'steelCoaster')
            ->setLabel('Favorite Steel Coaster')
            ->addOption(0, 'Choose a Roller Coaster')
            ->addOptions($coasterFavs)
            ->setValue($this->member->get('fav_steel_coaster'));

        $favCoasters->addField('Select', 'woodCoaster')
            ->setLabel('Favorite Wooden Coaster')
            ->addOption(0, 'Choose a Roller Coaster')
            ->addOptions($coasterFavs)
            ->setValue($this->member->get('fav_wooden_coaster'));

        $this->addField('Input_Submit', 'submit')
            ->setValue('Save my Settings');
    }

    /**
     * Validates the form
     */
    public function validate() {
        $input = $this->getModel()->get('preferences');

        if($this->member->get('name') != $input->get('username')) {
            if(\Application\Lib\Members::checkUsername($input->get('username'))) {
                $members = new \Application\Service\Members;
                $member  = $members->get($input->get('username'), 'name');
    
                if($member) {
                    $this->setFieldError('username', 'This username is already taken.');
                }
            } else {
                $this->setFieldError('username', 'This username contains characters that are not allowed.');
            }
        }
    }

    private function runThroughFields($group) {
        foreach($group as $field => $value) {
            if($value instanceof \Maverick\Lib\Model_Input) {
                $this->runThroughFields($value->getAsArray());
            } else {
                if(array_key_exists($field, $this->fieldMap)) {
                    $this->member->update($this->fieldMap[$field], $value);
                }
            }
        }
    }

    /**
     * Submits the form
     */
    public function submit() {
        $this->runThroughFields($this->getModel()->getAsArray());

        $website = strtolower($this->getModel()->get('profileInfo')->get('website'));

        if(strpos($website, 'http://') !== 0 && strpos($website, 'https://') !== 0 && $website) {
            $this->member->update('website', 'http://' . $website, true);
        }

        $this->member->update('seo_title', \Application\Lib\Utility::generateSeoTitle($this->getModel()->get('preferences')->get('username')));

        $members = new \Application\Service\Members;

        $members->commitChanges($this->member);
    }
}