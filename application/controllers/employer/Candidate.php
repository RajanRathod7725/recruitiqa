<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing as drawing;

class Candidate Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
	{

        parent::__construct();
        $this->load->model('employer/employer_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
        has_permission_employer();
        $this->limit = $this->site_setting->site_admin_rowperpage;
        $this->thisModuleName ='Candidate';
        $this->thisModuleBaseUrl = site_url('employer/candidate').'/';
        $this->employer_id = $this->session->userdata('super_employer_id')>0?$this->session->userdata('super_employer_id'):$this->session->userdata('employer_id');
        $this->common_data = array(
            'title' => $this->thisModuleName,
            'link_add' => $this->thisModuleBaseUrl.'add/',
            'edit_link' => $this->thisModuleBaseUrl.'edit/',
            'info_link' => $this->thisModuleBaseUrl.'information/',
            'tbl' => 'candidate',
            'column' => 'candidate_id',
            'main_module' => $this->thisModuleName,
            'module_base_url' => $this->thisModuleBaseUrl,
            'roles_key_value' => $this->config->item('roles_key_value'),
            'thumb_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/employers'),
            'big_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/employers','big'),
        );

	}

    function index($id)
    {
        is_role_access_employer('candidate','index');
        $data = $this->common_data;
        $data['title'] = 'Candidate';
        $data['meta_key'] = 'Candidate';
        $data['meta_desc'] = 'Candidate';
        $data['job_id']=$id;
        // For Search data
        $select_value= 'job.*';
        $data['jobs'] = $this->database_model->get_all_records('job',$select_value,array('job.status !='=>'3','job.employer_id'=>$this->employer_id),'job.job_title','ASC','')->result();
        $data['reasons'] = $this->database_model->get_all_records('reject_reason','reject_reason.*',array('reject_reason.status !='=>'3'),'reject_reason.reason','ASC','')->result();

        $candidate_select = 'candidate_id,candidate_name,candidate_email,candidate_location,current_job_title,candidate_linkedin,candidate_git,candidate_fb,candidate_twitter,candidate_stack,candidate_google,candidate_xing,candidate_photo,created_at,rating,candidate_status';

        $candidate = $this->database_model->get_all_records('candidate',$candidate_select,array('candidate.status !='=>'3','job_id'=>$id),'candidate.candidate_id','ASC',$this->limit)->result_array();

        if(!empty($candidate)){
            $data['candidates']=$this->common_model->group_by('candidate_status',$candidate);
        }else{
            $data['candidates']=array();
        }

        $data['mail_temps'] = $this->database_model->get_all_records('employer_mail_templates','mail_template_id,mail_title',array('status !='=>'3','employer_id'=>$this->employer_id),'mail_template_id ASC','')->result();
        /*echo "<pre>";
        print_r($candidate);
        echo "<pre>";
        print_r($data['candidates']);
        die;*/
        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/candidate', $data);
    }

    function add()
    {
        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'insert';
        $data['sub_module']=  'Add';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['method']=  'Add';
        
        /*******************************
        ||  Common data for all page ||
        *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/candidate_edit', $data);
    }

    function insert()
    {
        $PostArray = $this->input->post();

        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'insert';
        $data['sub_module']=  'Add';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['method']=  'Add';

        $this->_set_rules();
        $csrf_check = $this->common_model->csrfguard_validate_token($PostArray['csrf_name'],$PostArray['csrf_token']);

        if($this->form_validation->run() === TRUE && $csrf_check==true)
        {
            $this->ip_date = $this->common_model->get_date_ip();
            $value_array = array(
                'title ' => $PostArray['title'],
                'description ' => $PostArray['description'],
                'candidate_status' => $PostArray['candidate_status'],
                'employer_id' => $this->employer_id,
                'status' => '1',
                'created_at'=>$this->ip_date->cur_date,
                'updated_at'=>$this->ip_date->cur_date,
                'created_ip'=>$this->ip_date->ip,
            );
            $id = $this->database_model->save('candidate',$value_array);

            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('candidate'),
                'activity' => '1',
                'modified_by'=> $this->employer_id,
            );

            $this->database_model->insert_modified($value_array,$this->employer_id);
            $this->session->set_flashdata('notification',$this->lang->line('candidate_succ_added'));
            redirect($this->thisModuleBaseUrl);
            die();
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->title = $PostArray['title'];

            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('employer/candidate_edit', $data);
        }
    }

    function edit($id='')
    {
        if(!is_numeric($id))
            $this->add();
        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'update/'.$id;
        $data['sub_module']=  'Edit';
        $data['method']=  'Edit';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['Edit_id']=  $id;

        $select_val = 'candidate.*';
        $result = $this->database_model->get_all_records('candidate',$select_val,array('candidate.status !='=>'3','candidate.candidate_id'=>$id),'candidate.candidate_id DESC','1')->row();

        if(!empty($result))
        {
            @$this->form_data->id = $result->candidate_id;
            $this->form_data->title = $result->title;
            $this->form_data->description = $result->description;
            $this->form_data->candidate_status = $result->candidate_status;
            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('employer/candidate_edit', $data);
        }
        else
        {
            $this->add();
        }
    }

    function update($id)
    {
        $PostArray = $this->input->post();
        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'update/'.$id;
        $data['sub_module']=  'Edit';
        $data['method']=  'Edit';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['Edit_id']=  $id;

        $this->_set_rules($id);
        $csrf_check = $this->common_model->csrfguard_validate_token($PostArray['csrf_name'],$PostArray['csrf_token']);
        $this->ip_date = $this->common_model->get_date_ip();
        if($this->form_validation->run() === TRUE && $csrf_check==true)
        {

            $this->ip_date = $this->common_model->get_date_ip();
            $update_array = array(
                'title ' => $PostArray['title'],
                'description' => $PostArray['description'],
                'candidate_status' => $PostArray['candidate_status'],
                'updated_at'=>$this->ip_date->cur_date
            );
            $this->database_model->update('candidate',$update_array,array('candidate_id'=>$id));
            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('candidate'),
                'activity' => '2',
                'modified_by'=> $this->employer_id,
            );
            $this->database_model->insert_modified($value_array,$this->employer_id);
            //REDIRECT
            $this->session->set_flashdata('notification',$this->lang->line('candidate_succ_modified'));
            redirect($this->thisModuleBaseUrl);
            die();
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->title = $PostArray['title'];

            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('employer/candidate_edit', $data);

        }
    }

    function export_candidate($id='')
    {
        is_role_access_employer('candidate','export_candidate');
        //ini_set("include_path",'/home/silvegod/php:'.ini_get("include_path"));
        $data = $this->common_data;
        $data['sub_module']=  'Export Candidate';
        $data['method']=  'Export Candidate';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName;

        $candidate_select = 'candidate_id,candidate_name,candidate_email,candidate_location,current_job_title,candidate_linkedin,candidate_git,candidate_fb,candidate_twitter,candidate_stack,candidate_google,candidate_xing,candidate_photo,created_at,rating,candidate_status';

        $status= $this->config->item('candidate_status');
        $employeeData = $this->database_model->get_all_records('candidate',$candidate_select,array('candidate.status !='=>'3','job_id'=>$id),'candidate.candidate_id','ASC','')->result();
        /*echo "<pre>";
        print_r($employeeData);
        print_r($status);
        die;*/
        //$employeeData = $this->database_model->get_all_records('')->result();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //Create Styles Array
        $styleArrayFirstRow = [
            'font' => [
                'bold' => true,
            ]
        ];

        //Retrieve Highest Column (e.g AE)
        //$highestColumn = $sheet->getHighestColumn();

        //set first row bold
        $sheet->getStyle('A1:M1' )->applyFromArray($styleArrayFirstRow);

        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'Email');
        $sheet->setCellValue('C1', 'Current Location');
        $sheet->setCellValue('D1', 'Current Job Title');
        $sheet->setCellValue('E1', 'Ratting(Out of 5)');
        $sheet->setCellValue('F1', 'Job Status');
        $sheet->setCellValue('G1', 'LinkedIn');
        $sheet->setCellValue('H1', 'Github');
        $sheet->setCellValue('I1', 'Facebook');
        $sheet->setCellValue('J1', 'Twitter');
        $sheet->setCellValue('K1', 'Stack Overflow');
        $sheet->setCellValue('L1', 'Google Plus');
        $sheet->setCellValue('M1', 'Xing');
        $rows = 2;
        foreach ($employeeData as $val){
            foreach ($status as $key => $value){
                if($val->candidate_status==$key){
                    $job_status=$value;
                }
            }
            $sheet->setCellValue('A' . $rows, $val->candidate_name);
            $sheet->setCellValue('B' . $rows, $val->candidate_email);
            $sheet->setCellValue('C' . $rows, $val->candidate_location);
            $sheet->setCellValue('D' . $rows, $val->current_job_title);
            $sheet->setCellValue('E' . $rows, $val->rating);
            $sheet->setCellValue('F' . $rows, $job_status);
            $sheet->setCellValue('G' . $rows, $val->candidate_linkedin);
            $sheet->setCellValue('H' . $rows, $val->candidate_git);
            $sheet->setCellValue('I' . $rows, $val->candidate_fb);
            $sheet->setCellValue('J' . $rows, $val->candidate_twitter);
            $sheet->setCellValue('K' . $rows, $val->candidate_stack);
            $sheet->setCellValue('L' . $rows, $val->candidate_google);
            $sheet->setCellValue('M' . $rows, $val->candidate_xing );

            $rows++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'all-candidate';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');  // download file
    }
    function _set_rules($id='')
    {   
        $PostArray = $this->input->post();
        $this->form_validation->set_rules('title', 'Title', 'trim|required|callback_check_exists['.$id.']');
        $this->form_validation->set_rules('candidate_status', 'Status', 'trim');
    }

    function check_exists($field_value, $id='')
    {
        $data['result'] = $this->database_model->check_record_exist('candidate','candidate_id',array('title'=>$this->input->post('title'),'status !='=>'3'));
        
        if ($data['result']['candidate_id']>0)
        {
            $this->form_validation->set_message('check_exists',$this->lang->line('candidate_already_exist'));
                return FALSE;
        }else{
            return TRUE;
        }
        return TRUE;
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */