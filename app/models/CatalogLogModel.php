<?php 

	class CatalogLogModel extends Model
	{
		public $table = 'catalog_logs';
		public $_fillables = [
			'log_message',
			'user_id',
			'catalog_id'
		];

		public function createOrUpdate($data, $id = null) {
			$dataToUpdate = parent::getFillablesOnly($data);
			if(is_null($id)) {
				return parent::store($dataToUpdate);
			} else {
				return parent::update($dataToUpdate, $id);
			}
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