<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Candidate Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
	{

        parent::__construct();
        $this->load->model('admin/admin_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
        has_permission();
        $this->limit = $this->site_setting->site_admin_rowperpage;
        $this->thisModuleName ='Candidate';
        $this->thisModuleBaseUrl = site_url('admin/candidate').'/';
        
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
            'thumb_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/admins'),
            'big_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/admins','big'),
        );

	}

    function index($offset=0)
    {
        is_role_access('candidate','index');
        if(!is_numeric($offset))
        {
            $offset = 0;
        }
        $uri_segment = 4;
        $data = $this->common_data;
        $data['title'] = 'candidate';
        $data['meta_key'] = 'candidate';
        $data['meta_desc'] = 'candidate';

        // For Search data
        $suffix_array = array();
        $like_str = '';

        $where_array=array('candidate.status !='=>'3');
        $data['list_records']=array();
        $select_value= 'candidate.*,job.job_title,recruiter.recruiter_name';
        $joins=array(
            array(
                'table'=>'job',
                'condition'=>'candidate.job_id=job.job_id',
                'jointype'=>'left'
            ),
            array(
                'table'=>'recruiter',
                'condition'=>'candidate.recruiter_id=recruiter.recruiter_id',
                'jointype'=>'left'
            ),
        );

        $query_return = $this->database_model->get_joins('candidate',$select_value,$joins,$where_array,'candidate.candidate_id','DESC',$this->limit,$offset,'',$like_str,'','','','Yes');
        $data['list_records'] = $query_return['results'];
        $data['total_records'] = $query_return['total_records'];
        $data['pagination'] = $this->common_model->get_pagination($suffix_array,$this->thisModuleBaseUrl.'index',$data['total_records'],$this->limit,$uri_segment);
        $data['j'] = 0 + $offset;
        $data['offset'] = $offset;
        /*echo "<pre>";
        print_r($data['list_records']);
        die;*/
        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('admin/candidate', $data);
    }

    function edit($id='')
    {
        is_role_access('candidate','edit');
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
            $this->form_data->recruiter_id= $result->recruiter_id;
            $this->form_data->job_id= $result->job_id;
            $this->form_data->candidate_name= $result->candidate_name;
            $this->form_data->candidate_email= $result->candidate_email;
            $this->form_data->candidate_phone= $result->candidate_phone;
            $this->form_data->candidate_location= $result->candidate_location;
            $this->form_data->current_job_title= $result->current_job_title;
            $this->form_data->candidate_linkedin= $result->candidate_linkedin;
            $this->form_data->candidate_git= $result->candidate_git;
            $this->form_data->candidate_fb= $result->candidate_fb;
            $this->form_data->candidate_twitter= $result->candidate_twitter;
            $this->form_data->candidate_photo= $result->candidate_photo;
            $this->form_data->candidate_resume= $result->candidate_resume;
            $this->form_data->rating= $result->rating;
            $this->form_data->candidate_status= $result->candidate_status;
            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('admin/candidate_edit', $data);
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
            if($_FILES['candidate_photo']['name']!='')
            {
                $config['upload_path'] = './uploads/candidate/big';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '10240';
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('candidate_photo'))
                {
                    $error = array('error' => $this->upload->display_errors());
                    $data['upload_error'] = $error;

                    $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
                    $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);

                    $this->error_back_page($PostArray);
                }
                else
                {
                    $file_name = $this->upload->file_name;
                    $this->create_thumb($file_name,60,60,'thumb');
                    $this->create_thumb($file_name,150,150,'size150');
                }
            }

            if($_FILES['candidate_resume']['name']!='')
            {
                $config = array();
                $config['upload_path'] = './uploads/candidate/resume';
                $config['max_size'] = '1024000';
                $config['allowed_types'] = 'pdf';
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('candidate_resume'))
                {
                    $error = array('error' => $this->upload->display_errors());
                    $data['upload_error'] = $error;

                    $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
                    $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);

                    $this->error_back_page($PostArray);
                }
                else
                {
                    $resume_name = $this->upload->file_name;
                }
            }

            $update_array = array(
                'candidate_name' => $PostArray['candidate_name'],
                'candidate_phone' => $PostArray['candidate_phone']!=''?$PostArray['candidate_phone']:'',
                'candidate_email' => !empty($PostArray['email'])?implode(",",$PostArray['email']):'',
                'candidate_location' => $PostArray['candidate_location'],
                'current_job_title' => $PostArray['current_job_title'],
                'candidate_linkedin' => $PostArray['candidate_linkedin']!=''?$PostArray['candidate_linkedin']:'',
                'candidate_git' => $PostArray['candidate_git']!=''?$PostArray['candidate_git']:'',
                'candidate_fb' => $PostArray['candidate_fb']!=''?$PostArray['candidate_fb']:'',
                'candidate_twitter' => $PostArray['candidate_twitter']!=''?$PostArray['candidate_twitter']:'',
                'updated_at'=>$this->ip_date->cur_date
            );

            if($file_name!=''){
                $update_array['candidate_photo'] = $file_name;
            }
            if($resume_name!=''){
                $update_array['candidate_resume'] = $resume_name;
            }

            $this->database_model->update('candidate',$update_array,array('candidate_id'=>$id));
            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('candidate'),
                'activity' => '2',
                'modified_by'=> $this->session->userdata('admin_id'),
            );
            $this->database_model->insert_modified($value_array,$this->session->userdata('admin_id'));
            //REDIRECT
            $this->session->set_flashdata('notification',$this->lang->line('candidate_succ_modified'));
            redirect($this->thisModuleBaseUrl);
            die();
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->candidate_name = $PostArray['candidate_name'];
            @$this->form_data->candidate_email = $PostArray['candidate_email'];
            @$this->form_data->candidate_phone = $PostArray['candidate_phone'];
            @$this->form_data->candidate_location = $PostArray['candidate_location'];
            @$this->form_data->current_job_title = $PostArray['current_job_title'];
            @$this->form_data->candidate_linkedin = $PostArray['candidate_linkedin'];
            @$this->form_data->candidate_git = $PostArray['candidate_git'];
            @$this->form_data->candidate_fb = $PostArray['candidate_fb'];
            @$this->form_data->candidate_twitter = $PostArray['candidate_twitter'];

            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('admin/candidate_edit', $data);

        }
    }
    function information($id){
        is_role_access('candidate','information');
        $data = $this->common_data;
        $data['title'] = 'Jobs';
        $data['meta_key'] = 'Jobs';
        $data['meta_desc'] = 'Jobs';
        $data['sub_module']=  'Candidate Information';

        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $select_value= 'candidate.*,job.job_title,recruiter.recruiter_name';
        $joins=array(
            array(
                'table'=>'job',
                'condition'=>'candidate.job_id=job.job_id',
                'jointype'=>'left'
            ),
            array(
                'table'=>'recruiter',
                'condition'=>'candidate.recruiter_id=recruiter.recruiter_id',
                'jointype'=>'left'
            ),
        );

        $data['candidate']=$this->database_model->get_joins('candidate',$select_value,$joins,array('candidate.status !='=>'3','candidate.candidate_id'=>$id),'candidate.candidate_id','DESC',1)->row();
        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('admin/candidate_info', $data);
    }

    function _set_rules($id='')
    {
        $this->form_validation->set_rules('candidate_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('candidate_location', 'Candidate Location', 'trim|required');
        $this->form_validation->set_rules('current_job_title', 'Current Jobtitle', 'trim|required');
        if($this->input->post('method')!='Edit'){
            $this->form_validation->set_rules('candidate_resume', 'Resume', 'callback_file_check');
        }
    }

    function check_exists($field_value, $id='')
    {
        $data['result'] = $this->database_model->check_record_exist('job','job_id',array('job'=>$this->input->post('job'),'status !='=>'3'));

        if ($data['result']['job_id']>0)
        {
            $this->form_validation->set_message('check_exists',$this->lang->line('job_already_exist'));
            return FALSE;
        }else{
            return TRUE;
        }
        return TRUE;
    }

    public function file_check($str){
        $this->load->helper('file');
        $allowed_mime_type_arr = array('application/pdf');//,'image/gif','image/jpeg','image/pjpeg','image/png','image/x-png'
        $mime = get_mime_by_extension($_FILES['candidate_resume']['name']);
        if(isset($_FILES['candidate_resume']['name']) && $_FILES['candidate_resume']['name']!=""){
            if(in_array($mime, $allowed_mime_type_arr)){
                return true;
            }else{
                $this->form_validation->set_message('file_check', 'Please select only pdf file in resume.');
                return false;
            }
        }else{
            $this->form_validation->set_message('file_check', 'Please choose a resume to upload .');
            return false;
        }
    }

    function create_thumb($file,$width=60,$height=60,$folder='thumb')
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] = './uploads/candidate/big/'.$file;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = FALSE;
        $config['width'] = $width;
        $config['height'] = $height;
        $config['new_image'] = './uploads/candidate/'.$folder.'/'.$file;
        $this->load->library('image_lib', $config);
        $this->image_lib->clear();
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        if(!$this->image_lib->resize())
        {
            /*var_dump($this->image_lib->display_errors());die;
            return false;*/
        }
        else{
            $this->image_lib->clear();
        }

    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */