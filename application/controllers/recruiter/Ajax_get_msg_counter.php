<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_get_msg_counter extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('database_model');
        $this->load->model('recruiter/recruiter_model');
        $this->load->model('common_model');
        $this->recruiter_id = $this->session->userdata('super_recruiter_id')>0?$this->session->userdata('super_recruiter_id'):$this->session->userdata('recruiter_id');
        has_permission_recruiter();
    }
    public function index()
    {
        header('Content-Type: application/json');
        $csrf_check = $this->common_model->csrfguard_validate_token($this->input->post('csrf_name'),$this->input->post('csrf_token'));


        //total job msg counter
        $total_job =  $this->database_model->get_all_records('job_conversation','GROUP_CONCAT(job_id) as job_ids' ,array('user_id'=>$this->session->userdata('recruiter_id'),'user_type'=>2),'job_conversation_id ASC',1)->row();
        if($total_job->job_ids!='') {
            $totalcounter = $this->database_model->custom_query('SELECT GROUP_CONCAT(job_chat_id) as ids,COUNT(job_chat_id) as pre_count FROM tbl_job_chat WHERE job_id IN (' . $total_job->job_ids . ')')->row();
            if($totalcounter->ids!=''){
                $totaljobcounter = $this->database_model->custom_query('SELECT COUNT(job_chat_id) as count FROM tbl_job_chat_flag WHERE user_id= ' . $this->session->userdata('recruiter_id') . ' AND user_type = 2 AND job_chat_id IN (' . $totalcounter->ids . ')')->row()->count;
                $total_job_counter = $totalcounter->pre_count - $totaljobcounter;
            }

        }
        //single job msg counter
        $job_conversation = $this->database_model->get_all_records('job_conversation','job_id',array('job_conversation.status !='=>'3','job_conversation.user_id'=>$this->session->userdata('recruiter_id'),'job_conversation.user_type'=>2),'job_id ASC','')->result();

        $single_job_count = array();
        foreach ($job_conversation as $job_con){
            $cj_totalcounter = $this->database_model->get_all_records('job_chat','GROUP_CONCAT(job_chat_id) as ids,COUNT(job_chat_id) as pre_count' ,array('job_id'=>$job_con->job_id),'job_chat_id ASC',1)->row();
            if($cj_totalcounter->ids!=''){
                $single_job = $this->database_model->custom_query('SELECT COUNT(job_chat_id) as count FROM tbl_job_chat_flag WHERE user_id= '.$this->session->userdata('recruiter_id').' AND user_type = 2 AND job_chat_id IN ('.$cj_totalcounter->ids.')')->row()->count;

                $single_job_count[$job_con->job_id] =$cj_totalcounter->pre_count - $single_job;
            }

        }

        $message=array('code'=>1,'total_job_counter'=>$total_job_counter,'single_job_count'=>$single_job_count);


        $message['csrf_name']="CSRFGuard_".mt_rand(0,mt_getrandmax());
        $message['csrf_token']=$this->common_model->csrfguard_generate_token($message['csrf_name']);
        echo json_encode($message);
        die();
    }
}

/* End of file ajax_delete.php */
/* Location: ./application/controllers/manage/ajax_delete.php */