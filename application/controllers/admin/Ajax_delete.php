<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_delete extends CI_Controller {
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
        $tbl = $this->common_model->Decryption($this->input->post('tbl'));
        $column = $this->common_model->Decryption($this->input->post('column'));
        $modified_status='3';
        $csrf_check = $this->common_model->csrfguard_validate_token($this->input->post('csrf_name'),$this->input->post('csrf_token'));
        $tbl_with_prefix = $this->db->dbprefix($tbl);
        $PostArray = $this->input->post();
        if($csrf_check==true)
        {
            $this->ip_date = $this->common_model->get_date_ip();
            
                $tbl_id = $this->input->post('id');
                $where_con = array($column => $tbl_id);
                $this->database_model->update($tbl, array('status' => $modified_status, 'updated_at' => $this->ip_date->cur_date), $where_con);
                if($tbl =='candidate'){
                    $candidate =$this->database_model->get_all_records('candidate','*',array('candidate_id'=>$tbl_id),'candidate_id ASC',1,'')->row();
                    $this->database_model->custom_query("UPDATE tbl_job SET submitted_candidate = submitted_candidate - 1, remaining_candidate = remaining_candidate + 1 WHERE job_id =".$candidate->job_id);

                    //job record
                    $job =$this->database_model->get_all_records('job','*',array('job_id'=>$candidate->job_id),'job_id ASC',1,'')->row();
                    $this->database_model->custom_query("UPDATE tbl_employer_subscription SET remain_credit = remain_credit + 1 WHERE employer_id =".$job->employer_id." AND subscription_status ='0' AND status='1' ");

                }
                $value_array = array(
                    'table_id' => $tbl_id,
                    'table_name' => $this->db->dbprefix($tbl),
                    'activity' => '3',
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