<?php

use Mpdf\Tag\P;

defined('BASEPATH') or exit('No direct script access allowed');
require_once(dirname(__FILE__) . "/General.php");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

class Activity extends General
{
    protected $title = 'Activity';
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
            if ($role == "admin" || $role == "due" || $role == "officer") {
                $data['title'] = $this->title;
                $this->load_template_view('templates/activity/activity', $data);
            } else {
                // redirect(base_url('dashboard'));
                redirect(base_url('dashboard'));
            }
        } else {
            redirect(base_url('Login'));
        }
    }
    public function get_activity()
    {
        $datatable = $this->input->post('datatable');
        $query['search']['append'] = "";
        $query['search']['total'] = "";
        $order = " ORDER BY id_log DESC";

        $query['query'] = "SELECT * FROM `tbl_activity_logs` WHERE id_log != 0 ";

        if ($datatable['query']['searchField'] != '') {
            $keyword = $datatable['query']['searchField'];
            $where = "(activity_description LIKE '%" . $keyword . "%' OR module LIKE '%" . $keyword . "%')";
            $query['search']['append'] = " AND ($where)";
            $query['search']['total'] = " AND ($where)";
        }

        $page = $datatable['pagination']['page'];
        $perpage = $datatable['pagination']['perpage'];

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
            $query['query'] .= $order . $limit;
        }

        $data = $this->general_model->custom_query($query['query']);
        $meta = [
            "page" => intval($page),
            "pages" => intval($pages),
            "perpage" => intval($perpage),
            "total" => $total,
            "sort" => "desc",  // Set sort to 'desc'
            "field" => "id_log",  // Set field to 'id_log'
        ];
        echo json_encode(['meta' => $meta, 'data' => $data]);
    }
}
