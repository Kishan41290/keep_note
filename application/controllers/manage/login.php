<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {
	public function __construct()
	 {
		parent::__construct();
		$this->load->model('manage/admin_model');
		if($this->session->userdata('admin_logged_in') == TRUE)
			header('location:'.site_url().'manage/home');
	 }
	public function index()
	{
		$data = array('title'=>$this->lang->line('admin')." ". $this->lang->line('login'));
		
		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
		
		$this->load->view('manage/login', $data);
	}
	public function check()
	{	
		$data = array('title'=> $this->lang->line('admin')." ". $this->lang->line('login'),);		
		
		if(is_array($this->input->post()))
			extract($this->input->post());
		$this->load->library(array('form_validation'));
		$this->form_validation->set_rules('email',  $this->lang->line('email'),'trim|required|valid_email');
		$this->form_validation->set_rules('password',  $this->lang->line('password'),'required');
		$this->_emailID = $this->input->post('email');
		
		$csrf_check = $this->admin_model->csrfguard_validate_token($csrf_name,$csrf_token);
		
		if($this->form_validation->run() === TRUE && $csrf_check==true)
		{
			$data['login_result'] = $this->admin_model->check_login('adminmaster',array('Email'=>$this->input->post('email'),'Status !='=>'Delete') );
			if (!empty($data['login_result']))
			{
				if ($data['login_result']['Status']=='Enable')
				{
					$this->_password = hash('sha256',$this->input->post('password'));
					$this->_password =  hash('sha256',$data['login_result']['Salt'] . $this->_password);
					if($data['login_result']['Password'] == $this->_password)
					{
						$this->session->set_userdata(array('admin_role'=>$data['login_result']['RoleId'], 'adminname'=>$data['login_result']['Name'],'adminid'=>$data['login_result']['Id'], 'adminemail'=>$data['login_result']['Email'] , 'admin_logged_in'=>TRUE ,'admin_image'=>$data['login_result']['Image']));
						$this->session->userdata('admin_logged_in');
						redirect(site_url('manage/home/'));
						die();
					}
					else  // WRONG PAASWORD
					{
						$this->admin_model->clean_session();
						$data['error_msg'] = $this->lang->line('err_valid_pwd');
					}
				}
				else // ACCOUNT SUSPENDED
				{
					$this->admin_model->clean_session();
					$data['error_msg'] = $this->lang->line('err_acc_suspend');	
				}
			}
			else // ACCOUNT SUSPENDED
			{
				$this->admin_model->clean_session();
				$data['error_msg'] = $this->lang->line('err_valid_mail');
			}	
		}
		   
		if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');
		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
		$this->load->view('manage/login', $data);
		//$data['error_msg'] = 'Error: Enter email and password';
	}
	public function logout()			//LOGOUT - DESTROY ALL SESSION DATA
	{
		$data = array('title'=>$this->lang->line('admin_login'),'target'=>site_url('manage'), 'error_msg'=>'');
		$this->admin_model->clean_session();
		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
		$this->load->view('manage/login', $data);
	}
	
}
