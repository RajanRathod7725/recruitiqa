<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Team Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
	{
        parent::__construct();
        $this->load->model('admin/admin_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
        has_permission();
        $this->limit = $this->site_setting->site_admin_rowperpage;
        $this->thisModuleName ='Team';
        $this->thisModuleBaseUrl = site_url('admin/team').'/';
        
        $this->common_data = array(
            'title' => $this->thisModuleName,
            'link_add' => $this->thisModuleBaseUrl.'add/',
            'edit_link' => $this->thisModuleBaseUrl.'edit/',
            'info_link' => $this->thisModuleBaseUrl.'information/',
            'tbl' => 'admins',
            'column' => 'admin_id',
            'main_module' => $this->thisModuleName,
            'module_base_url' => $this->thisModuleBaseUrl,
            'roles_key_value' => $this->config->item('roles_key_value'),
            'thumb_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/admins'),
            'big_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/admins','big'),
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

        $where_array=array('admins.status !='=>'3','admins.created_by'=>$this->session->userdata('admin_id'));
        $data['list_records']=array();
        $select_value= 'admins.*,role.title';
        $joins=array(
            array(
                'table'=>'role_permission',
                'condition'=>'admins.admin_id=role_permission.user_id',
                'jointype'=>'left'
            ),
            array(
                'table'=>'role',
                'condition'=>'role_permission.role_id=role.role_id',
                'jointype'=>'left'
            ),
        );

        $query_return = $this->database_model->get_joins('admins',$select_value,$joins,$where_array,'admins.admin_name','ASC',$this->limit,$offset,'',$like_str,'','','','Yes');

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
        $this->load->view('admin/team', $data);
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
        $data['roles'] = $this->database_model->get_all_records('role',$select_value,array('status !='=>'3','role_for'=>'1'),'role.role_id','ASC','')->result();

        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('admin/team_edit', $data);
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
            $this->_password =  hash('sha256', $this->_salt . ( hash('sha256',$this->input->post('admin_password'))));
            if($_FILES['admin_photo']['name']!='')
            {
                $config['upload_path'] = './uploads/admin/big';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '10240';
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('admin_photo'))
                {   
                    $error = array('error' => $this->upload->display_errors());
                    $data['upload_error'] = $error;

                    $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
                    $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);

                    $this->form_data->admin_name = $PostArray['admin_name'];
                    $this->form_data->admin_email= $PostArray['admin_email'];
                    $this->form_data->role_id= $PostArray['role_id'];
                    $this->load->view('admin/team_edit', $data);
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
                'admin_name ' => $PostArray['admin_name'],
                'admin_email' => $PostArray['admin_email'],
                'admin_phone' => $PostArray['admin_phone'],
                'admin_password' => $this->_password,
                'admin_salt' => $this->_salt,
                'admin_photo' => $file_name!=''? $file_name :'',
                'admin_type' => '4',
                'status' => '1',
                'created_at'=>$this->ip_date->cur_date,
                'updated_at'=>$this->ip_date->cur_date,
                'created_ip'=>$this->ip_date->ip,
                'created_by'=>$this->session->userdata('admin_id'),
            );
            $id = $this->database_model->save('admins',$value_array);

            $value_array2 = array(
                'user_id ' => $id,
                'role_id' => $PostArray['role_id'],
                'user_type' => 1,
                'status' => '1',
                'created_at'=>$this->ip_date->cur_date,
                'updated_at'=>$this->ip_date->cur_date,
                'created_ip'=>$this->ip_date->ip,
            );
            $id = $this->database_model->save('role_permission',$value_array2);

            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('admins'),
                'activity' => '1',
                'modified_by'=> $this->session->userdata('admin_id'),
            );
            $this->database_model->insert_modified($value_array,$this->session->userdata('admin_id'));

            $this->session->set_flashdata('notification',$this->lang->line('member_succ_added'));
            redirect($this->thisModuleBaseUrl);
            die();
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->admin_name = $PostArray['admin_name'];
            $this->form_data->admin_email= $PostArray['admin_email'];
            $this->form_data->admin_phone= $PostArray['admin_phone'];
            $this->form_data->admin_password= $PostArray['admin_password'];
            $this->form_data->role_id= $PostArray['role_id'];

            $select_value= 'role.*';
            $data['roles'] = $this->database_model->get_all_records('role',$select_value,array('status !='=>'3','role_for'=>'1'),'role.role_id','ASC','')->result();

            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('admin/team_edit', $data);
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

        $select_val = 'admins.*';
        $result = $this->database_model->get_all_records('admins',$select_val,array('admins.status !='=>'3','admins.admin_id'=>$id,'admins.created_by'=>$this->session->userdata('admin_id')),'admins.admin_id DESC','1')->row();

        if(!empty($result))
        {
            @$this->form_data->id = $result->admin_id;
            $this->form_data->admin_name = $result->admin_name;
            $this->form_data->admin_email= $result->admin_email;
            $this->form_data->admin_phone= $result->admin_phone;
            $this->form_data->admin_photo= $result->admin_photo;
            $this->form_data->role_id = $this->database_model->get_all_records('role_permission','role_id',array('user_id'=>$result->admin_id),'role_permission_id ASC',1,'')->row()->role_id;
            /*******************************
            ||  Common data for all page ||
             *******************************/

            $select_value= 'role.*';
            $data['roles'] = $this->database_model->get_all_records('role',$select_value,array('status !='=>'3','role_for'=>'1'),'role.role_id','ASC','')->result();

            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('admin/team_edit', $data);
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
            if($_FILES['admin_photo']['name']!='')
            {
                $config['upload_path'] = './uploads/admin/big';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '10240';
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('admin_photo'))
                {   
                    $error = array('error' => $this->upload->display_errors());
                    $data['upload_error'] = $error;

                    $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
                    $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);

                    $this->form_data->admin_name = $PostArray['admin_name'];
                    $this->load->view('admin/team_edit', $data);
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
                'admin_name ' => $PostArray['admin_name'],
                'admin_phone' => $PostArray['admin_phone'],
                'updated_at'=>$this->ip_date->cur_date
            );
            
            if($file_name!=''){
                $update_array['admin_photo'] = $file_name;
            }
            if($PostArray['admin_password']!=''){
                $this->_salt = $this->common_model->create_pwd_salt();
                $this->_password =  hash('sha256', $this->_salt . ( hash('sha256',$this->input->post('password')) ) );
                $update_array['admin_password'] = $this->_password;
                $update_array['admin_salt'] = $this->_salt;
            }

            $this->database_model->update('admins',$update_array,array('admin_id'=>$id));
            $this->database_model->update('role_permission',array('role_id'=>$PostArray['role_id']),array('user_id'=>$id));
            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('admins'),
                'activity' => '2',
                'modified_by'=> $this->session->userdata('admin_id'),
            );
            $this->database_model->insert_modified($value_array,$this->session->userdata('admin_id'));
            //REDIRECT
            $this->session->set_flashdata('notification',$this->lang->line('member_succ_modified'));
            redirect($this->thisModuleBaseUrl);
            die();
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->admin_name = $PostArray['admin_name'];
            $this->form_data->admin_email= $PostArray['admin_email'];
            $this->form_data->admin_phone= $PostArray['admin_phone'];
            $this->form_data->role_id= $PostArray['role_id'];

            $select_value= 'role.*';
            $data['roles'] = $this->database_model->get_all_records('role',$select_value,array('status !='=>'3','role_for'=>'1'),'role.role_id','ASC','')->result();

            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('admin/team_edit', $data);

        }
    }

    function _set_rules($id='')
    {   
        $PostArray = $this->input->post();

        $this->form_validation->set_rules('admin_name', 'Name', 'trim|required|min_length[3]|callback_alpha_dash_space');
        if($PostArray['method'] == "Add"){
            $this->form_validation->set_rules('admin_email', 'Email', 'trim|required|valid_email|callback_check_exists['.$id.']');
            $this->form_validation->set_rules('admin_password', 'Password', 'trim|required');
        }
    }
    function check_exists($field_value, $id='')
    {
        
        $data['result'] = $this->database_model->check_record_exist('admins','admin_id',array('admin_email'=>$this->input->post('admin_email'),'status !='=>'3'));
        
        if ($data['result']['admin_id']>0)
        {
            $this->form_validation->set_message('check_exists',$this->lang->line('member_already_exist'));
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
        $config['source_image'] = './uploads/admin/big/'.$file;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = FALSE;
        $config['width'] = $width;
        $config['height'] = $height;
        $config['new_image'] = './uploads/admin/'.$folder.'/'.$file;
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