<?php 
    namespace Form;
    use Core\Form;
    load(['Form'], CORE);

    class ItemCommentForm extends Form {

        public function __construct()
        {
            parent::__construct();
            $this->init([
                'url' => _route('item-comment:create')
            ]);
            $this->addComment();
            $this->addItemId();
            $this->customSubmit('Add Comment','btn_comment', [
                'class' => 'btn btn-sm btn-primary mt-2'
            ]);
        }

        public function addComment() {

            $this->add([
                'type' => 'text',
                'name' => 'comment',
                'required' => true,
                'class' => 'form-control',
                'attributes' => [
                    'placeholder' => 'Enter your comment here..'
                ]
                
            ]);
        }

        public function addItemId() {

            $this->add([
                'type' => 'hidden',
                'name' => 'item_id',
                'required' => true
                
            ]);
        }
    }