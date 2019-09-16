<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!IS_AJAX) exit('No direct script access allowed');
class Ajax extends CI_Controller {

	// public function index()
	// {
	// 	$data = array('title' => 'About us');
	// 	$this->load->view('includes/header', $data);
	// 	$this->load->view('about');
	// 	$this->load->view('includes/footer');
	// }
	public function create_guest_user(){ // GUEST USER CREATE
		$uid = $this->input->cookie('uuid');
		if($uid==''){
			$pwd = $this->common_model->generateToken(8);	
			$this->_salt = $this->common_model->create_pwd_salt();
			$this->ip_date = $this->common_model->get_date_ip();
			$email = 'guest@'.time().'.com';
			$value_array = array(
						'FirstName' => 'Guest',
						'LastName' => 'User',
						'Email' => $email,
						'Password' => $pwd,
						'Salt' => $this->_salt,
						'Status'=>'Pending',
						'CreatedDate'=>$this->ip_date->cur_date,
						'CreatedIp'=>'123456', // $this->ip_date->ip,
					);
			$insert_id = $this->common_model->insert('users',$value_array);
			$this->common_model->setcookie('uuid', $pwd);
			echo json_encode(array('code' => 1, 'msg' => 'Guest user added successfully!'));
			exit;
		}else{
			echo json_encode(array('code' => 0, 'msg' => 'Something went wrong'));
			exit;
		}
	}
	public function check_guest_user(){	
		$uid = $this->input->cookie('uuid');
		if($uid!=''){
			echo json_encode(array('code' => 1, 'msg' => 'Guest user already exist'));
			exit;
		}else{
			echo json_encode(array('code' => 0));
			exit;
		}
	}
	public function save_note(){

		$uid = $this->common_model->get_userid();
		$note_data = $this->input->post('note_data');
		$note_color = $this->input->post('note_color');
		$id = $this->input->post('id');
		if($uid!='' && $id!=''){
			$this->ip_date = $this->common_model->get_date_ip();
			$res_note = $this->common_model->get_all_records('user_notes', '*', array('TempNoteId' => $id, 'UserId' => $uid), 'Id DESC', 1)->row();
			// echo '<pre>';
			// print_r($res_note);
			// exit;
			if(empty($res_note)){
				$ins_arr = array(
					'UserId' => $uid,
					'TempNoteId' => $id,
					'Color'	=> $note_color!=''?$note_color:'',
					'Description' => $note_data,
					'CreatedDate'=>$this->ip_date->cur_date,
					'CreatedIp'=>'123456', // $this->ip_date->ip,
				);
				$this->common_model->insert('user_notes', $ins_arr);
				echo json_encode(array('code' => 1, 'msg' => 'Note added successfully'));
			}else{
				$update_arr = array(
					'Description' => $note_data,
				);
				if($note_color!=''){
					$update_arr['Color'] = $note_color;
				}
				$this->common_model->update('user_notes', $update_arr, array('TempNoteId' => $id, 'UserId' => $uid) );
				echo json_encode(array('code' => 1, 'msg' => 'Note updated successfully'));
			}
		}else{
			echo json_encode(array('code' => 0, 'msg' => 'Something went wrong'));
		}
		exit;
		
	}

	public function get_note_count(){
		$uid = $this->common_model->get_userid();
		$res = $this->common_model->get_all_records('user_notes', 'Id', array(), 'Id DESC', 1)->row();
		echo json_encode(array('code'=>1, 'n_count' => $res->Id));
		exit;
	}

	public function delete_note(){
		$uid = $this->common_model->get_userid();
		$note_id = $this->input->post('id');
		if($uid!='' && $note_id!=''){
			$update_arr = array('Status' => 'Delete');
			$this->common_model->delete('user_notes',  array('UserId' => $uid, 'TempNoteId' => $note_id));
			// $res_count = $this->common_model->update('user_notes', $update_arr, array('UserId' => $uid, 'TempNoteId' => $note_id));	
			$res_count = $this->common_model->count_all('user_notes', array('UserId' => $uid));		
			echo json_encode(array('code'=>1, 'n_count' => $res_count));
		}else{
			$res_count = $this->common_model->count_all('user_notes', array('UserId' => $uid));		
			echo json_encode(array('code'=>0, 'n_count' => $res_count));
		}
		exit;
	}
}
