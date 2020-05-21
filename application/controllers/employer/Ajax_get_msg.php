<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_get_msg extends CI_Controller {
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
            //conversion data
            $conversation = $this->database_model->custom_query("SELECT super_employer_id,team_employer_id FROM `tbl_employer_conversation` WHERE `conversation_id` = '".$PostArray['conversation_id']."' ORDER BY `conversation_id` ASC LIMIT 1")->row();


            //check sender is super employer or not
            if($this->session->userdata('employer_id')==$conversation->super_employer_id){
                $sender_id=$conversation->super_employer_id;
                $receiver_id=$conversation->team_employer_id;

            }else{
                $sender_id=$conversation->team_employer_id;
                $receiver_id=$conversation->super_employer_id;
            }

            //receiver data
            $receiver = $this->database_model->get_all_records('employer','employer_id,employer_photo,employer_name,chat_status,employer_about',array('employer_id'=>$receiver_id),'employer_id ASC',1)->row();
            $receiver_pic = check_image($receiver->employer_photo,'uploads/employer','size150');
            //sender and receiver image
            $sender_pic =check_image($this->session->userdata('employer_photo'),'uploads/employer','size150');
            //check only message or whole data
            if($PostArray['only_msg']==1){
                if($PostArray['old_msg']==1){
                    $where_str = 'tbl_employer_conversation.conversation_id = '.$PostArray['conversation_id'];
                    $limit_str = 'LIMIT '.$PostArray['offset'].' , '.$limit;
                }else{
                    $where_str = 'tbl_employer_conversation.conversation_id = '.$PostArray['conversation_id'].' AND tbl_employer_chat.employer_chat_id >'.$PostArray['last_delivered_msg_id'];
                    $limit_str = '';
                }
                $messages = $this->database_model->custom_query('SELECT * FROM ( SELECT tbl_employer_chat.*, s_employer.employer_name as sender_name, t_employer.employer_name as receiver_name FROM `tbl_employer_chat`
LEFT JOIN tbl_employer_conversation ON tbl_employer_chat.conversation_id = tbl_employer_conversation.conversation_id 
LEFT JOIN tbl_employer as s_employer ON s_employer.employer_id = tbl_employer_chat.sender_id
LEFT JOIN tbl_employer as t_employer ON t_employer.employer_id = tbl_employer_chat.receiver_id 

WHERE '.$where_str.' ORDER BY tbl_employer_chat.employer_chat_id DESC '.$limit_str.') as data_result
ORDER BY data_result.employer_chat_id ASC;')->result();
                $counter = count($messages);
                $html = '';
                foreach ($messages as $message){
                    $msg_time = $this->common_model->time_ago_in_php($message->created_at);
                    if($message->sender_id == $this->session->userdata('employer_id')){
                        //current user
                        $html .='<div class="chat" id="'.$message->employer_chat_id.'"> <div class="chat-avatar"> <a class="avatar m-0" data-toggle="tooltip" href="javascript:;" data-placement="bottom" title="'.$message->sender_name.'" data-original-title="'.$message->sender_name.'"> <img src="'.$sender_pic.'" alt="'.$message->sender_name.'" height="40" width="40" /> </a> </div> <div class="chat-body"> <div class="chat-content"> <p><span class="span-msg">'.$message->message.'</span><span class="span-msg-time">'.$msg_time.'</span></p> </div> </div> </div>';
                    }else{
                        //opposite user
                        $html .='<div class="chat chat-left" id="'.$message->employer_chat_id.'"> <div class="chat-avatar"> <a class="avatar m-0" data-toggle="tooltip" href="javascript:;" data-placement="bottom" title="'.$message->receiver_name.'" data-original-title="'.$message->receiver_name.'"> <img src="'.$receiver_pic.'" alt="'.$message->receiver_name.'" height="40" width="40" /> </a> </div> <div class="chat-body"> <div class="chat-content"> <p><span class="span-msg">'.$message->message.'</span><span class="span-msg-time">'.$msg_time.'</span></p> </div> </div> </div>';
                    }
                    $last_msg_id = $message->employer_chat_id;
                    $last_msg_txt = $message->message;
                    $last_msg_time = $msg_time;
                }
            }else{
                $offset = $PostArray['offset'];
                //html
                $html = '<div class="chat_navbar"><header class="chat_header d-flex justify-content-between align-items-center p-1"> <div class="vs-con-items d-flex align-items-center"> <div class="block d-lg-none mr-1"><i class="feather icon-menu font-large-1"></i></div><div class="avatar user-profile-toggle m-0 m-0 mr-1"> <img src="'.$receiver_pic.'" alt="" height="40" width="40" /> </div> <h6 class="mb-0">'.$receiver->employer_name.'</h6> <input type="hidden" name="receiver_id" id="receiver_id" value="'.$receiver->employer_id.'"></div> </header> </div>';

                $messages = $this->database_model->custom_query('SELECT * FROM ( SELECT tbl_employer_chat.*, s_employer.employer_name as sender_name, t_employer.employer_name as receiver_name FROM `tbl_employer_chat`
LEFT JOIN tbl_employer_conversation ON tbl_employer_chat.conversation_id = tbl_employer_conversation.conversation_id 
LEFT JOIN tbl_employer as s_employer ON s_employer.employer_id = tbl_employer_chat.sender_id
LEFT JOIN tbl_employer as t_employer ON t_employer.employer_id = tbl_employer_chat.receiver_id 

WHERE tbl_employer_conversation.conversation_id = '.$PostArray['conversation_id'].'  ORDER BY tbl_employer_chat.employer_chat_id DESC LIMIT '.$PostArray['offset'].' , '.$limit.' ) as data_result
ORDER BY data_result.employer_chat_id ASC;')->result();
                $counter = count($messages);
                //MSG LOOP
                $html .= '<div class="user-chats"> <div class="chats">';
                foreach ($messages as $message){
                    $msg_time = $this->common_model->time_ago_in_php($message->created_at);
                    if($message->sender_id == $this->session->userdata('employer_id')){
                        //current user
                        $html .='<div class="chat" id="'.$message->employer_chat_id.'"> <div class="chat-avatar"> <a class="avatar m-0" data-toggle="tooltip" href="javascript:;" data-placement="bottom" title="'.$message->sender_name.'" data-original-title="'.$message->sender_name.'"> <img src="'.$sender_pic.'" alt="'.$message->sender_name.'" height="40" width="40" /> </a> </div> <div class="chat-body"> <div class="chat-content"> <p><span class="span-msg">'.$message->message.'</span><span class="span-msg-time">'.$msg_time.'</span></p> </div> </div> </div>';
                    }else{
                        //opposite user
                        $html .='<div class="chat chat-left" id="'.$message->employer_chat_id.'"> <div class="chat-avatar"> <a class="avatar m-0" data-toggle="tooltip" href="javascript:;" data-placement="bottom" title="'.$message->receiver_name.'" data-original-title="'.$message->receiver_name.'"> <img src="'.$receiver_pic.'" alt="'.$message->receiver_name.'" height="40" width="40" /> </a> </div> <div class="chat-body"> <div class="chat-content"> <p><span class="span-msg">'.$message->message.'</span><span class="span-msg-time">'.$msg_time.'</span></p> </div> </div> </div>';
                    }
                    $last_msg_id = $message->employer_chat_id;
                }
                $html .="</div></div>";

                //button
                $html .='<div class="chat-app-form"> <form class="chat-app-input d-flex" id="msg_frm" action="javascript:void(0);"> <input type="text" class="form-control message mr-1 ml-50" id="msg_txt_box" placeholder="Type your message" data-emojiable="true" data-emoji-input="unicode"> <button type="submit" class="btn btn-primary send msg-send"><i class="fa fa-paper-plane-o d-lg-none"></i> <span class="d-none d-lg-block ">Send</span></button> </form> </div><input type="hidden" name="last_msg_id" id="last_msg_id" value="'.$last_msg_id.'"><script>window.emojiPicker = new EmojiPicker({ emojiable_selector: "[data-emojiable=true]", assetsPath: "' . base_url().'resources/app-assets/emoji_master/lib/img/", popupButtonClasses: "fa fa-smile-o" });
                    window.emojiPicker.discover();</script>';
            }


            //update the message as read flag
            $this->database_model->custom_query('UPDATE tbl_employer_chat SET flag=1 WHERE conversation_id = '.$PostArray['conversation_id'].' AND receiver_id='.$this->session->userdata('employer_id'));
            $totalcounter = $this->database_model->count_all('employer_chat',array('receiver_id'=>$this->session->userdata('employer_id'),'flag'=>0));

            $status_html = '<ul class="list-unstyled user-status mb-0">';
            if($receiver->chat_status == 1){
                $status_html .= '<li class="pb-50"> <fieldset> <div class="vs-radio-con vs-radio-success"> <input type="radio" name="chat_status" value="1" checked="checked"> <span class="vs-radio"> <span class="vs-radio--border"></span> <span class="vs-radio--circle"></span> </span> <span class="">Active</span> </div> </fieldset> </li>';
            }
            if($receiver->chat_status == 2){
                $status_html .='<li class="pb-50"> <fieldset> <div class="vs-radio-con vs-radio-danger"> <input type="radio" name="chat_status" value="2" checked="checked"> <span class="vs-radio"> <span class="vs-radio--border"></span> <span class="vs-radio--circle"></span> </span> <span class="">Do Not Disturb</span> </div> </fieldset> </li>';
            }
            if($receiver->chat_status == 3){
                $status_html .='<li class="pb-50"> <fieldset> <div class="vs-radio-con vs-radio-warning"> <input type="radio" name="chat_status" value="3" checked="checked"> <span class="vs-radio"> <span class="vs-radio--border"></span> <span class="vs-radio--circle"></span> </span> <span class="">Away</span> </div> </fieldset> </li>';
            }
            if($receiver->chat_status == 4){
                $status_html .='<li class="pb-50"> <fieldset> <div class="vs-radio-con vs-radio-secondary"> <input type="radio" name="chat_status" value="4" checked="checked"> <span class="vs-radio"> <span class="vs-radio--border"></span> <span class="vs-radio--circle"></span> </span> <span class="">Offline</span> </div> </fieldset> </li>';
            }
            $status_html .='</ul>';

            $message=array('code'=>1,'data_html'=>$html,'limit'=>$limit,'offset'=>$offset,'last_msg_id'=>$last_msg_id,'msgtime'=>$last_msg_time,'last_msg_txt'=>$last_msg_txt,'last_serve_counter'=>$counter,'totalcounter'=>$totalcounter,'receiver_chat_status'=>$receiver->chat_status,'receiver_employer_about'=>$receiver->employer_about,'receiver_photo'=>$receiver_pic,'receiver_name'=>$receiver->employer_name,'receiver_html'=>$status_html);
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