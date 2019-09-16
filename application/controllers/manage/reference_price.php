<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reference_price extends CI_Controller {
	var $limit = 10;
	
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('form_validation'));
		$this->load->model('manage/admin_model');
		$this->load->helper('form');
		$this->limit = @$this->site_setting->site_admin_rowperpage;
		$this->admin_model->check_seesion(current_url(),array(ROLE_SUPPER_ADMIN));		
	}
	function index($msg='',$offset = 0)
	{
		$data = array('title'=>'Reference Prices','message'=>'','edit_link'=>site_url('manage/reference_price/edit'),'link_add'=>site_url('manage/reference_price/add'), 'tbl'=>'reference_price' ,'module'=>'Reference Price','link_info'=>site_url('manage/reference_price/index'));

		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);

		$this->form_validation->set_rules('search','Search','trim|xss_clean');
		$search =urldecode($this->input->get('search'));
		$client_filter =urldecode($this->input->get('id'));


		$data['list_records']=array();
		$val = 'reference_price.*, product.Price as CurrentPrice , product.Image as ProImage, product.ProductName as ProName, user.Name as UserName, user.Email as UserEmail, category.CategoryName as CatName';
		$join = array(
			array(
				'table'=>'fhs_category',
				'condition'=>'fhs_category.Id = fhs_reference_price.CatId',
				'jointype'=> 'left'
			),
			array(
				'table'=>'fhs_product',
				'condition'=>'fhs_product.Id = fhs_reference_price.ProductId',
				'jointype'=> 'left'
			),
			array(
				'table'=>'fhs_user',
				'condition'=>'fhs_user.Id = fhs_reference_price.UserId' ,
				'jointype'=> 'left'
			),
		);

		if($search=='')
		{
			$this->total = $this->admin_model->count_all($data['tbl'],array('Status !='=>'Delete'));
			if($this->total!=0)
			{
				$data['list_records'] = $this->common_model->get_joins('reference_price',$val,$join,array('reference_price.Status !='=>'Delete'),'reference_price.Id','DESC',$this->limit, $offset,'','','')->result();
				// echo '<pre>'; print_r($data['list_records']); exit;
			}
			else
			{
				$data['no_data']=1;
			}
		}
		else
		{
			$search_filter = $this->db->escape_like_str($search);
			$res = $this->admin_model->get_joinlist_like('reference_price',$val,$join,array('reference_price.Status !='=>'Delete'),'reference_price.Id','DESC',$this->limit, $offset,"(fhs_category.CategoryName Like '%".$search_filter."%' OR fhs_product.ProductName Like '%".$search_filter."%' OR fhs_adminmaster.Email Like '%".$search_filter."%' OR fhs_adminmaster.Name Like '%".$search_filter."%' OR fhs_adminmaster.Name Like '%".$search_filter."%')");
			$this->total = $res['total_records'];
			if($this->total!=0)
			{
				$data['list_records'] = $res['results'];
			}
			else
			{
				$data['search_data']='No';
			}
			$config['suffix'] = '?search='.$this->input->get('search');
			$data['search'] = $search;
		}

		if($client_filter != '')
		{
			$this->total = $this->admin_model->count_all($data['tbl'],array('Status !='=>'Delete'));
			if($this->total!=0)
			{
				$data['list_records'] = $this->common_model->get_joins('reference_price',$val,$join,array('reference_price.Status !='=>'Delete','UserId'=>$client_filter),'reference_price.Id','DESC',$this->limit, $offset,'','','')->result();
			}
			else
			{
				$data['no_data']=1;
			}
			$config['suffix'] = '?id='.$this->input->get('id');
		}
		$data['client_list'] = $this->admin_model->get_all_records('user','*',array('Status'=>'Enable'))->result();


		// generate pagination
		$this->load->library('pagination');
		$offset = $this->uri->segment($uri_segment);
		$config['base_url'] = site_url('manage/reference_price/index/');
		$config['total_rows'] = $this->total;
		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['j'] = 0 + $offset;
		$data['total_rows']= $this->total;
		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
		// load view
		$this->load->view('manage/includes/header', $data);
		$this->load->view('manage/ref_price');
		$this->load->view('manage/includes/footer');
	}

	function add()
	{
		// SET COMMON PROPERTIES
		$data = array( 'title'=>'Add new Reference Price','message'=>'', 'action'=>site_url('manage/reference_price/insert'), 'link_back'=>site_url('manage/reference_price'),'link_add'=>site_url('manage/reference_price/add'),'tbl'=>'reference_price' ,'add'=>'Yes');
		// load view
		$data['category'] = $this->admin_model->get_all_records('category','Id,CategoryName',array('Status'=>'Enable'),'Id Desc')->result();
		$data['product'] = $this->admin_model->get_all_records('product','Id,ProductName',array('Status'=>'Enable'),'Id Desc')->result();
		$data['client'] = $this->admin_model->get_all_records('user','Id,Name',array('Status'=>'Enable'),'Id Desc')->result();

		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
		$this->load->view('manage/includes/header', $data);
		$this->load->view('manage/ref_price_edit');
		$this->load->view('manage/includes/footer');

	}
	function insert()
	{
		$data = array( 'title'=>'Add New Reference Price','message'=>'', 'action'=>site_url('manage/reference_price/insert'), 'link_back'=>site_url('manage/reference_price'), 'link_add'=>site_url('manage/reference_price/add'),'tbl'=>'reference_price' );

		// set validation properties
		$data['category'] = $this->admin_model->get_all_records('category','Id,CategoryName',array('Status'=>'Enable'),'Id Desc')->result();
		$data['product'] = $this->admin_model->get_all_records('product','Id,ProductName',array('Status'=>'Enable'),'Id Desc')->result();
		$data['client'] = $this->admin_model->get_all_records('adminmaster','Id,Name',array('Status'=>'Enable','RoleId' => ROLE_CLIENT_ADMIN),'Id Desc')->result();


		$this->_set_rules();
		if(is_array($this->input->post()))
			extract($this->input->post());

		$csrf_check = $this->admin_model->csrfguard_validate_token($csrf_name,$csrf_token);
		if($this->form_validation->run() === TRUE && $csrf_check==true)
		{
			$this->ip_date = $this->admin_model->get_date_ip();
			// save data
			$value_array = array(
				'CatId' => $this->input->post('category'),
				'ProductId' => $this->input->post('product') ,
				'UserId' =>$this->input->post('client'),
				'Price' =>$this->input->post('price'),
				'status'=>'Enable',
				'CreatedDate'=> $this->ip_date->cur_date,
				'CreatedIp'=> $this->ip_date->ip,
			);
			$id = $this->admin_model->save($data['tbl'],$value_array);
			if(!empty($error))
			{
				$this->session->set_flashdata('error',$this->lang->line('store_pro_image'));
				redirect(site_url('manage/reference_price/edit/'.$id));
			}
			else
			{
				$this->session->set_flashdata('notification','Reference Price added successfully ');
				redirect(site_url('manage/reference_price'));
				die();
			}
		}
		else
		{
			if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');
			$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
			$this->load->view('manage/includes/header', $data);
			$this->load->view('manage/ref_price');
			$this->load->view('manage/includes/footer');
		}
	}


	function edit($id='')
	{
		if(!is_numeric($id))
			site_url('manage/reference_price/index/');
		
		$data = array('title'=>'Edit Reference Price','message'=>'', 'message'=>'', 'link_back'=>$_SERVER['HTTP_REFERER'],'tbl'=>'reference_price');
		$data['action'] = site_url('manage/reference_price/update/'.$id);

		$result = $this->admin_model->get_all_records($data['tbl'],'*',array('Id'=>$id),'',1)->row();

		$data['category'] = $this->admin_model->get_all_records('category','Id,CategoryName',array('Status'=>'Enable'),'Id Desc')->result();
		$data['product'] = $this->admin_model->get_all_records('product','Id,ProductName',array('Status'=>'Enable','CatId' => $result->CatId),'Id Desc')->result();
		$data['client'] = $this->admin_model->get_all_records('user','Id,Name',array('Status'=>'Enable'),'Id Desc')->result();

		$this->form_data->cat_id = $result->CatId;
		$this->form_data->pro_id = $result->ProductId;
		$this->form_data->client_id = $result->UserId;
		$this->form_data->price = $result->Price;

		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);	
		// load view
		$this->load->view('manage/includes/header', $data);
		$this->load->view('manage/ref_price_edit');
		$this->load->view('manage/includes/footer');


	}
	function update($id)
	{
		$data = array('title'=>'Edit Reference Price','message'=>'', 'message'=>'', 'link_back'=>site_url('manage/reference_price'));
		$data['action'] = site_url('manage/reference_price/update/'.$id);
		
		$this->_set_rules();
		if(is_array($this->input->post()))
		extract($this->input->post());
		$csrf_check = $this->admin_model->csrfguard_validate_token($csrf_name,$csrf_token);
		if($this->form_validation->run() === TRUE && $csrf_check==true)
		{
			// save data
			$value_array = array(
							'CatId' => $this->input->post('category'),
							'ProductId' => $this->input->post('product') ,
							'UserId' =>$this->input->post('client'),
							'Price' =>$this->input->post('price'),
							'CreatedIp'=> $this->ip_date->ip,
							);
			$id = $this->admin_model->update('reference_price',$value_array,array('Id'=>$id));
		    $this->session->set_flashdata('notification', 'Reference Price updated successfully.');
            redirect(site_url('manage/reference_price/index'));
			die();
		}
		else
		{
			if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');
			$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);	
			$data['message'] = 'Enter All Fields';
			$this->load->view('manage/includes/header', $data);
			$this->load->view('manage/ref_price_edit');
			$this->load->view('manage/includes/footer');

		}
	 }

	// validation rules
	function _set_rules()
	{
		$this->form_validation->set_rules('category', 'Category', 'trim|required');
		$this->form_validation->set_rules('product', 'Product', 'trim|required');
		$this->form_validation->set_rules('client', 'Client', 'trim|required');
		$this->form_validation->set_rules('price', 'Price', 'trim|required|Number');
    }
}
