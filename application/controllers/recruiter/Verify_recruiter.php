<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Verify_recruiter Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
	{
        parent::__construct();
        $this->load->model('recruiter/recruiter_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
        has_permission();

        $this->limit = $this->site_setting->site_admin_rowperpage;
        $this->thisModuleName ='Verify Recruiter';
        $this->thisModuleBaseUrl = site_url('recruiter/verify_recruiter').'/';
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
            'thumb_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/recruiters'),
            'big_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/recruiters','big'),
        );
	}

    function index($token)
    {

        $token_result = $this->database_model->get_all_records('recruiter_verification','*',array('recruiter_verification_token'=>$token),'recruiter_id DESC',1,0)->row();

        if(!empty($token_result)){
            //generate random password
            $pass = strtoupper($this->common_model->generateToken(8));
            $this->_salt = $this->common_model->create_pwd_salt();
            $this->_password =  hash('sha256', $this->_salt . ( hash('sha256',$pass) ) );
            //update query for user verification
            $this->database_model->update('recruiter',array('status'=>'1','recruiter_password'=>$this->_password,'recruiter_salt'=>$this->_salt),array('recruiter_id'=>$token_result->recruiter_id));
            //delete the verification token.
            $this->database_model->delete('recruiter_verification',array('recruiter_verification_token'=>$token));
            //get recruiter record
            $recruiter_result = $this->database_model->get_all_records('recruiter','*',array('recruiter_id'=>$token_result->recruiter_id),'recruiter_id DESC',1,0)->row();



            //welcome mail
            //select query for mail
            $mail_temp = $this->database_model->get_all_records('mail_templates','*',array('status !='=>'3','mail_slug'=>'user_welcome_mail'),'mail_template_id DESC',1,0)->row();

            // parameter for mail template and function to send
            $to_email = $recruiter_result->recruiter_email;
            $from_email = $mail_temp->mail_sender;
            $from_text = $mail_temp->mail_from_text;
            $sub_mail =str_replace('[SITE_NAME]', $this->site_setting->site_name,$mail_temp->mail_subject);

            $msg = str_replace('[USER_NAME]', $recruiter_result->recruiter_name,$mail_temp->mail_content);
            $msg = str_replace('[SITE_LINK]', $this->site_setting->site_link, $msg);
            $msg = str_replace('[LOGO_LINK]', site_url('themes/manage/images/logo_white.png'), $msg);
            $msg = str_replace('[SITE_NAME]', $this->site_setting->site_name, $msg);
            $msg = str_replace('[SITE_COPYRIGHT]', $this->site_setting->site_copyright, $msg);
            $msg = str_replace('[CONTACT_MAIL]', $this->site_setting->contact_email, $msg);
            $msg = str_replace('[USERNAME]',$recruiter_result->recruiter_email , $msg);
            $msg = str_replace('[PASSWORD]', $pass, $msg);
            $msg = str_replace('[LOGIN_LINK]','<a href="'.site_url('recruiter/login').'">CLICK HERE</a>' , $msg);
            $result = $this->common_model->send_mail($to_email,$from_email,$from_text,$sub_mail,$msg);

            $this->session->set_flashdata('notification',$this->lang->line('succ_verify_msg'));
        }else{
            $this->session->set_flashdata('error',$this->lang->line('link_exp'));
        }
        /*$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('recruiter/login', $data);*/
        redirect(site_url().'recruiter/login');
        die();
    }
}
/* End of file home.php */
/* Location: ./application/controllers/home.php */