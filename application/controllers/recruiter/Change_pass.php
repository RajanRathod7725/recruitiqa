<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Change_pass Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
	{
        parent::__construct();
        $this->load->model('recruiter/recruiter_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
        has_permission_recruiter();
        $this->thisModuleName ='Change Password';
        $this->thisModuleBaseUrl = site_url('recruiter/change_pass').'/';
        $this->common_data = array(
            'module_base_url' => $this->thisModuleBaseUrl,
            'tbl' => 'recruiter',
            'column' => 'recruiter_id',
            'title' => $this->thisModuleName,
            'edit_link' => $this->thisModuleBaseUrl.'edit/',
            'main_module' => $this->thisModuleName,
            'roles_key_value' => $this->config->item('roles_key_value'),
            'thumb_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/recruiters'),
            'big_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/recruiters','big'),
        );

	}

    function index()
    {
        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'update/'.$this->session->userdata('recruiter_id');
        $data['sub_module']=  '';
        $data['method']=  'Edit';
        $data['title']= $this->thisModuleName ;

        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('recruiter/change_pass', $data);

    }
    function update($id)
    {
        $PostArray = $this->input->post();

        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'update/'.$this->session->userdata('recruiter_id');;
        $data['sub_module']=  '';
        $data['method']=  'Edit';
        $data['title']= $this->thisModuleName ;
        $data['Edit_id']=  $id;

        $this->_set_rules($id);
        $csrf_check = $this->common_model->csrfguard_validate_token($PostArray['csrf_name'],$PostArray['csrf_token']);
        $this->ip_date = $this->common_model->get_date_ip();
        if($this->form_validation->run() === TRUE && $csrf_check==true)
        {

            $this->ip_date = $this->common_model->get_date_ip();
            $update_array = array(
                'updated_at'=>$this->ip_date->cur_date
            );
            if($PostArray['password']!=''){
                $this->_salt = $this->common_model->create_pwd_salt();
                $this->_password =  hash('sha256', $this->_salt . ( hash('sha256',$this->input->post('password')) ) );
                $update_array['recruiter_password'] = $this->_password;
                $update_array['recruiter_salt'] = $this->_salt;
            }

            $this->database_model->update('recruiter',$update_array,array('recruiter_id'=>$id));
            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('recruiter'),
                'activity' => '2',
                'modified_by'=> $this->session->userdata('recruiter_id'),
            );

            $this->database_model->insert_modified($value_array,$this->session->userdata('recruiter_id'));

            //REDIRECT
            $this->session->set_flashdata('notification','Password has been successfully modified.');
            redirect($this->thisModuleBaseUrl.'index/'.$id);
            die();
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');
            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('recruiter/change_pass', $data);
        }
    }

    function _set_rules($id='')
    {
        $PostArray = $this->input->post();
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]');
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */