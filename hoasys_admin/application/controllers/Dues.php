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
        if ($this->session->userdata("id_admin")) {
            $date_time = $this->get_current_date_time();
            $data["date_time"] = $date_time["dateTime"];
            $role = $this->session->userdata("role");
            if ($role == "due") {
                $data['title'] = $this->title;
                $this->load_template_view('templates/dues/dues', $data);
            } else {
                // redirect(base_url('dashboard'));
                redirect(base_url('dashboard'));
            }
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
        $block_where = "";
        $lot_where = "";
        $month = $datatable['query']['month'];
        $year = $datatable['query']['year'];
        $status = $datatable['query']['stat'];
        $block = $datatable['query']['block'];
        $lot = $datatable['query']['lot'];
        // var_dump($month);

        if (!empty($month) && trim($month) !== 'All') {
            $month_where = " AND r.month_record = '" . $month . "'";
        }
        if (!empty($year) && trim($year) !== 'All') {
            $year_where =  " AND r.year_record = '" . $year . "'";
        }
        if (!empty($status) && trim($status) !== 'All') {
            $stat_where = " AND r.status_record = '" . $status . "'";
        }

        if (!empty($block) && trim($block) !== 'All') {
            $block_where = " AND h.block = '" . $block . "'";
        }
        if (!empty($lot) && trim($lot) !== 'All') {
            $lot_where =  " AND h.lot = '" . $lot . "'";
        }

        $query['query'] = "SELECT h.id_ho,h.lname,h.fname,h.mname,h.block,h.lot,h.village,h.email_add,h.contact_num,h.status,r.*, CONCAT(h.fname,' ', h.mname, ' ', h.lname) as fullname FROM tbl_records r, tbl_homeowner h WHERE r.status_record != 'archived' AND h.id_ho = r.id_ho " . $month_where . "" . $year_where . "" . $stat_where . "" . $block_where . "" . $lot_where;

        if ($datatable['query']['searchField'] != '') {
            $keyword = $datatable['query']['searchField'];
            $where = "(CONCAT(fname,' ', mname,' ', lname) LIKE '%" . $keyword . "%' OR CONCAT(fname,' ', lname) LIKE '%" . $keyword . "%' OR lname LIKE '%" . $keyword . "%' OR fname LIKE '%" . $keyword . "%' OR mname LIKE '%" . $keyword . "%')";
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
    public function create_billing()
    {
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $type = $this->input->post('type');
        $id = $this->input->post('id');
        $can_Add = 0;
        $homeid_arr = array();
        $loop = 0;

        if ($type == 1) {
            // check if existing ? 
            $check = $this->general_model->custom_query('SELECT * FROM `tbl_billing` WHERE month = "' . $month . '" AND year = ' . $year);
            if (count($check) > 0) {
                $can_Add = 0;
            } else {
                $billing['month'] =   $month;
                $billing['year'] =  $year;
                $homeowner_IDs = $this->general_model->custom_query('SELECT h.homeownerID, p.paymentID,p.payment FROM tbl_homeowners h, tbl_payment p WHERE h.status = "active" AND h.homeownerID = p.homeownerID ');

                if (count($homeowner_IDs) > 0) {
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
                } else {
                    // cannot add billing since theres no homeowners active 
                    $can_Add = 2;
                }
            }
        } else {
            $check = $this->general_model->custom_query('SELECT * FROM `tbl_billing` WHERE month = "' . $month . '" AND year = ' . $year);
            if (count($check) > 0) {

                // Check if existing in this homeowner
                $bD = $check[0]->billingID;
                $checkH = $this->general_model->custom_query('SELECT * FROM `tbl_billing_homeowner` WHERE billingID = ' . $bD . ' AND homeownerID = ' . $id);
                if (count($checkH) > 0) {
                    $can_Add = 0;
                } else {
                    $can_Add = 1;
                    $payment = $this->general_model->custom_query('SELECT * FROM `tbl_payment` WHERE homeownerID = ' . $id);
                    $save['homeownerID'] = $id;
                    $save['billingID'] = $bD;
                    $save['payment'] = $payment[0]->payment;
                    $save['status'] = "unpaid";
                    $this->general_model->insert_vals($save, "tbl_billing_homeowner");
                }
            } else {
                $can_Add = 0;
            }
        }
        echo json_encode($can_Add);
    }
    public function delete_billing()
    {
        $id = $this->input->post('id');
        $where_del = 'bhomeID = ' . $id;
        $this->general_model->delete_vals($where_del, 'tbl_billing_homeowner');
    }
    public function confirm_billing()
    {
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

        $message = "Hi! This is a reminder that you still have to pay your dues for " . $month . " " . $year . ". Your payment is: " . $payment;
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
                } else {
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
    public function fetch_homeowners_options()
    {
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
                } else {
                    // Na addan na tana employee so dapat wala na syay i return nga options 
                    $homeowner['return'] = 0;
                }
                // Get otherdetails
            } else {
                //walay employees nga naay record ani na billing
                $homeowner['opt'] = $this->general_model->custom_query("SELECT id_ho as id,CONCAT(lname,', ',fname,' ',mname) as text FROM `tbl_homeowner` WHERE status = 'active'");
            }
        } else {
            // Get all homeowners since this billing is not yet created 
            $homeowner['opt'] = $this->general_model->custom_query("SELECT id_ho as id,CONCAT(lname,', ',fname,' ',mname) as text FROM `tbl_homeowner` WHERE status = 'active'");
            $homeowner['created'] = 0;
        }
        echo json_encode($homeowner);
    }
    public function create_billing_ho()
    {
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        $homeowners = $this->input->post('homeowners');
        $ho_str = implode(',', $homeowners);

        $res = $this->general_model->custom_query("Select billingID FROM tbl_billing_month_year  WHERE month = '$month' AND year = '$year'");
        if (count($res) > 0) {
            // Naay billing month and year
            $bil_id = $res[0]->billingID;
        } else {
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
    public function get_details_dues_per_homeowner()
    {
        $id = $this->input->post('id');
        $id_rec = $this->input->post('record_id');
        $details = $this->general_model->custom_query("SELECT h.id_ho,h.lname,h.fname,h.mname,h.block,h.lot,h.village,h.email_add,h.contact_num,h.status,r.*, CONCAT(h.fname,' ', h.mname, ' ', h.lname) as fullname FROM tbl_records r, tbl_homeowner h WHERE h.id_ho = r.id_ho AND h.id_ho = $id AND r.id_record = $id_rec");
        echo json_encode($details);
    }
    public function update_record_status()
    {
        $date_time = $this->get_current_date_time();
        $id_rec = $this->input->post('record_id');
        $id_ho = $this->input->post('ho_id');
        $stat = $this->input->post('status');
        if ($stat == "paid") {
            $records['receipt_num'] = $this->input->post('receipt');
        } else {
            $records['receipt_num'] = NULL;
        }
        $records['status_record'] =  $stat;
        $records['date_updated'] =  $date_time["dateTime"];
        $this->general_model->update_vals($records, "id_record = $id_rec", "tbl_records");

        $admin_name = $this->session->userdata("fullname");
        $ho_records = $this->get_records_for_logs($id_rec);
        $message = $admin_name . ' changed dues status of ' . $ho_records[0]->fullname . ' to ' . $stat . ' for ' . $ho_records[0]->month_record . ' ' . $ho_records[0]->year_record . ' dues.';
        $this->activity_log('dues', $id_rec, $message);
        $this->send_billing_transaction_email($id_rec, $id_ho, $stat, $admin_name);
    }
    public function update_penalty()
    {
        $id_rec = $this->input->post('record_id');
        $ho_id = $this->input->post('ho_id');
        $penalty = $this->input->post('penalty');
        if ($penalty == "0" || $penalty == "") {
            $penalty == null;
        }
        $records['penalty'] =  $penalty;
        $this->general_model->update_vals($records, "id_record = $id_rec", "tbl_records");

        $admin_name = $this->session->userdata("fullname");
        $penalty_records = $this->get_records_for_logs($id_rec);
        $message = $admin_name . ' added penalty of ' . $penalty . ' to homeowner' . $penalty_records[0]->fullname . ' for ' . $penalty_records[0]->month_record . ' ' . $penalty_records[0]->year_record . ' dues.';
        $this->activity_log('dues', $id_rec, $message);
        $this->send_billing_transaction_email($id_rec, $ho_id, "penalty", $admin_name);
    }
    public function update_amount()
    {
        $id_rec = $this->input->post('record_id');
        $ho_id = $this->input->post('ho_id');
        $amount = $this->input->post('amount');
        if ($amount == "0" || $amount == "") {
            $amount == "0";
        }
        $records['paid_amount'] =   $amount;
        $this->general_model->update_vals($records, "id_record = $id_rec", "tbl_records");

        $admin_name = $this->session->userdata("fullname");
        $pay_records = $this->get_records_for_logs($id_rec);
        $message = $admin_name . ' changed the billing amount of ' . $pay_records[0]->fullname . ' to ' . $amount . ' for ' . $pay_records[0]->month_record . ' ' . $pay_records[0]->year_record . ' dues.';
        $this->activity_log('dues', $id_rec, $message);
        $this->send_billing_transaction_email($id_rec, $ho_id, "amount", $admin_name);
    }

    public function delete_record()
    {
        $id_rec = $this->input->post('record_id');
        $ho_id = $this->input->post('ho_id');
        $admin_name = $this->session->userdata("fullname");
        $penalty_records = $this->get_records_for_logs($id_rec);
        $message = $admin_name . ' removed/deleted  due record of ' . $penalty_records[0]->fullname . ' for ' . $penalty_records[0]->month_record . ' ' . $penalty_records[0]->year_record . '.';
        $this->activity_log('dues', $id_rec, $message);
        $this->send_billing_transaction_email($id_rec, $ho_id, "delete", $admin_name);
        $this->general_model->delete_vals("id_record = $id_rec", "tbl_records");
    }
    public function send_billing_dues()
    {
        $record_id = $this->input->post('record_id');
        $ho_id = $this->input->post('ho_id');
        $billing_info = $this->general_model->custom_query("SELECT h.id_ho,h.lname,h.fname,h.mname,h.block,h.lot,h.village,h.email_add,h.contact_num,h.status,r.*, CONCAT(h.fname,' ', h.lname) as fullname, h.email_add FROM tbl_records r, tbl_homeowner h WHERE h.id_ho = r.id_ho AND r.id_record =" . $record_id . " AND r.id_ho = " . $ho_id . " ");
        $subject = $billing_info[0]->fullname . " | Billing for " . $billing_info[0]->month_record . " " . $billing_info[0]->year_record . ".";
        $month_year = $billing_info[0]->month_record . " " . $billing_info[0]->year_record;
        $fullname = $billing_info[0]->fullname;
        $paid_amount = $billing_info[0]->paid_amount;
        $penalty = 0;
        $total = $paid_amount;
        if ($billing_info[0]->penalty != null) {
            $penalty = $billing_info[0]->penalty;
            $total = $paid_amount + $penalty;
        }
        $email = "Good Day, $fullname,<br><br>

        As part of our ongoing commitment to transparency and effective communication, we are pleased to provide you with your monthly billing statement for $month_year.<br><br>

        Below, you will find a detailed breakdown of your charges for your Homeowners Association Monthly Fee: <br><br>

        MONTHLY PAYMENT = $paid_amount<br>
        PENALTY =  $penalty <br>
        TOTAL PAYMENT = $total <br><br>

        If you have any questions or need assistance, feel free to contact the homeowners' association by sending concern through the website or by replying this email. * Please disregard this email if you are already paid<br><br>

        Best regards,<br>
        Hoasys Admin
    ";
        $this->email_sending_billing($billing_info[0]->email_add, $subject, $email);
    }
    public function download_dues_report()
    {
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $status = $this->input->post('status');
        $block = $this->input->post('block');
        $lot = $this->input->post('lot');
        $search = $this->input->post('search');
        $status_where = "";
        $search_where = "";
        $month_where = "";
        $block_where = "";
        $lot_where = "";

        if ($search != '') {
            // $search_where = 'AND (h.lname LIKE "%' .$search. '%" OR h.fname LIKE "%' .$search. '%" OR h.mname LIKE "%' .$search. '%")';
            $search_where = 'AND (CONCAT(h.fname, " ", h.mname, " ", h.lname) LIKE "%' . $search . '%" OR CONCAT(h.fname, " ", h.lname) LIKE "%' . $search . '%" OR h.lname LIKE "%' . $search . '%" OR h.fname LIKE "%' . $search . '%" OR h.mname LIKE "%' . $search . '%")';
        }

        if ($status != "All" && $status != " " && $status != null) {
            $status_where = ' AND r.status_record = "' . $status . '" ';
        }
        if ($month != "All") {
            $month_where = ' AND r.month_record = "' . $month . '" ';
        }
        if ($block != "All") {
            $block_where = ' AND h.block = "' . $block . '" ';
        }
        if ($lot != "All") {
            $lot_where = ' AND h.lot = "' . $lot . '" ';
        }

        $dues = $this->general_model->custom_query("
        SELECT 
        h.id_ho,h.lname,h.fname,h.mname,h.block,h.lot,h.village,h.email_add,h.contact_num, 
            r.*, 
            CONCAT(h.fname, ' ', h.mname, ' ', h.lname) as fullname,
            COALESCE(r.penalty, 0) + r.paid_amount as total_amount
        FROM 
            tbl_records r
            JOIN tbl_homeowner h ON h.id_ho = r.id_ho 
        WHERE 
            r.status_record != 'archived' 
            AND r.year_record = '" . $year . "'" . $status_where . $search_where . $month_where . $block_where . $lot_where);


        $all_paid = $this->general_model->custom_query('
    SELECT COUNT(r.id_record) as paid
    FROM tbl_records r, tbl_homeowner h
    WHERE r.status_record != "archived" AND h.id_ho = r.id_ho AND r.year_record = ' . $year . ' AND r.status_record = "paid" ' . $month_where . $search_where);
        $all_unpaid = $this->general_model->custom_query('
    SELECT COUNT(r.id_record) as unpaid
    FROM tbl_records r, tbl_homeowner h
    WHERE r.status_record != "archived" AND h.id_ho = r.id_ho AND r.year_record = ' . $year . ' AND r.status_record = "pending" ' . $month_where . $search_where);

        $all_dues = $this->general_model->custom_query('
    SELECT COUNT(r.id_record) as paid_unpaid
    FROM tbl_records r, tbl_homeowner h
    WHERE r.status_record != "archived" AND h.id_ho = r.id_ho AND r.year_record = ' . $year . ' ' . $month_where . $search_where);

        $this->load->library('PHPExcel', null, 'excel');
        // for ($sheet_loop = 0; $sheet_loop < 1; $sheet_loop++) {
        // $this->excel->createSheet(1);
        // }
        // ----------------------------------- Dues
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Homeowners');
        $this->excel->getActiveSheet()->setShowGridlines(false);
        $header_condition = [
            ['col' => 'A', 'id' => 'A7', 'title' => 'LAST NAME', 'width' => 20, 'data_id' => 'lname', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT],
            ['col' => 'B', 'id' => 'B7', 'title' => 'FIRST NAME', 'width' => 20, 'data_id' => 'fname', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT],
            ['col' => 'C', 'id' => 'C7', 'title' => 'MIDDLE NAME', 'width' => 20, 'data_id' => 'mname', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
            ['col' => 'D', 'id' => 'D7', 'title' => 'BLOCK', 'width' => 10, 'data_id' => 'block', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
            ['col' => 'E', 'id' => 'E7', 'title' => 'LOT', 'width' => 10, 'data_id' => 'lot', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT],
            ['col' => 'F', 'id' => 'F7', 'title' => 'VILLAGE', 'width' => 15, 'data_id' => 'village', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT],
            ['col' => 'G', 'id' => 'G7', 'title' => 'MONTH DUE', 'width' => 20, 'data_id' => 'month_record', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT],
            ['col' => 'H', 'id' => 'H7', 'title' => 'YEAR DUE', 'width' => 10, 'data_id' => 'year_record', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT],
            ['col' => 'I', 'id' => 'I7', 'title' => 'PAYMENT', 'width' => 15, 'data_id' => 'paid_amount', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT],
            ['col' => 'J', 'id' => 'J7', 'title' => 'PENALTY', 'width' => 15, 'data_id' => 'penalty', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT],
            ['col' => 'K', 'id' => 'K7', 'title' => 'TOTAL PAYMENT', 'width' => 20, 'data_id' => 'total_amount', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT],
            ['col' => 'L', 'id' => 'L7', 'title' => 'STATUS', 'width' => 10, 'data_id' => 'status_record', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT],
            ['col' => 'M', 'id' => 'M7', 'title' => 'RECEIPT NUMBER', 'width' => 20, 'data_id' => 'receipt_num', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT],
            ['col' => 'N', 'id' => 'N7', 'title' => 'DATE PAID', 'width' => 20, 'data_id' => 'date_updated', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT],

        ];
        $this->excel->getActiveSheet()->setCellValue('B2', "HOASYS HOMEOWNER DUES");
        $this->excel->getActiveSheet()->setCellValue('B3', "TOTAL HOMEOWNERS DUES: " . $all_dues[0]->paid_unpaid);
        $this->excel->getActiveSheet()->setCellValue('B4', "TOTAL HOMEOWNERS PAID: " . $all_paid[0]->paid);
        $this->excel->getActiveSheet()->setCellValue('B5', "TOTAL HOMEOWNERS UNPAID: " . $all_unpaid[0]->unpaid);
        $this->excel->getActiveSheet()->getStyle('B2:B2')->getFont()->setSize(20);
        // var_dump($header_condition);
        // exit();
        for ($excel_data_header_loop = 0; $excel_data_header_loop < count($header_condition); $excel_data_header_loop++) {
            $this->excel->getActiveSheet()->setCellValue($header_condition[$excel_data_header_loop]['id'], $header_condition[$excel_data_header_loop]['title']);
            $this->excel->getActiveSheet()->getStyle($header_condition[$excel_data_header_loop]['id'])->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle($header_condition[$excel_data_header_loop]['id'])->getFont()->getColor()->setRGB('FFFFFF');
            $this->excel->getActiveSheet()->getStyle($header_condition[$excel_data_header_loop]['id'])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            if ($header_condition[$excel_data_header_loop]['id'] != "D2") {
                $this->excel->getActiveSheet()->getStyle($header_condition[$excel_data_header_loop]['id'])->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('404040');
            }
            if ($header_condition[$excel_data_header_loop]['width'] > 0) {
                $this->excel->getActiveSheet()->getColumnDimension($header_condition[$excel_data_header_loop]['col'])->setWidth($header_condition[$excel_data_header_loop]['width']);
            }
        }
        $this->excel->getActiveSheet()->getStyle('D2:D2')->getFont()->setSize(20);
        $this->excel->getActiveSheet()->getStyle('D2:D3')->getFont()->getColor()->setRGB('000000');
        // $this->excel->getActiveSheet()->freezePane('E6');
        $rowNum = 8;
        $last_row = (count($dues) + $rowNum) - 1;
        if (count($dues) > 0) {
            foreach ($dues as $condition_rows) {
                for ($loop_header = 0; $loop_header < count($header_condition); $loop_header++) {
                    $this->excel->getActiveSheet()->setCellValue($header_condition[$loop_header]['col'] . $rowNum, $condition_rows->{$header_condition[$loop_header]['data_id']});
                    $this->excel->getActiveSheet()->getStyle($header_condition[$loop_header]['col'] . $rowNum . ":" . $header_condition[$loop_header]['col'] . $rowNum)->getAlignment()->setHorizontal($header_condition[$loop_header]['position']);
                }
                $rowNum++;
            }
        }
        $this->excel->getActiveSheet()->getStyle('A7:N7' . $this->excel->getActiveSheet()->getHighestRow())->applyFromArray(
            array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('rgb' => 'DDDDDD'),
                    ),
                ),
            )
        );

        $this->excel->getActiveSheet()->getProtection()->setPassword('password hoa_sys');
        $this->excel->getActiveSheet()->getProtection()->setSheet(true);
        $dateTime = $this->get_current_date_time();
        $filename = 'HOASYS_REPORT' . $dateTime['dateTime'] . '.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        // header('Set-Cookie: fileDownload=true; path=/');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        ob_end_clean();
        $objWriter->save('php://output');
        // Yes export 
        // activity log 
        $id_inserter = $this->session->userdata("id_admin");
        $admin_name = $this->session->userdata("fullname");
        $message_due = $admin_name . ' successfully exported due details of homeowners as excel file.';
        $this->activity_log('dues', $id_inserter, $message_due);
    }
    public function email_sending_billing($email_to, $subject, $email)
    {
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
        $message = $email;
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->set_crlf("\r\n");
        $this->email->from("ggn1cdo@gmail.com");
        $this->email->to($email_to);
        $this->email->subject($subject);
        $this->email->message($message);
        if ($this->email->send()) {
            // echo "Mail successful";
        } else {
            // echo "Sorry";
            // print_r($this->email->print_debugger());
        }
    }
    function send_batch_emails()
    {
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $year_where =  " AND r.year_record = '" . $year . "'";
        $month_where = " AND r.month_record = '" . $month . "'";
        $result = $this->general_model->custom_query("SELECT h.email_add,r.*, CONCAT(h.fname,' ', h.lname) as fullname FROM tbl_records r, tbl_homeowner h WHERE r.status_record != 'archived' AND h.id_ho = r.id_ho " . $year_where . $month_where);
        if (count($result) > 0) {
            $res = 1;
            $this->send_billing_dues_batch($result);
        } else {
            $res = 0;
        }
        echo json_encode($res);
    }
    public function send_billing_dues_batch($records)
    {
        // $records = $this->input->post('records'); // Assuming 'records' is an array of records
        $email_subject_prefix = "Billing for ";

        foreach ($records as $billing_info) {
            // $billing_info = $this->general_model->custom_query("SELECT h.*,r.*, CONCAT(h.fname,' ', h.mname, ' ', h.lname) as fullname, h.email_add FROM tbl_records r, tbl_homeowner h WHERE h.id_ho = r.id_ho AND r.id_record = {$record['id_record']} AND r.id_ho = {$record['id_ho']}");

            $subject = $billing_info->fullname . " | " . $email_subject_prefix . $billing_info->month_record . " " . $billing_info->year_record;
            $month_year = $billing_info->month_record . " " . $billing_info->year_record;
            $fullname = $billing_info->fullname;
            $paid_amount = $billing_info->paid_amount;
            $penalty = 0;
            $total = $paid_amount;

            if ($billing_info->penalty != null) {
                $penalty = $billing_info->penalty;
                $total = $paid_amount + $penalty;
            }

            $email_content = "Good Day, $fullname,<br><br>

        As part of our ongoing commitment to transparency and effective communication, we are pleased to provide you with your monthly billing statement for $month_year.<br><br>

        Below, you will find a detailed breakdown of your charges for your Homeowners Association Monthly Fee : <br><br>

        MONTHLY PAYMENT = $paid_amount<br>
        PENALTY =  $penalty <br>
        TOTAL PAYMENT = $total <br><br>

        If you have any questions or need assistance, feel free to contact the homeowners' association by sending concern through the website or by replying to this email. * Please disregard this email if you are already paid.<br><br>

        Best regards,<br>
        Hoasys Admin
    ";
            $this->email_sending_billing($billing_info->email_add, $subject, $email_content);
        }
    }
    public function send_billing_transaction_email($record_id, $ho_id, $stat, $admin_name)
    {
        // $record_id = $this->input->post('record_id');
        // $ho_id = $this->input->post('ho_id');
        $billing_info = $this->general_model->custom_query("SELECT h.id_ho,h.lname,h.fname,h.mname,h.block,h.lot,h.village,h.email_add,h.contact_num,h.status,r.*, CONCAT(h.fname,' ', h.lname) as fullname, h.email_add FROM tbl_records r, tbl_homeowner h WHERE h.id_ho = r.id_ho AND r.id_record =" . $record_id . " AND r.id_ho = " . $ho_id . " ");
        $month_year = $billing_info[0]->month_record . " " . $billing_info[0]->year_record;
        $fullname = $billing_info[0]->fullname;
        $paid_amount = $billing_info[0]->paid_amount;
        $penalty = 0;
        $total = $paid_amount;
        if ($billing_info[0]->penalty != null) {
            $penalty = $billing_info[0]->penalty;
            $total = $paid_amount + $penalty;
        }

        if ($stat == "paid") {
            $subject = $billing_info[0]->fullname . " | PAID Billing for " . $billing_info[0]->month_record . " " . $billing_info[0]->year_record . ".";

            $email = "Good Day, $fullname,<br><br>

            We are pleased to inform you that $admin_name updated your monthly billing statement for $month_year to $stat.<br><br>
    
            Below, you will find a detailed breakdown of your paid charges for your Homeowners Association Monthly Fee: <br><br>
    
            MONTHLY PAYMENT = $paid_amount<br>
            PENALTY =  $penalty <br>
            TOTAL PAYMENT = $total <br><br>
    
            If you have any questions or need assistance, feel free to contact the homeowners' association by sending concern through the website or approach your homeowner representative to personally ask your queries. <br><br>
    
            Best regards,<br>
            Hoasys Admin    ";
        } else if ($stat == "pending") {
            $subject = $billing_info[0]->fullname . " | REVERT TO UNPAID Billing for " . $billing_info[0]->month_record . " " . $billing_info[0]->year_record . ".";

            $email = "Good Day, $fullname,<br><br>

            We are pleased to inform you that $admin_name updated your monthly billing statement for $month_year to PENDING which means the Due Manager reverted your PAID status to UNPAID. Take note that this action should have your approval first.<br><br>
    
            Below, you will find a detailed breakdown of your unpaid charges for your Homeowners Association Monthly Fee: <br><br>
    
            MONTHLY PAYMENT = $paid_amount<br>
            PENALTY =  $penalty <br>
            TOTAL PAYMENT = $total <br><br>
    
            If this action is against your will, and you have any questions, feel free to contact the homeowners' association by sending concern through the website or approach your homeowner representative to personally ask your queries. <br><br>
    
            Best regards,<br>
            Hoasys Admin    ";
        } else if ($stat == "penalty") {
            $subject = $billing_info[0]->fullname . " | PENALTY for " . $billing_info[0]->month_record . " " . $billing_info[0]->year_record . " Billing.";

            $email = "Good Day, $fullname,<br><br>
        
            We are pleased to inform you that $admin_name has added/removed a PENALTY to your monthly billing statement for $month_year, as this billing has passed your due date or, if the penalty was removed, possibly due to an incorrect tagging of PENALTY.<br><br>
        
            Below, you will find a detailed breakdown of your unpaid charges with PENALTY for your Homeowners Association Monthly Fee: <br><br>
        
            MONTHLY PAYMENT = $paid_amount<br>
            PENALTY =  $penalty <br>
            TOTAL PAYMENT = $total <br><br>
        
            If this action is against your will, and you have any questions, feel free to contact the homeowners' association by sending concerns through the website or approach your homeowner representative to personally ask your queries. <br><br>
        
            Best regards,<br>
            Hoasys Admin    ";
        } else if ($stat == "delete") {
            $subject = $billing_info[0]->fullname . " | DELETION of " . $billing_info[0]->month_record . " " . $billing_info[0]->year_record . " Billing Record.";

            $email = "Good day, $fullname,<br><br>

            We are pleased to inform you that $admin_name has removed/deleted your monthly billing record for $month_year. Please report to us if this action causes any discrepancies.<br><br>
            
            Below, you will find a detailed breakdown of your deleted/removed charges for your Homeowners Association Monthly Fee:<br><br>
            
            MONTHLY PAYMENT = $paid_amount<br>
            PENALTY = $penalty <br>
            TOTAL PAYMENT = $total <br><br>
            
            If you have any questions, feel free to contact the homeowners' association by submitting concerns through the website or approach your homeowner representative to personally ask your queries.<br><br>
            
            Best regards,<br>
            Hoasys Admin ";
        } else if ($stat == "amount") {
            $subject = $billing_info[0]->fullname . " | CHANGED AMOUNT TO PAY for " . $billing_info[0]->month_record . " " . $billing_info[0]->year_record . " Billing.";

            $email = "Good Day, $fullname,<br><br>
        
            We are pleased to inform you that $admin_name has changed your AMOUNT TO PAY (*excluding penalties if any) of your monthly billing statement for $month_year.<br><br>
        
            Below, you will find a detailed breakdown of your unpaid charges with new AMOUNT TO PAY VALUE for your Homeowners Association Fee ONLY for this specific Month & Year: <br><br>
        
            MONTHLY PAYMENT = $paid_amount<br>
            PENALTY =  $penalty <br>
            TOTAL PAYMENT = $total <br><br>
        
            If this action is against your will, and you have any questions, feel free to contact the homeowners' association by sending concerns through the website or approach your homeowner representative to personally ask your queries. <br><br>
        
            Best regards,<br>
            Hoasys Admin    ";
        }

        $this->email_sending_billing($billing_info[0]->email_add, $subject, $email);
    }
    public function create_billing_all_year()
    {
        $year = $this->input->post('year');

        // Array of month names
        $months = array(
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        );

        foreach ($months as $month) {
            $res = $this->general_model->custom_query("SELECT billingID FROM tbl_billing_month_year WHERE month = '$month' AND year = '$year'");

            if (count($res) > 0) {
                $homeowner['billing_id'] = $res[0]->billingID;

                // Check if records exist for the current month and year
                $id_records_has_billing = $this->general_model->custom_query("SELECT id_ho FROM `tbl_records` WHERE year_record = '$year' AND month_record = '$month' ");

                if (count($id_records_has_billing) > 0) {
                    // Filter out existing homeowner IDs
                    $excludedIds = array_map(function ($item) {
                        return $item->id_ho;
                    }, $id_records_has_billing);

                    $sql_ex = "SELECT id_ho FROM tbl_homeowner WHERE status = 'active'";

                    if (!empty($excludedIds)) {
                        $sql_ex .= " AND id_ho NOT IN (" . implode(',', $excludedIds) . ")";
                    }

                    $tobe_added_homeowner = $this->general_model->custom_query($sql_ex);

                    if (count($tobe_added_homeowner) > 0) {
                        $id_ho_values = array_map(function ($obj) {
                            return $obj->id_ho;
                        }, $tobe_added_homeowner);

                        $id_ho_in_clause = implode(',', $id_ho_values);

                        $query_details = "SELECT h.id_ho, hm.id_ho_monthly, hm.monthly, hm.duedate
                      FROM tbl_homeowner h, tbl_homeowner_monthly hm
                      WHERE h.status = 'active' AND h.id_ho = hm.id_ho AND h.id_ho IN ($id_ho_in_clause)";

                        $result_ho_details = $this->general_model->custom_query($query_details);

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
                    }
                } else {
                    // Create records for all homeowners
                    $this->insert_all_homeowners($res[0]->billingID, $month, $year);
                }
            } else {
                // ADDS BILLING RECORD
                $data = array(
                    'month' => $month,
                    'year' =>  $year,
                );

                $billing_id = $this->general_model->insert_vals_last_inserted_id($data, "tbl_billing_month_year");
                $this->insert_all_homeowners($billing_id, $month, $year);
            }
        }

        echo json_encode("Success.");
    }
    public function send_batch_emails_cronjobs()
    {
        $date_time = $this->get_current_date_time();
        // Get current date and time
        $date_time_vals = $date_time["dateTime"];
        $current_date_time = strtotime($date_time_vals);

        // Get the next month and adjust the year if necessary
        $next_month = date('m', strtotime('+1 month', $current_date_time));
        $next_month_year = date('Y', strtotime('+1 month', $current_date_time));
        if ($next_month == '01') {
            $next_month_year++;
        }

        // Construct year and month conditions for the query
        $year_where =  " AND r.year_record = '" . $next_month_year . "'";
        $month_where = " AND r.month_record = '" . date('F', strtotime('+1 month', $current_date_time)) . "'";

        // Execute the query
        $result = $this->general_model->custom_query("SELECT h.email_add,r.*, CONCAT(h.fname,' ', h.lname) as fullname FROM tbl_records r, tbl_homeowner h WHERE r.status_record != 'archived' AND h.id_ho = r.id_ho " . $year_where . $month_where);

        // Check if there are results
        if (count($result) > 0) {
            $res = 1;
            $this->send_billing_dues_batch($result);
        } else {
            $res = 0;
        }

        // Output result as JSON
        // echo json_encode($res);
    }
    public function create_billing_all_cronjobs()
    {
        $date_time = $this->get_current_date_time();
        // Get current date and time
        $date_time_vals = $date_time["dateTime"];
        $current_date_time = strtotime($date_time_vals);

        // Get the next month and adjust the year if necessary
        $next_month = date('m', strtotime('+1 month', $current_date_time));
        $next_month_year = date('Y', strtotime('+1 month', $current_date_time));
        if ($next_month == '01') {
            $next_month_year++;
        }
        // Construct year and month conditions for the query
        $year = $next_month_year;
        $month = date('F', strtotime('+1 month', $current_date_time));

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
                } else {
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
        // echo json_encode($can_Add);
    }
    public function get_ledger_details()
    {
        $id = $this->input->post('id');
        $ledger_details = $this->general_model->custom_query('
            SELECT id_record, year_record, month_record, status_record, penalty, paid_amount, duedate_record 
            FROM `tbl_records` 
            WHERE status_record = "pending" AND id_ho = ' . $id . '
            ORDER BY STR_TO_DATE(CONCAT(year_record, " ", month_record), "%Y %M") DESC
        ');
        echo json_encode($ledger_details);
    }
    public function auto_set_bad_payers()
    {
        $bad_payers = $this->general_model->custom_query("SELECT id_ho FROM tbl_records WHERE status_record = 'pending' GROUP BY id_ho HAVING COUNT(id_ho) >= 3");

        // Extracting id_ho values from objects
        $bad_payer_ids = array_map(function ($payer) {
            return $payer->id_ho;
        }, $bad_payers);

        if (!empty($bad_payer_ids)) {
            // There are bad payers
            $string_payer_id = implode(',', $bad_payer_ids);
            $home['good_payer'] =  0;
            $home['can_vote'] =  0;
            $home['can_run'] =  0;
            $where_in = " id_ho IN (" . $string_payer_id . ") AND status = 'active' ";
            $this->general_model->update_vals($home, $where_in, "tbl_homeowner");
        }
    }
    public function auto_set_good_payers()
    {

        $good_payers = $this->general_model->custom_query("SELECT id_ho FROM tbl_records GROUP BY id_ho HAVING SUM(CASE WHEN status_record = 'pending' THEN 1 ELSE 0 END) <= 2");

        // Extracting id_ho values from objects
        $good_payer_ids = array_map(function ($payer) {
            return $payer->id_ho;
        }, $good_payers);

        if (!empty($good_payer_ids)) {
            // There are good payers
            $string_payer_id = implode(',', $good_payer_ids);
            $home['good_payer'] =  1;
            $home['can_vote'] =  1;
            $where_in = " id_ho IN (" . $string_payer_id . ") AND status = 'active' ";
            $this->general_model->update_vals($home, $where_in, "tbl_homeowner");
        }
    }
    public function auto_send_pending_reminders()
    {
        $bad_payers = $this->general_model->custom_query("SELECT r.id_ho,ho.mname,ho.lname,ho.fname,ho.email_add,ho.lot,ho.block,ho.village,ho.status FROM tbl_records r,tbl_homeowner ho WHERE r.id_ho = ho.id_ho AND r.status_record = 'pending' AND ho.status = 'active' GROUP BY r.id_ho HAVING COUNT(r.id_ho) >= 3");

        if (count($bad_payers) > 0) {
            // not empty 
            foreach ($bad_payers as $email_recipient) {
                // Get pending details 
                $id_ho = $email_recipient->id_ho;
                $email_ho = $email_recipient->email_add;
                $fullname = $email_recipient->fname . " " . $email_recipient->lname;
                $full_address = "Block " . $email_recipient->block . " ,Lot " . $email_recipient->lot . ", " . $email_recipient->village;
                $subject = "URGENT REMINDER : Pending Billings for " . $fullname;
                $pending_details_of_employee = $this->general_model->custom_query("SELECT r.id_ho,r.year_record,r.month_record,r.penalty,r.paid_amount,r.status_record,r.duedate_record,ho.lname,ho.fname,ho.mname,ho.status,ho.email_add,ho.block,ho.lot,ho.village FROM tbl_records r,tbl_homeowner ho WHERE r.status_record = 'pending' AND r.id_ho = " . $id_ho . " AND r.id_ho = ho.id_ho AND ho.status = 'active'");

                if (count($pending_details_of_employee) > 0) {
                    $outstanding_billings = "";
                    $total_billing_balance = 0; // Initialize total billing balance
                    // not empty
                    // Can send email

                    // This is to foreach each payment details
                    foreach ($pending_details_of_employee as $ho_details) {
                        //Create a table display here and store in this string $outstanding_billings

                        $total = 0;
                        $paid_amount = $ho_details->paid_amount;
                        $penalty_val = 0;

                        if ($ho_details->penalty != null) {
                            $penalty = $ho_details->penalty;
                            $total = $paid_amount + $penalty;
                            $penalty_val = $ho_details->penalty;
                        } else {
                            $total = $paid_amount;
                            $penalty_val = 0;
                        }

                        // Table format
                        $outstanding_billings .= "<tr>
                        <td>{$ho_details->month_record} {$ho_details->year_record}</td>
                        <td>{$ho_details->paid_amount}</td>
                        <td>{$penalty_val}</td>
                        <td>{$total}</td>
                        <td>{$ho_details->status_record}</td>
                        <td>{$ho_details->month_record} {$ho_details->duedate_record} {$ho_details->year_record}</td>
                    </tr>";

                        // Compute total billing balance
                        $total_billing_balance += $total;
                    }

                    // Format total_billing_balance to two decimal places
                    $total_billing_balance = number_format($total_billing_balance, 2);

                    $email_content = "Dear $fullname,<br><br>
                    As part of our ongoing effort to maintain the smooth operation and development of our community, we would like to bring to your attention that there are currently 3 or more pending billings associated with your property at $full_address.<br><br>

                    The outstanding billings of your Homeowners Association Monthly Fee are as follows: <br><br>
                    <table border='1'>
                        <tr>
                            <th>Month and Year Billing</th>
                            <th>Amount to Pay</th>
                            <th>Penalty</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Due date</th>
                        </tr>
                        $outstanding_billings
                    </table>
                    <br><br>
                    Total Outstanding Balance: $total_billing_balance
                    <br><br>
                    These payments are vital for the well-being and upkeep of our community, as they contribute directly to the maintenance of essential services and amenities. The due payment from each homeowner is the lifeline that ensures the smooth functioning of our village.<br><br>
                    To avoid any inconvenience or disruption in services, we kindly urge you to settle these outstanding payments at your earliest convenience. Your prompt attention to this matter is highly appreciated, as it helps in maintaining the overall harmony and development of our community.<br><br>
                    Thank you for your understanding and cooperation in this matter. Should you have any questions or concerns, feel free to reach out to our dedicated Homeowner officers or send a concern ticket through our website. Thank you!<br><br>

                    Best regards,<br>
                    Hoasys Admin
                ";
                    $this->email_sending_billing($email_ho, $subject, $email_content);
                }
            }
        }
    }
}
