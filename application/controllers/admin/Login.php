<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    var $common_data,$thisModuleName,$thisModuleBaseUrl;

	public function __construct(){
		parent::__construct();
		$this->load->model('database_model');
		$this->load->model('admin/admin_model');
        $this->load->model('common_model');
	}

	public function index()
	{

        if($this->session->userdata('admin_logged_in') == TRUE) {
            header('location:' . site_url('admin/dashboard/'));
        }
        $data = array('title'=>$this->lang->line('admin')." ". $this->lang->line('login'));

        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('admin/login',$data);
	}
    public function check()
    {
        /*print_r($this->session->userdata());die;*/
        $PostArray = $this->input->post();

        $data = array(
            'title'=> $this->lang->line('admin')." ". $this->lang->line('login'),
        );
        $this->form_validation->set_rules('email',  'Email','trim|required|valid_email');
        $this->form_validation->set_rules('password',  $this->lang->line('password'),'required');

        $csrf_check = $this->common_model->csrfguard_validate_token(@$PostArray['csrf_name'],@$PostArray['csrf_token']);

        if($this->form_validation->run() === TRUE && $csrf_check==true)
        {
            $data['login_result']= $this->common_model->check_login('admins',array('admin_email'=>$this->input->post('email'),'status !='=>'3') );

            if (!empty($data['login_result']))
            {
                if ($data['login_result']['status']=='1')
                {
                    $this->_password = hash('sha256',$this->input->post('password'));
                    $this->_password =  hash('sha256',$data['login_result']['admin_salt'] . $this->_password);

                    if($data['login_result']['admin_password'] == $this->_password)
                    {
                        $this->session->sess_regenerate();

                        $this->session->set_userdata(array(
                            'admin_type'=>$data['login_result']['admin_type'],
                            'login_type'=>$data['login_result']['admin_type'],
                            'admin_name'=>$data['login_result']['admin_name'],
                            'admin_id'=>$data['login_result']['admin_id'],
                            'admin_logged_in'=>TRUE ,
                            'common_image'=>$data['login_result']['admin_photo']
                        ));
                        $this->session->userdata('admin_logged_in');

                        $this->ip_date = $this->common_model->get_date_ip();
                        $value_array = array(
                            'admin_id' => $this->session->userdata('admin_id'),
                            'admin_login_at'=>$this->ip_date->cur_date,
                            'admin_login_ip'=>$this->ip_date->ip,
                            'admin_login_detail'=> $_SERVER['HTTP_USER_AGENT']
                        );
                        //insert in login log table
                        $id = $this->database_model->save('admin_login_log',$value_array);
                        redirect(site_url('admin/dashboard'));
                        die;
                    }
                    else  // WRONG PAASWORD
                    {
                        $this->admin_model->clean_session();
                        $data['error_msg'] = $this->lang->line('err_valid_pwd');
                    }
                }
                else // ACCOUNT SUSPENDED
                {
                    $this->admin_model->clean_session();
                    $data['error_msg'] = $this->lang->line('err_acc_suspend');
                }
            }
            else // ACCOUNT SUSPENDED
            {
                $this->admin_model->clean_session();
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
        $this->load->view('admin/login',$data);
    }

    public function login_to_admin(){
            
        // Common variable
        $this->session->unset_userdata('direct_login');

        // for employer
        $this->session->unset_userdata('eadmin_role');
        $this->session->unset_userdata('emp_sender_mail');
        $this->session->unset_userdata('emp_sender_name');
        $this->session->unset_userdata('employer_type');
        $this->session->unset_userdata('employer_name');
        $this->session->unset_userdata('employer_email');
        $this->session->unset_userdata('employer_id');
        $this->session->unset_userdata('employer_logged_in');

        // for recruiter
        $this->session->unset_userdata('recruiter_name');
        $this->session->unset_userdata('recruiter_id');
        $this->session->unset_userdata('recruiter_logged_in');
        $this->session->unset_userdata('recruiter_type');
        $this->session->unset_userdata('common_image');
        
        $admin = $this->database_model->get_all_records('admins','*','','admin_id','ASC')->row();
        $this->session->set_userdata(array(
            'admin_type'=>$admin->admin_type,
            'login_type'=>$admin->admin_type,
            'admin_name'=>$admin->admin_name,
            'admin_id'=>$admin->admin_id,
            'admin_logged_in'=>TRUE ,
            'common_image'=>$admin->admin_photo
        ));
        header('location:'.base_url('admin'));
        die;
        
    }

    public function logout()//LOGOUT - DESTROY ALL SESSION DATA
    {
        $data = array('title'=>$this->lang->line('admin_login'),'target'=>site_url('manage'), 'error_msg'=>'');
        $this->admin_model->clean_session();
        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('admin/login',$data);
    }
}
