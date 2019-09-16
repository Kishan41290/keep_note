<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');  
class Login extends REST_Controller
{
	public function __construct()
	{ 
		parent::__construct();
		$this->load->model('general/common_model', 'cm');
		$apikey = $this->input->post('apikey');	
		$apikey = $apikey==''?$this->input->get('apikey'):$apikey;
		// if($apikey != REST_API_SECRET_KEY){
		// 	$this->response(array('res'=>'false','content'=>$this->lang->line('err_authentication')), 200);exit;
		// }
	}
	function index_post()
	{
		$email = $this->input->post('email');
		$pwd = $this->input->post('password');
	//	$gcm_token = $this->post('token');  // GCM token - for push bnotification
	//	$device = $this->post('device');  // DEVICE TYPE - ANDROID , IPHONE or IPAD
		$login_by = $this->post('login_by'); 
		$tb_pri = $this->db->dbprefix;
	
		if($pwd != '' && $email != '' && $login_by!='')
		{ 
			$result = $this->cm->get_all_records('users','*', array('Email'=>$email,
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
					$this->ip_date = $this->cm->get_date_ip();
					// $query = "INSERT INTO ".$tb_pri."device (DeviceType,GCMToken,UserId,CreatedDate,UpdatedDate,CreatedIp) VALUES ('".$device."','".$gcm_token."','".$result->Id."','".$this->ip_date->cur_date."','".$this->ip_date->cur_date."','".$this->ip_date->ip."') ON DUPLICATE KEY UPDATE UpdatedDate='".$this->ip_date->cur_date."'";
					// $this->cm->custom_query($query);
						
					 if($result->Status=='Enable')
					 {
					 	$this->response(array('res'=>'true','content'=>$this->lang->line('succ_msg_login'),
					'auth_key' => $this->cm->get_auth_key($result->Id), 'user_id'=>$result->Id,
					'first_name'=>$result->FirstName,'last_name'=>$result->LastName,'Email' => $email,'user_type'=>$result->Type));
					 
					 }
					 elseif($result->Status=='Disable')
					 {
						 $this->response(array('res'=>'false','content'=>$this->lang->line('err_acc_suspend')), 200);
					 }
					 else
					 {
						 $this->response(array('res'=>'false','content'=>$this->lang->line('err_acc_deleted')), 200);
					 }
					
				}
				else
				{
					$this->response(array('res'=>'false','content'=>$this->lang->line('err_valid_pwd')), 200);
				}	
			}
			else
			{
				$this->response(array('res'=>'false','content'=>$this->lang->line('api_wrong_email')), 200);
			}
		}
		else
		{ 
			$this->response(array('res'=>'false','content'=>$this->lang->line('api_require_msg')), 200);
		}			
	}


}