<?php 
    namespace Form;
    use Core\Form;
    load(['Form'], CORE);

    class ItemForm extends Form
    {
        public function __construct()
        {
            parent::__construct();
            $this->categoryModel = model('CategoryModel');
            $this->init([
                'enctype' => 'multipart/form-data'
            ]);
            $this->addReference();
            $this->addTitle();
            $this->addBrief();
            $this->addDescription();
            $this->addGenre();
            $this->addYear();
            $this->addCategory();
            $this->addChildCategory();
            $this->addTags();
            $this->addPdf();
            $this->addWallpaper();
            $this->addAuthors();
            $this->addPublishers();
        }

        public function addReference() {
            $this->add([
                'type' => 'text',
                'name' => 'reference',
                'options' => [
                    'label' => 'Reference'
                ],
                'class' => 'form-control',
            ]);
        }

        public function addTitle() {
            $this->add([
                'type' => 'text',
                'name' => 'title',
                'required' => true,
                'options' => [
                    'label' => 'Title'
                ],
                'class' => 'form-control',
            ]);
        }

        public function addBrief() {
            $this->add([
                'type' => 'textarea',
                'name' => 'brief',
                'required' => true,
                'options' => [
                    'label' => 'Course And Section'
                ],
                'class' => 'form-control',
                'attributes' => [
                    'rows' => '3'
                ]
            ]);
        }

        public function addDescription() {
            $this->add([
                'type' => 'textarea',
                'name' => 'description',
                'required' => true,
                'options' => [
                    'label' => 'Description'
                ],
                'class' => 'form-control',
                'attributes' => [
                    'rows' => '3'
                ]
            ]);
        }

        public function addGenre() {
            $this->add([
                'type' => 'text',
                'name' => 'genre',
                'required' => true,
                'options' => [
                    'label' => 'Genre'
                ],
                'class' => 'form-control'
            ]);
        }

        public function addYear() {
            $this->add([
                'type' => 'select',
                'name' => 'year',
                'required' => true,
                'options' => [
                    'label' => 'Year',
                    'option_values' => generate_year(1997)
                ],
                'class' => 'form-control'
            ]);
        }

        public function addCategory() 
        {
            $categories = $this->categoryModel->all([
                'cat.active' => true
            ],'cat.name asc');
            $categories = arr_layout_keypair($categories, ['id', 'name'], null, ' - ');

            $this->add([
                'type' => 'select',
                'name' => 'category_id_parent',
                'required' => true,
                'options' => [
                    'label' => 'Course',
                    'option_values' => $categories
                ],
                'attributes' => [
                    'id' => 'category_id_parent'
                ],
                'class' => 'form-control'
            ]);
        }

        public function addChildCategory() 
        {
            $categories = $this->categoryModel->all([
                'cat.active' => true,
                'cat.parent_id' => [
                    'condition' => 'not null'
                ]
            ],'cat.name asc');
            $categories = arr_layout_keypair($categories, ['id', 'name'], null, ' - ');

            $this->add([
                'type' => 'select',
                'name' => 'category_id',
                'required' => true,
                'options' => [
                    'label' => 'Category',
                    'option_values' => $categories
                ],
                'attributes' => [
                    'id' => 'category_id'
                ],
                'class' => 'form-control'
            ]);
        }

        public function addTags() {
            $this->add([
                'type' => 'text',
                'name' => 'tags',
                'required' => true,
                'options' => [
                    'label' => 'Tags'
                ],
                'class' => 'form-control'
            ]);
        }

        public function addPdf() {
            $this->add([
                'type' => 'file',
                'name' => 'pdf_file',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Readable PDF File'
                ],
            ]);
        }


        public function addWallpaper() {
            $this->add([
                'type' => 'file',
                'name' => 'wallpaper',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Catalog Profile'
                ],
            ]);
        }
        
        public function addAuthors() {
            $this->add([
                'type' => 'textarea',
                'name' => 'authors',
                'required' => true,
                'class' => 'form-control',
                'options' => [
                    'label' => 'Authors'
                ],
                'attributes' => [
                    'rows' => '4'
                ]
            ]);
        }

        public function addPublishers(){
            $this->add([
                'type' => 'textarea',
                'name' => 'publishers',
                'required' => true,
                'class' => 'form-control',
                'options' => [
                    'label' => 'Publishers'
                ],
                'attributes' => [
                    'rows' => '4'
                ]
            ]);
        }
    }