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
            if ($role == "admin" || $role == "officer") {
                $data['title'] = $this->title;
                $this->load_template_view('templates/homeowner/index', $data);
            } else {
                // redirect(base_url('dashboard'));
                redirect(base_url('dashboard'));
            }
        } else {
            redirect(base_url('Login'));
        }
    }
    public function get_homeowners()
    {
        $datatable = $this->input->post('datatable');
        $query['search']['append'] = "";
        $query['search']['total'] = "";
        $block = $datatable['query']['block'];
        $lot= $datatable['query']['lot'];
        $where_name = "";
        $block_where = "";
        $lot_where = "";
        $order = " lname ";

        if (!empty($block) && trim($block) !== 'All') {
            $block_where = " AND block = '" . $block . "'";
        }
        if (!empty($lot) && trim($lot) !== 'All') {
            $lot_where =  " AND lot = '" . $lot . "'";
        }
    
        $query['query'] = "SELECT id_ho, lname, fname, mname, block, lot, contact_num, email_add, status, username, village,good_payer FROM `tbl_homeowner` WHERE id_ho != 0 " . $where_name. $block_where. $lot_where;
        
        if ($datatable['query']['searchField'] != '') {
            $keyword = $datatable['query']['searchField'];
            
            $where = "(lname LIKE '%" . $keyword . "%' OR fname LIKE '%" . $keyword . "%' OR village LIKE '%" . $keyword . "%' OR username LIKE '%" . $keyword . "%' OR block LIKE '%" . $keyword . "%' OR lot LIKE '%" . $keyword . "%' OR CONCAT(fname,' ', mname,' ', lname) LIKE '%" . $keyword . "%' OR CONCAT(fname,' ', lname) LIKE '%" . $keyword . "%')";
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
     public function check_username_allowed($username){
        // $username = "charrot";
        $CanSave = 0;
        $checker = $this->general_model->custom_query('SELECT id_ho FROM `tbl_homeowner` WHERE username = "'.$username.'"');
        if(count($checker) > 0){
            $CanSave = 0;
        }else{
            $CanSave = 1;
        }
        return $CanSave;
        // var_dump($CanSave);
    }
    public function check_email_allowed($email_add){
        // $email_add = "ricajhkjh@mail.com";
        $CanSave = 0;
        $checker = $this->general_model->custom_query('SELECT id_ho FROM `tbl_homeowner` WHERE email_add = "'.$email_add.'"');
        if(count($checker) > 0){
            $CanSave = 0;
        }else{
            $CanSave = 1;
        }
        return $CanSave;
        // var_dump($CanSave);
    }
        public function save_homeowner()
    {
            $em = $this->input->post('email_address');
            $admin_name = $this->session->userdata("fullname");
            $pass = $this->generate_password();
            $un = $this->input->post('username');
     
            // Check username to avoid duplication 
            $username_allowed = $this->check_username_allowed($un);
            $email_allowed = $this->check_email_allowed($em);
     
            if($username_allowed == 0){
                // Which means, saving is not possible since duplicate is detected
                $data['success'] = 2;
            }else if($email_allowed == 0){
                $data['success'] = 3;
            }else{
                $fullname = $this->input->post('fname') . " " . $this->input->post('lname');
                $home['fname'] = $this->input->post('fname');
                $home['lname'] = $this->input->post('lname');
                $home['mname'] = $this->input->post('mname');
                $home['block'] = $this->input->post('block');
                $home['lot'] = $this->input->post('lot');
                $home['village'] = $this->input->post('village');
                $home['contact_num'] = $this->input->post('contact_number');
                $home['email_add'] = $em;
                $home['password'] = $pass;
                $home['username'] = $un;
                $home['status'] = "inactive";
                $home['email_add'] = $em;
        
                 //evaluate renter owner
                $homeownerType = $this->input->post('owner');
                if($homeownerType == "renter"){
                    $home['can_run'] = 0;
                }else{
                    $home['can_run'] = $this->input->post('run');
                }
                $home['owner_type'] = $homeownerType;
                $home['move_in_year'] = $this->input->post('movein');
                $home['move_in_month'] = $this->input->post('moveinmonth');
                $home['can_vote'] = $this->input->post('vote');
                $home['good_payer'] = $this->input->post('payer');
        
                $newinserted_id = $this->general_model->insert_vals_last_inserted_id($home, "tbl_homeowner");
                $fullname = $this->input->post('fname') . " " . $this->input->post('lname');
                $payment['monthly'] = $this->input->post('payment');
                $payment['id_ho'] = $newinserted_id;
                $payment['duedate'] = $this->input->post('duedate');
                $id_ho_inserted = $this->general_model->insert_vals_last_inserted_id($payment, "tbl_homeowner_monthly");
                $message = $admin_name . ' successfully added ' . $fullname . ' as a Homeowner.';
                $this->activity_log('homeowner', $id_ho_inserted, $message);
                $data['success'] = 1;
                $this->email_sending($newinserted_id, $em, $pass, $un, $fullname);
            }
            echo json_encode($data);  
        }
    // email sending back end
    public function email_sending($newinserted_id, $em, $pass, $username, $fullname)
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
            // echo "Mail successful";
        } else {
            // echo "Sorry";
            print_r($this->email->print_debugger());
        }
    }
    public function verify_account($id)
    {
        $data['status'] = "active";
        $where = "id_ho = " . $id;
        $this->general_model->update_vals($data, $where, 'tbl_homeowner');
        $this->load->view('templates/verified');
    }
    public function get_homeowner_details()
    {
        $id = $this->input->post('id');
        $homeowners_info = $this->general_model->custom_query('SELECT h.id_ho,h.lname,h.fname,h.mname,h.lname,h.block,h.lot,h.contact_num,h.email_add,h.status,h.username,h.village,h.can_vote,h.good_payer,h.can_run,h.move_in_month,h.move_in_year,h.owner_type,p.monthly,p.duedate,p.id_ho_monthly FROM tbl_homeowner h, tbl_homeowner_monthly p WHERE h.id_ho = p.id_ho AND h.id_ho = ' . $id);
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
    public function check_email_allowed_for_update($email_add,$id_ho){
        // $email_add = "ricajhkjh@mail.com";
        $CanSave = 0;
        $checker = $this->general_model->custom_query('SELECT id_ho FROM `tbl_homeowner` WHERE email_add = "'.$email_add.'" AND id_ho != '.$id_ho.'');
        if(count($checker) > 0){
            $CanSave = 0;
        }else{
            $CanSave = 1;
        }
        return $CanSave;
        // var_dump($CanSave);
    }
    public function update_homeowner()
    {
        $id = $this->input->post('id');
        $un = $this->input->post('username');
        $em = $this->input->post('email_address');
        $admin_name = $this->session->userdata("fullname");

          // Check username to avoid duplication 
          $username_allowed = $this->check_username_allowed_for_update($un,$id);
          $email_allowed = $this->check_email_allowed_for_update($em,$id);
   
          if($username_allowed == 0){
              // Which means, saving is not possible since duplication is detected for username
              $data['success'] = 2;
          }else if($email_allowed == 0){
                    // Which means, saving is not possible since duplication is detected for email
              $data['success'] = 3;
          }else{
            $home['fname'] = $this->input->post('fname');
            $home['lname'] = $this->input->post('lname');
            $home['mname'] = $this->input->post('mname');
            $home['block'] = $this->input->post('block');
            $home['lot'] = $this->input->post('lot');
            $home['village'] = $this->input->post('village');
            $home['username'] = $un;
            $home['contact_num'] = $this->input->post('contact_number');
            $home['email_add'] = $em;
            // $home['password'] = $this->input->post('password');
            $home['updated_by'] = $this->session->userdata("id_admin");
            $home['status'] =  $this->input->post('status');
            $pay['monthly'] = $this->input->post('monthly');
            $pay['duedate'] = $this->input->post('due');
            
             //evaluate renter owner
             $homeownerType = $this->input->post('owner');
             if($homeownerType == "renter"){
                 $home['can_run'] = 0;
             }else{
                 $home['can_run'] = $this->input->post('run');
             }
            $home['owner_type'] = $homeownerType;
            $home['move_in_year'] = $this->input->post('year');
            $home['move_in_month'] = $this->input->post('month');
            $home['good_payer'] = $this->input->post('payer');
            $home['can_vote'] = $this->input->post('vote');
    
            $pay['updated_by'] = $this->session->userdata("id_admin");
            $this->general_model->update_vals($home, "id_ho = $id", "tbl_homeowner");
            $this->general_model->update_vals($pay, "id_ho = $id", "tbl_homeowner_monthly");
            $message = $admin_name . ' successfully updated info of homeowner ' . $this->input->post('fname') . ' ' . $this->input->post('lname') . '.';
            $this->activity_log('homeowner', $id, $message);
            $data['success'] = 1;
          }
          echo json_encode($data); 
    }
    public function generate_password()
    {
        $this->load->helper('string');
        $password = random_string('alnum', 5);
        return $password;
    }
    public function download_homeowners_report()
    {
        $search = $this->input->post('searchField');
        $block = $this->input->post('block');
        $lot = $this->input->post('lot');
        $search_where = "";
        $block_where = "";
        $lot_where = "";

        if ($block != "All") {
            $block_where = ' AND block = "' . $block . '" ';
        }
        if ($lot != "All") {
            $lot_where = ' AND lot = "' . $lot . '" ';
        }

        if ($search != '') {
            $search_where = 'AND (CONCAT(fname, " ", mname, " ", lname) LIKE "%' . $search . '%" OR CONCAT(fname, " ", lname) LIKE "%' . $search . '%" OR lname LIKE "%' . $search . '%" OR fname LIKE "%' . $search . '%" OR mname LIKE "%' . $search . '%")';
        }

        $homeowners = $this->general_model->custom_query('SELECT id_ho,lname,fname,mname,block,lot,village,email_add,contact_num,status FROM `tbl_homeowner` WHERE id_ho != 0 '.$search_where. $block_where. $lot_where);

        $all_ho = $this->general_model->custom_query('SELECT COUNT(id_ho) as homeowner_all FROM `tbl_homeowner`');
        $all_ho_active = $this->general_model->custom_query('SELECT COUNT(id_ho) as homeowner_all FROM `tbl_homeowner`WHERE status = "active"');
        $all_ho_inactive = $this->general_model->custom_query('SELECT COUNT(id_ho) as homeowner_all FROM `tbl_homeowner`WHERE status = "inactive"');

        $this->load->library('PHPExcel', null, 'excel');
        // for ($sheet_loop = 0; $sheet_loop < 1; $sheet_loop++) {
        // $this->excel->createSheet(1);
        // }

        // ----------------------------------- Homeowners
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Homeowners');
        $this->excel->getActiveSheet()->setShowGridlines(false);
        $header_condition = [
            ['col' => 'A', 'id' => 'A7', 'title' => 'LAST NAME', 'width' => 30, 'data_id' => 'lname', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT],
            ['col' => 'B', 'id' => 'B7', 'title' => 'FIRST NAME', 'width' => 30, 'data_id' => 'fname', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT],
            ['col' => 'C', 'id' => 'C7', 'title' => 'MIDDLE NAME', 'width' => 20, 'data_id' => 'mname', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
            ['col' => 'D', 'id' => 'D7', 'title' => 'BLOCK', 'width' => 20, 'data_id' => 'block', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
            ['col' => 'E', 'id' => 'E7', 'title' => 'LOT', 'width' => 20, 'data_id' => 'lot', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT],
            ['col' => 'F', 'id' => 'F7', 'title' => 'VILLAGE', 'width' => 20, 'data_id' => 'village', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT],
            ['col' => 'G', 'id' => 'G7', 'title' => 'CONTACT NUM', 'width' => 20, 'data_id' => 'contact_num', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT],
            ['col' => 'H', 'id' => 'H7', 'title' => 'EMAIL', 'width' => 50, 'data_id' => 'email_add', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT],
            ['col' => 'I', 'id' => 'I7', 'title' => 'STATUS', 'width' => 20, 'data_id' => 'status', 'position' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT],

        ];
        $this->excel->getActiveSheet()->setCellValue('B2', "HOASYS HOMEOWNER MEMBERS");
        $this->excel->getActiveSheet()->setCellValue('B3', "TOTAL HOMEOWNERS: " . $all_ho[0]->homeowner_all);
        $this->excel->getActiveSheet()->setCellValue('B4', "TOTAL ACTIVE HOMEOWNERS: " . $all_ho_active[0]->homeowner_all);
        $this->excel->getActiveSheet()->setCellValue('B5', "TOTAL INACTIVE HOMEOWNERS: " . $all_ho_inactive[0]->homeowner_all);
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
        $last_row = (count($homeowners) + $rowNum) - 1;
        if (count($homeowners) > 0) {
            foreach ($homeowners as $condition_rows) {
                for ($loop_header = 0; $loop_header < count($header_condition); $loop_header++) {
                    $this->excel->getActiveSheet()->setCellValue($header_condition[$loop_header]['col'] . $rowNum, $condition_rows->{$header_condition[$loop_header]['data_id']});
                    $this->excel->getActiveSheet()->getStyle($header_condition[$loop_header]['col'] . $rowNum . ":" . $header_condition[$loop_header]['col'] . $rowNum)->getAlignment()->setHorizontal($header_condition[$loop_header]['position']);
                }
                $rowNum++;
            }
        }
        $this->excel->getActiveSheet()->getStyle('A7:I7' . $this->excel->getActiveSheet()->getHighestRow())->applyFromArray(
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
        $message_ho = $admin_name . ' successfully exported homeowner details as excel file.';
        $this->activity_log('homeowner',$id_inserter, $message_ho);
    }
}