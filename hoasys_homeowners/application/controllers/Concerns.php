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
        if ($this->session->userdata("id_ho")) {
            $date_time = $this->get_current_date_time();
            $data["date_time"] = $date_time["dateTime"];
            $role = $this->session->userdata("role");
            $data['title'] = $this->title;
            $this->load_template_view('templates/concerns/concerns', $data);
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
         $id_ho = $this->session->userdata("id_ho");
    
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
                1=1 AND con.id_ho = ".$id_ho."". $stat_where ;
    
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
        $id = $this->input->post('id');
        $em['status_concern'] = $this->input->post('status');
        $this->general_model->update_vals($em, "id_concern = $id", "tbl_concern");
    }
    // New code 
    public function send_concern()
    {
        $date_time = $this->get_current_date_time();
        $concern_data = [
            'title_concern' => $this->input->post('title'),
            'desc_concern' => $this->input->post('description'),
            'datesent_concern' => $date_time["dateTime"],
            'status_concern' => "unresolved",
            'isReceivedEmail' => 0,
            'id_admin' => 0,
            'id_ho' => $this->session->userdata("id_ho"),
        ];

        // Insert concern details

        $id_concern = $this->general_model->insert_vals_last_inserted_id($concern_data, "tbl_concern");

        // Handle file uploads
        if (!empty($_FILES['upload_iRequest_files']['name'][0])) {
            $this->upload_ConcernFiles($id_concern);
        }
    }

    // private function upload_ConcernFiles($id_concern)
    // {
    //     $api_key = 'e55741dd106ddb2d015c10c94996d52c'; // Get your ImgBB API key from https://imgbb.com/api

    //     $path = 'uploads/concerns/';
    //     $files = $_FILES['upload_iRequest_files'];

    //     foreach ($files['name'] as $key => $name) {
    //         $size = $files['size'][$key];
    //         $temp = $files['tmp_name'][$key];

    //         $file_name = pathinfo($name, PATHINFO_FILENAME);
    //         $file_extension = pathinfo($name, PATHINFO_EXTENSION);
    //         $final_name = "concern_file_" . $id_concern . "_" . $key . "_" . time() . "." . $file_extension;

    //         $new_path = $path . $final_name;

    //         if (move_uploaded_file($temp, $new_path)) {
    //             // Upload image to ImgBB
    //             $imgbb_response = $this->uploadToImgBB($new_path, $api_key);

    //             if ($imgbb_response['success']) {
    //                 $file_data = [
    //                     'id_concern' => $id_concern,
    //                     'file_link' => $imgbb_response['data']['url'],
    //                     'datetime_saved' => date('Y-m-d H:i:s'),
    //                 ];

    //                 $this->general_model->insert_vals($file_data, 'tbl_concern_files');
    //             } else {
    //                 // Handle ImgBB upload error
    //                 // You might want to log the error or handle it accordingly
    //             }
    //         }
    //     }
    // }
    private function upload_ConcernFiles($id_concern)
    {
        $api_key = 'e55741dd106ddb2d015c10c94996d52c'; // Get your ImgBB API key from https://imgbb.com/api
    
        $files = $_FILES['upload_iRequest_files'];
    
        foreach ($files['name'] as $key => $name) {
            $size = $files['size'][$key];
            $temp = $files['tmp_name'][$key];
    
            // Remove code related to local storage
            // $file_name = pathinfo($name, PATHINFO_FILENAME);
            // $file_extension = pathinfo($name, PATHINFO_EXTENSION);
            // $final_name = "concern_file_" . $id_concern . "_" . $key . "_" . time() . "." . $file_extension;
    
            // Remove this line
            // $new_path = $path . $final_name;
    
            // Remove the condition related to move_uploaded_file
            // if (move_uploaded_file($temp, $new_path)) {
    
            // Upload image to ImgBB
            $imgbb_response = $this->uploadToImgBB($temp, $api_key); // Use $temp directly instead of $new_path
    
            if ($imgbb_response['success']) {
                $file_data = [
                    'id_concern' => $id_concern,
                    'file_link' => $imgbb_response['data']['url'],
                    'datetime_saved' => date('Y-m-d H:i:s'),
                ];
    
                $this->general_model->insert_vals($file_data, 'tbl_concern_files');
            } else {
                // Handle ImgBB upload error
                // You might want to log the error or handle it accordingly
            }
            // }
        }
    }
    
    private function uploadToImgBB($image_path, $api_key)
    {
        $api_url = 'https://api.imgbb.com/1/upload?key=' . $api_key;
    
        $curl = curl_init();
    
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_URL, $api_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, ['image' => base64_encode(file_get_contents($image_path))]);
    
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl)) {
            echo 'Curl error: ' . curl_error($curl);
        }
    
        curl_close($curl);
    
        if ($http_code == 200) {
            return json_decode($response, true);
        } else {
            // Handle API request error
            // You might want to log the error or handle it accordingly
            return ['success' => false, 'error' => 'API request failed. HTTP code: ' . $http_code];
        }
    }
}