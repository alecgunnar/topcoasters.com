<?php

namespace Application\Service;

class Exchange extends Standard {
    protected function setUp() {
        $this->primaryKey = 'file_id';
        $this->dbTable    = 'exchange_files';
        $this->model      = 'ExchangeFile';
    }

    public function create($category, $name, $file, $screenshot, $description) {
        $time = new \Application\Lib\Time(null, true);

        $id = $this->db->put(array('name'        => $name,
                                   'member_id'   => \Application\Lib\Members::getMember()->get('member_id'),
                                   'category'    => $category,
                                   'file'        => $file,
                                   'screenshot'  => $screenshot,
                                   'description' => $description,
                                   'upload_date' => $time->getTimestamp()), $this->dbTable);

        if($id) {
            $recentExchangeFilesCache = new \Maverick\Lib\Cache('recentExchangeFiles');
            $recentExchangeFilesCache->clear();

            $file = $this->get($id);
            $file->update('seo_title', \Application\Lib\Utility::generateSeoTitle($file->getName(), $file->get('file_id')));

            return $this->commitChanges($file);
        }

        return false;
    }

    public function getRecentFiles() {
        return $this->db->get(array('select' => '*',
                                    'from'   => 'exchange_files',
                                    'order'  => 'upload_date DESC',
                                    'limit'  => 7), $this->model);
    }
}