<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_custom_sub_req extends CI_Controller {
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
            $update_array= array(
                'employer_id'=>$this->employer_id,
                'description'=>$PostArray['msg'],
                'request_status'=>'0',
                'status'=>'1',
                'created_at'=>$this->ip_date->cur_date,
                'updated_at'=>$this->ip_date->cur_date,
                'created_ip'=>$this->ip_date->ip,
            );
            $id =$this->database_model->save('subscription_request_cust',$update_array);

            $value_array = array(
                'table_id' => $id,
                'table_name' => 'tbl_subscription_request_cust',
                'activity' => '1',
                'modified_by'=> $this->employer_id,
            );
            $this->database_model->insert_modified($value_array,$this->employer_id);
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