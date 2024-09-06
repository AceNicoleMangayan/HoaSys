<?php

defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Content-Type");

class General extends CI_Controller
{
    protected $SERVERFILEPATH = "";
    protected $privateKey = 'AA74CDCC2BBRT935136HH7B63C27';
    protected $secretKey = 'szye22!#'; // user define secret key
    protected $encryptMethod  = "AES-256-CBC";
    protected $token = "";
    public function __construct()
    {
        parent::__construct();
        if (ENVIRONMENT == 'development') {
            $this->SERVERFILEPATH = $_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/qr/';
            // $this->SERVERFILEPATH = $_SERVER['DOCUMENT_ROOT'] . '/xuhs96/assets/uploads/qr/';
        } else {
            $this->SERVERFILEPATH = $_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/qr/';
        }
        $this->load->library('phpqrcode/qrlib');
    }
    protected function encrypter($string)
    {
        $key = hash('sha256', $this->privateKey);
        $ivalue = substr(hash('sha256', $this->secretKey), 0, 16); // sha256 is hash_hmac_algo
        $result = openssl_encrypt($string, $this->encryptMethod, $key, 0, $ivalue);
        return base64_encode($result);  // output is a encripted value
    }
    public function encrypter2($string)
    {
        $result = openssl_encrypt($string, $this->encryptMethod, null, 0, $this->secretKey);
        return base64_encode($result);
    }
    public function decrypter2($stringEncrypt)
    {
        return openssl_decrypt(base64_decode($stringEncrypt), $this->encryptMethod, null, 0, $this->secretKey);
    }
    // public
    protected function encrypt($data, $password)
    {
        $iv = substr(sha1(mt_rand()), 0, 16);
        $password = sha1($password);

        $salt = sha1(mt_rand());
        $saltWithPassword = hash('sha256', $password . $salt);

        $encrypted = openssl_encrypt(
            "$data",
            'aes-256-cbc',
            "$saltWithPassword",
            null,
            $iv
        );
        $msg_encrypted_bundle = "$iv:$salt:$encrypted";
        return $msg_encrypted_bundle;
    }
    protected function decrypt($msg_encrypted_bundle, $password)
    {
        $password = sha1($password);

        $components = explode(':', $msg_encrypted_bundle);
        if (count($components) == 3) {
            $iv            = $components[0];
            $salt          = hash('sha256', $password . $components[1]);
            $encrypted_msg = $components[2];
            $decrypted_msg = openssl_decrypt(
                $encrypted_msg,
                'aes-256-cbc',
                $salt,
                null,
                $iv
            );
            if ($decrypted_msg === false) {
                return false;
            }
        } else {
            return false;
        }
        return $decrypted_msg;
    }
    public function side_menu()
    {
        $role = $this->session->userdata("role");
        $menu = [];
        if ($role == "Admin") {
            $menu[1] = [
                'menu' => 'committee',
                'icon' => 'fa fa-users'
            ];
        }
        return json_decode(json_encode($menu));
    }

    public function load_template_view($path, $data = null)
    {
        $menu['menu'] = $this->side_menu();
        $menu['role'] = $this->session->userdata("role");
        // var_dump($menu);
        // exit();
        $this->load->view('partials/header', $data);
        $this->load->view('partials/sidebar', $menu);
        $this->load->view('partials/header_topbar', $data);
        $this->load->view($path);
        $this->load->view('partials/footer', $data);
    }
    protected function get_current_date_time()
    {
        $date = new DateTime("now", new DateTimeZone('Asia/Manila'));
        $dates = [
            'dateTime' => $date->format('Y-m-d H:i:s'),
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
        ];
        return $dates;
    }
    protected function string_to_num_formatter($string)
    {
        $string_num = preg_replace('/[^0-9]/', '', $string);
        if (ltrim($string_num, "0") != "") {
            return ltrim($string_num, "0");
        } else {
            return 0;
        }
    }
    protected function multiexplode($delimiters, $string)
    {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }
    protected function search_like_concat($field_arr, $search_val = "")
    {
        $where_arr = [];
        $search_val = (preg_replace('/\s+/', ' ', $search_val) != " ") ? preg_replace('/\s+/', ' ', $search_val) : null;
        if ($search_val != null) {
            for ($field_arr_loop = 0; $field_arr_loop < count($field_arr); ++$field_arr_loop) {
                $search_arr = $this->multiexplode(array(",", ".", "|", ":", " "), trim($search_val));
                for ($search_arr_loop = 0; $search_arr_loop < count($search_arr); ++$search_arr_loop) {
                    array_push($where_arr, "$field_arr[$field_arr_loop] LIKE '%$search_arr[$search_arr_loop]%'");
                }
            }
        }
        return (count($where_arr) > 0) ? implode($where_arr, ' OR ') : null;
    }
    protected function search_concat($field, $search_val = "")
    {
        $where_arr = [];
        $search_arr = $this->multiexplode(array(",", ".", "|", ":", " "), trim($search_val));
        for ($search_arr_loop = 0; $search_arr_loop < count($search_arr); ++$search_arr_loop) {
            array_push($where_arr, "$field LIKE '%$search_arr[$search_arr_loop]%'");
        }
        return $where_arr;
    }
    public function readable_time_minutes($minutes = 0)
    {
        $d = floor($minutes / 1440);
        $h = floor(($minutes - $d * 1440) / 60);
        $m = $minutes - ($d * 1440) - ($h * 60);
        $time_arr = [];
        if ($d > 0) {
            $days = "day";
            if ($d > 1) {
                $days = "days";
            }
            array_push($time_arr, "$d $days");
        }
        if ($h > 0) {
            $hours = "hour";
            if ($h > 1) {
                $hours = "hours";
            }
            array_push($time_arr, "$h $hours");
        }
        if ($m > 0) {
            $minutes = "minute";
            if ($m > 1) {
                $minutes = "minutes";
            }
            array_push($time_arr, "$m $minutes");
        }
        return implode(", ", $time_arr);
    }
    //GENERAL QUERIES START --------------------------------
    protected function qry_committee($qry_others = [])
    {
        $qry['field'] = 'committee_ID, fname, lname, mname, username, role';
        $qry['table'] = 'tbl_committee';
        $qry = array_merge($qry, $qry_others);
        return $this->general_model->fetch_vals($qry);
    }

    //Authentication
    protected function token($id, $username)
    {
        $jwt = new JWT();
        $JwtSecretKey  = 'szye22!*reg';
        $data = [
            'committee_ID' => $id,
            'username' => $username,
        ];
        $token = $jwt->encode($data, $JwtSecretKey, 'HS256');
        return $token;
    }

    public function decode_token($token)
    {
        try {
            $jwt = new JWT();
            $JwtSecretKey  = 'szye22!*reg';
            $decoded_token = $jwt->decode($token, $JwtSecretKey, 'HS256');
            return $decoded_token;
        } catch (Exception $e) { // if error
            return [];
        }
    }
    public function qry_all_participants($qry_and = [])
    {
        $qry['field'] = "participant_ID, CONCAT(firstname, ' ', (CASE WHEN midname IS NOT NULL THEN CONCAT(LEFT(midname, 1), '. ') ELSE '' END), lastname, (CASE WHEN nameExt IS NOT NULL THEN CONCAT(' ', nameExt) ELSE '' END)) fullname";
        $qry['table'] = 'tbl_participant';
        if (count($qry_and) > 0) {
            $qry['where']['and'] = $qry_and;
        }
        return $this->general_model->fetch_vals($qry);
    }
    public function qry_participants($qry_and = [])
    {
        $qry['field'] = "part.participant_ID, part.code, part.emp_id, CONCAT(part.firstname, ' ', (CASE WHEN part.midname IS NOT NULL THEN CONCAT(LEFT(part.midname, 1), '. ') ELSE '' END), part.lastname, (CASE WHEN part.nameExt IS NOT NULL THEN CONCAT(' ', part.nameExt) ELSE '' END)) fullname, part.acc_id, part.account, part.participantType, part.dateRegistered, part.registeredBy, part.guest,  CONCAT(com.fname, ' ', com.lname) register";
        $qry['table'] = 'tbl_participant part';
        $qry['join']['left'] = [
            "tbl_committee com" => "com.committee_ID = part.registeredBy",
        ];
        if (count($qry_and) > 0) {
            $qry['in']['and'] = $qry_and;
        }
        return $this->general_model->fetch_vals($qry);
    }
    public function qry_accounts($acc_name, $participant_type = null, $acc_id = NULL)
    {
        $where = "";
        $where_arr = [];
        $field_arr = ['account'];
        $search_concat =  $this->search_like_concat($field_arr, $acc_name);
        if ($search_concat != null) {
            $where_acc = "AND ($search_concat)";
            array_push($where_arr, "($search_concat)");
        }
        if ($participant_type) {
            array_push($where_arr, "participantType = '$participant_type'");
        }
        if ($acc_id) {
            array_push($where_arr, "acc_id = '$acc_id'");
        }
        if (count($where_arr) > 0) {
            $where = "WHERE " . implode(" AND ", $where_arr);
        }
        return $this->general_model->custom_query("SELECT DISTINCT (acc_id) id, account as text FROM tbl_participant " . $where . " ORDER BY account ASC");
    }
    public function get_accounts()
    {
        $acc_name = $this->input->post('name');
        $participant_type = $this->input->post('participant_type') ? $this->input->post('participant_type') : null;
        $data["results"] = $this->qry_accounts($acc_name, $participant_type);
        // echo $this->db->last_query();
        echo json_encode($data);
    }
    //AUTHENTICATION
    private function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
    protected function check_token()
    {
        $this->token = "";
        $headers = $this->getAuthorizationHeader();
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                $this->token = $matches[1];
            }
        }
    }
    protected function check_authentication()
    {
        $this->check_token();
        $result = $this->decode_token($this->token);
        if (count($result) > 0) {
            $committee_details = $this->general_model->fetch_specific_val("committee_ID, fname, lname, mname, username, role, authorized", "username = '$result->username' AND committee_ID = '$result->committee_ID'", "tbl_committee");
            if (count($committee_details) < 1) {
                echo json_encode(array('status' => 'error', 'message' => 'Not Authorized'));
                die();
            } else {
                return $committee_details;
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Signature verification failed'));
            die();
        }
    }
    public function authenticate_token()
    {
        $authentication = $this->check_authentication();
        echo json_encode(array('status' => 'success', 'message' => 'Authenticated!', 'details' => $authentication));
    }
    public function authenticate_user()
    {
        $_POST = json_decode(file_get_contents("php://input"));
        $uname =  $_POST->username;
        $password = sha1(md5($_POST->password));
        $result = $this->general_model->fetch_specific_val("committee_ID, fname, lname, mname, username, role, authorized", "username = '$uname' AND password = '$password'", "tbl_committee");
        if (count($result) > 0) {
            $token = $this->token($result->committee_ID, $result->username);
            echo json_encode(array('status' => 'success', 'message' => 'Authenticated!', 'token' => $token, 'details' => $result));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Incorrect Username or Password'));
        }
    }
    public function get_homeowner_fullname($id)
    {
        $name = $this->general_model->custom_query("SELECT CONCAT(fname,' ',lname) as fullname FROM `tbl_homeowner` WHERE id_ho = $id");
        return $name;
    }
    public function get_admin_fullname($id)
    {
        $name = $this->general_model->custom_query("SELECT CONCAT(fname,' ',lname) as fullname FROM `tbl_admin` WHERE id_admin = $id");
        return $name;
    }
    public function activity_log($module, $id_admin_last_insert, $message)
    {
        $date_time = $this->get_current_date_time();
        $log["datetime_transaction"] = $date_time["dateTime"];
        $log['activity_description']  = $message;
        $log['module'] = $module;
        $log['module_id'] = $id_admin_last_insert;
        $log['transacted_by_id'] = $this->session->userdata("id_admin");
        $this->general_model->insert_vals($log, "tbl_activity_logs");
    }
    public function get_records_for_logs($id_rec)
    {
        $name = $this->general_model->custom_query("SELECT h.id_ho,h.lname,h.fname,h.mname,h.block,h.lot,h.village,h.email_add,h.contact_num,h.status,r.*, CONCAT(h.fname,' ', h.mname, ' ', h.lname) as fullname FROM tbl_records r, tbl_homeowner h WHERE h.id_ho = r.id_ho AND r.id_record = " . $id_rec);
        return $name;
    }
    // NEW send batch email 
    public function send_any_batch_emails($email_data)
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
            'newline' => '\r\n',
        );

        $this->email->initialize($config);

        foreach ($email_data as $email_info) {
            $this->email->set_newline("\r\n");
            $this->email->set_crlf("\r\n");
            $this->email->from("ggn1cdo@gmail.com");
            $this->email->to($email_info['to']);
            $this->email->subject($email_info['subject']);
            $this->email->message($email_info['message']);

            if (!$this->email->send()) {
                // Handle email sending failure
                // You can log errors or perform any other action
                // print_r($this->email->print_debugger());
            }
        }
    }
}
