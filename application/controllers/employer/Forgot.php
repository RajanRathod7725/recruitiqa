<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forgot extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('database_model');
        $this->load->model('common_model');
        $this->load->model('employer/employer_model');
        if($this->session->userdata('user_logged_in') == TRUE){
            header('location:' . site_url('employer/dashboard/'));
        }
        $this->thisModuleName ='Forgot';
        $this->thisModuleBaseUrl = site_url('employer/forgot').'/';
        $this->common_data = array(
            'title' => $this->thisModuleName,
            'action' => $this->thisModuleBaseUrl.'check/',
        );
    }
    public function index()
    {
        $data = $this->common_data;
        $data['title'] = "Forgot Password | ".$this->site_setting->site_name;
        $data['meta_keyword'] = $this->site_setting->meta_keyword;
        $data['meta_description'] = $this->site_setting->meta_description;
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);

        $this->load->view('employer/forgot', $data);
    }
    public function check()
    {
        $PostArray = $this->input->post();
        $data = array('title'=>$this->lang->line('forg_password'), 'error_msg'=>'');

        $this->form_validation->set_rules('forgot_email', $this->lang->line('email'),'required|valid_email');

        $csrf_check = $this->common_model->csrfguard_validate_token($PostArray['csrf_name'],$PostArray['csrf_token']);

        if($this->form_validation->run() === TRUE && $csrf_check==true)
        {

            $data['login_result'] = $this->common_model->check_login('employer',array('employer_email'=>$this->input->post('forgot_email'),'status !='=>'3') );
            if(empty($data['login_result']))
            {
                $data['error_msg']=$this->lang->line('js_email');
            }
            else
            {
                if ($data['login_result']['status']=='1')
                {
                    $name=$data['login_result']['employer_name'];
                    $this->_salt = $this->common_model->create_pwd_salt();
                    $token = hash('sha256', $this->_salt . ( hash('sha256',$data['login_result']['employer_id']) ) ).time() . rand(1,988);
                    $this->ip_date = $this->common_model->get_date_ip();
                    $value_array=array(
                        'employer_id' => $data['login_result']['employer_id'],
                        'employer_token' => $token,
                        'created_at'=>$this->ip_date->cur_date,
                    );
                    $this->database_model->replace('employer_forget_password',$value_array);

                    $mail_temp = $this->database_model->get_all_records('mail_templates','*',array('status !='=>'3','mail_slug'=>'user_forgot'),'mail_template_id DESC',1,0)->row();

                    // parameter for mail template and function to send
                    $to_email = $this->input->post('forgot_email');
                    $from_email = $mail_temp->mail_sender;
                    $from_text = $mail_temp->mail_from_text;
                    $sub_mail = $mail_temp->mail_subject;

                    $msg = str_replace('[USER_NAME]', $name, $mail_temp->mail_content);
                    $msg = str_replace('[TOKEN]', $token, $msg);
                    $msg = str_replace('[SITE_LINK]', site_url(), $msg);
                    $msg = str_replace('[LOGO_LINK]', site_url('themes/manage/images/logo_white.png'), $msg);
                    $msg = str_replace('[SITE_NAME]', $this->site_setting->site_name, $msg);
                    $msg = str_replace('[CONTACT_MAIL]', $this->site_setting->contact_email, $msg);
                    $msg = str_replace('[SITE_COPYRIGHT]', $this->site_setting->site_copyright, $msg);
                    $msg = str_replace('[LINK]', site_url('employer/recover_password/index/' . $token), $msg);

                    $result = $this->common_model->send_mail($to_email,$from_email,$from_text,$sub_mail,$msg);
                    if($result!='')
                    {
                        $this->session->set_flashdata('notification',$this->lang->line('frgt_mail_sent'));
                        redirect(site_url('employer/forgot/'));
                        die();
                    }
                    else
                    {
                        $data['error_msg']=$this->lang->line('frgt_mail_failed');
                    }
                }
                else // ACCOUNT SUSPENDED
                {
                    $data['error_msg']=$this->lang->line('err_acc_suspend');
                }
            }
        }
        else  //EMPTY FIELDS
        {
            $this->session->unset_userdata(array('login_type'=>'','employer_id'=>'', 'employer_logged_in'=>FALSE, 'employer_name'=>'', 'employer_email'=>'', 'employer_phone'=>'', 'employer_profile_pic'=>'', 'employer_gender'=>'', 'employer_dob'=>'', 'employer_marital_status'=>'', 'employer_anniversary_date'=>'', 'employer_wallet_balance'=>''));

        }
        if($csrf_check==false){
            $data['csrf_error'] = $this->lang->line('csrf_error');
        }
        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/forgot', $data);
    }
}
