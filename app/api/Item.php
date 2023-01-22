<?php 

    class Item extends Controller
    {

        public function __construct()
        {
            $this->itemModel = model('ItemModel');
        }

        public function index() {
            dump(
                $this->itemModel->getAll([
                    'order' =>  'item.id desc'
                ])
            );
        }
    }