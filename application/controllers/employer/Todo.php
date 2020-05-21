<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Todo Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
	{

        parent::__construct();
        $this->load->model('employer/employer_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
        has_permission_employer();
        $this->limit = $this->site_setting->site_admin_rowperpage;
        $this->thisModuleName ='ToDos';
        $this->thisModuleBaseUrl = site_url('employer/todo').'/';
        
        $this->common_data = array(
            'title' => $this->thisModuleName,
            'link_add' => $this->thisModuleBaseUrl.'add/',
            'edit_link' => $this->thisModuleBaseUrl.'edit/',
            'info_link' => $this->thisModuleBaseUrl.'information/',
            'tbl' => 'todo',
            'column' => 'todo_id',
            'main_module' => $this->thisModuleName,
            'module_base_url' => $this->thisModuleBaseUrl,
            'roles_key_value' => $this->config->item('roles_key_value'),
            'thumb_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/employers'),
            'big_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/employers','big'),
        );

	}

    function index($offset=0)
    {
        is_role_access_employer('todo','index');
        if(!is_numeric($offset))
        {
            $offset = 0;
        }
        $uri_segment = 4;
        $data = $this->common_data;
        $data['title'] = 'ToDos';
        $data['meta_key'] = 'ToDos';
        $data['meta_desc'] = 'ToDos';

        // For Search data
        $suffix_array = array();
        $like_str = '';

        $where_array=array('todo_status.status !='=>'3','todo_status.employer_id'=>$this->session->userdata('employer_id'));
        $data['list_records']=array();
        $select_value= 'todo_status.*';
        $data['list_records'] = $this->database_model->get_all_records('todo_status',$select_value,$where_array,'todo_status.todo_status_id','ASC','')->result();
        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/todo', $data);
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
        $this->load->view('employer/todo_edit', $data);
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
                'title ' => $PostArray['title'],
                'description ' => $PostArray['description'],
                'todo_status' => $PostArray['todo_status'],
                'employer_id' => $this->session->userdata('employer_id'),
                'status' => '1',
                'created_at'=>$this->ip_date->cur_date,
                'updated_at'=>$this->ip_date->cur_date,
                'created_ip'=>$this->ip_date->ip,
            );
            $id = $this->database_model->save('todo',$value_array);

            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('todo'),
                'activity' => '1',
                'modified_by'=> $this->session->userdata('employer_id'),
            );

            $this->database_model->insert_modified($value_array,$this->session->userdata('employer_id'));
            $this->session->set_flashdata('notification',$this->lang->line('todo_succ_added'));
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
            $this->load->view('employer/todo_edit', $data);
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

        $select_val = 'todo.*';
        $result = $this->database_model->get_all_records('todo',$select_val,array('todo.status !='=>'3','todo.todo_id'=>$id),'todo.todo_id DESC','1')->row();

        if(!empty($result))
        {
            @$this->form_data->id = $result->todo_id;
            $this->form_data->title = $result->title;
            $this->form_data->description = $result->description;
            $this->form_data->todo_status = $result->todo_status;
            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('employer/todo_edit', $data);
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
                'description' => $PostArray['description'],
                'todo_status' => $PostArray['todo_status'],
                'updated_at'=>$this->ip_date->cur_date
            );
            $this->database_model->update('todo',$update_array,array('todo_id'=>$id));
            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('todo'),
                'activity' => '2',
                'modified_by'=> $this->session->userdata('employer_id'),
            );
            $this->database_model->insert_modified($value_array,$this->session->userdata('employer_id'));
            //REDIRECT
            $this->session->set_flashdata('notification',$this->lang->line('todo_succ_modified'));
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
            $this->load->view('employer/todo_edit', $data);

        }
    }

    function _set_rules($id='')
    {   
        $PostArray = $this->input->post();
        $this->form_validation->set_rules('title', 'Title', 'trim|required|callback_check_exists['.$id.']');
        $this->form_validation->set_rules('todo_status', 'Status', 'trim');
    }

    function check_exists($field_value, $id='')
    {
        $data['result'] = $this->database_model->check_record_exist('todo','todo_id',array('title'=>$this->input->post('title'),'status !='=>'3'));
        
        if ($data['result']['todo_id']>0)
        {
            $this->form_validation->set_message('check_exists',$this->lang->line('todo_already_exist'));
                return FALSE;
        }else{
            return TRUE;
        }
        return TRUE;
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */