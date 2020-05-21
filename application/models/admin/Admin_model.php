<?php
class Admin_model extends CI_Model
{
    //CHECK SESSION AS USER VISE - ADMIN
    function check_seesion($target_url = '')
    {
        if ($this->session->userdata('admin_logged_in') == FALSE) {
            $data = array('title' => $this->lang->line('admin') . " " . $this->lang->line('login'));
            $data['target'] = $target_url != '' ? $target_url : 'admin/dashboard';
            $data['unique_form_name'] = "CSRFGuard_" . mt_rand(0, mt_getrandmax());
            $data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('admin/login', $data);
            echo $this->output->get_output();
            exit;
        } else {
            //check status
            $result = $this->db->select('Id')->from('users')->where('Id', $this->session->userdata('admin_id'))->where('Status', 'Enable')->limit(1)->get()->row();
            if ($result->Id == '') {
                $array_items = array('adminname' => '', 'admin_id' => '', 'admin_logged_in' => FALSE, 'login_type' => '',);
                $this->session->unset_userdata($array_items);
                redirect(site_url('admin/login'));
            }
        }
    }

    //CLEAN SESSION AS USER VISE - ADMIN
    function clean_session($param='')
    {
        $array_items = array('admin_name'=>'', 'admin_id'=>'', 'admin_logged_in'=>FALSE,'admin_role'=>'','login_type'=>'','admin_type'=>'','common_image'=>'');
        $this->session->set_userdata($array_items);
        $this->session->unset_userdata($array_items);
    }
}