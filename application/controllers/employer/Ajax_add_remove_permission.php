<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_add_remove_permission extends CI_Controller {
	public function __construct()
    {
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('database_model');
        $this->load->model('common_model');
        has_permission_employer();
    }
	public function index()
	{
        header('Content-Type: application/json');
        $PostArray = $this->input->post();

        $csrf_check = $this->common_model->csrfguard_validate_token($this->input->post('csrf_name'),$this->input->post('csrf_token'));

        if($csrf_check==true)
        {
            $this->ip_date = $this->common_model->get_date_ip();
            $permission = $this->database_model->get_all_records('permission','permission_id',array('role_id'=>$PostArray['role_id'],'ck_id'=>$PostArray['ck_id']),'permission_id ASC','')->row();
            if(empty($permission) && $PostArray['operation']=='Add'){
                $update_array= array(
                    'ck_id'=>$PostArray['ck_id'],
                    'module_name'=>$PostArray['module'],
                    'role_id'=>$PostArray['role_id'],
                    'method_name'=>$PostArray['method'],
                    'method_value'=>$PostArray['ck_val'],
                    'status'=>'1',
                    'created_at'=>$this->ip_date->cur_date,
                    'updated_at'=>$this->ip_date->cur_date,
                    'created_ip'=>$this->ip_date->ip,
                );
                $id= $this->database_model->save('permission',$update_array);
                $message = 'Permission has been Add successfully!';
            }else{
                $this->database_model->delete('permission',array('role_id'=>$PostArray['role_id'],'ck_id'=>$PostArray['ck_id']));
                $message = 'Permission has been removed successfully!';
            }

            $message=array('code'=>1,'message'=>$message);
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