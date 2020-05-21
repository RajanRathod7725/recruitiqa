<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_add_note extends CI_Controller {
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
        $csrf_check = $this->common_model->csrfguard_validate_token($this->input->post('csrf_name'),$this->input->post('csrf_token'));

        if($csrf_check==true)
        {
            $this->ip_date = $this->common_model->get_date_ip();

            $PostArray = $this->input->post();
            $update_array= array(
                'candidate_id'=>$PostArray['candidate_id'],
                'note'=>$PostArray['note'],
                'created_at'=>$this->ip_date->cur_date,
                'updated_at'=>$this->ip_date->cur_date,
                'created_ip'=>$this->ip_date->ip,
            );
            $this->database_model->save('candidate_note',$update_array);

            $value_array = array(
                'table_id' => $PostArray['candidate_id'],
                'table_name' => 'tbl_candidate_note',
                'activity' => '2',
                'modified_by'=> $this->employer_id,
            );
            $this->database_model->insert_modified($value_array,$this->employer_id);

            /*candidate_note logic*/
            $notes=$this->database_model->get_all_records('candidate_note','*',array('candidate_note.status !='=>'3','candidate_id'=>$PostArray['candidate_id']),'candidate_note.note_id','DESC','')->result();

            $note_html = '<div class="col-md-12"> <div style="text-align: right;"><input type="button" value="Add Note" class="btn btn-primary mt-1" id="add_note"></div> <ul class="not-ul">';
            foreach ($notes as $note){
                $note_html .='<li class="not-li" id="data_'.$note->note_id.'"> <div class="row"> <div class="col-md-10"> <h6>'.$note->note.'</h6> <p>'.date('d M, Y H:i A',strtotime($note->created_at)).'</p> </div> <div class="col-md-2"> <div class="mt-1"><a href="javascript:;" class="delete-note pt-1"><i class="feather icon-trash font-medium-3"></i></a></div> </div> </div> </li>';
            }
            $note_html .='</ul></div>';
            $message=array('code'=>1,'note_html'=>$note_html);
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