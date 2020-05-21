<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    var $common_data,$thisModuleName,$thisModuleBaseUrl;

    public function __construct(){
        parent::__construct();
        $this->load->model('database_model');
        $this->load->model('recruiter/recruiter_model');
        $this->load->model('common_model');
        has_permission_recruiter();
        $this->thisModuleName ='Dashboard';
        $this->thisModuleBaseUrl = site_url('recruiter/dashboard').'/';
        $this->common_data = array(
            'title' => $this->thisModuleName,
            'link_add' => $this->thisModuleBaseUrl.'add/',
            'edit_link' => $this->thisModuleBaseUrl.'edit/',
            'info_link' => $this->thisModuleBaseUrl.'information/',
            'tbl' => '',
            'main_module' => $this->thisModuleName,
            'module_base_url' => $this->thisModuleBaseUrl,
            'roles_key_value' => $this->config->item('roles_key_value'),
            'thumb_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/admins'),
            'big_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/admins','big'),
        );
    }
	public function index()
	{
        $data = $this->common_data;
        $lastweek = $this->common_model->getLastWeekDates();
        $lastweek_s_day=$lastweek[0].' 00:00:00';
        $lastweek_e_day=$lastweek[6].' 23:59:59';

        $data['profile'] = $this->database_model->get_all_records('candidate','COUNT(candidate_id) as count',array('candidate.status !='=>'3', 'candidate.recruiter_id'=>$this->session->userdata('recruiter_id'),'candidate.created_at >='=>$lastweek_s_day,'candidate.created_at <='=>$lastweek_e_day),'candidate.candidate_id','ASC')->row();

        $data['job']=$this->database_model->get_all_records('job','COUNT(job_id) as jobcount',array('job.status !='=>'3', 'job.job_status'=>'1'),'job.job_id','ASC')->row();
        $i=0;
        $counter = array();
        foreach ($lastweek as $date){
            $stime =$date.' 00:00:00';
            $etime =$date.' 23:59:59';
            $counter[$i]=$this->database_model->get_all_records('job','COUNT(job_id) as job_count',array('job.status !='=>'3', 'job.job_status'=>'1','job.created_at >='=>$stime,'job.created_at <='=>$etime),'job.job_id','ASC')->row()->job_count;
            $i++;
        }
        $data['week_job']=implode(',',$counter);

		$this->load->view('/recruiter/dashboard',$data);
	}
}
