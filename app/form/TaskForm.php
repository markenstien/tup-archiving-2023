<?php
    namespace Form;
    use Core\Form;
    load(['Form'], CORE);

    class TaskForm extends Form {
        
        public function __construct()
        {
            parent::__construct();
            $this->addName();
            $this->addCode();
            $this->addGoogleFormLink();
            $this->addDescription();
            $this->addStatus();
            $this->addParentId();
            $this->addCreatedBy();
            $this->addPassingScore();
            $this->customSubmit('Save Task');
        }

        public function addName() {
            $this->add([
                'type' => 'text',
                'name' => 'task_name',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Task Name'
                ]
            ]);
        }

        public function addCode() {
            $this->add([
                'type' => 'text',
                'name' => 'task_code',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Code'
                ]
            ]);
        }

        public function addGoogleFormLink() {
            $this->add([
                'name' => 'google_form_link',
                'type' => 'textarea',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Google Form Link'
                ],
                'attributes' => [
                    'rows' => 2
                ]
            ]);
        }

        public function addStatus() {
            $this->add([
                'name' => 'status',
                'type' => 'select',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Status',
                    'option_values' => [
                        'pending','completed','on-going','cancelled'
                    ]
                ]
            ]);
        }

        public function addPassingScore() {
            $this->add([
                'name' => 'passing_score',
                'type' => 'number',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Passing Score'
                ]
            ]);
        }

        public function addParentId() {
            $this->add([
                'name' => 'parent_id',
                'type' => 'hidden',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Label'
                ]
            ]);
        }

        public function addCreatedBy() {
            $this->add([
                'name' => 'created_by',
                'type' => 'hidden',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Label'
                ],
                'value' => whoIs('id')
            ]);
        }
    }