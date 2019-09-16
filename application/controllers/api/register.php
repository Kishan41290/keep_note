<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class Register extends REST_Controller
{
	public function __construct()
	{ 
		parent::__construct();
		$apikey = $this->input->post('apikey');	
		$this->load->model('general/common_model', 'cm');
		$apikey = $apikey==''?$this->input->get('apikey'):$apikey;
		// if($apikey != REST_API_SECRET_KEY){
		// 	$this->response(array('res'=>'false','content'=>$this->lang->line('err_authentication')), 200);exit;
		// }
	}
	function index_post()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		// $gcm_token = $this->post('token');  // GCM token - for push bnotification
		// $device = $this->post('device');  // DEVICE TYPE - ANDROID , IPHONE or IPAD
		$type = $this->post('type');
		$tb_pri = $this->db->dbprefix;
		
		$this->set_rules();  // SET VALIDATION
		if ($this->form_validation->run() === TRUE) {

			$chk_email = $this->cm->get_all_records('users','*', array('Email'=>$email,
			'Status !='=>'Delete'), '', 1 )->row();

			if(empty($chk_email)){

				$pwd = $password;
				$this->_salt = $this->cm->create_pwd_salt();
				$this->_password =  hash('sha256', $this->_salt . ( hash('sha256',$pwd)) );
				$this->ip_date = $this->cm->get_date_ip();
				$value_array = array(
									'Email' => $email,
									'Password' => $this->_password,
									'Salt' => $this->_salt,
									'Status'=>'Pending',
									'CreatedDate'=>$this->ip_date->cur_date,
									'CreatedIp'=>'123456', // $this->ip_date->ip,
								);
			
				$insert_id = $this->cm->insert('users',$value_array);
				
				// SEND MAIL FOR VARIFICATION LINK.
				$this->send_email_verification($insert_id, $email, '', '');

				
				// $query = "INSERT INTO ".$tb_pri."device (DeviceType,GCMToken,UserId,CreatedDate,UpdatedDate,CreatedIp) VALUES ('".$device."','".$gcm_token."','".$insert_id."','".$this->ip_date->cur_date."','".$this->ip_date->cur_date."','".$this->ip_date->ip."') ON DUPLICATE KEY UPDATE UpdatedDate='".$this->ip_date->cur_date."'";
				// $this->cm->custom_query($query);

				$this->response(array('res'=>'true','content'=>$this->lang->line('suc_msg_account_create'),
					'auth_key' => $this->cm->get_auth_key($insert_id), 'user_id'=>$insert_id,
					'Email' => $email));

			}else{
				$this->response(array('res'=>'false','content'=>$this->lang->line('email_exist')), 200);
			}

		}
		else{
			$this->response(array('res'=>'false','content'=>validation_errors()), 200);
		}

	}



	function send_email_verification($id, $email, $fname, $lname){
		// TO SEND EMAIL FOR USER VERIFICATION.
		$token = $this->cm->generateToken(8); //TOKEN FOR CONFIRMATION.
		$value_array = array(   
							'UserId' =>$id,
							'Token' => $token,
						);
		$this->cm->replace('user_confirmation',$value_array);
		
		$get_email = $this->cm->get_all_records('mailsettings','*',array('Id'=>4),'Id',1)->row(); 	// GET USER TEMPLATE DATA.
		$to_email = $email;
		$from_email=$this->cm->filterOutput($get_email->Email);
		$from_text=$this->cm->filterOutput($get_email->FromText);
		$subject=$this->cm->filterOutput($get_email->Subject);
		$link = site_url('register/confirm/'.$token);						
		$message  = str_replace('[SITE_NAME]',$this->site_setting->site_name,$get_email->Content);
		$message  = str_replace('[SITE_LOGO]',site_url('themes/image/logo.png'),$message);			
		// $message  = str_replace('[FIRST_NAME]',$fname,$message);
		// $message  = str_replace('[LAST_NAME]',$lname,$message);
		$message  = str_replace('[LINK]',$link,$message);
		$message  = str_replace('[CONTACT_EMAIL]',$this->site_setting->site_email,$message);
		
		$result=$this->cm->send_mail($to_email,$from_email,$subject,$from_text,$message,'');
	}

	function set_rules(){
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
}