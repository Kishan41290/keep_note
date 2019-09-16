<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Forgot extends CI_Controller {
	
	public function __construct() 
	 {
		parent::__construct();
		$this->load->helper('form');
		$this->load->model('manage/admin_model');
		if($this->session->userdata('admin_logged_in') == TRUE)
		{	
			header('location:'.site_url().'manage/home');}
	    }
	public function index()
	{
		$data = array('title'=>$this->lang->line('forg_password'), 'error_msg'=>'');
		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);	
		$this->load->view('manage/forgot_pwd', $data);
	}
	public function check()
	{
		$data = array('title'=>$this->lang->line('forg_password'), 'error_msg'=>'');
		$this->form_validation->set_rules('forgot_email', $this->lang->line('email'),'required|valid_email');
		if(is_array($this->input->post()))
			extract($this->input->post());
		$csrf_check = $this->admin_model->csrfguard_validate_token($csrf_name,$csrf_token);
		if($this->form_validation->run() === TRUE && $csrf_check==true)
		{
			$data['login_result'] = $this->admin_model->check_login('adminmaster',array('Email'=>$this->input->post('forgot_email'),'Status !='=>'Delete') );
			if(empty($data['login_result']))
			{
				$data['error_msg']=$this->lang->line('js_email');
			}
			else
			{
				if ($data['login_result']['Status']=='Enable')
				{
					$name=$data['login_result']['Name'];
					$this->_salt = $this->admin_model->create_pwd_salt();
					$token = hash('sha256', $this->_salt . ( hash('sha256',$data['login_result']['Id']) ) ).time() . rand(1,988);
					$this->ip_date = $this->admin_model->get_date_ip();
					$value_array=array('UserId' => $data['login_result']['Id'],
									   'Token' => $token,
									   'CreatedDate'=>$this->ip_date->cur_date,
									   'CreatedIp'=>$this->ip_date->ip,
									  );
					$this->admin_model->replace('admin_forgotpassword',$value_array);
					
					// PASSING to email,from email, and custom data array
					$get_email = $this->admin_model->get_all_records('mailsettings','*',array('Id'=>1),'Id',1)->row();
					$to_email= $this->input->post('forgot_email');

					$from_email = $this->site_setting->site_email; // $this->admin_model->filterOutput($get_email->Email);
					$from_text = 'MIME-Version: 1.0' . "\r\n";
					$from_text .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					//	$from = $this->common_model->filterOutput($get_email->FromText);
					$from_text .= "From: $from_email\r\n";
					$from_text .= "Content-type: text/html\r\n";

					$subject=$this->admin_model->filterOutput($get_email->Subject);
					$reply_to = $this->admin_model->filterOutput($get_email->Email);

					$link = site_url('manage/recover_pwd/index/'.$token);

					$logo_link = site_url('themes/admin/images/logo.gif');
					$message  = str_replace('[SITE_NAME]',$this->sitename,$get_email->Content);
					$message  = str_replace('[LINK]',$link,$message);
					$message  = str_replace('[LOGO]',$logo_link,$message);
					$message  = str_replace('[SITE_URL]',$this->site_setting->site_url,$message);
					$result = mail($to_email, $subject, $message, $from_text);
					$this->session->set_flashdata('notification',$this->lang->line('frgt_mail_sent'));
					redirect(site_url('manage/forgot/'));
					die();
				}
				else // ACCOUNT SUSPENDED
				{
					$data['error_msg']=$this->lang->line('err_acc_suspend');
					/*$this->session->set_flashdata('error',$this->lang->line('err_acc_suspend'));
					redirect(site_url('manage/forgot_pwd/'));*/
				}
			}
		}
		else  //EMPTY FIELDS
		{   
		  	$this->session->unset_userdata(array('adminname'=>'','adminid'=>'', 'logged_in'=>FALSE, 'admin_type'=>''));
			
		}
		if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');
		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);	
		$this->load->view('manage/forgot_pwd', $data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/manage/home.php */