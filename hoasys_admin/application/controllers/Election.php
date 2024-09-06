<?php

use Mpdf\Tag\P;

defined('BASEPATH') or exit('No direct script access allowed');
require_once(dirname(__FILE__) . "/General.php");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

class Election extends General
{
    protected $title = 'Election';
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        if ($this->session->userdata("id_admin")) {
            $date_time = $this->get_current_date_time();
            $data["date_time"] = $date_time["dateTime"];
            $role = $this->session->userdata("role");
            if ($role == "election") {
                $data['title'] = $this->title;
                $this->load_template_view('templates/election/election', $data);
            } else {
                // redirect(base_url('dashboard'));
                 redirect(base_url('dashboard'));
            }
            
        } else {
            redirect(base_url('Login'));
        }
    }
    public function manage_election()
    {
        if ($this->session->userdata("id_admin")) {
            $date_time = $this->get_current_date_time();
            $data["date_time"] = $date_time["dateTime"];
            $role = $this->session->userdata("role");
            if ($role == "election") {
                $data['title'] = $this->title;
                $this->load_template_view('templates/election/manage_election', $data);
            } else {
                // redirect(base_url('dashboard'));
                 redirect(base_url('dashboard'));
            }
            
        } else {
            redirect(base_url('Login'));
        }
    }
    public function manage_position()
    {
        if ($this->session->userdata("id_admin")) {
            $date_time = $this->get_current_date_time();
            $data["date_time"] = $date_time["dateTime"];
            $role = $this->session->userdata("role");
            if ($role == "election") {
                $data['title'] = $this->title;
                $this->load_template_view('templates/election/manage_position', $data);
            } else {
                // redirect(base_url('dashboard'));
                 redirect(base_url('dashboard'));
            }
            
        } else {
            redirect(base_url('Login'));
        }
    }
    public function monitor_voters()
    {
        if ($this->session->userdata("id_admin")) {
            $date_time = $this->get_current_date_time();
            $data["date_time"] = $date_time["dateTime"];
            $role = $this->session->userdata("role");
            if ($role == "election") {
                $data['title'] = $this->title;
                $this->load_template_view('templates/election/monitor_voters', $data);
            } else {
                // redirect(base_url('dashboard'));
                 redirect(base_url('dashboard'));
            }
            
        } else {
            redirect(base_url('Login'));
        }
    }
    
    public function get_positions()
    {
        $datatable = $this->input->post('datatable');
        $query['search']['append'] = "";
        $query['search']['total'] = "";
        // $status = $datatable['query']['status'];
        $where_name = "";
        $stat_where = "";
        $order = "position_name";
        // if (!empty($status) && trim($status) !== 'All') {
        //     $stat_where = " AND con.status_concern = '".$status."'";
        // }
        $query['query'] = "SELECT election_pos_id,position_name,position_description,position_status,datetime_added FROM tbl_election_position WHERE election_pos_id != 0";
        if ($datatable['query']['searchField'] != '') {
            $keyword = $datatable['query']['searchField'];
            $where = "(position_name LIKE '%" . $keyword . "%' OR position_description LIKE '%" . $keyword . "%')";
            $query['search']['append'] = " AND ($where)";
            $query['search']['total'] = " AND ($where)";
        }
        $page = $datatable['pagination']['page'];
        $pages = $datatable['pagination']['page'] * $datatable['pagination']['perpage'];
        $perpage = $datatable['pagination']['perpage'];
        $sort = (isset($datatable['sort']['sort'])) ? $datatable['sort']['sort'] : '';
        $field = (isset($datatable['sort']['field'])) ? $datatable['sort']['field'] : '';
        if (isset($query['search']['append'])) {
            $query['query'] .= $query['search']['append'];
            $search = $query['query'] . $query['search']['total'];
            $total = count($this->general_model->custom_query($search));
            $pages = ceil($total / $perpage);
            $page = ($page > $pages) ? 1 : $page;
        } else {
            $total = count($this->general_model->custom_query($query['query']));
        }
        if (isset($datatable['pagination'])) {
            $offset = $page * $perpage - $perpage;
            $limit = ' LIMIT ' . $offset . ' ,' . $perpage;
            // $order = $field ? " ORDER BY  " . $field : '';
            $order = $field ? " ORDER BY  " . $order : '';
            if ($perpage < 0) {
                $limit = ' LIMIT 0';
            }
            $query['query'] .= $order . ' ' . $sort . $limit;
        }
        $data = $this->general_model->custom_query($query['query']);
        $meta = [
            "page" => intval($page),
            "pages" => intval($pages),
            "perpage" => intval($perpage),
            "total" => $total,
            "sort" => $sort,
            "field" => $field,
        ];
        echo json_encode(['meta' => $meta, 'data' => $data]);
    }
    public function get_voters()
    {
        $datatable = $this->input->post('datatable');
        $query['search']['append'] = "";
        $query['search']['total'] = "";
        // $status = $datatable['query']['status'];
        $where_name = "";
        $stat_where = "";
        $order = "v.id_voter";
        // if (!empty($status) && trim($status) !== 'All') {
        //     $stat_where = " AND con.status_concern = '".$status."'";
        // }
        $query['query'] = "SELECT v.id_voter,v.datetime_voted,ho.lname,ho.mname,ho.fname,el.election_title FROM tbl_voter v, tbl_homeowner ho,tbl_election el WHERE v.voter_id_ho = ho.id_ho AND el.id_elect = v.id_elect AND v.id_voter != 0";
        if ($datatable['query']['searchField'] != '') {
            $keyword = $datatable['query']['searchField'];
            $where = "(el.election_title LIKE '%" . $keyword . "%' OR ho.fname LIKE '%" . $keyword . "%' OR ho.lname LIKE '%" . $keyword . "%')";
            $query['search']['append'] = " AND ($where)";
            $query['search']['total'] = " AND ($where)";
        }
        $page = $datatable['pagination']['page'];
        $pages = $datatable['pagination']['page'] * $datatable['pagination']['perpage'];
        $perpage = $datatable['pagination']['perpage'];
        $sort = (isset($datatable['sort']['sort'])) ? $datatable['sort']['sort'] : '';
        $field = (isset($datatable['sort']['field'])) ? $datatable['sort']['field'] : '';
        if (isset($query['search']['append'])) {
            $query['query'] .= $query['search']['append'];
            $search = $query['query'] . $query['search']['total'];
            $total = count($this->general_model->custom_query($search));
            $pages = ceil($total / $perpage);
            $page = ($page > $pages) ? 1 : $page;
        } else {
            $total = count($this->general_model->custom_query($query['query']));
        }
        if (isset($datatable['pagination'])) {
            $offset = $page * $perpage - $perpage;
            $limit = ' LIMIT ' . $offset . ' ,' . $perpage;
            // $order = $field ? " ORDER BY  " . $field : '';
            $order = $field ? " ORDER BY  " . $order : '';
            if ($perpage < 0) {
                $limit = ' LIMIT 0';
            }
            $query['query'] .= $order . ' ' . $sort . $limit;
        }
        $data = $this->general_model->custom_query($query['query']);
        $meta = [
            "page" => intval($page),
            "pages" => intval($pages),
            "perpage" => intval($perpage),
            "total" => $total,
            "sort" => $sort,
            "field" => $field,
        ];
        echo json_encode(['meta' => $meta, 'data' => $data]);
    }
    public function save_position()
    {
        $date_time = $this->get_current_date_time();
        $pos["datetime_added"] = $date_time["dateTime"];
        $pos['position_name']  = $this->input->post('title');
        $pos['position_description'] = $this->input->post('desc');
        $pos['position_status'] = "active";
        $pos['added_by'] = $this->session->userdata("id_admin");
        $this->general_model->insert_vals($pos, "tbl_election_position");
    }
    public function change_position_status()
    {
        $id = $this->input->post('id');
        $stat['position_status'] = $this->input->post('stat');
        $this->general_model->update_vals($stat, "election_pos_id = $id", "tbl_election_position");
    }
    public function get_position_details(){
        $id = $this->input->post('id');
        $position_info = $this->general_model->custom_query('SELECT election_pos_id,position_name,position_description,position_status,datetime_added FROM tbl_election_position WHERE election_pos_id = '. $id);
        echo json_encode($position_info);
    }
    public function update_position(){
        $id = $this->input->post('pos_id');
        $pos['position_name']  = $this->input->post('title');
        $pos['position_description'] = $this->input->post('desc');
        $this->general_model->update_vals($pos, "election_pos_id = $id", "tbl_election_position");
    }
    public function fetch_positions_options(){
        $position['opt'] = $this->general_model->custom_query("SELECT election_pos_id id,position_name text FROM tbl_election_position WHERE position_status = 'active' ORDER BY position_name ASC ");    
        $position['created'] = 0;   
        echo json_encode($position);
    }

    // Election codes 
    public function save_election(){
        $date_time = $this->get_current_date_time();
        $positions = $this->input->post('positions');
        $data["datecreated_elect"] = $date_time["dateTime"];
        $data["election_title"] = $this->input->post('election_title');
        $data['election_desc']  = $this->input->post('election_desc');
        $data['election_status'] = "pending";
        $data['created_by'] = $this->session->userdata("id_admin");
        $data['datetimeStart'] =  $this->input->post('start');
        $data['datetimeEnd'] = $this->input->post('end');  
        $election_id = $this->general_model->insert_vals_last_inserted_id($data, "tbl_election");

          // Prepare data for batch insert into tbl_election_positions_added
        $positions_arr = array();
        $loop = 0;

            foreach ($positions as $election_pos_id) {
                $positions_arr[$loop] = array(
                    'election_pos_id' => $election_pos_id,
                    'id_elect' => $election_id,
                    'candidates_winner' => 0, // You may set a default value here
                    'status_elect_pos_add' => 'active', // You may set a default value here
                );
                $loop++;
            }

            // Batch insert into tbl_election_positions_added
        $this->general_model->batch_insert($positions_arr, 'tbl_election_positions_added');
    }
    public function get_election_list()
    {
        $datatable = $this->input->post('datatable');
        $query['search']['append'] = "";
        $query['search']['total'] = "";
        // $status = $datatable['query']['status'];
        $where_name = "";
        $stat_where = "";
        $order = "e.datecreated_elect";
        // if (!empty($status) && trim($status) !== 'All') {
        //     $stat_where = " AND con.status_concern = '".$status."'";
        // }
        $query['query'] = "SELECT e.id_elect,e.election_title,e.election_desc,e.election_status, e.created_by,e.datecreated_elect, a.fname,a.lname,e.datetimeStart,e.datetimeEnd FROM tbl_election e, tbl_admin a WHERE a.id_admin = e.created_by";
        if ($datatable['query']['searchField'] != '') {
            $keyword = $datatable['query']['searchField'];
            $where = "(e.election_title LIKE '%" . $keyword . "%' OR e.election_desc LIKE '%" . $keyword . "%')";
            $query['search']['append'] = " AND ($where)";
            $query['search']['total'] = " AND ($where)";
        }
        $page = $datatable['pagination']['page'];
        $pages = $datatable['pagination']['page'] * $datatable['pagination']['perpage'];
        $perpage = $datatable['pagination']['perpage'];
        $sort = (isset($datatable['sort']['sort'])) ? $datatable['sort']['sort'] : '';
        $field = (isset($datatable['sort']['field'])) ? $datatable['sort']['field'] : '';
        if (isset($query['search']['append'])) {
            $query['query'] .= $query['search']['append'];
            $search = $query['query'] . $query['search']['total'];
            $total = count($this->general_model->custom_query($search));
            $pages = ceil($total / $perpage);
            $page = ($page > $pages) ? 1 : $page;
        } else {
            $total = count($this->general_model->custom_query($query['query']));
        }
        if (isset($datatable['pagination'])) {
            $offset = $page * $perpage - $perpage;
            $limit = ' LIMIT ' . $offset . ' ,' . $perpage;
            // $order = $field ? " ORDER BY  " . $field : '';
            $order = $field ? " ORDER BY  " . $order : '';
            if ($perpage < 0) {
                $limit = ' LIMIT 0';
            }
            $query['query'] .= $order . ' ' . $sort . $limit;
        }
        $data = $this->general_model->custom_query($query['query']);
        $meta = [
            "page" => intval($page),
            "pages" => intval($pages),
            "perpage" => intval($perpage),
            "total" => $total,
            "sort" => $sort,
            "field" => $field,
        ];
        echo json_encode(['meta' => $meta, 'data' => $data]);
    }
    public function get_election_details(){
        $id = $this->input->post('id');
        $election_info = $this->general_model->custom_query('SELECT id_elect,election_title,election_desc,election_status,datetimeStart,datetimeEnd FROM `tbl_election` WHERE id_elect = '. $id);
        echo json_encode($election_info);
    }
    public function update_election(){
        $id = $this->input->post('id');
        $el['election_title']  = $this->input->post('election_title');
        $el['election_desc'] = $this->input->post('election_desc');
        $el['datetimeStart']  = $this->input->post('start');
        $el['datetimeEnd'] = $this->input->post('end');
        $this->general_model->update_vals($el, "id_elect = $id", "tbl_election");
    }
    public function get_election_position_list(){
        $id = $this->input->post('id');
        $election_pos_info = $this->general_model->custom_query('SELECT ep.election_pos_add_id,ep.election_pos_id,ep.id_elect,ep.candidates_winner,ep.status_elect_pos_add, pos.position_name FROM tbl_election_positions_added ep, tbl_election_position pos WHERE ep.election_pos_id = pos.election_pos_id AND ep.id_elect = '. $id);
        echo json_encode($election_pos_info);
    }
    public function get_candidates_list()
    {
        $datatable = $this->input->post('datatable');
        $query['search']['append'] = "";
        $query['search']['total'] = "";
        $pos_can = $datatable['query']['pos_can_add_id'];
        $where_name = "";
        $stat_where = "";
        $order = "ho.fname";
        // if (!empty($status) && trim($status) !== 'All') {
        //     $stat_where = " AND con.status_concern = '".$status."'";
        // }
        $query['query'] = "SELECT can.id_elect_cand,can.candidate_description,can.datecreated_cand,can.status_elect,can.total_score,can.is_elected,can.id_elect,can.election_pos_add_id,can.election_pos_id,can.id_ho, ho.fname, ho.lname FROM tbl_election_candidates can, tbl_homeowner ho WHERE can.id_ho = ho.id_ho AND can.election_pos_add_id = ".$pos_can;
        if ($datatable['query']['searchField'] != '') {
            $keyword = $datatable['query']['searchField'];
            $where = "(ho.fname LIKE '%" . $keyword . "%' OR ho.lname LIKE '%" . $keyword . "%')";
            $query['search']['append'] = " AND ($where)";
            $query['search']['total'] = " AND ($where)";
        }
        $page = $datatable['pagination']['page'];
        $pages = $datatable['pagination']['page'] * $datatable['pagination']['perpage'];
        $perpage = $datatable['pagination']['perpage'];
        $sort = (isset($datatable['sort']['sort'])) ? $datatable['sort']['sort'] : '';
        $field = (isset($datatable['sort']['field'])) ? $datatable['sort']['field'] : '';
        if (isset($query['search']['append'])) {
            $query['query'] .= $query['search']['append'];
            $search = $query['query'] . $query['search']['total'];
            $total = count($this->general_model->custom_query($search));
            $pages = ceil($total / $perpage);
            $page = ($page > $pages) ? 1 : $page;
        } else {
            $total = count($this->general_model->custom_query($query['query']));
        }
        if (isset($datatable['pagination'])) {
            $offset = $page * $perpage - $perpage;
            $limit = ' LIMIT ' . $offset . ' ,' . $perpage;
            // $order = $field ? " ORDER BY  " . $field : '';
            $order = $field ? " ORDER BY  " . $order : '';
            if ($perpage < 0) {
                $limit = ' LIMIT 0';
            }
            $query['query'] .= $order . ' ' . $sort . $limit;
        }
        $data = $this->general_model->custom_query($query['query']);
        $meta = [
            "page" => intval($page),
            "pages" => intval($pages),
            "perpage" => intval($perpage),
            "total" => $total,
            "sort" => $sort,
            "field" => $field,
        ];
        echo json_encode(['meta' => $meta, 'data' => $data]);
    }
    public function election_candidate_select(){
        $election = $this->input->post('election');
        $where_elect = "";
        if (isset($election) && trim($election) != "") {
            $where_elect = "AND (fname LIKE '%$election%' OR lname LIKE '%$election%')";
        }
        $election = $this->general_model->custom_query("SELECT id_ho as id,CONCAT(lname,', ',fname,' ',mname) as text FROM `tbl_homeowner` WHERE status = 'active' AND can_run = 1 AND good_payer = 1 $where_elect ORDER BY lname ASC ");
        $data["results"] = $election;
        echo json_encode($data);
    }
    public function positions_options_specific(){
        $election = $this->input->post('election');
        $where_elect = "";
        if (isset($election) && trim($election) != "") {
            $where_elect = "AND position_name LIKE '%$election%' ";
        }
        $election = $this->general_model->custom_query("SELECT election_pos_id id,position_name text FROM tbl_election_position WHERE position_status = 'active'  $where_elect ORDER BY position_name ASC ");
        $data["results"] = $election;
        echo json_encode($data);
    }
    public function save_candidate(){
        $candidate_id = $this->input->post('candidate');
        $description = $this->input->post('desc');
        $pos_added_id = $this->input->post('pos_added_id');
        $elect_id = $this->input->post('elect_id');
        $pos_id = $this->input->post('pos_id');
        $date_time = $this->get_current_date_time();

        // check if this is already added   
        $candidate = $this->general_model->custom_query("SELECT id_elect_cand FROM tbl_election_candidates WHERE id_elect = ".$elect_id." AND id_ho = ". $candidate_id." AND election_pos_id = ".$pos_id." AND election_pos_add_id = ". $pos_added_id." ");
         // check if this is added to other positions 
        $candidate_from_other_pos = $this->general_model->custom_query("SELECT id_elect_cand FROM `tbl_election_candidates` WHERE id_elect = ".$elect_id." AND id_ho = ". $candidate_id."");
        
        if(count($candidate) > 0){
            $return['success'] = 0;
        }else if(count($candidate_from_other_pos) > 0){
            $return['success'] = 2;
        }else{
            // can add candidate
            $return['success'] = 1;
            $can['candidate_description'] = $description;
            $can['status_elect'] = "active";
            $can['id_elect'] = $elect_id;
            $can['id_ho'] = $candidate_id;
            $can['election_pos_add_id'] = $pos_added_id;
            $can['election_pos_id'] =  $pos_id;
            $can["datecreated_cand"] = $date_time["dateTime"];
            $this->general_model->insert_vals($can, "tbl_election_candidates");
        }
        echo json_encode($return);
    }
    public function save_number_of_winners(){
        $id = $this->input->post('pos_added_id');
        $el['candidates_winner'] = $this->input->post('winner');
        $this->general_model->update_vals($el, "election_pos_add_id = $id", "tbl_election_positions_added");
    }
    public function save_additional_position(){
        $election_id = $this->input->post('election_id');
        $position_id = $this->input->post('pos_id');
        // check if already added 
        $res = $this->general_model->custom_query("SELECT election_pos_add_id FROM `tbl_election_positions_added` WHERE election_pos_id = ".$position_id." AND id_elect = ".$election_id." ");
        if(count($res) > 0){
            $success = 0;
        }else{
            $success = 1;
            $el['election_pos_id'] = $position_id;
            $el['id_elect'] = $election_id;
            $el['candidates_winner'] = 0;
            $el['status_elect_pos_add'] = "active";
            $this->general_model->insert_vals($el, "tbl_election_positions_added");
        }
        echo json_encode($success);
    }
    public function delete_position(){
        $election_id = $this->input->post('election_id');
        $pos_added_id = $this->input->post('pos_added_id');
        // check how many positions are there in this election
        $res = $this->general_model->custom_query("SELECT election_pos_add_id FROM `tbl_election_positions_added` WHERE id_elect = ".$election_id);
        if(count($res) == 1){
            // Cannot delete since its 1 only
            $success = 0;
        }else if(count($res) > 1){
            $success = 1;
            // delete candidates first
            $candidates = $this->general_model->custom_query(" SELECT * FROM `tbl_election_candidates` WHERE election_pos_add_id = ".$pos_added_id);
           
            if(count($candidates) > 0){
                $can_arr = array_column($candidates, 'id_elect_cand');
				$can_string = implode(',', $can_arr);
				// delete candidates
				$where_can = 'id_elect_cand IN ('.$can_string.')';
        		$this->general_model->delete_vals($where_can, 'tbl_election_candidates');
            }
            // deletion of position
            $where_del = 'election_pos_add_id = '.$pos_added_id;
            $this->general_model->delete_vals($where_del, 'tbl_election_positions_added');
        }
        echo json_encode($success);
    }
    public function delete_candidate(){
        $can_id = $this->input->post('candidate_id');
        $where_del = 'id_elect_cand = '.$can_id;
        $this->general_model->delete_vals($where_del, 'tbl_election_candidates');
    }
    public function update_election_statuses(){
        $id = $this->input->post('election_id');
        $stat_data = $this->input->post('stat');
        $fullname = $this->session->userdata("fullname");

        if($stat_data == "pending"){
            // delete all votes 
           $this->delete_all_votes_records($id);
        }
        $stat['election_status'] = $stat_data;
        $this->general_model->update_vals($stat, "id_elect = $id", "tbl_election");
        //send notif via email 
        $email_info = $this->general_model->custom_query('SELECT email_add, CONCAT(fname," ", lname) as fullname FROM `tbl_homeowner` WHERE status = "active"');
        $election_details = $this->general_model->custom_query('SELECT election_title FROM tbl_election WHERE id_elect = '.$id);
        if (count($email_info) > 0) {
            $this->send_email_to_active_homeowners($email_info, $election_details[0]->election_title, $stat_data);
        }
        // activity logs 
        $message = $fullname." successfully changed status of the Election titled ' ".$election_details[0]->election_title." ' to ".  $stat_data.".";
        $this->activity_log('election', $id, $message);
    }
    public function delete_all_votes_records($id){
        $where_del1 = 'id_elect ='.$id;
        $this->general_model->delete_vals($where_del1, 'tbl_voter');

        $where_del2 = 'id_elect ='.$id;
        $this->general_model->delete_vals($where_del2, 'tbl_votes');

        $stat['total_score'] = 0;
        $this->general_model->update_vals($stat, "id_elect = $id", "tbl_election_candidates");
    }
    public function fetch_elect_cand(){
    // check if allowed to vote
    $result = [];
    $id = $this->input->post('id');
    
      // Execute the count query
    $count_voters = $this->general_model->custom_query('SELECT COUNT(id_voter) AS total_voters FROM `tbl_voter` WHERE id_elect = ' . $id);
    $total_voters = isset($count_voters[0]->total_voters) ? $count_voters[0]->total_voters : 0;
    
        $data = $this->general_model->custom_query('SELECT e.id_elect, e.election_title, e.election_status,
            epa.election_pos_add_id, epa.election_pos_id, epa.candidates_winner,
            ep.position_name, ep.position_description,
            ec.id_elect_cand, ec.candidate_description, ec.total_score, ec.is_elected,
            ho.id_ho, ho.lname, ho.fname, ho.mname,
            (SELECT COALESCE(SUM(v.num_votes), 0)
             FROM tbl_votes v
             WHERE v.id_elect = e.id_elect AND v.election_pos_add_id = epa.election_pos_add_id) AS total_votes
     FROM tbl_election e
     LEFT JOIN tbl_election_positions_added epa ON e.id_elect = epa.id_elect
     LEFT JOIN tbl_election_position ep ON epa.election_pos_id = ep.election_pos_id
     LEFT JOIN tbl_election_candidates ec ON epa.election_pos_add_id = ec.election_pos_add_id
     LEFT JOIN tbl_homeowner ho ON ec.id_ho = ho.id_ho
     WHERE e.id_elect = '.$id);
        
        foreach ($data as $item) {
            $positionName = $item->position_name;
            $winnerNum = isset($item->candidates_winner) ? $item->candidates_winner : 0; // Set a default value if candidates_winner is not available
            
            if (!isset($result[$positionName])) {
                $result[$positionName] = [];
            }
    
            $candidateData = [
                "fname" => $item->fname,
                "mname" => $item->mname,
                "lname" => $item->lname,
                "desc_candidate" => $item->candidate_description,
                "total_votes" => $item->total_votes,
                "total_score" => $item->total_score,
                "winner" => $winnerNum, // Use the initialized value
                "candidate_id" =>$item->id_elect_cand,
                "position_election_added_id" => $item->election_pos_add_id,
                "election_pos_id" => $item->election_pos_id
            ];
    
            $result[$positionName][] = $candidateData;
        }
    
         // Add total voters count to the result
        $result['total_voters'] = $total_voters;
    
        echo json_encode($result);
    }
    public function get_election_ongoing_published_details_list(){
        $stat = $this->input->post('type');
        $election_pos_info = $this->general_model->custom_query('SELECT * FROM `tbl_election` WHERE election_status = "'.$stat.'"');
        echo json_encode($election_pos_info);
    }
    
    public function save_balot() {
        $checkedValues = $this->input->post('checkedValues_global');
        $positionCheckedCounts = $this->input->post('positionCheckedCounts_global');
        $electionId = $this->input->post('election_id');
        $voterId = $this->session->userdata("id_admin"); // replace with id_ho when transferred

        // Update total_score in tbl_election_candidates
        $this->general_model->updateTotalScore($checkedValues);

        // Save the ballot in tbl_votes
        $this->general_model->saveBallot($voterId, $electionId, $positionCheckedCounts);

        $this->save_voter($electionId);

        // Return a success response
        echo json_encode(['success' => true]);
    }
    public function save_voter($electionId){
        $date_time = $this->get_current_date_time();
        $vote["datetime_voted"] = $date_time["dateTime"];
        $vote['voter_id_ho'] = $this->session->userdata("id_admin");
        $vote['id_elect'] = $electionId;
        $this->general_model->insert_vals($vote, "tbl_voter");
    }
    public function check_if_allowed_voter(){
        $id = $this->input->post('id'); 
        $ho_id = $this->session->userdata("id_admin"); // replace with id_ho when transferred
        $voterId = $this->general_model->custom_query('SELECT * FROM `tbl_voter` WHERE voter_id_ho ='.$ho_id.' AND id_elect = '.$id.'');
        if(count($voterId) > 0){
            // Done voting 
            $result = 0;
        }else{
            // Can vote
            $result = 1;
        }
        echo json_encode($result);
    }
     // Codes for Emailing
     public function send_email_to_active_homeowners($records, $title, $stat)
     {
         $email_data = array(); // Array to store email information
         foreach ($records as $info) {
             $subject = "ELECTION ANNOUNCEMENT |" . $title;
             $fullname = $info->fullname;
 
             if($stat == "done"){
                $email_content = "Dear, $fullname,<br><br>
                We are pleased to inform you that the Election - '$title' is now CLOSED. Which means, you can no longer submit your votes.<br><br>
                If you have any concerns regarding this election, kindly send your concern to concern page and the Hoasys officers will answer your inquiries as soon as possible. Thank you! <br><br>
                
                Best regards,<br>
                Hoasys Admin";
    
             } else if($stat == "ongoing"){
                $email_content = "Dear, $fullname,<br><br>
                We are pleased to inform you that the Election - '$title' is now OPEN. Which means, you can now start/resume casting your votes.<br><br>
                If you have any concerns regarding this election, kindly send your concern to concern page and the Hoasys officers will answer your inquiries as soon as possible. Thank you! <br><br>
                
                Best regards,<br>
                Hoasys Admin";
    
             } else if($stat == "pending"){
                $email_content = "Dear, $fullname,<br><br>
                We are pleased to inform you that the Election - '$title' has been RESET. Which means, all the recorded votes of this election was removed by your Election Committee. We will inform you once this election will re-open again for voting.<br><br>
                If you have any concerns regarding this election, kindly send your concern to concern page and the Hoasys officers will answer your inquiries as soon as possible. Thank you! <br><br>
                
                Best regards,<br>
                Hoasys Admin";
             } else if($stat == "active"){
                $email_content = "Dear, $fullname,<br><br>
                We are pleased to inform you that the Election - '$title' has been PUBLISHED. Which means,the results are displayed in the Election Dashboard.<br><br>
                If you have any concerns regarding this election, kindly send your concern to concern page and the Hoasys officers will answer your inquiries as soon as possible. Thank you! <br><br>
                
                Best regards,<br>
                Hoasys Admin";
             } else if($stat == "inactive"){
                $email_content = "Dear, $fullname,<br><br>
                We are pleased to inform you that the Election - '$title' has been UNPUBLISHED or DEACTIVATED. Which means,the election will not be displayed on your Election Dashboard.<br><br>
                If you have any concerns regarding this election, kindly send your concern to concern page and the Hoasys officers will answer your inquiries as soon as possible. Thank you! <br><br>
                
                Best regards,<br>
                Hoasys Admin";
             }
           
             // Store email information in the array
             $email_data[] = array(
                 'to' => $info->email_add,
                 'subject' => $subject,
                 'message' => $email_content,
             );
         }
 
         // Send batch emails
         $this->send_any_batch_emails($email_data);
     }
     public function save_sample(){
        $pos['category'] = "Category testing";
        $pos['value'] = 5;
        $this->general_model->insert_vals($pos, "chart_data");
     }
    public function send_automatic_email_ongoing_pending($stat, $title){
        $email_data = array();
        $subject = "ELECTION ANNOUNCEMENT |" . $title;
        if($stat == "done"){
            $email_content = "Dear Homeowners,<br><br>
            We are pleased to inform you that the Election - '$title' is now CLOSED. Which means, you can no longer submit your votes.<br><br>
            If you have any concerns regarding this election, kindly send your concern to concern page and the Hoasys officers will answer your inquiries as soon as possible. Thank you! <br><br>
            
            Best regards,<br>
            Hoasys Admin";

         } else if($stat == "ongoing"){
            $email_content = "Dear Homeowners,<br><br>
            We are pleased to inform you that the Election - '$title' is now OPEN. Which means, you can now start/resume casting your votes.<br><br>
            If you have any concerns regarding this election, kindly send your concern to concern page and the Hoasys officers will answer your inquiries as soon as possible. Thank you! <br><br>
            
            Best regards,<br>
            Hoasys Admin";
         } 
          // Store email information in the array
          $employee_records = $this->general_model->custom_query('SELECT email_add FROM `tbl_homeowner` WHERE status = "active"');
          foreach ( $employee_records as $emp) {
            $email_data[] = array(
                'to' => $emp->email_add,
                'subject' => $subject,
                'message' => $email_content,
              );
          }
        // Send batch emails
        $this->send_any_batch_emails($email_data);
    }
     public function auto_start_end_election() {
        // Fetching current date and time
        $date_time = $this->get_current_date_time();
        $current_datetime =  $date_time["dateTime"];
        // var_dump($current_datetime);
        // Extract current date and time
        $current_date = $date_time["date"];
        $current_time = $date_time["time"];
    
          // Calculate the start and end time range based on the current time
        $time_parts = explode(':', $current_time);
        $hours = $time_parts[0];
        $minutes = $time_parts[1];
        $seconds = $time_parts[2];

        // Round down to the nearest 30-minute interval
        $rounded_minutes = floor(($minutes * 60 + $seconds) / (30 * 60)) * 30;
        $start_time_range = date('H:i', strtotime("$current_date $hours:$minutes:$seconds") - $minutes * 60 - $seconds + $rounded_minutes * 60);
        $end_time_range = date('H:i', strtotime($start_time_range) + 29 * 60);

        // Fetch ongoing elections for today within the specified time range
        $ongoing_elections = $this->general_model->custom_query("SELECT id_elect, election_title, datetimeEnd FROM `tbl_election` WHERE election_status = 'ongoing' AND DATE(datetimeEnd) = '$current_date' AND TIME(datetimeEnd) BETWEEN '$start_time_range' AND '$end_time_range'");
        // Fetch pending elections for today within the specified time range
        $pending_elections = $this->general_model->custom_query("SELECT id_elect, election_title, datetimeStart FROM `tbl_election` WHERE election_status = 'pending' AND DATE(datetimeStart) = '$current_date' AND TIME(datetimeStart) BETWEEN '$start_time_range' AND '$end_time_range'");
            // Update ongoing elections status if datetimeEnd has passed
            foreach ($ongoing_elections as $election) {
                    $update_data = array('election_status' => 'done');
                    $this->general_model->update_vals($update_data, "id_elect = $election->id_elect", "tbl_election");
                    $this->send_automatic_email_ongoing_pending('done', $election->election_title);
                }
            // Update pending elections status if datetimeStart has passed
            foreach ($pending_elections as $election) {
                    $update_data = array('election_status' => 'ongoing');
                    $this->general_model->update_vals($update_data, "id_elect = $election->id_elect", "tbl_election");
                    $this->send_automatic_email_ongoing_pending('ongoing', $election->election_title);
                }
    }
}