<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_get_job_msg extends CI_Controller {
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
            $limit = 10;
            //sender and receiver image
            $sender_pic =check_image($this->session->userdata('employer_photo'),'uploads/employer','size150');
            //check only message or whole data
            if($PostArray['only_msg']==1){

                if($PostArray['old_msg']==1){
                    $where_str = '';
                    $limit_str = 'LIMIT '.$PostArray['offset'].' , '.$limit;
                }else{
                    $where_str = 'WHERE job_chat_id >'.$PostArray['last_delivered_msg_id'];
                    $limit_str = '';
                }

                $messages = $this->database_model->custom_query('SELECT * FROM (SELECT * FROM ((SELECT c.*, e.employer_name as username, e.employer_photo as user_photo FROM tbl_job_chat c JOIN tbl_employer e ON e.employer_id = c.user_id WHERE c.user_type = 1 AND c.job_id = '.$PostArray['job_id'].') UNION (SELECT c.*, r.recruiter_name as username, r.recruiter_photo as user_photo FROM tbl_job_chat c JOIN tbl_recruiter r ON r.recruiter_id = c.user_id WHERE c.user_type = 2 AND c.job_id = '.$PostArray['job_id'].') ) as data_result '.$where_str.' ORDER BY data_result.job_chat_id DESC '.$limit_str.') as temp ORDER BY temp.job_chat_id ASC')->result();
                $counter = count($messages);

                $html = '';
                foreach ($messages as $message){
                    $msg_time = $this->common_model->time_ago_in_php($message->created_at);
                    if($message->user_id == $this->session->userdata('employer_id') && $message->user_type ==1){
                        //current user
                        $html .='<div class="chat" id="'.$message->job_chat_id.'"> <div class="chat-avatar"> <a class="avatar m-0" data-toggle="tooltip" href="javascript:;" data-placement="bottom" title="'.$message->username.'" data-original-title="'.$message->username.'"> <img src="'.$sender_pic.'" alt="'.$message->username.'" height="40" width="40" /> </a> </div> <div class="chat-body"> <div class="chat-content"> <p><span class="span-msg">'.$message->message.'</span><span class="span-msg-time">'.$msg_time.'</span></p> </div> </div> </div>';
                    }else{
                        //opposite user
                        if($message->user_type ==1){
                            $receiver_pic = check_image($message->user_photo,'uploads/employer','size150');
                        }else{
                            $receiver_pic = check_image($message->user_photo,'uploads/recruiter','size150');
                        }

                        $html .='<div class="chat chat-left" id="'.$message->job_chat_id.'"> <div class="chat-avatar"> <a class="avatar m-0" data-toggle="tooltip" href="javascript:;" data-placement="bottom" title="'.$message->username.'" data-original-title="'.$message->username.'"> <img src="'.$receiver_pic.'" alt="'.$message->username.'" height="40" width="40" /> </a> </div> <div class="chat-body"> <div class="chat-content"> <p><span class="span-msg">'.$message->message.'</span><span class="span-msg-time">'.$msg_time.'</span></p> </div> </div> </div>';
                    }
                    $last_msg_id = $message->job_chat_id;
                    if($message->user_id == $this->session->userdata('employer_id')){
                        $last_msg_txt = '<b>You:</b>'.$message->message;
                    }else{
                        $last_msg_txt = '<b>'.$message->username.': </b>'.$message->message;
                    }
                    $last_msg_time = $msg_time;

                    $this->database_model->custom_query('INSERT INTO tbl_job_chat_flag VALUES ('.$message->job_chat_id.','.$this->session->userdata('employer_id').',1) ON DUPLICATE KEY UPDATE job_chat_id='.$message->job_chat_id.',user_id='.$this->session->userdata('employer_id').',user_type=1');
                }
            }else{

                $messages = $this->database_model->custom_query('SELECT * FROM (SELECT * FROM ((SELECT c.*, e.employer_name as username, e.employer_photo as user_photo FROM tbl_job_chat c JOIN tbl_employer e ON e.employer_id = c.user_id WHERE c.user_type = 1 AND c.job_id = '.$PostArray['job_id'].') UNION (SELECT c.*, r.recruiter_name as username, r.recruiter_photo as user_photo FROM tbl_job_chat c JOIN tbl_recruiter r ON r.recruiter_id = c.user_id WHERE c.user_type = 2 AND c.job_id = '.$PostArray['job_id'].') ) as data_result ORDER BY data_result.job_chat_id DESC LIMIT '.$PostArray['offset'].' , '.$limit.' ) as temp ORDER BY temp.job_chat_id ASC')->result();
                $counter = count($messages);
                $offset = $PostArray['offset'];
                //html
                $html = '';
                //MSG LOOP
                $html .= '<div class="user-chats" style="height: calc(var(--vh, 1vh) * 100 - 18.7rem) !important; "> <div class="chats">';
                foreach ($messages as $message){
                    $msg_time = $this->common_model->time_ago_in_php($message->created_at);
                    if($message->user_id == $this->session->userdata('employer_id') && $message->user_type ==1){
                        //current user
                        $html .='<div class="chat" id="'.$message->job_chat_id.'"> <div class="chat-avatar"> <a class="avatar m-0" data-toggle="tooltip" href="javascript:;" data-placement="bottom" title="'.$message->username.'" data-original-title="'.$message->username.'"> <img src="'.$sender_pic.'" alt="'.$message->username.'" height="40" width="40" /> </a> </div> <div class="chat-body"> <div class="chat-content"><p><span class="span-msg">'.$message->message.'</span><span class="span-msg-time">'.$msg_time.'</span></p></div> </div> </div>';
                    }else{
                        //opposite user
                        if($message->user_type ==1){
                            $receiver_pic = check_image($message->user_photo,'uploads/employer','size150');
                        }else{
                            $receiver_pic = check_image($message->user_photo,'uploads/recruiter','size150');
                        }
                        $html .='<div class="chat chat-left" id="'.$message->job_chat_id.'"> <div class="chat-avatar"> <a class="avatar m-0" data-toggle="tooltip" href="javascript:;" data-placement="bottom" title="'.$message->username.'" data-original-title="'.$message->username.'"> <img src="'.$receiver_pic.'" alt="'.$message->username.'" height="40" width="40" /> </a> </div> <div class="chat-body"> <div class="chat-content"> <p><span class="span-msg">'.$message->message.'</span><span class="span-msg-time">'.$msg_time.'</span></p> </div> </div> </div>';
                    }
                    $last_msg_id = $message->job_chat_id;
                    if($message->user_id == $this->session->userdata('employer_id')){
                        $last_msg_txt = '<b>You:</b>'.$message->message;
                    }else{
                        $last_msg_txt = '<b>'.$message->username.':</b>'.$message->message;
                    }
                    $last_msg_time = $msg_time;

                    $this->database_model->custom_query('INSERT INTO tbl_job_chat_flag VALUES ('.$message->job_chat_id.','.$this->session->userdata('employer_id').',1) ON DUPLICATE KEY UPDATE job_chat_id='.$message->job_chat_id.',user_id='.$this->session->userdata('employer_id').',user_type=1');
                }
                $html .="</div></div>";

                //button
                $html .='<div class="chat-app-form"> <form class="chat-app-input d-flex" id="msg_frm" action="javascript:void(0);"> <input type="text" class="form-control message mr-1 ml-50" id="msg_txt_box" placeholder="Type your message" data-emojiable="true" data-emoji-input="unicode"><button type="submit" class="btn btn-primary send msg-send"><i class="fa fa-paper-plane-o d-lg-none"></i> <span class="d-none d-lg-block ">Send</span></button></form> </div><input type="hidden" name="last_msg_id" id="last_msg_id" value="'.$last_msg_id.'"><script>window.emojiPicker = new EmojiPicker({ emojiable_selector: "[data-emojiable=true]", assetsPath: "' . base_url().'resources/app-assets/emoji_master/lib/img/", popupButtonClasses: "fa fa-smile-o" }); window.emojiPicker.discover();</script>';
            }

            //current job wise
            $cj_totalcounter = $this->database_model->get_all_records('job_chat','GROUP_CONCAT(job_chat_id) as ids,COUNT(job_chat_id) as pre_count' ,array('job_id'=>$PostArray['job_id']),'job_chat_id ASC',1)->row();
            if($cj_totalcounter->ids!=''){
                $cj_totaljobcounter = $this->database_model->custom_query('SELECT COUNT(job_chat_id) as count FROM tbl_job_chat_flag WHERE user_id= '.$this->session->userdata('employer_id').' AND user_type = 1 AND job_chat_id IN ('.$cj_totalcounter->ids.')')->row()->count;
            }

            //total job counter
            $total_job =  $this->database_model->get_all_records('job_conversation','GROUP_CONCAT(job_id) as job_ids' ,array('user_id'=>$this->session->userdata('employer_id'),'user_type'=>1),'job_conversation_id ASC',1)->row();

            if($total_job->job_ids!=''){
                $totalcounter =$this->database_model->custom_query('SELECT GROUP_CONCAT(job_chat_id) as ids,COUNT(job_chat_id) as pre_count FROM tbl_job_chat WHERE job_id IN ('.$total_job->job_ids.')')->row();
                if($totalcounter->ids){
                    $totaljobcounter = $this->database_model->custom_query('SELECT COUNT(job_chat_id) as count FROM tbl_job_chat_flag WHERE user_id= '.$this->session->userdata('employer_id').' AND user_type = 1 AND job_chat_id IN ('.$totalcounter->ids.')')->row()->count;
                }
            }


            $message=array('code'=>1,'data_html'=>$html,'limit'=>$limit,'offset'=>$offset,'last_msg_id'=>$last_msg_id,'msgtime'=>$last_msg_time,'last_msg_txt'=>$last_msg_txt,'last_serve_counter'=>$counter,'current_job_counter'=>$cj_totalcounter->pre_count - $cj_totaljobcounter,'total_job_counter'=>$totalcounter->pre_count - $totaljobcounter);
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