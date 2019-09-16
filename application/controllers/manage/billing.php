<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Billing extends CI_Controller {
	var $limit = 10;
	
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('form_validation'));
		$this->load->model('manage/admin_model');
		$this->load->helper('form');
		$this->limit = @$this->site_setting->site_admin_rowperpage;
		$this->admin_model->check_seesion(current_url(),array(ROLE_SUPPER_ADMIN,ROLE_CLIENT_ADMIN));
	}
	function index($msg='',$offset = 0)
	{
		$data = array('title'=>'Billing','message'=>'', 'tbl'=>'order' ,'module'=>'Billing','link_info'=>site_url('manage/billing/index'));

		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment)!=''?$this->uri->segment($uri_segment):'';

		$this->form_validation->set_rules('search','Search','trim|xss_clean');
		$search =urldecode($this->input->get('search'));
		$client_filter =urldecode($this->input->get('id'));


		$data['list_records']=array();
		$val = " product.ProductName, product.Image as ProImage, category.CategoryName, user.Name as AdminName, user.Email as AdminEmail, order.* ";
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
				'table'=>'user',
				'condition'=>'user.Id = order.UserId',
				'jointype'=> 'left'
			),
		);

		$array = array('order.Status !=' => 'Delete', 'OrderStatus' => 'Approved');

		if($client_filter!=''){

			$array = array('order.Status !=' => 'Delete','OrderStatus' => 'Approved', 'UserId' => $client_filter);
			$config['suffix'] = '?id='.$this->input->get('id');
		}

		if($search=='')
		{
			$this->total = $this->admin_model->count_all($data['tbl'],$array);
			if($this->total!=0)
			{
				$data['list_records'] = $this->common_model->get_joins($data['tbl'], $val, $join, $array, 'order.Id','DESC', $this->limit, $offset, '', '', '')->result();
			}
			else
			{
				$data['no_data']=1;
			}
		}
		else
		{
			$search_filter = $this->db->escape_like_str($search);
			$res = $this->admin_model->get_joinlist_like('order', $val, $join, $array, 'order.Id','DESC',$this->limit, $offset, "(fhs_category.CategoryName Like '%".$search_filter."%' OR fhs_product.ProductName Like '%".$search_filter."%' OR fhs_user.Email Like '%".$search_filter."%' OR fhs_user.Name Like '%".$search_filter."%' OR fhs_order.Price Like '%".$search_filter."%')");
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

		$data['client_list'] = $this->admin_model->get_all_records('user','*',array('Status'=>'Enable'))->result();

		// generate pagination
		$this->load->library('pagination');
		$offset = $this->uri->segment($uri_segment);
		$config['base_url'] = site_url('manage/billing/index/');
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
		$this->load->view('manage/billing');
		$this->load->view('manage/includes/footer');
	}

	function generate_bill($msg='',$offset = 0)
	{

		$data = array('title'=>'Billing','message'=>'', 'tbl'=>'order' ,'module'=>'Billing','link_info'=>site_url('manage/billing/index'));
		
		$csrf_check = $this->admin_model->csrfguard_validate_token($this->input->post('csrf_name'),$this->input->post('csrf_token'));

		if($csrf_check==true && $this->input->post('user_id'))
		{
			$order_id= "'".implode("','",$this->input->post('bill_order_id'))."'";
			$user_id = $this->input->post('user_id');

			$data = array('title'=>'Billing','message'=>'', 'tbl'=>'order' ,'module'=>'Billing','link_info'=>site_url('manage/billing/index'));

			$uri_segment = 4;
			$offset = $this->uri->segment($uri_segment)!=''?$this->uri->segment($uri_segment):'';

            $sql = "select o.*,p.ProductName, c.CategoryName FROM fhs_order AS o 
                        LEFT JOIN fhs_product AS p ON o.ProductId = p.Id
                        LEFT JOIN fhs_category AS c ON p.CatId = c.Id
                        WHERE o.Status = 'Enable' AND o.OrderStatus = 'Approved'
                        AND o.UserId = $user_id 
                        AND o.Id IN($order_id) ORDER BY o.Id DESC";


			$data['order_data'] = $this->common_model->custom_query($sql)->result();
			
			$data['post_user_id']= $user_id;
			$data['post_order_id']= $order_id;

			$data['client_data'] = $this->admin_model->get_all_records('user','*',array('Status'=>'Enable','Id'=>$user_id))->row();
			$data['admin_data'] = $this->admin_model->get_all_records('adminmaster','*',array('Status'=>'Enable'))->row();

		}
		else
		{
			redirect(site_url('manage/billing'));
			exit;
		}
		// load view
		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
		$this->load->view('manage/includes/header',$data );
		$this->load->view('manage/generate_bill');
		$this->load->view('manage/includes/footer');
	}
	
	function makeBill()
	{
		if($this->input->post('post_user_id') !='' && $this->input->post('post_order_id') != '')
		{
			$order_id= $this->input->post('post_order_id');
			$user_id = $this->input->post('post_user_id');

			$data = array('title'=>'Billing','message'=>'', 'tbl'=>'order' ,'module'=>'Billing','link_info'=>site_url('manage/billing/index'));

			$sql = "select o.*,p.ProductName, c.CategoryName FROM fhs_order AS o 
                        LEFT JOIN fhs_product AS p ON o.ProductId = p.Id
                        LEFT JOIN fhs_category AS c ON p.CatId = c.Id
                        WHERE o.Status = 'Enable' AND o.OrderStatus = 'Approved'
                        AND o.UserId = $user_id 
                        AND o.Id IN($order_id) ORDER BY o.Id DESC";

			$data['order_data'] = $this->common_model->custom_query($sql)->result();

			$data['client_data'] = $this->admin_model->get_all_records('user','*',array('Status'=>'Enable','Id'=>$user_id))->row();
			$data['admin_data'] = $this->admin_model->get_all_records('adminmaster','*',array('Status'=>'Enable'))->row();
			
			$html=$this->load->view('manage/bill_pdf', $data, true);
			$pdfFilePath = "Fraazo_bill_".date('d_m_Y').'.pdf';

			$this->load->library('m_pdf');
			$this->m_pdf->pdf->WriteHTML($html);
			$this->m_pdf->pdf->Output($pdfFilePath, "D");
			exit;

		}
	}

}


