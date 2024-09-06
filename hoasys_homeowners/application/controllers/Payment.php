<?php

use Mpdf\Tag\P;

defined('BASEPATH') or exit('No direct script access allowed');
require_once(dirname(__FILE__) . "/General.php");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

class Payment extends General
{
    protected $title = 'Payment';
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
            $data['title'] = $this->title;
            $this->load_template_view('templates/payment/payment', $data);
        } else {
            redirect(base_url('Login'));
        }
    }
    public function get_payment()
    {
        $datatable = $this->input->post('datatable');
        $query['search']['append'] = "";
        $query['search']['total'] = "";
        $where_name = "";
        $order = " lastname ";

        $query['query'] = "SELECT homeownerID, lastname, firstname, status, email_add, contact_num FROM tbl_homeowners WHERE homeownerID != 0 " . $where_name;
        if ($datatable['query']['searchField'] != '') {
            $keyword = $datatable['query']['searchField'];
        $where = "(lastname LIKE '%" . $keyword . "%' OR firstname LIKE '%" . $keyword . "%')";
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
    public function get_payment_records()
    {
        $datatable = $this->input->post('datatable');
        $id = $datatable['query']['id'];
        $month = $datatable['query']['month'];
        $year = $datatable['query']['year'];
        $stat = $datatable['query']['stat'];
        $query['search']['append'] = "";
        $query['search']['total'] = "";
        $where_name = "";
        $order = " lastname ";
        $month_where = "";
        $year_where = "";
        $stat_where = "";

        if($month !== "All"){
            $month_where = " AND bl.month = '".$month."'";
        }

        if($year !== "All"){
            $year_where = " AND bl.year = ".$year;
        }

        if($stat !== "All"){
            $stat_where = " AND bh.status = '".$stat."'";
        }

        $query['query'] = "SELECT h.homeownerID,bh.bhomeID,bh.billingID, bh.payment, bh.status, bl.month, bl.year FROM tbl_homeowners h, tbl_billing_homeowner bh, tbl_billing bl WHERE h.homeownerID = ".$id." AND h.homeownerID = bh.homeownerID AND bh.billingID = bl.billingID ".$month_where."".$year_where."".$stat_where;
        if ($datatable['query']['searchField'] != '') {
            $keyword = $datatable['query']['searchField'];
        $where = "(lastname LIKE '%" . $keyword . "%' OR firstname LIKE '%" . $keyword . "%')";
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
    public function create_billing(){
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $type = $this->input->post('type');
        $id = $this->input->post('id');
        $can_Add = 0;
        $homeid_arr = array();
        $loop = 0;

        if($type == 1){
              // check if existing ? 
        $check = $this->general_model->custom_query('SELECT * FROM `tbl_billing` WHERE month = "'.$month.'" AND year = '.$year);
        if(count($check) > 0){
            $can_Add = 0;
        }else{
            $billing['month'] =   $month;
            $billing['year'] =  $year;
            $homeowner_IDs = $this->general_model->custom_query('SELECT h.homeownerID, p.paymentID,p.payment FROM tbl_homeowners h, tbl_payment p WHERE h.status = "active" AND h.homeownerID = p.homeownerID ');
            
            if(count($homeowner_IDs) > 0){
                $can_Add = 1;
                $billing_id = $this->general_model->insert_vals_last_inserted_id($billing, "tbl_billing");
                foreach ($homeowner_IDs as $bil) {
                    $homeid_arr[$loop] = [
                        'homeownerID' => $bil->homeownerID,
                        'billingID' => $billing_id,
                        'payment' => $bil->payment,
                        'status' => "unpaid"
                    ];
                    $loop++;
                }
                $this->general_model->batch_insert($homeid_arr, 'tbl_billing_homeowner');
            }else{
                // cannot add billing since theres no homeowners active 
                $can_Add = 2;
            }
        }
        }else{
            $check = $this->general_model->custom_query('SELECT * FROM `tbl_billing` WHERE month = "'.$month.'" AND year = '.$year);
            if(count($check) > 0){

                // Check if existing in this homeowner
                $bD = $check[0]->billingID;
                $checkH = $this->general_model->custom_query('SELECT * FROM `tbl_billing_homeowner` WHERE billingID = '.$bD.' AND homeownerID = '.$id);
                if(count($checkH) > 0){
                    $can_Add = 0;
                }else{
                    $can_Add = 1;
                    $payment = $this->general_model->custom_query('SELECT * FROM `tbl_payment` WHERE homeownerID = '.$id);
                    $save['homeownerID'] = $id;
                    $save['billingID'] = $bD;
                    $save['payment'] = $payment[0]->payment;
                    $save['status'] = "unpaid";
                    $this->general_model->insert_vals($save, "tbl_billing_homeowner");
                }
            }else{
                $can_Add = 0;
            }
        }
        echo json_encode($can_Add);
    }
    public function delete_billing(){
        $id = $this->input->post('id');
        $where_del = 'bhomeID = '.$id;
        $this->general_model->delete_vals($where_del, 'tbl_billing_homeowner');
    }
    public function confirm_billing(){
        $id = $this->input->post('id');
        $home['status'] =  "paid";
        $this->general_model->update_vals($home, "bhomeID = $id", "tbl_billing_homeowner");
    }
    public function email_sending_reminder()
    {
        $em = $this->input->post('email');
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $payment = $this->input->post('payment');
        $this->load->library('email');
        $ser = 'http://' . $_SERVER['SERVER_NAME'];
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com',
            'smtp_timeout' => 30,
            'smtp_port' => 465,
            'smtp_user' => 'ggn1cdo@gmail.com',
            'smtp_pass' => 'asklaymjpayxhkyi',
            'charset' => 'utf-8',
            'mailtype' => 'html',
            'newline' => '\r\n'
        );

        $message = "Hi! This is a reminder that you still have to pay your dues for ".$month." ".$year.". Your payment is: ".$payment;
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->set_crlf("\r\n");
        $this->email->from("ggn1cdo@gmail.com");
        $this->email->to($em);
        $this->email->subject("GGN1 Account Verification");
        $this->email->message($message);
        if ($this->email->send()) {
            echo "Mail successful";
        } else {
            echo "Sorry";
            print_r($this->email->print_debugger());
        }
    }
}