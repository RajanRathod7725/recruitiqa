<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_add_todo_task extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('database_model');
        $this->load->model('employer/employer_model');
        $this->load->model('common_model');
        has_permission_employer();
    }
    public function index()
    {
        header('Content-Type: application/json');
        $csrf_check = $this->common_model->csrfguard_validate_token($this->input->post('csrf_name'),$this->input->post('csrf_token'));

        if($csrf_check==true)
        {
            $this->ip_date = $this->common_model->get_date_ip();

            $PostArray = $this->input->post();
            $update_array= array(
                'description'=>$PostArray['title'],
                'todo_status_id'=>$PostArray['status_id'],
                'todo_status'=>'0',
                'created_at'=>$this->ip_date->cur_date,
                'updated_at'=>$this->ip_date->cur_date,
                'created_ip'=>$this->ip_date->ip,
            );
            $id= $this->database_model->save('todo',$update_array);

            $value_array = array(
                'table_id' => $id,
                'table_name' => 'tbl_todo',
                'activity' => '2',
                'modified_by'=> $this->session->userdata('employer_id'),
            );
            $this->database_model->insert_modified($value_array,$this->session->userdata('employer_id'));
            $task_html = '<li class="text-row ui-sortable-handle" data-todo-id="'.$id.'" id="todo_'.$id.'"> <div class="row"> <div class="col-md-10 pr-0"><span class="font-medium-1" id="todo_task_'.$id.'"> '.$PostArray['title'].' </span></div><div class="col-md-2"> <a href="javascript:;" class="task_options font-medium-1" id="sub_menu_'.$id.'"><i class="feather icon-more-horizontal"></i></a></div> </div> </li>';
            $message=array('code'=>1,'task_html'=>$task_html);
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