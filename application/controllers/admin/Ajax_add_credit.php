<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_add_credit extends CI_Controller {
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
            $PostArray = $this->input->post();
            $employer_subscription = $this->database_model->get_all_records('employer_subscription','subscription_status,employer_id',array('employer_subscription_id'=>$PostArray['curr_row_this_id']),'employer_subscription_id ASC',1,'')->row();

            if($employer_subscription->subscription_status == 0){
                $this->database_model->custom_query('UPDATE tbl_employer_subscription SET assigned_credit= assigned_credit +'.$PostArray['credit'].',remain_credit=remain_credit+'.$PostArray['credit'].' WHERE employer_subscription_id = '.$PostArray['curr_row_this_id']);

                //ENTRY IN HISTORY TABLE
                $history_value_array=array(
                    'employer_id'=>$employer_subscription->employer_id,
                    'employer_subscription_id'=>$PostArray['curr_row_this_id'],
                    'credit'=>$PostArray['credit'],
                    'status' => 1,
                    'created_at'=>$this->ip_date->cur_date,
                    'updated_at'=>$this->ip_date->cur_date,
                    'created_ip'=>$this->ip_date->ip,
                );
                $this->database_model->save('subscription_credit_history',$history_value_array);

                $value_array = array(
                    'table_id' => $PostArray['id'],
                    'table_name' => 'tbl_employer_subscription',
                    'activity' => '1',
                    'modified_by'=>$this->session->userdata('admin_id'),
                );
                $this->database_model->insert_modified($value_array,$this->session->userdata('admin_id'));

                $message=array('code'=>1);
            }else{
                $message=array('code'=>2,'error_msg'=>'You can not add credit because employer\'s profile is Inactive');
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