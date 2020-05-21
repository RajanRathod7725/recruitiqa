<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscription_request_cust Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
	{
        parent::__construct();
        $this->load->model('admin/admin_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
        has_permission();
        $this->limit = $this->site_setting->site_admin_rowperpage;
        $this->thisModuleName ='Custom Subscription Request';
        $this->thisModuleBaseUrl = site_url('admin/subscription_request_cust').'/';
        
        $this->common_data = array(
            'title' => $this->thisModuleName,
            'link_add' => $this->thisModuleBaseUrl.'add/',
            'edit_link' => $this->thisModuleBaseUrl.'edit/',
            'info_link' => $this->thisModuleBaseUrl.'information/',
            'tbl' => 'subscription_request_cust',
            'column' => 'subscription_request_cust_id',
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
        $data['title'] = 'Custom Subscription Request';
        $data['meta_key'] = 'Custom Subscription Request';
        $data['meta_desc'] = 'Custom Subscription Request';
        $data['action'] = $this->thisModuleBaseUrl;

        // For Search data
        $suffix_array = array();
        $like_str = '';
        if(@$_GET['section']=='' || @$_GET['section']=='Pending'){
            $where_array=array('subscription_request_cust.status !='=>'3','request_status'=>'0');
        }else{
            $where_array=array('subscription_request_cust.status !='=>'3','request_status'=>'1');
        }
        $data['list_records']=array();
        $select_value= 'subscription_request_cust.*,employer.employer_name,employer.outreach_email,employer.employer_phone,';
        $joins=array(
            array(
                'table'=>'employer',
                'condition'=>'subscription_request_cust.employer_id=employer.employer_id',
                'jointype'=>'left'
            ),
        );
        $query_return = $this->database_model->get_joins('subscription_request_cust',$select_value,$joins,$where_array,'subscription_request_cust.subscription_request_cust_id','DESC',$this->limit,$offset,'',$like_str,'','','','Yes');

        $data['list_records'] = $query_return['results'];
        $data['total_records'] = $query_return['total_records'];
        $data['pagination'] = $this->common_model->get_pagination($suffix_array,$this->thisModuleBaseUrl.'index',$data['total_records'],$this->limit,$uri_segment);
        $data['j'] = 0 + $offset;
        $data['offset'] = $offset;
        /*******************************
        ||  Common data for all page ||
         *******************************/
        /*echo "<pre>";
        print_r($data['list_records']);
        die;*/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('admin/subscription_request_cust', $data);
    }
}