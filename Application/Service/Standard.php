<?php

namespace Application\Service;

abstract class Standard {
    /**
     * The database connection
     */
    protected $db = null;

    /**
     * The database table used by this service
     *
     * @var string
     */
    protected $dbTable = '';

    /**
     * The the primary/unique key for this type
     *
     * @var string
     */
    protected $primaryKey = '';

    /**
     * The model to be used for this service
     *
     * @var string
     */
    protected $model = '';

    /**
     * The constructor
     */
    public function __construct() {
        $this->db = new \Maverick\Lib\DataSource_MySql;

        $this->setUp();
    }

    /**
     * Created a new row
     *
     * @param  array $data
     * @return mixed
     */
    public function put($data) {
        $dataEscaped = array();

        foreach($data as $column => $value) {
            if(@unserialize($value) !== false) {
                $dataEscaped[$column] = addslashes(addslashes($value));
            } else {
                $dataEscaped[$column] = $this->db->escape($value);
            }
        }

        $id = $this->db->put($dataEscaped, $this->dbTable);

        if($id) {
            $get = $this->get($id);

            if($get) {
                return $get;
            }
        }

        return false;
    }

    /**
     * Gets roller coasters
     *
     * @param  mixed   $value
     * @param  string  $column=''
     * @param  integer $start=0
     * @param  integer $limit=0
     * @param  boolean $asArray=false
     * @return array
     */
    public function get($value, $column='', $start=null, $limit=null, $asArray=false, $extraQuery=array()) {
        $query = array('select' => '*',
                       'from'   => $this->dbTable);

        if($value != '*') {
            if(!$column) {
                $column = $this->primaryKey;
            }

            $query['where']  = $column . ' = "' . $value . '"';
        }

        if(!is_null($start) && !is_null($limit)) {
            $query['limit'] = $start . ', ' . $limit;
        }

        $objs = $this->db->get(array_merge($query, $extraQuery), $this->model);

        if(($ct = count($objs))) {
            if($ct > 1 || $asArray) {
                return $objs;
            } else {
                return $objs[0];
            }
        }

        return false;
    }

    /**
     * Gets all of the database records
     *
     * @param  string $orderBy=''
     * @param  string $dir=''
     * @return array
     */
    public function getAll($orderBy='', $dir='') {
        $order = ($orderBy ?: $this->primaryKey) . ' ' . ($dir ?: 'desc');

        return $this->db->get(array('select' => '*',
                                    'from'   => $this->dbTable,
                                    'order'  => $order), $this->model);
    }

    /**
     * Commits data to be updated
     *
     * @param  \Application\Model\Standard $model
     * @return \Application\Model\Standard
     */
    public function commitChanges(\Application\Model\Standard $model) {
        if(!count($model->getDataToUpdate())) {
            return $model;
        }

        $data = $model->getDataToUpdate();

        foreach($data as $key => $value) {
            if(@unserialize($value) !== false) {
                $data[$key] = addslashes(addslashes($value));
            } else {
                $data[$key] = $this->db->escape($value);
            }
        }

        $this->db->post($data, array($this->primaryKey => $model->get($this->primaryKey)), $this->dbTable);

        $model->dataUpdated();

        return $model;
    }

    /** 
     * Deleted an object
     *
     * @param \Application\Model\Standard $model
     */
    public function delete($model) {
        $this->db->delete(array($this->primaryKey => $model->get($this->primaryKey)), $this->dbTable);
    }

    /**
     * Does initial setup of the service
     */
    protected abstract function setUp();
}