<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(dirname(__FILE__) . "/General.php");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Content-Type");


class Admin extends General
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
        // $data['title'] = $this->title;
        // $role = $this->session->userdata("role");
        // if ($role == "Admin") {
        // $this->load_template_view('templates/admin/index', $data);
        // } else {
        // redirect(base_url('homeowners'));
        //  redirect(base_url('dashboard'));
        // }
        if ($this->session->userdata("id_admin")) {
            $date_time = $this->get_current_date_time();
            $data["date_time"] = $date_time["dateTime"];
            $role = $this->session->userdata("role");
            if ($role == "admin") {
                $data['title'] = $this->title;
                $this->load_template_view('templates/admin/index', $data);
            } else {
                // redirect(base_url('dashboard'));
                redirect(base_url('dashboard'));
            }
        } else {
            redirect(base_url('Login'));
        }
    }

    public function admin_data()
    {
        $datatable = $this->input->post('datatable');
        $query['query'] = "SELECT id_admin, lname, fname, mname, username, password, role, status, addr_admin, contact_admin, email_admin FROM tbl_admin WHERE id_admin IS NOT NULL";
        $query['search']['append'] = "";
        $query['search']['total'] = "";
        if ($datatable['query']['searchField'] != '') {
            $keyword = explode(' ', $datatable['query']['searchField']);
            for ($x = 0; $x < count($keyword); $x++) {
                $query['search']['append'] .= " AND (fname LIKE '%$keyword[$x]%' OR lname LIKE '%$keyword[$x]%' OR mname LIKE '%$keyword[$x]%' OR username LIKE '%$keyword[$x]%' OR role LIKE '%$keyword[$x]%')";
                $query['search']['total'] .= " AND (fname LIKE '%$keyword[$x]%' OR lname LIKE '%$keyword[$x]%' OR mname LIKE '%$keyword[$x]%' OR username LIKE '%$keyword[$x]%' OR role LIKE '%$keyword[$x]%')";
            }
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
            $order = $field ? " ORDER BY  " . $field : '';
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
    public function admin_action()
    {
        $admin_ID = $this->input->post('admin_iD');
        $action = $this->input->post('action');
        $admin_name = $this->session->userdata("fullname");

        $lname = $this->input->post('lname');
        $fname = $this->input->post('fname');
        $mname = $this->input->post('mname');
        $password  = $this->input->post('password');
        $data = array(
            'lname'     => $lname,
            'fname'     => $fname,
            'mname'     => $mname,
            'username'  => $this->input->post('username'),
            'role'      => $this->input->post('role'),
            'addr_admin' => $this->input->post('address'),
            'email_admin' => $this->input->post('email'),
            'contact_admin' => $this->input->post('contact'),
        );

        if ($password !== '') {
            $data['password'] = sha1(md5($password));
        }

        $if_update = ($action === 'update') ? " AND id_admin != $admin_ID" : '';

        // $check_if_exist = $this->general_model->fetch_specific_val("COUNT(*) count", "lname = '$lname' AND fname = '$fname' AND mname = '$mname' $if_update", "tbl_admin")->count;
        $user_name = $this->input->post('username');
        $em = $this->input->post('email');
        // $check_username = $this->general_model->custom_query('SELECT id_admin FROM `tbl_admin` WHERE username = "' . $user_name . '"');

        // } else {
        if ($action === 'create') {
            //create admin
            // New condition 
              // Evaluate if multiple username / password
              $un_allowed = $this->check_username_allowed($user_name);
              $em_allowed = $this->check_email_allowed($em);
              if($un_allowed == 0){
                  // means not allowed 
                  $result = 3;
              }else if($em_allowed == 0){
                  // means not allowed 
                  $result = 4;
              }else{
                $result = 1;
                $message = $admin_name . ' successfully added ' . $fname . ' ' . $lname . ' as one of the system official administrators.';
                $id_admin_last_insert = $this->general_model->insert_vals_last_inserted_id($data, "tbl_admin");
                $this->activity_log('admin', $id_admin_last_insert, $message);
              }  
        } elseif ($action === 'update') {
            //update admin
            // $result = 1;
            // evaluate 
            $un_allowed = $this->check_username_allowed_admin($user_name,$admin_ID);
            $em_allowed = $this->check_email_allowed_admin($em,$admin_ID);
            if($un_allowed == 0){
                // means not allowed 
                $result = 3;
            }else if($em_allowed == 0){
                // means not allowed 
                $result = 4;
            }else{
                $result = 1;
                $message = $admin_name . ' successfully updated ' . $fname . ' ' . $lname . ' info.';
                $this->activity_log('admin', $admin_ID, $message);
                $result = $this->general_model->update_vals($data, "id_admin = $admin_ID", "tbl_admin");
            }
        } else {
            $result = 0;
        }
        // }
        echo json_encode($result);
    }
    public function update_status_admin()
    {
        $admin_ID = $this->input->post('admin_id');
        $admin_name = $this->session->userdata("fullname");
        $data = array(
            'status'     => $this->input->post('status')
        );
        $result = $this->general_model->update_vals($data, "id_admin = $admin_ID", "tbl_admin");
        $name = $this->get_admin_fullname($admin_ID);
        $message = $admin_name . ' successfully set status of Admin ' . $name[0]->fullname . ' to ' . $this->input->post('status') . '.';
        $this->activity_log('admin', $admin_ID, $message);
    }
    public function check_authorization()
    {
        $password = sha1(md5($this->input->post('pass')));
        // $password = $this->input->post('password');
        $result = $this->general_model->fetch_specific_val("committee_ID, fname, lname, mname, username, role, authorized", "password = '$password' AND (authorized = 1 OR role = 'admin')", "tbl_committee");
        if (count($result) > 0) {
            echo json_encode(array('status' => 'success', 'message' => 'Authorized!'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Incorrect Password or Not Authorized'));
        }
    }
    public function email_pass()
    {
        $this->email->from('banaagvianca@gmail.com', 'Cha cha');
        $this->email->to('chariseviancabsuan@gmail.com');
        $this->email->subject('Verify your email address');
        $this->email->message("Message sample");
        if ($this->email->send()) {
            echo 'Email sent successfully';
        } else {
            show_error($this->email->print_debugger());
        }
    }
    public function homeowners_to_admin_select(){
        $election = $this->input->post('election');
        $where_elect = "";
        if (isset($election) && trim($election) != "") {
            $where_elect = "AND (fname LIKE '%$election%' OR lname LIKE '%$election%')";
        }
        $election = $this->general_model->custom_query("SELECT id_ho as id,CONCAT(lname,', ',fname,' ',mname) as text FROM `tbl_homeowner` WHERE status = 'active' $where_elect ORDER BY lname ASC ");
        $data["results"] = $election;
        echo json_encode($data);
    }
    public function check_username_allowed($username){
        // $username = "charrot";
        $CanSave = 0;
        $checker = $this->general_model->custom_query('SELECT id_admin FROM `tbl_admin` WHERE username = "'.$username.'"');
        if(count($checker) > 0){
            $CanSave = 0;
        }else{
            $CanSave = 1;
        }
        return $CanSave;
    }
    public function check_email_allowed($email_add){
        // $email_add = "ricajhkjh@mail.com";
        $CanSave = 0;
        $checker = $this->general_model->custom_query('SELECT id_admin FROM `tbl_admin` WHERE email_admin = "'.$email_add.'"');
        if(count($checker) > 0){
            $CanSave = 0;
        }else{
            $CanSave = 1;
        }
        return $CanSave;
    }
    public function check_username_allowed_admin($username,$admin_id){
        // $username = "charrot";
        $CanSave = 0;
        $checker = $this->general_model->custom_query('SELECT id_admin FROM `tbl_admin` WHERE username = "'.$username.'" AND id_admin != '.$admin_id.'');
        if(count($checker) > 0){
            $CanSave = 0;
        }else{
            $CanSave = 1;
        }
        return $CanSave;
    }
    public function check_email_allowed_admin($email_add,$admin_id){
        // $email_add = "ricajhkjh@mail.com";
        $CanSave = 0;
        $checker = $this->general_model->custom_query('SELECT id_admin FROM `tbl_admin` WHERE email_admin = "'.$email_add.'" AND id_admin != '.$admin_id.'');
        if(count($checker) > 0){
            $CanSave = 0;
        }else{
            $CanSave = 1;
        }
        return $CanSave;
    }
    public function admin_ho_saving()
    {
        $result = 0;
        $admin_name = $this->session->userdata("fullname");
        $ho_id = $this->input->post('ho_id');
        $un = $this->input->post('username');
        $em = $this->input->post('email');
        $ho_details = $this->general_model->custom_query("SELECT id_ho,lname,fname,mname,block,lot,village,contact_num,is_admin FROM `tbl_homeowner` WHERE id_ho =".$ho_id."");
       
    //    check if this is already added 
        if($ho_details[0]->is_admin == 1){
            // already added 
            $result = 2;
        }else{
            // Evaluate if multiple username / password
            $un_allowed = $this->check_username_allowed($un);
            $em_allowed = $this->check_email_allowed($em);
            if($un_allowed == 0){
                // means not allowed 
                $result = 3;
            }else if($em_allowed == 0){
                // means not allowed 
                $result = 4;
            }else{
                // finally can save
                $password  = $this->input->post('password');
                $data = array(
                    'lname'     => $ho_details[0]->lname,
                    'fname'     => $ho_details[0]->fname,
                    'mname'     => $ho_details[0]->mname,
                    'username'  => $un,
                    'role'      => $this->input->post('role'),
                    'addr_admin' =>"Block ".$ho_details[0]->block.", Lot ".$ho_details[0]->lot.", ".$ho_details[0]->village,
                    'email_admin' => $em,
                    'contact_admin' => $ho_details[0]->contact_num,
                );
        
                if ($password !== '') {
                    $data['password'] = sha1(md5($password));
                }

                $ho_det['is_admin'] = 1;
                $where_ho_add = "id_ho = " . $ho_details[0]->id_ho;
                $this->general_model->update_vals($ho_det, $where_ho_add, 'tbl_homeowner');  

                $result = 1;
                $message = $admin_name . ' successfully added ' . $ho_details[0]->fname . ' ' . $ho_details[0]->lname . ' as one of the system official administrators.';
                $id_admin_last_insert = $this->general_model->insert_vals_last_inserted_id($data, "tbl_admin");
                $this->activity_log('admin', $id_admin_last_insert, $message);
            }
        }
        echo json_encode($result);
    }
}
