<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Order extends CI_Controller {
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
		$data = array('title'=>'Orders','message'=>'','edit_link'=>site_url('manage/order/edit'),'link_add'=>site_url('manage/order/add'), 'tbl'=>'order' ,'module'=>'Order','link_info'=>site_url('manage/order/index'));
		$userid = $this->session->userdata('adminid');

		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment)!=''?$this->uri->segment($uri_segment):'';

		$this->form_validation->set_rules('search','Search','trim|xss_clean');
		$search =urldecode($this->input->get('search'));
		$filter =urldecode($this->input->get('filter'));
		$client_filter =urldecode($this->input->get('id'));


		$data['list_records']=array();
		$val = "product.ProductName, product.Image as ProImage, category.CategoryName, CONCAT(users.FirstName, users.LastName) as AdminName, users.Email as AdminEmail, order.* ";
		$join = array(
			array(
				'table'=>'product',
				'condition'=>'product.Id = order.ProductId',
				'jointype'=> 'left'
			),
			array(
				'table'=>'category',
				'condition'=>'category.Id = product.CatId',
				'jointype'=> 'left'
			),
			array(
				'table'=>'users',
				'condition'=>'users.Id = order.UserId',
				'jointype'=> 'left'
			),
		);


		if($filter!=''){
				$array = array('order.Status !=' => 'Delete', 'OrderStatus' => $filter);
				$config['suffix'] = '?filter='.$this->input->get('filter');
		}else{
				$array = array('order.Status !=' => 'Delete');
		}


		if($client_filter!=''){
			if($this->session->userdata('admin_role')==ROLE_SUPPER_ADMIN)
			{
				$array = array('order.Status !=' => 'Delete', 'UserId' => $client_filter);
			}
			$config['suffix'] = '?id='.$this->input->get('id');
		}

		if($search=='')
		{
			$this->total = $this->admin_model->count_all($data['tbl'],$array);
			if($this->total!=0)
			{
				$data['list_records'] = $this->common_model->get_joins($data['tbl'], $val, $join, $array, 'order.OrderStatus','ASC', $this->limit, $offset, '', '', '')->result();
			}
			else
			{
				$data['no_data']=1;
			}
		}
		else
		{
			$search_filter = $this->db->escape_like_str($search);
			$res = $this->admin_model->get_joinlist_like('order', $val, $join, $array, 'order.Id','DESC',$this->limit, $offset, "(fhs_category.CategoryName Like '%".$search_filter."%' OR fhs_product.ProductName Like '%".$search_filter."%' OR fhs_users.Email Like '%".$search_filter."%' OR fhs_users.FirstName Like '%".$search_filter."%' OR fhs_users.LastName Like '%".$search_filter."%' OR fhs_order.Price Like '%".$search_filter."%')");
			$this->total = $res['total_records'];
			if($this->total!=0){
				$data['list_records'] = $res['results'];
			}
			else{
				$data['search_data']='No';
			}
			$config['suffix'] = '?search='.$this->input->get('search');
			$data['search'] = $search;
		}

		$data['client_list'] = $this->admin_model->get_all_records('users','*',array('Status'=>'Enable'))->result();

		// generate pagination
		$this->load->library('pagination');
		$offset = $this->uri->segment($uri_segment);
		$config['base_url'] = site_url('manage/order/index/');
		$config['total_rows'] = $this->total;
		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['j'] = 0 + $offset;
		$data['total_rows']= $this->total;

				// load view
		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
		$this->load->view('manage/includes/header', $data);
		$this->load->view('manage/order');
		$this->load->view('manage/includes/footer');
	}

	public function information($id){
		$data = array('title'=>'Order Information', 'tbl'=>'order', 'link_back'=>site_url('manage/order') );

		$val = " product.ProductName, product.Image as ProImage, category.CategoryName, users.MobileNo as AdminMobile, users.Address as AdminAddress, CONCAT(users.FirstName, users.LastName) as AdminName, users.Email as AdminEmail, order.* ";
		$join = array(
			array(
				'table'=>'product',
				'condition'=>'product.Id = order.ProductId',
				'jointype'=> 'left'
			),
			array(
				'table'=>'category',
				'condition'=>'category.Id = product.CatId',
				'jointype'=> 'left'
			),
			array(
				'table'=>'users',
				'condition'=>'users.Id = order.UserId',
				'jointype'=> 'left'
			),
		);

		$data['result'] = $this->admin_model->get_joins($data['tbl'], $val, $join, array('order.Status !=' => 'Delete', 'order.Id' => $id),'order.Id','DESC')->row();

		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
		$this->load->view('manage/includes/header', $data);
		$this->load->view('manage/order_info');
		$this->load->view('manage/includes/footer');
	}

   

	function placeOrder()
	{
		// set validation properties
		$this->_set_rules();
		if(is_array($this->input->post()))
			extract($this->input->post());
		$csrf_check = $this->admin_model->csrfguard_validate_token($this->input->post('csrf_name'),$this->input->post('csrf_token'));
		if($this->form_validation->run() === TRUE && $csrf_check==true)
		{
			$this->ip_date = $this->admin_model->get_date_ip();
			// save data
			if($this->input->post('modal_qty') != '')
			{
				$value_array = array(
					'ProductId' => $this->input->post('modal_product_id'),
					'UserId' => $this->session->userdata('adminid'),
					'Qty' =>$this->input->post('modal_qty'),
					'Price' =>$this->input->post('modal_price'),
					'Total' =>$this->input->post('modal_total'),
					'OrderStatus' =>'Pending',
					'status'=>'Enable',
					'CreatedDate'=> $this->ip_date->cur_date,
					'CreatedIp'=> $this->ip_date->ip,
				);
				$this->admin_model->save('order',$value_array);
				$unique_form_name = "CSRFGuard_".mt_rand(0,mt_getrandmax());
				$token = $this->admin_model->csrfguard_generate_token($unique_form_name);
				$message=array('code'=>1,'csrf_name'=>$unique_form_name,'csrf_token'=>$token, 'msg'=>'Your Order placed successfully');
			}
			else
			{
				$unique_form_name = "CSRFGuard_".mt_rand(0,mt_getrandmax());
				$token = $this->admin_model->csrfguard_generate_token($unique_form_name);
				$message=array('code'=>0,'csrf_name'=>$unique_form_name,'csrf_token'=>$token,'msg'=>'Please enter Quantity.');
			}
		}
		else
		{
			$unique_form_name = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$token = $this->admin_model->csrfguard_generate_token($unique_form_name);
			$message=array('code'=>0,'csrf_name'=>$unique_form_name,'csrf_token'=>$token);
		}
		header('Content-Type: application/json');
		echo json_encode($message);
		die();

	}

	function editorder()
	{
		if($this->input->post('modal_qty') != "" && $this->input->post('order_id') != "")
		{
			$url = $this->input->post('order_url');
			$update_array =  array(
				'Qty' => $this->input->post('modal_qty'),
				'Total' => $this->input->post('modal_total'),
			);
			$this->admin_model->update('order',$update_array,array('Id'=>$this->input->post('order_id')));
			$this->session->set_flashdata('notification','Your Order Update successfully ');
			redirect($url);
		}
	}

	function cancelOrder()
	{
		// set validation properties
		$this->_set_rules();
		if(is_array($this->input->post()))
			extract($this->input->post());

			$userid = $this->session->userdata('adminid');

			$this->ip_date = $this->admin_model->get_date_ip();
			// save data
			if($this->input->post('id') != '')
			{

				$update_array =  array(
					'OrderStatus' => 'Cancel',
					'CancelBy' => $userid,
				);
				$this->admin_model->update('order',$update_array,array('Id'=>$this->input->post('id')));

				$unique_form_name = "CSRFGuard_".mt_rand(0,mt_getrandmax());
				//$token = $this->admin_model->csrfguard_generate_token($unique_form_name);
				$message=array('code'=>1, 'msg'=>'Order canceled successfully');
			}
			else
			{
				$message=array('code'=>0,'msg'=>'Please select order.');
			}

		header('Content-Type: application/json');
		echo json_encode($message);
		die();

	}

	function approveOrder()
	{
		// set validation properties
		$this->_set_rules();
		if(is_array($this->input->post()))
			extract($this->input->post());
		
		$this->ip_date = $this->admin_model->get_date_ip();
		// save data
		if($this->input->post('id') != '')
		{

			$update_array =  array(
				'OrderStatus' => 'Approved'
			);
			$this->admin_model->update('order',$update_array,array('Id'=>$this->input->post('id')));

			$message=array('code'=>1, 'msg'=>'Order Approved successfully');
		}
		else
		{
			$message=array('code'=>0,'msg'=>'Please select order.');
		}

		header('Content-Type: application/json');
		echo json_encode($message);
		die();

	}


	function _set_rules()
	{
		$this->form_validation->set_rules('modal_qty',$this->lang->line('modal_qty'), 'trim|required');
		$this->form_validation->set_rules('modal_price',$this->lang->line('modal_price'), 'trim|required');
		$this->form_validation->set_rules('modal_total',$this->lang->line('modal_total'), 'trim|required');
		$this->form_validation->set_rules('modal_product_id',$this->lang->line('modal_product_id'), 'trim|required');
	}

}


