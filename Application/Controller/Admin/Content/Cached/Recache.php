<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Admin_Content_Cached_Recache extends \Maverick\Lib\Controller {
    public function main($toRecache='') {
        try {
            $cache = new \Maverick\Lib\Cache($toRecache);
            $cache->recache();
        } catch(\Exception $e) { }

        \Maverick\Lib\Http::location('/admin/content/cached', 'The cache has been recached.');
    }
}