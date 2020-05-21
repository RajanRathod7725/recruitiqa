<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscription Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
	{

        parent::__construct();
        $this->load->model('employer/employer_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
        has_permission_employer();
        $this->limit = $this->site_setting->site_admin_rowperpage;
        $this->thisModuleName ='Subscription';
        $this->thisModuleBaseUrl = site_url('employer/subscription').'/';
        
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
        $data['title'] = 'Subscription';
        $data['meta_key'] = 'Subscription';
        $data['meta_desc'] = 'Subscription';

        // For Search data
        $suffix_array = array();
        $like_str = '';
        $data['list_records']=array();

        $where_array=array('employer_subscription.status !='=>'3','employer_subscription.employer_id'=>$this->session->userdata('employer_id'));

        $select_value= 'employer_subscription.*,subscription_name as SubscriptionName';

        $query_return = $this->database_model->get_all_records('employer_subscription',$select_value,$where_array,'employer_subscription.employer_subscription_id','DESC',$this->limit)->result();


        /*$select_value= 'employer_subscription.*,subscription.name';
        $joins=array(
            array(
                'table'=>'subscription',
                'condition'=>'subscription.subscription_id=employer_subscription.subscription_id',
                'jointype'=>'left'
            ),
        );
        $query_return = $this->database_model->get_joins('employer_subscription',$select_value,$joins,array('employer_subscription.employer_id'=>$this->session->userdata('employer_id'),'employer_subscription.status !='=>'3','employer_subscription.subscription_status'=>'0'),'employer_subscription.employer_subscription_id','DESC',$this->limit,$offset,'',$like_str,'','','','Yes');*/

        $data['list_records'] = $query_return;
        $data['total_records'] = count($query_return);

        $data['pagination'] = $this->common_model->get_pagination($suffix_array,$this->thisModuleBaseUrl.'index',$data['total_records'],$this->limit,$uri_segment);
        $data['j'] = 0 + $offset;
        $data['offset'] = $offset;
        /*******************************
        ||  Common data for all page ||
         *******************************/
        $select_value= 'employer_subscription.*,subscription.name';
        $joins=array(
            array(
                'table'=>'subscription',
                'condition'=>'subscription.subscription_id=employer_subscription.subscription_id AND employer_subscription.subscription_id !=0',
                'jointype'=>'left'
            ),
        );
        $subscribe_result = $this->database_model->get_joins('employer_subscription',$select_value,$joins,array('employer_subscription.employer_id'=>$this->session->userdata('employer_id'),'employer_subscription.status !='=>'3','employer_subscription.subscription_status'=>'0'),'employer_subscription.employer_subscription_id','ASC','')->row();

        $data['show_subscribe_bar']=0;
        $data['current_pack'] = 'Expired';
        $data['left_day'] = 0;
        if(!empty($subscribe_result)){
            $data['current_pack']=$subscribe_result->subscription_name;
            $date1=date_create(date('Y-m-d'));
            $date2=date_create($subscribe_result->end_date);
            $diff=date_diff($date1,$date2);
            $left = $diff->format("%a") +1;
            $data['left_day']=$left;
            $data['show_subscribe_bar']=1;
        }
        $current_request = $this->database_model->get_all_records('subscription_request','*',array('status !='=>'3','request_status'=>'0','employer_id'=>$this->session->userdata('employer_id')),'subscription_request_id ASC','')->result();
        if(!empty($current_request)){
            $this->session->set_flashdata('notification',"Your request to buy the subscription plan has been submitted to the sales team successfully. We'll look into it and get you onboarded within 24 hours. Meanwhile, please schedule your pre-sales/onboarding call with the sales team by clicking this link:");
        }

        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/subscription', $data);
    }

    function add()
    {
        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'insert';
        $data['sub_module']=  'Plans';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['method']=  'Plans';

        $data['plans']= $this->database_model->get_all_records('subscription','*',array('status'=>'1'),'subscription_id ASC','')->result();

        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/subscription_edit', $data);
    }
    function insert()
    {
        $PostArray = $this->input->post();
        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'insert';
        $data['sub_module']=  'Add';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['method']=  'Add';

        $csrf_check = $this->common_model->csrfguard_validate_token($PostArray['csrf_name'],$PostArray['csrf_token']);


        if($csrf_check==true)
        {
            $result = $this->database_model->get_all_records('subscription_request','*',array('employer_id'=>$this->session->userdata('employer_id'),'request_status'=>'0','status !='=>'3'),'subscription_request_id ASC','1')->result();
            if(empty($result)){
                $this->ip_date = $this->common_model->get_date_ip();
                $subscription = $PostArray['subscription'];
                $value_array = array(
                    'employer_id' => $this->session->userdata('employer_id'),
                    'subscription_id' => $subscription,
                    'profile' => $PostArray['profile_count_'.$subscription],
                    'request_status' => '0',
                    'status' => '1',
                    'created_at'=>$this->ip_date->cur_date,
                    'updated_at'=>$this->ip_date->cur_date,
                    'created_ip'=>$this->ip_date->ip,
                );
                $id = $this->database_model->save('subscription_request',$value_array);

                //Insert Modified log
                $value_array = array(
                    'table_id' => $id,
                    'table_name' => $this->db->dbprefix('subscription_request'),
                    'activity' => '1',
                    'modified_by'=> $this->session->userdata('employer_id'),
                );
                $this->database_model->insert_modified($value_array,$this->session->userdata('employer_id'));

                $this->session->set_flashdata('notification',"Your request to buy the subscription plan has been submitted to the sales team successfully. We'll look into it and get you onboarded within 24 hours. Meanwhile, please schedule your pre-sales/onboarding call with the sales team by clicking this link:");
                redirect($this->thisModuleBaseUrl);
                die();
            }else{
                $this->session->set_flashdata('error','You have been already requested for the subscription, we will look it as soon as possible.');
                redirect($this->thisModuleBaseUrl);
                die();
            }
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            /*******************************
            ||  Common data for all page ||
             *******************************/
            $data['plans']= $this->database_model->get_all_records('subscription','*',array('status'=>'1'),'subscription_id ASC','')->result();


            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('employer/subscription_edit', $data);
        }
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */