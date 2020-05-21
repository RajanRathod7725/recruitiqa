<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_approve_cust_subscription extends CI_Controller {
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
            $Postarray = $this->input->post();
            $this->ip_date = $this->common_model->get_date_ip();
            //subscription result
            $select_value= 'subscription_request_cust.*,employer.employer_name,employer.company_name,employer.outreach_email';
            $joins=array(
                array(
                    'table'=>'employer',
                    'condition'=>'subscription_request_cust.employer_id=employer.employer_id',
                    'jointype'=>'left'
                ),
            );

            $result= $this->database_model->get_joins('subscription_request_cust',$select_value,$joins,array('subscription_request_cust.status !='=>'3','subscription_request_cust_id'=>$Postarray['c_request_id']),'subscription_request_cust.subscription_request_cust_id','DESC','1')->row();

            $current_subscription = $this->database_model->get_all_records('employer_subscription','*',array('employer_id'=>$result->employer_id,'status'=>'1','subscription_status'=>'0'),'employer_subscription_id DESC',1)->row();

            //if current time subscription is present
            if($current_subscription->employer_subscription_id>0){
                $final_credit = $current_subscription->remain_credit + $Postarray['profile'];
            }else{
                $final_credit = $Postarray['profile'];
            }
            $start_date = $admin_start_date = DateTime::createFromFormat('d/m/Y', $Postarray['start_date'])->format('Y-m-d');
            $end_date = $admin_start_date = DateTime::createFromFormat('d/m/Y', $Postarray['end_date'])->format('Y-m-d');

            //1 update subscription request
            $this->database_model->update('subscription_request_cust',array('request_status'=>'1'),array('subscription_request_cust_id'=>$result->subscription_request_cust_id));

            //2 if current pack is active then it will be expire and add new entry
            if($current_subscription->employer_subscription_id>0){
                $this->database_model->update('employer_subscription',array('subscription_status'=>'1'),array('employer_subscription_id'=>$current_subscription->employer_subscription_id));
            }

            //3 new entry in employer subscription with new entry
            $value_array = array(
                'employer_id'=>$result->employer_id,
                'subscription_id'=>0,
                'subscription_name'=>$Postarray['sub_name'],
                'start_date'=>$start_date,
                'end_date'=>$end_date,
                'assigned_credit'=>$final_credit,
                'remain_credit'=>$final_credit,
                'subscription_status'=>'0',
                'status'=>'1',
                'created_at'=>$this->ip_date->cur_date,
                'updated_at'=>$this->ip_date->cur_date,
                'created_ip'=>$this->ip_date->ip,
            );
            $id = $this->database_model->save('employer_subscription',$value_array);

            //4 entry in modify tbl
            $value_array = array(
                'table_id' => $result->subscription_request_cust_id,
                'table_name' => $this->db->dbprefix('subscription_request_cust'),
                'activity' => '2',
                'modified_by'=> $this->session->userdata('admin_id'),
            );
            $this->database_model->insert_modified($value_array,$this->session->userdata('admin_id'));

            $html = '<span class="text-success">Approved</span>';

            //5 sending mail with latest subscription detail
            $mail_temp = $this->database_model->get_all_records('mail_templates','*',array('status !='=>'3','mail_slug'=>'subscription_approve'),'mail_template_id DESC',1,0)->row();

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
            $msg = str_replace('[PLAN]', $Postarray['sub_name'], $msg);
            $msg = str_replace('[PROFILE]', $final_credit, $msg);
            $msg = str_replace('[LASTDATE]',date('d M,Y',strtotime($end_date)), $msg);
            $result = $this->common_model->send_mail($to_email,$from_email,$from_text,$sub_mail,$msg);

            $message=array('code'=>1,'html'=>$html);

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