<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Profile extends CI_Controller {
	private $limit = 10;
    function __construct()
	{  
		parent::__construct();		
		$this->load->model('manage/admin_model');		
		$this->load->helper('form');
		$this->load->library(array('form_validation'));			
		$this->admin_model->check_seesion(current_url(), array(ROLE_SUPPER_ADMIN,ROLE_CLIENT_ADMIN));
	}
	function index()
	{
		$id=$this->session->userdata('adminid');
        $data = array('title'=>$this->lang->line('edit_profile'),'message'=>'', 'link_back'=>site_url('manage/profile'), 'link_add'=>site_url('manage/profile/add'));
		$data['action'] = site_url('manage/profile/update/'.$id);
		$values='Id,Name,Email,Image,Address,MobileNo';
		$result = $this->admin_model->get_all_records('adminmaster',$values,array('Id'=>$id),'',1)->row();
		if(!empty($result))
		{
			$this->form_data->id = $id;
			$this->form_data->email = $result->Email;
			$this->form_data->name = $result->Name;
			$this->form_data->address = $result->Address;
			$this->form_data->mobile = $result->MobileNo;
			$this->form_data->image = $result->Image;
			$data['method']="Edit";
			$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
			$this->load->view('manage/includes/header', $data);
			$this->load->view('manage/profile_edit');
			$this->load->view('manage/includes/footer');

		}
		else
		{
			redirect('manage/home');
		}
   }
	
	function update($id)
	{
		if($id!=$this->session->userdata('adminid'))
		{
			redirect(site_url('manage/profile'));
			die();
		}
		$data = array('title'=>$this->lang->line('edit_profile'),'message'=>'', 'action'=>site_url('manage/profile/update/'.$id));
		if($this->input->post('password')!='')
			$check_pwd = "Yes";
		else 
			$check_pwd = "No";
		
		$this->_set_rules($id,$check_pwd);
		
		if(is_array($this->input->post()))
			extract($this->input->post());

		$csrf_check = $this->admin_model->csrfguard_validate_token($csrf_name,$csrf_token);
		if($this->form_validation->run() === TRUE && $csrf_check==true)
		{
			if($_FILES['file']['name']!='')
			{
				$config['upload_path'] = './uploads/profile/big';
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$config['max_size']	= '10240';
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('file'))
				{	
					$error = array('error' => $this->upload->display_errors());
					$data['upload_error'] = $error;
					$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
					$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
					$this->load->view('manage/includes/header', $data);
					$this->load->view('manage/profile_edit');
					$this->load->view('manage/includes/footer');
					return;
				}
				else
				{
					$file_name = $this->upload->file_name;
					$this->logo = $file_name;
					$this->create_thumb($file_name,60,60,'thumb'); 
				}
			}

			$update_array =  array('Name' => $this->input->post('name'),
									'Email' => $this->input->post('email'),
									'Address' => $this->input->post('address'),
									'MobileNo' => $this->input->post('mobile_no')
			);


			if($this->input->post('password')!='')
			{				
				$this->_salt = $this->admin_model->create_pwd_salt();
				$this->_password = hash('sha256',$this->input->post('password'));
				$this->_password =  hash('sha256', $this->_salt . $this->_password);
				$update_array = array(
									'Password' => $this->_password ,
									'Salt' => $this->_salt
								);
			}
			if($file_name!='') 
			{
				$update_array['Image'] = $file_name; $this->session->set_userdata('admin_image',$file_name); 
			}
			$this->admin_model->update('adminmaster',$update_array,array('Id'=>$id));
			$this->session->set_userdata('adminname',$this->input->post('name'));

			//REDIRECT
			$this->session->set_flashdata('notification',$this->lang->line('profile_succ_modified'));
            redirect(site_url('manage/profile'));
			die();
		}
		else
		{ 
		    if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');
			$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
			$data['method']="Edit";
			$this->load->view('manage/includes/header', $data);
			$this->load->view('manage/profile_edit');
			$this->load->view('manage/includes/footer');

		}
	 }
    function check_exists($emailID, $id='')
  	{
		$data['result'] = $this->admin_model->check_record_exist('adminmaster','Id',array('Email'=>$emailID));
	  	if (!empty($data['result']))
		{
			if($id !='' && $id==$data['result']['Id'])
			{
				return TRUE;
			}
			else
			{
				$this->form_validation->set_message('check_exists',$this->lang->line('email_exist'));
				return FALSE;
			}
		}		
		return TRUE;
	}



	function _set_rules($id='',$check_pwd='Yes')
	{
		$this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required');
		if($id=='')
		{
			$this->form_validation->set_rules('email',$this->lang->line('email'),'trim|required|valid_email|callback_check_exists');
		}
		else
		{
			$this->form_validation->set_rules('email',$this->lang->line('email'),'');
		}
		if($check_pwd=='Yes')
		{
			$this->form_validation->set_rules('password',$this->lang->line('password'),'required|min_length[6]|max_length[15]');
			$this->form_validation->set_rules('confirm_password',$this->lang->line('cnf_password'),'required|matches[password]');
		}
		else
		{
			$this->form_validation->set_rules('password',$this->lang->line('password'),'min_length[6]|max_length[15]');
			$this->form_validation->set_rules('confirm_password',$this->lang->line('cnf_password'),'');
		}
		$this->form_validation->set_rules('image','','');

		$this->form_validation->set_rules('mobile_no','','');
		$this->form_validation->set_rules('address','','');
	}

	function create_thumb($file,$width=60,$height=60)
	 {
		$config['image_library'] = 'gd2';
		$config['source_image'] = './uploads/profile/big/'.$file;
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = FALSE;
		$config['width'] = $width;
		$config['height'] = $height;
		$config['new_image'] = './uploads/profile/thumb/'.$file;
		$this->load->library('image_lib', $config);
		$this->image_lib->resize();
		if(!$this->image_lib->resize())
		{
			$data['error'] = $this->image_lib->display_errors();
			$this->load->view('manage/includes/header', $data);
			$this->load->view('manage/admin_edit');
			$this->load->view('manage/includes/footer');
		}
		else
			$this->image_lib->clear();
		
	 }
	
}
