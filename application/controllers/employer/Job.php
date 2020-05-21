<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Job Extends CI_Controller {
    var $common_data,$thisModuleName,$thisModuleBaseUrl;
    public function __construct()
	{
        parent::__construct();

        $this->load->model('employer/employer_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
        has_permission_employer();
        $this->limit = $this->site_setting->site_admin_rowperpage;
        $this->thisModuleName ='Jobs';
        $this->thisModuleBaseUrl = site_url('employer/job').'/';
        $this->employer_id = $this->session->userdata('super_employer_id')>0?$this->session->userdata('super_employer_id'):$this->session->userdata('employer_id');
        $this->common_data = array(
            'title' => $this->thisModuleName,
            'link_add' => $this->thisModuleBaseUrl.'add/',
            'edit_link' => $this->thisModuleBaseUrl.'edit/',
            'info_link' => $this->thisModuleBaseUrl.'information/',
            'tbl' => 'job',
            'column' => 'job_id',
            'main_module' => $this->thisModuleName,
            'module_base_url' => $this->thisModuleBaseUrl,
            'roles_key_value' => $this->config->item('roles_key_value'),
            'thumb_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/admins'),
            'big_profile_img_login_user' => check_image($this->session->userdata('common_image'),'uploads/admins','big'),
        );
	}
    function index($offset=0)
    {
        is_role_access_employer('job','index');
        if(!is_numeric($offset))
        {
            $offset = 0;
        }
        $uri_segment = 4;
        $data = $this->common_data;
        $data['title'] = 'job';
        $data['meta_key'] = 'job';
        $data['meta_desc'] = 'job';

        $data['action'] = $this->thisModuleBaseUrl;
        // For Search data
        $suffix_array = array();
        $like_str = '';
        if($this->input->get('status') != ''){
            $suffix_array['status'] = urldecode($this->input->get('status'));
            $where_array=array('job.status !='=>'3','job.employer_id'=>$this->employer_id,'job.job_status'=>$suffix_array['status']);
        }else{
            $where_array=array('job.status !='=>'3','job.employer_id'=>$this->employer_id);
        }
        $data['list_records']=array();
        $select_value= 'job.*,job_type.title';
        $joins=array(
            array(
                'table'=>'job_type',
                'condition'=>'job.job_type_id=job_type.job_type_id',
                'jointype'=>'left'
            ),
            array(
                'table'=>'job_industry',
                'condition'=>'job.job_industry_id=job_industry.job_industry_id',
                'jointype'=>'left'
            ),
        );

        $query_return = $this->database_model->get_joins('job',$select_value,$joins,$where_array,'job.job_id','DESC',$this->limit,$offset,'',$like_str,'','','','Yes');

        $data['list_records'] = $query_return['results'];

        $total = $this->database_model->get_joins('job',$select_value,$joins,$where_array=array('job.status !='=>'3','job.employer_id'=>$this->employer_id),'job.job_id','DESC',$this->limit,$offset,'',$like_str,'','','','Yes');
        $data['total_records'] = $total['total_records'];

        $data['pagination'] = $this->common_model->get_pagination($suffix_array,$this->thisModuleBaseUrl.'index',$data['total_records'],$this->limit,$uri_segment);
        $data['j'] = 0 + $offset;
        $data['offset'] = $offset;

        $data['open_counter']=$this->database_model->count_all('job',array('job.status !='=>'3','job.employer_id'=>$this->employer_id,'job.job_status'=>'1'));
        $data['paused_counter']=$this->database_model->count_all('job',array('job.status !='=>'3','job.employer_id'=>$this->employer_id,'job.job_status'=>'2'));
        $data['closed_counter']=$this->database_model->count_all('job',array('job.status !='=>'3','job.employer_id'=>$this->employer_id,'job.job_status'=>'3'));
        /*******************************
        ||  Common data for all page ||
         *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/job', $data);
    }

    function add()
    {
        is_role_access_employer('job','add');
        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'insert';
        $data['sub_module']=  'Add';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['method']=  'Add';
        $select_value= 'job_type.*';
        $data['job_types'] = $this->database_model->get_all_records('job_type',$select_value,array('job_type.status !='=>'3'),'job_type.title','ASC','')->result();

        $select_value= 'search_radius.*';
        $data['search_radiuses'] = $this->database_model->get_all_records('search_radius',$select_value,array('search_radius.status !='=>'3'),'search_radius.search_radius_id','ASC','')->result();

        $select_value= 'job_industry.*';
        $data['job_industries'] = $this->database_model->get_all_records('job_industry',$select_value,array('job_industry.status !='=>'3'),'job_industry.title','ASC','')->result();
        /*******************************
        ||  Common data for all page ||
        *******************************/
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/job_edit', $data);
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
            $subscription = $this->common_model->check_subscription($this->employer_id,$PostArray['job_profile_size']);
            if($subscription['allow_to_add']!=0){

                $this->ip_date = $this->common_model->get_date_ip();
                $value_array = array(
                    'employer_id' => $this->employer_id,
                    'job_title' => $PostArray['job_title'],
                    'code' => $PostArray['code'],
                    'job_type_id' => $PostArray['job_type_id'],
                    'job_location' => $PostArray['job_location'],
                    'job_source_location_type' => $PostArray['source_location'],
                    'job_industry_id' => $PostArray['job_industry_id'],
                    'min_experience' => $PostArray['min_experience'],
                    'max_experience' => $PostArray['max_experience'],
                    'job_profile_size' => $PostArray['job_profile_size'],
                    'job_description' => $PostArray['job_description'],
                    'job_requirement' => $PostArray['job_requirement'],
                    'job_remark' => $PostArray['job_remark'],
                    'job_profile_banchmark' => $PostArray['job_profile_banchmark'],
                    'submitted_candidate' => 0,
                    'contacted_candidate' => 0,
                    'remaining_candidate' => $PostArray['job_profile_size'],
                    'black_listed_company' => $PostArray['black_listed_company'],
                    'job_status' => '1',
                    'status' => 1,
                    'created_at'=>$this->ip_date->cur_date,
                    'updated_at'=>$this->ip_date->cur_date,
                    'created_ip'=>$this->ip_date->ip,
                );
                $id = $this->database_model->save('job',$value_array);

                $location_array = array(
                    'job_id'=>$id,
                    'user_id'=>$this->employer_id,
                    'user_type'=>1,
                    'status' => '1',
                    'created_at'=>$this->ip_date->cur_date,
                    'updated_at'=>$this->ip_date->cur_date,
                    'created_ip'=>$this->ip_date->ip,
                );
                $this->database_model->save('job_conversation',$location_array);

                if($PostArray['source_location']==1){
                    foreach ($PostArray['multiple_location'] as $location){
                        $location_array = array(
                            'job_id'=>$id,
                            'location'=>$location,
                            'search_radius_id'=>'',
                            'status' => '1',
                            'created_at'=>$this->ip_date->cur_date,
                            'updated_at'=>$this->ip_date->cur_date,
                            'created_ip'=>$this->ip_date->ip,
                        );
                        $this->database_model->save('source_location',$location_array);
                    }
                }else{
                    $i=0;
                    foreach ($PostArray['multiple_s_location'] as $location){
                        $location_array = array(
                            'job_id'=>$id,
                            'location'=>$location,
                            'search_radius_id'=>$PostArray['search_radius'][$i],
                            'status' => '1',
                            'created_at'=>$this->ip_date->cur_date,
                            'updated_at'=>$this->ip_date->cur_date,
                            'created_ip'=>$this->ip_date->ip,
                        );
                        $this->database_model->save('source_location',$location_array);
                        $i++;
                    }
                }
                //ENTRY IN HISTORY TABLE
                $history_value_array=array(
                    'job_id'=>$id,
                    'batch_size'=>$PostArray['job_profile_size'],
                    'operation_status'=>1,
                    'status' => 1,
                    'created_at'=>$this->ip_date->cur_date,
                    'updated_at'=>$this->ip_date->cur_date,
                    'created_ip'=>$this->ip_date->ip,
                );
                $this->database_model->save('job_batch_history',$history_value_array);

                $this->database_model->custom_query("UPDATE tbl_employer_subscription set remain_credit = remain_credit - ".$PostArray['job_profile_size']." WHERE employer_subscription_id = ".$subscription['employer_subscription_id']);

                //Insert Modified log
                $value_array = array(
                    'table_id' => $id,
                    'table_name' => $this->db->dbprefix('job'),
                    'activity' => '1',
                    'modified_by'=> $this->employer_id,
                );

                $this->database_model->insert_modified($value_array,$this->employer_id);
                $this->session->set_flashdata('notification',$this->lang->line('job_succ_added'));
                redirect($this->thisModuleBaseUrl);
                die();
            }else{
                $this->return_back($PostArray,$subscription['message']);
            }
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->job_title = $PostArray['job_title'];
            @$this->form_data->code = $PostArray['code'];
            @$this->form_data->job_type_id = $PostArray['job_type_id'];
            @$this->form_data->job_location = $PostArray['job_location'];
            @$this->form_data->source_location = $PostArray['source_location'];
            @$this->form_data->job_industry_id = $PostArray['job_industry_id'];
            @$this->form_data->min_experience = $PostArray['min_experience'];
            @$this->form_data->max_experience = $PostArray['max_experience'];
            @$this->form_data->job_profile_size = $PostArray['job_profile_size'];
            @$this->form_data->job_description = $PostArray['job_description'];
            @$this->form_data->job_requirement = $PostArray['job_requirement'];
            @$this->form_data->job_remark = $PostArray['job_remark'];
            @$this->form_data->job_profile_banchmark = $PostArray['job_profile_banchmark'];
            @$this->form_data->black_listed_company = $PostArray['black_listed_company'];

            $data['multiple_locations'] = $PostArray['multiple_location'];
            $data['s_location'] = $PostArray['multiple_s_location'];
            $data['s_radius'] = $PostArray['search_radius'];

            /*******************************
            ||  Common data for all page ||
             *******************************/
            $select_value= 'job_type.*';
            $data['job_types'] = $this->database_model->get_all_records('job_type',$select_value,array('job_type.status !='=>'3'),'job_type.title','ASC','')->result();

            $select_value= 'job_industry.*';
            $data['job_industries'] = $this->database_model->get_all_records('job_industry',$select_value,array('job_industry.status !='=>'3'),'job_industry.title','ASC','')->result();

            $select_value= 'search_radius.*';
            $data['search_radiuses'] = $this->database_model->get_all_records('search_radius',$select_value,array('search_radius.status !='=>'3'),'search_radius.search_radius_id','ASC','')->result();

            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);

            $this->load->view('employer/job_edit', $data);
        }
    }

    function edit($id='')
    {
        is_role_access_employer('job','edit');
        if(!is_numeric($id))
            $this->add();
        $data = $this->common_data;
        $data['action']= $this->thisModuleBaseUrl.'update/'.$id;
        $data['sub_module']=  'Edit';
        $data['method']=  'Edit';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['Edit_id']=  $id;

        $select_val = 'job.*';
        $result = $this->database_model->get_all_records('job',$select_val,array('job.status !='=>'3','job.job_id'=>$id),'job.job_id DESC','1')->row();

        if(!empty($result))
        {
            @$this->form_data->id = $result->job_id;
            $this->form_data->job_title= $result->job_title;
            $this->form_data->code= $result->code;
            $this->form_data->job_type_id= $result->job_type_id;
            $this->form_data->job_location= $result->job_location;
            $this->form_data->source_location= $result->job_source_location_type;
            $this->form_data->job_industry_id= $result->job_industry_id;
            $this->form_data->min_experience= $result->min_experience;
            $this->form_data->max_experience= $result->max_experience;
            $this->form_data->job_profile_size= $result->job_profile_size;
            $this->form_data->job_description= $result->job_description;
            $this->form_data->job_requirement= $result->job_requirement;
            $this->form_data->job_remark= $result->job_remark;
            $this->form_data->job_profile_banchmark= $result->job_profile_banchmark;
            @$this->form_data->black_listed_company = $result->black_listed_company;

            $select_value= 'source_location.*';
            $locations = $this->database_model->get_all_records('source_location',$select_value,array('source_location.status !='=>'3','source_location.job_id'=>$result->job_id,),'source_location.source_location_id','ASC','')->result();
            $multiple_locations = array();
            $location_ids = array();
            $s_location = array();
            $s_radius = array();
            if($result->job_source_location_type==1){
                for($i=0;$i<count($locations);$i++){
                    $multiple_locations[$i]=$locations[$i]->location;
                    $location_ids[$i]=$locations[$i]->source_location_id;
                }
            }else{
                for($i=0;$i<count($locations);$i++){
                    $s_location[$i]=$locations[$i]->location;
                    $s_radius[$i]=$locations[$i]->search_radius_id;
                    $location_ids[$i]=$locations[$i]->source_location_id;
                }
            }
            $data['multiple_locations'] = $multiple_locations;
            $data['s_location'] = $s_location;
            $data['s_radius'] = $s_radius;
            $data['location_ids'] = $location_ids;
            /*******************************
            ||  Common data for all page ||
             *******************************/
            $select_value= 'job_type.*';
            $data['job_types'] = $this->database_model->get_all_records('job_type',$select_value,array('job_type.status !='=>'3'),'job_type.title','ASC','')->result();

            $select_value= 'job_industry.*';
            $data['job_industries'] = $this->database_model->get_all_records('job_industry',$select_value,array('job_industry.status !='=>'3'),'job_industry.title','ASC','')->result();

            $select_value= 'search_radius.*';
            $data['search_radiuses'] = $this->database_model->get_all_records('search_radius',$select_value,array('search_radius.status !='=>'3'),'search_radius.search_radius_id','ASC','')->result();

            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('employer/job_edit', $data);
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
                'job_type_id' => $PostArray['job_type_id'],
                'job_location' => $PostArray['job_location'],
                'job_source_location_type' => $PostArray['source_location'],
                'job_industry_id' => $PostArray['job_industry_id'],
                'min_experience' => $PostArray['min_experience'],
                'max_experience' => $PostArray['max_experience'],
                'job_description' => $PostArray['job_description'],
                'job_requirement' => $PostArray['job_requirement'],
                'job_remark' => $PostArray['job_remark'],
                'job_profile_banchmark' => $PostArray['job_profile_banchmark'],
                'black_listed_company' => $PostArray['black_listed_company'],
                'updated_at'=>$this->ip_date->cur_date
            );
            $this->database_model->update('job',$update_array,array('job_id'=>$id));

            if($PostArray['source_location']==1) {
                $this->database_model->delete('source_location',array('job_id'=>$id,'search_radius_id >'=>0));
                $total = count($PostArray['multiple_location']);
                for ($i = 0; $i < $total; $i++) {
                    if ($PostArray['location_ids'][$i] > 0) { //update
                        $this->database_model->update('source_location', array('location' => $PostArray['multiple_location'][$i]), array('source_location_id' => $PostArray['location_ids'][$i]));
                    } else { //add
                        $location_array = array(
                            'job_id' => $id,
                            'location' => $PostArray['multiple_location'][$i],
                            'search_radius_id' => '',
                            'status' => '1',
                            'created_at' => $this->ip_date->cur_date,
                            'updated_at' => $this->ip_date->cur_date,
                            'created_ip' => $this->ip_date->ip,
                        );
                        $this->database_model->save('source_location', $location_array);
                    }
                }
            }else{
                $this->database_model->delete('source_location',array('job_id'=>$id,'search_radius_id '=>0));
                $total = count($PostArray['multiple_s_location']);
                for ($i = 0; $i < $total; $i++) {
                    if ($PostArray['location_ids'][$i] > 0) { //update
                        $this->database_model->update('source_location', array('location' => $PostArray['multiple_s_location'][$i],'search_radius_id' => $PostArray['search_radius'][$i]), array('source_location_id' => $PostArray['location_ids'][$i]));
                    } else { //add
                        $location_array = array(
                            'job_id' => $id,
                            'location' => $PostArray['multiple_s_location'][$i],
                            'search_radius_id' => $PostArray['search_radius'][$i],
                            'status' => '1',
                            'created_at' => $this->ip_date->cur_date,
                            'updated_at' => $this->ip_date->cur_date,
                            'created_ip' => $this->ip_date->ip,
                        );
                        $this->database_model->save('source_location', $location_array);
                    }
                }
            }
            //Insert Modified log
            $value_array = array(
                'table_id' => $id,
                'table_name' => $this->db->dbprefix('job'),
                'activity' => '2',
                'modified_by'=> $this->employer_id,
            );
            $this->database_model->insert_modified($value_array,$this->employer_id);
            //REDIRECT
            $this->session->set_flashdata('notification',$this->lang->line('job_succ_modified'));
            redirect($this->thisModuleBaseUrl);
            die();
        }
        else
        {
            if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');

            @$this->form_data->job_title = $PostArray['job_title'];
            @$this->form_data->code = $PostArray['code'];
            @$this->form_data->job_type_id = $PostArray['job_type_id'];
            @$this->form_data->job_location = $PostArray['job_location'];
            @$this->form_data->job_source_location = $PostArray['job_source_location'];
            @$this->form_data->job_industry_id = $PostArray['job_industry_id'];
            @$this->form_data->min_experience = $PostArray['min_experience'];
            @$this->form_data->max_experience = $PostArray['max_experience'];
            @$this->form_data->job_profile_size = $PostArray['job_profile_size'];
            @$this->form_data->job_description = $PostArray['job_description'];
            @$this->form_data->job_requirement = $PostArray['job_requirement'];
            @$this->form_data->job_remark = $PostArray['job_remark'];
            @$this->form_data->job_profile_banchmark = $PostArray['job_profile_banchmark'];
            @$this->form_data->search_radius_id = $PostArray['search_radius'];
            @$this->form_data->black_listed_company = $PostArray['black_listed_company'];

            $data['multiple_locations'] = $PostArray['multiple_location'];
            $data['s_location'] = $PostArray['multiple_s_location'];
            $data['s_radius'] = $PostArray['search_radius'];
            $data['location_ids'] = $PostArray['location_ids'];

            $select_value= 'source_location.*';
            $data['multiple_locations'] = $this->database_model->get_all_records('source_location',$select_value,array('source_location.status !='=>'3','source_location.job_id'=>$id,),'source_location.location','ASC','')->result();
            /*******************************
            ||  Common data for all page ||
             *******************************/
            $select_value= 'job_type.*';
            $data['job_types'] = $this->database_model->get_all_records('job_type',$select_value,array('job_type.status !='=>'3'),'job_type.title','ASC','')->result();

            $select_value= 'job_industry.*';
            $data['job_industries'] = $this->database_model->get_all_records('job_industry',$select_value,array('job_industry.status !='=>'3'),'job_industry.title','ASC','')->result();

            $select_value= 'search_radius.*';
            $data['search_radiuses'] = $this->database_model->get_all_records('search_radius',$select_value,array('search_radius.status !='=>'3'),'search_radius.search_radius_id','ASC','')->result();

            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('employer/job_edit', $data);

        }
    }

    function information($id='')
    {
        is_role_access_employer('job','information');
        if(!is_numeric($id))
            $this->add();

        $data = $this->common_data;
        $data['sub_module']=  'Information';
        $data['method']=  'Information';
        $data['title']= $data['sub_module'] .' | '. $this->thisModuleName ;
        $data['Edit_id']=  $id;

        $select_value= 'job.*,job_type.title as job_type,job_industry.title as job_industry,employer.employer_name';

        $joins=array(
            array(
                'table'=>'job_type',
                'condition'=>'job.job_type_id=job_type.job_type_id',
                'jointype'=>'left'
            ),
            array(
                'table'=>'job_industry',
                'condition'=>'job.job_industry_id=job_industry.job_industry_id',
                'jointype'=>'left'
            ),
            array(
                'table'=>'employer',
                'condition'=>'job.employer_id=employer.employer_id',
                'jointype'=>'left'
            ),
        );
        $data['row'] = $this->database_model->get_joins('job',$select_value,$joins,array('job.job_id'=>$id,'job.status !='=>'3'),'job.job_id','DESC',1)->row();
        $select_value= 'source_location.*,search_radius.radius';
        $joins=array(
            array(
                'table'=>'search_radius',
                'condition'=>'source_location.search_radius_id=search_radius.search_radius_id',
                'jointype'=>'left'
            ),
        );
        $data['locations'] = $this->database_model->get_joins('source_location',$select_value,$joins,array('source_location.status !='=>'3','source_location.job_id'=>$id),'source_location.source_location_id','ASC','')->result();

        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
        $this->load->view('employer/job_info', $data);
    }


    function _set_rules($id='')
    {   
        $PostArray = $this->input->post();
        $this->form_validation->set_rules('job_title', 'Job Title', 'trim|required');
        $this->form_validation->set_rules('code', 'Job ID', 'trim|required');
        $this->form_validation->set_rules('job_type_id', 'Job Type', 'trim|required');
        $this->form_validation->set_rules('job_location', 'Job Location', 'trim|required');
        $this->form_validation->set_rules('source_location', 'Source Location', 'trim|required');
        $this->form_validation->set_rules('job_industry_id', 'Job Industry', 'trim|required');
        $this->form_validation->set_rules('min_experience', 'Minimum Experience', 'trim|required');
        $this->form_validation->set_rules('max_experience', 'Maximum Experience', 'trim|required');
        $this->form_validation->set_rules('job_profile_size', 'Number of Profiles', 'trim|required');
        $this->form_validation->set_rules('job_description', 'Job Description', 'trim|required');
        $this->form_validation->set_rules('job_requirement', 'Must Haves (Required Skills/Experiences)', 'trim|required');
    }

    function check_exists($field_value, $id='')
    {
        $data['result'] = $this->database_model->check_record_exist('job','job_id',array('job'=>$this->input->post('job'),'status !='=>'3'));
        
        if ($data['result']['job_id']>0)
        {
            $this->form_validation->set_message('check_exists',$this->lang->line('job_already_exist'));
                return FALSE;
        }else{
            return TRUE;
        }
        return TRUE;
    }
    function return_back($PostArray,$message){

        @$this->form_data->job_title = $PostArray['job_title'];
        @$this->form_data->code = $PostArray['code'];
        @$this->form_data->job_type_id = $PostArray['job_type_id'];
        @$this->form_data->job_location = $PostArray['job_location'];
        @$this->form_data->source_location = $PostArray['source_location'];
        @$this->form_data->job_industry_id = $PostArray['job_industry_id'];
        @$this->form_data->min_experience = $PostArray['min_experience'];
        @$this->form_data->max_experience = $PostArray['max_experience'];
        @$this->form_data->job_profile_size = $PostArray['job_profile_size'];
        @$this->form_data->job_description = $PostArray['job_description'];
        @$this->form_data->job_requirement = $PostArray['job_requirement'];
        @$this->form_data->job_remark = $PostArray['job_remark'];
        @$this->form_data->job_profile_banchmark = $PostArray['job_profile_banchmark'];
        @$this->form_data->black_listed_company = $PostArray['black_listed_company'];

        $data['multiple_locations'] = $PostArray['multiple_location'];
        $data['s_location'] = $PostArray['multiple_s_location'];
        $data['s_radius'] = $PostArray['search_radius'];

        /*******************************
        ||  Common data for all page ||
         *******************************/
        $select_value= 'job_type.*';
        $data['job_types'] = $this->database_model->get_all_records('job_type',$select_value,array('job_type.status !='=>'3'),'job_type.title','ASC','')->result();

        $select_value= 'job_industry.*';
        $data['job_industries'] = $this->database_model->get_all_records('job_industry',$select_value,array('job_industry.status !='=>'3'),'job_industry.title','ASC','')->result();

        $select_value= 'search_radius.*';
        $data['search_radiuses'] = $this->database_model->get_all_records('search_radius',$select_value,array('search_radius.status !='=>'3'),'search_radius.search_radius_id','ASC','')->result();

        $data['csrf_error'] = $message;
        $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
        $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);

        $this->load->view('employer/job_edit', $data);
    }
}