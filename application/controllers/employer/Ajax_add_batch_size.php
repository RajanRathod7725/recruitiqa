<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_add_batch_size extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('database_model');
        $this->load->model('employer/employer_model');
        $this->load->model('common_model');
        $this->employer_id = $this->session->userdata('super_employer_id')>0?$this->session->userdata('super_employer_id'):$this->session->userdata('employer_id');
        has_permission_employer();
    }
    public function index()
    {
        header('Content-Type: application/json');
        $csrf_check = $this->common_model->csrfguard_validate_token($this->input->post('csrf_name'),$this->input->post('csrf_token'));

        if($csrf_check==true)
        {
            $this->ip_date = $this->common_model->get_date_ip();

            $PostArray = $this->input->post();
            $subscription = $this->common_model->check_subscription($this->employer_id,$PostArray['size']);
            if($subscription['allow_to_add']!=0){
                $this->database_model->custom_query("UPDATE tbl_job SET job_profile_size = ".$PostArray['size'].", remaining_candidate = remaining_candidate + ".$PostArray['size'].", job_status = '1' WHERE job_id = ".$PostArray['id']);

                //ENTRY IN HISTORY TABLE
                $history_value_array=array(
                    'job_id'=>$PostArray['id'],
                    'batch_size'=>$PostArray['size'],
                    'operation_status'=>1,
                    'status' => 1,
                    'created_at'=>$this->ip_date->cur_date,
                    'updated_at'=>$this->ip_date->cur_date,
                    'created_ip'=>$this->ip_date->ip,
                );
                $this->database_model->save('job_batch_history',$history_value_array);

                $value_array = array(
                    'table_id' => $PostArray['id'],
                    'table_name' => 'tbl_job',
                    'activity' => '1',
                    'modified_by'=> $this->employer_id,
                );
                $this->database_model->insert_modified($value_array,$this->employer_id);
                $total = $this->database_model->get_all_records('job','job_profile_size',array('job_id'=>$PostArray['id']),'job_id ASC',1,'')->row()->job_profile_size;

                $this->database_model->custom_query("UPDATE tbl_employer_subscription set remain_credit = remain_credit - ".$PostArray['size']." WHERE employer_subscription_id = ".$subscription['employer_subscription_id']);

                $message=array('code'=>1,'total'=>$total,'error_msg'=>'');
            }else{
                $message=array('code'=>2,'error_msg'=>$subscription['message']);
            }
        }
        else{
            $message=array('code'=>0,'error_msg'=>'');
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