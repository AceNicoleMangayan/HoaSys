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
        $data['title'] = $this->title;
        $role = $this->session->userdata("role");
        if ($role == "Admin") {
            $this->load_template_view('templates/admin/index', $data);
        } else {
            // redirect(base_url('homeowners'));
             redirect(base_url('dashboard'));
        }
    }

    public function admin_data()
    {
        $datatable = $this->input->post('datatable');
        $query['query'] = "SELECT id_admin, lname, fname, mname, username, password, role, status, addr_admin, contact_admin, email_admin FROM tbl_admin WHERE id_admin IS NOT NULL";
        $query['search']['append']="";
        $query['search']['total']="";
        if ($datatable['query']['searchField'] != '') {
            $keyword = explode(' ', $datatable['query']['searchField']);
            for ($x = 0; $x<count($keyword); $x++) {
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

        $lname = $this->input->post('lname');
        $fname = $this->input->post('fname');
        $mname = $this->input->post('mname');
        $password  = $this->input->post('password');
        // $authorization  = $this->input->post('authorization');
        $data = array(
            'lname'     => $lname,
            'fname'     => $fname,
            'mname'     => $mname,
            'username'  => $this->input->post('username'),
            'role'      => $this->input->post('role'),
            'addr_admin' => $this->input->post('address'),
            'email_admin' => $this->input->post('email'),
            'contact_admin' => $this->input->post('contact'),
            // 'authorized'=> $authorization == 'true' ? 1: 0
        );

        if ($password !== '') {
            $data['password'] = sha1(md5($password));
        }

        $if_update = ($action === 'update') ? " AND id_admin != $admin_ID" : '';

        $check_if_exist = $this->general_model->fetch_specific_val("COUNT(*) count", "lname = '$lname' AND fname = '$fname' AND mname = '$mname' $if_update", "tbl_admin")->count;

        if ($check_if_exist > 0) {
            $result = 'Admin already exist!';
        } else {
            if ($action === 'create') {
                $result = $this->general_model->insert_vals($data, "tbl_admin");
            } elseif ($action === 'update') {
                $result = $this->general_model->update_vals($data, "id_admin = $admin_ID", "tbl_admin");
            } else {
                $result = 0;
            }
        }

        echo json_encode($result);
    }
    public function update_status_admin(){
        $admin_ID = $this->input->post('admin_id');
        $data = array(
            'status'     => $this->input->post('status')
        );
        $result = $this->general_model->update_vals($data, "id_admin = $admin_ID", "tbl_admin");
    }
    public function check_authorization(){
        $password = sha1(md5($this->input->post('pass')));
        // $password = $this->input->post('password');
        $result = $this->general_model->fetch_specific_val("committee_ID, fname, lname, mname, username, role, authorized", "password = '$password' AND (authorized = 1 OR role = 'admin')", "tbl_committee");
        if(count($result) > 0){
            echo json_encode(array('status' => 'success', 'message' => 'Authorized!'));
        }else{
            echo json_encode(array('status' => 'error', 'message' => 'Incorrect Password or Not Authorized'));
        }
    }
    public function email_pass(){
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
}
