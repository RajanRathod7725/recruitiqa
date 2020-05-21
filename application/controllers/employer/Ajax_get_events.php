<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_get_events extends CI_Controller {
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

        $where = array('employer_id'=>$this->session->userdata('employer_id'));
        $result = $this->database_model->get_all_records('event','*',$where,'event_id DESC','','')->result();

        $defaultDate = date('Y-m-d');

        $events = array();
        foreach($result as $rs){

            $dataset['id'] = $rs->event_id;
            $dataset['title'] = $rs->event_name;
            if($rs->start_time != ""){
                $dataset['start'] = $rs->start_date."T".$rs->start_time;
            }else{
                $dataset['start'] = $rs->start_date;
            }
            if($rs->end_date != ""){
                $dataset['end'] = $rs->end_date;
                if($rs->end_time != ""){
                    $dataset['end'] .= "T".$rs->end_time;
                }
            }
            $events[] = $dataset;

        }

        $json = array('events'=>$events,'defaultDate'=>$defaultDate);
		echo json_encode($json);
		die();
	}
}

/* End of file ajax_delete.php */
/* Location: ./application/controllers/manage/ajax_delete.php */