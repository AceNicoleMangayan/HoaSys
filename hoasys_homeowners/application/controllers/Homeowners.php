<?php

use Mpdf\Tag\P;

defined('BASEPATH') or exit('No direct script access allowed');
require_once(dirname(__FILE__) . "/General.php");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

class Homeowners extends General
{
    protected $title = 'Homeowners';
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
            // if ($role == "Admin") {
                $this->load_template_view('templates/homeowner/index', $data);
            // } else {
            //     redirect(base_url('participants'));
            // }
        } else {
            redirect(base_url('Login'));
        }
    }
    public function get_homeowners()
    {
        $datatable = $this->input->post('datatable');
        $query['search']['append'] = "";
        $query['search']['total'] = "";
        $where_name = "";
        $order = " lname ";

        $query['query'] = "SELECT id_ho, lname, fname,mname, block,lot,contact_num,email_add,status,username, village FROM `tbl_homeowner` WHERE id_ho != 0 " . $where_name;
        if ($datatable['query']['searchField'] != '') {
            $keyword = $datatable['query']['searchField'];
        $where = "(lname LIKE '%" . $keyword . "%' OR fname LIKE '%" . $keyword . "%' OR village LIKE '%" . $keyword . "%' OR username LIKE '%" . $keyword . "%')";
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
    public function save_homeowner()
    {
        $em = $this->input->post('email_address');
		$pass = $this->generate_password();
		$un = $this->input->post('username');
		$fullname = $this->input->post('fname')." ".$this->input->post('lname');
        $home['fname'] = $this->input->post('fname');
        $home['lname'] = $this->input->post('lname');
        $home['mname'] = $this->input->post('mname');
        $home['block'] = $this->input->post('block');
        $home['lot'] = $this->input->post('lot');
        $home['village'] = $this->input->post('village');
        $home['contact_num'] = $this->input->post('contact_number');
        $home['email_add'] = $this->input->post('email_address');
        $home['password'] = $pass;
        $home['username'] = $un;
        $home['status'] = "inactive";
        $home['email_add'] = $em;

        $newinserted_id = $this->general_model->insert_vals_last_inserted_id($home, "tbl_homeowner");
        $fullname = $this->input->post('fname')." ".$this->input->post('lname');
        $payment['monthly'] =$this->input->post('payment');
        $payment['id_ho'] = $newinserted_id;
        $payment['duedate'] = $this->input->post('duedate');
        $this->general_model->insert_vals($payment, "tbl_homeowner_monthly");
        $data['success'] = 1;
        echo json_encode($data);
        $this->email_sending($newinserted_id,$em,$pass,$un,$fullname);
    }
    // email sending back end
    public function email_sending($newinserted_id, $em,$pass,$username,$fullname)
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

        // $message = "Hi ".$fullname.", This is to inform you that we have created your account and these are your credentials: ( Username: ".$username." ) ( Password: ".$pass.". Kindly click the verification link to activate your account: " .anchor($ser . '/homeowners/verify_account/'.$newinserted_id, '   VERIFY MY ACCOUNT.');
		$message = "Hi $fullname,<br><br>

		Thank you for choosing our platform! We are excited to inform you that your account has been successfully created.<br><br>
		
		Here are your login credentials:<br>
		- Username: $username<br>
		- Password: $pass<br><br>
		
		To activate your account and start exploring the platform, please click the verification link below:<br>
		" . anchor($ser . '/homeowners/verify_account/' . $newinserted_id, 'VERIFY MY ACCOUNT') . "<br><br>
		
		If you have any questions or need assistance, feel free to contact our support team.<br><br>
		
		Best regards,<br>
		Hoasys Admin
		";
		

		$this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->set_crlf("\r\n");
        $this->email->from("ggn1cdo@gmail.com");
        $this->email->to($em);
        $this->email->subject("Hoasys Account Verification");
        $this->email->message($message);
        if ($this->email->send()) {
            echo "Mail successful";
        } else {
            echo "Sorry";
            print_r($this->email->print_debugger());
        }
}
    // public function email_sending($newinserted_id,$fullname,$email)
    // {
    //     $this->load->library('email');
    //     $ser = 'http://' . $_SERVER['SERVER_NAME'];
    //     $num = 2;
    //     $config = array(
    //         'protocol' => 'smtp',
    //         'smtp_host' => 'ssl://smtp.gmail.com',
    //         'smtp_timeout' => 30,
    //         'smtp_port' => 465,
    //         'smtp_user' => 'ggn1cdo@gmail.com',
    //         'smtp_pass' => 'asklaymjpayxhkyi',
    //         'charset' => 'utf-8',
    //         'mailtype' => 'html',
    //         'newline' => '\r\n'
    //     );

    //     $message = "Hi ".$fullname." ! In order for you to logged in to on your GGN1 account, Kindly click the verification link: " .anchor($ser . '/controlpanel/verify_account/'.$newinserted_id.'/'. $fullname . $num, 'VERIFY MY ACCOUNT');
    //     $this->email->initialize($config);
    //     $this->email->set_newline("\r\n");
    //     $this->email->set_crlf("\r\n");
    //     $this->email->from("ggn1cdo@gmail.com");
    //     $this->email->to("chariseviancabsuan@gmail.com");
    //     $this->email->subject("GGN1 Account Verification");
    //     $this->email->message($message);
    //     if ($this->email->send()) {
    //         echo "Mail successful";
    //     } else {
    //         echo "Sorry";
    //         print_r($this->email->print_debugger());
    //     }
    // }
    public function verify_account($id)
    {
        $data['status'] = "active";
        $where = "id_ho = " . $id;
        $this->general_model->update_vals($data, $where, 'tbl_homeowner');
        $this->load->view('templates/verified');
    }
    public function update_current_Active($id)
    {
        // Update the current active 
        $where_ac = "prize_ID = " . $id;
        $Up_ac['isActive'] = null;
        $this->general_model->update_vals($Up_ac, $where_ac, 'tbl_prize');
    }
    public function update_active()
    {
        $pid = $this->input->post('prize');
        $stat = $this->input->post('active');
        $major = $this->input->post('major');
        if ($stat == 1) {
            if ($major == 1) {
                $major_w = " AND major = 1";
            } else {
                $major_w = " AND major IS NULL ";
            }
            $find_active = $this->general_model->custom_query('SELECT prize_ID FROM `tbl_prize` WHERE isActive = 1 ' . $major_w);
            if ($find_active != null || $find_active != "") {
                // Update the current active 
                $this->update_current_Active($find_active[0]->prize_ID);
            }
        } else {
            $stat = null;
        }
        $where_stat = "prize_ID = " . $pid;
        $Up_stat['isActive'] = $stat;
        $this->general_model->update_vals($Up_stat, $where_stat, 'tbl_prize');
    }
    public function update_registered()
    {
        $pid = $this->input->post('prize');
        $reg = $this->input->post('reg');
        if ($reg != 1 || $reg == "") {
            $Up_reg['registered'] = null;
        } else {
            $Up_reg['registered'] = $reg;
        }
        $where_reg = "prize_ID = " . $pid;

        $this->general_model->update_vals($Up_reg, $where_reg, 'tbl_prize');
    }
    public function update_major()
    {
        $pid = $this->input->post('prize');
        $reg = $this->input->post('major');
        $seq = $this->input->post('sequence');
        $where = "";
        if ($reg != 1 || $reg == "") {
            $Up_reg['major'] = null;
            $where = " AND major IS NULL ";
            $old_major = " AND major = 1 ";
        } else {
            $Up_reg['major'] = $reg;
            $where = " AND major = 1 ";
            $old_major = " AND major = 0 ";
        }
        $Up_reg['isActive'] = null;
        $where_reg = "prize_ID = " . $pid;

        // update sequence previous
        $succeeding_prizes = $this->general_model->custom_query('SELECT sequence, prize_ID FROM `tbl_prize` WHERE sequence > ' . $seq . '' . $old_major);
        if (count($succeeding_prizes) > 0) {
            $incre_prizes = [];
            foreach ($succeeding_prizes as $succeeding_prizes_row) {
                array_push($incre_prizes, [
                    "prize_ID" => $succeeding_prizes_row->prize_ID,
                    "sequence" => (int) $succeeding_prizes_row->sequence - 1,
                ]);
            }
            if (count($incre_prizes) > 0) {
                $this->general_model->batch_update($incre_prizes, 'prize_ID', 'tbl_prize');
            }
        }
        $query = $this->general_model->custom_query('SELECT MAX(sequence) as seq FROM tbl_prize WHERE prize_ID != 0 ' . $where);
        if (count($query) != 0) {
            $Up_reg['sequence'] = $query[0]->seq + 1;
        } else {
            $Up_reg['sequence'] = 1;
        }
        $this->general_model->update_vals($Up_reg, $where_reg, 'tbl_prize');
    }
    public function fetch_recordsUpdate()
    {
        $pid = $this->input->post('pid');
        $query = $this->general_model->custom_query("SELECT prize_ID,name,winners,sequence,dateTimeSpin,major FROM `tbl_prize` WHERE prize_ID = " . $pid);
        echo json_encode($query);
    }
    public function update_prize()
    {
        $pid = $this->input->post('prize');
        $Up_reg['name'] = $this->input->post('name');
        $Up_reg['winners'] = $this->input->post('winners');
        $sequence = $this->input->post('sequence');
        $major_up = $this->input->post('major');
        $where_reg = "prize_ID = " . $pid;
        $where_major = "";
        if ($major_up == "major") {
            $where_major = " AND major = 1 ";
        } else {
            $where_major = " AND major IS NULL ";
        }

        $current_prize = $this->general_model->custom_query('SELECT sequence, prize_ID FROM `tbl_prize` WHERE prize_ID = ' . $pid);
        if ($current_prize[0]->sequence < $sequence) {
            $succeeding_prizes = $this->general_model->custom_query('SELECT sequence, prize_ID FROM `tbl_prize` WHERE sequence > ' . $current_prize[0]->sequence . ' AND sequence <= ' . $sequence . ' ' . $where_major);
            if (count($succeeding_prizes) > 0) {
                $decre_prizes = [];
                foreach ($succeeding_prizes as $succeeding_prizes_row) {
                    array_push($decre_prizes, [
                        "prize_ID" => $succeeding_prizes_row->prize_ID,
                        "sequence" => (int) $succeeding_prizes_row->sequence - 1,
                    ]);
                }
                if (count($decre_prizes) > 0) {
                    $this->general_model->batch_update($decre_prizes, 'prize_ID', 'tbl_prize');
                }
            }
        } else if ($current_prize[0]->sequence > $sequence) {
            $succeeding_prizes = $this->general_model->custom_query('SELECT sequence, prize_ID FROM `tbl_prize` WHERE sequence < ' . $current_prize[0]->sequence . ' AND sequence >= ' . $sequence . ' ' . $where_major);
            if (count($succeeding_prizes) > 0) {
                $incre_prizes = [];
                foreach ($succeeding_prizes as $succeeding_prizes_row) {
                    array_push($incre_prizes, [
                        "prize_ID" => $succeeding_prizes_row->prize_ID,
                        "sequence" => (int) $succeeding_prizes_row->sequence + 1,
                    ]);
                }
                if (count($incre_prizes) > 0) {
                    // var_dump($incre_prizes);
                    // exit();
                    $this->general_model->batch_update($incre_prizes, 'prize_ID', 'tbl_prize');
                }
            }
        }
        // $prev_prizes = $this->general_model->custom_query('SELECT sequence, prize_ID FROM `tbl_prize` WHERE sequence <= ' . $sequence . '' . $where_major);
        // if (count($succeeding_prizes) > 0) {
        //     $incre_prizes = [];
        //     foreach ($succeeding_prizes as $succeeding_prizes_row) {
        //         array_push($incre_prizes, [
        //             "prize_ID" => $succeeding_prizes_row->prize_ID,
        //             "sequence" => (int) $succeeding_prizes_row->sequence + 1,
        //         ]);
        //     }
        //     if (count($incre_prizes) > 0) {
        //         // var_dump($incre_prizes);
        //         // exit();
        //         $this->general_model->batch_update($incre_prizes, 'prize_ID', 'tbl_prize');
        //     }
        // }
        // if (count($prev_prizes) > 0) {
        //     $decre_prizes = [];
        //     foreach ($prev_prizes as $prev_prizes_row) {
        //         array_push($decre_prizes, [
        //             "prize_ID" => $prev_prizes_row->prize_ID,
        //             "sequence" => (int) $prev_prizes_row->sequence - 1,
        //         ]);
        //     }
        //     if (count($decre_prizes) > 0) {
        //         // var_dump($decre_prizes);
        //         // exit();
        //         $this->general_model->batch_update($decre_prizes, 'prize_ID', 'tbl_prize');
        //     }
        // }
        // $seq_res = $this->general_model->custom_query('SELECT sequence, prize_ID FROM `tbl_prize` WHERE sequence = ' . $sequence . $where_major);

        // if (count($seq_res) != 0) {
        //     // Exchange
        //     $new = $this->general_model->custom_query('SELECT sequence, prize_ID FROM `tbl_prize` WHERE prize_ID = ' . $pid);
        //     $new_max = $new[0]->sequence;

        //     $where = "prize_ID = " . $seq_res[0]->prize_ID;
        //     $Up['sequence'] = $new_max;
        //     $this->general_model->update_vals($Up, $where, 'tbl_prize');
        // }
        $Up_reg['sequence']  = $this->input->post('sequence');
        $this->general_model->update_vals($Up_reg, $where_reg, 'tbl_prize');
    }
    public function reset_winners()
    {
        $pid = $this->input->post('prize');
        $where_reg = "prize_ID = " . $pid;
        $Up_reg['dateTimeSpin'] = null;
        $this->general_model->update_vals($Up_reg, $where_reg, 'tbl_prize');

        $winners = $this->general_model->custom_query('SELECT prizeWinner_ID,participant_ID FROM tbl_prize_winners WHERE prize_ID = ' . $pid);
        if (count($winners) != 0) {
            // Update
            $win_part = implode(',', array_column($winners, 'participant_ID'));
            $where_part = 'participant_ID IN (' . $win_part . ')';
            $Up_parti['winner'] = null;
            $this->general_model->update_vals($Up_parti, $where_part, 'tbl_participant');
            //remove
            $winids = implode(',', array_column($winners, 'prizeWinner_ID'));
            $where_del = 'prizeWinner_ID IN (' . $winids . ')';
            $this->general_model->delete_vals($where_del, 'tbl_prize_winners');
        }
    }
    public function get_prize_option()
    {
        $major = $this->input->post("major");
        $where_major = "";
        if ((int) $major) {
            $where_major = "AND major = 1";
        } else {
            $where_major = "AND major IS NULL";
        }
        $prizes = $this->general_model->custom_query("SELECT prize_ID, name, winners, sequence, major, isActive FROM tbl_prize WHERE dateTimeSpin IS NULL $where_major ORDER BY sequence ASC");
        echo json_encode($prizes);
    }
    public function remove_prize()
    {
        $pid = $this->input->post('prize');
        $seq = $this->input->post('sequence');
        $major = $this->input->post('major');
        if ($major == 1) {
            $prize['major'] = $major;
            $where_major = " AND major = 1 ";
        } else {
            $where_major = " AND major IS NULL ";
        }
        // update sequence 
        $succeeding_prizes = $this->general_model->custom_query('SELECT sequence, prize_ID FROM `tbl_prize` WHERE sequence > ' . $seq . '' . $where_major);
        if (count($succeeding_prizes) > 0) {
            $incre_prizes = [];
            foreach ($succeeding_prizes as $succeeding_prizes_row) {
                array_push($incre_prizes, [
                    "prize_ID" => $succeeding_prizes_row->prize_ID,
                    "sequence" => (int) $succeeding_prizes_row->sequence - 1,
                ]);
            }
            if (count($incre_prizes) > 0) {
                $this->general_model->batch_update($incre_prizes, 'prize_ID', 'tbl_prize');
            }
        }
        // removal record 
        $where_remove = "prize_ID = $pid";
        $this->general_model->delete_vals($where_remove, 'tbl_prize');
    }
    public function get_homeowner_details(){
        $id = $this->input->post('id');
        $homeowners_info = $this->general_model->custom_query('SELECT h.id_ho,h.lname,h.fname,h.mname,h.lname,h.block,h.lot,h.contact_num,h.email_add,h.status,h.password,h.username,h.village,p.monthly,p.duedate,p.id_ho_monthly FROM tbl_homeowner h, tbl_homeowner_monthly p WHERE h.id_ho = p.id_ho AND h.id_ho = '. $id);
        echo json_encode($homeowners_info);
    }
    public function update_homeowner(){
        $id = $this->input->post('id');
        $home['fname'] = $this->input->post('fname');
        $home['lname'] = $this->input->post('lname');
        $home['mname'] = $this->input->post('mname');
        $home['block'] = $this->input->post('block');
        $home['lot'] = $this->input->post('lot');
        $home['village'] = $this->input->post('village');
        $home['username'] = $this->input->post('username');
        $home['contact_num'] = $this->input->post('contact_number');
        $home['email_add'] = $this->input->post('email_address');
        $home['password'] = $this->input->post('password');
        $home['updated_by'] = $this->session->userdata("id_admin");
        $home['status'] =  $this->input->post('status');
        $pay['monthly'] = $this->input->post('monthly');
        $pay['duedate'] = $this->input->post('due');
        $pay['updated_by'] = $this->session->userdata("id_admin");
        $this->general_model->update_vals($home, "id_ho = $id", "tbl_homeowner");
        $this->general_model->update_vals($pay, "id_ho = $id", "tbl_homeowner_monthly");
    }
	public function generate_password() {
        $this->load->helper('string');
        $password = random_string('alnum', 5);
        return $password;
    }
}
