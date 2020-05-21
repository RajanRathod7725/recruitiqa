<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_send_job_msg extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('database_model');
        $this->load->model('recruiter/recruiter_model');
        $this->load->model('common_model');
        has_permission_recruiter();
    }
    public function index()
    {
        header('Content-Type: application/json');
        $csrf_check = $this->common_model->csrfguard_validate_token($this->input->post('csrf_name'),$this->input->post('csrf_token'));

        if($csrf_check==true)
        {
            $this->ip_date = $this->common_model->get_date_ip();
            $PostArray = $this->input->post();

            //inserting the message data
            $value_array=array(
                'job_id'=>$PostArray['job_id'],
                'user_id'=>$this->session->userdata('recruiter_id'),
                'user_type'=>2,
                'message'=>$PostArray['message'],
                'status' => '1',
                'created_at'=>$this->ip_date->cur_date,
                'updated_at'=>$this->ip_date->cur_date,
                'created_ip'=>$this->ip_date->ip,
            );

            $id = $this->database_model->save('job_chat',$value_array);

            $value_array=array(
                'job_chat_id'=>$id,
                'user_id'=>$this->session->userdata('recruiter_id'),
                'user_type'=>2,
            );
            $this->database_model->save('job_chat_flag',$value_array);

            //sender and receiver image
            $sender_pic =check_image($this->session->userdata('common_image'),'uploads/recruiter','size150');

            $msg_time = $this->common_model->time_ago_in_php($this->ip_date->cur_date);
            //html
            $html = '<div class="chat"> <div class="chat-avatar"> <a class="avatar m-0" data-toggle="tooltip" href="javascript:;" data-placement="bottom" title="'.$this->session->userdata('recruiter_name').'" data-original-title="'.$this->session->userdata('recruiter_name').'"> <img src="'.$sender_pic.'" alt="'.$this->session->userdata('recruiter_name').'" height="40" width="40" /> </a> </div> <div class="chat-body"> <div class="chat-content"> <p><span class="span-msg">'.$PostArray['message'].'</span><span class="span-msg-time">'.$msg_time.'</span></p> </div> </div> </div>';
            $message=array('code'=>1,'msg_html'=>$html,'msgtime'=>$msg_time,'last_msg_id'=>$id);
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