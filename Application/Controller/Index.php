<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Index extends \Maverick\Lib\Controller {
    /**
     * The directory which holds all of the flexslider images
     *
     * @var string
     */
    const PATH_TO_FLEX_IMAGES = '/assets/images/flexslider';

    /**
     * The maximum number of images to show in the flex slider
     *
     * @var integer
     */
    const NUM_FLEX_IMAGES = 5;

    public function main() {
        Output::setPageTitle('Top Coasters - Experience the Top Thrill!');
        Output::setGlobalVariable('title_suffix', '');

        $this->setupFlexslider();
        $this->getRecentNews();
        $this->getHighestRated();
    }

    private function setupFlexslider() {
        $flexImagesCache  = new \Maverick\Lib\Cache('homepageFlexImages', 1800);
        $toShow           = $flexImagesCache->get();

        if($toShow == false) {
            $dir    = PUBLIC_PATH . self::PATH_TO_FLEX_IMAGES;
            $images = array();
    
            if(is_dir($dir)) {
                $openDir = opendir($dir);

                $coasters = new \Application\Service\RollerCoasters;

                while(($file = readdir($openDir)) !== false) {
                    if($file[0] != '.') {
                        $expFile = explode('.', $file);
                        $expData = explode('-', $expFile[0]);

                        $theCoaster = ucwords(str_replace('_', ' ', $expData[0]));
                        $thePark    = ucwords(str_replace('_', ' ', $expData[1]));

                        $images[] = array($file, $theCoaster, $thePark);
                    }
                }
    
                $i   = 0;
                $max = count($images) - 1;
    
                while($i < self::NUM_FLEX_IMAGES) {
                    $idx = rand(0, $max);
    
                    if(array_key_exists($idx, $images)) {
                        $theCoaster = $images[$idx][1];
                        $thePark    = $images[$idx][2];

                        $coaster = $coasters->getForFlex($theCoaster, $thePark);
                        $msg     = '%s at %s';

                        if(!is_null($coaster)) {
                            $theCoaster = $coaster->getLink();
                            $thePark    = $coaster->getPark()->getLink();
                        }

                        $toShow[$i] = array($images[$idx][0], sprintf($msg, $theCoaster, $thePark));
    
                        unset($images[$idx]);
    
                        $i++;
                    }
                }
            }

            $flexImagesCache->set($toShow);
        }

        $this->setVariable('urlToImage', self::PATH_TO_FLEX_IMAGES . '/');
        $this->setVariable('flexImages', $toShow);
    }

    private function getRecentNews() {
        $featuredTopicsCache = new \Maverick\Lib\Cache('featuredTopics');
        $recentNews          = $featuredTopicsCache->get();

        if(!is_array($recentNews)) {
            $topics     = new \Application\Service\Topics;
            $recentNews = $topics->getRecentNews();

            $featuredTopicsCache->set($recentNews);
        }

        $this->setVariable('recentNews', $recentNews);
    }

    private function getHighestRated() {
        $topCoastersCache = new \Maverick\Lib\Cache('topRatedCoasters', 3600);
        $topCoasters      = $topCoastersCache->get();

        if(!$topCoasters) {
            $rollerCoasters = new \Application\Service\RollerCoasters;
            $topCoasters    = $rollerCoasters->getHighestRated();

            $topCoastersCache->set($topCoasters);
        }

        $topParksCache = new \Maverick\Lib\Cache('topRatedParks', 3600);
        $topParks      = $topParksCache->get();

        if(!$topParks) {
            $amusementParks = new \Application\Service\AmusementParks;
            $topParks    = $amusementParks->getHighestRated();

            $topParksCache->set($topParks);
        }

        $this->setVariable('topRatedTabs', array('Roller Coasters' => array(true,  Output::getTplEngine()->getTemplate('Tabs/TopCoasters', array('topCoasters' => $topCoasters))),
                                                 'Amusement Parks' => array(false, Output::getTplEngine()->getTemplate('Tabs/TopParks', array('topParks' => $topParks)))));
    }
}