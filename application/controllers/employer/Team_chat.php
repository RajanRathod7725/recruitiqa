<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Team_chat Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
	{

        parent::__construct();
        $this->load->model('employer/employer_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
        has_permission_employer();
        $this->limit = $this->site_setting->site_admin_rowperpage;
        $this->thisModuleName ='Team Chat';
        $this->thisModuleBaseUrl = site_url('employer/team_chat').'/';
        $this->employer_id = $this->session->userdata('super_employer_id')>0?$this->session->userdata('super_employer_id'):$this->session->userdata('employer_id');
        
        $this->common_data = array(
            'title' => $this->thisModuleName,
            'link_add' => $this->thisModuleBaseUrl.'add/',
            'edit_link' => $this->thisModuleBaseUrl.'edit/',
            'info_link' => $this->thisModuleBaseUrl.'information/',
            'tbl' => '',
            'column' => '',
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
        $data['title'] = 'Team Chat';
        $data['meta_key'] = 'Team Chat';
        $data['meta_desc'] = 'Team Chat';

        if($this->session->userdata('employer_type')== 2){ //if supper employer
            $where_array=array('employer_conversation.status !='=>'3','employer_conversation.super_employer_id'=>$this->session->userdata('employer_id'));
            $select_value= 'employer_conversation.*,employer.employer_name,employer.employer_photo,(SELECT  	CONCAT (message,"||",created_at) as msg FROM tbl_employer_chat WHERE tbl_employer_chat.conversation_id = tbl_employer_conversation.conversation_id AND (tbl_employer_chat.sender_id= tbl_employer_conversation.team_employer_id OR tbl_employer_chat.sender_id = tbl_employer_conversation.super_employer_id) ORDER BY employer_chat_id DESC LIMIT 1) as last_msg,(SELECT COUNT(employer_chat_id) as unread FROM `tbl_employer_chat` where conversation_id=tbl_employer_conversation.conversation_id AND receiver_id = '.$this->session->userdata('employer_id').' AND flag = 0 LIMIT 1) as unread';
            $joins=array(
                array(
                    'table'=>'employer',
                    'condition'=>'employer_conversation.team_employer_id=employer.employer_id',
                    'jointype'=>'left'
                ),
            );

            $data['teams_chat'] = $this->database_model->get_joins('employer_conversation',$select_value,$joins,$where_array,'employer.employer_name','ASC','')->result();

        }else{
            $data['member_chat'] = $this->database_model->get_all_records('employer_conversation','employer_conversation.*',array('employer_conversation.status !='=>'3','employer_conversation.team_employer_id'=>$this->session->userdata('employer_id')),'conversation_id')->row();
        }


        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/team_chat', $data);
    }
}