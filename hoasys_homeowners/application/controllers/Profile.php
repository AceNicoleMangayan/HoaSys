<?php

use Mpdf\Tag\P;

defined('BASEPATH') or exit('No direct script access allowed');
require_once(dirname(__FILE__) . "/General.php");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

class Profile extends General
{
    protected $title = 'Concerns';
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        if ($this->session->userdata("id_ho")) {
            $date_time = $this->get_current_date_time();
            $data["date_time"] = $date_time["dateTime"];
            $role = $this->session->userdata("role");
            $data['title'] = $this->title;
            $this->load_template_view('templates/profile/profile', $data);
        } else {
            redirect(base_url('Login'));
        }
    }
    public function get_homeowner_details()
    {
        $id = $this->session->userdata("id_ho");
        $homeowners_info = $this->general_model->custom_query('SELECT h.id_ho,h.lname,h.fname,h.mname,h.lname,h.block,h.lot,h.contact_num,h.email_add,h.status,h.password,h.username,h.village,p.monthly,p.duedate,p.id_ho_monthly FROM tbl_homeowner h, tbl_homeowner_monthly p WHERE h.id_ho = p.id_ho AND h.id_ho = ' . $id);
        echo json_encode($homeowners_info);
    }
   public function check_username_allowed_for_update($username,$id_ho){
        $CanSave = 0;
        $checker = $this->general_model->custom_query('SELECT id_ho FROM `tbl_homeowner` WHERE username = "'.$username.'" AND id_ho != '.$id_ho.'');
        if(count($checker) > 0){
            $CanSave = 0;
        }else{
            $CanSave = 1;
        }
        return $CanSave;
        // var_dump($CanSave);
    }
    public function save_profile_changes()
    {
        $result = 0;
        $id = $this->session->userdata("id_ho");
        $un = $this->input->post('username');
        $username_allowed = $this->check_username_allowed_for_update($un,$id);
         if($username_allowed == 0){
             $result = 0;
         }else{
             $result = 1;
             $home['username'] = $un;
             $home['contact_num'] = $this->input->post('contact');
        // $home['email_add'] = $this->input->post('email');
            $this->general_model->update_vals($home, "id_ho = $id", "tbl_homeowner");
         }
           echo json_encode($result); 
    }
    public function confirm_pass_changes()
    {
        $id = $this->session->userdata("id_ho");
        $code = $this->generate_password();
        $home['recovery_code'] = $code;
        $this->general_model->update_vals($home, "id_ho = $id", "tbl_homeowner");
        $details = $this->general_model->custom_query("SELECT email_add, CONCAT(fname,' ',lname) as fullname FROM `tbl_homeowner` WHERE id_ho = $id");
        // send email 
        $this->email_sending_for_password($details[0]->email_add, $code, $details[0]->fullname);
    }
    public function generate_password()
    {
        $this->load->helper('string');
        $password = random_string('alnum', 5);
        return $password;
    }
    public function email_sending_for_password($em, $pass, $fullname)
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

		We have detected that you attempted to change your password. To proceed with changes, use the OTP below. <br><br>
		
		Here are your OTP:<br>
		$pass<br><br>
		
		<br><br>
		
		Please do not share this OTP with anyone. If you have any questions or need assistance, feel free to contact our support team.<br><br>
		
		Best regards,<br>
		Hoasys Admin
		";


        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->set_crlf("\r\n");
        $this->email->from("ggn1cdo@gmail.com");
        $this->email->to($em);
        $this->email->subject("Hoasys Change Password");
        $this->email->message($message);
        if ($this->email->send()) {
            // echo "Mail successful";
        } else {
            // echo "Sorry";
            // print_r($this->email->print_debugger());
        }
    }
    function final_password_confirmation()
    {
        $id = $this->session->userdata("id_ho");
        $code = $this->input->post('otp');
        $details = $this->general_model->custom_query("SELECT recovery_code FROM `tbl_homeowner` WHERE id_ho = $id");
        if ($details[0]->recovery_code == $code) {
            $return = 1;
            $home['password'] = $this->input->post('pass');
            $home['recovery_code'] = null;
            $this->general_model->update_vals($home, "id_ho = $id", "tbl_homeowner");
        } else {
            $return = 0;
        }
        echo json_encode($return);
    }
}
