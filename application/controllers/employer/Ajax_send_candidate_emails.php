<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_send_candidate_emails extends CI_Controller {
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

        if($csrf_check==true) {
            $this->ip_date = $this->common_model->get_date_ip();

            $PostArray = $this->input->post();
            $isreaload = 0;
            $attachment_name ='';
            //if attachment then
            if ($_FILES['attachment']['name'] != '') {
                $config = array();
                $config['upload_path'] = './uploads/mail_attachment';
                $config['max_size'] = '1024000';
                $config['allowed_types'] = 'pdf|gif|jpg|png|jpeg|txt|csv|xls|ppt|pptx|docx|doc';
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('attachment')) {
                    $error = array('error' => $this->upload->display_errors());
                    $data['upload_error'] = $error;
                    $data['unique_form_name'] = "CSRFGuard_" . mt_rand(0, mt_getrandmax());
                    $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);

                    $this->error_back_page($PostArray);
                } else {
                    $attachment_name = $this->upload->file_name;
                }
            }

            if($attachment_name!=''){
                $file_path = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'mail_attachment' . DIRECTORY_SEPARATOR . $attachment_name;
            }else{
                $file_path='';
            }

            $mail_temp = $this->database_model->get_all_records('mail_templates', '*', array('status !=' => '3', 'mail_slug' => 'user_contact'), 'mail_template_id DESC', 1, 0)->row();

            // parameter for mail template and function to send
            $html = $PostArray['html'];
            $from_email = $this->session->userdata('emp_sender_mail');
            $from_text = $this->session->userdata('emp_sender_name');//$mail_temp->mail_from_text;
            $sub_mail = $PostArray['subject'];

            /*$msg = str_replace('[DATA]', $html, $mail_temp->mail_content);
            $msg = str_replace('[SITE_LINK]', site_url(), $msg);
            $msg = str_replace('[LOGO_LINK]', site_url('themes/manage/images/logo_white.png'), $msg);
            $msg = str_replace('[SITE_NAME]', $this->site_setting->site_name, $msg);
            $msg = str_replace('[CONTACT_MAIL]', $this->site_setting->contact_email, $msg);
            $msg = str_replace('[SITE_COPYRIGHT]', $this->site_setting->site_copyright, $msg);*/

            $emails = explode(',',$PostArray['emailto']);
            $old_candidate_id = 0;
            $old_candidate_status_id = 0;
            foreach ($emails as $email) {

                $to_email = $email;

                $candidate = $this->database_model->custom_query("SELECT tbl_candidate.candidate_id,tbl_candidate.candidate_name, tbl_candidate.candidate_status FROM `tbl_candidate` WHERE `tbl_candidate`.`status` != '3' AND FIND_IN_SET('".$email."',`tbl_candidate`.`candidate_email`)")->row();
                /*$msg = str_replace('[USER_NAME]', $candidate->candidate_name, $msg);*/
                if($candidate->candidate_id !=$old_candidate_id){
                    if($PostArray['type']=='contact'){
                        $can_status = '8';
                    }
                    if($PostArray['type']=='followup'){
                        $can_status = '9';
                    }
                    if($PostArray['type']=='invite'){
                        $can_status = '10';
                    }
                    //ENTRY IN HISTORY TABLE
                    $history_value_array=array(
                        'candidate_id'=>$candidate->candidate_id,
                        'candidate_status'=>$can_status,
                        'status' => 1,
                        'created_at'=>$this->ip_date->cur_date,
                        'updated_at'=>$this->ip_date->cur_date,
                        'created_ip'=>$this->ip_date->ip,
                    );
                    $this->database_model->save('candidate_history',$history_value_array);
                    $old_candidate_id = $candidate->candidate_id;
                }
                $result = $this->common_model->send_mail_multiple_user($to_email, $from_email, $from_text, $sub_mail, $html, '', $file_path);
                if($result==1 && $candidate->candidate_status=='1' && $PostArray['type']=='contact'){
                    $this->database_model->update('candidate',array('candidate_status'=>'2'),array('candidate_id'=>$candidate->candidate_id));
                    $isreaload =1;

                    if($old_candidate_status_id != $candidate->candidate_id){
                        //ENTRY IN HISTORY TABLE
                        $history_value_array=array(
                            'candidate_id'=>$candidate->candidate_id,
                            'candidate_status'=>'2',
                            'status' => 1,
                            'created_at'=>$this->ip_date->cur_date,
                            'updated_at'=>$this->ip_date->cur_date,
                            'created_ip'=>$this->ip_date->ip,
                        );
                        $this->database_model->save('candidate_history',$history_value_array);
                        $old_candidate_status_id=$candidate->candidate_id;
                    }
                }
            }
            $message=array('code'=>1,'isreaload'=>$isreaload);
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