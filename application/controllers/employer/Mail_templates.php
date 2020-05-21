<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail_templates Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
	{
        parent::__construct();
        $this->load->model('employer/employer_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
        has_permission_employer();
        $this->limit = $this->site_setting->site_admin_rowperpage;

        $this->thisModuleName ='Email Templates';
        $this->thisModuleBaseUrl = site_url('employer/mail_templates').'/';
        
        $this->common_data = array(
            'title' => $this->thisModuleName,
            'link_add' => $this->thisModuleBaseUrl.'add/',
            'edit_link' => $this->thisModuleBaseUrl.'edit/',
            'info_link' => $this->thisModuleBaseUrl.'information/',
            'tbl' => 'employer_mail_templates',
            'column' => 'mail_template_id',
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
        $data['title'] = 'Email Templates';
        $data['meta_key'] = 'Email Templates';
        $data['meta_desc'] = 'Email Templates';

        // For Search data
        $suffix_array = array();
        $like_str = '';
        /*if($this->input->get('search') != ''){
            $suffix_array['search'] = trim(urldecode($this->input->get('search')));
            $like_str = '(erp_country.Name LIKE "%'.$suffix_array['search'].'%" OR erp_country.CountryCode LIKE "%'.$suffix_array['search'].'%") ';
        }*/
        $where_array=array('employer_mail_templates.status !='=>'3','employer_id'=>$this->session->userdata('employer_id'));

        $data['list_records']=array();
        $select_value= 'employer_mail_templates.*';
        $query_return = $this->database_model->get_all_records('employer_mail_templates',$select_value,$where_array,'employer_mail_templates.mail_template_id','DESC',$this->limit,$offset,'',$like_str,'','','','Yes');
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
        $this->load->view('employer/mail_templates', $data);
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
        $this->load->view('employer/mail_templates_edit', $data);
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
                'employer_id' => $this->session->userdata('employer_id'),
                'mail_title' => $PostArray['mail_title'],
                'mail_subject' => $PostArray['mail_subject'],
                'mail_content' => $PostArray['mail_content'],
                'status' => '1',
                'created_at'=>$this->ip_date->cur_date,
                'updated_at'=>$this->ip_date->cur_date,
                'created_ip'=>$this->ip_date->ip,
            );
            $id = $this->database_model->save('employer_mail_templates',$value_array);

            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('employer_mail_templates'),
                'activity' => '1',
                'modified_by'=> $this->session->userdata('employer_id'),
            );
            $this->database_model->insert_modified($value_array,$this->session->userdata('employer_id'));

            $this->session->set_flashdata('notification',$this->lang->line('mail_template_succ_added'));
            redirect($this->thisModuleBaseUrl);
            die();
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->mail_title = $PostArray['mail_title'];
            $this->form_data->mail_subject= $PostArray['mail_subject'];
            $this->form_data->mail_content= $PostArray['mail_content'];

            /*******************************
            ||  Common data for all page ||
             *******************************/

            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('employer/mail_templates_edit', $data);
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

        $select_val = 'employer_mail_templates.*';
        $result = $this->database_model->get_all_records('employer_mail_templates',$select_val,array('employer_mail_templates.status !='=>'3','employer_mail_templates.mail_template_id'=>$id,'employer_id'=>$this->session->userdata('employer_id')),'employer_mail_templates.mail_template_id DESC','1')->row();

        if(!empty($result))
        {
            @$this->form_data->id = $id;
            $this->form_data->mail_title = $result->mail_title;
            $this->form_data->mail_subject= $result->mail_subject;
            $this->form_data->mail_content= $result->mail_content;
            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('employer/mail_templates_edit', $data);
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
                'mail_title ' => $PostArray['mail_title'],
                'mail_subject ' => $PostArray['mail_subject'],
                'mail_content ' => $PostArray['mail_content'],
                'updated_at'=>$this->ip_date->cur_date
            );
            
            $this->database_model->update('employer_mail_templates',$update_array,array('mail_template_id'=>$id));
            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('employer_mail_templates'),
                'activity' => '2',
                'modified_by'=> $this->session->userdata('employer_id'),
            );
            $this->database_model->insert_modified($value_array,$this->session->userdata('employer_id'));
            //REDIRECT
            $this->session->set_flashdata('notification',$this->lang->line('mail_template_succ_modified'));
            redirect($this->thisModuleBaseUrl);
            die();
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');
            $this->form_data->mail_title = $PostArray['mail_title'];
            $this->form_data->mail_subject= $PostArray['mail_subject'];
            $this->form_data->mail_content= $PostArray['mail_content'];


            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);

            $this->load->view('employer/mail_templates_edit', $data);

        }
    }

    function _set_rules($id='')
    {   
        $PostArray = $this->input->post();
        $this->form_validation->set_rules('mail_title', 'Mail Title', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('mail_subject', 'Mail Subject', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('mail_content', 'Mail Content', 'trim|required|min_length[3]');
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */