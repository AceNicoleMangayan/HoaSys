<?php

class General_model extends CI_Model
{

    // SELECT -----------------------------------------------------------------------------------------

    public function fetch_specific_val($fields, $where, $tables, $order = null)
    { //get 1  record
        $this->db->select($fields);
        $this->db->where($where);
        if ($order !== null) {
            $this->db->order_by($order);
        }
        $query = $this->db->get($tables);
        return $query->row();
    }

    public function fetch_specific_vals($fields, $where, $tables, $order = null)
    { //get more than 1 records
        $this->db->select($fields);
        $this->db->where($where);
        if ($order !== null) {
            $this->db->order_by($order);
        }
        $query = $this->db->get($tables);
        return $query->result();
    }
    public function fetch_vals($qry)
    {
        $this->db->select($qry['field']);
        $this->db->from($qry['table']);
        if (isset($qry['join']['inner'])) {
            foreach ($qry['join']['inner'] as $key => $value) {
                $this->db->join($key, $value, 'inner');
            }
        }
        if (isset($qry['join']['left'])) {
            foreach ($qry['join']['left'] as $key => $value) {
                $this->db->join($key, $value, 'left');
            }
        }
        if (isset($qry['join']['right'])) {
            foreach ($qry['join']['right'] as $key => $value) {
                $this->db->join($key, $value, 'right');
            }
        }
        if (isset($qry['join']['outer'])) {
            foreach ($qry['join']['outer'] as $key => $value) {
                $this->db->join($key, $value, 'outer');
            }
        }
        //WHERE
        if (isset($qry['where']['and'])) {
            $this->db->group_start();
            foreach ($qry['where']['and'] as $key => $value) {
                $this->db->where($key, $value);
            }
            $this->db->group_end();
        }
        if (isset($qry['where']['or'])) {
            $this->db->group_start();
            foreach ($qry['where']['or'] as $key => $value) {
                $this->db->or_where($key, $value);
            }
            $this->db->group_end();
        }
        //IN
        if (isset($qry['in']['and'])) {
            $this->db->group_start();
            foreach ($qry['in']['and'] as $key => $value) {
                $this->db->where_in($key, $value);
            }
            $this->db->group_end();
        }
        if (isset($qry['in']['or'])) {
            $this->db->or_group_start();
            foreach ($qry['in']['or'] as $key => $value) {
                $this->db->or_where_in($key, $value);
            }
            $this->db->group_end();
        }
        if (isset($qry['in']['not'])) {
            $this->db->group_start();
            foreach ($qry['in']['not'] as $key => $value) {
                $this->db->where_not_in($key, $value);
            }
            $this->db->group_end();
        }
        if (isset($qry['in']['or_not'])) {
            $this->db->group_start();
            foreach ($qry['in']['or_not'] as $key => $value) {
                $this->db->or_where_not_in($key, $value);
            }
            $this->db->group_end();
        }
        //LIKE
        if (isset($qry['like']['and'])) {
            $this->db->group_start();
            foreach ($qry['like']['and'] as $key => $value) {
                $this->db->like($key, $value);
            }
            $this->db->group_end();
        }
        if (isset($qry['like']['or'])) {
            $this->db->group_start();
            foreach ($qry['like']['or'] as $key => $value) {
                $this->db->or_like($key, $value);
            }
            $this->db->group_end();
        }
        if (isset($qry['like']['not'])) {
            $this->db->group_start();
            foreach ($qry['like']['not'] as $key => $value) {
                $this->db->not_like($key, $value);
            }
            $this->db->group_end();
        }
        if (isset($qry['like']['or_not'])) {
            $this->db->group_start();
            foreach ($qry['like']['or_not'] as $key => $value) {
                $this->db->or_not_like($key, $value);
            }
            $this->db->group_end();
        }
        //GROUP BY
        if (isset($qry['group'])) {
            $this->db->group_by($qry['group']);
        }
        //HAVING
        if (isset($qry['having']['and'])) {
            $this->db->having($qry['having']['and']);
        }
        if (isset($qry['having']['or'])) {
            $this->db->or_having($qry['having']['or']);
        }
        //ORDER BY
        if (isset($qry['order'])) {
            $this->db->order_by($qry['order']);
        }
        //LIMIT
        if (isset($qry['limit']) && isset($qry['offset'])) {
            $this->db->limit($qry['limit'], $qry['offset']);
        } elseif (isset($qry['limit'])) {
            $this->db->limit($qry['limit']);
        }
        $this->db->trans_start();
        $query = $this->db->get();
        $this->db->trans_complete();
        return $query->result();
    }
    public function fetch_all($fields, $tables, $order = null)
    { //get all records
        $this->db->select($fields);
        if ($order !== null) {
            $this->db->order_by($order);
        }
        $query = $this->db->get($tables);
        return $query->result();
    }

    // INSERT -----------------------------------------------------------------------------------------

    public function insert_vals($data, $table) // simple insert
    {
        $this->db->trans_start();
        $this->db->insert($table, $data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return 0;
        } else {
            return 1;
        }
    }

    public function batch_insert($data, $table)
    {
        $this->db->trans_start();
        $this->db->insert_batch($table, $data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return 0;
        } else {
            return 1;
        }
    }

    public function insert_array_vals($data, $table) // Optional
    {
        $this->db->trans_start();
        for ($loop = 0; $loop < count($data); $loop++) {
            $this->db->insert($table, $data[$loop]);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return 0;
        } else {
            return 1;
        }
    }

    public function insert_vals_last_inserted_id($data, $table) //Simple Insert and return last inserted ID
    {
        $this->db->trans_start();
        $this->db->insert($table, $data);
        $lastInsertId = $this->db->insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return 0;
        } else {
            return $lastInsertId;
        }
    }

    public function batch_insert_first_inserted_id($data, $table)
    { // Batch Insert and return first inserted ID
        $this->db->trans_start();
        $this->db->insert_batch($table, $data);
        $firstInsertedId = $this->db->insert_id();
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return 0;
        } else {
            return $firstInsertedId;
        }
    }

    // UPDATE -----------------------------------------------------------------------------------------

    public function update_vals($data, $where, $table)
    {
        $this->db->trans_start();
        $this->db->where($where);
        $this->db->update($table, $data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return 0;
        } else {
            return 1;
        }
    }

    public function update_array_vals($array, $table)
    { // Optional
        $this->db->trans_start();
        for ($loop = 0; $loop < count($array); $loop++) {
            $this->db->set($array[$loop]['data']);
            $this->db->where($array[$loop]['where']);
            $this->db->update($table);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return 0;
        } else {
            return 1;
        }
    }

    public function batch_update($data, $field, $table)
    {
        $this->db->trans_start();
        $this->db->update_batch($table, $data, $field);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return 0;
        } else {
            return 1;
        }
    }

    // DELETE -----------------------------------------------------------------------------------------

    public function delete_vals($where, $table)
    {
        $this->db->trans_start();
        $this->db->where($where);
        $this->db->delete($table);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return 0;
        } else {
            return 1;
        }
    }

    // CUSTOM QUERY -----------------------------------------------------------------------------------------

    public function custom_query($qry)
    { //custom query
        $query = $this->db->query($qry);
        return $query->result();
    }

    public function custom_query_row($qry)
    { //custom query
        $query = $this->db->query($qry);
        return $query->row();
    }

    public function custom_query_no_return($qry)
    { //custom query
        $this->db->trans_start();
        $this->db->query($qry);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return 0;
        } else {
            return 1;
        }
    }

    public function custom_query_no_return_array($array)
    { //arrays of queries
        $this->db->trans_start();
        for ($loop = 0; $loop < count($array); $loop++) {
            $this->db->query($array[$loop]);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return 0;
        } else {
            return 1;
        }
    }

    public function join_select($query)
    {
        $this->db->select($query['fields']);
        $this->db->from($query['table']);

        foreach ($query['join'] as $key => $val) {
            $this->db->join($key, $val);
        }

        if (isset($query['where'])) {
            $this->db->where($query['where']);
        }

        $data = $this->db->get();

        return $data->result();
    }
		// sample  chart
        public function getChartData($year,$id) {
			// Your MySQL query to fetch data
		$query = $this->db->query("SELECT status_record AS category, COUNT(id_record) AS value FROM tbl_records WHERE status_record IN ('paid', 'pending') AND year_record = '".$year."' AND id_ho = ".$id." GROUP BY status_record");
		return $query->result_array();
	}
	    //get forum
        public function get_forum_data($limit, $offset, $where_forum = "")
        {
            $this->db->select('id_forum, title_forum, desc_forum, datetime_post, published_by, publisher_role, id_admin, id_ho');
            $this->db->from('tbl_forum');
    
            // Add the condition for visibility
            $this->db->where('visibility', 'all');
    
            if (!empty($where_forum)) {
                $this->db->where($where_forum);
            }
    
            $this->db->order_by('datetime_post', 'desc');
            $this->db->limit($limit, $offset);
    
            $query = $this->db->get();
            return $query->result();
        }
    
    public function get_total_forums() {
        return $this->db->count_all_results('tbl_forum');
    }
	public function get_forum_replies($forum_id, $limit, $offset) {
		$this->db->select('id_forum_rep, forum_rep, reference, id_forum, id_admin, id_ho, commented_by, datetime_rep');
		$this->db->from('tbl_forum_replies');
		$this->db->where('id_forum', $forum_id);
		$this->db->where('reference', 0); // Add this line to filter by reference = 0
		$this->db->order_by('datetime_rep','desc'); // Remove 'desc' to get the latest records
		$this->db->limit($limit, $offset);
	
		$query = $this->db->get();
		return $query->result();
	}
	public function get_forum_sub_comments($forum_rep_id) {
        $this->db->select('id_forum_rep, forum_rep, reference, id_forum, id_admin, id_ho, commented_by, datetime_rep');
        $this->db->from('tbl_forum_replies');
        $this->db->where('reference', $forum_rep_id);
        $this->db->order_by('datetime_rep', 'desc');
        // $this->db->limit($limit, $offset);

        $query = $this->db->get();
        return $query->result();
    }
    // Voting 
    public function updateTotalScore($candidateIds) {
        foreach ($candidateIds as $candidateId) {
            $this->db->set('total_score', 'total_score + 1', FALSE);
            $this->db->where('id_elect_cand', $candidateId);
            $this->db->update('tbl_election_candidates');
        }
    }

    public function saveBallot($voterId, $electionId, $positionCheckedCounts) {
        foreach ($positionCheckedCounts as $position => $data) {
            $dataToInsert = array(
                'id_ho_voter' => $voterId,
                'id_elect' => $electionId,
                'election_pos_add_id' => $data['position_election_added_id'],
                'election_pos_id' => $data['election_pos_id'],
                'num_votes' => $data['count']
            );
            $this->db->insert('tbl_votes', $dataToInsert);
        }
    }
}