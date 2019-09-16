<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forgot extends CI_Controller {

	public function index()
	{
		$email = $this->input->post('forgot_email');
		if($email!='' && filter_var($email, FILTER_VALIDATE_EMAIL)){
				$temp = array(
					'email' => strtolower($this->input->post('forgot_email')),
				);
				$res = (object) $this->user_forgot($temp);
				$msg = $res->content;
				// $url = site_url('api/forgot');
				// $res1 = $this->common_model->php_curl($url, $temp);
				// $res = json_decode($res1);
				
				$msg = $res->content;
				if($res->res=="false"){
					echo json_encode(array('code' => 0, 'msg' => $msg));
				}else{
					echo json_encode(array('code' => 1, 'msg' => $msg));
					exit;	
				}
				
		}else{
			echo json_encode(array('code' => 0, 'msg' => 'Email Invalid!'));
			exit;
		}
		//_pre($res);

	}
	private function user_forgot($post_data){
		$email = $post_data['email'];
        $tb_pri = $this->db->dbprefix;
        if($email != '')
        {
            $result = $this->common_model->get_all_records('users', ' * ', array('Email'=>$email, 'Status !='=>'Delete'),'Id',1,0)->row();

            if($result->Id != '')
            {
                if($result->Status == 'Enable' || $result->Status == 'Temp')
                {
                    $pass_token = $token = $this->common_model->create_pwd_salt(5);                     
                    $this->ip_date = $this->common_model->get_date_ip();
                    
                    $this->common_model->custom_query("INSERT INTO ".$tb_pri."user_forgot (UserId, Token, CreatedDate, CreatedIp) VALUE ('".$result->Id."', '".$pass_token."', '".$this->ip_date->cur_date."', '".$this->ip_date->ip."') ON DUPLICATE KEY UPDATE Token='".$pass_token."', CreatedDate='".$this->ip_date->cur_date."', CreatedIp='".$this->ip_date->ip."' ");
                        
                    $get_email = $this->common_model->get_all_records('mailsettings','*',array('Id'=>2),'Id',1,0)->row();
                    
                    $to_email= $email;
                    $from_email=$get_email->Email;
                    $from_text=$get_email->FromText;
                    $subject=$get_email->Subject;
                    $link = site_url('recover_pwd/index/'.$pass_token);
                    
                    $message  = str_replace('[SITE_NAME]',$this->site_setting->site_name,$get_email->Content);
                    $message  = str_replace('[SITE_LOGO]',site_url('themes/image/logo.png'),$message);
                    $message  = str_replace('[SITE_URL]',$this->site_setting->site_url,$message);
                    $message  = str_replace('[LINK]',$link,$message);
                   
                    $result=$this->common_model->send_mail($to_email,$from_email,$subject,$from_text,$message,'');                   
                    
                    return array('res'=>'true','content'=>"We've sent you reset password token on your email address.");
                    
                }
                elseif($result->Status == 'Delete')
                {   
                    return array('res'=>'false','content'=>'Authentication failed...');
                }
                else
                {
                    return array('res'=>'false','content'=>'Your account has been suspended.');
                }
            }
            else
            {
                return array('res'=>'false','content'=>'You have entered wrong email address.');
            }
        }
        else
        { 
            return array('res'=>'false','content'=>'Please enter your email address.');
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
}
