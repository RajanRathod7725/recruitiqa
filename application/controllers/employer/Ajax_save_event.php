<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_save_event extends CI_Controller {
	public function __construct()
    {
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('database_model');
        $this->load->model('common_model');
        // has_permission();
    }
	public function index()
	{  

        $data = array();

        $data['employer_id'] = $this->session->userdata('employer_id');
        $data['employer_type'] = $this->session->userdata('employer_type');
        $data['event_name'] = $_POST['form_data']['cal_event_name'];
        $data['event_description'] = $_POST['form_data']['cal_event_description']; 
        $data['start_date'] = date('Y-m-d', strtotime($_POST['form_data']['cal_event_start_date']));         
        $data['is_full_day_event'] = $_POST['form_data']['cal_event_full_day']; 

        if($data['is_full_day_event'] == "1"){
        
            $data['start_time'] = ""; 
            $data['end_date'] = ""; 
            $data['end_time'] = ""; 
        
        }else{
            
            $data['start_time'] = $_POST['form_data']['cal_event_start_time'];
            if(strlen($data['start_time']) == "4"){
                $data['start_time'] = "0".$data['start_time'].":00";
            }else{
                $data['start_time'] = "0".$data['start_time'];
            }

            $data['end_date'] = date('Y-m-d', strtotime($_POST['form_data']['cal_event_end_date'])); 

            $data['end_time'] = $_POST['form_data']['cal_event_end_time']; 
            if(strlen($data['end_time']) == "4"){
                $data['end_time'] = "0".$data['end_time'].":00";
            }else{
                $data['end_time'] = "0".$data['end_time'];
            }
        }

        if($_POST['form_data']['cal_event_reminder_date'] != ""){
            $data['reminder_date'] = date('Y-m-d', strtotime($_POST['form_data']['cal_event_reminder_date'])); 
        }
        $data['reminder_time'] = $_POST['form_data']['cal_event_reminder_time']; 
        $data['url'] = $_POST['form_data']['cal_event_url'];

        if($_POST['form_data']['action'] == "insert"){
            
            $event_id = $this->database_model->save('event',$data);
            
            if($event_id > 0){
                $this->session->set_flashdata('notification','Event Created Successfully');
                $json = array('status'=>1,'msg'=>'Event Created Successfully');
            }else{
                $json = array('status'=>0,'msg'=>'Failed to Create Event, Something went wrong !');
            }

        }else if($_POST['form_data']['action'] == "update"){

            if($this->database_model->update('event',$data,array('event_id'=>$_POST['form_data']['event_id']))){
                $json = array('status'=>1,'msg'=>'Event Updated Successfully');
            }else{
                $json = array('status'=>0,'msg'=>'Failed to Update Event, Something went wrong !');
            }

        }


        // echo "<pre>";
        // print_r($json);
        // exit;

		echo json_encode($json);
		die();
        
	}
}

/* End of file ajax_delete.php */
/* Location: ./application/controllers/manage/ajax_delete.php */