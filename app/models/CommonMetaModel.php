<?php 

    class CommonMetaModel extends Model
    {
        public $table = 'common_meta';
        public $_fillables = [
            'parent_id',
            'meta_key',
            'meta_value',
            'user_id'
        ];

        public function createOrUpdate($data, $id = null) {
            $_fillables = parent::getFillablesOnly($data);
            if(!is_null($id)) {
                $retVal = parent::update($_fillables, $id);
            }else{
                $retVal = parent::store($_fillables);
            }
            return $retVal;
        } 
    }