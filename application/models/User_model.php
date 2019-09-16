<?php
class User_model extends CI_Model {
	public function __construct()
	{
		
	}
	function send_mail($to_email,$from_email,$subject,$from_text,$message,$password,$replyto=''){
			$config = Array(
					  'useragent' => 'CodeIgniter',
					  'protocol' => 'smtp',
					  'mailpath' => '/usr/sbin/sendmail',
					  'smtp_host' => $this->site_setting->smtp_host,
					  'smtp_user' => $from_email,	
					  'smtp_pass' =>$password,
					  'smtp_port' => $this->site_setting->smtp_port,
					  'smtp_timeout' =>15,
					  'wordwrap' => TRUE,
					  'mailtype' => 'html',
					  'charset' => 'utf-8',
					  'validate' =>FALSE,
					  'priority' => 3,
					  'newline' => "\r\n",
					  'bcc_batch_mode' => FALSE,
					  'bcc_batch_size' => 200,
					  //'smtp_crypto' => 'ssl',
				  );
	
			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");
			$this->email->from($from_email,$from_text);
			if($replyto!='')
			$this->email->reply_to($replyto,'');					   
			$this->email->to($to_email);
			$this->email->subject($subject);   // should be subject as required and use admin message file to write subject so we can use multi language
			
			$this->email->message($message); 
			
			return $this->res=$this->email->send();
	}	
	function send_mail2($to_email,$from_email,$from_text,$subject1,$message){
			$config = Array(
					  'useragent' => 'CodeIgniter',
					  'protocol' => 'smtp',
					  'mailpath' => '/usr/sbin/sendmail',
					  'smtp_host' => $this->site_setting->smtp_host,
					  'smtp_user' => $this->site_setting->site_smtp_email,
					  'smtp_pass' => $this->site_setting->site_smtp_pwd,
					  'smtp_port' => $this->site_setting->smtp_port,
					  'smtp_timeout' =>15,
					  'wordwrap' => TRUE,
					  'mailtype' => 'html',
					  'charset' => 'utf-8',
					  'validate' =>FALSE,
					  'priority' => 3,
					  'newline' => "\r\n",
					  'bcc_batch_mode' => FALSE,
					  'bcc_batch_size' => 200,
					  //'smtp_crypto' => 'ssl',
				  );
	
			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");
			$this->email->from($from_email,$from_text);
			$this->email->to($to_email);
			$this->email->subject($subject1); 
			$this->email->message($message); 
			
			return $this->res=$this->email->send();
	}		
	function check_seesion($target_url='')
	{
		if($this->session->userdata('user_logged_in') == FALSE)
		{
			redirect('home#login');
			exit;
		}else{
			$result = $this->db->select('Id,FullName,Email,UserType,FacebookId,Status')->from('user')->where('Id',$this->session->userdata('userid'))->where(array("Status !="=>"Delete"))->limit(1)->get()->row();
			if($result->Id =='')
			{
				$this->session->set_flashdata('error',$this->lang->line('session_expired'));
				$array_items = array('username'=>'', 'userid'=>'', 'user_logged_in'=>FALSE,'user_temp'=>FALSE,'email'=>'','username'=>'','usertype'=>'');
				$this->session->unset_userdata($array_items);
				redirect('home#login');
				exit;
			}
			elseif($result->Status == 'Disable')
			{
				$this->session->set_flashdata('error',$this->lang->line('err_acc_suspend'));
				$array_items = array('username'=>'', 'userid'=>'', 'user_logged_in'=>FALSE,'user_temp'=>FALSE,'email'=>'','username'=>'','usertype'=>'');
				$this->session->unset_userdata($array_items);
				redirect('home#login');
				exit;
			}
		}
		return true;
	}
	
	function check_login($table, $where_array)
	{
		$query = $this->db->get_where($table, $where_array);
		return $query->row_array();	
	}
	function convert_timezone($datetime,$timezone='UTC',$local='')
	{
		$zone_key = timezones($timezone);
		if($local == 'local'){ // GMT TO LOCAL
			$converted_date = $datetime + ($zone_key * 3600);		
		}else{  // LOCAL TO GMT
			$converted_date = $datetime - ($zone_key * 3600);		
		}
		return $converted_date;	
	}
	function human_unix($datetime,$type='')
	{
		
		if($type == 'human'){ // UNIX TO HUMAN
			$converted_date = unix_to_human($datetime,TRUE);
		}else{  // HUMAN TO UNIX
			$converted_date = human_to_unix($datetime);
		}
		return  $converted_date;
	}
	function show_date($date,$format='')
	{
		if($format == ''){
			$ret_date = mdate('%Y-%m-%d %h:%i:%s %A',$date);
		}elseif($format == 'date'){
			$ret_date = mdate('%d %M, %Y',$date);
		}elseif($format == 'time'){
			$ret_date = mdate('%h:%i:%s %a',$date);
		}elseif($format == 'time_24'){
			$ret_date = mdate('%H:%i:%s',$date);
		}elseif($format == 'total_minutes'){
			$ret_date = mdate('%i',$date);
		}elseif($format == 'days'){
			$ret_date = mdate('%m-%d-%Y %h:%i:%s',$date);
		}elseif($format == 'flip'){
			$ret_date = mdate('%Y-%m-%d %h:%i:%s',$date);
		}		
		return $ret_date;
	}
	function php_date($get_date,$type='date')
	{
		$date=date_create($get_date);
		if($type == 'time'){
			return date_format($date,'h:i A');
		}
		else{
			return date_format($date,'l,F d,Y');
		}
	}
	function string_display($str)
	{
		return substr($str,0,10)."...";
	}
}
