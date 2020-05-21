<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_email_status extends CI_Controller {
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
        $PostArray = $this->input->post();

        $csrf_check = $this->common_model->csrfguard_validate_token($this->input->post('csrf_name'),$this->input->post('csrf_token'));

        if($csrf_check==true)
        {
            $this->ip_date = $this->common_model->get_date_ip();
            //get the detail
            $select_value= 'personal_email.*,employer.employer_name,employer.company_name,employer.employer_email';
            $joins=array(
                array(
                    'table'=>'employer',
                    'condition'=>'personal_email.employer_id=employer.employer_id',
                    'jointype'=>'left'
                ),
            );

            $result = $this->database_model->get_joins('personal_email',$select_value,$joins,array('personal_email_id'=>$PostArray['request_id']),'personal_email.personal_email_id','ASC',1)->row();

            if($PostArray['status']==1){

                //change the tbl_personal_email status =1
                $this->database_model->update('personal_email',array('email_status'=>'1','password'=>$PostArray['password']),array('personal_email_id'=>$PostArray['request_id']));

                //change the tbl_employer status =1
                $this->database_model->update('employer',array('email_type'=>2,'outreach_email'=>$result->email_username.$this->site_setting->employer_mail_suffix),array('employer_id'=>$result->employer_id));

                //change in session
                $this->session->set_userdata(array('emp_sender_mail'=>$result->email_username.$this->site_setting->employer_mail_suffix));

                //Send the Email to employer for mail created successfully.
                $mail_temp = $this->database_model->get_all_records('mail_templates','*',array('status !='=>'3','mail_slug'=>'personalized_mail_created'),'mail_template_id DESC',1,0)->row();

                // parameter for mail template and function to send
                $to_email = $result->employer_email;
                $from_email = $mail_temp->mail_sender;
                $from_text = $mail_temp->mail_from_text;
                $sub_mail = $mail_temp->mail_subject;

                $msg = str_replace('[USER_NAME]', $result->employer_name, $mail_temp->mail_content);
                $msg = str_replace('[NEW_EMAIL]', $result->email_username.$this->site_setting->employer_mail_suffix, $msg);
                $msg = str_replace('[PASSWORD]', $PostArray['password'], $msg);
                $msg = str_replace('[SITE_LINK]', site_url(), $msg);
                $msg = str_replace('[LOGO_LINK]', site_url('themes/manage/images/logo_white.png'), $msg);
                $msg = str_replace('[SITE_NAME]', $this->site_setting->site_name, $msg);
                $msg = str_replace('[CONTACT_MAIL]', $this->site_setting->contact_email, $msg);
                $msg = str_replace('[SITE_COPYRIGHT]', $this->site_setting->site_copyright, $msg);
                $msg = str_replace('[WEBMAIL_LINK]', 'https://webmail1.hostinger.in/', $msg);

                $result = $this->common_model->send_mail($to_email,$from_email,$from_text,$sub_mail,$msg);
            }
            if($PostArray['status']==3){
                $reason = 'Your personalized mail has been deleted by the admin. The reason for deletion is : '.$PostArray['reject_reason'];
                $this->database_model->delete('personal_email',array('personal_email_id'=>$PostArray['request_id']));
                $this->database_model->update('employer',array('outreach_email'=>$result->employer_email,'email_type'=>'1','email_reject_reason'=>$reason),array('employer_id'=>$result->employer_id));
            }
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