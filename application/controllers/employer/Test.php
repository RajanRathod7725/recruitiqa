<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Test extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('database_model');
        $this->load->model('employer/employer_model');
        $this->load->model('common_model');
        $this->load->library("verify_mail");

        $this->employer_id = $this->session->userdata('super_employer_id')>0?$this->session->userdata('super_employer_id'):$this->session->userdata('employer_id');

    }
    public function index()
    {

        //
        $email = "rajan.rathod@yahoo.com";//pass an email here to test
		//

		$vmail = new verify_mail();
		$vmail->setStreamTimeoutWait(20);
		$vmail->Debug= TRUE;
		$vmail->Debugoutput= 'html';

		$vmail->setEmailFrom('contact@recruitiqa-mail.com');//email which is used to set from headers,you can add your own/company email over here

		if ($vmail->check($email)) {
            echo '<h1>email &lt;' . $email . '&gt; exist!</h1>';
        } elseif (verify_mail::validate($email)) {
            echo '<h1>email &lt;' . $email . '&gt; valid, but not exist!</h1>';
        } else {
            echo '<h1>email &lt;' . $email . '&gt; not valid and not exist!</h1>';
        }
    }
}

/* End of file ajax_delete.php */
/* Location: ./application/controllers/manage/ajax_delete.php */