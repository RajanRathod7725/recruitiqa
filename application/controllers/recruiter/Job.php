<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Job Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
	{
        parent::__construct();

        $this->load->model('recruiter/recruiter_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
        has_permission_recruiter();
        $this->limit = $this->site_setting->site_admin_rowperpage;
        $this->thisModuleName ='Jobs';
        $this->thisModuleBaseUrl = site_url('recruiter/job').'/';
        
        $this->common_data = array(
            'title' => $this->thisModuleName,
            'link_add_candidate' => $this->thisModuleBaseUrl.'add_candidate/',
            'candidate_list_link' => $this->thisModuleBaseUrl.'candidate_list/',
            'info_link' => $this->thisModuleBaseUrl.'information/',
            'tbl' => 'job',
            'column' => 'job_id',
            'main_module' => $this->thisModuleName,
            'module_base_url' => $this->thisModuleBaseUrl,
            'roles_key_value' => $this->config->item('roles_key_value'),
            'thumb_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/admins'),
            'big_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/admins','big'),
        );

	}

    function index($offset=0)
    {

        if(!is_numeric($offset))
        {
            $offset = 0;
        }
        $uri_segment = 4;
        $data = $this->common_data;
        $data['title'] = 'Jobs';
        $data['meta_key'] = 'Jobs';
        $data['meta_desc'] = 'Jobs';

        // For Search data
        $suffix_array = array();
        $like_str = '';

        if(@$_GET['section']=='' || @$_GET['section']=='Remaining'){
            $where_array =array('job.status !='=>'3','job.remaining_candidate >'=>0);
        }else{
            $where_array =array('job.status !='=>'3');
        }


        $data['list_records']=array();
        /*$select_value= 'job.*,job_type.title';
        $joins=array(
            array(
                'table'=>'job_type',
                'condition'=>'job.job_type_id=job_type.job_type_id',
                'jointype'=>'left'
            ),
            array(
                'table'=>'job_industry',
                'condition'=>'job.job_industry_id=job_industry.job_industry_id',
                'jointype'=>'left'
            ),
        );

        $query_return = $this->database_model->get_joins('job',$select_value,$joins,$where_array,'job.job_id','DESC',$this->limit,$offset,'',$like_str,'','','','Yes');*/
        $this->limit =10;
        $select_value= 'job.*, (SELECT `candidate_id` FROM `tbl_candidate` WHERE `job_id`=`tbl_job`.`job_id` AND `recruiter_id` = '.$this->session->userdata('recruiter_id').' LIMIT 1) as is_candidate';
        $query_return = $this->database_model->get_all_records('job',$select_value,$where_array,'job.created_at','ASC',$this->limit,$offset,'',$like_str,'','','','Yes');

        $data['list_records'] = $query_return['results'];
        $data['total_records'] = $query_return['total_records'];

        $data['pagination'] = $this->common_model->get_pagination($suffix_array,$this->thisModuleBaseUrl.'index',$data['total_records'],$this->limit,$uri_segment);
        $data['j'] = 0 + $offset;
        $data['offset'] = $offset;

        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('recruiter/job', $data);
    }

    function information($id='')
    {
        if(!is_numeric($id))
            $this->add();

        $data = $this->common_data;
        $data['sub_module']=  'Information';
        $data['method']=  'Information';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['Edit_id']=  $id;


        $select_value= 'job.*,job_type.title as job_type,job_industry.title as job_industry,employer.employer_name';

        $joins=array(
            array(
                'table'=>'job_type',
                'condition'=>'job.job_type_id=job_type.job_type_id',
                'jointype'=>'left'
            ),
            array(
                'table'=>'job_industry',
                'condition'=>'job.job_industry_id=job_industry.job_industry_id',
                'jointype'=>'left'
            ),
            array(
                'table'=>'employer',
                'condition'=>'job.employer_id=employer.employer_id',
                'jointype'=>'left'
            ),
        );
        $data['row'] = $this->database_model->get_joins('job',$select_value,$joins,array('job.job_id'=>$id,'job.status !='=>'3'),'job.job_id','DESC',1)->row();

        $select_value= 'source_location.*,search_radius.radius';
        $joins=array(
            array(
                'table'=>'search_radius',
                'condition'=>'source_location.search_radius_id=search_radius.search_radius_id',
                'jointype'=>'left'
            ),
        );

        $data['locations'] = $this->database_model->get_joins('source_location',$select_value,$joins,array('source_location.status !='=>'3','source_location.job_id'=>$id),'source_location.source_location_id','ASC','')->result();

        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('recruiter/job_info', $data);
    }

    function add_candidate($id)
    {
        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'insert_candidate/'.$id;
        $data['sub_module']=  'Add Candidate';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['method']=  'Add';
        $data['job_id']=  $id;


        /*******************************
        ||  Common data for all page ||
        *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('recruiter/candidate_edit', $data);
    }

    function insert_candidate($id)
    {
        $PostArray = $this->input->post();

        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'insert_candidate/'.$id;
        $data['sub_module']=  'Add';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['method']=  'Add';
        $data['job_id']=  $id;

        $this->_set_rules($id);

        $csrf_check = $this->common_model->csrfguard_validate_token($PostArray['csrf_name'],$PostArray['csrf_token']);


        if($this->form_validation->run() === TRUE && $csrf_check==true)
        {

            $job=$this->database_model->get_all_records('job','job_id,remaining_candidate',array('job_id'=>$id),'job.job_id','ASC',1)->row();


            if($job->remaining_candidate<1){
                $this->session->set_flashdata('error','Opps! There is no remaining candidate for this job.');
                redirect(site_url().'recruiter/job');
                die();
            }


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

            $this->ip_date = $this->common_model->get_date_ip();
            $value_array = array(
                'recruiter_id' => $this->session->userdata('recruiter_id'),
                'job_id' => $id,
                'candidate_name' => $PostArray['candidate_name'],
                'candidate_email' => !empty($PostArray['email'])?implode(",",$PostArray['email']):'',
                'candidate_phone' => $PostArray['candidate_phone']!=''?$PostArray['candidate_phone']:'',
                'candidate_location' => $PostArray['candidate_location'],
                'current_job_title' => $PostArray['current_job_title'],
                'candidate_linkedin' => $PostArray['candidate_linkedin']!=''?$PostArray['candidate_linkedin']:'',
                'candidate_git' => $PostArray['candidate_git']!=''?$PostArray['candidate_git']:'',
                'candidate_fb' => $PostArray['candidate_fb']!=''?$PostArray['candidate_fb']:'',
                'candidate_twitter' => $PostArray['candidate_twitter']!=''?$PostArray['candidate_twitter']:'',
                'candidate_stack' => $PostArray['candidate_stack']!=''?$PostArray['candidate_stack']:'',
                'candidate_google' => $PostArray['candidate_google']!=''?$PostArray['candidate_google']:'',
                'candidate_xing' => $PostArray['candidate_xing']!=''?$PostArray['candidate_xing']:'',
                'candidate_photo' => $file_name!=''?$file_name:'',
                'candidate_resume' => $resume_name!=''?$resume_name:'',
                'candidate_status' => '1',
                'status' => 1,
                'created_at'=>$this->ip_date->cur_date,
                'updated_at'=>$this->ip_date->cur_date,
                'created_ip'=>$this->ip_date->ip,
            );
            
            $candi_id = $this->database_model->save('candidate',$value_array);

            //add record in job conversation table
            $is_conversation = $this->database_model->get_all_records('job_conversation','job_conversation_id',array('user_id'=>$this->session->userdata('recruiter_id'),'job_id'=>$id,'user_type'=>2),'job_conversation_id ASC',1)->row();
            if($is_conversation->job_conversation_id<1){
                $location_array = array(
                    'job_id'=>$id,
                    'user_id'=>$this->session->userdata('recruiter_id'),
                    'user_type'=>2,
                    'status' => '1',
                    'created_at'=>$this->ip_date->cur_date,
                    'updated_at'=>$this->ip_date->cur_date,
                    'created_ip'=>$this->ip_date->ip,
                );
                $this->database_model->save('job_conversation',$location_array);
            }

            //ENTRY IN HISTORY TABLE
            $history_value_array=array(
                'candidate_id'=>$candi_id,
                'candidate_status'=>'1',
                'status' => 1,
                'created_at'=>$this->ip_date->cur_date,
                'updated_at'=>$this->ip_date->cur_date,
                'created_ip'=>$this->ip_date->ip,
            );
            $this->database_model->save('candidate_history',$history_value_array);

            $this->database_model->custom_query('UPDATE `tbl_job` SET `remaining_candidate`=`remaining_candidate` - 1, `submitted_candidate` = `submitted_candidate`+ 1 WHERE `job_id`='.$id);
            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('candidate'),
                'activity' => '1',
                'modified_by'=> $this->session->userdata('recruiter_id'),
            );

            //LOGIC FOR ALL
            $job = $this->database_model->get_all_records('job','*',array('job_id'=>$id),'job_id ASC',1,'')->row();
            if($job->remaining_candidate==0){
                $employer = $this->database_model->get_all_records('employer','employer_email,email_type,outreach_email,employer_name',array('employer_id'=>$job->employer_id),'employer_id ASC',1,'')->row();
                $to_email ='';
                if($employer->email_type==2){
                    $to_email = $employer->employer_email.','.$employer->outreach_email;
                }else{
                    $to_email =$employer->employer_email;
                }
                $mail_temp = $this->database_model->get_all_records('mail_templates', '*', array('status !=' => '3', 'mail_slug' => 'all_profile_delivered'), 'mail_template_id ASC', 1, 0)->row();
                // parameter for mail template and function to send
                $to_email = $to_email;
                $from_email = $mail_temp->mail_sender;
                $from_text = $mail_temp->mail_from_text;
                $sub_mail = str_replace("[JOB]", $job->job_title, $mail_temp->mail_subject);

                $msg = str_replace('[SITE_LINK]', site_url(), $mail_temp->mail_content);
                $msg = str_replace('[LOGO_LINK]', site_url('themes/manage/images/logo_white.png'), $msg);
                $msg = str_replace('[SITE_NAME]', $this->site_setting->site_name, $msg);
                $msg = str_replace('[CONTACT_MAIL]', $this->site_setting->contact_email, $msg);
                $msg = str_replace('[SITE_COPYRIGHT]', $this->site_setting->site_copyright, $msg);
                $msg = str_replace('[EMPLOYER_NAME]',$employer->employer_name , $msg);
                $msg = str_replace('[JOB]', $job->job_title , $msg);

                $result = $this->common_model->send_mail_multiple_user($to_email, $from_email, $from_text, $sub_mail, $msg);
            }

            $this->database_model->insert_modified($value_array,$this->session->userdata('recruiter_id'));
            $this->session->set_flashdata('notification','Candidate has been successfully submitted!');
            redirect(site_url().'recruiter/job/add_candidate/'.$id);
            die();
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->candidate_name = $PostArray['candidate_name'];
            @$this->form_data->candidate_email = !empty($PostArray['email'])?implode(",",$PostArray['email']):'';
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
            $this->load->view('recruiter/candidate_edit', $data);
        }
    }

    function candidate_list($id)
    {
        $data = $this->common_data;
        $data['title'] = 'Jobs';
        $data['meta_key'] = 'Jobs';
        $data['meta_desc'] = 'Jobs';
        $data['sub_module']=  'Candidate List';
        $data['info_link']=  $this->thisModuleBaseUrl.'candidate_info/';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;

        if(!is_numeric(@$offset))
        {
            $offset = 0;
        }
        $uri_segment = 4;
        // For Search data
        $suffix_array = array();
        $like_str = '';
        $data['list_records']=array();

        $select_value= 'candidate.*';
        $query_return = $this->database_model->get_all_records('candidate',$select_value,$where_array =array('candidate.status !='=>'3','candidate.recruiter_id'=>$this->session->userdata('recruiter_id'),'candidate.job_id'=>$id),'candidate.created_at','ASC',$this->limit,$offset,'',$like_str,'','','','Yes');

        $data['list_records'] = $query_return['results'];
        $data['total_records'] = $query_return['total_records'];

        $data['pagination'] = $this->common_model->get_pagination($suffix_array,$this->thisModuleBaseUrl.'index',$data['total_records'],$this->limit,$uri_segment);
        $data['j'] = 0 + $offset;
        $data['offset'] = $offset;
        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('recruiter/candidate', $data);
    }

    function candidate_info($id){

        $data = $this->common_data;
        $data['title'] = 'Jobs';
        $data['meta_key'] = 'Jobs';
        $data['meta_desc'] = 'Jobs';
        $data['sub_module']=  'Candidate List';
        $data['ssub_modele']=  'Candidate Information';

        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;

        $select_value= 'candidate.*';
        $data['candidate'] = $this->database_model->get_all_records('candidate',$select_value,$where_array =array('candidate.status !='=>'3','candidate.recruiter_id'=>$this->session->userdata('recruiter_id'),'candidate.candidate_id'=>$id),'candidate.created_at','ASC',1)->row();

        $data['sub_module_base_url']=  $this->thisModuleBaseUrl.'candidate_list'.'/'.$data['candidate']->job_id;

        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('recruiter/candidate_info', $data);
    }

    function _set_rules($id='')
    {

        $this->form_validation->set_rules('candidate_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Candidate Email', 'trim|callback_check_exists['.$id.']');
        $this->form_validation->set_rules('candidate_location', 'Candidate Location', 'trim|required');
        $this->form_validation->set_rules('current_job_title', 'Current Jobtitle', 'trim|required');
        $this->form_validation->set_rules('candidate_resume', 'Resume', 'callback_file_check');
    }

    function check_exists($id='',$val)
    {
        $job_id = $val;

        $emails = $this->input->post('email');

        foreach ($emails as $email){
            $candidate_id = array();
            $candidate_id = $this->database_model->custom_query("SELECT tbl_candidate.candidate_id FROM `tbl_candidate` WHERE `tbl_candidate`.`status` != '3' AND `tbl_candidate`.`job_id` = ".$job_id." AND FIND_IN_SET('".$email."',`tbl_candidate`.`candidate_email`)")->result();

            if(!empty($candidate_id)){
                $this->form_validation->set_message('check_exists',$this->lang->line('candidate_already_exist'));
                return FALSE;
                break;
            }else{
                $data['result'] = $this->database_model->check_record_exist('black_list','black_list_id',array('email'=>$email,'status !='=>'3'));

                if ($data['result']['black_list_id']>0)
                {
                    $this->form_validation->set_message('check_exists','This Candidate is blacklisted by admin, Please contact to the admin!');
                    return FALSE;
                    break;
                }
            }
        }
        return TRUE;
    }

    public function file_check($str){
        $allowed_mime_type_arr = array('application/pdf');//,'image/gif','image/jpeg','image/pjpeg','image/png','image/x-png'
        $this->load->helper('file');
        $mime = get_mime_by_extension($_FILES['candidate_resume']['name']);
        if(isset($_FILES['candidate_resume']['name']) && $_FILES['candidate_resume']['name']!=""){
            if(in_array($mime, $allowed_mime_type_arr)){

                if(filesize($_FILES['candidate_resume']['tmp_name']) <= 21000000){
                    return true;
                }else{
                    $this->form_validation->set_message('file_check', 'The pdf file size should not exceed 20MB!');
                    return false;
                }
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

    function error_back_page($PostArray){
        $data = $this->common_data;
        @$this->form_data->candidate_name = $PostArray['candidate_name'];
        @$this->form_data->candidate_email = $PostArray['candidate_email'];
        @$this->form_data->candidate_phone = $PostArray['candidate_phone'];
        @$this->form_data->candidate_location = $PostArray['candidate_location'];
        @$this->form_data->current_job_title = $PostArray['current_job_title'];
        @$this->form_data->candidate_linkedin = $PostArray['candidate_linkedin'];
        @$this->form_data->candidate_git = $PostArray['candidate_git'];
        @$this->form_data->candidate_fb = $PostArray['candidate_fb'];
        @$this->form_data->candidate_twitter = $PostArray['candidate_twitter'];
        $this->load->view('recruiter/candidate_edit', $data);
        return;
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */