<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_add_todo_status extends CI_Controller {
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
                'title'=>$PostArray['title'],
                'employer_id'=>$this->session->userdata('employer_id'),
                'created_at'=>$this->ip_date->cur_date,
                'updated_at'=>$this->ip_date->cur_date,
                'created_ip'=>$this->ip_date->ip,
            );
            $id= $this->database_model->save('todo_status',$update_array);

            $value_array = array(
                'table_id' => $id,
                'table_name' => 'tbl_todo_status',
                'activity' => '2',
                'modified_by'=> $this->session->userdata('employer_id'),
            );
            $this->database_model->insert_modified($value_array,$this->session->userdata('employer_id'));

            $status_html = '<div class="status-card" id="status_'.$id.'"> <div class="card-header p-0 todo-header"> <div class="row w-100"> <div class="col-md-10"> <span class="card-header-text font-medium-2" id="col_name_'.$id.'">'.$PostArray['title'].'</span> </div> <div class="col-md-2 pr-0" style="text-align:right;"> <a href="javascript:;" class="status_options font-medium-2" id="status_menu_'.$id.'"><i class="feather icon-more-horizontal"></i></a> </div> </div> </div> <ul class="sortable ui-sortable contact-ul" id="todo_status_'.$id.'" data-todo-status-id="'.$id.'">  </ul><div class="card-footer p-0"> <form class="add-task-frm w-100 p-1"> <a href="javascript:;" class="open-add-frm-task btn-icon btn waves-effect waves-light font-medium-3 w-100"><i class="feather icon-plus-square font-medium-4"></i> Add Task</a> <input class="form-control mb-1 list-input-task" type="text" name="name" placeholder="Enter task title..." autocomplete="off"> <div class="list-add-control-task "> <button class="save_list_task btn-icon btn btn-success waves-effect waves-light">Add task</button> <a class="cancel_list_task" href="javascript:;"><i class="feather icon-x-square font-large-2"></i></a> </div> </form> </div> </div><div class="status-card"> <div class="card-header p-0"> <form class="add-column-frm w-100 p-1"> <a href="javascript:;" class="open-add-frm btn-icon btn waves-effect waves-light font-medium-3 w-100"><i class="feather icon-plus-square font-medium-4"></i> Add Column</a> <input class="form-control mb-1 list-input" type="text" name="name" placeholder="Enter list title..." autocomplete="off"> <div class="list-add-control "> <button class="save_list btn-icon btn btn-success waves-effect waves-light">Add List</button> <a class="cancel_list" href="javascript:;"><i class="feather icon-x-square font-large-2"></i></a> </div> </form> </div>  </div>';
            $message=array('code'=>1,'status_html'=>$status_html);
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