<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('form_validation'));
		$this->load->model('manage/admin_model', 'am');
		$this->am->check_seesion(current_url(),array(ROLE_SUPPER_ADMIN)); 
		// @PARAM: target url , who can access
	}
	
	public function index()
	{	
		$data['title'] = $this->lang->line('dashboard');

		// $data['cat_counter'] = $this->am->count_all('category', array('Status !='=>'Delete'));
		// $data['pro_counter'] = $this->am->count_all('product', array('Status !='=>'Delete'));
		// $data['order_counter'] = $this->am->count_all('order', array('Status !='=>'Delete', 'OrderStatus' => 'Pending'));
		// $data['order_complete_counter'] = $this->am->count_all('order', array('Status !='=>'Delete', 'OrderStatus' => 'Approved'));
		// $data['order_cancel_counter'] = $this->am->count_all('order', array('Status !='=>'Delete', 'OrderStatus' => 'Cancel'));
		// $data['user_counter'] = $this->am->count_all('users', array('Status !='=>'Delete'));


		// $data['cat_list'] = $this->am->get_all_records('category','Id,CategoryName', array('Status '=>'Enable') ,'Id DESC')->result();

		// $filter =urldecode($this->input->get('filterby'));
		// if($filter=='seven' || $filter==''){
		// 	$from_dt = date('Y-m-d',strtotime('-7 days'));
		// 	$to_dt = date('Y-m-d');
		// 	$data['days'] = '7';
		// 	$data['stamp'] = 518400;
		// }
		// elseif($filter=='thirty'){
		// 	$from_dt = date('Y-m-d',strtotime('-30 days'));
		// 	$to_dt = date('Y-m-d');
		// 	$data['days'] = '30';
		// 	$data['stamp'] = 2492000;
		// }
		// elseif($filter=='sixty'){
		// 	$from_dt = date('Y-m-d',strtotime('-60 days'));
		// 	$to_dt = date('Y-m-d');
		// 	$data['days'] = '60';
		// 	$data['stamp'] = 5084000;
		// }
		// elseif($filter=='ninty'){
		// 	$from_dt = date('Y-m-d',strtotime('-90 days'));
		// 	$to_dt = date('Y-m-d');
		// 	$data['days'] = '90';
		// 	$data['stamp'] = 7576000;
		// }else{
		// 	$from_dt = date('Y-m-d',strtotime('-180 days'));
		// 	$to_dt = date('Y-m-d');
		// 	$data['days'] = '180';
		// 	$data['stamp'] = 15152000;
		// }

		// $result_line = $this->am->custom_query("SELECT o.Id, o.Price, o.CreatedDate FROM fl_order AS o WHERE o.Status!='Delete' AND CreatedDate >= '".$from_dt." 00:00:00' AND CreatedDate <= '".$to_dt." 23:59:59' ORDER BY o.CreatedDate DESC ")->result();
		// foreach($result_line as $k=>$v){
		// 	$line_res[substr($v->CreatedDate,0,10)]['ORDERS']++;
		// 	$line_res[substr($v->CreatedDate,0,10)]['PRICE'][substr($v->Price,0,10)]++;
		// }
		// $data['line_order'] = $line_res;

		// $result_pro = $this->am->custom_query("SELECT p.Id, p.Price, p.CreatedDate FROM fl_product AS p WHERE p.Status!='Delete' AND CreatedDate >= '".$from_dt." 00:00:00' AND CreatedDate <= '".$to_dt." 23:59:59' ORDER BY p.CreatedDate DESC ")->result();
		// foreach($result_pro as $k=>$v){
		// 	$line_pro[substr($v->CreatedDate,0,10)]['PRODUCT']++;
		// 	$line_pro[substr($v->CreatedDate,0,10)]['PRICE'][substr($v->Price,0,10)]++;
		// }
		// $data['line_product'] = $line_pro;

		// $cid =urldecode($this->input->get('cid'));
		// $pid =urldecode($this->input->get('pid'));

		// if($cid!='' && $pid!='') {
		// 	$arr_list = "AND c.Id = ".$cid." AND o.ProductId = ".$pid." ";
		// 	$data['pro_list'] = $this->am->get_all_records('product','Id,ProductName', array('Status '=>'Enable', 'CatId' => $cid) ,'Id DESC')->result();
		// }

		// 	$result_order = $this->am->custom_query("SELECT o.ProductId, c.Id, o.Id, o.Price, o.CreatedDate,CategoryName FROM fl_order AS o
 	// 						LEFT JOIN fl_product as p ON p.Id = o.ProductId
 	// 						LEFT JOIN fl_category as c ON c.Id = p.CatId
		// 					WHERE o.Status!='Delete' ".$arr_list." AND o.CreatedDate >= '".$from_dt." 00:00:00' AND o.CreatedDate <= '".$to_dt." 23:59:59'
		// 					ORDER BY o.CreatedDate DESC ")->result();

		// 	$line_p = array();
		// 	foreach($result_order as $k=>$v){
		// 		$line_p[substr($v->CreatedDate,0,10)]['ORDERS']++;
		// 		$line_p[substr($v->CreatedDate,0,10)]['PRICE'][] = $v->Price;
		// 	}
		// 	$data['list_order'] = $line_p;


		// // SHOW LETEST FIVE PRODUCT.
		// $val_arr = "category.Id as catid, category.CategoryName, product.ProductName, product.Id, product.Image, product.Price";
		// 	$joins =array(
		// 		array(
		// 			'table' => 'category',
		// 			'condition' => 'category.Id = product.CatId',
		// 			'join_type' => 'LEFT'
		// 		),
		// );
		// $data['latest_product_list'] = $this->common_model->get_joins('product',$val_arr,$joins,array('product.Status !='=>'Delete'),'product.Id','DESC',5)->result();



		// $order_arr = "category.CategoryName, product.ProductName, product.Image, order.*";
		// $orderjoins =array(
		// 	array(
		// 		'table' => 'product',
		// 		'condition' => 'product.Id = order.ProductId',
		// 		'join_type' => 'LEFT'
		// 	),
		// 	array(
		// 		'table' => 'category',
		// 		'condition' => 'category.Id = product.CatId',
		// 		'join_type' => 'LEFT'
		// 	),
		// 	array(
		// 		'table' => 'users',
		// 		'condition' => 'users.Id = order.UserId',
		// 		'join_type' => 'LEFT'
		// 	),
		// );
		// $data['latest_order_list'] = $this->common_model->get_joins('order',$order_arr,$orderjoins,array('order.Status !='=>'Delete'),'order.Id','DESC',5)->result();

		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->am->csrfguard_generate_token($data['unique_form_name']);
		$this->load->view('manage/includes/header', $data);
		$this->load->view('manage/home');
		$this->load->view('manage/includes/footer');
	}
	public function language()
	{
		$id=$this->input->post('lang');
		define('LANG',$id);
		$this->input->set_cookie('lang',$id,31536000);
		redirect('manage/home');
	}
				
		
}

/* End of file home.php */
/* Location: ./application/controllers/manage/home.php */