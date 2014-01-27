<?php

namespace Application\Service;

class Facebook {
    /**
     * The Facebook API
     *
     * @var \Facebook | null
     */
    private $api = null;

    /**
     * The access token for the API
     *
     * @var string
     */
    private $accessToken = '';

    /**
     * The constructor
     */
    public function __construct() {
        $this->api = new \Facebook(array('appId'              => \Maverick\Maverick::getConfig('Facebook')->get('app_id'),
                                         'secret'             => \Maverick\Maverick::getConfig('Facebook')->get('app_secret'),
                                         'fileUpload'         => false,
                                         'allowSignedRequest' => false));

        $this->accessToken = $this->api->getAccessToken();
    }

    /**
     * Gets the API
     *
     * @return \Facebook
     */
    public function getApi() {
        return $this->api;
    }

    /**
     * Gets the access token
     *
     * @return string
     */
    public function getAccessToken() {
        return $this->accessToken;
    }

    /**
     * Gets the Facebook user data
     *
     * @param  string $accessToken=''
     * @return \Application\Model\FacebookUser | boolean
     */
    public function getUser($accessToken='') {
        $accessToken = $accessToken ?: $this->accessToken;

        try {
            if(!$this->api->getUser()) {
                return false;
            }

            $user = $this->api->api('/me', 'GET', array('access_token' => $accessToken));
            
            return new \Application\Model\FacebookUser($user);
        } catch(\Exception $e) {
            return false;
        }
    }
}