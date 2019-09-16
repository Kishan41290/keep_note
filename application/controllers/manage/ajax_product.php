<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_product extends CI_Controller {
	
	public function __construct()
	 {
		parent::__construct();
		$this->load->model('manage/admin_model');
		$this->admin_model->check_seesion(current_url(), array(ROLE_SUPPER_ADMIN,ROLE_CLIENT_ADMIN));
	 }
	
	public function index()
	{
		$this->load->library(array('form_validation'));
		$this->form_validation->set_rules('id', 'ID','required');

		$csrf_check = $this->admin_model->csrfguard_validate_token($this->input->post('csrf_name'),$this->input->post('csrf_token'));
		if($this->form_validation->run() === TRUE && $csrf_check==true)
		{
			$product = $this->admin_model->get_all_records('product','Id,ProductName',array('Status'=>'Enable', 'CatId' => $this->input->post('id')),'Id Desc')->result();
			$unique_form_name = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$token = $this->admin_model->csrfguard_generate_token($unique_form_name);
			$message=array('code'=>1,'csrf_name'=>$unique_form_name,'csrf_token'=>$token, 'pro'=>$product);
		}
		else  //EMPTY FIELDS
		{   
		  	$unique_form_name = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$token = $this->admin_model->csrfguard_generate_token($unique_form_name);
			$message=array('code'=>0,'csrf_name'=>$unique_form_name,'csrf_token'=>$token);	
		}
				
		header('Content-Type: application/json');
		echo json_encode($message);
		die();
	}

	public function order()
	{
		$this->load->library(array('form_validation'));
		$this->form_validation->set_rules('id', 'ID','required');

		$csrf_check = $this->admin_model->csrfguard_validate_token($this->input->post('csrf_name'),$this->input->post('csrf_token'));
		if($this->form_validation->run() === TRUE && $csrf_check==true)
		{

		    $user_id = $this->session->userdata('adminid');
			$prod_id = $this->input->post('id');


			$val = " product.Id, product.ProductName ,product.Image, product.Price, reference_price.Price as RefPrice";
			$join = array(
				array(
					'table'=>'fhs_reference_price',
					'condition'=>'fhs_reference_price.ProductId = fhs_product.Id AND fhs_reference_price.UserId = '.$user_id,
					'jointype'=> 'left'
				),

			);
			$array = array('product.Status !=' => 'Delete','product.Id' =>$prod_id);

			$product = $this->admin_model->get_joins('product', $val, $join, $array,'product.Id','ASC','')->row();

			if($product->RefPrice != "")
			{
				$price = $product->RefPrice;
			}
			else{
				$price = $product->Price;
			}


			$unique_form_name = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$token = $this->admin_model->csrfguard_generate_token($unique_form_name);
			$message=array('code'=>1,'csrf_name'=>$unique_form_name,'csrf_token'=>$token, 'pro'=>$product, 'price'=>$price);
		}
		else  //EMPTY FIELDS
		{
			$unique_form_name = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$token = $this->admin_model->csrfguard_generate_token($unique_form_name);
			$message=array('code'=>0,'csrf_name'=>$unique_form_name,'csrf_token'=>$token);
		}

		header('Content-Type: application/json');
		echo json_encode($message);
		die();
	}

}
