<?php
class Otp extends CI_Controller {

    public $code=404;
    public $status = 'error';
    public $msg='';
    public $details='';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('manage/common_model');
    }

    // USER LOGIN
    public function index()
    {
        $otp = $this->input->post('otp');
        $email = $this->input->post('email');

        if($otp!='' && $email != ''){

            $user_res = $this->common_model->get_all_records('user','Id,Email,Name,MobileToken',array('Email'=>$email, 'Status != '=>'Delete' ),'Id DESC',1)->row();

            if(!empty($user_res)){
                if($otp==$user_res->MobileToken){
                    $val_arr = array(
                                    'Status' => 'Enable',
                                );
                    $this->common_model->update('user', $val_arr, array('Id' => $user_res->Id));

                    $this->code = '200';
                    $this->status = 'success';
                    $this->msg = "Registration process successfully!.";
                    $this->details = array('next_step' => 'Login');
                }else{
                    $this->msg = "Please, Enter valid one time password.";
                }
            }else{
                $this->msg = "Please, Enter valid email.";
            }
        }else{
            $this->msg = "Please, enter OTP and email.";
        }
        $this->output();
    }

    private function output()
    {
        $resp=array(
            'code'=>$this->code,
            'status'=>$this->status,
            'msg'=>$this->msg,
            'user_data'=>$this->details
        );
        echo json_encode($resp);
    }



}
