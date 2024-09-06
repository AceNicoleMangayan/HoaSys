<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once(dirname(__FILE__) . "/General.php");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Content-Type");


class Login extends General
{
    protected $title = 'Login';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = $this->title;

        if ($this->session->userdata("id_ho")) {
            $this->check_login_access();
        } else {
            $this->load->view('templates/login/login', $data);
        }
    }

    public function login_authentication()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password'); 
        // $password = $this->input->post('password');
        $result = $this->general_model->fetch_specific_val("*", "username = '$email' AND password = '$password' AND status = 'active' ", "tbl_homeowner");
        // var_dump($result);
        // echo $email+" "+$password;
        if ($result != null) {
            $status = 'Success';
            $role = '';
            // if ($result->role === 'admin') {
            //     $role = 'Admin';
            // } elseif ($result->role === 'regular') {
            //     $role = 'Regular';
            // } elseif ($result->role === 'vct') {
            //     $role = 'VCT';
            // } 
            $set_session = array(
                'fullname' => $result->fname .' '. $result->lname,
                'id_ho'   => $result->id_ho,
                'username' => $result->username,
                'good_payer' => $result->good_payer,
            );
            $this->session->set_userdata($set_session);
        } else {
            $status = 'Failed';
        }
        echo json_encode($status);
    }

    public function check_login_access()
    {
        // if ($this->session->userdata("role") == "Admin") {
        //     redirect(base_url('admin'));
        // } else {
        //     redirect(base_url('homeowners'));
        // }
        redirect(base_url('dashboard'));
    }

    public function logout()
    {
        if ($this->session->has_userdata('id_ho')) {
            $array_items = array('fullname', 'id_ho', 'username');
            $this->session->unset_userdata($array_items);
        }
        redirect('login');
    }
    public function forgot_password()
    {
        $data['title'] = "Forgot Password";
        $this->load->view('templates/login/forgot_password', $data);
    }
        public function send_verification()
    {
        // $fname = $this->input->post('fname');
        // $lname = $this->input->post('lname');
        $creds = $this->input->post('creds');
        $rec = $this->input->post('rec');
        if($rec == "1"){
            // recover through username 
            $res = $this->general_model->custom_query('SELECT id_ho,lname,fname,mname,email_add FROM `tbl_homeowner` WHERE status = "active" AND username = "' . $creds . '"');
        }else{
            //recover through email
            $res = $this->general_model->custom_query('SELECT id_ho,lname,fname,mname,email_add FROM `tbl_homeowner` WHERE status = "active" AND email_add = "' . $creds . '"');
        }
        if (count($res) > 0) {
            // not empty
            $result = 1;
            $fullname = $res[0]->fname . " " . $res[0]->lname;
            $recovery_code = $this->generate_password();
            $dat['recovery_code'] =  $recovery_code;
            $where_rec = "id_ho = " .    $res[0]->id_ho;
            $this->general_model->update_vals($dat, $where_rec, 'tbl_homeowner');
            $this->email_sending_concern_reply($res[0]->email_add, "Hoasys Credentials Recovery " . $fullname, $fullname, $res[0]->id_ho, $recovery_code);
        } else {
            $result = 0;
        }
        echo json_encode($result);
    }
    public function email_sending_concern_reply($email_to, $subject, $fullname, $id,  $recovery_code)
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

        $encrypted_id = base64_encode($id);
        $link = $ser . '/login/password_reset/' . urlencode($encrypted_id);

        $buttonStyle = "padding: 10px 15px; background-color: #4CAF50; color: white; text-decoration: none; display: inline-block; border-radius: 5px;";

        $message = "Hi $fullname,<br><br>

        We received a request to reset the password for your account. To confirm your identity and proceed with the password reset, please click the button below and take note of the recovery code to proceed with the reset process:<br><br>

        OTP (Please do not share) :  $recovery_code <br>
        <a href='" . $link . "' style='" . $buttonStyle . "'>RESET CREDENTIALS</a><br><br>

        If you have any questions or need assistance, feel free to contact our support team.<br><br>

        Best regards,<br>
        Hoasys Admin
    ";
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->set_crlf("\r\n");
        $this->email->from("ggn1cdo@gmail.com");
        $this->email->to($email_to);
        $this->email->subject($subject);
        $this->email->message($message);
        if ($this->email->send()) {
            // echo "Mail successful";
            // Successful!
        } else {
            echo "Sorry";
            print_r($this->email->print_debugger());
        }
    }
    public function password_reset($id_from_url)
    {
        $decrypted_id = base64_decode(urldecode($id_from_url));
        $det['id_ho'] = $decrypted_id;
        $this->load->view('templates/reset',$det);
    }
    public function generate_password()
    {
        $this->load->helper('string');
        $password = random_string('alnum', 5);
        return $password;
    }
    public function recovery_code_verification(){
        $id = $this->input->post('id_ho');      
        $code = $this->input->post('code');
        $res = $this->general_model->custom_query('SELECT * FROM `tbl_homeowner` WHERE id_ho = '.$id.' AND recovery_code = "' . $code . '"');
        if(count($res) > 0){
            $pass = $this->generate_password();
            $data['password'] = $pass;
            $data['recovery_code'] = null;
            $where = "id_ho = " .   $id;
            $this->general_model->update_vals($data, $where, 'tbl_homeowner');
            $result['creds'] = $this->general_model->custom_query('SELECT * FROM `tbl_homeowner` WHERE id_ho = '.$id.' ');
            $result['status'] = 1;
        }else{
            $result['status'] = 0;
        }
        echo json_encode($result);
    }
}