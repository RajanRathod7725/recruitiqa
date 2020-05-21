<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_todo_status extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('database_model');
        $this->load->model('employer/employer_model');
        $this->load->model('common_model');
        $this->employer_id = $this->session->userdata('super_employer_id')>0?$this->session->userdata('super_employer_id'):$this->session->userdata('employer_id');
        has_permission_employer();
    }
    public function index()
    {
        header('Content-Type: application/json');
        $tbl = $this->common_model->Decryption($this->input->post('tbl'));
        $column = $this->common_model->Decryption($this->input->post('column'));
        $csrf_check = $this->common_model->csrfguard_validate_token($this->input->post('csrf_name'),$this->input->post('csrf_token'));
        $tbl_with_prefix = $this->db->dbprefix($tbl);
        if($csrf_check==true)
        {
            $this->ip_date = $this->common_model->get_date_ip();

            $tbl_id = $this->input->post('id');

            $query = "UPDATE  " . $tbl_with_prefix . " SET todo_status = '" . $this->input->post('value') . "', updated_at='" . $this->ip_date->cur_date . "' Where " . $column." =". $tbl_id;
            $this->database_model->custom_query($query);

            $value_array = array(
                'table_id' => $tbl_id,
                'table_name' => $this->db->dbprefix($tbl),
                'activity' => '2',
                'modified_by'=> $this->employer_id,
            );
            $this->database_model->insert_modified($value_array,$this->employer_id);
            $message=array('code'=>1);
        }
        else{
            $message=array('code'=>0);
            $message['error']=lang('csrf_error');
        }

        $message['csrf_name']="CSRFGuard_".mt_rand(0,mt_getrandmax());
        $message['csrf_token']=$this->common_model->csrfguard_generate_token($message['csrf_name']);
        echo json_encode($message);
        die();
    }
}

/* End of file ajax_delete.php */
/* Location: ./application/controllers/manage/ajax_delete.php */