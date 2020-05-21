<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hired_candidate Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
	{

        parent::__construct();
        $this->load->model('employer/employer_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
        has_permission_employer();
        $this->limit = $this->site_setting->site_admin_rowperpage;
        $this->thisModuleName ='Hired Candidate';
        $this->thisModuleBaseUrl = site_url('employer/hired_candidate').'/';
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

    function index($offset=0)
    {
        is_role_access_employer('hired_candidate','index');
        if(!is_numeric($offset))
        {
            $offset = 0;
        }
        $uri_segment = 4;
        // For Search data
        $suffix_array = array();
        $like_str = '';
        $data = $this->common_data;
        $data['title'] = 'Candidate';
        $data['meta_key'] = 'Candidate';
        $data['meta_desc'] = 'Candidate';
        // For Search data
        $select_value= 'GROUP_CONCAT(job_id) as ids';
        $jobs = $this->database_model->get_all_records('job',$select_value,array('job.status !='=>'3','job.employer_id'=>$this->employer_id),'job.job_title','ASC','')->row()->ids;
        if(!empty($jobs)){
            $data['list_records'] =$this->database_model->custom_query("SELECT tbl_candidate.*, tbl_job.job_title, tbl_recruiter.recruiter_name FROM `tbl_candidate` LEFT JOIN `tbl_job` ON `tbl_candidate`.`job_id`=`tbl_job`.`job_id` LEFT JOIN `tbl_recruiter` ON `tbl_candidate`.`recruiter_id`=`tbl_recruiter`.`recruiter_id` WHERE `tbl_candidate`.`status` != '3' AND `tbl_candidate`.`candidate_status` = '7' AND `tbl_candidate`.`job_id` IN(".$jobs.") ORDER BY `tbl_candidate`.`candidate_id` DESC")->result();

            $data['total_records'] = count($data['list_records']);
        }else{
            $data['list_records'] = array();
            $data['total_records']=0;
        }


        $data['pagination'] = $this->common_model->get_pagination($suffix_array,$this->thisModuleBaseUrl.'index',$data['total_records'],$this->limit,$uri_segment);
        $data['j'] = 0 + $offset;
        $data['offset'] = $offset;

        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/hired_candidate', $data);
    }
    function information($id){
        is_role_access_employer('hired_candidate','information');
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
        $this->load->view('employer/hired_candidate_info', $data);
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */