<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    var $common_data,$thisModuleName,$thisModuleBaseUrl;

	public function __construct(){
		parent::__construct();
		$this->load->model('database_model');
		$this->load->model('recruiter/recruiter_model');
        $this->load->model('common_model');
	}

	public function index()
	{

        if($this->session->userdata('recruiter_logged_in') == TRUE) {
            header('location:' . site_url('recruiter/dashboard/'));
        }
        $data = array('title'=>$this->lang->line('recruiter')." ". $this->lang->line('login'));

        /*******************************
        ||  Common data for all page ||
         *******************************/

        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('recruiter/login',$data);
	}
    public function check()
    {
        //print_r($this->session->userdata());
        $PostArray = $this->input->post();
        $data = array(
            'title'=> $this->lang->line('recruiter')." ". $this->lang->line('login'),
        );

        $this->form_validation->set_rules('email',  'Email','trim|required|valid_email');
        $this->form_validation->set_rules('password',  $this->lang->line('password'),'required');

        $csrf_check = $this->common_model->csrfguard_validate_token($PostArray['csrf_name'],$PostArray['csrf_token']);
        if($this->form_validation->run() === TRUE && $csrf_check==true)
        {
            $data['login_result'] = $this->common_model->check_login('recruiter',array('recruiter_email'=>$this->input->post('email'),'status !='=>'3') );

            if (!empty($data['login_result']))
            {
                if ($data['login_result']['status']=='1')
                {
                    $this->_password = hash('sha256',$this->input->post('password'));
                    $this->_password =  hash('sha256',$data['login_result']['recruiter_salt'] . $this->_password);

                    if($data['login_result']['recruiter_password'] == $this->_password)
                    {
                        $this->session->sess_regenerate();

                        if($this->input->post('check_right')== 'on')
                        {
                            $this->input->set_cookie('ci_jobboard_session_id',session_id(),(365 * 24 * 60 * 60));
                        }
                        $this->session->set_userdata(array(
                            'recruiter_type'=>2,
                            'login_type'=>'1',
                            'recruiter_name'=>$data['login_result']['recruiter_name'],
                            'recruiter_id'=>$data['login_result']['recruiter_id'],
                            'recruiter_logged_in'=>TRUE ,
                            'common_image'=>$data['login_result']['recruiter_photo']
                        ));
                        $this->session->userdata('recruiter_logged_in');


                        $this->ip_date = $this->common_model->get_date_ip();
                        $value_array = array(
                            'recruiter_id' => $this->session->userdata('recruiter_id'),
                            'recruiter_login_at'=>$this->ip_date->cur_date,
                            'recruiter_login_ip'=>$this->ip_date->ip,
                            'recruiter_login_detail'=> $_SERVER['HTTP_USER_AGENT']
                        );

                        //insert in login log table
                        $id = $this->database_model->save('recruiter_login_log',$value_array);
                        redirect(site_url('recruiter/dashboard/'));

                    }
                    else  // WRONG PAASWORD
                    {
                        $this->recruiter_model->clean_session();
                        $data['error_msg'] = $this->lang->line('err_valid_pwd');
                    }
                }
                else // ACCOUNT SUSPENDED
                {
                    $this->recruiter_model->clean_session();
                    $data['error_msg'] = $this->lang->line('err_acc_suspend');
                }
            }
            else // ACCOUNT SUSPENDED
            {
                $this->recruiter_model->clean_session();
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
        $this->load->view('recruiter/login',$data);
    }

    public function logout()			//LOGOUT - DESTROY ALL SESSION DATA
    {
        $data = array('title'=>$this->lang->line('recruiter_login'),'target'=>site_url('manage'), 'error_msg'=>'');
        $this->recruiter_model->clean_session();
        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('recruiter/login',$data);
    }
}
