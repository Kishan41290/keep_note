<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Recover_pwd extends CI_Controller { 
	public function __construct()
	{
		parent::__construct();
		$this->load->model('manage/admin_model');		
		if($this->session->userdata('admin_logged_in') == TRUE)
			header('location:'.site_url().'manage/home');		
	}
	public function index($Token)
	{
		$data = array('title'=>$this->lang->line('recover_pwd'),'action'=>site_url('manage/recover_pwd/insert/'.$Token),'message'=>'','tbl'=>'adminmaster' );
		//$result = $this->admin_model->get_all_records('admin_forgotpassword','*',array('Token'=>$Token),'Id',1)->row();
		
		$value=('adminmaster.Status,adminmaster.Id,admin_forgotpassword.CreatedDate');
		$joins = array
			   (
				  array
					(
					  'table' => 'adminmaster',
					  'condition' => 'admin_forgotpassword.UserId = adminmaster.Id',
					  'jointype' => 'left'
					 ),
				);
		$result = $this->admin_model->get_joins('admin_forgotpassword',$value,$joins,array('admin_forgotpassword.Token'=>$Token),'adminmaster.Id','asc',1)->row();
		if(empty($result))
		{
			$this->session->set_flashdata('error',$this->lang->line('gen_alrady_recovered'));
			redirect('manage/login');
			die();
		}
		elseif($result->Status=='Disable')
		{
			$this->session->set_flashdata('error',$this->lang->line('err_acc_suspend'));
			redirect('manage/login');
			die();
		}
		else
		{
			$sentdate=strtotime($result->CreatedDate);
			$current_date=strtotime(date('Y-m-d'));
			$diff= abs($sentdate - $current_date);
			$difference=($diff/(60*60*24));
			if($difference > 5)
			{
				$this->admin_model->delete('admin_forgotpassword',array('UserId'=>$result->Id));
				$this->session->set_flashdata('error',$this->lang->line('gen_link_expierd'));
				redirect('manage/login');
				die();
			}
			else
			{
				$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
				$data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
				//$data['success_msg'] = $this->lang->line('msg_for_reset'); 	
				$this->load->view('manage/recover_pwd', $data);
			}
			
		}
	}
	function insert($Token)
	{
		$data = array( 'js_validate'=>'Yes','action'=>site_url('manage/recover_pwd/insert/'.$Token),'message'=>'','tbl'=>'adminmaster');	
		
		$this->_set_rules();
		if(is_array($this->input->post()))
		extract($this->input->post());
		$csrf_check = $this->admin_model->csrfguard_validate_token($csrf_name,$csrf_token);
		if($this->form_validation->run() === TRUE && $csrf_check==true)
		{
			$value=('adminmaster.Status,adminmaster.Id,admin_forgotpassword.CreatedDate');
			$joins = array
				   (
					  array
						(
						  'table' => 'adminmaster',
						  'condition' => 'admin_forgotpassword.UserId = adminmaster.Id',
						  'jointype' => 'left'
						 ),
					);
			$result = $this->admin_model->get_joins('admin_forgotpassword',$value,$joins,array('admin_forgotpassword.Token'=>$Token),'adminmaster.Id','asc',1)->row();
		
			if(empty($result))
			{
				$this->session->set_flashdata('error',$this->lang->line('gen_alrady_recovered'));
				redirect('manage/login');
				die();
			}
			elseif($result->Status=='Disable')
			{
				$this->session->set_flashdata('error',$this->lang->line('err_acc_suspend'));
				redirect('manage/login');
				die();
			}
			else
			{
				$sentdate=strtotime($result->CreatedDate);
				$current_date=strtotime(date('Y-m-d'));
				$diff= abs($sentdate - $current_date);
				$difference=($diff/(60*60*24));
				if($difference > 5)
				{
					$this->admin_model->delete('admin_forgotpassword',array('UserId'=>$result->Id));
					$this->session->set_flashdata('error',$this->lang->line('gen_link_expierd'));
					redirect('manage/login');
					die();
				}
				else{
					//$result = $this->admin_model->get_all_records('admin_forgotpassword','*',array('Token'=>$Token),'Id',1)->row();
					$this->ip_date = $this->admin_model->get_date_ip();
					$this->_salt = $this->admin_model->create_pwd_salt();
					$this->_password = hash('sha256', $this->_salt . ( hash('sha256',$this->input->post('recover_pass')) ) );
					$value_array = array(
										'Password' => $this->_password ,
										'Salt' => $this->_salt ,
										);
					 $this->admin_model->update($data['tbl'],$value_array,array('Id'=>$result->Id));
					 $this->admin_model->delete('admin_forgotpassword',array('UserId'=>$result->Id));
					 $this->session->set_flashdata('notification',$this->lang->line('gen_succ_recover'));
					 redirect('manage/login');
					 die();
				}
			}
		}
		else
		{
			if($csrf_check==false) $this->session->set_flashdata('error',$this->lang->line('csrf_error'));
			redirect(site_url('manage/recover_pwd/index/'.$Token));
		}
	}
	//SET FORM DATA
	
	// VALIDATION RULES
	function _set_rules()
	{
		$this->form_validation->set_rules('recover_pass','New password','required|min_length[6]|max_length[15]');
		$this->form_validation->set_rules('conf_recover_pass','The Confirm new password ','required|matches[recover_pass]');
	}
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */