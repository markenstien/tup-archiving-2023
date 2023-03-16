<?php 

	class CatalogLogsController extends Controller
	{
		public function __construct() 
		{
			parent::__construct();
			$this->catalogLogModel = model('CatalogLogModel');
			$this->catalogArchivedModel = model('CatalogArchivedModel');
		}

		public function index() {
			$this->data['logs'] =  $this->catalogLogModel->getAll([
				'order' => 'id desc'
			]);
			return $this->view('catalog_log/index', $this->data);
		}


		public function archived() {
			$this->data['logs'] = $this->catalogArchivedModel->getAll([
				'order' => 'id desc'
			]);
			return $this->view('catalog_log/archived', $this->data);
		}

		public function show($id) {

			$catalog = $this->catalogArchivedModel->get($id);

			if(!$catalog) {
				Flash::set("Catalog has been removed");
				return redirect(_route('catalog-log:index'));
			}

			$categoryModel = model('CategoryModel');
			$userModel = model('UserModel');

			$catalogValue = $catalog->catalog_values;
			$catalogValue = json_decode($catalogValue);

			$catalogCategory = $categoryModel->get($catalogValue->category_id);
			$this->data['catalog'] = $catalogValue;
			$this->data['category'] = $catalogCategory;
			$this->data['id'] = $id;


			$catalogValue->wallpaper = $this->_attachmentModel->single([
                'global_key' => 'CATALOG_WALLPAPER',
                'global_id'  => $catalogValue->id
            ]);

            $catalogValue->document = $this->_attachmentModel->single([
                'global_key' => 'CATALOG_PDF_FILE',
                'global_id'  => $catalogValue->id
            ]);

            $catalogValue->uploader = $userModel->get($catalogValue->uploader_id);

			return $this->view('catalog_log/show', $this->data);
		}

		public function rollBack($id) {
			$catalog = $this->catalogArchivedModel->get($id);
			$catalogValue = $catalog->catalog_values;
			$catalogValue = json_decode($catalogValue);

			$this->itemModel = model('ItemModel');


			if($this->itemModel->get($catalogValue->id)) {
				Flash::set("This catalog already exists.");
				return request()->return();
			} else {

				$catalogValue = (array) $catalogValue;
				$this->itemModel->store($catalogValue);
				Flash::set("Archived Catalog Retrieved");
				return redirect(_route('item:show', $catalogValue['id']));
			}
		}
	}