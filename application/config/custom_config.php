<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User:
 * Date: 2/12/16
 * Time: 5:06 PM
 */
define('JS_CSS_VERSION','?v=0.0.27');

/*$config['JS_VERSION'] = '?v='.time();
$config['CSS_VERSION'] = '?v='.time();*/
$config['permission'] = array(
    'dashboard' =>array(
        'index' => array('1','2','3','4','5'),
    ),
    'change_pass'=>array(
        'index'=>array('1','2','3','4','5'),
        'update'=>array('1','2','3','4','5'),
    ),
    'job_type'=>array(
        'index' => array('1'),
        'add' => array('1'),
        'insert' => array('1'),
        'edit' => array('1'),
        'update' => array('1'),
    ),
    'job_industry'=>array(
        'index' => array('1'),
        'add' => array('1'),
        'insert' => array('1'),
        'edit' => array('1'),
        'update' => array('1'),
    ),
    'reject_reason'=>array(
        'index' => array('1'),
        'add' => array('1'),
        'insert' => array('1'),
        'edit' => array('1'),
        'update' => array('1'),
    ),
    'black_list'=>array(
        'index' => array('1'),
        'add' => array('1'),
        'insert' => array('1'),
        'edit' => array('1'),
        'update' => array('1'),
    ),
    'search_radius'=>array(
        'index' => array('1'),
        'add' => array('1'),
        'insert' => array('1'),
        'edit' => array('1'),
        'update' => array('1'),
    ),
    'role'=>array(
        'add' => array('1','2'),
        'insert' => array('1','2'),
        'index' => array('1','2'),
        'edit' => array('1','2'),
        'update' => array('1','2'),
        'permission_add' => array('1','2'),
    ),
    'settings'=>array(
        'index' => array('1'),
        'edit' => array('1'),
        'update' => array('1'),
    ),
    'mail_templates'=>array(
        'index' => array('1','2'),
        'add' => array('1','2'),
        'insert' => array('1','2'),
        'edit' => array('1','2'),
        'update' => array('1','2'),
    ),
    'employer'=>array(
        'index' => array('1','2','4'),
        'add' => array('1','2','4'),
        'insert' => array('1','2','4'),
        'edit' => array('1','2','4'),
        'update' => array('1','2','4'),
        'login_to_employer' => array('1','2','4'),
    ),
    'verify_employer'=>array(
        'index'=>array('1','2','5'),
    ),
    'recruiter'=>array(
        'index' => array('1','4','5'),
        'add' => array('1','4','5'),
        'insert' => array('1','4','5'),
        'edit' => array('1','4','5'),
        'update' => array('1','4','5'),
        'login_to_recruiter' => array('1','2','4'),
    ),
    'verify_recruiter'=>array(
        'index'=>array('1','3'),
    ),
    'candidate'=>array(
        'index' => array('1','2','3','4'),
        'edit' => array('1','2','3','4'),
        'update' => array('1','2','3','4'),
        'information' => array('1','2','3','4'),
        'export_candidate' => array('1','2','3','4','5'),
    ),
    'job'=>array(
        'index' => array('1','2','3','4','5'),
        'add' => array('1','2','3','4','5'),
        'insert' => array('1','2','3','4','5'),
        'edit' => array('1','2','3','4','5'),
        'update' => array('1','2','3','4','5'),
        'information' => array('1','2','3','4','5'),
        'add_candidate' => array('1','2','3','4','5'),
        'insert_candidate' => array('1','2','3','4','5'),
        'candidate_list' => array('1','2','3','4','5'),
        'candidate_info' => array('1','2','3','4','5'),
    ),
    'calendar'=>array(
        'index' => array('1','2','3','4','5'),
        'add' => array('1','2','3','4','5'),
        'insert' => array('1','2','3','4','5'),
        'edit' => array('1','2','3','4','5'),
        'update' => array('1','2','3','4','5'),
        'information' => array('1','2','3','4','5'),
        'add_candidate' => array('1','2','3','4','5'),
        'insert_candidate' => array('1','2','3','4','5'),
        'candidate_list' => array('1','2','3','4','5'),
        'candidate_info' => array('1','2','3','4','5'),
    ),
    'team'=>array(
        'add' => array('1','2'),
        'insert' => array('1','2'),
        'index' => array('1','2'),
        'edit' => array('1','2'),
        'update' => array('1','2'),
    ),
    'team_chat'=>array(
        'index' => array('1','2','5'),
    ),
    'job_chat'=>array(
        'index' => array('1','2','3'),
    ),
    'email_request'=>array(
        'index' => array('1'),
    ),
    'subscription_request'=>array(
        'index' => array('1'),
    ),
    'subscription_request_cust'=>array(
        'index' => array('1'),
    ),
    'todo'=>array(
        'index' => array('1','2','3','5'),
        'add' => array('1','2','3'),
        'insert' => array('1','2','3'),
        'edit' => array('1','2','3'),
        'update' => array('1','2','3'),
    ),
    'hired_candidate'=>array(
        'index' => array('1','2','3','5'),
        'information' => array('1','2','3','5'),
    ),
    'subscription'=>array(
        'index' => array('1','2','3','5'),
        'add' => array('1','2'),
        'insert' => array('1','2'),
        'edit' => array('1'),
        'update' => array('1'),
    ),
    'emp_subscription'=>array(
        'index' => array('1'),
    ),
    'profile'=>array(
        'edit' => array('1','2','3','4','5'),
        'update' => array('1','2','3','4','5'),
    ),
    'forgot'=>array(
        'index' => array('2','3'),
        'check' => array('2','3'),
    ),
    'recover_password'=>array(
        'index' => array('2','3'),
        'insert' => array('2','3'),
    ),
    'ajax_delete'=>array(
        'index' => array('1','2','3'),
    ),
    'ajax_status'=>array(
        'index' => array('1','2','3'),
    ),
    'ajax_todo_status'=>array(
        'index' => array('1','2','3'),
    ),
    'ajax_job_status'=>array(
        'index' => array('1','2','4','5'),
    ),
    'ajax_candidate_status'=>array(
        'index' => array('1','2','3'),
    ),
    'ajax_candidate_rating'=>array(
        'index' => array('1','2','3'),
    ),
    'ajax_get_candidate_emails'=>array(
        'index' => array('1','2','3'),
    ),
    'ajax_send_candidate_emails'=>array(
        'index' => array('1','2','3'),
    ),
    'ajax_get_candidate_details'=>array(
        'index' => array('1','2','3'),
    ),
    'ajax_add_note'=>array(
        'index' => array('1','2','3'),
    ),
    'ajax_note_delete'=>array(
        'index' => array('1','2','3'),
    ),
    'ajax_add_todo_status'=>array(
        'index' => array('1','2','3'),
    ),
    'ajax_add_todo_task'=>array(
        'index' => array('1','2','3'),
    ),
    'ajax_move_task'=>array(
        'index' => array('1','2','3'),
    ),
    'ajax_task_action'=>array(
        'index' => array('1','2','3'),
    ),
    'ajax_delete_location'=>array(
        'index' => array('1','2','3'),
    ),
    'ajax_add_remove_permission'=>array(
        'index' => array('1','2'),
    ),
    'ajax_email_status'=>array(
        'index' => array('1','2'),
    ),
    'ajax_mail_type_change'=>array(
        'index' => array('1','2'),
    ),
    'ajax_add_batch_size'=>array(
        'index' => array('1','2','4','5'),
    ),
    'ajax_activate_account'=>array(
        'index' => array('1','4'),
    ),
    'ajax_get_mail'=>array(
        'index' => array('1','2','5'),
    ),
    'ajax_approve_subscription'=>array(
        'index' => array('1','2','5'),
    ),
    'ajax_approve_cust_subscription'=>array(
        'index' => array('1','2','5'),
    ),
    'ajax_custom_sub_req'=>array(
        'index' => array('1','2'),
    ),
    'ajax_get_msg'=>array(
        'index' => array('1','2','5'),
    ),
    'ajax_send_msg'=>array(
        'index' => array('1','2','5'),
    ),
    'ajax_get_job_msg'=>array(
        'index' => array('1','2','5'),
    ),
    'ajax_send_job_msg'=>array(
        'index' => array('1','2','5'),
    ),
    'ajax_get_msg_counter'=>array(
        'index' => array('1','2','3','5'),
    ),
    'ajax_add_credit'=>array(
        'index' => array('1'),
    ),
    'ajax_up_about_status'=>array(
        'index' => array('1','2','4'),
    ),
    'ajax_get_events'=>array(
        'index' => array('1'),
    ),
    'ajax_save_event'=>array(
        'index' => array('1'),
    ),
);

$config['roles_key_value'] = array('1'=>'Admin','2'=>'Recruiter','3'=>'Employer','4'=>'Sub Admin','5'=>'Team Member');
$config['candidate_status'] = array('1'=>'Review','2'=>'Contacted','3'=>'Rejected','4'=>'Interested','5'=>'Not Interested','6'=>'Interview','7'=>'Hired');
$config['candidate_status_color'] = array('1'=>'primary','2'=>'info','3'=>'danger','4'=>'interested','5'=>'notinterested','6'=>'warning','7'=>'success');
/*Algolia Location*/
/*defined('APPID','plJES61WLXRM');
defined('APIKEY','a17570e937aa49c6723f30c9c28645c0');*/

