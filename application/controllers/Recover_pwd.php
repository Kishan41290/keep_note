<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recover_pwd extends CI_Controller {

	public function index($token){
		$data = array('title'=>$this->lang->line('recover_pwd'),'action'=>site_url('recover_pwd/insert/'.$token),'message'=>'','tbl'=>'users');
		//$result = $this->admin_model->get_all_records('admin_forgotpassword','*',array('Token'=>$Token),'Id',1)->row();
		
		$value=('users.Status,users.Id,user_forgot.CreatedDate');
		$joins = array
			   (
				  array
					(
					  'table' => 'users',
					  'condition' => 'user_forgot.UserId = users.Id',
					  'jointype' => 'left'
					 ),
				);
		$result = $this->common_model->get_joins('user_forgot',$value,$joins,array('user_forgot.Token'=>$token),'users.Id','asc',1)->row();
		if(empty($result))
		{
			$this->session->set_flashdata('error',$this->lang->line('gen_alrady_recovered'));
			redirect(site_url('#login'));
			die();
		}
		elseif($result->Status=='Disable')
		{
			$this->session->set_flashdata('error',$this->lang->line('err_acc_suspend'));
			redirect(site_url('#login'));
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
				$this->common_model->delete('user_forgot',array('UserId'=>$result->Id));
				$this->session->set_flashdata('error',$this->lang->line('gen_link_expierd'));
				redirect(site_url());
				die();
			}
			else
			{
				$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
				$data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
				//$data['success_msg'] = $this->lang->line('msg_for_reset'); 	
				$this->load->view('includes/header', $data);
				$this->load->view('reset_pwd');
				$this->load->view('includes/footer');
			}
			
		}

	}
	function insert($Token)
	{
		$data = array( 'js_validate'=>'Yes','action'=>site_url('recover_pwd/insert/'.$Token),'message'=>'','tbl'=>'users');	
		
		$this->_set_rules();
		if(is_array($this->input->post()))
		extract($this->input->post());
		$csrf_check = $this->common_model->csrfguard_validate_token($csrf_name,$csrf_token);
		if($this->form_validation->run() === TRUE && $csrf_check==true)
		{
			$value=('users.Status,users.Id,user_forgot.CreatedDate');
			$joins = array
				   (
					  array
						(
						  'table' => 'users',
						  'condition' => 'user_forgot.UserId = users.Id',
						  'jointype' => 'left'
						 ),
					);
			$result = $this->common_model->get_joins('user_forgot',$value,$joins,array('user_forgot.Token'=>$Token),'users.Id','asc',1)->row();
			
			
			if(empty($result))
			{
				$this->session->set_flashdata('error',$this->lang->line('gen_alrady_recovered'));
				redirect(site_url());
				die();
			}
			elseif($result->Status=='Disable')
			{
				$this->session->set_flashdata('error',$this->lang->line('err_acc_suspend'));
				redirect(site_url());
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
					$this->common_model->delete('user_forgot',array('UserId'=>$result->Id));
					$this->session->set_flashdata('error',$this->lang->line('gen_link_expierd'));
					redirect(site_url());
					die();
				}
				else{
					//$result = $this->admin_model->get_all_records('admin_forgotpassword','*',array('Token'=>$Token),'Id',1)->row();
					$this->ip_date = $this->common_model->get_date_ip();
					$this->_salt = $this->common_model->create_pwd_salt();
					$this->_password = hash('sha256', $this->_salt . ( hash('sha256',$this->input->post('new_pwd')) ) );
					$value_array = array(
										'Password' => $this->_password ,
										'Salt' => $this->_salt ,
										);
					 $this->common_model->update($data['tbl'],$value_array,array('Id'=>$result->Id));
					 $this->common_model->delete('user_forgot',array('UserId'=>$result->Id));
					 $this->session->set_flashdata('notification',$this->lang->line('gen_succ_recover'));
					 redirect(site_url());
					 die();
				}
			}
		}
		else
		{
			if($csrf_check==false) $this->session->set_flashdata('error',$this->lang->line('csrf_error'));
			redirect(site_url('recover_pwd/index/'.$Token));
		}
	}

	function _set_rules()
	{
		$this->form_validation->set_rules('new_pwd','New password','required|min_length[6]|max_length[15]');
		$this->form_validation->set_rules('cnf_pwd','The Confirm new password ','required|matches[new_pwd]');
	}
	// public function confirm($token)
	// {
	// 	$token = $this->input->post('hdn_token');		
	// 	$new_pwd = $this->input->post('new_pwd');
	// 	$conf_pwd = $this->input->post('cnf_pwd');
		
	// 	if($new_pwd!='' && $new_pwd==$conf_pwd && $token!=''){
	// 			$temp = array(
	// 				'email' => strtolower($this->input->post('email')),
	// 				'password' => strtolower($this->input->post('password')),
	// 				'token'	=> $token
	// 			);
	// 			$url = site_url('api/reset_pwd');
	// 			$res1 = $this->common_model->php_curl($url, $temp);
	// 			$res = json_decode($res1);

	// 	}else{
	// 		echo json_encode(array('code' => 0, 'msg' => 'Something went wrong'));
	// 		exit;
	// 	}		
		
	// 			$temp = array(
	// 				'email' => strtolower($this->input->post('email')),
	// 				'password' => strtolower($this->input->post('password')),
	// 				'token'	=> $token
	// 			);
	// 			$url = site_url('api/reset_pwd');
	// 			$res1 = $this->common_model->php_curl($url, $temp);
	// 			$res = json_decode($res1);
				
	// 			$msg = $res->content;
	// 			if($res->res=="false"){
	// 				echo json_encode(array('code' => 0, 'msg' => $msg));
	// 			}else{
	// 				echo json_encode(array('code' => 1, 'msg' => $msg));
	// 				exit;	
	// 			}
				
	// 	}else{
	// 		echo json_encode(array('code' => 0, 'msg' => 'Email Invalid!'));
	// 		exit;
	// 	}
	// 	//_pre($res);

	// }


	// public function confirm($token)
	// {
	// 	if($token!='')
	// 	{
	// 		$value=('users.Status,user_confirmation.*');
	// 		$joins = array
	// 		   (
	// 			  array
	// 				(
	// 				  'table' => 'users',
	// 				  'condition' => 'user_confirmation.UserId = users.Id',
	// 				  'jointype' => 'inner'
	// 				 ),
	// 			);
	// 		$get_user  = $this->common_model->get_joins('user_confirmation',$value,$joins,array('user_confirmation.Token'=>$token),'users.Id','asc',1)->row();
	// 		if($get_user->UserId!='')
	// 		{
	// 			if($get_user->Status=='Enable')
	// 			{
	// 				$this->common_model->clean_session();
	// 				$this->session->set_flashdata('warning',$this->lang->line('alrdy_conf_succ'));
	// 			}
	// 			elseif($get_user->Status=='Disable')
	// 			{
	// 				$this->common_model->clean_session();
	// 				$this->session->set_flashdata('error',$this->lang->line('err_acc_suspend'));
	// 			}
	// 			elseif($get_user->Status=='Delete')
	// 			{
	// 				$this->common_model->clean_session();
	// 				$this->session->set_flashdata('error',$this->lang->line('err_acc_delete'));
	// 			}
	// 			else
	// 			{
	// 				$this->ip_date = $this->common_model->get_date_ip();
	// 				$value_array = array(   
	// 									'Status'=>'Enable',
	// 									'ConfirmDate'=>$this->ip_date->cur_date,
	// 									// 'ConfirmIp'=>$this->ip_date->ip,
	// 								);
	// 				$this->common_model->Update('users',$value_array,array('Id'=>$get_user->UserId));
	// 				$this->common_model->clean_session();
	// 				$this->session->set_flashdata('notification',$this->lang->line('acc_conf_succ'));
	// 			}
	// 		}
	// 		else // NOT FOUND USERID
	// 		{
	// 			$this->session->set_flashdata('error',$this->lang->line('err_token'));
	// 		}
	// 	}
	// 	redirect(site_url());
	// 	exit;
	// }
}
