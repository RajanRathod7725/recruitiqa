<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//Dynamically add Javascript files to header page

if(!function_exists('has_permission')){
    function has_permission($json= false)
    {
        login_check($json);

        $ci = &get_instance();
        $permission  = $ci->config->item('permission');
        $curClass = $ci->router->fetch_class();
        $curMethod = $ci->router->fetch_method();
        $curUserType = $ci->session->userdata('admin_type');
     /*   echo "<br><pre>";
        print_r($permission);
        echo "<br><pre>";
        echo $curClass;
        echo "<br><pre>";
        echo $curMethod;
        echo "<br><pre>";
        echo $curUserType;
        exit;*/
        if(!in_array($curUserType,$permission[$curClass][$curMethod]) AND !key_exists($curUserType,$permission[$curClass][$curMethod])){
            not_accessible($json);
	    }
    }
}

if(!function_exists('is_accessible')){
    function is_accessible($curClass,$curMethod,$accessIdArray=array(),$accessId='',$json = false)
    {
        login_check($json);
        $ci = &get_instance();
        $permission  = $ci->config->item('permission');
        $curUserType = $ci->session->userdata('admin_type');
        if(!in_array($curUserType,$permission[$curClass][$curMethod])){
            if(!key_exists($curUserType,$permission[$curClass][$curMethod])) {
                $return = false;
            }else{
                if(in_array('own',$permission[$curClass][$curMethod][$curUserType])){
                    if($accessId !== ''  && in_array($accessId,$accessIdArray)){
                        $return = true;
                    }else{
                        $return = false;
                    }
                }else{
                    $return = false;
                }
            }
        }else{
            $return = true;
        }

        if($return){
            if (!$json) {
                return true;
            }else{
                return array('code'=>1);
            }
        }else{
            if (!$json) {
                return false;
            } else {
                return array('code' => 0, 'msg' => 'Page not accessible');
            }
        }
    }
}

if(!function_exists('is_role_access')){
    function is_role_access($curController,$curMethod,$display='',$json = false){
        login_check($json);
        $ci = &get_instance();
        $user_type = $ci->session->userdata('login_type');
        if($user_type==4){
            $user_id = $ci->session->userdata('admin_id');
            $role_permission= $ci->database_model->get_all_records('role_permission','*',array('user_id'=>$user_id,'user_type'=>1),'role_permission_id','ASC',1,0)->row();
            $methods = $ci->database_model->custom_query("SELECT GROUP_CONCAT(tbl_permission.method_name) as method FROM `tbl_permission` WHERE `tbl_permission`.`role_id` = ".$role_permission->role_id." AND `tbl_permission`.`module_name` = '".$curController."'")->row()->method;
            $avail_method= explode(',',$methods);
            if(in_array($curMethod,$avail_method)){
                $return = true;
            }else{
                if($display!='display'){
                    not_accessible();
                }

                $return =false;
            }
        }else{
            $return = true;
        }
        if($return){
            if (!$json) {
                return true;
            }else{
                return array('code'=>1);
            }
        }else{
            if (!$json) {
                return false;
            } else {
                return array('code' => 0, 'msg' => 'Page not accessible');
            }
        }
    }
}

if(!function_exists('not_accessible')) {
    function not_accessible($json = false)
    {

        if (!$json) {
            $ci = &get_instance();
            $data = array('title' => '401 Access Denied','roles_key_value' => $ci->config->item('roles_key_value'),
                'main_module' => '401 Access Denied',
                'thumb_profile_img_login_user' => check_image($ci->session->userdata('admin_image'),'uploads/admin'),
                'big_profile_img_login_user' => check_image($ci->session->userdata('admin_image'),'uploads/admin','big'));
            $data['unique_form_name'] = "CSRFGuard_" . mt_rand(0, mt_getrandmax());
            $data['token'] = $ci->common_model->csrfguard_generate_token($data['unique_form_name']);
            $ci->load->view('admin/not_accessible', $data);
            echo $ci->output->get_output();
            exit;
        } else {
            return array('code'=>0,'msg'=>'Page not accessible');
        }

    }
}

if(!function_exists('data_not_found')) {
    function data_not_found($json = false)
    {
        if (!$json) {
            $ci = &get_instance();
            $ci->config->set_item('footer_js', array());
            $ci->config->set_item('header_css', array());
            add_css_page($ci->config->item('not_found__page_css'));
            add_js_footer($ci->config->item('not_found__page_js'));

            $data = array('title' => '404 Not Found','roles_key_value' => $ci->config->item('roles_key_value'),
                'main_module' => '404 Not Found',
                'thumb_profile_img_login_user' => check_image($ci->session->userdata('admin_image'),'uploads/admin'),
                'big_profile_img_login_user' => check_image($ci->session->userdata('admin_image'),'uploads/admin','big'));

            $data['unique_form_name'] = "CSRFGuard_" . mt_rand(0, mt_getrandmax());
            $data['token'] = $ci->common_model->csrfguard_generate_token($data['unique_form_name']);
            $ci->load->view('admin/data_not_found', $data);
            echo $ci->output->get_output();
            exit;
        } else {
            return array('code'=>0,'msg'=>'Data not found.');
        }
    }
}

if(!function_exists('login_check')) {
    function login_check($json = false)
    {


        $ci = &get_instance();
        if ($ci->session->userdata('admin_logged_in') == FALSE) {

            if (!$json) {

                $data = array('title' => $ci->lang->line('admin') . " " . $ci->lang->line('login'));
                $data['target'] = current_url();
                redirect(site_url('admin/login/'));
            }else{
                return array('code'=>0,'msg'=>'Please login first and try again later.');
            }
        }else{
            $result = $ci->db->select('admin_id')->from('admins')->where('admin_id', $ci->session->userdata('admin_id'))->where('status', '1')->limit(1)->get()->row();
            
            if ($result->admin_id == '') {
                $array_items = array('admin_name' => '', 'admin_id' => '', 'admin_logged_in' => FALSE,'admin_type'=>'');
                $ci->session->unset_userdata($array_items);
                if(!$json){
                    //todo for check admin working ok
                    $data['unique_form_name'] = "CSRFGuard_" . mt_rand(0, mt_getrandmax());
                    $data['token'] = $ci->common_model->csrfguard_generate_token($data['unique_form_name']);
                    //code stopedd from here
                    $data['content_section'] = $ci->load->view('admin/login', $data);
                    echo $ci->output->get_output();
                    exit;
                    //redirect(site_url('admin/login'));
                }else{
                    return array('code'=>0,'msg'=>'Not login.');
                }
            }else{
                if($json){
                    return array('code'=>1,'msg'=>'');
                }
            }
        }
    }
}

/*Recruiter*/

if(!function_exists('has_permission_recruiter')){
    function has_permission_recruiter($json= false)
    {
        login_check_recruiter($json);
        $ci = &get_instance();
        $permission  = $ci->config->item('permission');
        $curClass = $ci->router->fetch_class();
        $curMethod = $ci->router->fetch_method();
        $curUserType = $ci->session->userdata('recruiter_type');
            /*echo "<br><pre>";
            print_r($permission);
            echo "<br><pre>";
            echo 'permission array end';
            echo "<br><pre>";
            echo $curClass;
            echo "<br><pre>";
            echo 'currnet class end';
            echo "<br><pre>";
            echo $curMethod;
            echo "<br><pre>";
            echo 'current method end';
            echo "<br><pre>";
            echo $curUserType;
            echo "<br><pre>";
            echo 'current type end';
            echo "<br><pre>";
            exit;*/
        if(!in_array($curUserType,$permission[$curClass][$curMethod]) AND !key_exists($curUserType,$permission[$curClass][$curMethod])){
            not_accessible_recruiter($json);
        }
    }
}

if(!function_exists('login_check_recruiter')) {
    function login_check_recruiter($json = false)
    {

        $ci = &get_instance();
        if ($ci->session->userdata('recruiter_logged_in') == FALSE) {

            if (!$json) {

                $data = array('title' => $ci->lang->line('recruiter') . " " . $ci->lang->line('login'));
                $data['target'] = current_url();

                $data['unique_form_name'] = "CSRFGuard_" . mt_rand(0, mt_getrandmax());
                $data['token'] = $ci->common_model->csrfguard_generate_token($data['unique_form_name']);
                //code stopedd from here

                $data['content_section'] = $ci->load->view('recruiter/login', $data);
                echo $ci->output->get_output();
                exit;
            }else{
                return array('code'=>0,'msg'=>'Please login first and try again later.');
            }
        }else{

            $result = $ci->db->select('recruiter_id')->from('recruiter')->where('recruiter_id', $ci->session->userdata('recruiter_id'))->where('status', '1')->limit(1)->get()->row();
            if ($result->recruiter_id == '') {
                $array_items = array('recruiter_name' => '', 'recruiter_id' => '', 'recruiter_logged_in' => FALSE);
                $ci->session->unset_userdata($array_items);
                if(!$json){
                    //todo for check admin working ok
                    $data['unique_form_name'] = "CSRFGuard_" . mt_rand(0, mt_getrandmax());
                    $data['token'] = $ci->common_model->csrfguard_generate_token($data['unique_form_name']);
                    //code stopedd from here
                    $data['content_section'] = $ci->load->view('recruiter/login', $data);
                    echo $ci->output->get_output();
                    exit;
                    //redirect(site_url('admin/login'));
                }else{
                    return array('code'=>0,'msg'=>'Not login.');
                }
            }else{
                if($json){
                    return array('code'=>1,'msg'=>'');
                }
            }
        }
    }
}

if(!function_exists('not_accessible_recruiter')) {
    function not_accessible_recruiter($json = false)
    {

        if (!$json) {
            $ci = &get_instance();
            $data = array('title' => '401 Access Denied','roles_key_value' => $ci->config->item('roles_key_value'),
                'main_module' => '401 Access Denied',
                'thumb_profile_img_login_user' => check_image($ci->session->userdata('admin_image'),'uploads/admin'),
                'big_profile_img_login_user' => check_image($ci->session->userdata('admin_image'),'uploads/admin','big'));
            $data['unique_form_name'] = "CSRFGuard_" . mt_rand(0, mt_getrandmax());
            $data['token'] = $ci->common_model->csrfguard_generate_token($data['unique_form_name']);
            $ci->load->view('recruiter/not_accessible', $data);
            echo $ci->output->get_output();
            exit;
        } else {
            return array('code'=>0,'msg'=>'Page not accessible');
        }

    }
}


/*Employer*/

if(!function_exists('has_permission_employer')){
    function has_permission_employer($json= false)
    {

        login_check_employer($json);
        $ci = &get_instance();
        $permission  = $ci->config->item('permission');
        $curClass = $ci->router->fetch_class();
        $curMethod = $ci->router->fetch_method();
        $curUserType = $ci->session->userdata('employer_type');
            /*echo "<br><pre>";
            print_r($permission);
            echo "<br><pre>";
            echo 'permission array end';
            echo "<br><pre>";
            echo $curClass;
            echo "<br><pre>";
            echo 'currnet class end';
            echo "<br><pre>";
            echo $curMethod;
            echo "<br><pre>";
            echo 'current method end';
            echo "<br><pre>";
            echo $curUserType;
            echo "<br><pre>";
            echo 'current type end';
            echo "<br><pre>";
            exit;*/
        if(!in_array($curUserType,$permission[$curClass][$curMethod]) AND !key_exists($curUserType,$permission[$curClass][$curMethod])){
            not_accessible_employer($json);
        }
    }
}

if(!function_exists('login_check_employer')) {
    function login_check_employer($json = false)
    {

        $ci = &get_instance();
        if ($ci->session->userdata('employer_logged_in') == FALSE) {

            if (!$json) {

                $data = array('title' => $ci->lang->line('employer') . " " . $ci->lang->line('login'));
                $data['target'] = current_url();

                $data['unique_form_name'] = "CSRFGuard_" . mt_rand(0, mt_getrandmax());
                $data['token'] = $ci->common_model->csrfguard_generate_token($data['unique_form_name']);
                //code stopedd from here

                $data['content_section'] = $ci->load->view('employer/login', $data);
                echo $ci->output->get_output();
                exit;
            }else{
                return array('code'=>0,'msg'=>'Please login first and try again later.');
            }
        }else{

            $result = $ci->db->select('employer_id')->from('employer')->where('employer_id', $ci->session->userdata('employer_id'))->where('status', '1')->limit(1)->get()->row();
            if ($result->employer_id == '') {
                $array_items = array('employer_name' => '', 'employer_id' => '', 'employer_logged_in' => FALSE);
                $ci->session->unset_userdata($array_items);
                if(!$json){
                    //todo for check admin working ok
                    $data['unique_form_name'] = "CSRFGuard_" . mt_rand(0, mt_getrandmax());
                    $data['token'] = $ci->common_model->csrfguard_generate_token($data['unique_form_name']);
                    //code stopedd from here
                    $data['content_section'] = $ci->load->view('employer/login', $data);
                    echo $ci->output->get_output();
                    exit;
                    //redirect(site_url('admin/login'));
                }else{
                    return array('code'=>0,'msg'=>'Not login.');
                }
            }else{
                if($json){
                    return array('code'=>1,'msg'=>'');
                }
            }
        }
    }
}
if(!function_exists('is_role_access_employer')){
    function is_role_access_employer($curController,$curMethod,$display='',$json = false){

        login_check_employer($json);
        $ci = &get_instance();
        $user_type = $ci->session->userdata('employer_type');

        if($user_type==5){
            $user_id = $ci->session->userdata('employer_id');
            $role_permission= $ci->database_model->get_all_records('role_permission','*',array('user_id'=>$user_id,'user_type'=>2),'role_permission_id','ASC',1,0)->row();
            $methods = $ci->database_model->custom_query("SELECT GROUP_CONCAT(tbl_permission.method_name) as method FROM `tbl_permission` WHERE `tbl_permission`.`role_id` = ".$role_permission->role_id." AND `tbl_permission`.`module_name` = '".$curController."'")->row()->method;

            $avail_method= explode(',',$methods);
            if(in_array($curMethod,$avail_method)){
                $return = true;
            }else{
                if($display!='display'){
                    not_accessible_employer();
                }

                $return =false;
            }
        }else{
            $return = true;
        }
        if($return){
            if (!$json) {
                return true;
            }else{
                return array('code'=>1);
            }
        }else{
            if (!$json) {
                return false;
            } else {
                return array('code' => 0, 'msg' => 'Page not accessible');
            }
        }
    }
}

if(!function_exists('not_accessible_employer')) {
    function not_accessible_employer($json = false)
    {

        if (!$json) {
            $ci = &get_instance();
            $data = array('title' => '401 Access Denied','roles_key_value' => $ci->config->item('roles_key_value'),
                'main_module' => '401 Access Denied',
                'thumb_profile_img_login_user' => check_image($ci->session->userdata('admin_image'),'uploads/admin'),
                'big_profile_img_login_user' => check_image($ci->session->userdata('admin_image'),'uploads/admin','big'));
            $data['unique_form_name'] = "CSRFGuard_" . mt_rand(0, mt_getrandmax());
            $data['token'] = $ci->common_model->csrfguard_generate_token($data['unique_form_name']);
            $ci->load->view('employer/not_accessible', $data);
            echo $ci->output->get_output();
            exit;
        } else {
            return array('code'=>0,'msg'=>'Page not accessible');
        }

    }
}
if(!function_exists('is_accessible_employer')){
    function is_accessible_employer($curClass,$curMethod,$accessIdArray=array(),$accessId='',$json = false)
    {
        login_check_employer($json);
        $ci = &get_instance();
        $permission  = $ci->config->item('permission');
        $curUserType = $ci->session->userdata('employer_type');
        if(!in_array($curUserType,$permission[$curClass][$curMethod])){
            if(!key_exists($curUserType,$permission[$curClass][$curMethod])) {
                $return = false;
            }else{
                if(in_array('own',$permission[$curClass][$curMethod][$curUserType])){
                    if($accessId !== ''  && in_array($accessId,$accessIdArray)){
                        $return = true;
                    }else{
                        $return = false;
                    }
                }else{
                    $return = false;
                }
            }
        }else{
            $return = true;
        }

        if($return){
            if (!$json) {
                return true;
            }else{
                return array('code'=>1);
            }
        }else{
            if (!$json) {
                return false;
            } else {
                return array('code' => 0, 'msg' => 'Page not accessible');
            }
        }
    }
}


/* ./application/helpers/acl_helper.php */
?>
