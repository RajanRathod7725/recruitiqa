<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_get_candidate_emails extends CI_Controller {
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
            $ids = explode(',',$PostArray['ids']);
            $emails='';
            $is_email_found = 0;
            foreach ($ids as $id){

                $email= $this->database_model->get_all_records('candidate','candidate_email',array('candidate.status !='=>'3','candidate_id'=>$id),'candidate.candidate_id','ASC',1)->row()->candidate_email;
                $emails .=$email.',';
            }
            $emails=trim($emails, ',');
            if($emails!=''){
                $is_email_found=1;
            }
            $message=array('code'=>1,'is_email_found'=>$is_email_found,'emails'=>$emails,'type'=>$PostArray['type']);
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