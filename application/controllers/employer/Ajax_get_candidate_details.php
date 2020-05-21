<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_get_candidate_details extends CI_Controller {
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

            $candidate=$this->database_model->get_all_records('candidate','*',array('candidate.status !='=>'3','candidate_id'=>$PostArray['candidate_id']),'candidate.candidate_id','ASC',1)->row();

            $candidate_pic = check_image($candidate->candidate_photo,'uploads/candidate','size150');
            $candidate_resume = site_url().'uploads/candidate/resume/'.$candidate->candidate_resume;

            /*rating*/
            $html = "<ul class='stars'>";
            for($i=1;$i<=5;$i++) {
                $selected = "";
                if(!empty($candidate->rating) && $i<=$candidate->rating) {
                    $selected = "selected";
                }
                $html .="<li class='star ".$selected."' title='".$i." Star' data-value='".$i."'><i class='fa fa-star fa-fw'></i></li>";
            }
            $html .="</ul>";

            $email_html ='';
            if($candidate->candidate_email!=''){
                $emails=explode(',',$candidate->candidate_email);
                foreach ($emails as $email){
                    $email_html.='<a href="mailto:'.$email.'">'.$email.'</a><br>';
                }
            }else{
                $email_html.='-';
            }

            $social_html='';
            if($candidate->candidate_linkedin){
                $social_html.='<a href="'.$this->common_model->addHttp($candidate->candidate_linkedin).'" target="_blank" title="LinkedIn"><i class="fa fa-linkedin-square font-medium-4 ml-1" style="margin-right: 0.75rem;"></i></a>';
            }
            if($candidate->candidate_git){
                $social_html.='<a href="'.$this->common_model->addHttp($candidate->candidate_git).'" target="_blank" title="Github"><i class="fa fa-github font-medium-4" style="margin-right: 0.75rem;"></i></a>';
            }
            if($candidate->candidate_fb){
                $social_html.='<a href="'.$this->common_model->addHttp($candidate->candidate_fb).'" target="_blank" title="Facebook"><i class="fa fa-facebook font-medium-4" style="margin-right: 0.75rem;"></i></a>';
            }
            if($candidate->candidate_twitter){
                $social_html.='<a href="'.$this->common_model->addHttp($candidate->candidate_twitter).'" target="_blank" title="Twitter"><i class="fa fa-twitter font-medium-4" style="margin-right: 0.75rem;"></i></a>';
            }
            if($candidate->candidate_twitter!=''){
                $social_html .='<a href="'.$this->common_model->addHttp($candidate->candidate_stack).'" target="_blank" title="Stack Overflow"><i class="fa fa-stack-overflow font-medium-4" style="margin-right: 0.75rem;"></i></a>';
            }
            if($candidate->candidate_google!=''){
                $social_html .='<a href="'.$this->common_model->addHttp($candidate->candidate_google).'" target="_blank" title="Google Plus"><i class="fa fa-google-plus-official font-medium-4" style="margin-right: 0.75rem;"></i></a>';
            }
            if($candidate->candidate_xing!=''){
                $social_html .='<a href="'.$this->common_model->addHttp($candidate->candidate_xing).'" target="_blank" title="Xing"><i class="fa fa-xing-square font-medium-4" style="margin-right: 0.75rem;"></i></a>';
            }

            $candidate_status = $this->config->item('candidate_status');
            $candidate_status_color = $this->config->item('candidate_status_color');
            $status = '';
            foreach ($candidate_status as $key => $value){
                if($key==$candidate->candidate_status){
                    $status='<span class="text-'.$candidate_status_color[$key].'">'.$value.'</span>';
                }
            }
            $contact_html = $candidate->candidate_phone?'<a href="tel:'.$candidate->candidate_phone.'">'.$candidate->candidate_phone.'</a>':'-';

            $file_path = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'candidate' . DIRECTORY_SEPARATOR . 'resume' . DIRECTORY_SEPARATOR . $candidate->candidate_resume;
            $pdf_html = '<embed src="'.$candidate_resume.'" width="100%" style="min-height:315px;"/>';

            /*candidate_note logic*/
            $notes=$this->database_model->get_all_records('candidate_note','*',array('candidate_note.status !='=>'3','candidate_id'=>$PostArray['candidate_id']),'candidate_note.note_id','DESC','')->result();
            $note_html = '';
            if(!empty($notes)){
                $note_html = '<div class="col-md-12"> <div style="text-align: right;"><input type="button" value="Add Note" class="btn btn-primary mt-1" id="add_note"></div> <ul class="not-ul">';
                foreach ($notes as $note){
                    $note_html .='<li class="not-li" id="data_'.$note->note_id.'"> <div class="row"> <div class="col-md-10"> <h6>'.$note->note.'</h6> <p>'.date('d M, Y H:i A',strtotime($note->created_at)).'</p> </div> <div class="col-md-2"><div class="mt-1"> <a href="javascript:;" class="delete-note pt-1"><i class="feather icon-trash font-medium-3"></i></a></div> </div> </div> </li>';
                }
                $note_html .='</ul></div>';
            }else{
                $note_html ='<div class="col-md-12 p-2 no-notes"><span class="mb-1">No notes are added for this candidate.</span><br><input type="button" value="Add Note" class="btn btn-primary mt-1" id="add_note"></div>';
            }

            /*candidate activity logic*/
            $activities = $this->database_model->get_all_records('candidate_history','*',array('candidate_history.status !='=>'3','candidate_id'=>$PostArray['candidate_id']),'candidate_history.candidate_history_id','ASC','')->result();

            $activity_html = '<ul class="activity-timeline timeline-left list-unstyled">';

            foreach ($activities as $activity){
                if($activity->candidate_status==1){
                    $activity_html .='<li> <div class="timeline-icon bg-primary"> <i class="fa fa-binoculars font-medium-2"></i> </div> <div class="timeline-info"> <p class="font-weight-bold">The candidate went into a review List!</p> </div> <small class="">'.$this->common_model->time_ago($activity->created_at).'</small> </li>';
                }else if($activity->candidate_status==2){
                    $activity_html .='<li> <div class="timeline-icon bg-info"> <i class="fa fa-bullseye font-medium-2"></i> </div> <div class="timeline-info"> <p class="font-weight-bold">The candidate went into a Contact List!</p> </div> <small class="">'.$this->common_model->time_ago($activity->created_at).'</small> </li>';
                }else if($activity->candidate_status==3){
                    $activity_html .='<li> <div class="timeline-icon bg-danger"> <i class="fa fa-frown-o font-medium-2"></i> </div> <div class="timeline-info"> <p class="font-weight-bold">The candidate got Rejected!</p> </div> <small class="">'.$this->common_model->time_ago($activity->created_at).'</small> </li>';
                }else if($activity->candidate_status==4){
                    $activity_html .='<li> <div class="timeline-icon bg-interested"> <i class="fa fa-thumbs-o-up font-medium-2"></i> </div> <div class="timeline-info"> <p class="font-weight-bold">The candidate went into an Interested List!</p> </div> <small class="">'.$this->common_model->time_ago($activity->created_at).'</small> </li>';
                }else if($activity->candidate_status==5){
                    $activity_html .='<li> <div class="timeline-icon bg-notinterested"> <i class="fa fa-thumbs-o-down font-medium-2"></i> </div> <div class="timeline-info"> <p class="font-weight-bold">The candidate went into a Not Interested List!</p> </div> <small class="">'.$this->common_model->time_ago($activity->created_at).'</small> </li>';
                }else if($activity->candidate_status==6){
                    $activity_html .='<li> <div class="timeline-icon bg-warning"> <i class="fa fa-laptop font-medium-2"></i> </div> <div class="timeline-info"> <p class="font-weight-bold">The candidate went into an interview List!</p> </div> <small class="">'.$this->common_model->time_ago($activity->created_at).'</small> </li>';
                }else if($activity->candidate_status==7){
                    $activity_html .='<li> <div class="timeline-icon bg-success"> <i class="fa fa-trophy font-medium-2"></i> </div> <div class="timeline-info"> <p class="font-weight-bold">The candidate got selected by this employer!</p> </div> <small class="">'.$this->common_model->time_ago($activity->created_at).'</small> </li>';
                }else if($activity->candidate_status==8){
                    $activity_html .='<li> <div class="timeline-icon bg-contact-mail"> <i class="fa fa-envelope-o font-medium-2"></i> </div> <div class="timeline-info"> <p class="font-weight-bold"> Contact mail sent by you.</p> </div> <small class="">'.$this->common_model->time_ago($activity->created_at).'</small> </li>';
                }else if($activity->candidate_status==9){
                    $activity_html .='<li> <div class="timeline-icon bg-followup-mail"> <i class="fa fa-envelope-o font-medium-2"></i> </div> <div class="timeline-info"> <p class="font-weight-bold"> Follow-up Mail send by you.</p> </div> <small class="">'.$this->common_model->time_ago($activity->created_at).'</small> </li>';
                }else{
                    $activity_html .='<li> <div class="timeline-icon bg-invite-mail"> <i class="fa fa-envelope-o font-medium-2"></i> </div> <div class="timeline-info"> <p class="font-weight-bold">Interview invitation mail sent by you.</p> </div> <small class="">'.$this->common_model->time_ago($activity->created_at).'</small> </li>';
                }
            }
            $activity_html .='</ul>';

            $contact_reject_btn ='';
            if($candidate->candidate_status == 1){
                $contact_reject_btn ='<a href="javascript:;" class="c-btn btn-primary waves-effect waves-light font-medium-1 pull-right pop-single-contact w-100 mb-1" style="line-height: 30px;" id="pop_contact_'.$candidate->candidate_id.'">Contact</a> <a href="javascript:;" class="c-btn btn-danger waves-effect waves-light font-medium-1 pull-right pop-single-reject w-100" style="line-height: 30px;" id="pop_reject_'.$candidate->candidate_id.'">Reject</a>';
            }

            $message=array('code'=>1,'candidate'=>$candidate,'candidate_pic'=>$candidate_pic,'candidate_resume'=>$candidate_resume,'posted_on'=>date('d M, Y',strtotime($candidate->created_at)),'ratting_html'=>$html,'emails'=>$email_html,'contact'=>$contact_html,'social'=>$social_html!=''?$social_html:'Not Provided','status'=>$status,'pdf_html'=>$pdf_html,'note_html'=>$note_html,'activity_html'=>$activity_html,'contact_reject_btn'=>$contact_reject_btn);
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