<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {

	public function index()
	{
		$email = strtolower($this->input->post('email'));
		
		$password = $this->input->post('password');
		$cnf_password = $this->input->post('cnf_password');
		if($email!='' && filter_var($email, FILTER_VALIDATE_EMAIL)){
			if($password!='' && strlen($password)>6 && $password==$cnf_password){
				$temp = array(
					'email' => strtolower($this->input->post('email')),
					'password' => $this->input->post('password'),
				);
				
				// $url = site_url('api/register');
				// $res1 = $this->common_model->php_curl($url, $temp);
				// $res = json_decode($res1);
				// $msg = $res->content;
				$res = (object) $this->user_register($temp);
			//	print_r($res);
			//	exit;
				if($res->res=="false"){
					echo json_encode(array('code' => 0, 'msg' => $res->content));
				}else{
					echo json_encode(array('code' => 1, 'msg' => $res->content));
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

	private function user_register($post_data){
		$email = $post_data['email'];
		$password = $post_data['password'];
	//	$type = $post_data['type'];
		$tb_pri = $this->db->dbprefix;
		$this->set_rules();  // SET VALIDATION
		if ($this->form_validation->run() === TRUE) {

			$chk_email = $this->common_model->get_all_records('users','*', array('Email'=>$email,
			'Status !='=>'Delete'), '', 1 )->row();

			if(empty($chk_email)){

				$pwd = $password;
				$this->_salt = $this->common_model->create_pwd_salt();
				$this->_password =  hash('sha256', $this->_salt . ( hash('sha256',$pwd)) );
				$this->ip_date = $this->common_model->get_date_ip();
				$value_array = array(
									'Email' => $email,
									'Password' => $this->_password,
									'Salt' => $this->_salt,
									'Status'=>'Pending',
									'CreatedDate'=>$this->ip_date->cur_date,
									'CreatedIp'=>'123456', // $this->ip_date->ip,
								);
			
				$insert_id = $this->common_model->insert('users',$value_array);
				
				// SEND MAIL FOR VARIFICATION LINK.
				$this->send_email_verification($insert_id, $email, '', '');

				return array('res'=>'true','content'=>'Account has been created successfully.  Please verify your account by email to get started.',
					'auth_key' => $this->common_model->get_auth_key($insert_id), 'user_id'=>$insert_id,
					'Email' => $email);

			}else{
				return array('res'=>'false','content'=>'This email is already registered. Please login to access your account.');
			}

		}
		else{
			return array('res'=>'false','content'=>validation_errors());
		}



	}
	function send_email_verification($id, $email, $fname, $lname){
		// TO SEND EMAIL FOR USER VERIFICATION.
		$token = $this->common_model->generateToken(8); //TOKEN FOR CONFIRMATION.
		$value_array = array(   
							'UserId' =>$id,
							'Token' => $token,
						);
		$this->common_model->replace('user_confirmation',$value_array);
		
		$get_email = $this->common_model->get_all_records('mailsettings','*',array('Id'=>4),'Id',1)->row(); 	// GET USER TEMPLATE DATA.
		$to_email = $email;
		$from_email=$this->common_model->filterOutput($get_email->Email);
		$from_text=$this->common_model->filterOutput($get_email->FromText);
		$subject=$this->common_model->filterOutput($get_email->Subject);
		$link = site_url('register/confirm/'.$token);						
		$message  = str_replace('[SITE_NAME]',$this->site_setting->site_name,$get_email->Content);
		$message  = str_replace('[SITE_LOGO]',site_url('themes/image/logo.png'),$message);			
		// $message  = str_replace('[FIRST_NAME]',$fname,$message);
		// $message  = str_replace('[LAST_NAME]',$lname,$message);
		$message  = str_replace('[LINK]',$link,$message);
		$message  = str_replace('[CONTACT_EMAIL]',$this->site_setting->site_email,$message);
		
		$result=$this->common_model->send_mail($to_email,$from_email,$subject,$from_text,$message,'');
	}

	private function set_rules(){
		$this->form_validation->set_error_delimiters('', '');
		// $this->form_validation->set_rules('fname',  'fname','trim|required|max_length[12]');
		// $this->form_validation->set_rules('lname',  'lname','trim|required|max_length[12]');
		// $this->form_validation->set_rules('address',  'address','trim|required');
		// $this->form_validation->set_rules('device',  'device','trim|required');
		// $this->form_validation->set_rules('token',  'token','trim|required');
		// $this->form_validation->set_rules('type',  'type','trim|required');
		$this->form_validation->set_rules('email',  'email','trim|required|valid_email');
		$this->form_validation->set_rules('password', 'password', 'required|min_length[6]|max_length[12]');
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
					$this->session->set_flashdata('warning','Your account is already confirmed. Please login to continue.');
				}
				elseif($get_user->Status=='Disable')
				{
					$this->common_model->clean_session();
					$this->session->set_flashdata('error','Account suspended by admin.');
				}
				elseif($get_user->Status=='Delete')
				{
					$this->common_model->clean_session();
					$this->session->set_flashdata('error','Your account has been deleted.');
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
					$this->session->set_flashdata('notification','Your account is confirmed successfully. Please login to continue.');
				}
			}
			else // NOT FOUND USERID
			{
				$this->session->set_flashdata('error','Invalid token.');
			}
		}
		redirect(site_url());
		exit;
	}
}
