<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class Forgot_new extends REST_Controller 
{
    public function __construct()
    {   
        parent::__construct();
        $this->load->model('general/common_model');     
        // $apikey = $this->input->post('apikey'); 
        // $apikey = $apikey==''?$this->input->get('apikey'):$apikey;  
        // if($apikey != REST_API_SECRET_KEY){
        //     $this->response(array('res'=>'false','content'=>$this->lang->line('err_authentication')), 200); exit;
        // }       
    }
    function index_post()
    {           
        $email = $this->input->post('email');
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
                    echo 'test';
                    exit;   
                    $this->common_model->custom_query("INSERT INTO ".$tb_pri."user_forgot (UserId, Token, CreatedDate, CreatedIp) VALUE ('".$result->Id."', '".$pass_token."', '".$this->ip_date->cur_date."', '".$this->ip_date->ip."') ON DUPLICATE KEY UPDATE Token='".$pass_token."', CreatedDate='".$this->ip_date->cur_date."', CreatedIp='".$this->ip_date->ip."' ");
                        
                    $get_email = $this->common_model->get_all_records('mailsettings','*',array('Id'=>2),'Id',1,0)->row();
                    echo '<pre>';
                    print_r($get_email);
                    exit;
                    $to_email= $email;
                    $from_email=$get_email->Email;
                    $from_text=$get_email->FromText;
                    $subject=$get_email->Subject;
                    $link = site_url('recover_pwd/index/'.$pass_token);
                    
                    $message  = str_replace('[SITE_NAME]',$this->site_setting->site_name,$get_email->Content);
                    $message  = str_replace('[SITE_LOGO]',site_url('themes/image/logo.png'),$message);
                    $message  = str_replace('[SITE_URL]',$this->site_setting->site_url,$message);
                    $message  = str_replace('[LINK]',$link,$message);
                    echo '<pre>';
                    print_r($message);
                    exit;
                    $result=$this->common_model->send_mail($to_email,$from_email,$subject,$from_text,$message,'');                   
                    
                    // if($result!='')
                    // {
                        $this->response(array('res'=>'true','content'=>$this->lang->line('api_forget_msg')), 200);
                    // }
                    // else
                    // {
                    //     $this->response(array('res'=>'false','content'=>$this->lang->line('mail_send_fail')), 200);
                    // }                   
                }
                elseif($result->Status == 'Delete')
                {   
                    $this->response(array('res'=>'false','content'=>$this->lang->line('err_authentication')), 200);
                }
                else
                {
                    $this->response(array('res'=>'false','content'=>$this->lang->line('err_acc_suspend')), 200);
                }
            }
            else
            {
                $this->response(array('res'=>'false','content'=>$this->lang->line('api_wrong_emai')), 200);
            }
        }
        else
        { 
            $this->response(array('res'=>'false','content'=>$this->lang->line('val_mail_err')), 200);
        }           
    }
}