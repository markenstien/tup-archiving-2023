<?php 

    class Category extends Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->model = model('CategoryModel');
        }
        
        public function getChild() {
            $req = request()->inputs();
            $category = $this->model->get($req['category_id']);
            
            if(isEqual($category->category, 'CATALOG_PARENT') {
                $categories = $this->model->all([
                    'cat.parent_id' => $category->id
                ], 'cat.name asc');

                echo json_encode($categories);
            }
           
        }
    }