<?php
    namespace Form;
    
    use Core\Form;
    use Services\CategoryService;

    load(['Form'],CORE);
    load(['CategoryService'],SERVICES);
    
    class CategoryForm extends Form
    {
        public function __construct()
        {
            parent::__construct();

            $this->model = model('CategoryModel');
            $this->name = 'category_form';
            $this->addName();
            $this->addAbbr();
            $this->addParent();
            $this->addCategory();

            $this->customSubmit('Create New Category');
        }

        public function addName() {
            $this->add([
                'name' => 'name',
                'type' => 'text',
                'required' => true,
                'options' => [
                    'label' => 'Name'
                ],
                'class' => 'form-control'
            ]);
        }


        public function addAbbr() {
            $this->add([
                'name' => 'abbr',
                'type' => 'text',
                'required' => true,
                'options' => [
                    'label' => 'Abbr'
                ],
                'class' => 'form-control',
                'attributes' => [
                    'placeholder' => 'EG. BSIT maximum of 10 characters.'
                ]
            ]);
        }

        public function addCategory() {
            $this->add([
                'name' => 'category',
                'type' => 'select',
                'required' => true,
                'options' => [
                    'label' => 'Category For',
                    'option_values' => [
                        CategoryService::CATALOG_PARENT,
                        CategoryService::CATALOG_CHILD
                    ]
                ],
                'class' => 'form-control'
            ]);
        }

        public function addParent() {
            $categories = $this->model->all([
                'cat.active' => true,
                'cat.category' => CategoryService::CATALOG_PARENT
            ],'cat.name asc');
            $categories = arr_layout_keypair($categories, ['id', 'category@name'], null, ' - ');

            $this->add([
                'name' => 'parent_id',
                'type' => 'select',
                'options' => [
                    'label' => 'Parent',
                    'option_values' => $categories
                ],
                'class' => 'form-control'
            ]);
        }
    }