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
    }

    private function setupFlexslider() {
        Output::addCssFile('flexslider');

        $flexImagesCache  = new \Maverick\Lib\Cache('homepageFlexImages', 1800);
        $toShow           = $flexImagesCache->get();

        if($toShow == false) {
            $dir    = PUBLIC_PATH . self::PATH_TO_FLEX_IMAGES;
            $images = array();
    
            if(is_dir($dir)) {
                $openDir = opendir($dir);
    
                while(($file = readdir($openDir)) !== false) {
                    if($file[0] != '.') {
                        $expFile = explode('.', $file);
                        $expData = explode('-', $expFile[0]);
    
                        $images[] = array($file,
                                          ucwords(str_replace('_', ' ', $expData[0])) . ' at ' . ucwords(str_replace('_', ' ', $expData[1])));
                    }
                }
    
                $i   = 0;
                $max = count($images) - 1;
    
                while($i < self::NUM_FLEX_IMAGES) {
                    $idx = rand(0, $max);
    
                    if(array_key_exists($idx, $images)) {
                        $toShow[$i] = $images[$idx];
    
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
}