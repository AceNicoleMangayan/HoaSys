<?php

use Mpdf\Tag\P;

defined('BASEPATH') or exit('No direct script access allowed');
require_once(dirname(__FILE__) . "/General.php");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

class Dues extends General
{
    protected $title = 'Dues';
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        if ($this->session->userdata("id_ho")) {
            $date_time = $this->get_current_date_time();
            $data["date_time"] = $date_time["dateTime"];
            // $role = $this->session->userdata("role");
            $data['title'] = $this->title;
            $this->load_template_view('templates/dues/dues', $data);
        } else {
            redirect(base_url('Login'));
        }
    }
    public function get_dues()
    {
        $datatable = $this->input->post('datatable');
        $query['search']['append'] = "";
        $query['search']['total'] = "";
        $where_name = "";
        $order = " lname ";
        $month_where = "";
        $year_where = "";
        $stat_where = "";
        $month = $datatable['query']['month'];
        $year = $datatable['query']['year'];
        $status = $datatable['query']['stat'];
        $session_id = $this->session->userdata("id_ho");
        // var_dump($month);

        if (!empty($month) && trim($month) !== 'All') {
            $month_where = " AND r.month_record = '".$month."'";
        }
        if (!empty($year) && trim($year) !== 'All') {
            $year_where =  " AND r.year_record = '".$year."'";
        }
        if (!empty($status) && trim($status) !== 'All') {
            $stat_where = " AND r.status_record = '".$status."'";
        }
        

        $query['query'] = "SELECT h.*,r.*, CONCAT(h.fname,' ', h.mname, ' ', h.lname) as fullname FROM tbl_records r, tbl_homeowner h WHERE h.id_ho = r.id_ho AND r.id_ho =".$session_id."".$month_where."".$year_where."".$stat_where;
        // if ($datatable['query']['searchField'] != '') {
        //     $keyword = $datatable['query']['searchField'];
        // $where = "(lname LIKE '%" . $keyword . "%' OR fname LIKE '%" . $keyword . "%')";
        //     $query['search']['append'] = " AND ($where)";
        //     $query['search']['total'] = " AND ($where)";
        // }
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
    public function create_billing_all()
    {
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        $homeowner['created'] = 1; 
        // check billing if existing
        $res = $this->general_model->custom_query("Select billingID FROM tbl_billing_month_year  WHERE month = '$month' AND year = '$year'");

        if (count($res) > 0) {
            // if existing 
            // Get All homeowners that has billing 
            $homeowner['billing_id'] = $res[0]->billingID; 
            $id_records_has_billing = $this->general_model->custom_query("SELECT id_ho FROM `tbl_records` WHERE year_record = '$year' AND month_record = '$month' ");
            if (count($id_records_has_billing) > 0) {
                $excludedIds = array_map(function ($item) {
                    return $item->id_ho;
                }, $id_records_has_billing);

                $sql_ex = "SELECT id_ho FROM tbl_homeowner WHERE status = 'active'";

                // Checking if there are ids to exclude
                if (!empty($excludedIds)) {
                    // Adding NOT IN clause to exclude ids from $array1
                    $sql_ex .= " AND id_ho NOT IN (" . implode(',', $excludedIds) . ")";
                }

                // fetch sa active homeowners nga walay in ani nga billing
                $tobe_added_homeowner = $this->general_model->custom_query($sql_ex);
                // Get otherdetails

                if (count($tobe_added_homeowner) > 0) {
                    // Naa pay mga active homeowners nga need pa butangan ani nga billing
                    $id_ho_values = array_map(function ($obj) {
                        return $obj->id_ho;
                    }, $tobe_added_homeowner);

                    // Get all the homeowners ID that needs to be inserted with tbl_record
                    // Convert the array values to a comma-separated string
                    $id_ho_in_clause = implode(',', $id_ho_values);

                    // Modify your query with the generated IN clause
                    $query_details = "SELECT h.id_ho, hm.id_ho_monthly, hm.monthly, hm.duedate
              FROM tbl_homeowner h, tbl_homeowner_monthly hm
              WHERE h.status = 'active' AND h.id_ho = hm.id_ho AND h.id_ho IN ($id_ho_in_clause)";
                    $result_ho_details = $this->general_model->custom_query($query_details);

                    // Add records 
                    $data_records = array();
                    foreach ($result_ho_details as $item) {
                        $record_ho = array(
                            'year_record' => $year, 
                            'month_record' => $month, 
                            'status_record' => "pending",
                            'id_ho' => $item->id_ho,
                            'id_admin' => $this->session->userdata("id_admin"),
                            'duedate_record' => $item->duedate,
                            'paid_amount' => $item->monthly,
                            'id_ho_monthly' => $item->id_ho_monthly,
                            'billing_id' => $res[0]->billingID
                        );

                        $data_records[] = $record_ho;
                    }
                    $this->general_model->batch_insert($data_records, "tbl_records");
                    $can_Add = 2;
                }else{
                    // Wala nay pwede ma addan ani nga billing 
                    $can_Add = 3;
                }
            } else {
                // Should create billing to all homeowners
                $this->insert_all_homeowners($res[0]->billingID, $month, $year);
                $can_Add = 4;
            }
        } else {
            // ADDS BILLING RECORD
            $data = array(
                'month'     => $month,
                'year'     =>  $year,
            );

            $billing_id = $this->general_model->insert_vals_last_inserted_id($data, "tbl_billing_month_year");
            $this->insert_all_homeowners($billing_id, $month, $year);
            $can_Add = 1;
        }
        echo json_encode($can_Add);
    }
    public function insert_all_homeowners($billing_id, $month, $year)
    {
        // GET ALL HOMEOWNERS ID 
        $homeowners = $this->general_model->custom_query("SELECT h.id_ho,hm.id_ho_monthly,hm.monthly,hm.duedate  FROM tbl_homeowner h, tbl_homeowner_monthly hm WHERE h.status = 'active' AND h.id_ho = hm.id_ho");
        $data = array();
        foreach ($homeowners as $item) {
            $record = array(
                'year_record' => $year, 
                'month_record' => $month,
                'status_record' => "pending",
                'id_ho' => $item->id_ho,
                'id_admin' => $this->session->userdata("id_admin"),
                'duedate_record' => $item->duedate,
                'paid_amount' => $item->monthly,
                'id_ho_monthly' => $item->id_ho_monthly,
                'billing_id' => $billing_id
            );

            $data[] = $record;
        }
        $this->general_model->batch_insert($data, "tbl_records");
    }
    public function fetch_homeowners_options(){
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        $homeowner['return'] = 1;
        // check billing if existing
        $res = $this->general_model->custom_query("Select billingID FROM tbl_billing_month_year  WHERE month = '$month' AND year = '$year'");
        if (count($res) > 0) {
            // Na create na nga billing
            // Identify ang mga homeowners nga naa nay in ani nga billing
            $homeowner['bid'] = $res[0]->billingID;
            $homeowner['created'] = 1;
            $id_records_has_billing = $this->general_model->custom_query("SELECT id_ho FROM `tbl_records` WHERE year_record = '$year' AND month_record = '$month' ");
            if (count($id_records_has_billing) > 0) {
                // Naay employees nga naa ani nga billing 
                $excludedIds = array_map(function ($item) {
                    return $item->id_ho;
                }, $id_records_has_billing);

                $sql_ex = "SELECT id_ho as id,CONCAT(lname,', ',fname,' ',mname) as text  FROM tbl_homeowner WHERE status = 'active'";

                // Checking if there are ids to exclude
                if (!empty($excludedIds)) {
                    // Adding NOT IN clause to exclude ids from $array1
                    $sql_ex .= " AND id_ho NOT IN (" . implode(',', $excludedIds) . ")";
                }
                // fetch sa active homeowners nga walay in ani nga billing
                $tobe_added_homeowner = $this->general_model->custom_query($sql_ex);

                if (count($tobe_added_homeowner) > 0) {
                    // Naay walay in ani nga billing
                    $homeowner['opt'] = $tobe_added_homeowner;
                }else{
                    // Na addan na tana employee so dapat wala na syay i return nga options 
                    $homeowner['return'] = 0;
                }
                // Get otherdetails
            }else{
            //walay employees nga naay record ani na billing
        $homeowner['opt'] = $this->general_model->custom_query("SELECT id_ho as id,CONCAT(lname,', ',fname,' ',mname) as text FROM `tbl_homeowner` WHERE status = 'active'");    
            }

        }else{
        // Get all homeowners since this billing is not yet created 
        $homeowner['opt'] = $this->general_model->custom_query("SELECT id_ho as id,CONCAT(lname,', ',fname,' ',mname) as text FROM `tbl_homeowner` WHERE status = 'active'");    
        $homeowner['created'] = 0;
    }   
        echo json_encode($homeowner);
    }
    public function create_billing_ho(){
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        $homeowners = $this->input->post('homeowners');
        $ho_str = implode(',', $homeowners);

        $res = $this->general_model->custom_query("Select billingID FROM tbl_billing_month_year  WHERE month = '$month' AND year = '$year'");
        if (count($res) > 0) {
            // Naay billing month and year
            $bil_id = $res[0]->billingID;
        }else{
            $data = array(
                'month'     => $month,
                'year'     =>  $year,
            );
            $bil_id = $this->general_model->insert_vals_last_inserted_id($data, "tbl_billing_month_year"); 
        }

        // $id_ho_values = array_map(function ($obj) {
        //     return $obj->id_ho;
        // }, $homeowners);

        // Get all the homeowners ID that needs to be inserted with tbl_record
        // Convert the array values to a comma-separated string
        // $id_ho_in_clause = implode(',', $id_ho_values);

        // Modify your query with the generated IN clause
        $query_details = "SELECT h.id_ho, hm.id_ho_monthly, hm.monthly, hm.duedate
  FROM tbl_homeowner h, tbl_homeowner_monthly hm
  WHERE h.status = 'active' AND h.id_ho = hm.id_ho AND h.id_ho IN ($ho_str)";
        $result_ho_details = $this->general_model->custom_query($query_details);

        // Add records 
        $data_records = array();
        foreach ($result_ho_details as $item) {
            $record_ho = array(
                'year_record' => $year, 
                'month_record' => $month, 
                'status_record' => "pending",
                'id_ho' => $item->id_ho,
                'id_admin' => $this->session->userdata("id_admin"),
                'duedate_record' => $item->duedate,
                'paid_amount' => $item->monthly,
                'id_ho_monthly' => $item->id_ho_monthly,
                'billing_id' => $bil_id
            );

            $data_records[] = $record_ho;
        }
        $this->general_model->batch_insert($data_records, "tbl_records");
    }
    public function get_details_dues_per_homeowner(){
        $id = $this->input->post('id');
        $id_rec = $this->input->post('record_id');
        $details = $this->general_model->custom_query("SELECT h.*,r.*, CONCAT(h.fname,' ', h.mname, ' ', h.lname) as fullname FROM tbl_records r, tbl_homeowner h WHERE h.id_ho = r.id_ho AND h.id_ho = $id AND r.id_record = $id_rec");
        echo json_encode($details);
    }
    public function update_record_status(){
        $date_time = $this->get_current_date_time();
        $id_rec = $this->input->post('record_id');
        $stat = $this->input->post('status');
        $records['status_record'] =  $stat;
        $records['date_updated'] =  $date_time["dateTime"];
        $this->general_model->update_vals($records, "id_record = $id_rec", "tbl_records");
    }
    public function update_penalty(){
        $id_rec = $this->input->post('record_id');
        $penalty = $this->input->post('penalty');
        if($penalty == "0" || $penalty == "0" || $penalty == ""){
            $penalty == null;
        }
        $records['penalty'] =  $penalty;
        $this->general_model->update_vals($records, "id_record = $id_rec", "tbl_records");
    }
    public function delete_record(){
        $id_rec = $this->input->post('record_id');
        $this->general_model->delete_vals("id_record = $id_rec", "tbl_records");
    }
    public function get_ledger_details(){
        $id = $this->session->userdata("id_ho");
        $ledger_details = $this->general_model->custom_query('
            SELECT id_record, year_record, month_record, status_record, penalty, paid_amount, duedate_record 
            FROM `tbl_records` 
            WHERE status_record = "pending" AND id_ho = ' . $id . '
            ORDER BY STR_TO_DATE(CONCAT(year_record, " ", month_record), "%Y %M") DESC
        ');
        echo json_encode($ledger_details);
    }
}