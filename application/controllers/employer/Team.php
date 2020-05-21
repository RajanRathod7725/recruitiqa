<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Team Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
	{
        parent::__construct();
        $this->load->model('employer/employer_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
        has_permission_employer();
        $this->limit = $this->site_setting->site_admin_rowperpage;
        $this->thisModuleName ='Team';
        $this->thisModuleBaseUrl = site_url('employer/team').'/';
        
        $this->common_data = array(
            'title' => $this->thisModuleName,
            'link_add' => $this->thisModuleBaseUrl.'add/',
            'edit_link' => $this->thisModuleBaseUrl.'edit/',
            'info_link' => $this->thisModuleBaseUrl.'information/',
            'tbl' => 'employer',
            'column' => 'employer_id',
            'main_module' => $this->thisModuleName,
            'module_base_url' => $this->thisModuleBaseUrl,
            'roles_key_value' => $this->config->item('roles_key_value'),
            'thumb_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/employers'),
            'big_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/employers','big'),
        );

	}

    function index($offset=0)
    {

        if(!is_numeric($offset))
        {
            $offset = 0;
        }
        $uri_segment = 4;
        $data = $this->common_data;
        $data['title'] = 'Team';
        $data['meta_key'] = 'Team';
        $data['meta_desc'] = 'Team';

        // For Search data
        $suffix_array = array();
        $like_str = '';

        $where_array=array('employer.status !='=>'3','employer.created_by'=>$this->session->userdata('employer_id'));
        $data['list_records']=array();
        /*$select_value= 'employer.*';
        $query_return = $this->database_model->get_all_records('employer',$select_value,$where_array,'employer.employer_name','ASC',$this->limit,$offset,'',$like_str,'','','','Yes');*/
        $select_value= 'employer.*,role.title';
        $joins=array(
            array(
                'table'=>'role_permission',
                'condition'=>'employer.employer_id=role_permission.user_id',
                'jointype'=>'left'
            ),
            array(
                'table'=>'role',
                'condition'=>'role_permission.role_id=role.role_id',
                'jointype'=>'left'
            ),
        );

        $query_return = $this->database_model->get_joins('employer',$select_value,$joins,$where_array,'employer.employer_name','ASC',$this->limit,$offset,'',$like_str,'','','','Yes');
        $data['list_records'] = $query_return['results'];
        $data['total_records'] = $query_return['total_records'];
        $data['pagination'] = $this->common_model->get_pagination($suffix_array,$this->thisModuleBaseUrl.'index',$data['total_records'],$this->limit,$uri_segment);
        $data['j'] = 0 + $offset;
        $data['offset'] = $offset;
        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/team', $data);
    }

    function add()
    {
        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'insert';
        $data['sub_module']=  'Add';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['method']=  'Add';
        
        /*******************************
        ||  Common data for all page ||
        *******************************/
        $select_value= 'role.*';
        $data['roles'] = $this->database_model->get_all_records('role',$select_value,array('status !='=>'3','role_for'=>'2'),'role.role_id','ASC','')->result();
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/team_edit', $data);
    }

    function insert()
    {
        $PostArray = $this->input->post();

        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'insert';
        $data['sub_module']=  'Add';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['method']=  'Add';

        $this->_set_rules();
        $csrf_check = $this->common_model->csrfguard_validate_token($PostArray['csrf_name'],$PostArray['csrf_token']);

        if($this->form_validation->run() === TRUE && $csrf_check==true)
        {
            //check candidate exist or not
            $data['result'] = $this->database_model->check_record_exist('employer','employer_id',array('employer_email'=>$this->input->post('employer_email'),'status !='=>'3'));

            if ($data['result']['employer_id']>0)
            {
                $data['sorry_error']="This team member has already created an account with Recruitiqa. Feel free to add another member.";
                @$this->form_data->employer_name = $PostArray['employer_name'];
                $this->form_data->employer_email= $PostArray['employer_email'];
                $this->form_data->employer_linkedin= $PostArray['employer_phone'];
                $this->form_data->employer_password= $PostArray['employer_password'];
                $this->form_data->role_id= $PostArray['role_id'];

                /*******************************
                ||  Common data for all page ||
                 *******************************/
                $select_value= 'role.*';
                $data['roles'] = $this->database_model->get_all_records('role',$select_value,array('status !='=>'3','role_for'=>'2'),'role.role_id','ASC','')->result();

                $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
                $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
                $this->load->view('employer/team_edit', $data);
            }else{
                $this->ip_date = $this->common_model->get_date_ip();
                $this->_salt = $this->common_model->create_pwd_salt();
                $this->_password =  hash('sha256', $this->_salt . ( hash('sha256',$this->input->post('employer_password'))));
                if($_FILES['employer_photo']['name']!='')
                {
                    $config['upload_path'] = './uploads/employer/big';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['max_size'] = '10240';
                    $config['encrypt_name'] = TRUE;
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('employer_photo'))
                    {
                        $error = array('error' => $this->upload->display_errors());
                        $data['upload_error'] = $error;

                        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
                        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);

                        $select_value= 'role.*';
                        $data['roles'] = $this->database_model->get_all_records('role',$select_value,array('status !='=>'3','role_for'=>'2'),'role.role_id','ASC','')->result();
                        $this->form_data->employer_name = $PostArray['employer_name'];
                        $this->form_data->employer_email= $PostArray['employer_email'];
                        $this->load->view('employer/team_edit', $data);
                        return;
                    }
                    else
                    {
                        $file_name = $this->upload->file_name;
                        $this->create_thumb($file_name,60,60,'thumb');
                        $this->create_thumb($file_name,150,150,'size150');
                    }
                }
                $employer =$this->database_model->get_all_records('employer','*',array('employer_id'=>$this->session->userdata('employer_id')),'employer_id ASC', 1)->row();

                $value_array = array(
                    'employer_name ' => $PostArray['employer_name'],
                    'employer_email' => $PostArray['employer_email'],
                    'employer_phone' => $PostArray['employer_phone'],
                    'outreach_email' => $PostArray['employer_email'],
                    'company_name' => $employer->company_name,
                    'company_website' => $employer->company_website,
                    'email_verification' => '0',
                    'employer_password' => $this->_password,
                    'employer_salt' => $this->_salt,
                    'employer_photo' => $file_name!=''? $file_name :'',
                    'employer_type' => '5',
                    'status' => '1',
                    'created_at'=>$this->ip_date->cur_date,
                    'updated_at'=>$this->ip_date->cur_date,
                    'created_ip'=>$this->ip_date->ip,
                    'created_by'=>$this->session->userdata('employer_id'),
                );
                $id = $this->database_model->save('employer',$value_array);

                $value_array2 = array(
                    'user_id ' => $id,
                    'role_id' => $PostArray['role_id'],
                    'user_type' => 2,
                    'status' => '1',
                    'created_at'=>$this->ip_date->cur_date,
                    'updated_at'=>$this->ip_date->cur_date,
                    'created_ip'=>$this->ip_date->ip,
                );
                $this->database_model->save('role_permission',$value_array2);

                $value_array3 = array(
                    'super_employer_id' => $this->session->userdata('employer_id'),
                    'team_employer_id' => $id,
                    'status' => '1',
                    'created_at'=>$this->ip_date->cur_date,
                    'updated_at'=>$this->ip_date->cur_date,
                    'created_ip'=>$this->ip_date->ip,
                );
                $this->database_model->save('employer_conversation',$value_array3);

                //entry in verification
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
                $to_email = $PostArray['employer_email'];
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
                $msg = str_replace('[LINK]', site_url('employer/verify_employer/' . $veri_token), $msg);

                $result = $this->common_model->send_mail($to_email,$from_email,$from_text,$sub_mail,$msg);


                //Insert Modified log
                $value_array = array(
                    'table_id' => $id,
                    'table_name' => $this->db->dbprefix('employer'),
                    'activity' => '1',
                    'modified_by'=> $this->session->userdata('employer_id'),
                );
                $this->database_model->insert_modified($value_array,$this->session->userdata('employer_id'));

                $this->session->set_flashdata('great_notification',$this->lang->line('member_succ_added'));
                redirect($this->thisModuleBaseUrl);
                die();
            }
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->employer_name = $PostArray['employer_name'];
            $this->form_data->employer_email= $PostArray['employer_email'];
            $this->form_data->employer_linkedin= $PostArray['employer_phone'];
            $this->form_data->employer_password= $PostArray['employer_password'];
            $this->form_data->role_id= $PostArray['role_id'];

            /*******************************
            ||  Common data for all page ||
             *******************************/
            $select_value= 'role.*';
            $data['roles'] = $this->database_model->get_all_records('role',$select_value,array('status !='=>'3','role_for'=>'2'),'role.role_id','ASC','')->result();

            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('employer/team_edit', $data);
        }
    }

    function edit($id='')
    {
        if(!is_numeric($id))
            $this->add();
        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'update/'.$id;
        $data['sub_module']=  'Edit';
        $data['method']=  'Edit';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['Edit_id']=  $id;

        $select_val = 'employer.*';
        $result = $this->database_model->get_all_records('employer',$select_val,array('employer.status !='=>'3','employer.employer_id'=>$id,'employer.created_by'=>$this->session->userdata('employer_id')),'employer.employer_id DESC','1')->row();

        if(!empty($result))
        {
            @$this->form_data->id = $result->employer_id;
            $this->form_data->employer_name = $result->employer_name;
            $this->form_data->employer_email= $result->employer_email;
            $this->form_data->employer_phone= $result->employer_phone;
            $this->form_data->employer_photo= $result->employer_photo;
            $this->form_data->role_id = $this->database_model->get_all_records('role_permission','role_id',array('user_id'=>$result->employer_id),'role_permission_id ASC',1,'')->row()->role_id;
            /*******************************
            ||  Common data for all page ||
             *******************************/
            $select_value= 'role.*';
            $data['roles'] = $this->database_model->get_all_records('role',$select_value,array('status !='=>'3','role_for'=>'2'),'role.role_id','ASC','')->result();

            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('employer/team_edit', $data);
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
        $data['sub_module']=  'Edit';
        $data['method']=  'Edit';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['Edit_id']=  $id;

        $this->_set_rules($id);
        $csrf_check = $this->common_model->csrfguard_validate_token($PostArray['csrf_name'],$PostArray['csrf_token']);
        $this->ip_date = $this->common_model->get_date_ip();
        if($this->form_validation->run() === TRUE && $csrf_check==true)
        {
            //check candidate exist or not
            $data['result'] = $this->database_model->check_record_exist('employer','employer_id',array('employer_email'=>$this->input->post('employer_email'),'employer_id !='=>$id,'status !='=>'3'));

            if ($data['result']['employer_id']>0)
            {
                $data['sorry_error']="This team member has already created an account with Recruitiqa. Feel free to update another member.";
                @$this->form_data->employer_name = $PostArray['employer_name'];
                $this->form_data->employer_email= $PostArray['employer_email'];
                $this->form_data->employer_linkedin= $PostArray['employer_phone'];
                $this->form_data->employer_password= $PostArray['employer_password'];
                $this->form_data->role_id= $PostArray['role_id'];

                /*******************************
                ||  Common data for all page ||
                 *******************************/
                $select_value= 'role.*';
                $data['roles'] = $this->database_model->get_all_records('role',$select_value,array('status !='=>'3','role_for'=>'2'),'role.role_id','ASC','')->result();

                $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
                $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
                $this->load->view('employer/team_edit', $data);
            }else{
                $this->ip_date = $this->common_model->get_date_ip();
                if($_FILES['employer_photo']['name']!='')
                {
                    $config['upload_path'] = './uploads/employer/big';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['max_size'] = '10240';
                    $config['encrypt_name'] = TRUE;
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('employer_photo'))
                    {
                        $error = array('error' => $this->upload->display_errors());
                        $data['upload_error'] = $error;

                        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
                        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);

                        $select_value= 'role.*';
                        $data['roles'] = $this->database_model->get_all_records('role',$select_value,array('status !='=>'3','role_for'=>'2'),'role.role_id','ASC','')->result();
                        $this->form_data->employer_name = $PostArray['employer_name'];
                        $this->load->view('employer/team_edit', $data);
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
                    'employer_phone' => $PostArray['employer_phone'],
                    'updated_at'=>$this->ip_date->cur_date
                );

                if($file_name!=''){
                    $update_array['employer_photo'] = $file_name;
                }
                if($PostArray['employer_password']!=''){
                    $this->_salt = $this->common_model->create_pwd_salt();
                    $this->_password =  hash('sha256', $this->_salt . ( hash('sha256',$this->input->post('password')) ) );
                    $update_array['employer_password'] = $this->_password;
                    $update_array['employer_salt'] = $this->_salt;
                }

                $this->database_model->update('employer',$update_array,array('employer_id'=>$id));
                $this->database_model->update('role_permission',array('role_id'=>$PostArray['role_id']),array('user_id'=>$id));
                //Insert Modified log
                $value_array = array(
                    'table_id' => $id,
                    'table_name' => $this->db->dbprefix('employer'),
                    'activity' => '2',
                    'modified_by'=> $this->session->userdata('employerid'),
                );
                $this->database_model->insert_modified($value_array,$this->session->userdata('employer_id'));
                //REDIRECT
                $this->session->set_flashdata('notification',$this->lang->line('member_succ_modified'));
                redirect($this->thisModuleBaseUrl);
                die();
            }
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->employer_name = $PostArray['employer_name'];
            $this->form_data->employer_email= $PostArray['employer_email'];
            $this->form_data->employer_phone= $PostArray['employer_phone'];
            $this->form_data->role_id= $PostArray['role_id'];

            /*******************************
            ||  Common data for all page ||
             *******************************/
            $select_value= 'role.*';
            $data['roles'] = $this->database_model->get_all_records('role',$select_value,array('status !='=>'3','role_for'=>'2'),'role.role_id','ASC','')->result();
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('employer/team_edit', $data);

        }
    }

    function _set_rules($id='')
    {   
        $PostArray = $this->input->post();

        $this->form_validation->set_rules('employer_name', 'Name', 'trim|required|min_length[3]|callback_alpha_dash_space');
        if($PostArray['method'] == "Add"){
            $this->form_validation->set_rules('employer_email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('employer_password', 'Password', 'trim|required');
        }
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