<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_activate_account extends CI_Controller {
	public function __construct()
    {
		/**change1234567**///
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
            $tbl_id = $this->input->post('id');

            $result= $this->database_model->get_all_records('employer','email_verification,employer_name,outreach_email',array('employer_id'=>$tbl_id),'employer_id ASC',1,'')->row();
            if($result->email_verification == 1){
                //update employer tbl to active account
                $this->ip_date = $this->common_model->get_date_ip();
                $this->database_model->update('employer',array('status'=>'1'),array('employer_id'=>$tbl_id));

                //calculation for free trial
                $dates = $this->common_model->count_last_day($this->ip_date->cur_date,$this->site_setting->free_subscription_days);

                //set free profile for this employer
                $value_array = array(
                    'employer_id'=>$tbl_id,
                    'subscription_id'=>0,
                    'subscription_name'=>$this->site_setting->free_subscription_name,
                    'start_date'=>$dates[0],
                    'end_date'=>$dates[1],
                    'assigned_credit'=>$this->site_setting->free_subscription_profiles,
                    'remain_credit'=>$this->site_setting->free_subscription_profiles,
                    'subscription_status'=>'0',
                    'status'=>'1',
                    'created_at'=>$this->ip_date->cur_date,
                    'updated_at'=>$this->ip_date->cur_date,
                    'created_ip'=>$this->ip_date->ip,
                );
                $id = $this->database_model->save('employer_subscription',$value_array);
                //update employer tbl
                $value_array = array(
                    'table_id' => $tbl_id,
                    'table_name' => $this->db->dbprefix('employer'),
                    'activity' => '2',
                    'modified_by'=> $this->session->userdata('admin_id'),
                );
                $this->database_model->insert_modified($value_array,$this->session->userdata('admin_id'));
                $html = '<div class="custom-control custom-switch switch-lg custom-switch-success mr-1" id=""> <input type="checkbox" class="list-switch custom-control-input status-switch" id="switch'.$tbl_id.'" checked=""> <label class="custom-control-label" for="switch'.$tbl_id.'"> <span class="switch-text-left">Enable</span> <span class="switch-text-right">Disable</span> </label> </div>';

                //sending account activation mail
                $mail_temp = $this->database_model->get_all_records('mail_templates','*',array('status !='=>'3','mail_slug'=>'employer_ac_activated'),'mail_template_id DESC',1,0)->row();

                // parameter for mail template and function to send
                $to_email = $result->outreach_email;
                $from_email = $mail_temp->mail_sender;
                $from_text = $mail_temp->mail_from_text;
                $sub_mail =str_replace('[SITE_NAME]', $this->site_setting->site_name,$mail_temp->mail_subject);

                $msg = str_replace('[USER_NAME]', $result->employer_name,$mail_temp->mail_content);
                $msg = str_replace('[SITE_LINK]', $this->site_setting->site_link, $msg);
                $msg = str_replace('[LOGO_LINK]', site_url('themes/manage/images/logo_white.png'), $msg);
                $msg = str_replace('[SITE_NAME]', $this->site_setting->site_name, $msg);
                $msg = str_replace('[SITE_COPYRIGHT]', $this->site_setting->site_copyright, $msg);
                $msg = str_replace('[CONTACT_MAIL]', $this->site_setting->contact_email, $msg);
                $msg = str_replace('[DAYS]', $this->site_setting->free_subscription_days, $msg);
                $result = $this->common_model->send_mail($to_email,$from_email,$from_text,$sub_mail,$msg);

                $message=array('code'=>1,'error_msg'=>'','html'=>$html);
            }else{
                $message=array('code'=>2,'error_msg'=>'Email not verified yet!');
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