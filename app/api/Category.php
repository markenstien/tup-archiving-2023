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
            
            if(!$category->parent_id) {
                $categories = $this->model->all([
                    'cat.parent_id' => $category->id
                ]);
                echo json_encode($categories);
            }
           
        }
    }