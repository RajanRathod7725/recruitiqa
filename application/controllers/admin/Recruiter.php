<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recruiter Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
	{

        parent::__construct();
        $this->load->model('admin/admin_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
        has_permission();
        $this->limit = $this->site_setting->site_admin_rowperpage;
        $this->thisModuleName ='Recruiter';
        $this->thisModuleBaseUrl = site_url('admin/recruiter').'/';
        
        $this->common_data = array(
            'title' => $this->thisModuleName,
            'link_add' => $this->thisModuleBaseUrl.'add/',
            'edit_link' => $this->thisModuleBaseUrl.'edit/',
            'info_link' => $this->thisModuleBaseUrl.'information/',
            'login_recruiter' => $this->thisModuleBaseUrl.'login_to_recruiter/',
            'tbl' => 'recruiter',
            'column' => 'recruiter_id',
            'main_module' => $this->thisModuleName,
            'module_base_url' => $this->thisModuleBaseUrl,
            'roles_key_value' => $this->config->item('roles_key_value'),
            'thumb_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/admins'),
            'big_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/admins','big'),
        );

	}

    function index($offset=0)
    {
        is_role_access('recruiter','index');
        if(!is_numeric($offset))
        {
            $offset = 0;
        }
        $uri_segment = 4;
        $data = $this->common_data;
        $data['title'] = 'Recruiter';
        $data['meta_key'] = 'Recruiter';
        $data['meta_desc'] = 'Recruiter';

        // For Search data
        $suffix_array = array();
        $like_str = '';

        $where_array=array('recruiter.status !='=>'3');
        $data['list_records']=array();
        $select_value= 'recruiter.*';
        $query_return = $this->database_model->get_all_records('recruiter',$select_value,$where_array,'recruiter.recruiter_id','DESC',$this->limit,$offset,'',$like_str,'','','','Yes');
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
        $this->load->view('admin/recruiter', $data);
    }

    function add()
    {
        is_role_access('recruiter','add');
        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'insert';
        $data['sub_module']=  'Add';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['method']=  'Add';
        
        /*******************************
        ||  Common data for all page ||
        *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('admin/recruiter_edit', $data);
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
            $this->ip_date = $this->common_model->get_date_ip();
            $this->_salt = $this->common_model->create_pwd_salt();
            $this->_password =  hash('sha256', $this->_salt . ( hash('sha256',$this->input->post('recruiter_password'))));
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
                    $this->form_data->recruiter_email= $PostArray['recruiter_email'];
                    $this->form_data->recruiter_linkedin= $PostArray['recruiter_linkedin'];
                    $this->load->view('admin/recruiter_edit', $data);
                    return;
                }
                else
                {
                    $file_name = $this->upload->file_name;
                    $this->create_thumb($file_name,60,60,'thumb');
                    $this->create_thumb($file_name,150,150,'size150');
                }
            }

            /*'recruiter_password' => $this->_password,
                'recruiter_salt' => $this->_salt,
                */
            $value_array = array(
                'recruiter_name ' => $PostArray['recruiter_name'],
                'recruiter_email' => $PostArray['recruiter_email'],
                'recruiter_linkedin' => $PostArray['recruiter_linkedin'],
                'recruiter_photo' => $file_name!=''? $file_name :'',
                'status' => '0',
                'created_at'=>$this->ip_date->cur_date,
                'updated_at'=>$this->ip_date->cur_date,
                'created_ip'=>$this->ip_date->ip,
            );
            $id = $this->database_model->save('recruiter',$value_array);

            $veri_token = $this->common_model->generateToken(45);
            $value_array3 = array(
                'recruiter_id' => $id,
                'recruiter_verification_token' => $veri_token,
                'created_at'=>$this->ip_date->cur_date,
            );
            $last_id = $this->database_model->save('recruiter_verification',$value_array3);
            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('recruiter'),
                'activity' => '1',
                'modified_by'=> $this->session->userdata('recruiter_id'),
            );

            //select query for mail
            $mail_temp = $this->database_model->get_all_records('mail_templates','*',array('status !='=>'3','mail_slug'=>'user_account_verification'),'mail_template_id DESC',1,0)->row();
            // parameter for mail template and function to send
            $to_email = $this->input->post('recruiter_email');
            $from_email = $mail_temp->mail_sender;
            $from_text = $mail_temp->mail_from_text;
            $sub_mail =str_replace('[SITE_NAME]', $this->site_setting->site_name,$mail_temp->mail_subject);

            $msg = str_replace('[TOKEN]', $veri_token,$mail_temp->mail_content);
            $msg = str_replace('[USER_NAME]', $PostArray['recruiter_name'],$msg);
            $msg = str_replace('[SITE_LINK]', $this->site_setting->site_link, $msg);
            $msg = str_replace('[LOGO_LINK]', site_url('themes/manage/images/logo_white.png'), $msg);
            $msg = str_replace('[SITE_NAME]', $this->site_setting->site_name, $msg);
            $msg = str_replace('[SITE_COPYRIGHT]', $this->site_setting->site_copyright, $msg);
            $msg = str_replace('[CONTACT_MAIL]', $this->site_setting->contact_email, $msg);
            $msg = str_replace('[LINK]', site_url('recruiter/verify_recruiter/' . $veri_token), $msg);

            $result = $this->common_model->send_mail($to_email,$from_email,$from_text,$sub_mail,$msg);

            $this->database_model->insert_modified($value_array,$this->session->userdata('admin_id'));
            $this->session->set_flashdata('notification',$this->lang->line('recruiter_succ_added'));
            redirect($this->thisModuleBaseUrl);
            die();
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->recruiter_name = $PostArray['recruiter_name'];
            $this->form_data->recruiter_email= $PostArray['recruiter_email'];
            $this->form_data->recruiter_linkedin= $PostArray['recruiter_linkedin'];
            $this->form_data->recruiter_password= $PostArray['recruiter_password'];

            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('admin/recruiter_edit', $data);
        }
    }

    function edit($id='')
    {
        is_role_access('recruiter','edit');
        if(!is_numeric($id))
            $this->add();
        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'update/'.$id;
        $data['sub_module']=  'Edit';
        $data['method']=  'Edit';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
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
            $this->load->view('admin/recruiter_edit', $data);
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
                    $this->load->view('admin/recruiter_edit', $data);
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
            /*if($PostArray['recruiter_password']!=''){
                $this->_salt = $this->common_model->create_pwd_salt();
                $this->_password =  hash('sha256', $this->_salt . ( hash('sha256',$this->input->post('password')) ) );
                $update_array['recruiter_password'] = $this->_password;
                $update_array['recruiter_salt'] = $this->_salt;
            }*/

            $this->database_model->update('recruiter',$update_array,array('recruiter_id'=>$id));
            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('recruiter'),
                'activity' => '2',
                'modified_by'=> $this->session->userdata('adminid'),
            );
            $this->database_model->insert_modified($value_array,$this->session->userdata('admin_id'));
            //REDIRECT
            $this->session->set_flashdata('notification',$this->lang->line('recruiter_succ_modified'));
            redirect($this->thisModuleBaseUrl);
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
            $this->load->view('admin/recruiter_edit', $data);

        }
    }

    function login_to_recruiter($recruiter_id)
    {   

        // is_role_access('employer','login');

        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'insert';
        $data['sub_module']=  'Login';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['method']=  'Add';

        $recruiter = $this->database_model->get_all_records('recruiter','*',array('recruiter_id'=>$recruiter_id),'','')->row();

        $this->session->unset_userdata('admin_type');
        $this->session->unset_userdata('login_type');
        $this->session->unset_userdata('admin_name');
        $this->session->unset_userdata('admin_id');
        $this->session->unset_userdata('admin_logged_in');
        $this->session->unset_userdata('common_image');

        $this->admin_model->clean_session();
        $this->session->set_userdata(array(
            'recruiter_name' => $recruiter->recruiter_name,
            'recruiter_id' => $recruiter->recruiter_id,
            'recruiter_logged_in' => TRUE,
            'login_type' => '1',
            'recruiter_type' => '2',
            'direct_login' => TRUE,
            'common_image' => $recruiter->recruiter_photo
        ));
        
        header('location:'.base_url('recruiter/dashboard'));
        die;

    }

    function _set_rules($id='')
    {   
        $PostArray = $this->input->post();

        $this->form_validation->set_rules('recruiter_name', 'Name', 'trim|required|min_length[3]|callback_alpha_dash_space');
        if($PostArray['method'] == "Add"){
            $this->form_validation->set_rules('recruiter_email', 'Email', 'trim|required|valid_email|callback_check_exists['.$id.']');
            //$this->form_validation->set_rules('recruiter_password', 'Password', 'trim|required');
        }
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