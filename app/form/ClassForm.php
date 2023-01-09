<?php
    namespace Form;
    use Core\Form;
use Services\UserService;

    load(['Form'], CORE);

    class ClassForm extends Form
    {

        public function __construct()
        {
            parent::__construct('clas-form');
            $this->user = model('UserModel');
            $this->addClassName();
            $this->addClassCode();
            $this->addTeacher();
            $this->addDescription();
        }

        public function addClassName() {
            $this->add([
                'name' => 'class_name',
                'type' => 'text',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Class Name'
                ],
                'required' => true
            ]);
        }

        public function addClassCode() {
            $this->add([
                'name' => 'class_code',
                'type' => 'text',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Class Code'
                ],
                'required' => true
            ]);
        }

        public function addJoinCode() {
            $this->add([
                'name' => 'join_code',
                'type' => 'text',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Class Code'
                ],
                'required' => true
            ]);
        }

        public function addDescription() {
            $this->add([
                'name' => 'description',
                'type' => 'textarea',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Description'
                ],
                'attributes' => [
                    'rows' => 3
                ]
            ]);
        }

        public function addTeacher() {
            $teachers = $this->user->getAll([
                'where' => [
                    'user_type' => UserService::TEACHER
                ]
            ]);

            $teachers = arr_layout_keypair($teachers, ['id', 'firstname@lastname']);
            
            $this->add([
                'name' => 'teacher_id',
                'type' => 'select',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Teachers',
                    'option_values' => $teachers
                ]
            ]);
        }
    }