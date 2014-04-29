<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Admin_Content_Cached extends \Maverick\Lib\Controller {
    public function main() {
        Output::setPageTitle('Cached Content');

        $caches   = array();
        $cacheDir = APPLICATION_PATH . 'Cache/';
        $openDir  = opendir($cacheDir . 'Storage');

        while(($cache = readdir($openDir)) !== false) {
            if($cache[0] != '.') {
                $caches[] = array('key'     => $cache,
                                  'size'    => round(((file_exists($cacheDir . 'Storage/' . $cache . '/data.txt') ? filesize($cacheDir . 'Storage/' . $cache . '/data.txt') : 0) / 1024), 3),
                                  'recache' => file_exists($cacheDir . 'Controller/' . ucfirst($cache) . PHP_EXT));
            }
        }

        $this->setVariable('caches', $caches);
    }
}