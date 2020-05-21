<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscription Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
    {

        parent::__construct();
        $this->load->model('admin/admin_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
        has_permission();
        $this->limit = $this->site_setting->site_admin_rowperpage;
        $this->thisModuleName ='Subscription';
        $this->thisModuleBaseUrl = site_url('admin/subscription').'/';

        $this->common_data = array(
            'title' => $this->thisModuleName,
            'link_add' => $this->thisModuleBaseUrl.'add/',
            'edit_link' => $this->thisModuleBaseUrl.'edit/',
            'info_link' => $this->thisModuleBaseUrl.'information/',
            'tbl' => 'subscription',
            'column' => 'subscription_id',
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
        $data['title'] = 'Subscription';
        $data['meta_key'] = 'Subscription';
        $data['meta_desc'] = 'Subscription';

        // For Search data
        $suffix_array = array();
        $like_str = '';

        $where_array=array('subscription.status !='=>'3');
        $data['list_records']=array();
        $select_value= 'subscription.*';
        $query_return = $this->database_model->get_all_records('subscription',$select_value,$where_array,'subscription.name','ASC',$this->limit,$offset,'',$like_str,'','','','Yes');
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
        $this->load->view('admin/subscription', $data);
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
        $this->load->view('admin/subscription_edit', $data);
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
                'name ' => $PostArray['name'],
                'description ' => $PostArray['description'],
                'minimum_credit ' => $PostArray['minimum_credit'],
                'maximum_credit ' => $PostArray['maximum_credit'],
                'profile_rate ' => $PostArray['profile_rate'],
                'no_of_month ' => $PostArray['no_of_month'],
                'status' => 1,
                'created_at'=>$this->ip_date->cur_date,
                'updated_at'=>$this->ip_date->cur_date,
                'created_ip'=>$this->ip_date->ip,
            );
            $id = $this->database_model->save('subscription',$value_array);

            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('subscription'),
                'activity' => '1',
                'modified_by'=> $this->session->userdata('admin_id'),
            );

            $this->database_model->insert_modified($value_array,$this->session->userdata('admin_id'));
            $this->session->set_flashdata('notification',$this->lang->line('subscription_succ_added'));
            redirect($this->thisModuleBaseUrl);
            die();
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->name = $PostArray['name'];
            @$this->form_data->description = $PostArray['description'];
            @$this->form_data->minimum_credit = $PostArray['minimum_credit'];
            @$this->form_data->maximum_credit = $PostArray['maximum_credit'];
            @$this->form_data->profile_rate = $PostArray['profile_rate'];
            @$this->form_data->no_of_month = $PostArray['no_of_month'];

            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('admin/subscription_edit', $data);
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

        $select_val = 'subscription.*';
        $result = $this->database_model->get_all_records('subscription',$select_val,array('subscription.status !='=>'3','subscription.subscription_id'=>$id),'subscription.subscription_id DESC','1')->row();

        if(!empty($result))
        {
            @$this->form_data->id = $result->subscription_id;
            $this->form_data->name = $result->name;
            $this->form_data->description = $result->description;
            $this->form_data->minimum_credit = $result->minimum_credit;
            $this->form_data->maximum_credit = $result->maximum_credit;
            $this->form_data->profile_rate = $result->profile_rate;
            $this->form_data->no_of_month = $result->no_of_month;
            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('admin/subscription_edit', $data);
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
                'name ' => $PostArray['name'],
                'description ' => $PostArray['description'],
                'minimum_credit ' => $PostArray['minimum_credit'],
                'maximum_credit ' => $PostArray['maximum_credit'],
                'profile_rate ' => $PostArray['profile_rate'],
                'no_of_month ' => $PostArray['no_of_month'],
                'updated_at'=>$this->ip_date->cur_date
            );
            $this->database_model->update('subscription',$update_array,array('subscription_id'=>$id));
            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('subscription'),
                'activity' => '2',
                'modified_by'=> $this->session->userdata('admin_id'),
            );
            $this->database_model->insert_modified($value_array,$this->session->userdata('admin_id'));
            //REDIRECT
            $this->session->set_flashdata('notification',$this->lang->line('subscription_succ_modified'));
            redirect($this->thisModuleBaseUrl);
            die();
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->name = $PostArray['name'];
            @$this->form_data->description = $PostArray['description'];
            @$this->form_data->minimum_credit = $PostArray['minimum_credit'];
            @$this->form_data->maximum_credit = $PostArray['maximum_credit'];
            @$this->form_data->profile_rate = $PostArray['profile_rate'];
            @$this->form_data->no_of_month = $PostArray['no_of_month'];

            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('admin/subscription_edit', $data);

        }
    }

    function _set_rules($id='')
    {
        $PostArray = $this->input->post();
        if($PostArray['method']!='Edit'){
            $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]|callback_check_exists['.$id.']');
        }else{
            $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]|callback_check_exists_edit['.$id.']');
        }

        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('minimum_credit', 'Minimum Credit', 'trim|required');
        $this->form_validation->set_rules('maximum_credit', 'Maximum Credit', 'trim|required');
        $this->form_validation->set_rules('profile_rate', 'Profile Rate', 'trim|required');
        $this->form_validation->set_rules('no_of_month', 'No Of Month', 'trim|required');
    }
    function check_exists($field_value, $id='')
    {
        $data['result'] = $this->database_model->check_record_exist('subscription','subscription_id',array('name'=>$this->input->post('name'),'status !='=>'3'));

        if ($data['result']['subscription_id']>0)
        {
            $this->form_validation->set_message('check_exists',$this->lang->line('subscription_already_exist'));
            return FALSE;
        }else{
            return TRUE;
        }
        return TRUE;
    }
    function check_exists_edit($field_value, $id='')
    {
        $data['result'] = $this->database_model->check_record_exist('subscription','subscription_id',array('name'=>$this->input->post('name'),'status !='=>'3','subscription_id !='=>$id));

        if ($data['result']['subscription_id']>0)
        {
            $this->form_validation->set_message('check_exists_edit',$this->lang->line('subscription_already_exist'));
            return FALSE;
        }else{
            return TRUE;
        }
        return TRUE;
    }
}