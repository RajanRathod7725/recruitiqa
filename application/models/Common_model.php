<?php
class Common_model extends CI_Model
{
    public function __construct()
    {
        $this->lang->load("english_lang", "english");
        $CI = &get_instance();
        $CI->unread_e_msg =$this->database_model->count_all('employer_chat',array('receiver_id'=>$this->session->userdata('employer_id'),'flag'=>0));

        if($this->session->userdata('employer_type')==2){
            $total_job =  $this->database_model->get_all_records('job_conversation','GROUP_CONCAT(job_id) as job_ids' ,array('user_id'=>$this->session->userdata('employer_id'),'user_type'=>1),'job_conversation_id ASC',1)->row();

            if($total_job->job_ids!='') {
                $totalcounter = $this->database_model->custom_query('SELECT GROUP_CONCAT(job_chat_id) as ids,COUNT(job_chat_id) as pre_count FROM tbl_job_chat WHERE job_id IN (' . $total_job->job_ids . ')')->row();
                if($totalcounter->ids!=''){
                    $totaljobcounter = $this->database_model->custom_query('SELECT COUNT(job_chat_id) as count FROM tbl_job_chat_flag WHERE user_id= ' . $this->session->userdata('employer_id') . ' AND user_type = 1 AND job_chat_id IN (' . $totalcounter->ids . ')')->row()->count;
                    $CI->unread_j_msg = $totalcounter->pre_count - $totaljobcounter;
                }
            }
        }else{
            //recruiter
            if($this->session->userdata('recruiter_id')>0) {
                $total_job = $this->database_model->get_all_records('job_conversation', 'GROUP_CONCAT(job_id) as job_ids', array('user_id' => $this->session->userdata('recruiter_id'), 'user_type' => 2), 'job_conversation_id ASC', 1)->row();
                if($total_job->job_ids!='') {
                    $totalcounter = $this->database_model->custom_query('SELECT GROUP_CONCAT(job_chat_id) as ids,COUNT(job_chat_id) as pre_count FROM tbl_job_chat WHERE job_id IN (' . $total_job->job_ids . ')')->row();
                    if($totalcounter->ids!=''){
                        $totaljobcounter = $this->database_model->custom_query('SELECT COUNT(job_chat_id) as count FROM tbl_job_chat_flag WHERE user_id= ' . $this->session->userdata('recruiter_id') . ' AND user_type = 2 AND job_chat_id IN (' . $totalcounter->ids . ')')->row()->count;
                        $CI->unread_j_msg = $totalcounter->pre_count - $totaljobcounter;
                    }
                }
            }
        }
    }
    //MAIL SENDING
    function send_mail($to_email,$from_email,$from_text,$subject,$message,$replyto=''){
        $config = Array(
            'useragent' => 'CodeIgniter',
            'protocol' => 'http',
            'mailpath' => '/usr/sbin/sendmail',
            'smtp_host' => @$this->site_setting->smtp_host,
            'smtp_user' => @$this->site_setting->site_smtp_email,
            'smtp_pass' => @$this->site_setting->site_smtp_pwd,
            'smtp_port' => @$this->site_setting->smtp_port,
            'smtp_timeout' =>15,
            'wordwrap' => TRUE,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'validate' =>FALSE,
            'priority' => 3,
            'newline' => "\r\n",
            'bcc_batch_mode' => FALSE,
            'bcc_batch_size' => 200,
            //'smtp_crypto' => 'ssl',
        );
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from($from_email,$from_text);
        if($replyto!='')
            $this->email->reply_to($replyto,'');
        $this->email->to($to_email);
        $this->email->subject($subject);   // should be subject as required and use admin message file to write subject so we can use multi language
        $this->email->message($message);
        return $this->res=$this->email->send();
        /*echo ">>".$this->res=$this->email->send();
        echo "<br/> >>".$this->email->print_debugger();
        exit;*/
    }

    //SEND MAIL TO MULTIPLE USER
    function send_mail_multiple_user($to_email,$from_email,$from_text,$subject,$message,$replyto='',$attachment=''){
        $config = Array(
            'useragent' => 'CodeIgniter',
            'protocol' => 'http',
            'mailpath' => '/usr/sbin/sendmail',
            'smtp_host' => @$this->site_setting->smtp_host,
            'smtp_user' => @$this->site_setting->site_smtp_email,
            'smtp_pass' => @$this->site_setting->site_smtp_pwd,
            'smtp_port' => @$this->site_setting->smtp_port,
            'smtp_timeout' =>15,
            'wordwrap' => TRUE,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'validate' =>FALSE,
            'priority' => 3,
            'newline' => "\r\n",
            'bcc_batch_mode' => FALSE,
            'bcc_batch_size' => 200,
            //'smtp_crypto' => 'ssl',
        );
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from($from_email,$from_text);
        if($replyto!='')
            $this->email->reply_to($replyto,'');
        $this->email->to($to_email);
        $this->email->subject($subject);   // should be subject as required and use admin message file to write subject so we can use multi language
        $this->email->message($message);
        if($attachment!='') {
            $this->email->attach($attachment);
        }
        return $this->res=$this->email->send();
        /*echo ">>".$this->res=$this->email->send();
        echo "<br/> >>".$this->email->print_debugger();
        exit;*/
    }

    function send_mail_multiple_user1($to_email,$from_email,$from_text,$subject,$message,$replyto='',$attachment=''){

        $config = Array(
            'useragent' => 'CodeIgniter',
            'protocol' => 'smtp',
            'mailpath' => '/usr/sbin/sendmail',
            'smtp_host' => @$this->site_setting->smtp_host,
            'smtp_user' => @$this->site_setting->site_smtp_email,
            'smtp_pass' => @$this->site_setting->site_smtp_pwd,
            'smtp_port' => @$this->site_setting->smtp_port,
            'smtp_timeout' =>15,
            'wordwrap' => TRUE,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'validate' =>FALSE,
            'priority' => 3,
            'newline' => "\r\n",
            'bcc_batch_mode' => FALSE,
            'bcc_batch_size' => 200,
            //'smtp_crypto' => 'ssl',
        );

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from($from_email,$from_text);
        if($replyto!='')
            $this->email->reply_to($replyto,'');
        $this->email->to($to_email);
        $this->email->subject($subject);   // should be subject as required and use admin message file to write subject so we can use multi language
        $this->email->message($message);
        if($attachment!='') {
            $this->email->attach($attachment);
        }
        return $this->res=$this->email->send();
        /*echo ">>".$this->res=$this->email->send();
        echo "<br/> >>".$this->email->print_debugger();
        exit;*/
    }

    //CUSTOM PHP PAGINATION
    public function get_pagination($suffix_array,$base_url,$total_records,$limit,$uri_segment)
    {
        // generate pagination
        $this->load->library('pagination');
        if(count($suffix_array) > 0){
            $config['suffix'] = "?".http_build_query($suffix_array);
        }
        $config['base_url'] =$base_url;
        $config['total_rows'] = $total_records;
        $config['per_page'] = $limit;
        $config['uri_segment'] = $uri_segment;
        $config['use_page_numbers'] = false;
        $config['num_links'] = 5;
        //config for bootstrap pagination class integration
        $config['full_tag_open'] = '<ul class="pagination justify-content-right mt-2">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = 'Prev';
        $config['prev_tag_open'] = '<li class="page-item prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li class="page-item next">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active" aria-current="page"><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['next_tag_disable_open'] = '<li class="page-item disabled">';
        $config['prev_tag_disable_open'] = '<li class="page-item disabled">';
        $config['display_prev_link'] = true;
        $config['display_next_link'] = true;
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

    //CHECK USER EXIST/ CHECK LOGIN
    function check_login($table, $where_array)
    {
        $query = $this->db->get_where($table, $where_array);
        return $query->row_array();
    }

    //CSRF TOKEN GENERATION
    function csrfguard_generate_token($unique_form_name)
    {
        if (function_exists("hash_algos") and in_array("sha512", hash_algos())) {
            $token = hash("sha512", mt_rand(0, mt_getrandmax()));
        } else {
            $token = ' ';
            for ($i = 0; $i < 128; ++$i) {
                $r = mt_rand(0, 35);
                if ($r < 26) {
                    $c = chr(ord('a') + $r);
                } else {
                    $c = chr(ord('0') + $r - 26);
                }
                $token .= $c;
            }
        }
        $this->store_in_session($unique_form_name, $token);

        return $token;
    }

    //CSRF VALIDATE TOKEN
    function csrfguard_validate_token($unique_form_name,$token_value)
    {
        if($unique_form_name=='')
            return false;
        $token=$this->get_from_session($unique_form_name);
        if ($token===false)
        {
            $result=false;
        }
        elseif ($token===$token_value)
        {
            $result=true;
        }
        else
        {
            $result=false;
        }
        $this->unset_session($unique_form_name);
        return $result;
    }

    //STORE IN SESSION/ SET SESSION
    function store_in_session($key,$value)
    {
        $this->session->set_userdata($key,$value);
    }

    //UNSET SESSION
    function unset_session($key)
    {
        $this->session->unset_userdata($key); //kishan
    }

    //GET SESSION
    function get_from_session($key)
    {
        if ($this->session->userdata($key)!='')
        {
            return $this->session->userdata($key);
        }
        else
        {
            return false;
        }
    }

    //GENERATE TOKEN AS REQUIRE LENGTH
    function generateToken($length = 40) {
        $characters = '0123456789';
        $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters)-1;
        $password = '';

        for ($i = 0; $i < $length; $i++) 				 //select some random characters
        {
            $password .= $characters[mt_rand(0, $charactersLength)];
        }

        return $password;
    }

    //GET DATE/TIME
    function get_date_ip()
    {
        return $this->db->query('SELECT INET_ATON("'.$this->input->ip_address().'") AS ip,  "'.date('Y-m-d H:i:s', time()).'" AS cur_date ')->row();
    }

    //FILTERED OUTPUT
    function filterOutput($string){

        if(is_object($string)){
            foreach($string as $key => $val) {
                $string->$key =trim(htmlspecialchars(stripslashes($val)));
            }
        } else {
            $string=trim(htmlspecialchars(stripslashes($string)));
        }

        return 	$string;
    }

    //URI FRIENDLY ENCRYPTION
    function Encryption($val)
    {
        $letter1 = ucfirst(chr(rand(97,122)));
        $letter2 = ucfirst(chr(rand(97,122)));
        $letter3 = ucfirst(chr(rand(97,122)));
        $letter4 = ucfirst(chr(rand(97,122)));
        $str1=$letter1.$letter4."#";
        $str2="#".$letter2.$letter3;
        return rtrim(strtr(base64_encode($str1.$val.$str2), '+/', '-_'), '=');
        //return base64_encode($str1.$val.$str2);
    }

    //URI FRIENDLY DECRYPTION
    function Decryption($val)
    {
        $exp = explode("#",base64_decode(str_pad(strtr($val, '-_', '+/'), strlen($val) % 4, '=', STR_PAD_RIGHT)));
        return $exp[1];
    }

    //CREATE PASSWORD SALT
    function create_pwd_salt($length = '3')
    {
        $string = md5(uniqid(rand(), true));
        return substr($string, 0, $length);
    }

    //TIME AGO FUNCTION
    function time_ago($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    //GROUP BY FUNCTION
    function group_by($key, $data) {
        $result = array();

        foreach($data as $val) {
            if(array_key_exists($key, $val)){
                $result[$val[$key]][] = $val;
            }else{
                $result[""][] = $val;
            }
        }

        return $result;
    }

    /*Add HTTP if not there*/
    function addHttp($url) {

        // Search the pattern
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {

            // If not exist then add http
            $url = "http://" . $url;
        }

        // Return the URL
        return $url;
    }

    /*Get Last Week Dates*/
    function getLastWeekDates()
    {
        $lastWeek = array();

        $prevMon = abs(strtotime("previous monday"));
        $currentDate = abs(strtotime("today"));
        $seconds = 86400; //86400 seconds in a day

        $dayDiff = ceil( ($currentDate-$prevMon)/$seconds );

        if( $dayDiff < 7 )
        {
            $dayDiff += 1; //if it's monday the difference will be 0, thus add 1 to it
            $prevMon = strtotime( "previous monday", strtotime("-$dayDiff day") );
        }

        $prevMon = date("Y-m-d",$prevMon);

        // create the dates from Monday to Sunday
        for($i=0; $i<7; $i++)
        {
            $d = date("Y-m-d", strtotime( $prevMon." + $i day") );
            $lastWeek[]=$d;
        }

        return $lastWeek;
    }

    //ONLY HOURS AGO FUNCTION
    function time_ago_in_php($timestamp){

        $time_ago        = strtotime($timestamp);
        $current_time    = time();
        $time_difference = $current_time - $time_ago;
        $seconds         = $time_difference;

        $minutes = round($seconds / 60); // value 60 is seconds
        $hours   = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec
        $days    = round($seconds / 86400); //86400 = 24 * 60 * 60;
        $weeks   = round($seconds / 604800); // 7*24*60*60;
        $months  = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60
        $years   = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60

        if ($seconds <= 60){

            return "Just Now";

        } else if ($minutes <= 60){

            if ($minutes == 1){

                return "one minute ago";

            } else {

                return "$minutes minutes ago";

            }

        } else {

            if ($hours == 1){

                return "an hour ago";

            } else {

                return "$hours hrs ago";

            }
        }
    }

    //CHECK EMPLOYER SUBSCRIPTION
    function check_subscription($employer_id,$profile){
        $allow_to_add = 0;
        $message = '';
        $employer_subscription_id = 0;

        $subscribe = $this->database_model->get_all_records('employer_subscription','employer_subscription.*',array('employer_subscription.employer_id'=>$employer_id,'employer_subscription.status !='=>'3','employer_subscription.subscription_status'=>'0'),'employer_subscription.employer_subscription_id','DESC',1)->row();

        //if record exist
        if($subscribe->employer_subscription_id>0){
            if($subscribe->remain_credit>=$profile){
                $message ='Go ahed to add';
                $allow_to_add = 1;
            }else{
                if($subscribe->remain_credit >0 ){
                    $message = 'You have only '.$subscribe->remain_credit.' profile(s) in current credit.';
                }else{
                    $message = 'You have no any profile in current credit. Please request for more profile.';
                }
            }
            $employer_subscription_id = $subscribe->employer_subscription_id;
        }else{
            $message = 'Your subscription plan has been expired. Please subscribe to get more profiles.';
        }
        $subscription = array(
            'employer_subscription_id'=>$employer_subscription_id,
            'allow_to_add'=> $allow_to_add,
            'message'=>$message,
        );
        return $subscription;
    }

    //COUNT THE FIRST AND LAST DATE
    function count_last_day($todaydate,$days){
        $last_day_counter = $days - 1;
        $today = date('Y-m-d',strtotime($todaydate));
        $date=date_create($today);
        date_add($date,date_interval_create_from_date_string($last_day_counter." days"));
        $last_day=date_format($date,"Y-m-d");
        return array($today,$last_day);
    }

    //CHAT MESSAGE DATE
    function get_day_name($timestamp) {
        $date = date('d/m/Y', strtotime($timestamp));

        if($date == date('d/m/Y')) {
            $date = date('h:i A',strtotime($timestamp));
        }
        else if($date == date('d/m/Y',time() - 60 * 60 * 24)) {
            $date = 'Yesterday <br>'.date('h:i A',strtotime($timestamp));
        }else{
            $date = date('d/m/Y',strtotime($timestamp)).'<br>'.date('h:i A',strtotime($timestamp));
        }
        return $date;
    }
}