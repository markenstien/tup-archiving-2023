<?php

    use Form\ItemCommentForm;
    use Form\ItemForm;
    use Services\CommonMetaService;
    use Services\ItemService;
    use Services\QRTokenService;
    use Services\UserService;

    load(['ItemForm','ItemCommentForm'],FORMS);
    load(['CommonMetaService', 'ItemService', 'QRTokenService', 'UserService'], SERVICES);


    class ItemController extends Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->model = model('ItemModel');
            $this->commentModel = model('ItemCommentModel');
            $this->category = model('CategoryModel');
            $this->itemService = new ItemService();
            _requireAuth();

            /**
             * CHANGE ATTACHMENT PATH
             */
            $this->_attachmentModel->setPath(PATH_UPLOAD.DS.'catalogs');
            $this->_attachmentModel->setURL(GET_PATH_UPLOAD.DS.'catalogs');
            $this->data['_form'] = new ItemForm();
            $this->data['_formComment'] = new ItemCommentForm();
        }
        public function index() {
            $req = request()->inputs();

            if(isset($req['advance_search'])) {
                return $this->advanceFilter();
            }

            /*
            *keyword search
            */
            if(!empty($req['keyword']))
            {
                $searchBy = $req['searchBy'] ?? 'keyword';
                $keyword = $req['keyword'];

                switch($searchBy) {
                    case 'tag':
                        $this->itemService->saveKeyword($keyword, 'tags');
                        $catalogs = $this->model->getAll([
                            'where' => [
                                'tags' => [
                                    'condition' => 'like',
                                    'value' => "%{$keyword}%"
                                ],
                            ],

                            'order' => 'view_total desc'
                        ]);
                    break;

                    case 'genre' :
                        $this->itemService->saveKeyword($keyword, 'genre');
                        $catalogs = $this->model->getAll([
                            'where' => [
                                'genre' => [
                                    'condition' => 'like',
                                    'value' => "%{$keyword}%"
                                ],
                            ],
                            'order' => 'view_total desc'
                        ]);
                    break;

                    case 'author' :
                        $this->itemService->saveKeyword($keyword, 'authors');
                        $catalogs = $this->model->getAll([
                            'where' => [
                                'authors' => [
                                    'condition' => 'like',
                                    'value' => "%{$keyword}%"
                                ],
                            ],
                            'order' => 'view_total desc'
                        ]);
                    break;

                    case 'publishers' :
                        $this->itemService->saveKeyword($keyword, 'publishers');
                        $catalogs = $this->model->getAll([
                            'where' => [
                                'publishers' => [
                                    'condition' => 'like',
                                    'value' => "%{$keyword}%"
                                ],
                            ],
                            'order' => 'view_total desc'
                        ]);
                    break;

                    default:
                        $keyword = trim($req['keyword']);
                        $this->itemService->saveKeyword($keyword);
                        if(substr($keyword, 0, 1) === "#") {
                            $keyword = substr($keyword, 1);

                            $catalogs = $this->model->getAll([
                                'where' => [
                                    'tags' => [
                                        'condition' => 'like',
                                        'value' => '%'.$keyword .'%'
                                    ]
                                    ],
                                'order' => 'view_total desc'
                            ]);
                            
                        } else 
                        {
                            $isSpecificSearch = false;
                            $arrayKeyWord = explode('&;', $keyword);

                            $genreKeyWord = $keyword;
                            $authorKeyword =  $keyword;
                            $publisherKeyword = $keyword;
                            $year = $keyword;

                            if($arrayKeyWord) {
                                if(empty($arrayKeyWord[0])) {
                                    $searchSpecific = $arrayKeyWord;
                                    $searchSpecific = array_splice($searchSpecific,1);
                                    $condition = [
                                        'where' => [],
                                        'order' => 'view_total desc'
                                    ];

                                    foreach($searchSpecific as $key => $row) {
                                        $toMatch = explode('=',$row);
                                        $condition['where'][$toMatch[0]] = [
                                            'condition' => 'like',
                                            'value' => '%'.$toMatch[1].'%',
                                                'concatinator' => 'OR'
                                        ];
                                    }

                                    $catalogs = $this->model->getAll($condition);
                                    $isSpecificSearch = true;
                                }
                                $mainKeyword = $arrayKeyWord[0];
                            } else{
                                $mainKeyword = $keyword;
                            }
                            
                            if(!$isSpecificSearch) {
                                $catalogs = $this->model->getAll([
                                    'where' => [
                                        'title' => [
                                            'condition' => 'like',
                                            'value' => '%'.$mainKeyword.'%',
                                            'concatinator' => 'OR'
                                        ],

                                        'reference' => [
                                            'condition' => 'like',
                                            'value' => '%'.$mainKeyword.'%',
                                            'concatinator' => 'OR'
                                        ]
                                    ],
                                    'order' => 'view_total desc'
                                ]);

                                foreach($arrayKeyWord as $key => $row) {
                                    if($key == 0) {
                                        continue;
                                    }

                                    $toMatch = explode('=',$row);

                                    if($toMatch > 0) {
                                        if(isEqual($toMatch[0], ['publishers','publisher','pub'])) {
                                            $publisherKeyword = $toMatch[1];
                                        }

                                        if(isEqual($toMatch[0], ['genre','genres','gen'])) {
                                            $genreKeyWord = $toMatch[1];
                                        }

                                        if(isEqual($toMatch[0], ['authors','author','aut'])) {
                                            $authorKeyword = $toMatch[1];
                                        }
                                        if(isEqual($toMatch[0], ['years','year','yr'])) {
                                            $year = $toMatch[1];
                                        }
                                    }
                                }
                            }   
                            $subResults = $this->model->getAll([
                                'where' => [
                                    'genre' => [
                                        'condition' => 'like',
                                        'value' => '%'.$genreKeyWord.'%',
                                        'concatinator' => 'OR'
                                    ],
                                    'authors' => [
                                        'condition' => 'like',
                                        'value' => '%'.$authorKeyword.'%',
                                        'concatinator' => 'OR'
                                    ],

                                    'publishers' => [
                                        'condition' => 'like',
                                        'value' => '%'.$publisherKeyword.'%',
                                        'concatinator' => 'OR'
                                    ],

                                    'year' => [
                                        'condition' => 'like',
                                        'value' => '%'.$publisherKeyword.'%',
                                        'concatinator' => 'OR'
                                    ],
                                ],
                                'order' => 'view_total desc'
                            ]);
                            //1 result only and no sub results
                            if(empty($subResults) && count($arrayKeyWord) < 2 && $catalogs) {
                                $catalog = $catalogs[0];

                                $subResults = $this->model->getAll([
                                    'where' => [
                                        'GROUP_CONDITION' => [
                                            'genre' => [
                                                'condition' => 'like',
                                                'value' => '%'.$catalog->genre.'%',
                                                'concatinator' => 'OR'
                                            ],
                                            'publishers' => [
                                                'condition' => 'like',
                                                'value' => '%'.$catalog->publishers.'%',
                                                'concatinator' => 'OR'
                                            ],
                                        ],
                                        'item.id' => [
                                            'condition' => 'not equal',
                                            'value' => $catalog->id
                                        ]
                                    ],

                                    'order' => 'meta_view.total_count desc',
                                    'limit' => '20'
                                ]);
                            }

                            if($catalogs) 
                            {
                                $catalog = $catalogs[0];
                                $catalogIds = [];

                                if(!empty($subResults)) {
                                    foreach($subResults as $key => $row) {
                                        $catalogIds[] = $row->id;
                                    }
                                }

                                $catalogIds[] = $catalog->id;
                                $otherResults = $this->model->getAll([
                                    'where' => [
                                        'GROUP_CONDITION' => [
                                            'genre' => [
                                                'condition' => 'like',
                                                'value' => '%'.$catalog->genre.'%',
                                                'concatinator' => 'OR'
                                            ],
                                            'authors' => [
                                                'condition' => 'like',
                                                'value' => '%'.$catalog->authors.'%',
                                                'concatinator' => 'OR'
                                            ],
                                        ],
                                        'item.id' => [
                                            'condition' => 'not in',
                                            'value' => $catalogIds
                                        ]
                                    ],
                                    
                                    'order' => 'meta_view.total_count asc'
                                ]);
                            }
                            
                            $catalogs = array_merge($catalogs, $subResults);


                            if(empty($catalogs) && empty($subResults)) {
                                
                                $possibleCatalogs = $this->model->getAll([
                                    'where' => [
                                        'description' => [
                                                'condition' => 'like',
                                                'value' => '%'.$mainKeyword.'%',
                                                'concatinator' => 'OR'
                                            ],
                                            'brief' => [
                                                'condition' => 'like',
                                                'value' => '%'.$mainKeyword.'%',
                                                'concatinator' => 'OR'
                                            ],
                                        ],

                                        'order' => 'meta_view.total_count desc',
                                        'limit' => '20'
                                    ]);
                            }
                        }
                }

                $this->data['catalogs'] = $catalogs ?? [];
                $this->data['otherResults'] = $otherResults ?? [];
                $this->data['possibleCatalogs'] = $possibleCatalogs ?? [];
            }

            if(isset($req['searchTokenKey'])) {
                $searchTokenKey = unseal($req['searchTokenKey']);
                $category = $this->category->get($searchTokenKey);

                if($category) {
                    $children = $this->category->all([
                        'cat.parent_id' => $category->id
                    ]);

                    $this->data['children'] = $children;
                }
            }
            $categoriesOriginal = $this->category->all([
                'cat.active' => true
            ],'cat.name asc');

            $categories = arr_layout_keypair($categoriesOriginal, ['id', 'name'], null, ' - ');

            $this->data['categories'] = $categories;
            $this->data['categoriesOriginal'] = $categoriesOriginal;
            return $this->view('item/index', $this->data);
        }

        private function advanceFilter() {
            $req = request()->inputs();
            $isAllowedFilter = false;

            foreach($req as $key => $value) {
                if($key == 'advance_search')
                    continue;

                if(!empty($value)) {
                    $isAllowedFilter = true;
                    break;
                }
            }

            if(!$isAllowedFilter) {
                Flash::set("Invalid Filter", 'danger');
                return request()->return();
            }

            $filterBuilder = [];

            if(!empty($req['category_id_parent'])) {
                $filterBuilder['category_id'] = $req['category_id_parent'];
            }
            //overwrite if category child is set
            if(!empty($req['category_child'])) {
                $filterBuilder['category_id'] = [
                    'condition' => 'in',
                    'value'     => $req['category_child']
                ];
            }

            if(!empty($req['publishers'])) {
                $filterBuilder['publishers'] = $req['publishers'];
            }

            if(!empty($req['authors'])) {
                $filterBuilder['authors'] = $req['authors'];
            }

            if(!empty($req['year'])) {
                $filterBuilder['year'] = $req['year'];
            }

            if(!empty($req['keyword'])) {
                $filterBuilder['brief'] = $req['keyword'];
                $filterBuilder['tags'] = $req['tags'];
                $filterBuilder['title'] = $req['title'];
            }

            $filterBuilder = $this->model->conditionConvert($filterBuilder, 'like');
            $catalogs = $this->model->getAll([
                            'where' => $filterBuilder]);

            $this->data['catalogs'] = $catalogs;
            $this->data['otherResults'] = $otherResults ?? [];
            $this->data['possibleCatalogs'] = $possibleCatalogs ?? [];

            return $this->view('item/index', $this->data);
        }
        
        public function create() {
            if(isSubmitted()) {
                $post = request()->posts();
                $catalogId = $this->model->createOrUpdate($post);
                if(!$catalogId) {
                    Flash::set($this->model->getErrorString(), 'danger');
                    return request()->return();
                }

                $qrValue = URL.DS._route('item:show', $catalogId, [
					'tokenID' => seal($catalogId)
				]);

				$qrName = seal(random_number(15).$catalogId);
				$QRCODE = QRTokenService::createIMAGE([
					'qr_name' => $qrName,
					'path_upload' => PATH_UPLOAD.DS.'catalogs'.DS.'qr_codes',
					'image_link'  => GET_PATH_UPLOAD.'/'.'catalogs/qr_codes',
					'qr_value' => $qrValue 
				]);

				$this->model->update([
                    'qr_path' => $QRCODE['qr_path'],
                    'qr_link' => $QRCODE['qr_link'],
                    'qr_value' => $QRCODE['qr_value'],
                ], $catalogId);

                if(!upload_empty('pdf_file')) {
                    $this->_attachmentModel->upload([
                        'global_key' => 'CATALOG_PDF_FILE',
                        'global_id'  => $catalogId,
                    ], 'pdf_file');
                }

                if(!upload_empty('wallpaper')) {
                    $this->_attachmentModel->upload([
                        'global_key' => 'CATALOG_WALLPAPER',
                        'global_id'  => $catalogId,
                    ], 'wallpaper');
                }


                Flash::set("Catalog Created");
                return redirect(_route('item:show', $catalogId));
            }
            return $this->view('item/create', $this->data);
        }

        public function edit($id) {
            $catalog = $this->model->get($id);
            if(isSubmitted()) {
                $post = request()->posts();
                $isOkay = $this->model->createOrUpdate($post, $post['id']);

                if($isOkay) {
                    Flash::set("Catalog updated successfully");
                    return redirect(_route('item:show', $id));
                }else{
                    Flash::set($this->model->getErrorString(), 'danger');
                    return request()->return();
                }
                
            }
            $form = $this->data['_form'];

            $form->add([
                'type' => 'hidden',
                'name' => 'id',
                'value' => $catalog->id
            ]);

            $category = $this->category->get($catalog->category_id);

            if($category) {
                if($category->parent_id) {
                    $categoryParent = $this->category->get($category->parent_id);
                    $form->setValue('category_id_parent', $categoryParent->id);
    
                    $categories = $this->category->all([
                        'cat.active' => true,
                        'cat.parent_id' => $category->parent_id
                    ],'cat.name asc');
    
                    $categories = arr_layout_keypair($categories, ['id', 'category@name'], null, ' - ');
    
                    $form->add([
                        'type' => 'select',
                        'name' => 'category_id',
                        'options' => [
                            'option_values' => $categories
                        ],
                        'attributes' => [
                            'id' => 'category_id'
                        ],
                        'class' => 'form-control',
                        'value' => $category->id
                    ]);
                }
    
                $form->setValue('category_id', $categoryParent->id);
            }
            $form->setValueObject($catalog);

            $this->data['catalog'] = $catalog;
            $this->data['form'] = $form;

            $this->data['category_id'] = isset($categoryParent);
            

            return $this->view('item/edit', $this->data);
        }

        public function show($id) {
            $catalog = $this->model->get($id);

            if(!$catalog) {
                Flash::set("Catalog does not exists", 'danger');
                return redirect(_route('item:index'));
            }

            CommonMetaService::addRecord($id, CommonMetaService::CATALOG_VIEW, 1);

            $this->data['_formComment']->add([
                'type' => 'hidden',
                'name' => 'returnTo',
                'value' => seal(_route('item:show', $id))
            ]);

            $this->data['_formComment']->setValue('item_id',$id);

            if(!$catalog) {
                Flash::set("Catalog does not exists", 'danger');
                return redirect(_route('item:index'));
            }

            $this->data['catalog'] = $catalog;
            $this->data['catalogItems'] = [
                'authors' => str_explode_trim(',', $catalog->authors),
                'publishers' => str_explode_trim(',', $catalog->publishers),
            ];

            $this->data['userUploads'] = $this->model->getAll([
                'where' => [
                    'item.uploader_id' => whoIs('id'),
                    'item.id' => [
                        'condition' => 'not equal',
                        'value' => $catalog->id
                    ]
                ]
            ]);

            $this->data['relatedCatalogs'] = $this->model->relatedCatalogs($id);
            $this->data['comments'] = $this->commentModel->getComments($id);

            return $this->view('item/show', $this->data);
        }

        public function myCatalog($id = null) {
            $id = $id ?? whoIs('id');

            $this->data['catalogs'] = $this->model->getAll([
                'where' => [
                    'item.uploader_id' => $id
                ],
                'order' => 'item.title asc'
            ]);
            return $this->view('item/my_catalog', $this->data);
        }

        public function read($id) {
            $catalog = $this->model->get($id);
            CommonMetaService::addRecord($id, CommonMetaService::CATALOG_READ, 1);
            $this->data['catalog'] = $catalog;
            return $this->view('item/read', $this->data);
        }

        public function like($id) {
            CommonMetaService::addRecord($id, CommonMetaService::CATALOG_LIKE, 1);

            Flash::set(CommonMetaService::$message, 'warning', 'item-like');
            return redirect(_route('item:show', $id));
        }

        public function destroy($id) {
            $catalog = $this->model->get($id);

            if(!$catalog) {
                Flash::set("Unable to delete catalog");
                return redirect(_route('item:index'));
            }

            $this->model->delete($id);

            Flash::set("Catalog Removed");
            return redirect(_route('item:my-catalog'));
        }

        public function addAttachment() {
            $req = request()->inputs();

            if(isSubmitted()) {
                $id = unseal($req['id']);

                if(isEqual($req['type'], 'pdf_file')) {
                    if(!upload_empty('pdf_file')) {
                        $isokay = $this->_attachmentModel->upload([
                            'global_key' => 'CATALOG_PDF_FILE',
                            'global_id'  => $id,
                        ], 'pdf_file');

                        if($isokay) {
                            Flash::set("Document Uploaded");
                            return redirect(_route('item:show', $id));
                        }
                    }
                } else if(isEqual($req['type'], 'wallpaper')) {
                    if(!upload_empty('wallpaper')) {
                        $isokay = $this->_attachmentModel->upload([
                            'global_key' => 'CATALOG_WALLPAPER',
                            'global_id'  => $id,
                        ], 'wallpaper');

                        if($isokay) {
                            Flash::set("Wallpaper Uploaded");
                            return redirect(_route('item:show', $id));
                        }
                        
                    }
                } else {
                    Flash::set("Invalid Attachment Type");
                    return redirect(_route('item:show', $id));
                }
            }

            $this->data['id'] = unseal($req['id']);
            $this->data['type'] = $req['type'];
            return $this->view('item/add_attachment', $this->data);
        }

        public function catalogs() {
            // if(!isEqual($this->data['whoIs']->user_type, UserService::ADMIN)) {
            //     Flash::set("You are not authorized to access that page", 'warning');
            //     return redirect(_route('item:my-catalog'));
            // }
            $req = request()->inputs();
            $condition = null;

            if(isset($req['searchFocus'])) {
                $parentId = $req['searchTokenKey'];
                $parentId = unseal($parentId);
                $categories = $this->category->all([
                    'cat.parent_id' => $parentId
                ]);

                $categoyIds = [];
                foreach($categories as $key => $row) {
                    $categoyIds[] = $row->id;
                }

                $condition = [
                    'category_id' => [
                        'condition' => 'in',
                        'value' => $categoyIds
                    ]
                ];
            }
            $this->data['catalogs'] = $this->model->getAll([
                'where' => $condition
            ]);

            return $this->view('item/catalogs', $this->data);
        }
    }