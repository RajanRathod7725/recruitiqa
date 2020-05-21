<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_mail_type_change extends CI_Controller {
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
        $msg_show = 0;
        if($csrf_check==true)
        {
            $this->ip_date = $this->common_model->get_date_ip();
            if($this->input->post('type')==1){
                $this->database_model->update('employer',array('email_type'=>'1','outreach_email'=>$this->session->userdata('employer_email')),array('employer_id'=>$this->employer_id));
                $this->session->set_userdata(array('emp_sender_mail'=>$this->session->userdata('employer_email')));
                $msg_show = 1;
            }else{
                $emp_mail = $this->database_model->get_all_records('personal_email','email_username',array('employer_id'=>$this->employer_id,'email_status'=>'1'),'employer_id','ASC',1)->row();
                if(!empty($emp_mail)){
                    $this->database_model->update('employer',array('email_type'=>'2','outreach_email'=>$emp_mail->email_username.$this->site_setting->employer_mail_suffix),array('employer_id'=>$this->employer_id));
                    $this->session->set_userdata(array('emp_sender_mail'=>$emp_mail->email_username.$this->site_setting->employer_mail_suffix));
                    $msg_show = 1;
                }
            }

            $message=array('code'=>1,'msg_show'=>$msg_show);
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