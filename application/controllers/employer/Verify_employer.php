<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Verify_employer Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
	{
        parent::__construct();
        $this->load->model('employer/employer_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
        has_permission_employer();

        $this->limit = $this->site_setting->site_admin_rowperpage;
        $this->thisModuleName ='Verify Employer';
        $this->thisModuleBaseUrl = site_url('employer/verify_employer').'/';
        $this->common_data = array(
            'title' => $this->thisModuleName,
            'link_add' => $this->thisModuleBaseUrl.'add/',
            'edit_link' => $this->thisModuleBaseUrl.'edit/',
            'info_link' => $this->thisModuleBaseUrl.'information/',
            'tbl' => '',
            'column' => '',
            'main_module' => $this->thisModuleName,
            'module_base_url' => $this->thisModuleBaseUrl,
            'roles_key_value' => $this->config->item('roles_key_value'),
            'thumb_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/employers'),
            'big_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/employers','big'),
        );
	}

    function index($token)
    {

        $token_result = $this->database_model->get_all_records('employer_verification','*',array('employer_verification_token'=>$token),'employer_id DESC',1,0)->row();

        if(!empty($token_result)){
            $this->ip_date = $this->common_model->get_date_ip();
            //generate random password
            $pass = strtoupper($this->common_model->generateToken(8));
            $this->_salt = $this->common_model->create_pwd_salt();
            $this->_password =  hash('sha256', $this->_salt . ( hash('sha256',$pass) ) );
            //update query for user verification
            $this->database_model->update('employer',array('email_verification'=>'1','employer_password'=>$this->_password,'employer_salt'=>$this->_salt),array('employer_id'=>$token_result->employer_id));
            //delete the verification token.
            $this->database_model->delete('employer_verification',array('employer_verification_token'=>$token));

            //get employer record
            $employer_result = $this->database_model->get_all_records('employer','*',array('employer_id'=>$token_result->employer_id),'employer_id DESC',1,0)->row();

            $employer_type = $this->database_model->get_all_records('employer','employer_type',array('status !='=>'3','employer_id'=>$token_result->employer_id),'employer_id ASC',1,0)->row();
            if($employer_type->employer_type==2){
                //calculation for free trial
                $dates = $this->common_model->count_last_day($this->ip_date->cur_date,$this->site_setting->free_subscription_days);
                //set free profile for this employer
                $value_array = array(
                    'employer_id'=>$token_result->employer_id,
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
            }
            //welcome mail
            //select query for mail
            $mail_temp = $this->database_model->get_all_records('mail_templates','*',array('status !='=>'3','mail_slug'=>'user_welcome_mail'),'mail_template_id DESC',1,0)->row();

            // parameter for mail template and function to send
            $to_email = $employer_result->employer_email;
            $from_email = $mail_temp->mail_sender;
            $from_text = $mail_temp->mail_from_text;
            $sub_mail =str_replace('[SITE_NAME]', $this->site_setting->site_name,$mail_temp->mail_subject);

            $msg = str_replace('[USER_NAME]', $employer_result->employer_name,$mail_temp->mail_content);
            $msg = str_replace('[SITE_LINK]', $this->site_setting->site_link, $msg);
            $msg = str_replace('[LOGO_LINK]', site_url('themes/manage/images/logo_white.png'), $msg);
            $msg = str_replace('[SITE_NAME]', $this->site_setting->site_name, $msg);
            $msg = str_replace('[SITE_COPYRIGHT]', $this->site_setting->site_copyright, $msg);
            $msg = str_replace('[CONTACT_MAIL]', $this->site_setting->contact_email, $msg);
            $msg = str_replace('[USERNAME]',$employer_result->employer_email , $msg);
            $msg = str_replace('[PASSWORD]', $pass, $msg);
            $msg = str_replace('[LOGIN_LINK]','<a href="'.site_url('employer/login').'">CLICK HERE</a>' , $msg);
            $result = $this->common_model->send_mail($to_email,$from_email,$from_text,$sub_mail,$msg);

            if($employer_result->employer_type==2){
                $this->session->set_flashdata('awesome_notification',$this->lang->line('admin_cr_emp_succ_verify_msg'));
            }else{
                $this->session->set_flashdata('awesome_notification',$this->lang->line('team_succ_verify_msg'));
            }
        }else{
            $this->session->set_flashdata('error',$this->lang->line('link_exp'));
        }
        /*$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/login', $data);*/
        redirect(site_url().'employer/login');
        die();
    }
}
/* End of file home.php */
/* Location: ./application/controllers/home.php */