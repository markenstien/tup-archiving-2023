<?php 

    namespace Form;
    use Core\Form;

    load(['Form'], CORE);

    class FeedForm extends Form
    {

        public function __construct()
        {
            parent::__construct('feed-form');

            $this->addParentKey();
            $this->addParentId();
            $this->addTitle();
            $this->addContent();
            $this->addTags();
            $this->addType();
            $this->addCategory();
            $this->addOwnerId();
        }

        public function addTitle() {
            $this->add([
                'name' => 'title',
                'type' => 'text',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Title'
                ],
                'required' => true
            ]);
        }

        public function addContent() {
            $this->add([
                'name' => 'content',
                'type' => 'textarea',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Content'
                ],
                'attributes' => [
                    'rows' => 5
                ],
                'require' => true
            ]);
        }

        public function addOwnerId() {
            $this->add([
                'name' => 'owner_id',
                'type' => 'hidden',
                'value' => whoIs('id')
            ]);
        }

        public function addTags() {
            $this->add([
                'name' => 'tags',
                'type' => 'text',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Tags Seperate by comma(,)'
                ]
            ]);
        }

        public function addCategory() {
            $this->add([
                'name' => 'category',
                'type' => 'select',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Category',
                    'option_values' => [
                        'informative',
                        'must-read',
                        'warning'
                    ]
                ]
            ]);
        }

        public function addType() {
            $this->add([
                'name' => 'type',
                'type' => 'select',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Type',
                    'option_values' => [
                        'announcements',
                        'feed'
                    ]
                ],
                'required' => true
            ]);
        }

        public function addParentKey() {
            $this->add([
                'name' => 'parent_key',
                'type' => 'hidden',
            ]);
        }

        public function addParentId() {
            $this->add([
                'name' => 'parent_id',
                'type' => 'hidden',
            ]);
        }
    }