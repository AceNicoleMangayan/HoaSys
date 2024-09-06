<?php

use Mpdf\Tag\P;

defined('BASEPATH') or exit('No direct script access allowed');
require_once(dirname(__FILE__) . "/General.php");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

class Forum extends General
{
    protected $title = 'Forum';
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        if ($this->session->userdata("id_admin")) {
            $date_time = $this->get_current_date_time();
            $data["date_time"] = $date_time["dateTime"];
			$data["session_id"] = $this->session->userdata("id_admin");
            $role = $this->session->userdata("role");
            $data['title'] = $this->title;
            $this->load_template_view('templates/forum/forum', $data);
        } else {
            redirect(base_url('Login'));
        }
    }
	public function load_forum_data() {
        $limit = $this->input->post('limit');
        $offset = $this->input->post('offset');
		$search = $this->input->post('search');
		$where_forum = "";
		if (!empty($search)) {
			$where_forum = "(title_forum LIKE '%" . $search . "%' OR desc_forum LIKE '%" . $search . "%')";
        }

		$forum_data = $this->general_model->get_forum_data($limit, $offset, $where_forum);
        $total_forums = $this->general_model->get_total_forums();

        $data['forum_data'] = $forum_data;
        $data['total_forums'] = $total_forums;

        echo json_encode($data);
    }
	public function load_forum_replies() {
        $forum_id = $this->input->post('forum_id');
        $limit = $this->input->post('limit');
        $offset = $this->input->post('offset');

        $forum_replies = $this->general_model->get_forum_replies($forum_id, $limit, $offset);

        $data['forum_replies'] = $forum_replies;

        echo json_encode($data);
    }
	public function load_forum_sub_comments() {
        $forum_rep_id = $this->input->post('forum_rep_id');
        // $limit = $this->input->post('limit');
        // $offset = $this->input->post('offset');

        $forum_sub_comments = $this->general_model->get_forum_sub_comments($forum_rep_id);

        $data['forum_sub_comments'] = $forum_sub_comments;

        echo json_encode($data);
    }
	public function save_forum() {
        $date_time = $this->get_current_date_time();
        $role_label = $this->session->userdata("role_label");
        $forum["datetime_post"] = $date_time["dateTime"];
        $forum['title_forum'] = $this->input->post('forum_title');
        $forum['desc_forum'] = $this->input->post('forum_desc');
        $forum['published_by'] = $this->session->userdata("fullname")." "."(".$role_label.")";
        $forum['id_admin'] = $this->session->userdata("id_admin");
        $forum['visibility'] = $this->input->post('visibility');
        $forum['publisher_role'] = $this->input->post('role');
        $this->general_model->insert_vals($forum, "tbl_forum");
    
        // Respond with a JSON message
        $response = array('message' => 'Forum saved successfully');
        echo json_encode($response);
    }
    public function save_forum_comment() {
        $date_time = $this->get_current_date_time();
        $role_label = $this->session->userdata("role_label");
        $forum["datetime_rep"] = $date_time["dateTime"];
        $forum['forum_rep'] = $this->input->post('forum_comment');
        $forum['id_forum'] = $this->input->post('forum_id');
        $forum['commented_by'] = $this->session->userdata("fullname")." "."(".$role_label.")";
        $forum['id_admin'] =  $this->session->userdata("id_admin");
        $forum['reference'] =  0;
        $forum['id_ho'] =  0;
        $this->general_model->insert_vals($forum, "tbl_forum_replies");
    
        // Respond with a JSON message
        $response = array('message' => 'Forum saved successfully');
        echo json_encode($response);
    }
    public function save_forum_comment_reply() {
        $date_time = $this->get_current_date_time();
        $role_label = $this->session->userdata("role_label");
        $forum["datetime_rep"] = $date_time["dateTime"];
        $forum['forum_rep'] = $this->input->post('forum_comment');
        $forum['id_forum'] = $this->input->post('forum_id');
        $forum['reference'] = $this->input->post('reply_id');
        $forum['commented_by'] = $this->session->userdata("fullname")." "."(".$role_label.")";
        $forum['id_admin'] =  $this->session->userdata("id_admin");
        $forum['id_ho'] =  0;
        $this->general_model->insert_vals($forum, "tbl_forum_replies");
    
        // Respond with a JSON message
        $response = array('message' => 'Forum saved successfully');
        echo json_encode($response);
    }
	public function fetch_forum_details(){
		$forum_id = $this->input->post('forum_id');
        $forum_info = $this->general_model->custom_query('SELECT id_forum,title_forum, desc_forum,visibility,id_admin,id_ho,published_by FROM `tbl_forum` WHERE id_forum ='. $forum_id);
        echo json_encode($forum_info);
	}
	public function update_forum(){
        $id = $this->input->post('forum_id');
        $forum['title_forum'] = $this->input->post('forum_title');
        $forum['desc_forum'] = $this->input->post('forum_desc');
        $this->general_model->update_vals($forum, "id_forum = $id", "tbl_forum");
		// Respond with a JSON message
		$response = array('message' => 'Forum saved updated');
		echo json_encode($response);
    }
	public function delete_forum(){
        $id = $this->input->post('forum_id');
		$where_sub = "";
		$where_com  = "";
		$where_forum = "";
		$res = $this->general_model->custom_query('SELECT id_forum_rep, forum_rep FROM `tbl_forum_replies` WHERE reference = 0 AND id_forum ='. $id);

	
		// Check if forum contains comments
		if(count($res)> 0){
			// has comments
			$id_forum_rep_arr = array_column($res, 'id_forum_rep');
			$id_forum_rep_string = implode(',', $id_forum_rep_arr);

			// Check sub-comments
			$sub_comments = $this->general_model->custom_query('SELECT id_forum_rep, forum_rep FROM `tbl_forum_replies` WHERE reference IN ('.$id_forum_rep_string.')');
			if(count($sub_comments)> 0){
				// Naay subcomments 
				$sub_comments_arr = array_column($sub_comments, 'id_forum_rep');
				$sub_comments_string = implode(',', $sub_comments_arr);

				// delete sub comments
				$where_sub = 'id_forum_rep IN ('.$sub_comments_string.')';
        		$this->general_model->delete_vals($where_sub, 'tbl_forum_replies');
			}
			// delete comments
			$where_com = 'id_forum_rep IN ('.$id_forum_rep_string.')';
			$this->general_model->delete_vals($where_com, 'tbl_forum_replies');
		}
		// delete the forum
		$where_forum = 'id_forum ='.$id;
		$this->general_model->delete_vals($where_forum, 'tbl_forum');
		// Respond with a JSON message
		$response = array('message' => 'Forum saved updated');
		echo json_encode($response);
	}
	public function delete_forum_comment(){
        $id = $this->input->post('forum_comment_id');
		$where_sub = "";
		$where_com  = "";

			// Check sub-comments
		$sub_comments = $this->general_model->custom_query('SELECT id_forum_rep, forum_rep FROM `tbl_forum_replies` WHERE reference ='.$id);
			if(count($sub_comments)> 0){
				// Naay subcomments 
				$sub_comments_arr = array_column($sub_comments, 'id_forum_rep');
				$sub_comments_string = implode(',', $sub_comments_arr);

				// delete sub comments
				$where_sub = 'id_forum_rep IN ('.$sub_comments_string.')';
        		$this->general_model->delete_vals($where_sub, 'tbl_forum_replies');
			}

			// logs 
			$comment_info = $this->general_model->custom_query('SELECT commented_by FROM `tbl_forum_replies` WHERE id_forum_rep ='.$id);
			$admin_name = $this->session->userdata("fullname");
			$message = $admin_name.' removed/deleted comment of '.$comment_info[0]->commented_by.' in Forum.';
			$this->activity_log('forum',$id,$message);

			// delete comments
			$where_com = 'id_forum_rep ='.$id;
			$this->general_model->delete_vals($where_com, 'tbl_forum_replies');
		
		// Respond with a JSON message
		$response = array('message' => 'Forum saved updated');


		echo json_encode($response);
	}
	public function delete_forum_sub_comment(){
        $id = $this->input->post('forum_comment_id');
		$where_com  = "";

		// logs 
		$comment_info = $this->general_model->custom_query('SELECT commented_by FROM `tbl_forum_replies` WHERE id_forum_rep ='.$id);
		$admin_name = $this->session->userdata("fullname");
		$message = $admin_name.' removed/deleted sub-comment of '.$comment_info[0]->commented_by.' in Forum.';
		$this->activity_log('forum',$id,$message);


			// delete comments
		$where_com = 'id_forum_rep ='.$id;
		$this->general_model->delete_vals($where_com, 'tbl_forum_replies');
		
		// Respond with a JSON message
		$response = array('message' => 'Forum saved updated');
		echo json_encode($response);
	}
}