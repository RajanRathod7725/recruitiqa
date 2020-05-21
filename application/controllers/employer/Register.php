<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {

    var $common_data,$thisModuleName,$thisModuleBaseUrl;

    public function __construct(){
        parent::__construct();
        $this->load->model('database_model');
        $this->load->model('employer/employer_model');
        $this->load->model('common_model');
    }

    public function index()
    {

        if($this->session->userdata('employer_logged_in') == TRUE) {
            header('location:' . site_url('employer/dashboard/'));
        }
        $data = array('title'=>$this->lang->line('employer')." ". $this->lang->line('register'));

        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['countries'] = $this->database_model->get_all_records('countries','*',array(),'country_name ASC','')->result();

        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/register',$data);
    }
    public function create_account()
    {
        $PostArray = $this->input->post();
        $data = array(
            'title'=> $this->lang->line('employer')." ". $this->lang->line('register'),
        );
        $this->_set_rules();

        $csrf_check = $this->common_model->csrfguard_validate_token(@$PostArray['csrf_name'],@$PostArray['csrf_token']);
        if($this->form_validation->run() === TRUE && $csrf_check==true)
        {
            if(@$PostArray['term_condition']!='on'){
                $this->return_screen($this->lang->line('err_term_condition'));
            }else{
                $data['result'] = $this->database_model->check_record_exist('employer','*',array('employer_email'=>$this->input->post('employer_email'),'status !='=>'3'));

                if ($data['result']['employer_id']>0)
                {
                    //prndig then error
                    if($data['result']['status']=='0'){
                        $this->return_screen($this->lang->line('acc_not_conf'));
                        //if exist
                    }else if($data['result']['status']=='1'){
                        $this->return_screen($this->lang->line('account_already_exist'));
                        //if suspended
                    }else{
                        $this->return_screen($this->lang->line('err_acc_suspend'));
                    }
                }else{
                    /*Create New One*/
                    $this->ip_date = $this->common_model->get_date_ip();
                    $this->_salt = $this->common_model->create_pwd_salt();
                    $this->_password =  hash('sha256', $this->_salt . ( hash('sha256',$this->input->post('employer_password')) ) );

                    if(@$_FILES['employer_photo']['name']!='')
                    {
                        $config['upload_path'] = './uploads/employer/big';
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['max_size'] = '1024000';
                        $config['encrypt_name'] = TRUE;
                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload('employer_photo'))
                        {
                            $error = array('error' => $this->upload->display_errors());
                            $data['upload_error'] = $error;

                            $data['countries'] = $this->database_model->get_all_records('countries','*',array(),'country_name ASC','')->result();
                            
                            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
                            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);

                            $this->form_data->employer_name = $PostArray['employer_name'];
                            $this->form_data->employer_email= $PostArray['employer_email'];
                            $this->form_data->employer_linkedin= $PostArray['employer_linkedin'];
                            $this->form_data->employer_phone= $PostArray['employer_phone'];
                            $this->form_data->company_name= $PostArray['company_name'];
                            $this->form_data->company_website= $PostArray['company_website'];
                            $this->form_data->employer_country= $PostArray['employer_country'];
                            $this->form_data->employer_position= $PostArray['employer_position'];
                            $this->form_data->term_condition= $PostArray['term_condition'];
                            $this->load->view('employer/register', $data);
                            return;
                        }
                        else
                        {
                            $file_name = $this->upload->file_name;
                            $this->create_thumb($file_name,60,60,'thumb');
                            $this->create_thumb($file_name,150,150,'size150');
                        }
                    }
                    $value_array = array(
                        'employer_name ' => $PostArray['employer_name'],
                        'employer_email' => $PostArray['employer_email'],
                        'email_type' => '1',
                        'outreach_email' => $PostArray['employer_email'],
                        'email_reject_reason' => '',
                        'employer_password' => $this->_password,
                        'employer_salt' => $this->_salt,
                        'employer_linkedin' => $PostArray['employer_linkedin'],
                        'employer_phone' => $PostArray['employer_phone'],
                        'company_name' => $PostArray['company_name'],
                        'company_website' => $PostArray['company_website'],
                        'country_id' => $PostArray['employer_country'],
                        'employer_position' => $PostArray['employer_position'],
                        'employer_photo' => $file_name!=''? $file_name :'',
                        'employer_type' => 2,
                        'status' => '0',
                        'created_at'=>$this->ip_date->cur_date,
                        'updated_at'=>$this->ip_date->cur_date,
                        'created_ip'=>$this->ip_date->ip,
                    );
                    $id = $this->database_model->save('employer',$value_array);

                    $veri_token = $this->common_model->generateToken(45);
                    $value_array3 = array(
                        'employer_id' => $id,
                        'employer_verification_token' => $veri_token,
                        'created_at'=>$this->ip_date->cur_date,
                    );
                    $last_id = $this->database_model->save('employer_verification',$value_array3);

                    //select query for mail
                    $mail_temp = $this->database_model->get_all_records('mail_templates','*',array('status !='=>'3','mail_slug'=>'user_account_verification'),'mail_template_id DESC',1,0)->row();
                    // parameter for mail template and function to send
                    $to_email = $this->input->post('employer_email');
                    $from_email = $mail_temp->mail_sender;
                    $from_text = $mail_temp->mail_from_text;
                    $sub_mail =str_replace('[SITE_NAME]', $this->site_setting->site_name,$mail_temp->mail_subject);

                    $msg = str_replace('[TOKEN]', $veri_token,$mail_temp->mail_content);
                    $msg = str_replace('[USER_NAME]', $PostArray['employer_name'],$msg);
                    $msg = str_replace('[SITE_LINK]', $this->site_setting->site_link, $msg);
                    $msg = str_replace('[LOGO_LINK]', site_url('themes/manage/images/logo_white.png'), $msg);
                    $msg = str_replace('[SITE_NAME]', $this->site_setting->site_name, $msg);
                    $msg = str_replace('[SITE_COPYRIGHT]', $this->site_setting->site_copyright, $msg);
                    $msg = str_replace('[CONTACT_MAIL]', $this->site_setting->contact_email, $msg);
                    $msg = str_replace('[LINK]', site_url('employer/register/verify_employer/' . $veri_token), $msg);


                    $result = $this->common_model->send_mail($to_email,$from_email,$from_text,$sub_mail,$msg);

                    $this->session->set_flashdata('notification',str_replace('[USER_EMAIL]',$this->input->post('employer_email'), $this->lang->line('user_succ_register')));
                    redirect(site_url().'employer/register');
                    die();
                }
            }
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->employer_name = $PostArray['employer_name'];
            $this->form_data->employer_email= $PostArray['employer_email'];
            $this->form_data->employer_linkedin= $PostArray['employer_linkedin'];
            $this->form_data->employer_password= $PostArray['employer_password'];
            $this->form_data->employer_phone= $PostArray['employer_phone'];
            $this->form_data->company_name= $PostArray['company_name'];
            $this->form_data->company_website= $PostArray['company_website'];
            $this->form_data->employer_country= $PostArray['employer_country'];
            $this->form_data->employer_position= $PostArray['employer_position'];
            $this->form_data->term_condition= $PostArray['term_condition'];
            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['countries'] = $this->database_model->get_all_records('countries','*',array(),'country_name ASC','')->result();

            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);

            $this->load->view('employer/register', $data);
        }
    }
    function _set_rules($id='')
    {
        $PostArray = $this->input->post();
        $this->form_validation->set_rules('employer_name',  'Your Full Name','trim|required');
        $this->form_validation->set_rules('employer_email',  'Work Email','trim|required|valid_email');
        $this->form_validation->set_rules('employer_password',  'Password','trim|required');
        $this->form_validation->set_rules('employer_phone',  'Contact No.','trim|required');
        $this->form_validation->set_rules('employer_country',  'Country','trim|required');
        $this->form_validation->set_rules('employer_position',  'Your Position','trim|required');
        $this->form_validation->set_rules('company_name',  'Company Name','trim|required');
        $this->form_validation->set_rules('company_website',  'Company Website','trim|required');
    }

    function return_screen($message){
        $data['warning_msg']=$message;
        $PostArray = $this->input->post();
        @$this->form_data->employer_name = $PostArray['employer_name'];
        @$this->form_data->employer_email= $PostArray['employer_email'];
        @$this->form_data->employer_linkedin= $PostArray['employer_linkedin'];
        @$this->form_data->employer_phone= $PostArray['employer_phone'];
        @$this->form_data->company_name= $PostArray['company_name'];
        @$this->form_data->company_website= $PostArray['company_website'];
        @$this->form_data->employer_country= $PostArray['employer_country'];
        @$this->form_data->employer_position= $PostArray['employer_position'];
        @$this->form_data->term_condition= $PostArray['term_condition'];
        @$this->form_data->term_condition= $PostArray['term_condition'];

        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/register', $data);
    }
    function create_thumb($file,$width=60,$height=60,$folder='thumb')
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] = './uploads/employer/big/'.$file;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = FALSE;
        $config['width'] = $width;
        $config['height'] = $height;
        $config['new_image'] = './uploads/employer/'.$folder.'/'.$file;
        $this->load->library('image_lib', $config);
        $this->image_lib->clear();
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        if(!$this->image_lib->resize())
        {
            /*var_dump($this->image_lib->display_errors());die;
            return false;*/
        }
        else{
            $this->image_lib->clear();
        }

    }


    function verify_employer($token){
        $token_result = $this->database_model->get_all_records('employer_verification','*',array('employer_verification_token'=>$token,'employer_id ASC',1))->row();
        $this->ip_date = $this->common_model->get_date_ip();
        if(!empty($token_result)){
            //update query for user verification
            $this->database_model->update('employer',array('email_verification'=>'1'),array('employer_id'=>$token_result->employer_id));
            //delete the verification token.
            $this->database_model->delete('employer_verification',array('employer_verification_token'=>$token));
            //get employer record
            $employer_result = $this->database_model->get_all_records('employer','*',array('employer_id'=>$token_result->employer_id),'employer_id DESC',1,0)->row();

            //welcome mail
            //select query for mail
            $mail_temp = $this->database_model->get_all_records('mail_templates','*',array('status !='=>'3','mail_slug'=>'user_welcome_mail_front'),'mail_template_id DESC',1,0)->row();

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
            $result = $this->common_model->send_mail($to_email,$from_email,$from_text,$sub_mail,$msg);

            $this->session->set_flashdata('awesome_notification',$this->lang->line('employer_succ_verify_msg'));
            //redirect to user profile
            redirect(site_url('employer/login'));
            die();
        }else{
            $this->session->set_flashdata('error',$this->lang->line('link_exp'));
            redirect(site_url().'employer/login');
            die();
        }
        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/login', $data);
    }
}
