<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

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
        $data = array('title'=>$this->lang->line('employer')." ". $this->lang->line('login'));

        /*******************************
        ||  Common data for all page ||
         *******************************/

        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/login',$data);
	}
    public function check()
    {
        //print_r($this->session->userdata());
        $PostArray = $this->input->post();
        $data = array(
            'title'=> $this->lang->line('employer')." ". $this->lang->line('login'),
        );

        $this->form_validation->set_rules('email',  'Email','trim|required|valid_email');
        $this->form_validation->set_rules('password',  $this->lang->line('password'),'required');

        $csrf_check = $this->common_model->csrfguard_validate_token(@$PostArray['csrf_name'],@$PostArray['csrf_token']);
        if($this->form_validation->run() === TRUE && $csrf_check==true)
        {
            $data['login_result'] = $this->common_model->check_login('employer',array('employer_email'=>$this->input->post('email'),'status !='=>'3') );

            if (!empty($data['login_result']))
            {
                if ($data['login_result']['email_verification']=='1')
                {
                    if ($data['login_result']['status']=='1')
                    {
                        $this->_password = hash('sha256',$this->input->post('password'));
                        $this->_password =  hash('sha256',$data['login_result']['employer_salt'] . $this->_password);
                        if($data['login_result']['employer_password'] == $this->_password)
                        {
                            $this->session->sess_regenerate();
                            if($data['login_result']['employer_type']==5){
                                $employer = $this->database_model->get_all_records('employer','employer_id,outreach_email',array('employer_id'=>$data['login_result']['created_by']))->row();
                                $this->session->set_userdata(array('super_employer_id'=>$employer->employer_id,'emp_sender_mail'=>$employer->outreach_email,'emp_sender_name'=>$employer->employer_name));
                            }else{
                                $this->session->set_userdata(array('emp_sender_mail'=>$data['login_result']['outreach_email'],'emp_sender_name'=>$data['login_result']['employer_name']));
                            }
                            /*if($this->input->post('check_right')== 'on')
                            {
                                $this->input->set_cookie('ci_jobboard_session_id',session_id(),(365 * 24 * 60 * 60));
                            }*/
                            $this->session->set_userdata(array(
                                'employer_type'=>$data['login_result']['employer_type'],
                                'login_type'=>'1',
                                'employer_name'=>$data['login_result']['employer_name'],
                                'employer_email'=>$data['login_result']['employer_email'],
                                'employer_photo'=>$data['login_result']['employer_photo'],
                                'employer_id'=>$data['login_result']['employer_id'],
                                'employer_logged_in'=>TRUE ,
                                'common_image'=>$data['login_result']['employer_photo'],
                                'employer_about'=>$data['login_result']['employer_about'],
                                'chat_status'=>$data['login_result']['chat_status'],
                                'employer_position'=>$data['login_result']['employer_position'],
                            ));

                            $this->ip_date = $this->common_model->get_date_ip();
                            $value_array = array(
                                'employer_id' => $this->session->userdata('employer_id'),
                                'employer_login_at'=>$this->ip_date->cur_date,
                                'employer_login_ip'=>$this->ip_date->ip,
                                'employer_login_detail'=> $_SERVER['HTTP_USER_AGENT']
                            );

                            //insert in login log table
                            $id = $this->database_model->save('employer_login_log',$value_array);
                            redirect(site_url('employer/dashboard/'));

                        }
                        else  // WRONG PAASWORD
                        {
                            $this->employer_model->clean_session();
                            $data['error_msg'] = $this->lang->line('err_valid_pwd');
                        }
                    }
                    else // ACCOUNT SUSPENDED
                    {
                        if($data['login_result']['status']=='0'){
                            $this->employer_model->clean_session();
                            $data['error_msg'] = $this->lang->line('acc_not_approve');
                        }else{
                            $this->employer_model->clean_session();
                            $data['error_msg'] = $this->lang->line('err_acc_suspend');
                        }
                    }
                }
                else  // WRONG PAASWORD
                {
                    $this->employer_model->clean_session();
                    $data['sorry_error_msg'] = $this->lang->line('acc_not_conf');
                }

            }
            else // ACCOUNT SUSPENDED
            {
                $this->employer_model->clean_session();
                $data['error_msg'] = $this->lang->line('err_valid_mail');
            }
        }

        if($csrf_check==false){
            $data['csrf_error'] = $this->lang->line('csrf_error');
        }

        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/login',$data);
    }

    public function logout()			//LOGOUT - DESTROY ALL SESSION DATA
    {
        $data = array('title'=>$this->lang->line('employer_login'),'target'=>site_url('manage'), 'error_msg'=>'');
        $this->employer_model->clean_session();
        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/login',$data);
    }
}
