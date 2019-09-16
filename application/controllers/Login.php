<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		if($email!='' && filter_var($email, FILTER_VALIDATE_EMAIL)){
			if($password!='' && strlen($password)>6){
				$temp = array(
					'email' => strtolower($this->input->post('email')),
					'password' => $this->input->post('password'),
					'login_by'	=> 'App',
				);

				$res = (object) $this->check_login($temp);
				// print_r($res);
				// exit;
				// $url = site_url('api/login');
				// $res1 = $this->common_model->php_curl($url, $temp);
				// 	$res = json_decode($res1);

				$msg = $res->content;
				if($res->res=="false" || empty($res)){
					echo json_encode(array('code' => 0, 'msg' => $msg));
				}else{
					$user_data = array(
							'userid'	=> $res->user_id,
							'email'	=> $res->Email,
							'username' => $res->first_name.' '.$res->last_name,
							'user_logged_in'	=> true,
						);
					$this->session->set_userdata($user_data);
					echo json_encode(array('code' => 1, 'msg' => $msg));
					exit;	
				}
				
			}else{
				echo json_encode(array('code' => 0, 'msg' => 'Password Invalid!'));
				exit;
			}
		}else{
			echo json_encode(array('code' => 0, 'msg' => 'Email Invalid!'));
			exit;
		}
		//_pre($res);

	}

	private function check_login($post_data){
		$email = $post_data['email'];
		$pwd = $post_data['password'];
		$login_by = $post_data['login_by']; 
		$tb_pri = $this->db->dbprefix;
		if($pwd != '' && $email != '' && $login_by!='')
		{ 
			$result = $this->common_model->get_all_records('users','*', array('Email'=>$email,
			'Status !='=>'Delete'), '', 1 )->row();
			
			if(!empty($result)) // EMAIL EXIST
			{
				$password = hash('sha256',$pwd);
				$password =  hash('sha256',$result->Salt . $password);						
				$exist_pwd = $result->Password;
				$cur_password = $login_by == 'App' ? $password : $exist_pwd;
				if($exist_pwd == $cur_password)// CHECK PWD
				{ 
					//INSERT : DEVICE CODE
					$this->ip_date = $this->common_model->get_date_ip();
				
					 if($result->Status=='Enable')
					 {
					 	$res = array('res'=>'true','content'=>$this->lang->line('succ_msg_login'),
					'auth_key' => $this->common_model->get_auth_key($result->Id), 'user_id'=>$result->Id,
					'first_name'=>$result->FirstName,'last_name'=>$result->LastName,'Email' => $email,'user_type'=>$result->Type);

					 }
					 elseif($result->Status=='Disable')
					 {
					 	$res = array('res'=>'false','content'=>'Account suspended by admin.');
					 }
					 elseif($result->Status=='Pending')
					 {
					 	$res = array('res'=>'false','content'=>'Your email is not confirmed yet.');
					 }else{
					 	$res = array('res'=>'false','content'=>'Your account has been deleted.');
					 }
					return $res;
				}
				else
				{
					return array('res'=>'false','content'=>'Enter valid password.');
				}	
			}
			else
			{
				return array('res'=>'false','content'=>'Please enter a valid email');
			}
		}
		else
		{ 
			return array('res'=>'false','content'=>$this->lang->line('api_require_msg'));
		}	

	}

	public function confirm($token)
	{
		if($token!='')
		{
			$value=('users.Status,user_confirmation.*');
			$joins = array
			   (
				  array
					(
					  'table' => 'users',
					  'condition' => 'user_confirmation.UserId = users.Id',
					  'jointype' => 'inner'
					 ),
				);
			$get_user  = $this->common_model->get_joins('user_confirmation',$value,$joins,array('user_confirmation.Token'=>$token),'users.Id','asc',1)->row();
			if($get_user->UserId!='')
			{
				if($get_user->Status=='Enable')
				{
					$this->common_model->clean_session();
					$this->session->set_flashdata('warning',$this->lang->line('alrdy_conf_succ'));
				}
				elseif($get_user->Status=='Disable')
				{
					$this->common_model->clean_session();
					$this->session->set_flashdata('error',$this->lang->line('err_acc_suspend'));
				}
				elseif($get_user->Status=='Delete')
				{
					$this->common_model->clean_session();
					$this->session->set_flashdata('error',$this->lang->line('err_acc_delete'));
				}
				else
				{
					$this->ip_date = $this->common_model->get_date_ip();
					$value_array = array(   
										'Status'=>'Enable',
										'ConfirmDate'=>$this->ip_date->cur_date,
										// 'ConfirmIp'=>$this->ip_date->ip,
									);
					$this->common_model->Update('users',$value_array,array('Id'=>$get_user->UserId));
					$this->common_model->clean_session();
					$this->session->set_flashdata('notification',$this->lang->line('acc_conf_succ'));
				}
			}
			else // NOT FOUND USERID
			{
				$this->session->set_flashdata('error',$this->lang->line('err_token'));
			}
		}
		redirect(site_url());
		exit;
	}

	public function logout()			//LOGOUT - DESTROY ALL SESSION DATA
	{
		$this->common_model->clean_session();
		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->common_model->csrfguard_generate_token($data['unique_form_name']);
		redirect(site_url());
		exit;
	}
}
