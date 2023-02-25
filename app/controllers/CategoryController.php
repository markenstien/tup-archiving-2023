<?php

    use Form\CategoryForm;
    load(['CategoryForm'],APPROOT.DS.'form');

    class CategoryController extends Controller
    {
        public function __construct()
        {
            $this->model = model('CategoryModel');
            $this->categoryForm = new CategoryForm();
        }

        public function index() {
            $req = request()->inputs();

            if($req) {
                $this->data['categories'] = $this->model->all([
                    'cat.category' => $req['category']
                ], 'category desc,name asc');
            } else {
                $this->data['categories'] = $this->model->all(null, 'category desc,name asc');
            }

            return $this->view('category/index', $this->data);
        }

        public function show($id) {
            $category = $this->model->get($id);

            if(isEqual($category->category, 'CATALOG_PARENT')) {
                $children = $this->model->all([
                    'cat.parent_id' => $category->id
                ]);

                $this->data['children'] = $children;
                $this->data['category'] = $category;

                return $this->view('category/show', $this->data);
            } else {
                Flash::set("This category is not parent", 'warning');
                return request()->return();
            }
        }

        public function create() {
            $req = request()->inputs();
            if(isSubmitted()) {
                $req = $this->model->createOrUpdate($req);

                if($req) {
                    Flash::set($this->model->getMessageString());
                }else{
                    Flash::set($this->model->getErrorString(),'danger');
                }

                return redirect(_route('category:index'));
            }
            $this->categoryForm->init([
                'method' => 'post',
                'url' => _route('category:create')
            ]);

            $this->data['category_form'] = $this->categoryForm;
            return $this->view('category/create', $this->data);
        }

        public function edit($id) {
            $req = request()->inputs();
            if(isSubmitted()) {
                $req = $this->model->createOrUpdate($req, $id);

                if($req) {
                    Flash::set($this->model->getMessageString());
                }else{
                    Flash::set($this->model->getErrorString(),'danger');
                }

                return redirect(_route('category:edit', $id));
            }
            $this->categoryForm->init([
                'method' => 'post',
                'url' => _route('category:edit', $id)
            ]);

            $category = $this->model->get($id);

            $this->categoryForm->setValueObject($category);
            $this->data['category_form'] = $this->categoryForm;
            $this->data['category'] = $category;

            return $this->view('category/edit', $this->data);
        }

        public function deactivateOrActivate($id) {
            $this->model->deactivateOrActivate($id);
            Flash::set($this->model->getMessageString());
            return redirect(_route('category:index'));
        }

        //remove from parent
        public function remove($id) {
            $this->model->update([
                'parent_id' => null
            ], $id);

            Flash::set("Removed from Child");
            return request()->return();
        }
    }