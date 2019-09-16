<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_slug extends CI_Controller {
	
	public function __construct()
	 {
		parent::__construct();
		$this->load->model('manage/admin_model');
		$this->admin_model->check_seesion(current_url(), array(ROLE_SUPPER_ADMIN,MAGAZINE_ADMIN));	
	 }
	
	public function index()
	{
		$slug_string	=	$this->input->post('slug_string');		
		$slug_table		=	$this->admin_model->Decryption($this->input->post('slug_table'));
		$val 			=   $this->input->post('id_val');			

		if($val!="")
			$slug=$this->admin_model->create_unique_slug($slug_string,$slug_table,'Slug','Id',$val);
		else
			$slug=$this->admin_model->create_unique_slug($slug_string,$slug_table,'Slug','','');	
		
		if($slug!="")
			$message=array('code'=>1,'msg'=>$slug);	
		else
			$message=array('code'=>0);
			
		header('Content-Type: application/json');
		echo json_encode($message);
		die();
	}
}
