<?php 

    class KeywordModel extends Model
    {
        public $table = 'keywords';
        public $_fillables = [
            'category',
            'value',
            'created_at'
        ];

        public function getTrends() {
            $this->db->query(
                "SELECT * FROM v_keyword_rank order by total desc LIMIT 8"
            );

            return $this->db->resultSet();
        }
    }