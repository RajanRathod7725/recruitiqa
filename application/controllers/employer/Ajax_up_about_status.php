<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_up_about_status extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('database_model');
        $this->load->model('employer/employer_model');
        $this->load->model('common_model');
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
            $this->database_model->update('employer',array('employer_about'=>$PostArray['employer_about'],'chat_status'=>$PostArray['chat_status']),array('employer_id'=>$this->session->userdata('employer_id')));
            $this->session->set_userdata(array(
                'employer_about'=>$PostArray['employer_about'],
                'chat_status'=>$PostArray['chat_status'],
                ));
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