<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Subscription_cron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/admin_model');
        $this->load->model('database_model');
        $this->load->model('common_model');
    }
    public function index()
    {
        $rank_log_string = '';
        $datetime = date('Y-m-d H:i:s');
        //GETTING OLD FLAG ID

        $flagid=$this->database_model->get_all_records('subscription_flag','fid',array(),'','')->row();
        $current_flag_id=$flagid->fid;

        //1. UPDATE THE SUBSCRIPTION TO 1
        $this->database_model->update('employer_subscription',array('subscription_status'=>'1'),array('end_date'=>date('Y-m-d')));

        $next_flag_id = $flagid->fid + 1;
        //2 UPDATE FLAGID
        $this->database_model->update('subscription_flag',array('fid'=>$next_flag_id,'currentdate'=>$datetime),array('fid'=>$current_flag_id));

        echo "Success";
    }
}