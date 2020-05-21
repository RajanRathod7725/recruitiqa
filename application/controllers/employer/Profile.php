<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('database_model');
        $this->load->model('employer/employer_model');
        $this->load->model('common_model');
        has_permission_employer();
        $this->thisModuleName ='Edit Profile';
        $this->thisModuleBaseUrl = site_url('employer/profile').'/';
        $this->common_data = array(
            'module_base_url' => $this->thisModuleBaseUrl,
            'tbl' => 'employer',
            'column' => 'employer_id',
            'title' => $this->thisModuleName,
            'edit_link' => $this->thisModuleBaseUrl.'edit/',
            'main_module' => $this->thisModuleName,
            'roles_key_value' => $this->config->item('roles_key_value'),
            'thumb_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/admins'),
            'big_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/admins','big'),
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
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['Edit_id']=  $id;

        $select_val = 'employer.*';
        $result = $this->database_model->get_all_records('employer',$select_val,array('employer.status !='=>'3','employer.employer_id'=>$id),'employer.employer_id DESC','1')->row();

        if(!empty($result))
        {
            @$this->form_data->id = $result->employer_id;
            $this->form_data->employer_name = $result->employer_name;
            $this->form_data->employer_email= $result->employer_email;
            $this->form_data->email_type = $result->email_type;
            $this->form_data->outreach_email = $result->outreach_email;
            $this->form_data->email_reject_reason = $result->email_reject_reason;
            $this->form_data->employer_linkedin= $result->employer_linkedin;
            $this->form_data->employer_photo= $result->employer_photo;
            $this->form_data->company_name= $result->company_name;
            $this->form_data->company_website= $result->company_website;
            $this->form_data->employer_country= $result->country_id;
            $this->form_data->employer_position= $result->employer_position;
            $this->form_data->employer_about= $result->employer_about;
            /*******************************
            ||  Common data for all page ||
             *******************************/

            $data['personalized_mail']=$this->database_model->get_all_records('personal_email','*',array('employer_id'=>$this->session->userdata('employer_id')),'personal_email_id','ASC',1,'')->row();
            @$this->form_data->email_username= $data['personalized_mail']->email_username;
            @$this->form_data->email_note= $data['personalized_mail']->note;

            $data['countries'] = $this->database_model->get_all_records('countries','*',array(),'country_name ASC','')->result();

            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('employer/profile_edit', $data);
        }
        else
        {
            $this->add();
        }
    }
    function update($id)
    {
        $PostArray = $this->input->post();
        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'update/'.$id;
        $data['sub_module']=  '';
        $data['method']=  'Edit';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['Edit_id']=  $id;

        $this->_set_rules($id);
        $csrf_check = $this->common_model->csrfguard_validate_token($PostArray['csrf_name'],$PostArray['csrf_token']);
        $success_message=$this->lang->line('profile_succ_modified');
        $this->ip_date = $this->common_model->get_date_ip();

        if($this->form_validation->run() === TRUE && $csrf_check==true)
        {
            $this->ip_date = $this->common_model->get_date_ip();
            if($_FILES['employer_photo']['name']!='')
            {
                $config['upload_path'] = './uploads/employer/big';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '102400';
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
                    $this->load->view('employer/employer_edit', $data);
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
                'employer_name ' => $PostArray['employer_name'],
                'employer_linkedin ' => $PostArray['employer_linkedin'],
                'company_name ' => $PostArray['company_name'],
                'company_website ' => $PostArray['company_website'],
                'country_id ' => $PostArray['employer_country'],
                'employer_position ' => $PostArray['employer_position'],
                'employer_about ' => $PostArray['employer_about'],
                'updated_at'=>$this->ip_date->cur_date
            );

            if(@$file_name!=''){
                $update_array['employer_photo'] = $file_name;
            }
            if($PostArray['employer_password']!=''){
                $this->_salt = $this->common_model->create_pwd_salt();
                $this->_password =  hash('sha256', $this->_salt . ( hash('sha256',$this->input->post('password')) ) );
                $update_array['employer_password'] = $this->_password;
                $update_array['employer_salt'] = $this->_salt;
            }

            $this->database_model->update('employer',$update_array,array('employer_id'=>$id));

            //personalized mail
            $result=$this->database_model->get_all_records('personal_email','*',array('employer_id'=>$this->session->userdata('employer_id')),'personal_email_id','ASC',1,'')->row();

            if(empty($result) && $PostArray['email_type']==2 && $PostArray['email_username']!=''){
                $value_array2 = array(
                    'employer_id'=>$this->session->userdata('employer_id'),
                    'email_username'=>$PostArray['email_username'],
                    'note'=>$PostArray['email_note']!=''?$PostArray['email_note']:'',
                    'email_status'=>'0',
                    'status'=>'1',
                    'created_at'=>$this->ip_date->cur_date,
                    'updated_at'=>$this->ip_date->cur_date,
                    'created_ip'=>$this->ip_date->ip,
                );
                $this->database_model->save('personal_email',$value_array2);
                $success_message = $this->lang->line('profile_succ_modified').'<br>'.'Your request for new personalized mail has been submitted successfully, You will get your mail activated within 24 hours. Stay Tune. Thanks';
                $this->database_model->update('employer',array('email_reject_reason'=>''),array('employer_id'=>$this->session->userdata('employer_id')));
            }
            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('employer'),
                'activity' => '2',
                'modified_by'=> $this->session->userdata('employer_id'),
            );

            $this->database_model->insert_modified($value_array,$this->session->userdata('employer_id'));
            //REDIRECT
            $this->session->set_flashdata('notification',$success_message);
            redirect($this->thisModuleBaseUrl.'edit/'.$id);
            die();
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->employer_name = $PostArray['employer_name'];
            $this->form_data->employer_email= $PostArray['employer_email'];
            $this->form_data->employer_linkedin= $PostArray['employer_linkedin'];
            @$this->form_data->personal_email= @$PostArray['personal_email']!=''?@$PostArray['personal_email']:'';
            $this->form_data->email_note= $PostArray['email_note'];
            $this->form_data->email_type= $PostArray['email_type'];
            $this->form_data->employer_country= $PostArray['employer_country'];
            $this->form_data->employer_position= $PostArray['employer_position'];
            $this->form_data->employer_about= $PostArray['employer_about'];

            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['countries'] = $this->database_model->get_all_records('countries','*',array(),'country_name ASC','')->result();

            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('employer/profile_edit', $data);

        }
    }

    function _set_rules($id='')
    {
        $PostArray = $this->input->post();

        $this->form_validation->set_rules('company_name', 'Company Name', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('company_website', 'Company Website', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('employer_name', 'Your Name', 'trim|required|min_length[3]|callback_alpha_dash_space');
        if($PostArray['email_type']==2){
            $this->form_validation->set_rules('email_username', 'Personalized User Name', 'trim|required|min_length[3]|callback_check_exists['.$id.']');
        }
        $this->form_validation->set_rules('employer_country', 'Country', 'trim|required');
        $this->form_validation->set_rules('employer_position', 'Position in company', 'trim|required');
    }
    function check_exists($field_value, $id='')
    {
        $outreach_email =$this->input->post('email_username').$this->site_setting->employer_mail_suffix;
        $data['result'] = $this->database_model->check_record_exist('employer','employer_id',array('outreach_email'=>$outreach_email,'status !='=>'3','employer_id !='=>$id));

        if ($data['result']['employer_id']>0)
        {
            $this->form_validation->set_message('check_exists',$this->lang->line('personal_email_already_exist'));
            return FALSE;
        }else{
            $data['result2'] = $this->database_model->check_record_exist('personal_email','personal_email_id',array('email_username'=>$this->input->post('email_username'),'status !='=>'3','email_status'=>'0'));
            if($data['result2']['personal_email_id']>0){
                $this->form_validation->set_message('check_exists',$this->lang->line('personal_email_already_exist'));
                return FALSE;
            }else{
                return TRUE;
            }
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
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */