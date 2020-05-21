<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_get_single_event extends CI_Controller {
	public function __construct()
    {
		parent::__construct();
		$this->load->model('database_model');
        // has_permission();
    }
	public function index()
	{

        $where = array('event_id'=>$_POST['event_id']);
        $result = $this->database_model->get_all_records('event','*',$where,'event_id DESC','','')->row();

        $result->start_date = date('d M, Y', strtotime($result->start_date));
        if($result->end_date != ""){
            $result->end_date = date('d M, Y', strtotime($result->end_date));
        }

        $json = array('event'=>$result);
		echo json_encode($json);
		die();
	}
}

/* End of file ajax_delete.php */
/* Location: ./application/controllers/manage/ajax_delete.php */