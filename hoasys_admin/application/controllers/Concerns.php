<?php

use Mpdf\Tag\P;

defined('BASEPATH') or exit('No direct script access allowed');
require_once(dirname(__FILE__) . "/General.php");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

class Concerns extends General
{
    protected $title = 'Concerns';
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
            if ($role == "election" || $role == "due" || $role == "officer") {
                $data['title'] = $this->title;
                $this->load_template_view('templates/concerns/concerns', $data);
            } else {
                // redirect(base_url('dashboard'));
                redirect(base_url('dashboard'));
            }
        } else {
            redirect(base_url('Login'));
        }
    }
        public function get_concerns()
    {
        $datatable = $this->input->post('datatable');
        $query['search']['append'] = "";
        $query['search']['total'] = "";
        $status = $datatable['query']['status'];
        $where_name = "";
        $stat_where = "";
        $order = " ORDER BY con.desc_concern DESC";
    
        if (!empty($status) && trim($status) !== 'All') {
            $stat_where = " AND con.status_concern = '" . $status . "'";
        }
    
        $query['query'] = "
            SELECT 
                con.id_concern, 
                con.title_concern, 
                con.desc_concern, 
                con.datesent_concern, 
                con.status_concern, 
                con.id_admin, 
                con.id_ho, 
                con.isReceivedEmail, 
                ho.lname AS ho_lname, 
                ho.fname AS ho_fname, 
                ho.email_add AS ho_email_add, 
                ad.lname AS ad_lname, 
                ad.fname AS ad_fname 
            FROM 
                tbl_concern con 
            INNER JOIN 
                tbl_homeowner ho ON con.id_ho = ho.id_ho 
            LEFT JOIN 
                tbl_admin ad ON con.id_admin = ad.id_admin 
            WHERE 
                1=1 " . $stat_where;
    
        if ($datatable['query']['searchField'] != '') {
            $keyword = $datatable['query']['searchField'];
            $where = "(
                con.title_concern LIKE '%" . $keyword . "%' OR 
                con.desc_concern LIKE '%" . $keyword . "%' OR 
                ho.fname LIKE '%" . $keyword . "%' OR 
                ho.lname LIKE '%" . $keyword . "%'
            )";
            $query['search']['append'] .= " AND ($where)";
        }
    
        $page = $datatable['pagination']['page'];
        $perpage = $datatable['pagination']['perpage'];
    
        if (isset($datatable['sort'])) {
            $sort = $datatable['sort']['sort'];
            $field = $datatable['sort']['field'];
            $order = " ORDER BY $field $sort";
        }
    
        $offset = ($page - 1) * $perpage;
        $limit = " LIMIT $offset, $perpage";
        $query['query'] .= $query['search']['append'] . $order . $limit;
    
        $data = $this->general_model->custom_query($query['query']);
        $total = count($this->general_model->custom_query("SELECT COUNT(*) AS count FROM tbl_concern con WHERE 1=1 " . $stat_where));
    
        $pages = ceil($total / $perpage);
    
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
    public function get_concern_details()
    {
        $id = $this->input->post('id');

        // Fetch concern files
        $attach = $this->general_model->custom_query('SELECT id_concern_file, id_concern, file_link FROM `tbl_concern_files` WHERE id_concern = ' . $id);

        // Fetch concern information
        $ann_info = $this->general_model->custom_query('SELECT con.id_concern, con.title_concern, con.desc_concern, con.datesent_concern, con.status_concern, con.id_admin, con.id_ho, con.isReceivedEmail,con.date_solved,con.email_reply_content, ho.lname, ho.fname, ho.email_add FROM tbl_concern con, tbl_homeowner ho WHERE con.id_ho = ho.id_ho AND con.id_concern =' . $id);

        // Create an associative array to hold both sets of data
        $result = [
            'attach' => $attach,
            'ann_info' => $ann_info,
        ];

        // Encode the associative array as JSON and echo the result
        echo json_encode($result);
    }
    public function send_concern_reply()
    {
        $email_to = $this->input->post('email_to');
        $subject = $this->input->post('subject');
        $email = $this->input->post('email_content');
        $concern_id = $this->input->post('concern_id');
        // Update concern 
        $em['isReceivedEmail'] = 1;
        $em['email_reply_content'] = $email;
        $this->general_model->update_vals($em, "id_concern = $concern_id", "tbl_concern");
        // Send email
        $this->email_sending_concern_reply($email_to, $subject, $email);
    }
    // email sending back end
    public function email_sending_concern_reply($email_to, $subject, $email)
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
            echo "Mail successful";
        } else {
            echo "Sorry";
            print_r($this->email->print_debugger());
        }
    }
    public function change_concern_status()
    {
        $date_time = $this->get_current_date_time();
        $id = $this->input->post('id');
        $stat = $this->input->post('status');
        $admin_name = $this->session->userdata("fullname");
        $stat = $this->input->post('status');
        $em['status_concern'] = $this->input->post('status');
        $em['id_admin'] = $this->session->userdata("id_admin");
        $em['date_solved'] = $date_time["dateTime"];
        $this->general_model->update_vals($em, "id_concern = $id", "tbl_concern");
        $message = $admin_name . ' successfully changed status of concern #000000' .$id. ' to '.$stat.'.';
        $this->activity_log('concerns', $id, $message);
        // send email 
        $this->send_status_email($id,$stat);
    }
    public function send_status_email($con_id,$stat)
    {
        $con_info = $this->general_model->custom_query("SELECT con.title_concern,con.status_concern,con.id_ho, CONCAT(ho.fname,' ', ho.lname) as fullname,ho.email_add FROM tbl_concern con, tbl_homeowner ho WHERE con.id_ho = ho.id_ho AND con.id_concern = ".$con_id);
        $subject =  $con_info[0]->fullname . " | Concern Notification for " .$con_info[0]->title_concern. ".";
        $fullname = $con_info[0]->fullname;


        $email = "Good Day, $fullname,<br><br>

        We are pleased to inform you that your concern has been tagged as '$stat'.<br><br>

        If you have any questions or more clarifications about this concern, feel free to reply within this email thread.<br><br>

        Best regards,<br>
        Hoasys Admin
    ";
        $this->email_sending_notif($con_info[0]->email_add, $subject, $email);
    }
    public function email_sending_notif($email_to, $subject, $email)
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
}