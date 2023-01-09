<?php 

    class ItemCommentModel extends Model
    {
        public $table = 'item_comments';

        public $_fillables = [
            'item_id',
            'comment',
            'parent_id',
            'user_id',
            'created_at'
        ];

        public function create($data) {
            $data['user_id'] = whoIs('id'); 
            $data['created_at'] = nowMilitary();

            $_fillables = parent::getFillablesOnly($data);
            return parent::store($_fillables);
        }

        public function getComments($id) {
            return $this->getAll([
                'where' => [
                    'item_id' => $id
                ]
            ]);
        }

        public function getAll($params = []) {
            $where = null;
            $order = null;
            $limit = null;

            if(isset($params['where'])) {
                $where = " WHERE " . parent::conditionConvert($params['where']);
            }

            if(isset($params['order'])) {
                $order = " ORDER {$params['order']}";
            }

            if(isset($params['limit'])) {
                $limit = " LIMIT {$params['limit']}";
            }
            $this->db->query(
                "SELECT com.id as comment_id,com.parent_id,
                    com.comment as comment, 
                    com.user_id as commentor_id,
                    com.created_at as posted_date,
                    concat(user.firstname , ' ' ,user.lastname) as commentor_name,
                    comment_like.total_count as total_like
                    
                    FROM {$this->table} as com

                    LEFT JOIN users as user 
                    ON user.id = com.user_id

                    LEFT JOIN (
                        SELECT * FROM v_comment_meta
                    ) as comment_like

                    ON comment_like.parent_id = com.id

                    {$where}{$order}{$limit}
                "
            );

            return $this->db->resultSet();
        }
    }