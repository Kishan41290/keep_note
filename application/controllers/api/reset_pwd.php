<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class Resetpassword extends REST_Controller 
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
        $pwd = $this->input->post('password');      
        $token = $this->input->post('token');
        $tb_pri = $this->db->dbprefix;
        if($email != '' && $token != '' && $pwd != '')
        {
            $value=('user.Status,user.Id,user.Email,user.UserType,user_forgot.*');
            $joins = array
                       (
                          array
                            (
                              'table' => 'user',
                              'condition' => 'user_forgot.UserId = user.Id',
                              'jointype' => 'inner'
                             ),
                        );
            $result = $this->common_model->get_joins('user_forgot',$value,$joins,array('user_forgot.Token'=>$token,'user.Email'=>$email),'user.Id','asc',1)->row();
            
            if(!empty($result))
            { 
                if($result->Status=='Enable')
                {
                    $sentdate=strtotime($result->CreatedDate);
                    $current_date=strtotime(date('Y-m-d'));
                    $diff= abs($sentdate - $current_date);
                    $difference=($diff/(60*60*24));
                    if($difference <= 5)
                    {
                        $this->_salt = $this->common_model->create_pwd_salt();
                        $this->_password = hash('sha256', $this->_salt . ( hash('sha256',$pwd) ) );
                        $value_array = array(
                                            'Password' => $this->_password ,
                                            'Salt' => $this->_salt ,
                                            );
                        
                        $this->common_model->update('user',$value_array,array('Id'=>$result->Id));
                        
                        $this->common_model->delete('user_forgot',array('UserId'=>$result->Id));
                        
                        $this->response(array('res'=>'true','content'=>$this->lang->line('gen_succ_recover')), 200);    
                    }
                    else
                    {
                        $this->response(array('res'=>'false','content'=>$this->lang->line('link_ex')), 200);
                    }
                }
                elseif($result->Status == 'Temp')
                {
                    $this->response(array('res'=>'false','content'=>$this->lang->line('err_acc_not_confm')), 200);
                }
                elseif($result->Status == 'Disable')
                {
                    $this->response(array('res'=>'false','content'=>$this->lang->line('login_act_spnd')), 200);
                }
                else
                {
                    $this->response(array('res'=>'false','content'=>$this->lang->line('err_acc_deleted')), 200);
                }   
            }
            else
            {
                $this->response(array('res'=>'false','content'=>$this->lang->line('err_msg_rec_pass')), 200);
            }
        }
        else
        {
            $this->response(array('res'=>'false','content'=>$this->lang->line('api_require_msg')), 200);
        }           
    }
}