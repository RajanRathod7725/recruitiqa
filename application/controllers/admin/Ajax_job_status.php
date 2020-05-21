<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_job_status extends CI_Controller {
	public function __construct()
    {
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('database_model');
        $this->load->model('common_model');
        has_permission();
    }
	public function index()
	{
        header('Content-Type: application/json');
        $csrf_check = $this->common_model->csrfguard_validate_token($this->input->post('csrf_name'),$this->input->post('csrf_token'));
        if($csrf_check==true)
        {
            $this->ip_date = $this->common_model->get_date_ip();

            $tbl_id = $this->input->post('id');
            $status = $this->input->post('status');
            if($status==1){
                $this->database_model->update('job',array('job_status'=>$status),array('job_id'=>$tbl_id));
            }else {
                $job_result = $this->database_model->get_all_records('job','*',array('job_id'=>$tbl_id),'job_id ASC',1,'')->row();

                //update job table
                $this->database_model->update('job',array('job_status'=>$status,'remaining_candidate'=>0,'job_profile_size'=>0),array('job_id'=>$tbl_id));

                //save the job batch history
                $value_array = array(
                    'job_id'=>$job_result->job_id,
                    'batch_size'=>$job_result->remaining_candidate,
                    'operation_status'=>'1',
                    'status'=>'1',
                    'created_at'=>$this->ip_date->cur_date,
                    'updated_at'=>$this->ip_date->cur_date,
                    'created_ip'=>$this->ip_date->ip,
                );
                $this->database_model->save('job_batch_history',$value_array);
                // todo remain - add remain candidate to the emp_subscription
                // todo remain - add remain candidate to the emp_subscription_detail
            }
            $value_array = array(
                'table_id' => $tbl_id,
                'table_name' => $this->db->dbprefix('job'),
                'activity' => '2',
                'modified_by'=> $this->session->userdata('admin_id'),
            );
            $this->database_model->insert_modified($value_array,$this->session->userdata('admin_id'));
            $message=array('code'=>1);
        }
        else{
            $message=array('code'=>0);
            $message['error']=lang('csrf_error');
        }
        
		$message['csrf_name']="CSRFGuard_".mt_rand(0,mt_getrandmax());
		$message['csrf_token']=$this->common_model->csrfguard_generate_token($message['csrf_name']);
		echo json_encode($message);
		die();
	}
}

/* End of file ajax_delete.php */
/* Location: ./application/controllers/manage/ajax_delete.php */