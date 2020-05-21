<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings Extends CI_Controller {
    var $admin_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
	{
        parent::__construct();
        $this->load->model('admin/admin_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
        has_permission();

        $this->limit = $this->site_setting->site_admin_rowperpage;
        $this->thisModuleName ='Settings';
        $this->thisModuleBaseUrl = site_url('admin/settings').'/';
        $this->admin_data = array(
            'title' => $this->thisModuleName,
            'link_add' => $this->thisModuleBaseUrl.'add/',
            'edit_link' => $this->thisModuleBaseUrl.'edit/',
            'info_link' => $this->thisModuleBaseUrl.'information/',
            'tbl' => 'setting',
            'column' => 'setting_id',
            'main_module' => $this->thisModuleName,
            'module_base_url' => $this->thisModuleBaseUrl,
            'roles_key_value' => $this->config->item('roles_key_value'),
            'thumb_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/admins'),
            'big_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/admins','big'),
        );
	}

    function index($offset='')
    {
        if(!is_numeric($offset))
        {
            $offset = 0;
        }
        $uri_segment =4;
        $data = $this->admin_data;
        $data['title'] = 'Settings';
        $data['meta_key'] = 'Settings';
        $data['meta_desc'] = 'Settings';
        // For Search data
        $suffix_array = array();
        $like_str = '';
        $where_array='setting.status !="3" AND (tbl_setting.setting_type="General" OR tbl_setting.setting_type="Image")';
        $data['list_records']=array();
        $select_value= 'setting.*';

        $query_return = $this->database_model->get_all_records('setting',$select_value,$where_array,'setting.setting_id','ASC',$this->limit,$offset,'',$like_str,'','','','Yes');

        $data['list_records'] = $query_return['results'];
        $data['total_records'] = $query_return['total_records'];
        
        $data['pagination'] = $this->common_model->get_pagination($suffix_array,$this->thisModuleBaseUrl.'index',$data['total_records'],$this->limit,$uri_segment);
        $data['j'] = 0 + $offset;
        $data['offset'] = $offset;
       /* print_r($suffix_array);
        print_r($this->limit);
        print_r($uri_segment);
        print_r($this->thisModuleBaseUrl.'index');die();*/
        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('admin/settings', $data);
    }
    function edit($id='')
    {
        if(!is_numeric($id))
            $this->add();

        $data = $this->admin_data;
        $data['action']= $this->thisModuleBaseUrl.'update/'.$id;
        $data['sub_module']=  'Edit';
        $data['method']=  'Edit';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['Edit_id']=  $id;

        $select_val = 'setting.*';
        $result = $this->database_model->get_all_records('setting',$select_val,'setting.status !="3" AND tbl_setting.setting_id="'.$id.'" AND (tbl_setting.setting_type="General" OR tbl_setting.setting_type="Image")','setting.setting_id DESC','1')->row();

        if(!empty($result))
        {
            @$this->form_data->setting_id = $result->setting_id;
            $this->form_data->setting_fieldname = $result->setting_fieldname;
            $this->form_data->setting_keytext= $result->setting_keytext;
            $this->form_data->setting_value= $result->setting_value;
            $this->form_data->setting_type= $result->setting_type;
            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('admin/settings_edit', $data);
        }
        else
        {
            $this->add();
        }
    }
    function update($id)
    {
        $PostArray = $this->input->post();
        $data = $this->admin_data;
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
            if($_FILES['setting_image']['name']!='')
            {
                $config['upload_path'] = './uploads/others/big';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '10240';
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('setting_image'))
                {
                    $error = array('error' => $this->upload->display_errors());
                    $data['upload_error'] = $error;
                    $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
                    $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);

                    @$this->form_data->setting_fieldname = $PostArray['setting_fieldname'];
                    $this->form_data->setting_keytext= $PostArray['setting_keytext'];
                    $this->form_data->setting_value= $PostArray['setting_value'];
                    $this->form_data->setting_type= $PostArray['setting_type'];
                    $this->load->view('admin/settings_edit', $data);
                    return;
                }
                else
                {
                    $file_name = $this->upload->file_name;
                    $this->create_thumb($file_name,60,60);
                }
            }
            $update_array = array(
                'setting_fieldname' => $PostArray['setting_fieldname'],
                'updated_at'=>$this->ip_date->cur_date
            );
            if($PostArray['setting_type']=='Image'){
                $update_array['setting_value']= $file_name;
            }else{
                $update_array['setting_value']= $PostArray['setting_value'];
            }

            $this->database_model->update('setting',$update_array,array('setting_id'=>$id));
            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('setting'),
                'activity' => '2',
                'modified_by'=> $this->session->userdata('admin_id'),
            );
            $this->database_model->insert_modified($value_array,$this->session->userdata('admin_id'));
            //REDIRECT
            $this->session->set_flashdata('notification',$this->lang->line('setting_succ_modified'));
            redirect($this->thisModuleBaseUrl);
            die();
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->setting_id = $id;
            $this->form_data->setting_fieldname = $PostArray['setting_fieldname'];
            $this->form_data->setting_keytext= $PostArray['setting_keytext'];
            $this->form_data->setting_value= $PostArray['setting_value'];
            $this->form_data->setting_type= $PostArray['setting_type'];

            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('admin/settings_edit', $data);
        }
    }

    function _set_rules($id='')
    {
        $PostArray = $this->input->post();
        $this->form_validation->set_rules('setting_fieldname', 'Field Name', 'trim|required|min_length[3]');
        if($PostArray['setting_type']!='Image') {
            $this->form_validation->set_rules('setting_value', 'Value', 'trim|required');
        }
    }
    function create_thumb($file,$width=60,$height=60,$folder='thumb')
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] = './uploads/others/big/'.$file;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = FALSE;
        $config['width'] = $width;
        $config['height'] = $height;
        $config['new_image'] = './uploads/others/'.$folder.'/'.$file;
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