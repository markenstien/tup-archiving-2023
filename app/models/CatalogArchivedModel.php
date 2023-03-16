<?php 

	class CatalogArchivedModel extends Model
	{
		public $table = 'catalog_archived';
		public $_fillables = [
			'user_id',
			'catalog_id',
			'catalog_values',
			'log_message'
		];

		//create only
		public function createOrUpdate($data, $id = null) {
			$dataToUpdate = parent::getFillablesOnly($data);
			return parent::store([
				'catalog_values' => json_encode($data['catalog_values']),
				'log_message' => whoIs(['firstname', 'lastname']) . " deleted {$data['catalog_values']->title} on ".nowMilitary(),
				'user_id' => whoIs('id'),
				'catalog_id' => seal($data['catalog_values']->id)
			]);
		}

		public function getAll($params = []) {
			$where = null;
			$order = null;
			$limit = null;

			if(isset($params['where'])) {
				$where = " WHERE ".parent::conditionConvert($params['where']);
			}

			if(isset($params['order'])) {
				$order = " ORDER BY {$params['order']}";
			}

			if(isset($limit)) {
				$limit = " LIMIT {$params['limit']}";
			}

			$this->db->query(
				"SELECT catalog_log.*, 
					concat(firstname, ' ',lastname)
					as fullname FROM {$this->table} as catalog_log 
					LEFT JOIN users as user 
					ON catalog_log.user_id = user.id 
					{$where} {$order} {$limit}"
			);

			return $this->db->resultSet();
		}
	}