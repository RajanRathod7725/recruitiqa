<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Recover_password extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('database_model');
        $this->load->model('recruiter/recruiter_model');
        $this->load->model('common_model');
        if($this->session->userdata('user_logged_in') == TRUE){
            header('location:' . site_url('recruiter/dashboard/'));
        }
        $this->thisModuleName ='Recover Password';
        $this->thisModuleBaseUrl = site_url('recruiter/recover_password').'/';
        $this->common_data = array(
            'title' => $this->thisModuleName,
        );
    }
    public function index($Token)
    {
        $data = $this->common_data;
        $data['action'] = site_url('recruiter/recover_password/insert/' . $Token);
        $data['title'] = $this->thisModuleName." | ".$this->site_setting->site_name;
        $data['meta_keyword'] = $this->site_setting->meta_keyword;
        $data['meta_description'] = $this->site_setting->meta_description;

        $value=('recruiter.status,recruiter.recruiter_id,recruiter_forget_password.created_at');
        $joins = array
        (
            array
            (
                'table' => 'recruiter',
                'condition' => 'recruiter_forget_password.recruiter_id = recruiter.recruiter_id',
                'jointype' => 'left'
            ),
        );
        $result = $this->database_model->get_joins('recruiter_forget_password',$value,$joins,array('recruiter_forget_password.recruiter_token'=>$Token),'recruiter.recruiter_id','asc',1)->row();
        if(empty($result))
        {
            $this->session->set_flashdata('error',$this->lang->line('gen_alrady_recovered'));
            redirect('recruiter/login');
            die();
        }
        elseif($result->status=='2')
        {
            $this->session->set_flashdata('error',$this->lang->line('err_acc_suspend'));
            redirect('recruiter/login');
            die();
        }
        else
        {
            $sentdate=strtotime($result->created_at);
            $current_date=strtotime(date('Y-m-d'));
            $diff= abs($sentdate - $current_date);
            $difference=($diff/(60*60*24));
            if($difference > 5)
            {
                $this->database_model->delete('recruiter_forget_password',array('recruiter_id'=>$result->recruiter_id));
                $this->session->set_flashdata('error',$this->lang->line('gen_link_expierd'));
                redirect('recruiter/forgot');
                die();
            }
            else
            {

                /*******************************
                ||  Common data for all page ||
                 *******************************/
                $data['unique_form_name'] = "CSRFGuard_" . mt_rand(0, mt_getrandmax());
                $data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
                $this->load->view('recruiter/recover_password', $data);
            }

        }
    }
    function insert($Token)
    {
        $PostArray = $this->input->post();

        $data = array(
            'js_validate'=>'Yes',
            'action'=>site_url('recover_password/insert/'.$Token),
            'message'=>'','tbl'=>'recruiter'
        );
        $this->_set_rules();

        $csrf_check = $this->common_model->csrfguard_validate_token($PostArray['csrf_name'],$PostArray['csrf_token']);

        if($this->form_validation->run() === TRUE && $csrf_check==true)
        {
            $value=('recruiter.status,recruiter.recruiter_id,recruiter_forget_password.created_at');
            $joins = array
            (
                array
                (
                    'table' => 'recruiter',
                    'condition' => 'recruiter_forget_password.recruiter_id = recruiter.recruiter_id',
                    'jointype' => 'left'
                ),
            );
            $result = $this->database_model->get_joins('recruiter_forget_password',$value,$joins,array('recruiter_forget_password.recruiter_token'=>$Token),'recruiter.recruiter_id','asc',1)->row();

            if(empty($result))
            {
                $this->session->set_flashdata('error',$this->lang->line('gen_alrady_recovered'));
                redirect('recruiter/login');
                die();
            }
            elseif($result->status=='2')
            {
                $this->session->set_flashdata('error',$this->lang->line('err_acc_suspend'));
                redirect('recruiter/login');
                die();
            }
            else
            {
                $sentdate=strtotime($result->created_at);
                $current_date=strtotime(date('Y-m-d'));
                $diff= abs($sentdate - $current_date);
                $difference=($diff/(60*60*24));
                if($difference > 5)
                {
                    $this->database_model->delete('recruiter_forget_password',array('recruiter_id'=>$result->recruiter_id));
                    $this->session->set_flashdata('error',$this->lang->line('gen_link_expierd'));
                    redirect('recruiter/login');
                    die();
                }
                else{

                    $this->ip_date = $this->common_model->get_date_ip();
                    $this->_salt = $this->common_model->create_pwd_salt();
                    $this->_password = hash('sha256', $this->_salt . ( hash('sha256',$this->input->post('recover_pass')) ) );
                    $value_array = array(
                        'recruiter_password' => $this->_password ,
                        'recruiter_salt' => $this->_salt ,
                    );
                    $this->database_model->update($data['tbl'],$value_array,array('recruiter_id'=>$result->recruiter_id));
                    $this->database_model->delete('recruiter_forget_password',array('recruiter_id'=>$result->recruiter_id));
                    $this->session->set_flashdata('notification',$this->lang->line('gen_succ_recover'));
                    redirect('recruiter/login');
                    die();
                }
            }
        }
        else
        {
            if($csrf_check==false) $this->session->set_flashdata('error',$this->lang->line('csrf_error'));
            redirect(site_url('recruiter/recover_password/index/'.$Token));
        }
    }
    //SET FORM DATA

    // VALIDATION RULES
    function _set_rules()
    {
        $this->form_validation->set_rules('recover_pass','New password','required|min_length[6]|max_length[15]');
        /*$this->form_validation->set_rules('conf_recover_pass','The Confirm new password ','required|matches[recover_pass]');*/
    }
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */