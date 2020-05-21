<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('database_model');
        $this->load->model('recruiter/recruiter_model');
        $this->load->model('common_model');
        has_permission_recruiter();
        $this->thisModuleName ='Edit Profile';
        $this->thisModuleBaseUrl = site_url('recruiter/profile').'/';
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
    function edit($id='')
    {
        if(!is_numeric($id))
            $this->add();

        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'update/'.$id;
        $data['sub_module']=  '';
        $data['method']=  'Edit';
        $data['title']=  $this->thisModuleName ;
        $data['Edit_id']=  $id;

        $select_val = 'recruiter.*';
        $result = $this->database_model->get_all_records('recruiter',$select_val,array('recruiter.status !='=>'3','recruiter.recruiter_id'=>$id),'recruiter.recruiter_id DESC','1')->row();

        if(!empty($result))
        {
            @$this->form_data->id = $result->recruiter_id;
            $this->form_data->recruiter_name = $result->recruiter_name;
            $this->form_data->recruiter_email= $result->recruiter_email;
            $this->form_data->recruiter_linkedin= $result->recruiter_linkedin;
            $this->form_data->recruiter_photo= $result->recruiter_photo;
            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('recruiter/profile_edit', $data);
        }
        else
        {
            $this->add();
        }
    }
    function update($id)
    {
        $PostArray = $this->input->post();
        // print_r($PostArray);
        // exit;
        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'update/'.$id;
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
            if($_FILES['recruiter_photo']['name']!='')
            {
                $config['upload_path'] = './uploads/recruiter/big';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '10240';
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('recruiter_photo'))
                {
                    $error = array('error' => $this->upload->display_errors());
                    $data['upload_error'] = $error;

                    $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
                    $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);

                    $this->form_data->recruiter_name = $PostArray['recruiter_name'];
                    $this->load->view('recruiter/recruiter_edit', $data);
                    return;
                }
                else
                {
                    $file_name = $this->upload->file_name;
                    $this->create_thumb($file_name,60,60,'thumb');
                    $this->create_thumb($file_name,150,150,'size150');
                }
            }

            $update_array = array(
                'recruiter_name ' => $PostArray['recruiter_name'],
                'recruiter_linkedin ' => $PostArray['recruiter_linkedin'],
                'updated_at'=>$this->ip_date->cur_date
            );

            if($file_name!=''){
                $update_array['recruiter_photo'] = $file_name;
            }
            if($PostArray['recruiter_password']!=''){
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
            $this->session->set_flashdata('notification',$this->lang->line('profile_succ_modified'));
            redirect($this->thisModuleBaseUrl.'edit/'.$id);
            die();
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->recruiter_name = $PostArray['recruiter_name'];
            $this->form_data->recruiter_email= $PostArray['recruiter_email'];
            $this->form_data->recruiter_linkedin= $PostArray['recruiter_linkedin'];

            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('recruiter/profile_edit', $data);

        }
    }

    function _set_rules($id='')
    {
        $PostArray = $this->input->post();

        $this->form_validation->set_rules('recruiter_name', 'Name', 'trim|required|min_length[3]|callback_alpha_dash_space');
    }
    function check_exists($field_value, $id='')
    {

        $data['result'] = $this->database_model->check_record_exist('recruiter','recruiter_id',array('recruiter_email'=>$this->input->post('recruiter_email'),'status !='=>'3'));

        if ($data['result']['recruiter_id']>0)
        {
            $this->form_validation->set_message('check_exists',$this->lang->line('email_already_exist'));
            return FALSE;
        }else{
            //print_r(2);die();
            return TRUE;
        }
        return TRUE;
    }

    function alpha_dash_space($fullname){
        if (! preg_match('/^[a-zA-Z\s]+$/', $fullname)) {
            $this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha characters & White spaces');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    function create_thumb($file,$width=60,$height=60,$folder='thumb')
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] = './uploads/recruiter/big/'.$file;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = FALSE;
        $config['width'] = $width;
        $config['height'] = $height;
        $config['new_image'] = './uploads/recruiter/'.$folder.'/'.$file;
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
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */