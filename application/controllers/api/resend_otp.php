<?php
class Resend_otp extends CI_Controller {

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
        $email = $this->input->post('email');

        if($email != ''){

            $user_res = $this->common_model->get_all_records('user','Id,Email,Name,MobileToken',array('Email'=>$email, 'Status != '=>'Delete' ),'Id DESC',1)->row();

            if(!empty($user_res)){
                    $token = $this->common_model->generateRandomKey(5);
                    $val_arr = array(
                                    'Status' => 'Disable',
                                    'MobileToken' => $token,
                                );
                    $this->common_model->update('user', $val_arr, array('Id' => $user_res->Id));

                    
                    // CALL SMS SEND API.


                    $this->code = '200';
                    $this->status = 'success';
                    $this->msg = "Resend One Time Password Sent successfully!.";
                    $this->details = array('next_step' => 'OTP');

            }else{
                $this->msg = "Please, Enter valid email.";
            }
        }else{
            $this->msg = "Please, enter email.";
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
