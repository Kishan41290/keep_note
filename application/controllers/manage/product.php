<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Product extends CI_Controller {
	var $limit = 10;
	
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('form_validation'));
		$this->load->model('manage/admin_model');
		$this->load->helper('form');
	//	$this->limit = @$this->site_setting->site_admin_rowperpage;
		$this->admin_model->check_seesion(current_url(),array(ROLE_SUPPER_ADMIN,ROLE_CLIENT_ADMIN));
	}
	function index($msg='',$offset = 0)
	{
		$data = array('title'=>'Product','message'=>'','edit_link'=>site_url('manage/product/edit'),'link_add'=>site_url('manage/product/add'), 'tbl'=>'product' ,'module'=>'Product','link_info'=>site_url('manage/product/index'));
		
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);

		$user_id = $this->session->userdata('adminid');

		$page_type=$_GET['section']==''?"General":$_GET['section'];
		$val = " product.*,category.CategoryName as CategoryName,reference_price.Price as RefPrice";
		$join = array(
			array(
				'table'=>'category',
				'condition'=>'category.Id = product.CatId',
				'jointype'=> 'left'
			),
			array(
				'table'=>'fhs_reference_price',
				'condition'=>'fhs_reference_price.ProductId = fhs_product.Id AND fhs_reference_price.UserId = '.$user_id,
				'jointype'=> 'left'
			),

		);
		$array = array('product.Status !=' => 'Delete','category.Status'=>'Enable');

		$data['list_records'] = $this->admin_model->get_joins($data['tbl'], $val, $join, $array,'product.Id','DESC', $this->limit, $offset)->result();
		// load view
		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
		$this->load->view('manage/includes/header', $data);
		$this->load->view('manage/product');
		$this->load->view('manage/includes/footer');
	}
	function edit($id='')
	{
		if(!is_numeric($id))
			site_url('manage/product/index/?section='.$this->input->get('section'));
		
		$data = array('title'=>'Edit Product','message'=>'', 'link_back'=>$_SERVER['HTTP_REFERER'],'tbl'=>'product','method'=>'Edit');
		$data['category'] = $this->admin_model->get_all_records('category','*',array('Status'=>'Enable'))->result();

		$data['action'] = site_url('manage/product/update/'.$id);
		$result = $this->admin_model->get_all_records($data['tbl'],'*',array('Id'=>$id),'',1)->row();
		$this->form_data->cat_id = $result->CatId;
		$this->form_data->name = $result->ProductName;
		$this->form_data->price = $result->Price;
		$this->form_data->image = $result->Image;
		$data['field'] = $result;
		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);	
		// load view
		$this->load->view('manage/includes/header', $data);
		$this->load->view('manage/product_edit');
		$this->load->view('manage/includes/footer');


	}
	function update($id)
	{
		$data = array('title'=>'Edit Product','message'=>'', 'link_back'=>site_url('manage/product'));
		$data['action'] = site_url('manage/product/update/'.$id);
		
		$this->_set_rules();
		if(is_array($this->input->post()))
		extract($this->input->post());
		$csrf_check = $this->admin_model->csrfguard_validate_token($csrf_name,$csrf_token);
		if($this->form_validation->run() === TRUE && $csrf_check==true)
		{
			// save data
			if($_FILES['file']['name']!='')
			{
				$config['upload_path'] = './uploads/product/big';
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
					$this->load->view('manage/product_edit');
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

			$value_array = array(
							'CatId' => $this->input->post('category'),
							'ProductName' => $this->input->post('name'),
			            	'Price' => $this->input->post('price')
							);
			if($file_name!='')
			{
				$value_array['Image'] = $file_name;
			}
			$id = $this->admin_model->update('product',$value_array,array('Id'=>$id));
		    $this->session->set_flashdata('notification','product update successfully');
            redirect(site_url('manage/product/index'));
			die();
		}
		else
		{
			if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');
			$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);	
			$data['message'] = 'Enter All Fields';
			$this->load->view('manage/includes/header', $data);
			$this->load->view('manage/product_edit');
			$this->load->view('manage/includes/footer');

		}
	 }
	 function add()
	 {
		 // SET COMMON PROPERTIES
		$data = array( 'title'=>'Add new Product','message'=>'', 'action'=>site_url('manage/product/insert'), 'link_back'=>site_url('manage/product'),'link_add'=>site_url('manage/product/add'),'tbl'=>'product' ,'add'=>'Yes');
		 
		$data['category'] = $this->admin_model->get_all_records('category','*',array('Status'=>'Enable'))->result();

		 // load view
		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);	
		$this->load->view('manage/includes/header', $data);
		$this->load->view('manage/product_edit');
		$this->load->view('manage/includes/footer');

	 }
	function insert()
	{
		$data = array( 'title'=>'Add New Product','message'=>'', 'action'=>site_url('manage/product/insert'), 'link_back'=>site_url('manage/product'), 'link_add'=>site_url('manage/product/add'),'tbl'=>'product' );
		
		// set validation properties
		$this->_set_rules();
		if(is_array($this->input->post()))
			extract($this->input->post());
		$csrf_check = $this->admin_model->csrfguard_validate_token($csrf_name,$csrf_token);
		if($this->form_validation->run() === TRUE && $csrf_check==true)
		{
			$this->ip_date = $this->admin_model->get_date_ip();
			// save data

			if($_FILES['file']['name']!='')
			{
				$config['upload_path'] = './uploads/product/big';
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
					$this->load->view('manage/product_edit');
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

			$value_array = array(
								'CatId' => $this->input->post('category'),
								'ProductName' => $this->input->post('name') ,
								'Price' =>$this->input->post('price'),
								'status'=>'Enable',
								'CreatedDate'=> $this->ip_date->cur_date,
								'CreatedIp'=> $this->ip_date->ip,
							);
			if($file_name!='')
			{
				$value_array['Image'] = $file_name;
			}
			$id = $this->admin_model->save($data['tbl'],$value_array);				
			if(!empty($error))
			{
				$this->session->set_flashdata('error',$this->lang->line('store_pro_image'));
				redirect(site_url('manage/product/edit/'.$id));
			}
			else
			{
				$this->session->set_flashdata('notification',$this->lang->line('setting_succ_added'));
				redirect(site_url('manage/product'));
				die();
			}
		}
		else
		{	
			if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');
			$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
			$this->load->view('manage/includes/header', $data);
			$this->load->view('manage/product_edit');
			$this->load->view('manage/includes/footer');
		}
	}

	// validation rules
	function _set_rules()
	{
		$this->form_validation->set_rules('category', $this->lang->line('category'), 'trim|required|max_length[200]');
		$this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|max_length[200]');
		$this->form_validation->set_rules('price',$this->lang->line('price'), 'trim|required');
	}
	function create_thumb($file,$width=60,$height=60)
	{
		$config['image_library'] = 'gd2';
		$config['source_image'] = './uploads/product/big/'.$file;
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = FALSE;
		$config['width'] = $width;
		$config['height'] = $height;
		$config['new_image'] = './uploads/product/thumb/'.$file;
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
