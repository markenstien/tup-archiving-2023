<?php

    class CategoryModel extends Model
    {
        public $table = 'categories';

        public $_fillables = [
            'name',
            'category',
            'parent_id',
            'abbr',
            'active'
        ];

        public function createOrUpdate($categoryData, $id = null) {
            $_fillables = parent::getFillablesOnly($categoryData);

            $checkingData['id'] = $id;
            $isCheckingGood = $this->checking($checkingData, $id);

            if(!$isCheckingGood) {
                return false;
            }
            if (!is_null($id)) 
            {
                $this->addMessage(parent::$MESSAGE_UPDATE_SUCCESS);
                return parent::update($_fillables, $id);
            } else {
                $_fillables['active'] = true;
                $this->addMessage(parent::$MESSAGE_CREATE_SUCCESS);
                return parent::store($_fillables);
            }
        }


        public function checking($data, $isUpdate = null) {
            if(isset($data['abbr']))  {
                $currentAbbr = parent::single([
                    'abbr' => $data['abbr']
                ]);

                if(!is_null($isUpdate)) {
                    if($currentAbbr->id != $data['id']) {
                        $this->addError("{$data['abbr']} Abbr already exists");
                        return false;
                    }
                } else {
                    $this->addError("{$data['abbr']} Abbr already exists");
                    return false;   
                }
            }

            return true;
        }
        
        public function deactivateOrActivate($id) {
            $category = parent::get($id);
            if(!$category) 
                return false;
            $this->addMessage(parent::$MESSAGE_UPDATE_SUCCESS);
            return parent::update([
                'active' => !$category->active
            ],$id);
        }

        public function all($where = null, $order = null, $limit = null) {

            if(!is_null($where)) {
                $where = " WHERE ". parent::conditionConvert($where);
            }

            if(!is_null($order)) {
                $order = " ORDER BY {$order}";
            }

            if(!is_null($limit)) {
                $limit = " LIMIT {$limit}";
            }

            $this->db->query(
                "SELECT cat.*,
                    ifnull(cat_parent.name , 'No Parent') as parent_name
                    FROM categories as cat 
                    LEFT JOIN categories as cat_parent
                    ON cat_parent.id = cat.parent_id
                {$where}{$order}{$limit}"
            );

            return $this->db->resultSet();
        }
    }