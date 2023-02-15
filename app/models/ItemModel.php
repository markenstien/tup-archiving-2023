<?php

    use Services\CommonMetaService;
    load(['CommonMetaService'],SERVICES);
    
    class ItemModel extends Model
    {
        public $table = 'items';
        public $_fillables = [
            'reference',
            'title',
            'brief',
            'description',
            'tags',
            'genre',
            'year',
            'publishers',
            'authors',
            'qr_link',
            'qr_path',
            'qr_value',
            'category_id'
        ];

        public function createOrUpdate($itemData, $id = null) 
        {
            $this->categoryModel = model('CategoryModel');
            
            $category = $this->categoryModel->get($itemData['category_id_parent']);
            //this means that this category is parent
            if(!$category->parent_id) {
                //then category_id must not be empty
                if(empty($itemData['category_id'])) {
                    $this->addError("Category id must not be empty!");
                    return false;
                } else {
                    $itemData['category_id_parent'] = $itemData['category_id'];
                }
            } else {
                $itemData['category_id'] = $itemData['category_id_parent'];
            }

            if(empty($itemData['authors'])) {
                $this->addError("Authors must not be empty");
            }
            
            if(empty($itemData['publishers'])) {
                $this->addError("Publishers should not be empty");
            }

            $_fillables = parent::getFillablesOnly($itemData);
            
            if(is_null($id)) {
                $_fillables['uploader_id'] = whoIs('id');
                if(empty($itemData['reference'])) {
                    $_fillables['reference'] = random_number(12);
                    if(!$this->primaryValidation($_fillables))
                        return false;
                }
            } else {
                if(!$this->primaryValidation($_fillables, $this->get($id)))
                    return false;
            }

            $clean = [
                'title',
                'genre',
                'publishers',
                'authors',
                'year'
            ];

            foreach($clean as $key => $row) {
                if(isset($_fillables[$row])) {
                    $_fillables[$row] = str_replace('"', '', $_fillables[$row]);
                }
            }

            if(is_null($id)) {
                return parent::store($_fillables);
            }else{
                return parent::update($_fillables, $id);
            }
        }

        /**
         * passed as string
         */
        public function checkUsers($userids) {
            $userids = array_walk(explode(',', $userids), 'trim');

            if(!isset($this->userModel)) {
                $this->userModel = model('UserModel');
            }

            $users = $this->userModel->all([
                'user_identification' => [
                    'condition' => 'in',
                    'value' => $userids
                ]
            ]);


            foreach($users as $key => $row) {
                $userIdSearch = array_search($row->user_identification, $userids);
                if($userIdSearch !== FALSE) {
                    unset($userids[$userIdSearch]);
                }
            }

            if(!empty($userids)) {
                $this->addError("Users ".implode(',' , $userids)." Does not exist");
                return false;
            }

            return $users;
        }

        public function get($id) {
            if(!isset($this->attachmentModel)) {
                $this->attachmentModel = model('AttachmentModel');
            }
            
            $item = $this->getAll([
                'where' => [
                    'item.id' => $id
                ]
            ]);
            
            if(!$item) {
                $this->addError("Cataloig does not exists");
                return false;
            }

            $item = $item[0];

            $item->wallpaper = $this->attachmentModel->single([
                'global_key' => 'CATALOG_WALLPAPER',
                'global_id'  => $id
            ]);

            $item->document = $this->attachmentModel->single([
                'global_key' => 'CATALOG_PDF_FILE',
                'global_id'  => $id
            ]);

            return $item;
        }

        public function getAll($params = []) {
            $where = null;
            $order = null;
            $limit = null;

            if(isset($params['where'])) {
                $where = " WHERE ".parent::conditionConvert($params['where']);
            }

            if(isset($params['order'])) {
                $order = " ORDER BY {$params['order']}";
            }

            if(isset($params['limit'])) {
                $limit = " LIMIT {$params['limit']}";
            }

            $catalogLike = CommonMetaService::CATALOG_LIKE;
            $catalogRead = CommonMetaService::CATALOG_READ;
            $catalogView = CommonMetaService::CATALOG_VIEW;

            $this->db->query(
                "SELECT item.*, concat(user.firstname , ' ',user.lastname) as uploader_name,
                    ifnull(category.name, 'no category') as category_name,
                    meta_read.total_count as read_total,
                    meta_like.total_count as like_total,
                    meta_view.total_count as view_total
                    FROM {$this->table} as item

                    LEFT JOIN users as user 
                    ON item.uploader_id = user.id

                    LEFT JOIN (
                        SELECT total_count, meta_name,parent_id
                            FROM v_item_meta
                            WHERE meta_name = '{$catalogLike}'
                    ) as meta_like
                    ON meta_like.parent_id = item.id

                    LEFT JOIN (
                        SELECT total_count, meta_name,parent_id
                            FROM v_item_meta
                            WHERE meta_name = '{$catalogView}'
                    ) as meta_view
                    ON meta_view.parent_id = item.id


                    LEFT JOIN (
                        SELECT total_count, meta_name,parent_id
                            FROM v_item_meta
                            WHERE meta_name = '{$catalogRead}'
                    ) as meta_read
                    ON meta_read.parent_id = item.id
                    
                    LEFT JOIN categories as category
                    ON category.id = item.category_id
                    {$where}{$order}{$limit}"
            );

            return $this->db->resultSet();
        }

        public function primaryValidation($catalogData, $referenceCatalog = null) {
            $isCheckable = true;
            if(!isset($catalogData['title'], $catalogData['year'], $catalogData['authors'])) {
                $isCheckable = false;
            }

            if($isCheckable) {
                $catalog = parent::single([
                    'title' => $catalogData['title'],
                    'year'  => $catalogData['year']
                ]);
                
                if(!is_null($referenceCatalog)) {
                    if($catalog->id == $referenceCatalog->id)
                        return true;
                }

                if($catalog) {
                    $this->addError("Catalog Already existed, advice administrator to assist you on your catalog upload");
                    return false;
                } else {
                    $this->addMessage("Catalog uploaded");
                    return true;
                }
            }
            return true;
        }

        public function relatedCatalogs($id) {
            $catalog = $this->get($id);
            if(!$catalog)
                return false;
            
            $condition = parent::conditionConvert([
                '1' => [
                    'condition' => 'db_condition_wrap',
                    'value' => parent::conditionConvert([
                        'genre' => [
                            'condition' => 'like',
                            'value' => '%'.$catalog->genre.'__%',
                            'concatinator' => 'OR'
                        ],
                        'tags' => [
                            'condition' => 'like',
                            'value' => '%'.$catalog->tags.'%',
                            'concatinator' => 'OR'
                        ],
                        'publishers' => [
                            'condition' => 'like',
                            'value' => '%'. str_escape($catalog->publishers) .'%',
                            'concatinator' => 'OR'
                        ],
                        'authors' => [
                            'condition' => 'like',
                            'value' => '%'.$catalog->authors.'%',
                            'concatinator' => 'OR'
                        ]
                    ]),
                    'concatinator' => ' AND '    
                ],

                'item.id' => [
                    'condition' => 'not equal',
                    'value' => $id
                ] 
            ]);
            return $this->getAll([
                'where' => $condition]);
        }
    }