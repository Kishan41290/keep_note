<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_status extends CI_Controller {
	
	public function __construct()
	 {
		parent::__construct();
		$this->load->model('manage/admin_model');
		$this->admin_model->check_seesion(current_url(), array(ROLE_SUPPER_ADMIN,MAGAZINE_ADMIN));	
	 }
	
	public function index()
	{
		$this->load->library(array('form_validation'));
		$this->form_validation->set_rules('id', 'ID','required');
		$this->form_validation->set_rules('tbl', 'Table','required');
		$csrf_check = $this->admin_model->csrfguard_validate_token($this->input->post('csrf_name'),$this->input->post('csrf_token'));
		if($this->form_validation->run() === TRUE && $csrf_check==true)
		{
			$data['tbl'] = $this->admin_model->Decryption($this->input->post('tbl'));
			
			if($data['tbl'] == 'mageinsd') $id = 'stateId';
			else
			$id = 'Id';
			
			$query = "UPDATE fhs_".$data['tbl']." SET Status = if(status='Enable','Disable','Enable' ) Where ".$id."=".$this->input->post('id');
			$this->admin_model->custom_query($query);
			$unique_form_name = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$token = $this->admin_model->csrfguard_generate_token($unique_form_name);
			$message=array('code'=>1,'csrf_name'=>$unique_form_name,'csrf_token'=>$token);		
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
