<?php
class Recruiter_model extends CI_Model
{
    //CHECK SESSION AS USER VISE - RECRUITER
    function check_seesion($target_url = '')
    {
        if ($this->session->userdata('recruiter_logged_in') == FALSE) {
            $data = array('title' => $this->lang->line('recruiter') . " " . $this->lang->line('login'));
            $data['target'] = $target_url != '' ? $target_url : 'recruiter/dashboard';
            $data['unique_form_name'] = "CSRFGuard_" . mt_rand(0, mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('recruiter/login', $data);
            echo $this->output->get_output();
            exit;
        } else {
            //check status
            $result = $this->db->select('Id')->from('users')->where('recruiter_id', $this->session->userdata('recruiter_id'))->where('Status', 'Enable')->limit(1)->get()->row();
            if ($result->Id == '') {
                $array_items = array('recruiter_name' => '', 'recruiter_id' => '', 'recruiter_logged_in' => FALSE, 'login_type' => '',);
                $this->session->unset_userdata($array_items);
                redirect(site_url('recruiter/dashboard'));
            }
        }
    }

    //CLEAN SESSION AS USER VISE - RECRUITER
    function clean_session($param='')
    {
        $array_items = array('recruiter_name'=>'', 'recruiter_id'=>'', 'recruiter_logged_in'=>FALSE,'login_type'=>'','recruiter_type'=>'');
        $this->session->set_userdata($array_items);
        $this->session->unset_userdata($array_items);

    }

}