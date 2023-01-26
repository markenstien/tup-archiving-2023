<?php

    use Form\ItemCommentForm;
    use Form\ItemForm;
    use Services\CommonMetaService;
    use Services\ItemService;
    use Services\UserService;

    load(['ItemForm','ItemCommentForm'],FORMS);
    load(['CommonMetaService', 'ItemService'], SERVICES);


    class ItemController extends Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->model = model('ItemModel');
            $this->commentModel = model('ItemCommentModel');
            $this->itemService = new ItemService();

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

            if(!empty($req)) 
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
                            ]
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
                            ]
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
                            ]
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
                            ]
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
                                ]
                            ]);
                            
                        } else 
                        {
                            $arrayKeyWord = explode('&;', $keyword);

                            $genreKeyWord = $keyword;
                            $authorKeyword =  $keyword;
                            $publisherKeyword = $keyword;

                            if($arrayKeyWord) {
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
                                    }
                                }
                                $mainKeyword = $arrayKeyWord[0];
                            } else{
                                $mainKeyword = $keyword;
                            }

                            $catalogs = $this->model->getAll([
                                'where' => [
                                    'title' => [
                                        'condition' => 'like',
                                        'value' => '%'.$mainKeyword.'%',
                                        'concatinator' => 'OR'
                                    ]
                                ]
                            ]);

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
                                    ]
                                ]
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
            $form->setValueObject($catalog);
            $this->data['catalog'] = $catalog;
            

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
            if(!isEqual($this->data['whoIs']->user_type, UserService::ADMIN)) {
                Flash::set("You are not authorized to access that page", 'warning');
                return redirect(_route('item:my-catalog'));
            }

            $this->data['catalogs'] = $this->model->getAll([
                'order' => 'id desc'
            ]);

            return $this->view('item/catalogs', $this->data);
        }
    }