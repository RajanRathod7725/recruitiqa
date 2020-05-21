<?php
class Employer_model extends CI_Model
{
    //CHECK SESSION AS USER VISE - EMPLOYER
    function check_seesion($target_url = '')
    {
        if ($this->session->userdata('employer_logged_in') == FALSE) {
            $data = array('title' => $this->lang->line('employer') . " " . $this->lang->line('login'));
            $data['target'] = $target_url != '' ? $target_url : 'employer/dashboard';
            $data['unique_form_name'] = "CSRFGuard_" . mt_rand(0, mt_getrandmax());
            $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('employer/login', $data);
            echo $this->output->get_output();
            exit;
        } else {
            //check status
            $result = $this->db->select('Id')->from('users')->where('employer_id', $this->session->userdata('employer_id'))->where('Status', 'Enable')->limit(1)->get()->row();
            if ($result->Id == '') {
                $array_items = array('employer_name' => '', 'employer_id' => '', 'employer_logged_in' => FALSE, 'login_type' => '',);
                $this->session->unset_userdata($array_items);
                redirect(site_url('employer/dashboard'));
            }
        }
    }

    //CLEAN SESSION AS USER VISE - EMPLOYER
    function clean_session($param='')
    {
        $array_items = array('employer_name'=>'', 'employer_id'=>'', 'employer_logged_in'=>FALSE,'login_type'=>'','emp_sender_mail'=>'','emp_sender_name'=>'','employer_type'=>'','employer_email'=>'');
        $this->session->set_userdata($array_items);
        $this->session->unset_userdata($array_items);

    }

}