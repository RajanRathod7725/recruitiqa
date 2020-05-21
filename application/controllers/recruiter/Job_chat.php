<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Job_chat Extends CI_Controller {
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
        $this->thisModuleBaseUrl = site_url('recruiter/job_chat').'/';

        $this->common_data = array(
            'title' => $this->thisModuleName,
            'tbl' => '',
            'column' => '',
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
        $data['title'] = 'Job Chat';
        $data['meta_key'] = 'Job Chat';
        $data['meta_desc'] = 'Job Chat';

        $where_array=array('job_conversation.status !='=>'3','job_conversation.user_id'=>$this->session->userdata('recruiter_id'),'job_conversation.user_type'=>2,'job.status'=>'1');
        $select_value= 'job_conversation.*,job.job_id,job.job_title,job.job_status,(SELECT CONCAT (message,"||",created_at,"||", user_type,"||",user_id) as msg FROM tbl_job_chat WHERE tbl_job_conversation.job_id = tbl_job_chat.job_id ORDER BY job_chat_id DESC LIMIT 1) as last_msg';
        $joins=array(
            array(
                'table'=>'job',
                'condition'=>'job_conversation.job_id=job.job_id',
                'jointype'=>'left'
            ),
        );

        $data['jobs_chat'] = $this->database_model->get_joins('job_conversation',$select_value,$joins,$where_array,'job.job_title','ASC','')->result();

        $i = 0;
        foreach ($data['jobs_chat'] as $job){
            $cj_totalcounter = $this->database_model->get_all_records('job_chat','GROUP_CONCAT(job_chat_id) as ids,COUNT(job_chat_id) as pre_count' ,array('job_id'=>$job->job_id),'job_chat_id ASC',1)->row();
            if($cj_totalcounter->ids!=''){
                $cj_totaljobcounter = $this->database_model->custom_query('SELECT COUNT(job_chat_id) as count FROM tbl_job_chat_flag WHERE user_id= '.$this->session->userdata('recruiter_id').' AND user_type = 2 AND job_chat_id IN ('.$cj_totalcounter->ids.')')->row()->count;

                $data['jobs_chat'][$i]->unread_counter=$cj_totalcounter->pre_count - $cj_totaljobcounter;
            }

            $i++;
        }
        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('recruiter/job_chat', $data);
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */