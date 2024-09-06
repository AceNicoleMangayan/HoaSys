<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(dirname(__FILE__) . "/General.php");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Content-Type");


class Dashboard extends General
{
    protected $title = 'Admin';

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata("id_admin")) {
        } else {
            redirect(base_url('Login'));
        }
    }

    public function index()
    {
        $data['title'] = $this->title;
        $role = $this->session->userdata("role");
        $data['fullname'] = $this->session->userdata("fullname");
        // if ($role == "Admin") {
            $this->load_template_view('templates/dashboard/index', $data);
        // } else {
            // redirect(base_url('participants'));
        //     redirect(base_url('homeowners'));
        // }
    }
    public function fetchData_sample() {
        $data['graph_data'] = $this->general_model->get_data();

        // Convert the data to JSON and echo it
        echo json_encode($data);
    }
	public function pie_chart() {
        // Fetch data from the model
        $year = $this->input->post('year');
        $chartData = $this->general_model->getChartData($year);

        // Prepare data for JSON response
        $data = [];
        foreach ($chartData as $row) {
            $data[] = [
                'name' => $row['category'],
                'y'    => (int)$row['value'],
            ];
        }
        // Send JSON response
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
    public function get_dashboard_counts(){
       $res['concerns'] = $this->general_model->custom_query('SELECT COUNT(id_concern) as concerns FROM `tbl_concern` WHERE status_concern = "unresolved"');
       $res['homeowner'] = $this->general_model->custom_query('SELECT COUNT(id_ho) as homeowner FROM `tbl_homeowner` WHERE status = "active"');
       $res['election'] = $this->general_model->custom_query('SELECT COUNT(id_elect) as election FROM `tbl_election` WHERE election_status = "ongoing"');
       $res['unpaid'] = $this->general_model->custom_query('SELECT COUNT(id_record) as unpaid FROM tbl_records WHERE status_record = "pending"');
       $res['announcement'] = $this->general_model->custom_query('SELECT id_anmnt, datecreated_anmnt, title_anmnt, desc_anmnt FROM tbl_announcement WHERE status_anmnt = "published" ORDER BY datecreated_anmnt DESC LIMIT 3');
       echo json_encode($res);
        // var_dump($res['announcement']);
    }
    public function get_specific_announcement_dashboard(){
       $id = $this->input->post('id');
       $res = $this->general_model->custom_query('SELECT id_anmnt, datecreated_anmnt, title_anmnt, desc_anmnt FROM tbl_announcement WHERE status_anmnt = "published" AND id_anmnt = '.$id);
       echo json_encode($res);
    }
    
}