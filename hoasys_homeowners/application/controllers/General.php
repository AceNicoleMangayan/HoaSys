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
        $saltWithPassword = hash('sha256', $password.$salt);

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
        if(count($components) == 3){
            $iv            = $components[0];
            $salt          = hash('sha256', $password.$components[1]);
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
        }else{
            return false;
        }
        return $decrypted_msg;
    }
    public function side_menu()
    {
        $role = $this->session->userdata("role");
        $menu = [];
        if ($role == "Admin") {
            $menu[1]=[
                'menu'=>'committee',
                'icon'=>'fa fa-users'
            ];
        }
        return json_decode(json_encode($menu));
    }

    public function load_template_view($path, $data=null)
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
                $search_arr = $this->multiexplode(array(",",".","|",":"," "), trim($search_val));
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
        $search_arr = $this->multiexplode(array(",",".","|",":"," "), trim($search_val));
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
        } catch (Exception $e) {// if error
            return [];
        }
    }
    public function qry_all_participants($qry_and = []){
        $qry['field'] = "participant_ID, CONCAT(firstname, ' ', (CASE WHEN midname IS NOT NULL THEN CONCAT(LEFT(midname, 1), '. ') ELSE '' END), lastname, (CASE WHEN nameExt IS NOT NULL THEN CONCAT(' ', nameExt) ELSE '' END)) fullname";
        $qry['table'] = 'tbl_participant';
        if(count($qry_and) > 0){
            $qry['where']['and'] = $qry_and;
        }
        return $this->general_model->fetch_vals($qry);
    }
    public function qry_participants($qry_and = []){
        $qry['field'] = "part.participant_ID, part.code, part.emp_id, CONCAT(part.firstname, ' ', (CASE WHEN part.midname IS NOT NULL THEN CONCAT(LEFT(part.midname, 1), '. ') ELSE '' END), part.lastname, (CASE WHEN part.nameExt IS NOT NULL THEN CONCAT(' ', part.nameExt) ELSE '' END)) fullname, part.acc_id, part.account, part.participantType, part.dateRegistered, part.registeredBy, part.guest,  CONCAT(com.fname, ' ', com.lname) register";
        $qry['table'] = 'tbl_participant part';
        $qry['join']['left'] = [
            "tbl_committee com" => "com.committee_ID = part.registeredBy",
        ];
        if(count($qry_and) > 0){
            $qry['in']['and'] = $qry_and;
        }
        return $this->general_model->fetch_vals($qry);
    }
    public function generate_participants(){
        $employee = array(
  array('emp_id' => '46','lname' => 'Abales','mname' => 'Lacerna','fname' => 'Myla Gracia','nameExt' => '','acc_id' => '94','acc_name' => 'Accounts Managers','acc_description' => 'Admin'),
  array('emp_id' => '2654','lname' => 'Abalona','mname' => 'Sinodlay','fname' => 'Marben','nameExt' => NULL,'acc_id' => '40','acc_name' => 'MyMDConnect','acc_description' => 'Agent'),
  array('emp_id' => '2657','lname' => 'Abamonga','mname' => 'Adame','fname' => 'Camille Ann','nameExt' => NULL,'acc_id' => '101','acc_name' => 'Lone Wolf Accounting','acc_description' => 'Agent'),
  array('emp_id' => '2458','lname' => 'Abehon','mname' => 'Juanitas','fname' => 'Roy','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2776','lname' => 'Abendan','mname' => 'Mari','fname' => 'Ana Jean','nameExt' => NULL,'acc_id' => '71','acc_name' => 'Center','acc_description' => 'Agent'),
  array('emp_id' => '1298','lname' => 'Abenir','mname' => 'Alberastine','fname' => 'Lorenz Miguel','nameExt' => '','acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '1932','lname' => 'Ablijena','mname' => 'T.','fname' => 'Irene','nameExt' => NULL,'acc_id' => '83','acc_name' => 'Lone Wolf Cebu','acc_description' => 'Agent'),
  array('emp_id' => '2743','lname' => 'Abragan','mname' => 'Isobal','fname' => 'Anderson','nameExt' => NULL,'acc_id' => '17','acc_name' => 'D.A. Lamont','acc_description' => 'Agent'),
  array('emp_id' => '2495','lname' => 'Abucejo','mname' => NULL,'fname' => 'Michael Norman','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2594','lname' => 'Acapulco','mname' => 'Calabroso','fname' => 'Marvin','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2667','lname' => 'Acapulco','mname' => 'Yorong','fname' => 'Jeremae','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '591','lname' => 'Acaso','mname' => 'Bahian','fname' => 'Denes','nameExt' => NULL,'acc_id' => '5','acc_name' => 'Safety  and Security','acc_description' => 'Admin'),
  array('emp_id' => '2195','lname' => 'Acebido','mname' => 'Rama','fname' => 'Jenane Claire','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2032','lname' => 'Acenas','mname' => 'Binalon','fname' => 'Kienth James','nameExt' => NULL,'acc_id' => '37','acc_name' => 'Software Programming','acc_description' => 'Admin'),
  array('emp_id' => '2796','lname' => 'Acero','mname' => 'Baal','fname' => 'Christine Mae','nameExt' => NULL,'acc_id' => '14','acc_name' => 'Finance','acc_description' => 'Admin'),
  array('emp_id' => '1348','lname' => 'Actub','mname' => 'Alcalde','fname' => 'Raycelle Kerzy','nameExt' => '','acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '1713','lname' => 'Agabon','mname' => 'Silva','fname' => 'Nichole','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2742','lname' => 'Aguhayon','mname' => 'Ubria','fname' => 'Renalyn','nameExt' => NULL,'acc_id' => '51','acc_name' => 'Executive Support Team','acc_description' => 'Admin'),
  array('emp_id' => '2613','lname' => 'Aguilar','mname' => 'Gamale','fname' => 'Justine Mae','nameExt' => NULL,'acc_id' => '34','acc_name' => 'Vistracks','acc_description' => 'Agent'),
  array('emp_id' => '2617','lname' => 'Aguilar','mname' => 'Gomez','fname' => 'Crystal Dea','nameExt' => NULL,'acc_id' => '47','acc_name' => 'Trademark Engine','acc_description' => 'Agent'),
  array('emp_id' => '2648','lname' => 'Aguilar','mname' => 'Dacer','fname' => 'Michael Anthony','nameExt' => NULL,'acc_id' => '89','acc_name' => 'G&A Partners','acc_description' => 'Agent'),
  array('emp_id' => '2690','lname' => 'Aguiñot','mname' => 'Zambas','fname' => 'Johne Juvein','nameExt' => NULL,'acc_id' => '5','acc_name' => 'Safety  and Security','acc_description' => 'Admin'),
  array('emp_id' => '519','lname' => 'Aguirre','mname' => 'Ucat','fname' => 'Kristel Mae','nameExt' => '','acc_id' => '44','acc_name' => 'VA Imtiaz','acc_description' => 'Agent'),
  array('emp_id' => '2546','lname' => 'Albano','mname' => 'Pejana','fname' => 'Rujean','nameExt' => NULL,'acc_id' => '71','acc_name' => 'Center','acc_description' => 'Agent'),
  array('emp_id' => '1779','lname' => 'Albellar','mname' => 'Sobretodo','fname' => 'Hazel Mae','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2614','lname' => 'Alcayde','mname' => 'Polinar','fname' => 'Jesiree','nameExt' => NULL,'acc_id' => '101','acc_name' => 'Lone Wolf Accounting','acc_description' => 'Agent'),
  array('emp_id' => '49','lname' => 'Alcazaren','mname' => 'Donayre','fname' => 'Araceli Jade','nameExt' => '','acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '1679','lname' => 'Alcomendras','mname' => 'Alinsugay','fname' => 'Alvin','nameExt' => '','acc_id' => '49','acc_name' => 'Onyx CS Wheels','acc_description' => 'Agent'),
  array('emp_id' => '2563','lname' => 'Alcontin','mname' => 'Obial','fname' => 'Ma. Carmela','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '1799','lname' => 'Alegre','mname' => 'Virtudazo','fname' => 'Katherine','nameExt' => '','acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2437','lname' => 'Alibangbang','mname' => 'Carcedo','fname' => 'Mark Christel','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2616','lname' => 'Alicante','mname' => 'Badar','fname' => 'Emerald','nameExt' => NULL,'acc_id' => '15','acc_name' => 'TelemedRN - USRN','acc_description' => 'Agent'),
  array('emp_id' => '2689','lname' => 'Aling','mname' => 'Oliveros','fname' => 'Romnick','nameExt' => NULL,'acc_id' => '5','acc_name' => 'Safety  and Security','acc_description' => 'Admin'),
  array('emp_id' => '2511','lname' => 'Alip','mname' => 'Umaran','fname' => 'Hazel','nameExt' => NULL,'acc_id' => '57','acc_name' => 'Total Rewards','acc_description' => 'Admin'),
  array('emp_id' => '1884','lname' => 'Alivo','mname' => 'Camilo','fname' => 'Renald','nameExt' => NULL,'acc_id' => '10','acc_name' => 'Service Delivery','acc_description' => 'Admin'),
  array('emp_id' => '1785','lname' => 'Alquitela','mname' => 'Baylin','fname' => 'Jerielle Love','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '978','lname' => 'Alvarez','mname' => 'n/a','fname' => 'Venz Eivanne','nameExt' => '','acc_id' => '69','acc_name' => 'Onyx Escalations Team','acc_description' => 'Agent'),
  array('emp_id' => '1308','lname' => 'Amar','mname' => 'Lim','fname' => 'Frenney Luz','nameExt' => '','acc_id' => '80','acc_name' => 'Onyx Customer Service (Cebu)','acc_description' => 'Agent'),
  array('emp_id' => '50','lname' => 'Amaro','mname' => 'Balaba','fname' => 'Kristel Ann','nameExt' => NULL,'acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '2405','lname' => 'Amasola','mname' => 'Boston','fname' => 'Marie Coleen','nameExt' => NULL,'acc_id' => '76','acc_name' => 'Nurse Dash','acc_description' => 'Agent'),
  array('emp_id' => '2133','lname' => 'Ambe','mname' => 'Gaerlan','fname' => 'Geneselle Marie','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2251','lname' => 'Ambrocio','mname' => 'Mijares','fname' => 'Franz Marie','nameExt' => NULL,'acc_id' => '73','acc_name' => 'Alpha Lion','acc_description' => 'Agent'),
  array('emp_id' => '2317','lname' => 'Amora','mname' => 'Yurong','fname' => 'Lovely Clear','nameExt' => NULL,'acc_id' => '53','acc_name' => 'Workplace Safety Screenings','acc_description' => 'Agent'),
  array('emp_id' => '184','lname' => 'An FBC Company','mname' => 'N/A','fname' => 'SupportZebra:','nameExt' => NULL,'acc_id' => '13','acc_name' => 'Managing Director','acc_description' => 'Admin'),
  array('emp_id' => '2586','lname' => 'Añasco','mname' => 'Calope','fname' => 'Krizzia Mae','nameExt' => NULL,'acc_id' => '101','acc_name' => 'Lone Wolf Accounting','acc_description' => 'Agent'),
  array('emp_id' => '2780','lname' => 'Andam','mname' => 'Cruda','fname' => 'John Siegfred','nameExt' => NULL,'acc_id' => '7','acc_name' => 'Link Tree','acc_description' => 'Agent'),
  array('emp_id' => '51','lname' => 'Ang','mname' => 'Morales','fname' => 'Pretzel','nameExt' => '','acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '1972','lname' => 'Ang','mname' => 'Dela Calzada','fname' => 'Charlo Lester','nameExt' => NULL,'acc_id' => '62','acc_name' => 'Organization Development and Strategy','acc_description' => 'Admin'),
  array('emp_id' => '2535','lname' => 'Aniasco','mname' => 'Horboda','fname' => 'Daisy Jane','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '1674','lname' => 'Anicoy','mname' => 'Llanos','fname' => 'Mixmerizele','nameExt' => '','acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '2414','lname' => 'Aniñon','mname' => 'Redeja','fname' => 'Friggen','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2706','lname' => 'Apa-ap','mname' => NULL,'fname' => 'Cydnee Kathryne','nameExt' => NULL,'acc_id' => '15','acc_name' => 'TelemedRN - USRN','acc_description' => 'Agent'),
  array('emp_id' => '2666','lname' => 'Apas','mname' => 'Tac-an','fname' => 'Rica Joy','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '1513','lname' => 'Apdian','mname' => 'Epon','fname' => 'Analee','nameExt' => '','acc_id' => '83','acc_name' => 'Lone Wolf Cebu','acc_description' => 'Agent'),
  array('emp_id' => '2555','lname' => 'Apo','mname' => 'Poblador','fname' => 'Earl Kristine','nameExt' => NULL,'acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '372','lname' => 'Apostol','mname' => 'Arana','fname' => 'Jim Nikko','nameExt' => '','acc_id' => '28','acc_name' => 'Onyx Digital Team','acc_description' => 'Agent'),
  array('emp_id' => '2781','lname' => 'Aranas','mname' => 'Hondrada','fname' => 'Crestal Shuara','nameExt' => NULL,'acc_id' => '7','acc_name' => 'Link Tree','acc_description' => 'Agent'),
  array('emp_id' => '2090','lname' => 'Araneta','mname' => 'Bitoy','fname' => 'Marhea Loren Karyz','nameExt' => NULL,'acc_id' => '65','acc_name' => 'Quality Assurance','acc_description' => 'Admin'),
  array('emp_id' => '2248','lname' => 'Aras','mname' => 'Sarang','fname' => 'Roselyn','nameExt' => NULL,'acc_id' => '75','acc_name' => 'Purchasing and Inventory Team','acc_description' => 'Admin'),
  array('emp_id' => '2213','lname' => 'Arcede','mname' => 'Asis','fname' => 'Regine','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '436','lname' => 'Arique','mname' => 'Gerodico','fname' => 'Nicca','nameExt' => '','acc_id' => '37','acc_name' => 'Software Programming','acc_description' => 'Admin'),
  array('emp_id' => '2662','lname' => 'Arnejo','mname' => 'Sebial','fname' => 'Charisse','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '1982','lname' => 'Arquero','mname' => 'Parojinog','fname' => 'Julie Mae','nameExt' => NULL,'acc_id' => '65','acc_name' => 'Quality Assurance','acc_description' => 'Admin'),
  array('emp_id' => '2715','lname' => 'Arroyo','mname' => 'Galarrita','fname' => 'Antonio Luis','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '1456','lname' => 'Aruta','mname' => 'Agbayani','fname' => 'Michael Philip','nameExt' => '','acc_id' => '49','acc_name' => 'Onyx CS Wheels','acc_description' => 'Agent'),
  array('emp_id' => '164','lname' => 'Asilo','mname' => 'Agbalog','fname' => 'Debie','nameExt' => '','acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '1755','lname' => 'Asne','mname' => 'Paña','fname' => 'Julianne Jeay','nameExt' => '','acc_id' => '70','acc_name' => 'Workforce Management','acc_description' => 'Admin'),
  array('emp_id' => '1890','lname' => 'Asok','mname' => 'Gaabucayan','fname' => 'Lyndon','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2605','lname' => 'Auditor','mname' => 'Medino','fname' => 'Sean Benedique','nameExt' => NULL,'acc_id' => '53','acc_name' => 'Workplace Safety Screenings','acc_description' => 'Agent'),
  array('emp_id' => '2698','lname' => 'Aurio','mname' => 'Baquiran','fname' => 'John Mark','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2700','lname' => 'Autida','mname' => 'Ohay','fname' => 'Analita','nameExt' => NULL,'acc_id' => '79','acc_name' => 'AMP Academy','acc_description' => 'Admin'),
  array('emp_id' => '2183','lname' => 'Avanceña','mname' => 'Maandig','fname' => 'Arra Kay','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2561','lname' => 'Awiten','mname' => 'Quiblat','fname' => 'Rene Alain','nameExt' => NULL,'acc_id' => '38','acc_name' => 'Training','acc_description' => 'Admin'),
  array('emp_id' => '1943','lname' => 'Babanto','mname' => 'Rafael','fname' => 'Rheyjan Carl','nameExt' => NULL,'acc_id' => '53','acc_name' => 'Workplace Safety Screenings','acc_description' => 'Agent'),
  array('emp_id' => '2569','lname' => 'Babate','mname' => 'Casing','fname' => 'Mikko','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2349','lname' => 'Babayen-on','mname' => 'Himbing','fname' => 'Maria Mianne','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '1989','lname' => 'Bacasno','mname' => 'Roble','fname' => 'Karla','nameExt' => NULL,'acc_id' => '19','acc_name' => 'FieldLogix','acc_description' => 'Agent'),
  array('emp_id' => '2631','lname' => 'Baclayo','mname' => 'Naval','fname' => 'Shawn Rainier','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2574','lname' => 'Baconga','mname' => 'Estrellado','fname' => 'Charisse','nameExt' => NULL,'acc_id' => '34','acc_name' => 'Vistracks','acc_description' => 'Agent'),
  array('emp_id' => '34','lname' => 'Baconguis','mname' => 'Paigalan','fname' => 'Dorothy','nameExt' => '','acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '2556','lname' => 'Baconguis','mname' => 'Campus','fname' => 'Fairy Jay','nameExt' => NULL,'acc_id' => '16','acc_name' => 'CT-Miami','acc_description' => 'Agent'),
  array('emp_id' => '2745','lname' => 'Baculio','mname' => 'Unson','fname' => 'Christine','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '1883','lname' => 'Bado','mname' => 'Jorolan','fname' => 'Raul','nameExt' => NULL,'acc_id' => '5','acc_name' => 'Safety  and Security','acc_description' => 'Admin'),
  array('emp_id' => '1617','lname' => 'Badulan','mname' => 'Minayan','fname' => 'Rafael','nameExt' => NULL,'acc_id' => '5','acc_name' => 'Safety  and Security','acc_description' => 'Admin'),
  array('emp_id' => '1936','lname' => 'Bagaslao','mname' => 'D.','fname' => 'Josephine','nameExt' => NULL,'acc_id' => '83','acc_name' => 'Lone Wolf Cebu','acc_description' => 'Agent'),
  array('emp_id' => '1549','lname' => 'Bagoyado','mname' => 'Cabrera','fname' => 'Manuel','nameExt' => 'Jr','acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '1842','lname' => 'Bahan','mname' => 'Talaman','fname' => 'Lynnel Lloyd','nameExt' => '','acc_id' => '28','acc_name' => 'Onyx Digital Team','acc_description' => 'Agent'),
  array('emp_id' => '168','lname' => 'Bahaynon','mname' => 'Buenavista','fname' => 'Ann Hazel','nameExt' => '','acc_id' => '94','acc_name' => 'Accounts Managers','acc_description' => 'Admin'),
  array('emp_id' => '2737','lname' => 'Baiño','mname' => 'Limbo','fname' => 'Jonathan Earl Daniel','nameExt' => NULL,'acc_id' => '92','acc_name' => 'Thinkific Chat','acc_description' => 'Agent'),
  array('emp_id' => '2788','lname' => 'Bajao','mname' => 'Alburo','fname' => 'Mylin','nameExt' => NULL,'acc_id' => '89','acc_name' => 'G&A Partners','acc_description' => 'Agent'),
  array('emp_id' => '2693','lname' => 'Balansag','mname' => 'Esco','fname' => 'Janilyn','nameExt' => NULL,'acc_id' => '51','acc_name' => 'Executive Support Team','acc_description' => 'Admin'),
  array('emp_id' => '2026','lname' => 'Baldivino','mname' => 'O.','fname' => 'Josephine','nameExt' => NULL,'acc_id' => '51','acc_name' => 'Executive Support Team','acc_description' => 'Admin'),
  array('emp_id' => '54','lname' => 'Baldon','mname' => 'Egarguin','fname' => 'Levi','nameExt' => '','acc_id' => '70','acc_name' => 'Workforce Management','acc_description' => 'Admin'),
  array('emp_id' => '701','lname' => 'Balintongog','mname' => 'Seblian','fname' => 'Lorellee','nameExt' => '','acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '927','lname' => 'Balistoy','mname' => 'Herdiles','fname' => 'Charlene Mae','nameExt' => '','acc_id' => '92','acc_name' => 'Thinkific Chat','acc_description' => 'Agent'),
  array('emp_id' => '1859','lname' => 'Banal','mname' => 'N/A','fname' => 'Ruby Ann','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '1654','lname' => 'Banca','mname' => 'rectasa','fname' => 'June','nameExt' => NULL,'acc_id' => '83','acc_name' => 'Lone Wolf Cebu','acc_description' => 'Agent'),
  array('emp_id' => '743','lname' => 'Bandico','mname' => 'Camingawan','fname' => 'Joanne Benith ','nameExt' => '','acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2064','lname' => 'Bandingon','mname' => 'Abunda','fname' => 'Reyna Mae','nameExt' => NULL,'acc_id' => '65','acc_name' => 'Quality Assurance','acc_description' => 'Admin'),
  array('emp_id' => '728','lname' => 'Bandoy','mname' => 'Policarpio','fname' => 'Leah Suzeth','nameExt' => '','acc_id' => '69','acc_name' => 'Onyx Escalations Team','acc_description' => 'Agent'),
  array('emp_id' => '2025','lname' => 'Bangot','mname' => 'Bermudo','fname' => 'Lotis Joy','nameExt' => NULL,'acc_id' => '94','acc_name' => 'Accounts Managers','acc_description' => 'Admin'),
  array('emp_id' => '1274','lname' => 'Baol','mname' => 'Gabales','fname' => 'Marianne Amifae','nameExt' => '','acc_id' => '26','acc_name' => 'Iscential','acc_description' => 'Agent'),
  array('emp_id' => '959','lname' => 'Barbon','mname' => 'Tejeros','fname' => 'Ma. Merryl','nameExt' => '','acc_id' => '28','acc_name' => 'Onyx Digital Team','acc_description' => 'Agent'),
  array('emp_id' => '2630','lname' => 'Barcelona','mname' => 'Mission','fname' => 'Kenneth Bernand','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2013','lname' => 'Baril','mname' => 'Japitana','fname' => 'Raymond','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2037','lname' => 'Barit','mname' => 'Gumaga','fname' => 'Adrian Irvin','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2533','lname' => 'Barlisan','mname' => 'Navarez','fname' => 'Geovanni','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2547','lname' => 'Barrion','mname' => 'Clarito','fname' => 'Era Mae','nameExt' => NULL,'acc_id' => '71','acc_name' => 'Center','acc_description' => 'Agent'),
  array('emp_id' => '1429','lname' => 'Barroso','mname' => 'Aguilar','fname' => 'Jesus miguel','nameExt' => NULL,'acc_id' => '65','acc_name' => 'Quality Assurance','acc_description' => 'Admin'),
  array('emp_id' => '530','lname' => 'Basalan','mname' => 'Autor','fname' => 'Vanessa Marie','nameExt' => NULL,'acc_id' => '65','acc_name' => 'Quality Assurance','acc_description' => 'Admin'),
  array('emp_id' => '2121','lname' => 'Basallo','mname' => 'Lumacad','fname' => 'Robe Anne','nameExt' => NULL,'acc_id' => '7','acc_name' => 'Link Tree','acc_description' => 'Agent'),
  array('emp_id' => '1930','lname' => 'Basañez','mname' => 'Rapirap','fname' => 'Randale','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2385','lname' => 'Batalla','mname' => 'Dalagan','fname' => 'Oren Jon','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2697','lname' => 'Bayawa','mname' => 'Guadez','fname' => 'Rodelyn Faith','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2760','lname' => 'Baylosis','mname' => NULL,'fname' => 'Djiego','nameExt' => NULL,'acc_id' => '59','acc_name' => 'ETC Institute','acc_description' => 'Agent'),
  array('emp_id' => '2227','lname' => 'Beleran','mname' => 'Bangud','fname' => 'Kristia','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2249','lname' => 'Bernados','mname' => 'Pangayao','fname' => 'Crisly','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2619','lname' => 'Bernales','mname' => 'Ramirez','fname' => 'Ma. Leana Lesette','nameExt' => NULL,'acc_id' => '15','acc_name' => 'TelemedRN - USRN','acc_description' => 'Agent'),
  array('emp_id' => '2206','lname' => 'Besabella','mname' => 'Bayot','fname' => 'Remon','nameExt' => NULL,'acc_id' => '83','acc_name' => 'Lone Wolf Cebu','acc_description' => 'Agent'),
  array('emp_id' => '2593','lname' => 'Besas','mname' => 'Bercero','fname' => 'Brian','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '167','lname' => 'Betonio','mname' => 'Lariosa','fname' => 'Cherish Von Ann','nameExt' => '','acc_id' => '34','acc_name' => 'Vistracks','acc_description' => 'Agent'),
  array('emp_id' => '1704','lname' => 'Bibal','mname' => 'Orencia','fname' => 'Jo-Ann Lizette','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '1856','lname' => 'Bigbig','mname' => 'Tanginan','fname' => 'Jeney Joy','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2761','lname' => 'Biña','mname' => 'Reynaldo','fname' => 'Xander Dan','nameExt' => NULL,'acc_id' => '59','acc_name' => 'ETC Institute','acc_description' => 'Agent'),
  array('emp_id' => '2277','lname' => 'Bince','mname' => 'Doong','fname' => 'Carlo Kearvin','nameExt' => NULL,'acc_id' => '33','acc_name' => 'EJ Wholesale Accounting Staff','acc_description' => 'Agent'),
  array('emp_id' => '2418','lname' => 'Biong','mname' => 'Palac','fname' => 'John Mark','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2390','lname' => 'Bitayo','mname' => 'Valmores','fname' => 'Hanna Joy','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '57','lname' => 'Boco','mname' => 'Pimentel','fname' => 'Mary Claire','nameExt' => '','acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '2086','lname' => 'Bolo','mname' => 'Antoni','fname' => 'Bernardsam','nameExt' => NULL,'acc_id' => '58','acc_name' => 'Visual Communications Team','acc_description' => 'Admin'),
  array('emp_id' => '2420','lname' => 'Bongato','mname' => 'Mandalupa','fname' => 'Marpet Boman','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2682','lname' => 'Bongcawil','mname' => 'Mascardo','fname' => 'Johannah Lady Leigh','nameExt' => NULL,'acc_id' => '71','acc_name' => 'Center','acc_description' => 'Agent'),
  array('emp_id' => '58','lname' => 'Bonita','mname' => 'Mercado','fname' => 'Michelle Joy','nameExt' => '','acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2754','lname' => 'Borja','mname' => 'Senor','fname' => 'Vincent John','nameExt' => NULL,'acc_id' => '58','acc_name' => 'Visual Communications Team','acc_description' => 'Admin'),
  array('emp_id' => '1535','lname' => 'Briones','mname' => 'Pabatao','fname' => 'Ivan Joseph','nameExt' => NULL,'acc_id' => '34','acc_name' => 'Vistracks','acc_description' => 'Agent'),
  array('emp_id' => '1750','lname' => 'Briones','mname' => 'Cuevas','fname' => 'Miguel Carlo','nameExt' => NULL,'acc_id' => '71','acc_name' => 'Center','acc_description' => 'Agent'),
  array('emp_id' => '2389','lname' => 'Brossard','mname' => 'Bilangdal','fname' => 'Amandine Raissa Annie','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2767','lname' => 'Bual','mname' => NULL,'fname' => 'Lovearn Mae','nameExt' => NULL,'acc_id' => '26','acc_name' => 'Iscential','acc_description' => 'Agent'),
  array('emp_id' => '2557','lname' => 'Buhisan','mname' => 'Vallermosa','fname' => 'Dan Joseph','nameExt' => NULL,'acc_id' => '90','acc_name' => 'Special Projects','acc_description' => 'Admin'),
  array('emp_id' => '2707','lname' => 'Bulanadi','mname' => 'Davide','fname' => 'Cloie','nameExt' => NULL,'acc_id' => '15','acc_name' => 'TelemedRN - USRN','acc_description' => 'Agent'),
  array('emp_id' => '1826','lname' => 'Cabactulan','mname' => 'Galarpe','fname' => 'Fruteleen','nameExt' => '','acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '115','lname' => 'Cabahug','mname' => 'Wenceslao','fname' => 'Raymond','nameExt' => NULL,'acc_id' => '12','acc_name' => 'Health and Safety','acc_description' => 'Admin'),
  array('emp_id' => '2137','lname' => 'Cabahug','mname' => 'Nacalaban','fname' => 'Rose Chalice','nameExt' => NULL,'acc_id' => '90','acc_name' => 'Special Projects','acc_description' => 'Admin'),
  array('emp_id' => '114','lname' => 'Caballero','mname' => 'Villacorta','fname' => 'John Michael','nameExt' => '','acc_id' => '2','acc_name' => 'IT and Systems Development','acc_description' => 'Admin'),
  array('emp_id' => '2474','lname' => 'Cabanatan','mname' => 'Tan','fname' => 'Ian Jun','nameExt' => NULL,'acc_id' => '70','acc_name' => 'Workforce Management','acc_description' => 'Admin'),
  array('emp_id' => '1922','lname' => 'Cabañeros','mname' => 'S.','fname' => 'Sean Michael','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2127','lname' => 'Cabañeros','mname' => 'Rojas','fname' => 'Chuck','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2362','lname' => 'Cabañeros','mname' => 'Pajo','fname' => 'Rustom','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2549','lname' => 'Cabingas','mname' => 'Jaquilmac','fname' => 'Christine Cara','nameExt' => NULL,'acc_id' => '89','acc_name' => 'G&A Partners','acc_description' => 'Agent'),
  array('emp_id' => '2748','lname' => 'Caga-anan','mname' => NULL,'fname' => 'John Rey','nameExt' => NULL,'acc_id' => '79','acc_name' => 'AMP Academy','acc_description' => 'Admin'),
  array('emp_id' => '2762','lname' => 'Cagaanan','mname' => 'Poquita','fname' => 'Maria Andrea','nameExt' => NULL,'acc_id' => '59','acc_name' => 'ETC Institute','acc_description' => 'Agent'),
  array('emp_id' => '1716','lname' => 'Cagalawan','mname' => 'Benzal','fname' => 'jose roy jr.','nameExt' => NULL,'acc_id' => '28','acc_name' => 'Onyx Digital Team','acc_description' => 'Agent'),
  array('emp_id' => '1751','lname' => 'Cagampang','mname' => 'Dela Paz','fname' => 'Jaquilyn','nameExt' => NULL,'acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '2494','lname' => 'Cagas','mname' => 'Dungay','fname' => 'Ann Jeneth','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2545','lname' => 'Cahintong','mname' => 'Octobre','fname' => 'Kristel Joy','nameExt' => NULL,'acc_id' => '70','acc_name' => 'Workforce Management','acc_description' => 'Admin'),
  array('emp_id' => '2578','lname' => 'Cailing','mname' => 'Jabel','fname' => 'Eddie John','nameExt' => NULL,'acc_id' => '60','acc_name' => 'Client Transitions Team','acc_description' => 'Admin'),
  array('emp_id' => '2634','lname' => 'Cailing','mname' => 'Gonong','fname' => 'Jenny Mae','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2060','lname' => 'Caiña','mname' => 'Bugtong','fname' => 'Venus Marie','nameExt' => NULL,'acc_id' => '48','acc_name' => 'TelemedRN - Receptionist','acc_description' => 'Agent'),
  array('emp_id' => '2758','lname' => 'Cainoy','mname' => 'Jorojoro','fname' => 'Rito','nameExt' => 'Jr','acc_id' => '5','acc_name' => 'Safety  and Security','acc_description' => 'Admin'),
  array('emp_id' => '2784','lname' => 'Cajoles','mname' => 'Ong','fname' => 'Jerahmeel','nameExt' => NULL,'acc_id' => '96','acc_name' => 'Human Resources and Administration','acc_description' => 'Admin'),
  array('emp_id' => '692','lname' => 'Calboni','mname' => 'Sobrepena','fname' => 'Maria Isabel','nameExt' => '','acc_id' => '28','acc_name' => 'Onyx Digital Team','acc_description' => 'Agent'),
  array('emp_id' => '2051','lname' => 'Calo','mname' => 'Maghanoy','fname' => 'Mac Kianro Maki','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2467','lname' => 'Camara','mname' => 'Kabiling','fname' => 'Joshua','nameExt' => NULL,'acc_id' => '38','acc_name' => 'Training','acc_description' => 'Admin'),
  array('emp_id' => '2568','lname' => 'Campos','mname' => 'Diadula','fname' => 'Gerald Agustin','nameExt' => 'jr','acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2163','lname' => 'Cañazares','mname' => 'Perlado','fname' => 'Jexxon Ryan','nameExt' => NULL,'acc_id' => '73','acc_name' => 'Alpha Lion','acc_description' => 'Agent'),
  array('emp_id' => '2482','lname' => 'Cane','mname' => 'Latonio','fname' => 'Alex','nameExt' => NULL,'acc_id' => '83','acc_name' => 'Lone Wolf Cebu','acc_description' => 'Agent'),
  array('emp_id' => '2185','lname' => 'Canino','mname' => 'Lemitares','fname' => 'Christine Joy','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2587','lname' => 'Cano-og','mname' => 'Geromo','fname' => 'Shane Clear','nameExt' => NULL,'acc_id' => '51','acc_name' => 'Executive Support Team','acc_description' => 'Admin'),
  array('emp_id' => '2588','lname' => 'Caotivo','mname' => 'Galan','fname' => 'Franie Rose','nameExt' => NULL,'acc_id' => '83','acc_name' => 'Lone Wolf Cebu','acc_description' => 'Agent'),
  array('emp_id' => '2432','lname' => 'capapas','mname' => 'Quan','fname' => 'Raymond Rafael','nameExt' => 'Jr','acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '1812','lname' => 'Cape','mname' => 'Beril','fname' => 'Jessereil','nameExt' => '','acc_id' => '88','acc_name' => 'Accounting','acc_description' => 'Admin'),
  array('emp_id' => '2719','lname' => 'Carampatana ','mname' => 'Pacala','fname' => 'Saloma','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2567','lname' => 'Carillo','mname' => 'Sajelan','fname' => 'Leonardo','nameExt' => 'jr','acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2453','lname' => 'Casiño','mname' => 'Alimbog','fname' => 'Shiney Queen','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2607','lname' => 'Casiño','mname' => 'Abrogar','fname' => 'Jhun Camille Ian','nameExt' => NULL,'acc_id' => '47','acc_name' => 'Trademark Engine','acc_description' => 'Agent'),
  array('emp_id' => '2191','lname' => 'Catalasan','mname' => 'Demacale','fname' => 'Neil','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2285','lname' => 'Catubay','mname' => 'Tacbobo','fname' => 'Webeth','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2711','lname' => 'Caunan','mname' => 'Seballos','fname' => 'Marisol Ellaine','nameExt' => NULL,'acc_id' => '71','acc_name' => 'Center','acc_description' => 'Agent'),
  array('emp_id' => '2645','lname' => 'Cayabyab','mname' => 'Rockwell','fname' => 'Justine Rein','nameExt' => NULL,'acc_id' => '17','acc_name' => 'D.A. Lamont','acc_description' => 'Agent'),
  array('emp_id' => '1502','lname' => 'Cempron','mname' => 'Gonzales','fname' => 'Felix Franz','nameExt' => NULL,'acc_id' => '45','acc_name' => 'Genuent','acc_description' => 'Agent'),
  array('emp_id' => '1827','lname' => 'Centural','mname' => 'Pabualan','fname' => 'Kate Nally','nameExt' => NULL,'acc_id' => '34','acc_name' => 'Vistracks','acc_description' => 'Agent'),
  array('emp_id' => '2759','lname' => 'Chacon','mname' => 'Zamora','fname' => 'Ryam Lorie','nameExt' => NULL,'acc_id' => '58','acc_name' => 'Visual Communications Team','acc_description' => 'Admin'),
  array('emp_id' => '822','lname' => 'Chaves','mname' => 'Neri','fname' => 'Pamela Iris','nameExt' => '','acc_id' => '69','acc_name' => 'Onyx Escalations Team','acc_description' => 'Agent'),
  array('emp_id' => '522','lname' => 'Chee','mname' => 'Ticao','fname' => 'Ana Bella Christina','nameExt' => NULL,'acc_id' => '62','acc_name' => 'Organization Development and Strategy','acc_description' => 'Admin'),
  array('emp_id' => '1797','lname' => 'Chiu','mname' => 'Sabulana','fname' => 'Jiff','nameExt' => '','acc_id' => '49','acc_name' => 'Onyx CS Wheels','acc_description' => 'Agent'),
  array('emp_id' => '2681','lname' => 'Cimagala','mname' => 'Seno','fname' => 'Dorothy Giel','nameExt' => NULL,'acc_id' => '71','acc_name' => 'Center','acc_description' => 'Agent'),
  array('emp_id' => '1988','lname' => 'Cinco','mname' => 'Unabia','fname' => 'Christopher Abel','nameExt' => NULL,'acc_id' => '65','acc_name' => 'Quality Assurance','acc_description' => 'Admin'),
  array('emp_id' => '2695','lname' => 'Clemeña','mname' => 'Lloren','fname' => 'Karen','nameExt' => NULL,'acc_id' => '51','acc_name' => 'Executive Support Team','acc_description' => 'Admin'),
  array('emp_id' => '1497','lname' => 'Clemencio','mname' => 'Asotigue','fname' => 'Lonevel','nameExt' => '','acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '1946','lname' => 'Colanggo','mname' => 'Cand-og','fname' => 'Gevey','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2536','lname' => 'Coling','mname' => 'Rogador','fname' => 'Mary Claire','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '1608','lname' => 'Comaingking','mname' => 'Alarde','fname' => 'Dianne','nameExt' => '','acc_id' => '80','acc_name' => 'Onyx Customer Service (Cebu)','acc_description' => 'Agent'),
  array('emp_id' => '2591','lname' => 'Concepcion','mname' => 'Marapo','fname' => 'Ellison','nameExt' => NULL,'acc_id' => '20','acc_name' => 'RIS','acc_description' => 'Agent'),
  array('emp_id' => '2398','lname' => 'Conde','mname' => 'Salibio','fname' => 'Ronald','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '1937','lname' => 'Condeza','mname' => 'Ramirez','fname' => 'Stephanie','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2204','lname' => 'Conol','mname' => 'Zabate','fname' => 'Ross Anthony','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2694','lname' => 'Corro','mname' => 'Dalagan','fname' => 'Janice Mary','nameExt' => NULL,'acc_id' => '51','acc_name' => 'Executive Support Team','acc_description' => 'Admin'),
  array('emp_id' => '2651','lname' => 'Cortez','mname' => 'Achas','fname' => 'Eric Jay','nameExt' => NULL,'acc_id' => '20','acc_name' => 'RIS','acc_description' => 'Agent'),
  array('emp_id' => '2240','lname' => 'Corvera','mname' => 'Alidon','fname' => 'Marielle','nameExt' => NULL,'acc_id' => '7','acc_name' => 'Link Tree','acc_description' => 'Agent'),
  array('emp_id' => '2766','lname' => 'Corvera','mname' => 'Alidon','fname' => 'Lord Chris','nameExt' => NULL,'acc_id' => '58','acc_name' => 'Visual Communications Team','acc_description' => 'Admin'),
  array('emp_id' => '1841','lname' => 'cuarteros','mname' => 'torrigosa','fname' => 'josephine','nameExt' => '','acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2419','lname' => 'Cuizon','mname' => 'Baquita','fname' => 'Roniple','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '513','lname' => 'Daayata','mname' => 'Rauto','fname' => 'Queeny Rose','nameExt' => '','acc_id' => '28','acc_name' => 'Onyx Digital Team','acc_description' => 'Agent'),
  array('emp_id' => '2386','lname' => 'Daba','mname' => 'Litanon','fname' => 'Rebecca','nameExt' => NULL,'acc_id' => '38','acc_name' => 'Training','acc_description' => 'Admin'),
  array('emp_id' => '138','lname' => 'Dableo ','mname' => 'Peligrino','fname' => 'Arsenio, Jr.','nameExt' => 'jr.','acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '1918','lname' => 'Dableo','mname' => 'Pelegrino','fname' => 'Dennis','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '44','lname' => 'Dablo','mname' => 'Balbon','fname' => 'Symonn Reeve','nameExt' => '','acc_id' => '10','acc_name' => 'Service Delivery','acc_description' => 'Admin'),
  array('emp_id' => '2393','lname' => 'Dacoco','mname' => 'Lagat','fname' => 'Apple Grace','nameExt' => NULL,'acc_id' => '61','acc_name' => 'Talent Acquisition','acc_description' => 'Admin'),
  array('emp_id' => '2166','lname' => 'Dadang','mname' => 'Jaramillo','fname' => 'Reniere','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '1598','lname' => 'Dael','mname' => 'Baron','fname' => 'Felipina Paula','nameExt' => '','acc_id' => '76','acc_name' => 'Nurse Dash','acc_description' => 'Agent'),
  array('emp_id' => '2367','lname' => 'Dagbay','mname' => 'Lagria','fname' => 'Jesrael Jay','nameExt' => NULL,'acc_id' => '92','acc_name' => 'Thinkific Chat','acc_description' => 'Agent'),
  array('emp_id' => '2622','lname' => 'Dagbay','mname' => 'Buadlart','fname' => 'Virgilio','nameExt' => 'Jr','acc_id' => '15','acc_name' => 'TelemedRN - USRN','acc_description' => 'Agent'),
  array('emp_id' => '1994','lname' => 'Dago-oc','mname' => 'Simbit','fname' => 'Claribel','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2003','lname' => 'Dagohoy','mname' => 'Macabinlar','fname' => 'Roda Mae','nameExt' => NULL,'acc_id' => '70','acc_name' => 'Workforce Management','acc_description' => 'Admin'),
  array('emp_id' => '2738','lname' => 'Dagondon','mname' => 'Arceno','fname' => 'Kevin John','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '1680','lname' => 'Daguplo','mname' => 'Bacod','fname' => 'Melody','nameExt' => '','acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '1763','lname' => 'Dahunan','mname' => NULL,'fname' => 'Edsa','nameExt' => NULL,'acc_id' => '28','acc_name' => 'Onyx Digital Team','acc_description' => 'Agent'),
  array('emp_id' => '2002','lname' => 'Dalaygon','mname' => 'Tulanda','fname' => 'Jistier','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2749','lname' => 'Dalogdog','mname' => 'Maputi','fname' => 'Johannes Xerxes','nameExt' => NULL,'acc_id' => '79','acc_name' => 'AMP Academy','acc_description' => 'Admin'),
  array('emp_id' => '1360','lname' => 'Damasco','mname' => 'Jamero','fname' => 'Lucille','nameExt' => '','acc_id' => '74','acc_name' => 'Administration','acc_description' => 'Admin'),
  array('emp_id' => '2039','lname' => 'Daug','mname' => 'Fagtanan','fname' => 'Prince Jedrick','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2638','lname' => 'Daug','mname' => 'Sasil','fname' => 'Melchor','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2488','lname' => 'David','mname' => 'Lagrosas','fname' => 'Michael James','nameExt' => NULL,'acc_id' => '70','acc_name' => 'Workforce Management','acc_description' => 'Admin'),
  array('emp_id' => '250','lname' => 'Dayao','mname' => 'Chaves','fname' => 'Liezl','nameExt' => '','acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '2598','lname' => 'De Guzman','mname' => 'Baring','fname' => 'Neneth','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '1432','lname' => 'Decasa','mname' => 'Chiu','fname' => 'Jude Martoni','nameExt' => '','acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '745','lname' => 'Del Puerto','mname' => 'Homillada','fname' => 'Alexis Adriene','nameExt' => '','acc_id' => '94','acc_name' => 'Accounts Managers','acc_description' => 'Admin'),
  array('emp_id' => '788','lname' => 'Del Rosario','mname' => 'Licong','fname' => 'Justine','nameExt' => '','acc_id' => '53','acc_name' => 'Workplace Safety Screenings','acc_description' => 'Agent'),
  array('emp_id' => '1352','lname' => 'Dela Cruz','mname' => 'Amit','fname' => 'Janine','nameExt' => '','acc_id' => '61','acc_name' => 'Talent Acquisition','acc_description' => 'Admin'),
  array('emp_id' => '2377','lname' => 'Dela Cruz','mname' => 'Caguiat','fname' => 'Maria Theonel','nameExt' => NULL,'acc_id' => '17','acc_name' => 'D.A. Lamont','acc_description' => 'Agent'),
  array('emp_id' => '2452','lname' => 'Delauro','mname' => 'Bartiana','fname' => 'Jezzamae','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2135','lname' => 'Delfin','mname' => 'Paladias','fname' => 'Jundel','nameExt' => NULL,'acc_id' => '70','acc_name' => 'Workforce Management','acc_description' => 'Admin'),
  array('emp_id' => '1839','lname' => 'delos reyes','mname' => 'bumanglag','fname' => 'brinda lee','nameExt' => '','acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '1242','lname' => 'Delos Santos','mname' => 'Bangcaya','fname' => 'Dana Marie','nameExt' => '','acc_id' => '42','acc_name' => 'TSN Operations','acc_description' => 'Admin'),
  array('emp_id' => '2322','lname' => 'Delos Santos','mname' => 'Galleto','fname' => 'Earl John','nameExt' => NULL,'acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '76','lname' => 'Densing','mname' => 'Lapiña','fname' => 'Mary Claudette','nameExt' => '','acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '1846','lname' => 'Deocampo','mname' => 'Macababat','fname' => 'Sarah','nameExt' => '','acc_id' => '70','acc_name' => 'Workforce Management','acc_description' => 'Admin'),
  array('emp_id' => '2484','lname' => 'Diaz','mname' => 'Pang','fname' => 'Lael Jhun','nameExt' => NULL,'acc_id' => '34','acc_name' => 'Vistracks','acc_description' => 'Agent'),
  array('emp_id' => '2373','lname' => 'Digamon','mname' => 'Blando','fname' => 'Marne Jemimah','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '139','lname' => 'Diosana','mname' => 'Umacob','fname' => 'Ma. Farlane June','nameExt' => NULL,'acc_id' => '42','acc_name' => 'TSN Operations','acc_description' => 'Admin'),
  array('emp_id' => '38','lname' => 'Divinagracia','mname' => 'Tabernero','fname' => 'Eidylene','nameExt' => '','acc_id' => '39','acc_name' => 'MWAR Tracking','acc_description' => 'Agent'),
  array('emp_id' => '53','lname' => 'Dizon','mname' => 'Amestad','fname' => 'Marbin','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2290','lname' => 'Dolera','mname' => 'Macalos','fname' => 'Aaron','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2629','lname' => 'Dolores','mname' => NULL,'fname' => 'Kathlyn Ricci','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2768','lname' => 'Domo','mname' => 'Corona','fname' => 'Cejay Diane','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2753','lname' => 'Donaldo','mname' => 'Pagaling','fname' => 'Loraine Mae','nameExt' => NULL,'acc_id' => '96','acc_name' => 'Human Resources and Administration','acc_description' => 'Admin'),
  array('emp_id' => '1857','lname' => 'Dulfo','mname' => 'Abayon','fname' => 'Zachary Benedict','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '980','lname' => 'Duran','mname' => 'Valdehueza','fname' => 'Niño Mae','nameExt' => NULL,'acc_id' => '13','acc_name' => 'Managing Director','acc_description' => 'Admin'),
  array('emp_id' => '2426','lname' => 'Duran','mname' => 'Gulapo','fname' => 'Stephanie','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2721','lname' => 'Dusaran','mname' => 'Felecio','fname' => 'Paul Vincent','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '1578','lname' => 'Ebalan','mname' => 'Herbias','fname' => 'John Michael','nameExt' => '','acc_id' => '83','acc_name' => 'Lone Wolf Cebu','acc_description' => 'Agent'),
  array('emp_id' => '2639','lname' => 'Eballe','mname' => 'Zamayla','fname' => 'April Apple Mae','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2186','lname' => 'Echeveria','mname' => 'Bade','fname' => 'Ivan','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '1265','lname' => 'Econar','mname' => 'Aniñon','fname' => 'Nazer James ','nameExt' => '','acc_id' => '45','acc_name' => 'Genuent','acc_description' => 'Agent'),
  array('emp_id' => '1542','lname' => 'Ederango','mname' => 'Rampola','fname' => 'Madel','nameExt' => '','acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2566','lname' => 'Edralin','mname' => 'Padua','fname' => 'Danielle Beaulah','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2409','lname' => 'Egargo','mname' => 'Buslon','fname' => 'Cris Paul','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2692','lname' => 'Elan','mname' => 'Musa','fname' => 'Rhea Bernadeth','nameExt' => NULL,'acc_id' => '86','acc_name' => 'Organization Excellence','acc_description' => 'Admin'),
  array('emp_id' => '2618','lname' => 'Elay','mname' => 'Rosales','fname' => 'Nelson Nathaniel','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2116','lname' => 'Elbambo','mname' => 'Mendoza','fname' => 'Hannah Isabel','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2625','lname' => 'Elcano','mname' => 'Villamor','fname' => 'Jey Ar','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '918','lname' => 'Elizaga','mname' => 'Inocian','fname' => 'Celeste Rose','nameExt' => NULL,'acc_id' => '75','acc_name' => 'Purchasing and Inventory Team','acc_description' => 'Admin'),
  array('emp_id' => '2532','lname' => 'Elliot','mname' => 'Bumaya','fname' => 'Jerson','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '272','lname' => 'Ello','mname' => 'Gonzales','fname' => 'Mae Christine Dawn 2','nameExt' => '','acc_id' => '26','acc_name' => 'Iscential','acc_description' => 'Agent'),
  array('emp_id' => '2446','lname' => 'Emuelin','mname' => 'Paroginog','fname' => 'Joshua','nameExt' => NULL,'acc_id' => '47','acc_name' => 'Trademark Engine','acc_description' => 'Agent'),
  array('emp_id' => '1232','lname' => 'Endan','mname' => 'Andales','fname' => 'Acer','nameExt' => NULL,'acc_id' => '69','acc_name' => 'Onyx Escalations Team','acc_description' => 'Agent'),
  array('emp_id' => '438','lname' => 'Enerio','mname' => 'Gabo','fname' => 'Dave Abe','nameExt' => '','acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2688','lname' => 'Esclamado','mname' => 'Garcia','fname' => 'Lesther John','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '88','lname' => 'Escoto','mname' => 'Villaester','fname' => 'James Jayne','nameExt' => '','acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '2178','lname' => 'Esling','mname' => 'Beltran','fname' => 'Heart Frances','nameExt' => NULL,'acc_id' => '15','acc_name' => 'TelemedRN - USRN','acc_description' => 'Agent'),
  array('emp_id' => '2680','lname' => 'Espada','mname' => 'Lao','fname' => 'Ramphel','nameExt' => NULL,'acc_id' => '34','acc_name' => 'Vistracks','acc_description' => 'Agent'),
  array('emp_id' => '2722','lname' => 'Espada','mname' => 'Lao','fname' => 'Roxan','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '1935','lname' => 'Espina','mname' => 'S.','fname' => 'Adolph','nameExt' => NULL,'acc_id' => '83','acc_name' => 'Lone Wolf Cebu','acc_description' => 'Agent'),
  array('emp_id' => '2455','lname' => 'Espiritu','mname' => 'Bacolod','fname' => 'John Michael','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2604','lname' => 'Fabre','mname' => 'Sogoc','fname' => 'Rhodora','nameExt' => NULL,'acc_id' => '53','acc_name' => 'Workplace Safety Screenings','acc_description' => 'Agent'),
  array('emp_id' => '1396','lname' => 'Fabrigas','mname' => NULL,'fname' => 'Kimberly','nameExt' => '','acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '2239','lname' => 'Famas','mname' => 'Estante','fname' => 'Trustin Meg Ryann','nameExt' => NULL,'acc_id' => '7','acc_name' => 'Link Tree','acc_description' => 'Agent'),
  array('emp_id' => '2712','lname' => 'Felonia','mname' => 'Apuya','fname' => 'Rincel Joy','nameExt' => NULL,'acc_id' => '15','acc_name' => 'TelemedRN - USRN','acc_description' => 'Agent'),
  array('emp_id' => '1742','lname' => 'Fernandez','mname' => 'N/A','fname' => 'John Wesley','nameExt' => NULL,'acc_id' => '92','acc_name' => 'Thinkific Chat','acc_description' => 'Agent'),
  array('emp_id' => '2445','lname' => 'Fernandez','mname' => 'Lluch','fname' => 'Vale Michael Eugene','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2786','lname' => 'Filipino','mname' => 'Nadera','fname' => 'Marlon Neil','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '1976','lname' => 'Flavell','mname' => 'Ellevera','fname' => 'Zachariah Matthew','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '1115','lname' => 'Flores','mname' => 'Ranes','fname' => 'Cherry Mae ','nameExt' => '','acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '2465','lname' => 'Flores','mname' => 'Ybañez','fname' => 'Monette','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2471','lname' => 'Flores','mname' => 'Fernan','fname' => 'Dino','nameExt' => NULL,'acc_id' => '66','acc_name' => 'Innovations and Customer Solutions','acc_description' => 'Admin'),
  array('emp_id' => '1445','lname' => 'Fontilar','mname' => 'Penalosa','fname' => 'elfon jones','nameExt' => NULL,'acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '1872','lname' => 'Forro','mname' => 'Patolombu','fname' => 'Juvy','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '641','lname' => 'Francisco','mname' => 'Galarpe','fname' => 'Perry','nameExt' => NULL,'acc_id' => '62','acc_name' => 'Organization Development and Strategy','acc_description' => 'Admin'),
  array('emp_id' => '1844','lname' => 'fuentes','mname' => 'fabria','fname' => 'lloyd','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '1929','lname' => 'Fuentes','mname' => 'Cabutchao','fname' => 'Cheryl','nameExt' => NULL,'acc_id' => '61','acc_name' => 'Talent Acquisition','acc_description' => 'Admin'),
  array('emp_id' => '2125','lname' => 'Fuentes','mname' => 'Silvela','fname' => 'Carmelo Philippe','nameExt' => NULL,'acc_id' => '7','acc_name' => 'Link Tree','acc_description' => 'Agent'),
  array('emp_id' => '2221','lname' => 'Fuentes','mname' => 'Helarman','fname' => 'Roni Jane','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2701','lname' => 'Fuentes','mname' => 'Villa','fname' => 'Ralph Anthony','nameExt' => NULL,'acc_id' => '79','acc_name' => 'AMP Academy','acc_description' => 'Admin'),
  array('emp_id' => '2723','lname' => 'Gaabucayan','mname' => 'Macale','fname' => 'Priscilla Wayne','nameExt' => NULL,'acc_id' => '59','acc_name' => 'ETC Institute','acc_description' => 'Agent'),
  array('emp_id' => '1281','lname' => 'Gabronino','mname' => 'Cabel','fname' => 'Joselle Ann','nameExt' => '','acc_id' => '53','acc_name' => 'Workplace Safety Screenings','acc_description' => 'Agent'),
  array('emp_id' => '97','lname' => 'Gaela','mname' => 'Yañez','fname' => 'Sheralen','nameExt' => '','acc_id' => '94','acc_name' => 'Accounts Managers','acc_description' => 'Admin'),
  array('emp_id' => '2571','lname' => 'Gaid','mname' => 'Pantao','fname' => 'Spencer Neil','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2782','lname' => 'Gaid','mname' => 'Villamor','fname' => 'Leonil','nameExt' => NULL,'acc_id' => '7','acc_name' => 'Link Tree','acc_description' => 'Agent'),
  array('emp_id' => '2608','lname' => 'Galangke','mname' => 'Capoy','fname' => 'Shiela Mae','nameExt' => NULL,'acc_id' => '47','acc_name' => 'Trademark Engine','acc_description' => 'Agent'),
  array('emp_id' => '2526','lname' => 'Galendez','mname' => 'Romorosa','fname' => 'Philip Cezar Augustus','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2061','lname' => 'Galera','mname' => 'Rojaa','fname' => 'Joyce Marie','nameExt' => NULL,'acc_id' => '53','acc_name' => 'Workplace Safety Screenings','acc_description' => 'Agent'),
  array('emp_id' => '2319','lname' => 'Galera','mname' => 'Rojas','fname' => 'Jennifer','nameExt' => NULL,'acc_id' => '92','acc_name' => 'Thinkific Chat','acc_description' => 'Agent'),
  array('emp_id' => '2544','lname' => 'Galicia','mname' => 'Valle','fname' => 'Jossan Lee','nameExt' => NULL,'acc_id' => '70','acc_name' => 'Workforce Management','acc_description' => 'Admin'),
  array('emp_id' => '790','lname' => 'Gallardo','mname' => 'Valiente','fname' => 'Marjorie','nameExt' => '','acc_id' => '53','acc_name' => 'Workplace Safety Screenings','acc_description' => 'Agent'),
  array('emp_id' => '1913','lname' => 'Galve','mname' => 'S.','fname' => 'Filjames','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2498','lname' => 'Gamale','mname' => 'Cuizon','fname' => 'Republica','nameExt' => NULL,'acc_id' => '17','acc_name' => 'D.A. Lamont','acc_description' => 'Agent'),
  array('emp_id' => '1158','lname' => 'Garcia','mname' => 'Jabiniar','fname' => 'Rowell','nameExt' => NULL,'acc_id' => '28','acc_name' => 'Onyx Digital Team','acc_description' => 'Agent'),
  array('emp_id' => '2583','lname' => 'Garcia','mname' => 'Jaramillo','fname' => 'Kellen Kerr','nameExt' => NULL,'acc_id' => '62','acc_name' => 'Organization Development and Strategy','acc_description' => 'Admin'),
  array('emp_id' => '2358','lname' => 'Garlit','mname' => 'Omamalin','fname' => 'Kristine Joy','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '1981','lname' => 'Garsuta','mname' => 'Godmalin','fname' => 'Elaine','nameExt' => NULL,'acc_id' => '92','acc_name' => 'Thinkific Chat','acc_description' => 'Agent'),
  array('emp_id' => '2542','lname' => 'Gayanilo','mname' => 'Guiñeta','fname' => 'Danna Christine','nameExt' => NULL,'acc_id' => '65','acc_name' => 'Quality Assurance','acc_description' => 'Admin'),
  array('emp_id' => '1683','lname' => 'Gayloa','mname' => 'Gallardo ','fname' => 'James Anthony ','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2775','lname' => 'Generalao','mname' => 'Cabarce','fname' => 'Bryan Caisar','nameExt' => NULL,'acc_id' => '5','acc_name' => 'Safety  and Security','acc_description' => 'Admin'),
  array('emp_id' => '2091','lname' => 'Genon','mname' => 'Colonia','fname' => 'Aira Grace','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2572','lname' => 'Ghan','mname' => 'Peralta','fname' => 'Maria Elaine Angelica','nameExt' => NULL,'acc_id' => '15','acc_name' => 'TelemedRN - USRN','acc_description' => 'Agent'),
  array('emp_id' => '2371','lname' => 'Gido','mname' => 'Honculada','fname' => 'Rogelio','nameExt' => 'Jr','acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '1260','lname' => 'Go','mname' => 'Baguinon','fname' => 'Anecito','nameExt' => 'Jr','acc_id' => '80','acc_name' => 'Onyx Customer Service (Cebu)','acc_description' => 'Agent'),
  array('emp_id' => '2113','lname' => 'Go','mname' => 'Santos','fname' => 'Mercylyn','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2427','lname' => 'Go','mname' => 'Saludsod','fname' => 'Carl Ian','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2724','lname' => 'Gomez','mname' => 'Labis','fname' => 'Jayson','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '1655','lname' => 'Gonzales','mname' => 'eroy','fname' => 'Arl Cyena','nameExt' => NULL,'acc_id' => '83','acc_name' => 'Lone Wolf Cebu','acc_description' => 'Agent'),
  array('emp_id' => '2503','lname' => 'Gonzales','mname' => 'Cagmat','fname' => 'Benjie','nameExt' => NULL,'acc_id' => '5','acc_name' => 'Safety  and Security','acc_description' => 'Admin'),
  array('emp_id' => '2787','lname' => 'Gonzales','mname' => 'Limbaco','fname' => 'Clark Luvin','nameExt' => NULL,'acc_id' => '89','acc_name' => 'G&A Partners','acc_description' => 'Agent'),
  array('emp_id' => '482','lname' => 'Gubalane','mname' => 'Veloso','fname' => 'Rejane Mae','nameExt' => '','acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '142','lname' => 'Guinea','mname' => 'Barro','fname' => 'Efren','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '650','lname' => 'Guinlamon','mname' => 'Arcena','fname' => 'Marchelle Kate','nameExt' => '','acc_id' => '26','acc_name' => 'Iscential','acc_description' => 'Agent'),
  array('emp_id' => '2354','lname' => 'Guiritan','mname' => 'Canoy','fname' => 'Ian Rey','nameExt' => NULL,'acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '236','lname' => 'Gulapo','mname' => 'Nacua','fname' => 'Aiza Febb','nameExt' => '','acc_id' => '8','acc_name' => 'Bulb America','acc_description' => 'Agent'),
  array('emp_id' => '2686','lname' => 'Gutierrez','mname' => 'Añasco','fname' => 'Mateo','nameExt' => 'jr','acc_id' => '41','acc_name' => 'Storage Plus','acc_description' => 'Agent'),
  array('emp_id' => '2509','lname' => 'Gutual','mname' => 'Kawaling','fname' => 'Raymond','nameExt' => NULL,'acc_id' => '96','acc_name' => 'Human Resources and Administration','acc_description' => 'Admin'),
  array('emp_id' => '2683','lname' => 'Halina','mname' => 'Idano','fname' => 'Cipriabel Dale','nameExt' => NULL,'acc_id' => '76','acc_name' => 'Nurse Dash','acc_description' => 'Agent'),
  array('emp_id' => '1544','lname' => 'Hernan','mname' => 'Dablio','fname' => 'Rutchel','nameExt' => '','acc_id' => '28','acc_name' => 'Onyx Digital Team','acc_description' => 'Agent'),
  array('emp_id' => '2378','lname' => 'Hernandez','mname' => 'Valcurza','fname' => 'Zed Reh','nameExt' => NULL,'acc_id' => '20','acc_name' => 'RIS','acc_description' => 'Agent'),
  array('emp_id' => '1885','lname' => 'Hormillosa','mname' => 'Cansancio','fname' => 'Lee Marvin','nameExt' => NULL,'acc_id' => '42','acc_name' => 'TSN Operations','acc_description' => 'Admin'),
  array('emp_id' => '1689','lname' => 'Hubaran','mname' => 'Echem','fname' => 'Jim Lou','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2606','lname' => 'Huervas','mname' => 'Alima','fname' => 'Lumen Cristy','nameExt' => NULL,'acc_id' => '47','acc_name' => 'Trademark Engine','acc_description' => 'Agent'),
  array('emp_id' => '1807','lname' => 'Ibasco','mname' => 'Maestrado','fname' => 'Cyril','nameExt' => '','acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2789','lname' => 'Ilustre','mname' => 'Capin','fname' => 'John Lester','nameExt' => NULL,'acc_id' => '89','acc_name' => 'G&A Partners','acc_description' => 'Agent'),
  array('emp_id' => '93','lname' => 'Intud','mname' => 'Fermano','fname' => 'Windy','nameExt' => NULL,'acc_id' => '65','acc_name' => 'Quality Assurance','acc_description' => 'Admin'),
  array('emp_id' => '2600','lname' => 'Jalagat','mname' => 'Jamlan','fname' => 'Ella Rica Everly','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '1667','lname' => 'JALAPADAN','mname' => 'BAUTISTA','fname' => 'JENNY BOY','nameExt' => '','acc_id' => '70','acc_name' => 'Workforce Management','acc_description' => 'Admin'),
  array('emp_id' => '2603','lname' => 'Jamila','mname' => NULL,'fname' => 'Darlene Mhaye','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2411','lname' => 'Janio','mname' => 'Balignot','fname' => 'Ann Michelle','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2189','lname' => 'Japay','mname' => 'Cornepillo','fname' => 'Geraldine Valerie','nameExt' => NULL,'acc_id' => '88','acc_name' => 'Accounting','acc_description' => 'Admin'),
  array('emp_id' => '1675','lname' => 'Jaquilmac','mname' => 'Pamplona','fname' => 'Joseph','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2196','lname' => 'Jawom','mname' => 'Aliwate','fname' => 'Aljun','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2214','lname' => 'Jimenez','mname' => 'Gallano','fname' => 'Annaline','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2459','lname' => 'Jimenez','mname' => 'Campano','fname' => 'Jevie','nameExt' => NULL,'acc_id' => '5','acc_name' => 'Safety  and Security','acc_description' => 'Admin'),
  array('emp_id' => '2430','lname' => 'Jimeno','mname' => 'Cañete','fname' => 'Neil Vier','nameExt' => NULL,'acc_id' => '62','acc_name' => 'Organization Development and Strategy','acc_description' => 'Admin'),
  array('emp_id' => '2199','lname' => 'Joloyohoy','mname' => 'Auguis','fname' => 'Blaisae','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '349','lname' => 'Jorolan','mname' => 'Alejado','fname' => 'Kim Ira Mariae','nameExt' => '','acc_id' => '65','acc_name' => 'Quality Assurance','acc_description' => 'Admin'),
  array('emp_id' => '2034','lname' => 'Jorolan','mname' => 'Lumanta','fname' => 'Dexter','nameExt' => NULL,'acc_id' => '83','acc_name' => 'Lone Wolf Cebu','acc_description' => 'Agent'),
  array('emp_id' => '2449','lname' => 'Josol','mname' => 'Ansigon','fname' => 'Eduardo','nameExt' => 'jr','acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '185','lname' => 'Juarez','mname' => 'Alcantar','fname' => 'Noelle Noreen','nameExt' => NULL,'acc_id' => '13','acc_name' => 'Managing Director','acc_description' => 'Admin'),
  array('emp_id' => '1995','lname' => 'Jumamil','mname' => 'Salon','fname' => 'Neslyn','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2797','lname' => 'Kayano','mname' => 'Quirante','fname' => 'Kayano','nameExt' => NULL,'acc_id' => '70','acc_name' => 'Workforce Management','acc_description' => 'Admin'),
  array('emp_id' => '2469','lname' => 'Khu','mname' => 'Francisco','fname' => 'Mitzban','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '1769','lname' => 'La Peña','mname' => 'Bagolcol','fname' => 'Elizabeth','nameExt' => '','acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '1975','lname' => 'Labadan','mname' => 'Salesa','fname' => 'Sandy','nameExt' => NULL,'acc_id' => '92','acc_name' => 'Thinkific Chat','acc_description' => 'Agent'),
  array('emp_id' => '2705','lname' => 'Labadan','mname' => 'Balino','fname' => 'Marco','nameExt' => NULL,'acc_id' => '5','acc_name' => 'Safety  and Security','acc_description' => 'Admin'),
  array('emp_id' => '2323','lname' => 'Labawan','mname' => 'Japuz','fname' => 'Ruel','nameExt' => NULL,'acc_id' => '100','acc_name' => 'RocketBuildr','acc_description' => 'Agent'),
  array('emp_id' => '2479','lname' => 'Labo','mname' => 'Jamodiong','fname' => 'Shane Janmarie','nameExt' => NULL,'acc_id' => '53','acc_name' => 'Workplace Safety Screenings','acc_description' => 'Agent'),
  array('emp_id' => '2120','lname' => 'Labradores','mname' => 'Agcopra','fname' => 'Ena Therese','nameExt' => NULL,'acc_id' => '61','acc_name' => 'Talent Acquisition','acc_description' => 'Admin'),
  array('emp_id' => '2750','lname' => 'Ladera','mname' => 'Jardinico','fname' => 'Kara Mareh','nameExt' => NULL,'acc_id' => '79','acc_name' => 'AMP Academy','acc_description' => 'Admin'),
  array('emp_id' => '2623','lname' => 'Lagamon','mname' => 'Ugay','fname' => 'Alyssa Ruby','nameExt' => NULL,'acc_id' => '15','acc_name' => 'TelemedRN - USRN','acc_description' => 'Agent'),
  array('emp_id' => '2658','lname' => 'Lagamon','mname' => 'Penaso','fname' => 'Dianne Rose','nameExt' => NULL,'acc_id' => '101','acc_name' => 'Lone Wolf Accounting','acc_description' => 'Agent'),
  array('emp_id' => '2575','lname' => 'Lagas','mname' => 'Limare','fname' => 'Joseph Robert','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2348','lname' => 'Lagobis','mname' => 'Opiso','fname' => 'Adrian','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2232','lname' => 'Lagura','mname' => 'Paraguya','fname' => 'John Rey','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2677','lname' => 'Lamumay','mname' => 'Baldivino','fname' => 'Jocelyn','nameExt' => NULL,'acc_id' => '51','acc_name' => 'Executive Support Team','acc_description' => 'Admin'),
  array('emp_id' => '371','lname' => 'Landero','mname' => 'Angelio','fname' => 'Michaela','nameExt' => '','acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2403','lname' => 'Landero','mname' => 'Natividad','fname' => 'May-Ann','nameExt' => NULL,'acc_id' => '34','acc_name' => 'Vistracks','acc_description' => 'Agent'),
  array('emp_id' => '2448','lname' => 'Lao','mname' => 'Dumdum','fname' => 'Anthony Joshua','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '81','lname' => 'Lapeciros','mname' => 'Pimentel','fname' => 'Monydee','nameExt' => '','acc_id' => '94','acc_name' => 'Accounts Managers','acc_description' => 'Admin'),
  array('emp_id' => '2144','lname' => 'Laurente','mname' => 'Saplot','fname' => 'Threcia Ann','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2397','lname' => 'Lauron','mname' => 'Barotil','fname' => 'Rea Angelica','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2460','lname' => 'Lavilla','mname' => 'Garcia','fname' => 'Roxzanne','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '1646','lname' => 'Layno','mname' => 'Dalapu','fname' => 'Reshell Carmae Duchess ','nameExt' => '','acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2708','lname' => 'Lee','mname' => 'Adaza','fname' => 'Czarina Louise','nameExt' => NULL,'acc_id' => '15','acc_name' => 'TelemedRN - USRN','acc_description' => 'Agent'),
  array('emp_id' => '1983','lname' => 'Legaspi','mname' => 'Pimentel','fname' => 'Marie Joy','nameExt' => NULL,'acc_id' => '65','acc_name' => 'Quality Assurance','acc_description' => 'Admin'),
  array('emp_id' => '2663','lname' => 'Libron','mname' => 'Acasio','fname' => 'Gregg Lemuel','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2640','lname' => 'Limosnero','mname' => 'Pacudan','fname' => 'Shalom Queen','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2004','lname' => 'Longno','mname' => 'Aling','fname' => 'Audrey','nameExt' => NULL,'acc_id' => '70','acc_name' => 'Workforce Management','acc_description' => 'Admin'),
  array('emp_id' => '2664','lname' => 'Longno','mname' => 'Aling','fname' => 'Stephanie','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '1528','lname' => 'Lonio','mname' => 'Lleoren','fname' => 'Merasol','nameExt' => NULL,'acc_id' => '51','acc_name' => 'Executive Support Team','acc_description' => 'Admin'),
  array('emp_id' => '1685','lname' => 'Lugma','mname' => 'Tomon','fname' => 'Marlo','nameExt' => NULL,'acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '2401','lname' => 'Lumacang','mname' => 'Pamplona','fname' => 'Nick Michael','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '1366','lname' => 'Lumahang','mname' => 'Tayros','fname' => 'Jeffryl Joy','nameExt' => '','acc_id' => '9','acc_name' => 'MWAR Warranty','acc_description' => 'Agent'),
  array('emp_id' => '1586','lname' => 'Lusay','mname' => 'Tolentino','fname' => 'Matlumar John','nameExt' => NULL,'acc_id' => '73','acc_name' => 'Alpha Lion','acc_description' => 'Agent'),
  array('emp_id' => '2592','lname' => 'Mabayo','mname' => 'Ubalde','fname' => 'Rikki Karryl','nameExt' => NULL,'acc_id' => '25','acc_name' => 'TLF','acc_description' => 'Agent'),
  array('emp_id' => '2394','lname' => 'Mabilen','mname' => 'Comcom','fname' => 'Wilson','nameExt' => 'jr','acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '1815','lname' => 'Macahibag','mname' => 'Otod','fname' => 'Pedrito','nameExt' => 'Jr','acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '1334','lname' => 'Macalam','mname' => 'Pulpul','fname' => 'Daryl Hannah','nameExt' => '','acc_id' => '92','acc_name' => 'Thinkific Chat','acc_description' => 'Agent'),
  array('emp_id' => '2620','lname' => 'Macaspac','mname' => NULL,'fname' => 'Sarah Joyce','nameExt' => NULL,'acc_id' => '91','acc_name' => 'Learning and Development','acc_description' => 'Admin'),
  array('emp_id' => '2147','lname' => 'Maceda','mname' => 'Balaba','fname' => 'Harold','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2627','lname' => 'Machado','mname' => 'Mowit','fname' => 'April Rey','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2129','lname' => 'Macua','mname' => 'Baldivino','fname' => 'Princess','nameExt' => NULL,'acc_id' => '51','acc_name' => 'Executive Support Team','acc_description' => 'Admin'),
  array('emp_id' => '2246','lname' => 'Madrigal','mname' => 'Sunga','fname' => 'Jose Noel','nameExt' => NULL,'acc_id' => '83','acc_name' => 'Lone Wolf Cebu','acc_description' => 'Agent'),
  array('emp_id' => '1770','lname' => 'Mag-away','mname' => 'Caguil','fname' => 'Jeason','nameExt' => NULL,'acc_id' => '69','acc_name' => 'Onyx Escalations Team','acc_description' => 'Agent'),
  array('emp_id' => '857','lname' => 'Magallones','mname' => 'Abrogueña','fname' => 'Nina Isobelle','nameExt' => '','acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '2357','lname' => 'Maghirang','mname' => 'Amahit','fname' => 'Keziah Kay','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '334','lname' => 'Maghuyop','mname' => 'Gaid','fname' => 'Jessfil','nameExt' => '','acc_id' => '34','acc_name' => 'Vistracks','acc_description' => 'Agent'),
  array('emp_id' => '1963','lname' => 'Maglangit','mname' => 'Varquez','fname' => 'Goldie Mae','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '26','lname' => 'Magparoc','mname' => 'Bade','fname' => 'Mark Jeffrey','nameExt' => '','acc_id' => '37','acc_name' => 'Software Programming','acc_description' => 'Admin'),
  array('emp_id' => '2478','lname' => 'Maguyon','mname' => NULL,'fname' => 'Ronald','nameExt' => NULL,'acc_id' => '38','acc_name' => 'Training','acc_description' => 'Admin'),
  array('emp_id' => '1065','lname' => 'Mahinay','mname' => 'Suarin','fname' => 'Theresa','nameExt' => '','acc_id' => '43','acc_name' => 'Admin: Cebu','acc_description' => 'Admin'),
  array('emp_id' => '1548','lname' => 'MALIG','mname' => 'DANDUAN','fname' => 'NADINE','nameExt' => '','acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '477','lname' => 'Malones','mname' => 'Absuelo','fname' => 'Bianca Marielle','nameExt' => '','acc_id' => '26','acc_name' => 'Iscential','acc_description' => 'Agent'),
  array('emp_id' => '2106','lname' => 'Mamac','mname' => 'Campo','fname' => 'Phillip John','nameExt' => NULL,'acc_id' => '7','acc_name' => 'Link Tree','acc_description' => 'Agent'),
  array('emp_id' => '2609','lname' => 'Mandoñahan','mname' => 'Teologo','fname' => 'Ian Dave','nameExt' => NULL,'acc_id' => '47','acc_name' => 'Trademark Engine','acc_description' => 'Agent'),
  array('emp_id' => '1744','lname' => 'Manla','mname' => 'N/A','fname' => 'Raffy Mar','nameExt' => NULL,'acc_id' => '16','acc_name' => 'CT-Miami','acc_description' => 'Agent'),
  array('emp_id' => '2462','lname' => 'Manliguis','mname' => 'Odvina','fname' => 'John Dave','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '1959','lname' => 'Manriquez','mname' => 'Arceo','fname' => 'Adrian','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2641','lname' => 'Manseguiao','mname' => 'Deña','fname' => 'Louis Marie','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2746','lname' => 'Mantagbo','mname' => 'Pamplona','fname' => 'Kathlen Rose','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2142','lname' => 'Manzanades','mname' => 'Figura','fname' => 'Kate Antoniette','nameExt' => NULL,'acc_id' => '53','acc_name' => 'Workplace Safety Screenings','acc_description' => 'Agent'),
  array('emp_id' => '2601','lname' => 'Mappala','mname' => 'Moncada','fname' => 'Francis Ian','nameExt' => NULL,'acc_id' => '5','acc_name' => 'Safety  and Security','acc_description' => 'Admin'),
  array('emp_id' => '2167','lname' => 'Maputi','mname' => 'Ares','fname' => 'Ellenor','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2668','lname' => 'Marasigan','mname' => 'Bunsato','fname' => 'Jayzar','nameExt' => NULL,'acc_id' => '66','acc_name' => 'Innovations and Customer Solutions','acc_description' => 'Admin'),
  array('emp_id' => '2773','lname' => 'Mariano','mname' => 'Circulado','fname' => 'John Emmar','nameExt' => NULL,'acc_id' => '6','acc_name' => 'Trademark Engine Retention','acc_description' => 'Agent'),
  array('emp_id' => '2678','lname' => 'Martil','mname' => 'Apilan','fname' => 'Artchimae','nameExt' => NULL,'acc_id' => '9','acc_name' => 'MWAR Warranty','acc_description' => 'Agent'),
  array('emp_id' => '526','lname' => 'Martinez','mname' => 'Alferez','fname' => 'Assley','nameExt' => '','acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '1697','lname' => 'Masiba','mname' => 'Bajuno','fname' => 'Keisha Nell','nameExt' => NULL,'acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '2642','lname' => 'Mataganas','mname' => 'Bayonas','fname' => 'Nolie','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2245','lname' => 'Matalines','mname' => 'Panganiban','fname' => 'Ma. Ariela Daneca','nameExt' => NULL,'acc_id' => '61','acc_name' => 'Talent Acquisition','acc_description' => 'Admin'),
  array('emp_id' => '2590','lname' => 'Matildo','mname' => 'Fano','fname' => 'Roann','nameExt' => NULL,'acc_id' => '34','acc_name' => 'Vistracks','acc_description' => 'Agent'),
  array('emp_id' => '2560','lname' => 'Maxino','mname' => 'Uriarte','fname' => 'Christian Mar','nameExt' => NULL,'acc_id' => '71','acc_name' => 'Center','acc_description' => 'Agent'),
  array('emp_id' => '1478','lname' => 'Medado','mname' => 'N/A','fname' => 'Sherlyn','nameExt' => '','acc_id' => '40','acc_name' => 'MyMDConnect','acc_description' => 'Agent'),
  array('emp_id' => '2501','lname' => 'Mediado','mname' => 'Mordeno','fname' => 'Rose Anne','nameExt' => NULL,'acc_id' => '61','acc_name' => 'Talent Acquisition','acc_description' => 'Admin'),
  array('emp_id' => '2391','lname' => 'Medina','mname' => 'Agnes','fname' => 'Christelle','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2643','lname' => 'Melendez','mname' => 'Balacuit','fname' => 'Mary Chayne','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '229','lname' => 'Mendez','mname' => 'Preciado','fname' => 'Airez','nameExt' => '','acc_id' => '8','acc_name' => 'Bulb America','acc_description' => 'Agent'),
  array('emp_id' => '83','lname' => 'Mendoza','mname' => 'Diolazo','fname' => 'Ranie','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2774','lname' => 'Meredor','mname' => 'Ravelo','fname' => 'Kenneth','nameExt' => NULL,'acc_id' => '6','acc_name' => 'Trademark Engine Retention','acc_description' => 'Agent'),
  array('emp_id' => '1367','lname' => 'Merlas','mname' => 'Noval','fname' => 'Jane Michelle','nameExt' => '','acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '1789','lname' => 'Micabalo','mname' => 'Cinco','fname' => 'Joanne','nameExt' => NULL,'acc_id' => '92','acc_name' => 'Thinkific Chat','acc_description' => 'Agent'),
  array('emp_id' => '1533','lname' => 'Micayabas','mname' => 'Layao','fname' => 'Ailyn','nameExt' => NULL,'acc_id' => '93','acc_name' => 'Employee Relations','acc_description' => 'Admin'),
  array('emp_id' => '2751','lname' => 'Mije','mname' => 'Campomanes','fname' => 'Kassandra','nameExt' => NULL,'acc_id' => '79','acc_name' => 'AMP Academy','acc_description' => 'Admin'),
  array('emp_id' => '2364','lname' => 'Mira','mname' => 'Guerra','fname' => 'Vonette Edmel','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '1595','lname' => 'Mojello','mname' => 'Beniga','fname' => 'Nimrod','nameExt' => '','acc_id' => '49','acc_name' => 'Onyx CS Wheels','acc_description' => 'Agent'),
  array('emp_id' => '2363','lname' => 'Molato','mname' => 'Paderanga','fname' => 'Jercelo','nameExt' => NULL,'acc_id' => '61','acc_name' => 'Talent Acquisition','acc_description' => 'Admin'),
  array('emp_id' => '2513','lname' => 'Moldez','mname' => 'n/a','fname' => 'Chiza Eufe','nameExt' => NULL,'acc_id' => '71','acc_name' => 'Center','acc_description' => 'Agent'),
  array('emp_id' => '1927','lname' => 'Mole','mname' => 'Poyogao','fname' => 'Concepcion Mariel','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2624','lname' => 'Moleño','mname' => 'Edul','fname' => 'John Michael','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '1904','lname' => 'Montargo','mname' => 'Nambatac','fname' => 'Bea','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '1168','lname' => 'Montecillo','mname' => 'Romero','fname' => 'Marvi Leonhardt','nameExt' => '','acc_id' => '65','acc_name' => 'Quality Assurance','acc_description' => 'Admin'),
  array('emp_id' => '2670','lname' => 'Montecir','mname' => 'Gullem','fname' => 'Kent Jaymar','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2779','lname' => 'Montejo','mname' => 'Yamut','fname' => 'Jerica','nameExt' => NULL,'acc_id' => '71','acc_name' => 'Center','acc_description' => 'Agent'),
  array('emp_id' => '2736','lname' => 'Moreno','mname' => NULL,'fname' => 'Almebeth','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2443','lname' => 'Mozar','mname' => 'None','fname' => 'Nathaniel Lorenz','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2665','lname' => 'Mugot','mname' => 'Caalaman','fname' => 'Claire','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '1461','lname' => 'Munar','mname' => 'Uao','fname' => 'Michelle','nameExt' => NULL,'acc_id' => '51','acc_name' => 'Executive Support Team','acc_description' => 'Admin'),
  array('emp_id' => '2310','lname' => 'Muñez','mname' => 'Suminig','fname' => 'Aprille kate','nameExt' => NULL,'acc_id' => '8','acc_name' => 'Bulb America','acc_description' => 'Agent'),
  array('emp_id' => '2428','lname' => 'Nabor','mname' => 'Lolong','fname' => 'Eljen','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2234','lname' => 'Nacua','mname' => 'Habagat','fname' => 'Danilo','nameExt' => 'jr','acc_id' => '56','acc_name' => 'Helpdesk','acc_description' => 'Admin'),
  array('emp_id' => '2725','lname' => 'Naguita','mname' => 'Casinillo','fname' => 'Princess Jenny Lou','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '1909','lname' => 'Naldo','mname' => 'Dungo','fname' => 'Jessa May','nameExt' => NULL,'acc_id' => '26','acc_name' => 'Iscential','acc_description' => 'Agent'),
  array('emp_id' => '2538','lname' => 'Nalla','mname' => 'Aman','fname' => 'Erlie Bob','nameExt' => NULL,'acc_id' => '17','acc_name' => 'D.A. Lamont','acc_description' => 'Agent'),
  array('emp_id' => '1984','lname' => 'Namoco','mname' => 'Sopranes','fname' => 'Kristine Kate','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2299','lname' => 'Napalla','mname' => 'Pacuño','fname' => 'Princess Mae','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2646','lname' => 'Napone','mname' => 'Vedra','fname' => 'Prince Vincent Ryan','nameExt' => NULL,'acc_id' => '17','acc_name' => 'D.A. Lamont','acc_description' => 'Agent'),
  array('emp_id' => '2726','lname' => 'Navarro','mname' => 'Nuevo','fname' => 'Pamela','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2626','lname' => 'Nemenzo','mname' => 'Peguit','fname' => 'Birgson','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2329','lname' => 'Neri','mname' => 'Clavete','fname' => 'Mary Grace','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2097','lname' => 'Nonot','mname' => 'Bagares','fname' => 'Genevieve','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2696','lname' => 'Noval','mname' => 'Yecyec','fname' => 'Yvonne','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '1977','lname' => 'Obeso','mname' => 'Zaballero','fname' => 'Kimwel','nameExt' => NULL,'acc_id' => '60','acc_name' => 'Client Transitions Team','acc_description' => 'Admin'),
  array('emp_id' => '2735','lname' => 'Oblina','mname' => 'Domingo','fname' => 'Phoebe Joy','nameExt' => NULL,'acc_id' => '9','acc_name' => 'MWAR Warranty','acc_description' => 'Agent'),
  array('emp_id' => '295','lname' => 'Obuga','mname' => 'Pernato','fname' => 'Marie Antonette Nathalie','nameExt' => '','acc_id' => '42','acc_name' => 'TSN Operations','acc_description' => 'Admin'),
  array('emp_id' => '1728','lname' => 'Odi','mname' => 'Balistoy','fname' => 'Petchie','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2056','lname' => 'Ogaya','mname' => 'Lagnason','fname' => 'Jerwin','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2733','lname' => 'Oira','mname' => 'Algodon','fname' => 'Chardellane','nameExt' => NULL,'acc_id' => '53','acc_name' => 'Workplace Safety Screenings','acc_description' => 'Agent'),
  array('emp_id' => '1950','lname' => 'Olais','mname' => 'Ua-o','fname' => 'Keirshey','nameExt' => NULL,'acc_id' => '39','acc_name' => 'MWAR Tracking','acc_description' => 'Agent'),
  array('emp_id' => '2763','lname' => 'Olivares','mname' => 'Deloso','fname' => 'Shiela Mae','nameExt' => NULL,'acc_id' => '59','acc_name' => 'ETC Institute','acc_description' => 'Agent'),
  array('emp_id' => '900','lname' => 'Omahoy','mname' => 'Carpentero','fname' => 'Danny John','nameExt' => '','acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '2035','lname' => 'Onahon','mname' => 'Travilla','fname' => 'Karen Mar Grace','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '592','lname' => 'Onipa','mname' => 'N/A','fname' => 'Earl Jay Vincent','nameExt' => '','acc_id' => '56','acc_name' => 'Helpdesk','acc_description' => 'Admin'),
  array('emp_id' => '509','lname' => 'Opiso','mname' => 'T','fname' => 'Vergildo','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2709','lname' => 'Orcajo','mname' => 'Buted','fname' => 'Karla','nameExt' => NULL,'acc_id' => '15','acc_name' => 'TelemedRN - USRN','acc_description' => 'Agent'),
  array('emp_id' => '2655','lname' => 'Orig','mname' => 'Aragon','fname' => 'Ebony Joy','nameExt' => NULL,'acc_id' => '15','acc_name' => 'TelemedRN - USRN','acc_description' => 'Agent'),
  array('emp_id' => '2233','lname' => 'Ortega','mname' => 'Razalo','fname' => 'Phoebe','nameExt' => NULL,'acc_id' => '61','acc_name' => 'Talent Acquisition','acc_description' => 'Admin'),
  array('emp_id' => '2628','lname' => 'Osio','mname' => 'Torequite','fname' => 'Richie','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2110','lname' => 'Osmeña','mname' => 'Cabalquinto','fname' => 'Kate','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2215','lname' => 'Pacaña','mname' => 'Laurente','fname' => 'Renes','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2262','lname' => 'Paclibar','mname' => 'Legaspi','fname' => 'James Jay','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '1938','lname' => 'Pacuribot','mname' => 'Tolentino','fname' => 'Mariecon','nameExt' => NULL,'acc_id' => '51','acc_name' => 'Executive Support Team','acc_description' => 'Admin'),
  array('emp_id' => '2685','lname' => 'Padayhag','mname' => 'Santillan','fname' => 'Stephen','nameExt' => NULL,'acc_id' => '38','acc_name' => 'Training','acc_description' => 'Admin'),
  array('emp_id' => '2425','lname' => 'Pader','mname' => 'Bacus','fname' => 'Rheymhar','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2078','lname' => 'Paderanga VII','mname' => 'Dagondon','fname' => 'Cayetano','nameExt' => NULL,'acc_id' => '65','acc_name' => 'Quality Assurance','acc_description' => 'Admin'),
  array('emp_id' => '2573','lname' => 'Padilla','mname' => 'Tagana','fname' => 'Maricris','nameExt' => NULL,'acc_id' => '48','acc_name' => 'TelemedRN - Receptionist','acc_description' => 'Agent'),
  array('emp_id' => '2114','lname' => 'Padinit','mname' => 'Ipan','fname' => 'Kevin','nameExt' => NULL,'acc_id' => '7','acc_name' => 'Link Tree','acc_description' => 'Agent'),
  array('emp_id' => '2180','lname' => 'Padios','mname' => 'Sarabia','fname' => 'Aljess','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '698','lname' => 'Paglinawan','mname' => 'Adel','fname' => 'Maricel','nameExt' => '','acc_id' => '28','acc_name' => 'Onyx Digital Team','acc_description' => 'Agent'),
  array('emp_id' => '2361','lname' => 'Pailagao','mname' => 'N/A','fname' => 'April Grace','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2570','lname' => 'Palasan','mname' => 'Vega','fname' => 'Kate Diane','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2374','lname' => 'Palma','mname' => 'Cabonilas','fname' => 'Maria Therese','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2117','lname' => 'Pamisaran','mname' => 'Balinas','fname' => 'Ailene Dianne','nameExt' => NULL,'acc_id' => '65','acc_name' => 'Quality Assurance','acc_description' => 'Admin'),
  array('emp_id' => '2468','lname' => 'Panagini','mname' => 'Elarco','fname' => 'Juaren','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2392','lname' => 'Panganuron','mname' => 'Nacaytuna','fname' => 'Alfeñeno','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2729','lname' => 'Panhay','mname' => 'Tacbobo','fname' => 'Michael Bullson','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '1164','lname' => 'Paquibulan','mname' => 'Gemparo','fname' => 'Breshnev','nameExt' => '','acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '1889','lname' => 'Para','mname' => 'Buena','fname' => 'Claire Marie','nameExt' => NULL,'acc_id' => '88','acc_name' => 'Accounting','acc_description' => 'Admin'),
  array('emp_id' => '1269','lname' => 'Paran','mname' => 'Valdez','fname' => 'Dannah Tiffany','nameExt' => '','acc_id' => '34','acc_name' => 'Vistracks','acc_description' => 'Agent'),
  array('emp_id' => '2352','lname' => 'Parojinog','mname' => 'Ymbong','fname' => 'Hannah Joyce','nameExt' => NULL,'acc_id' => '61','acc_name' => 'Talent Acquisition','acc_description' => 'Admin'),
  array('emp_id' => '66','lname' => 'Parungao','mname' => 'Añora','fname' => 'Francis Adrian','nameExt' => '','acc_id' => '56','acc_name' => 'Helpdesk','acc_description' => 'Admin'),
  array('emp_id' => '623','lname' => 'Pasenio','mname' => 'Dayata','fname' => 'Deccy Mae','nameExt' => '','acc_id' => '28','acc_name' => 'Onyx Digital Team','acc_description' => 'Agent'),
  array('emp_id' => '2412','lname' => 'Paslon','mname' => 'Cabillan','fname' => 'Charry Lyn','nameExt' => NULL,'acc_id' => '26','acc_name' => 'Iscential','acc_description' => 'Agent'),
  array('emp_id' => '2316','lname' => 'Patentes','mname' => 'Lumangyao','fname' => 'Alyssamae','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '212','lname' => 'Patron','mname' => 'Anasco','fname' => 'Jaylo','nameExt' => '','acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '1417','lname' => 'Paylangco','mname' => 'Matildo','fname' => 'Trexia Janine','nameExt' => '','acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '241','lname' => 'Payot','mname' => 'Cuizon','fname' => 'Christopher','nameExt' => '','acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '730','lname' => 'Pedrosa','mname' => 'N/A','fname' => 'Edmund Paul','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '420','lname' => 'Peñas','mname' => 'Merida','fname' => 'Realynme','nameExt' => '','acc_id' => '78','acc_name' => 'Thinkific','acc_description' => 'Agent'),
  array('emp_id' => '1849','lname' => 'penaso','mname' => 'ugay','fname' => 'jayson','nameExt' => NULL,'acc_id' => '28','acc_name' => 'Onyx Digital Team','acc_description' => 'Agent'),
  array('emp_id' => '1951','lname' => 'Peralta','mname' => 'Yapac','fname' => 'Irish','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2514','lname' => 'Permahin','mname' => 'Colonia','fname' => 'Samantha Allyssa','nameExt' => NULL,'acc_id' => '50','acc_name' => 'MWAR Warranty Supervisor','acc_description' => 'Agent'),
  array('emp_id' => '1996','lname' => 'Perolino','mname' => 'na','fname' => 'Vencent Paul','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2795','lname' => 'Pescador','mname' => 'Adaza','fname' => 'Junee Jay','nameExt' => NULL,'acc_id' => '14','acc_name' => 'Finance','acc_description' => 'Admin'),
  array('emp_id' => '548','lname' => 'Pestañas','mname' => 'De Asis','fname' => 'Dannith Jane','nameExt' => '','acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2210','lname' => 'Pestañas','mname' => 'NA','fname' => 'Miguel Angelo','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2092','lname' => 'Pilapil','mname' => 'NA','fname' => 'Gerard Souelle','nameExt' => NULL,'acc_id' => '53','acc_name' => 'Workplace Safety Screenings','acc_description' => 'Agent'),
  array('emp_id' => '1453','lname' => 'Pitas','mname' => 'Casamon','fname' => 'Joevanie','nameExt' => NULL,'acc_id' => '56','acc_name' => 'Helpdesk','acc_description' => 'Admin'),
  array('emp_id' => '626','lname' => 'Pizaña','mname' => 'Torres','fname' => 'Julieto','nameExt' => '','acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2702','lname' => 'Platan','mname' => 'Benabese','fname' => 'Don Dioscoro Jones','nameExt' => NULL,'acc_id' => '79','acc_name' => 'AMP Academy','acc_description' => 'Admin'),
  array('emp_id' => '2275','lname' => 'Plaza','mname' => 'Crospe','fname' => 'Melody','nameExt' => NULL,'acc_id' => '101','acc_name' => 'Lone Wolf Accounting','acc_description' => 'Agent'),
  array('emp_id' => '1956','lname' => 'Poblete','mname' => 'Salvana','fname' => 'Princess Mae','nameExt' => NULL,'acc_id' => '28','acc_name' => 'Onyx Digital Team','acc_description' => 'Agent'),
  array('emp_id' => '2095','lname' => 'Polluso','mname' => 'Vallecera','fname' => 'Sheena Faith','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2506','lname' => 'Pontillas','mname' => 'Seno','fname' => 'Rene Susette Ann','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '1924','lname' => 'Pugosa','mname' => 'Nagac','fname' => 'MJ Brian Keen','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2637','lname' => 'Quilab','mname' => 'Apdian','fname' => 'Syrane Jane','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '288','lname' => 'Quillas','mname' => 'Gaid','fname' => 'Grazelle','nameExt' => '','acc_id' => '28','acc_name' => 'Onyx Digital Team','acc_description' => 'Agent'),
  array('emp_id' => '716','lname' => 'Quilon','mname' => 'Lituañas','fname' => 'Karl jason ','nameExt' => '','acc_id' => '76','acc_name' => 'Nurse Dash','acc_description' => 'Agent'),
  array('emp_id' => '2287','lname' => 'Quimno','mname' => 'Rauto','fname' => 'Milky','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '1794','lname' => 'Quimpang','mname' => 'Cabatina','fname' => 'Kimray Walter','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2687','lname' => 'Racaza','mname' => 'Abejo','fname' => 'Carmina Agnes','nameExt' => NULL,'acc_id' => '90','acc_name' => 'Special Projects','acc_description' => 'Admin'),
  array('emp_id' => '2672','lname' => 'Racho','mname' => 'Padero','fname' => 'Fritz Niño','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2289','lname' => 'Racsa','mname' => 'Perez','fname' => 'Jim','nameExt' => 'Jr','acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2674','lname' => 'Ramiro','mname' => 'n/a','fname' => 'Eugene Angelo','nameExt' => NULL,'acc_id' => '70','acc_name' => 'Workforce Management','acc_description' => 'Admin'),
  array('emp_id' => '2691','lname' => 'Ramos','mname' => 'Sicat','fname' => 'Ayessa Kim','nameExt' => NULL,'acc_id' => '15','acc_name' => 'TelemedRN - USRN','acc_description' => 'Agent'),
  array('emp_id' => '2785','lname' => 'Ranario','mname' => 'Gumpay','fname' => 'Eric','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '1925','lname' => 'Rañoa','mname' => 'Dragon','fname' => 'Vercyl','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '1966','lname' => 'Rapirap','mname' => 'Molinas','fname' => 'Christie Ann','nameExt' => NULL,'acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '2770','lname' => 'Rapista','mname' => 'Marbebe','fname' => 'Chierlyn','nameExt' => NULL,'acc_id' => '26','acc_name' => 'Iscential','acc_description' => 'Agent'),
  array('emp_id' => '2154','lname' => 'Ratunil','mname' => 'Nangcas','fname' => 'Jidy','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '1920','lname' => 'Recamara','mname' => NULL,'fname' => 'Keith Claire','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '1875','lname' => 'Recto','mname' => 'Cacivino','fname' => 'Cyrene Grace','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2224','lname' => 'Recto','mname' => 'Narvasa','fname' => 'Stephanie','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '1561','lname' => 'REGAHAL','mname' => 'BERE','fname' => 'ERIC','nameExt' => '','acc_id' => '34','acc_name' => 'Vistracks','acc_description' => 'Agent'),
  array('emp_id' => '1783','lname' => 'Rellin','mname' => 'Mangulabnan','fname' => 'Khalil Bernard','nameExt' => NULL,'acc_id' => '10','acc_name' => 'Service Delivery','acc_description' => 'Admin'),
  array('emp_id' => '1939','lname' => 'Rem','mname' => 'Ladica','fname' => 'Receljane','nameExt' => NULL,'acc_id' => '51','acc_name' => 'Executive Support Team','acc_description' => 'Admin'),
  array('emp_id' => '2703','lname' => 'Rementina','mname' => 'Supan','fname' => 'Ives Matthew','nameExt' => NULL,'acc_id' => '79','acc_name' => 'AMP Academy','acc_description' => 'Admin'),
  array('emp_id' => '2173','lname' => 'Rendado','mname' => 'Bicoy','fname' => 'Lorevil June','nameExt' => NULL,'acc_id' => '76','acc_name' => 'Nurse Dash','acc_description' => 'Agent'),
  array('emp_id' => '2652','lname' => 'Ricafrente','mname' => 'Gundaya','fname' => 'Evelyn','nameExt' => NULL,'acc_id' => '77','acc_name' => 'Friction Labs','acc_description' => 'Agent'),
  array('emp_id' => '1314','lname' => 'Rife','mname' => 'Atillo','fname' => 'Xairily Mara','nameExt' => '','acc_id' => '53','acc_name' => 'Workplace Safety Screenings','acc_description' => 'Agent'),
  array('emp_id' => '2272','lname' => 'Rivera','mname' => 'Tamaca','fname' => 'Ruth Ann Micol','nameExt' => NULL,'acc_id' => '70','acc_name' => 'Workforce Management','acc_description' => 'Admin'),
  array('emp_id' => '101','lname' => 'Roa','mname' => 'Neri','fname' => 'Michael Irwin','nameExt' => '','acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '2580','lname' => 'Rocamora','mname' => 'Barinque','fname' => 'Mary Grace','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2551','lname' => 'Rodriguez','mname' => 'Melicor','fname' => 'Judy Amor','nameExt' => NULL,'acc_id' => '39','acc_name' => 'MWAR Tracking','acc_description' => 'Agent'),
  array('emp_id' => '2502','lname' => 'Rojo','mname' => 'Gallera','fname' => 'March Allen Rolf','nameExt' => NULL,'acc_id' => '25','acc_name' => 'TLF','acc_description' => 'Agent'),
  array('emp_id' => '2010','lname' => 'Roldan','mname' => 'Castro','fname' => 'Samuel','nameExt' => 'Jr','acc_id' => '66','acc_name' => 'Innovations and Customer Solutions','acc_description' => 'Admin'),
  array('emp_id' => '2416','lname' => 'Romanos','mname' => 'Rementizo','fname' => 'Geneva','nameExt' => NULL,'acc_id' => '40','acc_name' => 'MyMDConnect','acc_description' => 'Agent'),
  array('emp_id' => '224','lname' => 'Romero','mname' => 'Caliso','fname' => 'Ramir','nameExt' => NULL,'acc_id' => '5','acc_name' => 'Safety  and Security','acc_description' => 'Admin'),
  array('emp_id' => '1999','lname' => 'Rosales','mname' => 'Pesidas','fname' => 'Maria Nonila','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2395','lname' => 'Rosales','mname' => 'Abanil','fname' => 'Corinna Grace','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2741','lname' => 'Rosales','mname' => 'Hoylar','fname' => 'Sharrah Mae','nameExt' => NULL,'acc_id' => '51','acc_name' => 'Executive Support Team','acc_description' => 'Admin'),
  array('emp_id' => '2055','lname' => 'Rosel','mname' => 'Cachapero','fname' => 'Stephanie','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2635','lname' => 'Roxas','mname' => 'Carreon','fname' => 'Shiela Mae','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2131','lname' => 'Ruelan','mname' => 'Banawan','fname' => 'Desiree','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2063','lname' => 'Rumalay','mname' => 'Lumasag','fname' => 'Kin','nameExt' => NULL,'acc_id' => '89','acc_name' => 'G&A Partners','acc_description' => 'Agent'),
  array('emp_id' => '2042','lname' => 'Ruste','mname' => 'Gonzales','fname' => 'Neysa Mariz','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2375','lname' => 'Sabaduquia','mname' => 'Roxas','fname' => 'Erika Frances','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2589','lname' => 'Sabaduquia','mname' => 'Roxas','fname' => 'Erlito','nameExt' => 'ii','acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2423','lname' => 'Sabellina','mname' => 'Bote','fname' => 'Rock Wisley','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2207','lname' => 'Sabit','mname' => 'Cultura','fname' => 'Ernie Jake','nameExt' => NULL,'acc_id' => '92','acc_name' => 'Thinkific Chat','acc_description' => 'Agent'),
  array('emp_id' => '2491','lname' => 'Sablayan','mname' => 'Loren','fname' => 'Jayrald Rone','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2644','lname' => 'Sacay','mname' => 'Bongco','fname' => 'April Rose','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2794','lname' => 'Saclapos','mname' => 'Daug','fname' => 'Dodgie Boy','nameExt' => NULL,'acc_id' => '56','acc_name' => 'Helpdesk','acc_description' => 'Admin'),
  array('emp_id' => '1901','lname' => 'Sahagun','mname' => 'M.','fname' => 'Nee Tinsel','nameExt' => NULL,'acc_id' => '26','acc_name' => 'Iscential','acc_description' => 'Agent'),
  array('emp_id' => '2734','lname' => 'Salalima','mname' => 'Garzon','fname' => 'Veronica','nameExt' => NULL,'acc_id' => '53','acc_name' => 'Workplace Safety Screenings','acc_description' => 'Agent'),
  array('emp_id' => '2730','lname' => 'Salazar','mname' => 'Daclag','fname' => 'Roeh','nameExt' => NULL,'acc_id' => '34','acc_name' => 'Vistracks','acc_description' => 'Agent'),
  array('emp_id' => '27','lname' => 'Salo','mname' => 'Repulda','fname' => 'Christine Jessica','nameExt' => '','acc_id' => '94','acc_name' => 'Accounts Managers','acc_description' => 'Admin'),
  array('emp_id' => '2599','lname' => 'Salvaña','mname' => 'Guinawat','fname' => 'Stiffany','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '415','lname' => 'Samson','mname' => 'Laggui','fname' => 'Shemyrh','nameExt' => '','acc_id' => '69','acc_name' => 'Onyx Escalations Team','acc_description' => 'Agent'),
  array('emp_id' => '557','lname' => 'Sanchez','mname' => 'Guintapa','fname' => 'Michael','nameExt' => '','acc_id' => '37','acc_name' => 'Software Programming','acc_description' => 'Admin'),
  array('emp_id' => '2757','lname' => 'Sanchez','mname' => 'Gono','fname' => 'Jhonriel Mark','nameExt' => NULL,'acc_id' => '5','acc_name' => 'Safety  and Security','acc_description' => 'Admin'),
  array('emp_id' => '1652','lname' => 'Sandot','mname' => 'abelong','fname' => 'Soraphy','nameExt' => NULL,'acc_id' => '83','acc_name' => 'Lone Wolf Cebu','acc_description' => 'Agent'),
  array('emp_id' => '158','lname' => 'Sangel','mname' => 'Puno','fname' => 'Maria Ofelia','nameExt' => '','acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2552','lname' => 'Santiag','mname' => 'Padura','fname' => 'Ronibert','nameExt' => NULL,'acc_id' => '100','acc_name' => 'RocketBuildr','acc_description' => 'Agent'),
  array('emp_id' => '2647','lname' => 'Sara','mname' => 'Aporador','fname' => 'Shiellah Jane','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2769','lname' => 'Sarmiento','mname' => 'Catipay','fname' => 'Mary Kaye','nameExt' => NULL,'acc_id' => '26','acc_name' => 'Iscential','acc_description' => 'Agent'),
  array('emp_id' => '1961','lname' => 'Sarraga','mname' => 'Dagoc','fname' => 'Kyrei Wally','nameExt' => NULL,'acc_id' => '38','acc_name' => 'Training','acc_description' => 'Admin'),
  array('emp_id' => '2765','lname' => 'Seblos','mname' => NULL,'fname' => 'Justinn','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2284','lname' => 'Semaña','mname' => 'Carreon','fname' => 'President','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '879','lname' => 'Sencio','mname' => 'Abregana','fname' => 'Jamelyn','nameExt' => '','acc_id' => '28','acc_name' => 'Onyx Digital Team','acc_description' => 'Agent'),
  array('emp_id' => '198','lname' => 'Señedo','mname' => 'Alvia','fname' => 'Kirvy Carlo','nameExt' => '','acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '2512','lname' => 'Senining','mname' => 'Edquilag','fname' => 'Klarcheen Faith','nameExt' => NULL,'acc_id' => '71','acc_name' => 'Center','acc_description' => 'Agent'),
  array('emp_id' => '2179','lname' => 'Seno','mname' => 'Jangao','fname' => 'Fatima Claire','nameExt' => NULL,'acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '1876','lname' => 'Sepe','mname' => 'Balase','fname' => 'Joshua Jim','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2632','lname' => 'Sepulveda','mname' => 'Lagurin','fname' => 'Cherry Mae','nameExt' => NULL,'acc_id' => '57','acc_name' => 'Total Rewards','acc_description' => 'Admin'),
  array('emp_id' => '2118','lname' => 'Sese','mname' => 'Palarao','fname' => 'Keziah','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2312','lname' => 'Sieras','mname' => 'Adem','fname' => 'Juhanna Eloisa','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2636','lname' => 'Sigue','mname' => 'N/A','fname' => 'Jacer','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2007','lname' => 'Silaga','mname' => 'Erigbuagas','fname' => 'June Alerry','nameExt' => NULL,'acc_id' => '53','acc_name' => 'Workplace Safety Screenings','acc_description' => 'Agent'),
  array('emp_id' => '1940','lname' => 'Silangon','mname' => 'L.','fname' => 'Ronie','nameExt' => NULL,'acc_id' => '5','acc_name' => 'Safety  and Security','acc_description' => 'Admin'),
  array('emp_id' => '1426','lname' => 'Silmaro','mname' => 'Malalay','fname' => 'Randy','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2752','lname' => 'Simene','mname' => 'Barcubero','fname' => 'John Daryl','nameExt' => NULL,'acc_id' => '79','acc_name' => 'AMP Academy','acc_description' => 'Admin'),
  array('emp_id' => '2621','lname' => 'Singuay','mname' => 'Amonceda','fname' => 'Nova','nameExt' => NULL,'acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '2201','lname' => 'Siso','mname' => 'Rabutin','fname' => 'Bethel Grace','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2123','lname' => 'Sison','mname' => 'Sanchez','fname' => 'Sayra Belle','nameExt' => NULL,'acc_id' => '7','acc_name' => 'Link Tree','acc_description' => 'Agent'),
  array('emp_id' => '2410','lname' => 'Somosierra','mname' => 'Dablio','fname' => 'Jude Andrew','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2360','lname' => 'Soriño','mname' => 'Paraon','fname' => 'Erickson','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2740','lname' => 'Sorio','mname' => 'Tion','fname' => 'Jonathan','nameExt' => NULL,'acc_id' => '92','acc_name' => 'Thinkific Chat','acc_description' => 'Agent'),
  array('emp_id' => '1016','lname' => 'Suan','mname' => 'Banaag','fname' => 'Charise Vianca','nameExt' => NULL,'acc_id' => '37','acc_name' => 'Software Programming','acc_description' => 'Admin'),
  array('emp_id' => '2407','lname' => 'Sumalinog','mname' => 'Ramonal','fname' => 'Angy','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2029','lname' => 'Sumanduran','mname' => 'Gumirid','fname' => 'Larry','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2676','lname' => 'Tabar','mname' => 'Ruizo','fname' => 'John Jeremy','nameExt' => NULL,'acc_id' => '91','acc_name' => 'Learning and Development','acc_description' => 'Admin'),
  array('emp_id' => '2564','lname' => 'Tabasa','mname' => 'Podunas','fname' => 'Maria Casandra','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '1886','lname' => 'Taclindo','mname' => 'Guadalquiver','fname' => 'Abigail','nameExt' => NULL,'acc_id' => '14','acc_name' => 'Finance','acc_description' => 'Admin'),
  array('emp_id' => '1964','lname' => 'Tacna','mname' => 'Capote','fname' => 'Jess Michael','nameExt' => NULL,'acc_id' => '58','acc_name' => 'Visual Communications Team','acc_description' => 'Admin'),
  array('emp_id' => '2415','lname' => 'Tadena','mname' => 'Talatala','fname' => 'Orlie','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2235','lname' => 'Tagam','mname' => 'N/A','fname' => 'Jerson','nameExt' => NULL,'acc_id' => '56','acc_name' => 'Helpdesk','acc_description' => 'Admin'),
  array('emp_id' => '2582','lname' => 'Tagam','mname' => 'Piollo','fname' => 'Quennie May','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2537','lname' => 'Taganahan','mname' => 'Pepito','fname' => 'Don Engel','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '2519','lname' => 'Tagud','mname' => 'Balacua','fname' => 'Sarmin','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2548','lname' => 'Talipan','mname' => 'Torralba','fname' => 'Marlyn','nameExt' => NULL,'acc_id' => '71','acc_name' => 'Center','acc_description' => 'Agent'),
  array('emp_id' => '2713','lname' => 'Tampus','mname' => 'Prajes','fname' => 'Elgin Rudy','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2792','lname' => 'Tan','mname' => 'Ronolo','fname' => 'Jeremiah','nameExt' => NULL,'acc_id' => '56','acc_name' => 'Helpdesk','acc_description' => 'Admin'),
  array('emp_id' => '2295','lname' => 'Tanghal','mname' => 'Oclarit','fname' => 'Glenda','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '1440','lname' => 'Tantoy','mname' => 'Paglinawan','fname' => 'Austine','nameExt' => NULL,'acc_id' => '65','acc_name' => 'Quality Assurance','acc_description' => 'Admin'),
  array('emp_id' => '2376','lname' => 'Tanzo','mname' => 'Nericua','fname' => 'Jimmielyn Ann','nameExt' => NULL,'acc_id' => '17','acc_name' => 'D.A. Lamont','acc_description' => 'Agent'),
  array('emp_id' => '2518','lname' => 'Taotao','mname' => 'Hensis','fname' => 'Nicophon','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '1910','lname' => 'Tapic','mname' => 'Simene','fname' => 'Richel','nameExt' => NULL,'acc_id' => '75','acc_name' => 'Purchasing and Inventory Team','acc_description' => 'Admin'),
  array('emp_id' => '2318','lname' => 'Tedlos','mname' => 'Baculio','fname' => 'Annalie','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2188','lname' => 'Tenio','mname' => 'Ravidas','fname' => 'April Joy','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2456','lname' => 'Test Applicant 1','mname' => 'none','fname' => 'TLF','nameExt' => NULL,'acc_id' => '25','acc_name' => 'TLF','acc_description' => 'Agent'),
  array('emp_id' => '2083','lname' => 'Tomampos','mname' => 'Raypon','fname' => 'Maria Anna Patricia','nameExt' => NULL,'acc_id' => '99','acc_name' => 'Accounting and Finance','acc_description' => 'Admin'),
  array('emp_id' => '1990','lname' => 'Tubo','mname' => 'Ira','fname' => 'Airel Dan','nameExt' => NULL,'acc_id' => '55','acc_name' => 'Team Leaders','acc_description' => 'Admin'),
  array('emp_id' => '2517','lname' => 'Tubongbanua','mname' => 'Villanueva','fname' => 'Jessel','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2244','lname' => 'Tulang','mname' => 'Enterina','fname' => 'Estella Marize','nameExt' => NULL,'acc_id' => '82','acc_name' => 'Lone Wolf CDO','acc_description' => 'Agent'),
  array('emp_id' => '2457','lname' => 'Tuyogun','mname' => 'Yabut','fname' => 'Alex','nameExt' => NULL,'acc_id' => '5','acc_name' => 'Safety  and Security','acc_description' => 'Admin'),
  array('emp_id' => '2790','lname' => 'Tyson','mname' => 'Bazarte','fname' => 'Kert John','nameExt' => NULL,'acc_id' => '89','acc_name' => 'G&A Partners','acc_description' => 'Agent'),
  array('emp_id' => '2704','lname' => 'Udang','mname' => 'Emata','fname' => 'Gerzon','nameExt' => NULL,'acc_id' => '79','acc_name' => 'AMP Academy','acc_description' => 'Admin'),
  array('emp_id' => '2777','lname' => 'Ugsod','mname' => 'Suan','fname' => 'Dagny Rose','nameExt' => NULL,'acc_id' => '71','acc_name' => 'Center','acc_description' => 'Agent'),
  array('emp_id' => '2661','lname' => 'Umadhay','mname' => 'Carmen','fname' => 'Marides','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2499','lname' => 'Unico','mname' => 'Otero','fname' => 'Colleen Grace','nameExt' => NULL,'acc_id' => '39','acc_name' => 'MWAR Tracking','acc_description' => 'Agent'),
  array('emp_id' => '1705','lname' => 'Urbina','mname' => 'Delute','fname' => 'Mark Vincent','nameExt' => '','acc_id' => '49','acc_name' => 'Onyx CS Wheels','acc_description' => 'Agent'),
  array('emp_id' => '2153','lname' => 'usares','mname' => 'Salas','fname' => 'kent ira','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '1317','lname' => 'Uy','mname' => 'Patalinghug','fname' => 'Monette','nameExt' => '','acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2739','lname' => 'Valdeo','mname' => 'Barrion','fname' => 'Harry','nameExt' => NULL,'acc_id' => '56','acc_name' => 'Helpdesk','acc_description' => 'Admin'),
  array('emp_id' => '2778','lname' => 'Valdon','mname' => 'Jabiniar','fname' => 'Kent Aeron','nameExt' => NULL,'acc_id' => '71','acc_name' => 'Center','acc_description' => 'Agent'),
  array('emp_id' => '2450','lname' => 'Valencia','mname' => 'Balagot','fname' => 'Fritzi Ann','nameExt' => NULL,'acc_id' => '23','acc_name' => 'Onyx Customer Service (CDO)','acc_description' => 'Agent'),
  array('emp_id' => '2311','lname' => 'Valiente','mname' => 'Cuizon','fname' => 'Lucre Amaryllis','nameExt' => NULL,'acc_id' => '28','acc_name' => 'Onyx Digital Team','acc_description' => 'Agent'),
  array('emp_id' => '2408','lname' => 'Vallecera','mname' => 'Cañadilla','fname' => 'Kintdhel Lou','nameExt' => NULL,'acc_id' => '1','acc_name' => 'Athletic Greens','acc_description' => 'Agent'),
  array('emp_id' => '147','lname' => 'Vallente','mname' => 'Chatto','fname' => 'Shendie','nameExt' => '','acc_id' => '14','acc_name' => 'Finance','acc_description' => 'Admin'),
  array('emp_id' => '2660','lname' => 'Vasallo','mname' => 'Postrero','fname' => 'Jessa Mae','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2380','lname' => 'Veloso','mname' => 'Palisoc','fname' => 'Ferdinand','nameExt' => NULL,'acc_id' => '61','acc_name' => 'Talent Acquisition','acc_description' => 'Admin'),
  array('emp_id' => '1934','lname' => 'Vestil','mname' => 'C.','fname' => 'Michael Arthur','nameExt' => NULL,'acc_id' => '83','acc_name' => 'Lone Wolf Cebu','acc_description' => 'Agent'),
  array('emp_id' => '2203','lname' => 'Vicente','mname' => 'Aranzo','fname' => 'Gladys Joy','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2422','lname' => 'Villablanca','mname' => 'Payanan','fname' => 'Vonlehi','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '547','lname' => 'Villadores','mname' => 'Alquisola','fname' => 'Edwin','nameExt' => NULL,'acc_id' => '11','acc_name' => 'Facilities and Maintenance','acc_description' => 'Admin'),
  array('emp_id' => '2764','lname' => 'Villahermosa','mname' => 'Osin','fname' => 'Alexander','nameExt' => 'ii','acc_id' => '16','acc_name' => 'CT-Miami','acc_description' => 'Agent'),
  array('emp_id' => '2194','lname' => 'Villalba','mname' => 'Abellanosa','fname' => 'Krisan Marc','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '1212','lname' => 'Villegas','mname' => 'Nisperos','fname' => 'Giselle','nameExt' => '','acc_id' => '76','acc_name' => 'Nurse Dash','acc_description' => 'Agent'),
  array('emp_id' => '2783','lname' => 'Visayas','mname' => 'Sadlucap','fname' => 'Mico','nameExt' => NULL,'acc_id' => '7','acc_name' => 'Link Tree','acc_description' => 'Agent'),
  array('emp_id' => '2302','lname' => 'Wamil','mname' => 'Apao','fname' => 'Joseph William','nameExt' => NULL,'acc_id' => '92','acc_name' => 'Thinkific Chat','acc_description' => 'Agent'),
  array('emp_id' => '614','lname' => 'Wenceslao','mname' => 'Lague','fname' => 'Samuel','nameExt' => '','acc_id' => '69','acc_name' => 'Onyx Escalations Team','acc_description' => 'Agent'),
  array('emp_id' => '2298','lname' => 'Yañez','mname' => 'Pepito','fname' => 'Dandy','nameExt' => NULL,'acc_id' => '34','acc_name' => 'Vistracks','acc_description' => 'Agent'),
  array('emp_id' => '979','lname' => 'Yap','mname' => 'Valdehueza','fname' => 'Nathan','nameExt' => NULL,'acc_id' => '13','acc_name' => 'Managing Director','acc_description' => 'Admin'),
  array('emp_id' => '2387','lname' => 'Yap','mname' => NULL,'fname' => 'John Paul','nameExt' => NULL,'acc_id' => '64','acc_name' => 'Swyft','acc_description' => 'Agent'),
  array('emp_id' => '2130','lname' => 'Ybañez','mname' => 'Panilag','fname' => 'Jonas','nameExt' => NULL,'acc_id' => '83','acc_name' => 'Lone Wolf Cebu','acc_description' => 'Agent'),
  array('emp_id' => '2714','lname' => 'Ybañez','mname' => NULL,'fname' => 'Ivan','nameExt' => NULL,'acc_id' => '51','acc_name' => 'Executive Support Team','acc_description' => 'Admin'),
  array('emp_id' => '2370','lname' => 'Yecla','mname' => 'Palamine','fname' => 'Marjone','nameExt' => NULL,'acc_id' => '56','acc_name' => 'Helpdesk','acc_description' => 'Admin'),
  array('emp_id' => '2226','lname' => 'Zarate','mname' => 'Duman-ag','fname' => 'Genery','nameExt' => NULL,'acc_id' => '32','acc_name' => 'The Staffing Ninjas','acc_description' => 'Admin'),
  array('emp_id' => '2771','lname' => 'Zarate','mname' => 'Sanchez','fname' => 'Jovi Rose','nameExt' => NULL,'acc_id' => '26','acc_name' => 'Iscential','acc_description' => 'Agent'),
  array('emp_id' => '2576','lname' => 'Zarsuelo','mname' => 'Bruzola','fname' => 'Ma. Celina Charisse','nameExt' => NULL,'acc_id' => '15','acc_name' => 'TelemedRN - USRN','acc_description' => 'Agent')
);
        $employees = json_decode(json_encode($employee));
        $participants = $this->qry_participants();
        $emp_ids = array_column($participants, 'emp_id');
        $lookup = array_flip($emp_ids);
        if(count($employees) > 0){
            $qr_pre_code = "SZYE22_";
            $part_arr = [];
            foreach($employees as $employee_row){
                if(!isset($lookup[$employee_row->emp_id])){
                    $part_row = [];
                    $qr_code = $qr_pre_code . $employee_row->emp_id; // qr code
                    $part_row = [
                        'code' => $qr_code,
                        // 'img_string' => $imageString,
                        'emp_id' => $employee_row->emp_id,
                        'lastname' => $employee_row->lname,
                        'firstname' => $employee_row->fname,
                        'midname' => $employee_row->mname,
                        'nameExt' => $employee_row->nameExt,
                        'acc_id' => $employee_row->acc_id,
                        'account' => $employee_row->acc_name,
                        'participantType' => $employee_row->acc_description
                    ];
                    array_push($part_arr, $part_row);
                }
            }
            var_dump($part_arr);
        }
        if (count($part_arr) > 0) { // if qr codes are generated
            $this->general_model->batch_insert($part_arr, 'tbl_participant');
        }
    }
    public function qry_accounts($acc_name, $participant_type = null, $acc_id = NULL){
        $where = "";
        $where_arr = [];
        $field_arr = ['account'];
        $search_concat =  $this->search_like_concat($field_arr, $acc_name);
        if ($search_concat != null) {
            $where_acc = "AND ($search_concat)";
            array_push($where_arr, "($search_concat)");
        }
        if($participant_type){
            array_push($where_arr, "participantType = '$participant_type'");
        }
        if($acc_id){
            array_push($where_arr, "acc_id = '$acc_id'");
        }
        if(count($where_arr) > 0){
            $where = "WHERE ".implode(" AND ", $where_arr);
        }
        return $this->general_model->custom_query("SELECT DISTINCT (acc_id) id, account as text FROM tbl_participant ".$where." ORDER BY account ASC");
    }
    public function get_accounts(){
        $acc_name = $this->input->post('name');
        $participant_type = $this->input->post('participant_type')?$this->input->post('participant_type'): null;
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
    protected function check_authentication(){
        $this->check_token();
        $result = $this->decode_token($this->token);
        if(count($result) > 0){
            $committee_details = $this->general_model->fetch_specific_val("committee_ID, fname, lname, mname, username, role, authorized", "username = '$result->username' AND committee_ID = '$result->committee_ID'", "tbl_committee");
            if (count($committee_details) < 1) {
                echo json_encode(array('status' => 'error', 'message' => 'Not Authorized'));
                die();
            }else{
                return $committee_details;
            }
        }else{
            echo json_encode(array('status' => 'error', 'message' => 'Signature verification failed'));
            die();
        }
    }
    public function authenticate_token(){
        $authentication = $this->check_authentication();
        echo json_encode(array('status' => 'success', 'message' => 'Authenticated!', 'details' => $authentication));
    }
    public function authenticate_user(){
        $_POST = json_decode(file_get_contents("php://input"));
        $uname =  $_POST->username;
        $password = sha1(md5($_POST->password));
        $result = $this->general_model->fetch_specific_val("committee_ID, fname, lname, mname, username, role, authorized", "username = '$uname' AND password = '$password'", "tbl_committee");
        if(count($result) > 0){
            $token = $this->token($result->committee_ID, $result->username);
            echo json_encode(array('status' => 'success', 'message' => 'Authenticated!', 'token' => $token, 'details' =>$result));
        }else{
            echo json_encode(array('status' => 'error', 'message' => 'Incorrect Username or Password'));
        }
    }
}
