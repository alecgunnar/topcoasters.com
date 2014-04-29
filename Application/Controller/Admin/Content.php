<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Admin_Content extends \Maverick\Lib\Controller {
    public function main() {
        Output::setPageTitle('Content Overview');
        
        $cached   = round($this->determineCacheSize(), 5);
        $database = round($this->determineDatabaseSize(), 5);

        $total = $cached + $database;

        $this->setVariables(array('cached_content'   => $cached,
                                  'database_size'    => $database,
                                  'cached_percent'   => round(($total ? ($cached / $total) * 100 : 0), 5),
                                  'database_percent' => round(($total ? ($database / $total) * 100 : 0), 5)));
    }

    private function determineCacheSize() {
        $size     = 0;
        $cacheDir = APPLICATION_PATH . 'Cache/Storage/';

        $openDir = opendir($cacheDir);

        while(($file = readdir($openDir)) !== false) {
            if(is_dir($cacheDir . $file) && file_exists($cacheDir . $file . '/data.txt')) {
                $size += filesize($cacheDir . $file . '/data.txt');
            }
        }

        return (($size / 1024) / 1024);
    }

    private function determineDatabaseSize() {
        $size   = 0;
        $db     = new \Maverick\Lib\DataSource_MySql;
        $dbName = \Maverick\Maverick::getConfig('Database')->get('mysql')->get('default')->get('name');
        $query  = $db->query("SELECT table_schema '" . $dbName . "', Round(Sum(data_length + index_length) / 1024 / 1024, 1) 'size' FROM information_schema.tables GROUP BY table_schema;");

        while($result = $db->fetch($query)) {
            $size += $result['size'];
        }

        return $size;
    }
}