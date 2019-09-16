<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->app_version = getallheaders()['app_version'];
        $this->os_version = getallheaders()['os_version'];
        $this->device_id = getallheaders()['device_id'];
        $this->os = getallheaders()['os'];
        $this->user_id = getallheaders()['user_id'];
        $this->auth_key = getallheaders()['auth_key'];
        $this->language = getallheaders()['language'];
        $this->load->model('general/common_model', 'cm');
        //set_language();
        // if ($this->language != '') {
        //     $this->language = $this->language;
        // } else {
        //     $this->language = 'english';
        // }

        // $this->lang->load('message_lang', $lan);
    }


    public function index() {
        _success($code = 400, $text = 'bad req');
        exit;
    }


    public function sign_in() {

        $os = getallheaders()['os'];
        $language = getallheaders()['language'];
        $os_version = getallheaders()['os_version'];
        $app_version = getallheaders()['app_version'];
        $api_key = getallheaders()['api_key'];
        $device_id = getallheaders()['device_id'];
        $password = $_POST['password'];
        $email = $_POST['email']!=''?$_POST['email']:'';
        $login_by = $_POST['login_by'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $profile_pic = $profile_pic!=''?$profile_pic:'';
        $created_at = date('Y-m-d');
        $lang = $language != '' ? $language : 'english';

        $this->validation_lib->sign_in();
        
        if (isset($api_key) && !empty($api_key) && $api_key == SECRET_API_KEY) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') 
            {
                if ($email != '') {  // EMAIL ENTER (WITH LOGIN)
        
                   if(filter_var($email, FILTER_VALIDATE_EMAIL))
                   {
                        $exist_user = array(
                            "Username" => $email,
                        );
                        $check_user = $this->am->getData(TBL_USER_INFO, '*', $exist_user, '1')->row();
                       
                        if(!empty($check_user)) // EXIST USER & LOGIN
                        {
                            $id = $check_user->id;
                            $exist_password = $check_user->Password;
                            $email = $check_user->Username;
                            $username = $check_user->Firstname . ' ' . $check_user->Lastname;
                            $pwd = $login_by == 'iFax' ? $password : $exist_password;
                            if ($device_id == 'Web') { // FOR WEB
                                //  echo $password." == ".$pwd;exit;
                                if ($password == $pwd) {
                                    $msg = _api_lang_msg($lang, 'signin_succ');
                                    _success("success", $msg, '', array('userid' => $id, 'email' => $email, 'username' => $username));
                                    exit;
                                } else {
                                    $msg = _api_lang_msg($lang, 'sign_in_credential');
                                    _error(0, $msg, '');
                                    exit;
                                }
                            } else { // CHECK DIVECEID AND USERID BOTH ARE SAME
                                
                                if($password!=''){
                                    if ($exist_password == $pwd) 
                                    {
                                        
                                            $exist_email_user = array(
                                                "Username" => $email,
                                                "email_verified" => '1'
                                            );
                                            $user_login = $this->am->getData(TBL_USER_INFO, '*', $exist_email_user, '1')->row();

                                            if(!empty($user_login))
                                            {
                                                $device_count = $this->am->rowCount(TBL_SHARED_NUMBER, 'parentID = "' . $id . '" AND logedin = 0');
                                                if ($device_count > 4) {  // CHECK DEVICE LOGIN IN 5 DEVICE
                                                    $msg = _api_lang_msg($lang, 'max_devices');
                                                    $title = _api_lang_msg($lang, 'oops');

                                                       $res[] = array("error_code" => "1256");
                                                       _api_error(0,'', $title, $msg, $res);

                                                    exit;
                                                } 
                                                else {
                                                    if ($device_count < 5) {
                                                            $exist_device = array(
                                                                "parentID" => $id,
                                                                "userID" => $device_id,
                                                            );
                                                            $check_device = $this->am->getData(TBL_SHARED_NUMBER, '*', $exist_device, '1')->row();

                                                            if (empty($check_device) && $check_device == '') {
                                                                $device_manage = array(
                                                                    "userID" => $device_id,
                                                                    "parentID" => $id,
                                                                    "secretCode" => '',
                                                                    "logedin" => '0',
                                                                    "push_notification" => '1',
                                                                );
                                                                $this->am->insert(TBL_SHARED_NUMBER, $device_manage);
                                                            }
                                                    }

                                                    $device_list = $this->am->get_where(TBL_SHARED_NUMBER, array('parentID' => $id, 'logedin' => '0'));
                                                        
                                                    $temp_device = array();
                                                    foreach ($device_list as $r) {
                                                        array_push($temp_device, array('device_id' => $r->userID, 'os' => $os!=''?$os:'', 'os_device' => $os_version!=''?$os_version:''));
                                                    }
                                                    $device_count = $this->am->rowCount(TBL_SHARED_NUMBER, 'parentID = "' . $id . '" AND logedin ="0"');
                                                    $user_credits = $this->am->getData(TBL_USER_INFO, 'credits', array('id'=>$id) , '1')->row();
                                                 
                                                    $res[] = array("user_id" => $id, "credits" => $user_credits->credits,
                                                        "profile_url" => $profile_pic ,  "auth_key" => get_auth_key($id), 'devices' => $temp_device);
                                                    
                                                    $title = _api_lang_msg($lang, 'logged_in');
                                                    $msg = _api_lang_msg($lang, 'signin_succ');
                                                    _api_success(1,'', $title, $msg, $res);
                                                    exit;
                                                }
                                            }    
                                            else{
                                                $title = _api_lang_msg($lang, 'Logged_in');
                                                $msg = _api_lang_msg($lang, 'email_unverified');
                                                $data = array('error_code' => "12010");
                                                _api_error(0,'', $title, $msg, $data);
                                                exit;
                                            }   
                                      
                                    } else {



                                        $desc = "Please enter valid password."; // _api_lang_msg($lang, 'email_pwd_wrong');
                                        $title = _api_lang_msg($lang, 'oops');
                                        $data = array('error_code' => "12010");
                                        _api_error(0,'',$title, $desc, $data);
                                        exit; 
                                    }

                                }else{
                                   $desc = _api_lang_msg($lang, 'pwd_required');
                                   $title = _api_lang_msg($lang, "oops");
                                   $data = array('error_code' => "12010");
                                   _api_error(0,'',$title, $desc, $data);
                                   exit; 
                                }
                            }
                        }else{  // SIGNUP
                            if($password!=''){ 
                                $length = 10;
                                $validCharacters = TOKEN_CHARECTERS;
                                $validCharNumber = strlen($validCharacters);
                                $result_token = "";

                                for ($i = 0; $i < $length; $i++) {
                                    $index = mt_rand(0, $validCharNumber - 1);
                                    $result_token .= $validCharacters[$index];
                                }
                             
                                $user_arr = array(
                                            'Firstname'        => $first_name,
                                            'Lastname'         => $last_name,
                                            'Username'         => $email,
                                            'Password'         => $password,
                                            'Platform'         => '',
                                            'socialLogin'      => $login_by,
                                            'registrationDate' => date('Y-m-d'),
                                            'credits'          => '0',
                                            'device_id'        => $device_id,
                                            'is_used'          => '0',
                                            'reset_token'      => $result_token,
                                            'reset_token_date' => date('Y-m-d h:i:s'),
                                            'email_verified'   => '0'     
                                            );
                                $ins_id = $this->am->insert(TBL_USER_INFO, $user_arr);
                                $this->am->edit(TBL_USER_INFO, array('id' => $ins_id), array('No' => $ins_id));    
                                
                                $token_data = array(
                                    'reset_token' => $result_token,
                                    'reset_token_date' => date('Y-m-d H:i:s'),
                                );
                                //Update In DB Table user_info
                                $this->am->edit(TBL_USER_INFO, $token_data, array('No' => $ins_id));

                                // SEND TOKEN LINK ON MAIL
                                $tok_data = urlencode(base64_encode($email . ':::' . $result_token));
                                $TOKEN = URL.'reset_password/'.$tok_data;
                                
                                $sub = "iFax email confirmation";
                                $msg = "Hi ".$email.",<br><br>"
                                        . "Please <a href='".$TOKEN."'>click here to set</a> your iFax account password.<br><br>"
                                        . "This link is valid for the next 24 hours.<br><br>"
                                        . "Thanks for using iFax!<br><br>"
                                        . "Best,<br><br>";
                                $email_status = send_mail($email, '', SUPPORT, 'iFax Team', '', '', $sub, $msg, '');
                                if($login_by == LOGIN_BY_FACEBOOK) {
                                    $this->am->email_tracking($email, 'SIGNUP_Facebook', $email_status, $id, TBL_USER_INFO);
                                } else if ($login_by_track == LOGIN_BY_GOOGLE) {
                                    $this->am->email_tracking($email, 'SIGNUP_Google', $email_status, $id, TBL_USER_INFO);
                                } else if ($login_by_track == LOGIN_BY_LINKEDIN) {
                                    $this->am->email_tracking($email, 'SIGNUP_LinkedIn', $email_status, $id, TBL_USER_INFO);
                                } else {
                                    $this->am->email_tracking($email, 'SIGNUP_iFax', $email_status, $id, TBL_USER_INFO);
                                }

                                // INSERT INTO DEVICE TABLE.   
                                $device_manage = array(
                                    "userID" => $device_id,
                                    "parentID" => $ins_id,
                                    "secretCode" => '',
                                    "logedin" => '0',
                                    "push_notification" => '1',
                                );
                                $this->am->insert(TBL_SHARED_NUMBER, $device_manage);
                               
                                $device_list = $this->am->get_where(TBL_SHARED_NUMBER, array('parentID' => $ins_id, 'logedin' => '0'));
                                $temp_device = array();
                                $temp_device['device_id'] = $device_id;
                                $temp_device['os'] = $os!=''?$os:'';
                                $temp_device['os_device'] = $os_version!=''?$os_version:'';
                                
                                $res[] = array("error_code" => '12032', "user_id" => $ins_id, "credits" => 0,
                                                    "profile_url" => $profile_pic ,  "auth_key" => get_auth_key($ins_id), 'devices' => $temp_device);

                                $title = _api_lang_msg($lang, 'logged_in');
                                $msg = _api_lang_msg($lang, 'signup_succ');
                                _api_success(1,'', $title, $msg, $res);
                                exit;
                            }else{
                               $desc = _api_lang_msg($lang, 'pwd_required');
                               $title = _api_lang_msg($lang, "oops");
                               $data = array('error_code' => "12010");
                               _api_error(0,'',$title, $desc, $data);
                               exit; 
                            }
                         
                        }   
                    }else{
                       $desc = "Please enter valid email."; // _api_lang_msg($lang, 'email_required');
                       $title = _api_lang_msg($lang, "oops");
                       $data = array('error_code' => "12010");
                       _api_error(0,'',$title, $desc, $data);
                       exit; 
                    }
                } else {   // NOT ENTER EMAIL OR PASSWORD (WITHOUT SIGNUP)
                   $desc = _api_lang_msg($lang, 'email_required');
                   $title = _api_lang_msg($lang, "oops");
                   $data = array('error_code' => "12010");
                   _api_error(0,'',$title, $desc, $data);
                   exit; 
                }
            } else {
                $desc = _api_lang_msg($lang, 'invalid_request_method');
                $title = _api_lang_msg($lang, 'oops');
                $data = array('error_code' => "12010");

                _api_error(0,'',$title, $desc, $data);
                exit; 
            }
        } else {
            $desc = _api_lang_msg($lang, 'invalid_api_key');
            $title = _api_lang_msg($lang, 'oops');
            $data = array('error_code' => "12010");

            _api_error(0,'',$title, $desc, $data);
            exit; 
           
        }
    }
    
 
}