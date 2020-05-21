<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
	{

        parent::__construct();
        $this->load->model('admin/admin_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
        has_permission();
        $this->limit = $this->site_setting->site_admin_rowperpage;
        $this->thisModuleName ='Role';
        $this->thisModuleBaseUrl = site_url('admin/role').'/';
        
        $this->common_data = array(
            'title' => $this->thisModuleName,
            'link_add' => $this->thisModuleBaseUrl.'add/',
            'edit_link' => $this->thisModuleBaseUrl.'edit/',
            'permission_link' => $this->thisModuleBaseUrl.'permission_add/',
            'tbl' => 'role',
            'column' => 'role_id',
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
        $data['title'] = 'Role';
        $data['meta_key'] = 'Role';
        $data['meta_desc'] = 'Role';

        // For Search data
        $suffix_array = array();
        $like_str = '';

        $where_array=array('role.status !='=>'3','role.role_for'=>'1');
        $data['list_records']=array();
        $select_value= 'role.*';
        $query_return = $this->database_model->get_all_records('role',$select_value,$where_array,'role.title','ASC',$this->limit,$offset,'',$like_str,'','','','Yes');
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
        $this->load->view('admin/role', $data);
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
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('admin/role_edit', $data);
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
            $value_array = array(
                'title' => $PostArray['title'],
                'role_for' => '1',
                'status' => 1,
                'created_at'=>$this->ip_date->cur_date,
                'updated_at'=>$this->ip_date->cur_date,
                'created_ip'=>$this->ip_date->ip,
            );
            $id = $this->database_model->save('role',$value_array);

            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('role'),
                'activity' => '1',
                'modified_by'=> $this->session->userdata('admin_id'),
            );

            $this->database_model->insert_modified($value_array,$this->session->userdata('admin_id'));
            $this->session->set_flashdata('notification',$this->lang->line('role_succ_added'));
            redirect($this->thisModuleBaseUrl);
            die();
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->title = $PostArray['title'];
            @$this->form_data->role_for = $PostArray['role_for'];

            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('admin/role_edit', $data);
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

        $select_val = 'role.*';
        $result = $this->database_model->get_all_records('role',$select_val,array('role.status !='=>'3','role.role_id'=>$id),'role.role_id DESC','1')->row();

        if(!empty($result))
        {
            @$this->form_data->id = $result->role_id;
            $this->form_data->title = $result->title;
            $this->form_data->role_for = $result->role_for;
            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('admin/role_edit', $data);
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
            $update_array = array(
                'title ' => $PostArray['title'],
                'updated_at'=>$this->ip_date->cur_date
            );
            $this->database_model->update('role',$update_array,array('role_id'=>$id));
            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('role'),
                'activity' => '2',
                'modified_by'=> $this->session->userdata('admin_id'),
            );
            $this->database_model->insert_modified($value_array,$this->session->userdata('admin_id'));
            //REDIRECT
            $this->session->set_flashdata('notification',$this->lang->line('role_succ_modified'));
            redirect($this->thisModuleBaseUrl);
            die();
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->title = $PostArray['title'];

            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('admin/role_edit', $data);

        }
    }

    function permission_add($id='')
    {
        if(!is_numeric($id))
            $this->add();
        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'update/'.$id;
        $data['sub_module']=  'Permisssion';
        $data['method']=  'Permisssion';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['Edit_id']=  $id;

        $select_val = 'role.*';
        $result = $this->database_model->get_all_records('role',$select_val,array('role.status !='=>'3','role.role_id'=>$id),'role.role_id DESC','1')->row();

        if(!empty($result))
        {
            @$this->form_data->id = $result->role_id;
            $this->form_data->title = $result->title;
            $this->form_data->role_for = $result->role_for;
            /*******************************
            ||  Common data for all page ||
             *******************************/

            $data['permission'] = $this->database_model->custom_query("SELECT GROUP_CONCAT(tbl_permission.ck_id) as ids FROM `tbl_permission` WHERE `tbl_permission`.`role_id` = ".$result->role_id)->row()->ids;

            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('admin/permission_edit', $data);
        }
        else
        {
            $this->add();
        }
    }

    function _set_rules($id='')
    {   
        $PostArray = $this->input->post();
        if($PostArray['method']=='Add'){
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]|callback_check_exists['.$id.']');
        }else{
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]|callback_check_exists_edit['.$id.']');
        }
    }
    function check_exists($field_value, $id='')
    {
        $data['result'] = $this->database_model->check_record_exist('role','role_id',array('title'=>$this->input->post('title'),'status !='=>'3'));
        
        if ($data['result']['role_id']>0)
        {
            $this->form_validation->set_message('check_exists',$this->lang->line('role_already_exist'));
                return FALSE;
        }else{
            return TRUE;
        }
        return TRUE;
    }
    function check_exists_edit($field_value, $id='')
    {
        $data['result'] = $this->database_model->check_record_exist('role','role_id',array('title'=>$this->input->post('title'),'status !='=>'3','role_id !='=>$this->input->post('id')));

        if ($data['result']['role_id']>0)
        {
            $this->form_validation->set_message('check_exists_edit',$this->lang->line('role_already_exist'));
                return FALSE;
        }else{
            return TRUE;
        }
        return TRUE;
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */