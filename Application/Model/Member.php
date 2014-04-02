<?php

namespace Application\Model;

class Member extends Standard {
    protected $member_id   = null;
    protected $websiteLink = null;
    protected $memberTitle = null;

    protected function setUp() {
        $this->setMeasures();
    }

    private function setMeasures() {
        $this->set('measures', ($this->get('measures') ?: 'english'));
    }

    /**
     * Gets the profile picture url for a member
     *
     * @param  boolean $withTag=false
     * @param  string $type=''
     * @return string
     */
    public function getProfilePicture($withTag=false, $type='') {
        $url   = '';
        $types = \Application\Lib\Members::getProfilePictureTypes();

        switch(($type ?: $this->get('profile_picture_type'))) {
            case "gravatar":
                $url = sprintf($types['gravatar'], md5(strtolower(trim($this->get('email_address')))));
                break;
            case "facebook":
                $url = sprintf($types['facebook'], $this->get('facebook_id'));
                break;
            case "uploaded":
                $url = \Maverick\Maverick::getConfig('System')->get('url') . sprintf($types['uploaded'], $this->get('member_id'));
                break;
            default:
                $url = \Maverick\Maverick::getConfig('System')->get('url') . $types['default'];
        }

        if($withTag) {
            $imgTag = new \Maverick\Lib\Builder_Tag('img');

            $imgTag->addAttributes(array('src'   => $url,
                                         'title' => $this->get('name') . '\'s Profile Picture'));

            return $imgTag->render();
        }

        return $url;
    }

    protected function setUrl() {
        $this->url = '/profile/' . $this->get('seo_title');
    }

    private function setWebsiteLink() {
        $this->websiteLink = '';

        if($this->get('website')) {
            $aTag = new \Maverick\Lib\Builder_Tag('a');

            $aTag->addAttribute('href', $this->get('website'))
                ->addAttribute('target', '_blank')
                ->addContent($this->get('website'));

            $this->websiteLink = $aTag->render();
        }
    }

    public function getWebsiteLink() {
        if(is_null($this->websiteLink)) {
            $this->setWebsiteLink();
        }

        return $this->websiteLink;
    }

    private function setMemberTitle() {
        $this->memberTitle = 'Member';

        if($this->get('member_id') == 1) {
            $this->memberTitle = 'Site Owner';
        } else {
            if($this->get('is_admin')) {
                $this->memberTitle = 'Administrator';
            } else {
                if($this->get('is_mod')) {
                    $this->memberTitle = 'Moderator';
                }
            }
        }
    }

    public function getMemberTitle() {
        if(is_null($this->memberTitle)) {
            $this->setMemberTitle();
        }

        return $this->memberTitle;
    }
}