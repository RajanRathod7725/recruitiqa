<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_candidate_status extends CI_Controller {
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

            if(@$PostArray['reason_id']>0){
                $this->database_model->delete('candidate_reject_reason',array('candidate_id'=>$PostArray['candidate_id'],'job_id'=>$PostArray['job_id']));
                $value_array = array(
                    'candidate_id'=>$PostArray['candidate_id'],
                    'reason_id'=>$PostArray['reason_id'],
                    'reason'=>$PostArray['reason'],
                    'job_id'=>$PostArray['job_id'],
                    'status'=>'1',
                    'created_at'=>$this->ip_date->cur_date,
                    'updated_at'=>$this->ip_date->cur_date,
                    'created_ip'=>$this->ip_date->ip,
                );
                $this->database_model->save('candidate_reject_reason',$value_array);
            }
            $old_candidate_status=$this->database_model->get_all_records('candidate','candidate_status',array('candidate.status !='=>'3','candidate_id'=>$PostArray['candidate_id']),'candidate.candidate_name','ASC',1)->row();

            $old_count=$this->database_model->count_all('candidate',array('candidate_status'=>$old_candidate_status->candidate_status,'job_id'=>$PostArray['job_id']));

            $update_array= array(
                'candidate_status'=>$PostArray['status_id'],
                'updated_at'=>$this->ip_date->cur_date,
            );
            $this->database_model->update('candidate',$update_array,array('candidate_id'=>$PostArray['candidate_id']));

            //ENTRY IN HISTORY TABLE
            $history_value_array=array(
                'candidate_id'=>$PostArray['candidate_id'],
                'candidate_status'=>$PostArray['status_id'],
                'status' => 1,
                'created_at'=>$this->ip_date->cur_date,
                'updated_at'=>$this->ip_date->cur_date,
                'created_ip'=>$this->ip_date->ip,
            );
            $this->database_model->save('candidate_history',$history_value_array);

            $value_array = array(
                'table_id' => $PostArray['candidate_id'],
                'table_name' => 'tbl_candidate',
                'activity' => '2',
                'modified_by'=> $this->employer_id,
            );
            $this->database_model->insert_modified($value_array,$this->employer_id);

            $new_count=$this->database_model->count_all('candidate',array('candidate_status'=>$PostArray['status_id'],'job_id'=>$PostArray['job_id']));

            if($PostArray['status_id']==2){
                $this->database_model->custom_query('update `tbl_job` set contacted_candidate =contacted_candidate +1 where job_id ='.$PostArray['job_id']);
            }
            if($old_candidate_status->candidate_status==2){
                $this->database_model->custom_query('update `tbl_job` set contacted_candidate =contacted_candidate -1 where job_id ='.$PostArray['job_id']);
            }

            if($PostArray['status_id']==6){
                $new_date_format = DateTime::createFromFormat('d/m/Y', $PostArray['idate'])->format('Y-m-d');
                $new_date_format_for_mail = DateTime::createFromFormat('d/m/Y', $PostArray['idate'])->format('d M, Y');
                $note= $PostArray['inote']!=''?$PostArray['inote']:'';
                $interview_array = array(
                    'candidate_id'=> $PostArray['candidate_id'],
                    'interview_date'=> $new_date_format,
                    'interview_time'=> date('H:i:s',strtotime($PostArray['itime'])),
                    'interview_note'=> $note,
                    'status' => 1,
                    'created_at'=>$this->ip_date->cur_date,
                    'updated_at'=>$this->ip_date->cur_date,
                    'created_ip'=>$this->ip_date->ip,
                );

                $this->database_model->save('candidate_interview',$interview_array);

                //candidate detail
                $select_value= 'candidate.candidate_email,job.job_title,job.job_location,employer.company_name';
                $joins=array(
                    array(
                        'table'=>'job',
                        'condition'=>'candidate.job_id=job.job_id',
                        'jointype'=>'left'
                    ),
                    array(
                        'table'=>'employer',
                        'condition'=>'job.employer_id=employer.employer_id',
                        'jointype'=>'left'
                    ),
                );
                $candidate = $this->database_model->get_joins('candidate',$select_value,$joins,array('candidate.status !=' => '3', 'candidate.candidate_id' =>$PostArray['candidate_id']),'candidate.candidate_id','ASC',1,0)->row();

                if(!empty($candidate)){
                    /*Mail sending */
                    $mail_temp = $this->database_model->get_all_records('mail_templates', '*', array('status !=' => '3', 'mail_slug' => 'user_interview'), 'mail_template_id DESC', 1, 0)->row();
                    // parameter for mail template and function to send
                    $from_email = $this->session->userdata('emp_sender_mail');
                    $from_text = $this->session->userdata('emp_sender_name');
                    $sub_mail = $mail_temp->mail_subject;

                    $msg = str_replace('[SITE_LINK]', site_url(), $mail_temp->mail_content);
                    $msg = str_replace('[LOGO_LINK]', site_url('themes/manage/images/logo_white.png'), $msg);
                    $msg = str_replace('[SITE_NAME]', $this->site_setting->site_name, $msg);
                    $msg = str_replace('[CONTACT_MAIL]', $this->site_setting->contact_email, $msg);
                    $msg = str_replace('[SITE_COPYRIGHT]', $this->site_setting->site_copyright, $msg);
                    $msg = str_replace('[JOB]', $candidate->job_title , $msg);
                    $msg = str_replace('[COMPANY]',$candidate->company_name.' ('.$candidate->job_location.')' , $msg);
                    $msg = str_replace('[DATE]',$new_date_format_for_mail , $msg);
                    $msg = str_replace('[TIME]',$PostArray['itime'] , $msg);
                    $msg = str_replace('[NOTE]',$note!=''?'Note : '.$note:'' , $msg);

                    $emails = explode(',',$candidate->candidate_email);

                    foreach ($emails as $email) {
                        $to_email = $email;
                        $name = $this->database_model->custom_query("SELECT tbl_candidate.candidate_name FROM `tbl_candidate` WHERE `tbl_candidate`.`status` != '3' AND FIND_IN_SET('".$email."',`tbl_candidate`.`candidate_email`)")->row()->candidate_name;
                        $msg = str_replace('[USER_NAME]', $name, $msg);

                        $result = $this->common_model->send_mail_multiple_user($to_email, $from_email, $from_text, $sub_mail, $msg);

                    }
                }
            }
            $contact_btn = '';
            $is_dispaly='none';
            if($PostArray['status_id']==1 || $PostArray['status_id']==4|| $PostArray['status_id']==7){
                $contact_btn = 'Contact';
                $is_dispaly='block';
            }else if($PostArray['status_id']==2){
                $contact_btn = 'Follow-up';
                $is_dispaly='block';
            }else if($PostArray['status_id']==6){
                $contact_btn = 'Invite';
                $is_dispaly='block';
            }else{
                $contact_btn = '';
                $is_dispaly='none';
            }
            $message=array('code'=>1,'new_status_id'=>$PostArray['status_id'],'new_count'=>$new_count,'old_status_id'=>$old_candidate_status->candidate_status,'old_count'=>$old_count-1,'candidate_id'=>$PostArray['candidate_id'],'contact_btn'=>$contact_btn,'is_dispaly'=>$is_dispaly);
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