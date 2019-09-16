<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Admin extends CI_Controller {
    var $limit = 10;
	function __construct()
	{
		parent::__construct();		
		$this->load->model('manage/admin_model');		
		$this->limit = @$this->site_setting->site_admin_rowperpage;
		$this->admin_model->check_seesion(current_url(), array(ROLE_SUPPER_ADMIN)); 
	}
	function index($msg='',$offset = 0)
	{
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);

		$this->form_validation->set_rules('search','Search','trim|xss_clean');
		$search =urldecode($this->input->get('search'));
		$data = array('title'=>'Mobile User','message'=>'', 'link_add'=>site_url().'manage/admin/add/', 'edit_link'=>site_url('manage/admin/edit'),'info_link'=>site_url().'manage/admin/information/' ,'tbl'=>'user','multi_action'=> site_url('manage/admin/multiaction/'.$offset.'?search='.$search),'search_action'=> site_url().'manage/admin/','module'=>$this->lang->line('admin_magazine_user'));
		$data['list_records']=array();
		$select_value= 'Id,Image,Email,Name,Id,Status';
		if($search=='')
		{
			$this->total = $this->admin_model->count_all($data['tbl'],array('Status !='=>'Delete'));
			if($this->total!=0)
			{
				$data['list_records']= $this->admin_model->get_all_records($data['tbl'],$select_value,array('Status !='=>'Delete'),'Name ASC', $this->limit, $offset)->result();
			
			}
			else
			{
				 $data['no_data']=1;	
			}
		}
		else
		{
			$search_filter = $this->db->escape_like_str($search);
			$this->total = $this->admin_model->count_all($data['tbl'],array('Status !='=>'Delete'),"(Email Like '%".$search_filter."%' OR Name Like '%".$search_filter."%')");
			if($this->total!=0)
			{
				$data['list_records']= $this->admin_model->get_all_records($data['tbl'],$select_value,array('status !='=>'Delete'), 'Name ASC', $this->limit, $offset,"(Email Like '%".$search_filter."%' OR Name Like '%".$search_filter."%')")->result();
			}
			else
			{
				$data['search_data']='No';		
			}
			$config['suffix'] = '?search='.$this->input->get('search');	
			$data['search'] = $search;
		}
		// generate pagination
		$this->load->library('pagination');
		$offset = $this->uri->segment($uri_segment);
		$config['base_url'] = site_url('manage/admin/index/');
		$config['total_rows'] = $this->total;
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['j'] = 0 + $offset;
		$data['total_rows']= $this->total;
		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
		//load view
		$this->load->view('manage/includes/header', $data);
		$this->load->view('manage/admin');
		$this->load->view('manage/includes/footer');
	}
	function add()
	{
		$data = array( 'title'=>$this->lang->line('add_new')." ".'Mobile User','message'=>'', 'action'=>site_url('manage/admin/insert'), 'link_back'=>site_url('manage/admin'), 'link_add'=>site_url('manage/admin/add') );
		$data['method']=$this->router->fetch_method();
		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
		$this->load->view('manage/includes/header', $data);
		$this->load->view('manage/admin_edit');
		$this->load->view('manage/includes/footer');
	}
	function insert()
	{
		$data = array( 'title'=>$this->lang->line('add_new')." ".$this->lang->line('admin_magazine_user'), 'message'=>'', 'action'=>site_url('manage/admin/insert'), 'link_back'=>site_url('manage/admin'), 'link_add'=>site_url('manage/admin/add') );

		$this->_set_rules();
		$data['method']=$this->router->fetch_method();

		if(is_array($this->input->post()))
			extract($this->input->post());

		$csrf_check = $this->admin_model->csrfguard_validate_token($csrf_name,$csrf_token);
		$data['type'] = $tadmin;    // set Radio button value.
		if($this->form_validation->run() === TRUE && $csrf_check==true)
		{
			if($_FILES['file']['name']!='')
			{
				$config['upload_path'] = './uploads/admin/big';
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
					$this->load->view('manage/admin_edit');
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

			$this->ip_date = $this->admin_model->get_date_ip();
			$this->_salt = $this->admin_model->create_pwd_salt();
			$this->_password =  hash('sha256', $this->_salt . ( hash('sha256',$this->input->post('password')) ) );

			$value_array = array(
				'Email' => $this->input->post('email'),
				'Password' => $this->_password,
				'Salt' => $this->_salt ,
				'Image' => $file_name!=''? $file_name :'',
				'Name' => $this->input->post('name'),
				'Address' => $address,
				'MobileNo' => $mobile_no,
				'CreatedBy' => $this->session->userdata('adminid'),
				'CreatedDate'=>$this->ip_date->cur_date,
				'CreatedIp'=>'12312233',    // $this->ip_date->ip,
			);
			$this->admin_model->save('user',$value_array);
			$this->session->set_flashdata('notification','Mobile User add successfully!');
			redirect(site_url('manage/admin/index'));
			die();
		}
		else
		{
			if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');
			$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
			$this->load->view('manage/includes/header', $data);
			$this->load->view('manage/admin_edit');
			$this->load->view('manage/includes/footer');
		}
	}
	function edit($id='')
	{
		$data = array('title'=>$this->lang->line('edit')." ".$this->lang->line('admin_magazine_user'),'message'=>'', 'link_back'=>site_url('manage/admin'), 'link_add'=>site_url('manage/admin/add'));
		$data['method']="Edit";
		$data['action'] = site_url('manage/admin/update/'.$id);
		
		$result = $this->admin_model->get_all_records('user','*',array('Id'=>$id,'Status !='=>'Delete'),'',1)->row();

		if(!empty($result))
		{
			$this->form_data->id = $id;
			$this->form_data->email = $result->Email;
			$this->form_data->password = $result->Password;
			$this->form_data->name = $result->Name;
			$this->form_data->address = $result->Address;
			$this->form_data->mobile = $result->MobileNo;
			$this->form_data->image = $result->Image;

			$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
			$this->load->view('manage/includes/header', $data);
			$this->load->view('manage/admin_edit');
			$this->load->view('manage/includes/footer');
			
		}

	}
	
	function update($id)
	{
		if(!is_numeric($id)) 
			$this->add();
		$data = array('title'=>$this->lang->line('edit')." ".'Mobile user','message'=>'', 'action'=>site_url('manage/admin/update/'.$id),'link_back'=>site_url('manage/admin'), 'link_add'=>site_url('manage/admin/add'),'method'=>$this->lang->line('edit') );
		
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
				$config['upload_path'] = './uploads/admin/big';
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
					$this->load->view('manage/admin_edit');
					return;
				}
				else
				{
					$file_name = $this->upload->file_name;
					$this->logo = $file_name;
					$this->create_thumb($file_name,60,60,'thumb60');
					$this->create_thumb($file_name,120,120,'thumb120');
				}
			}
			$update_array =  array(
									'Address' => $this->input->post('address'),
									'MobileNo' => $this->input->post('mobile_no'),
									'Name' => $this->input->post('name'),
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
			if($file_name!='') {$update_array['Image'] = $file_name;}
			 $this->admin_model->update('user',$update_array,array('Id'=>$id));

			//REDIRECT
			$this->session->set_flashdata('notification','Mobile user modified successfully!');
            redirect(site_url('manage/admin'));
			die();
		}
		else
		{ 
		  	if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');
			$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
			$data['method']= $this->lang->line('edit');
			$this->load->view('manage/includes/header', $data);
			$this->load->view('manage/admin_edit');
			$this->load->view('manage/includes/footer');
		}
	 }
	
    function check_exists($email, $id='')
  	{
		$data['result'] = $this->admin_model->check_record_exist('user','Id',array('Email'=>$email,'Status !='=>'Delete'));
	  	if (!empty($data['result']))
		{
			if($id !='' && $id==$data['result']['Id'])
			{
				return TRUE;
			}
			else
			{
				$this->form_validation->set_message('check_exists',$this->lang->line('email_already_exist'));
				return FALSE;
			}
		}		
		return TRUE;
	}
	//SET FORM DATA
	
	
	// validation rules
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
		$this->form_validation->set_rules('image',$this->lang->line(''),'');
	}
	function create_thumb($file,$width=60,$height=60)
	 {
		$config['image_library'] = 'gd2';
		$config['source_image'] = './uploads/admin/big/'.$file;	
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = FALSE;
		$config['width'] = $width;
		$config['height'] = $height;
		$config['new_image'] = './uploads/admin/thumb/'.$file;
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
