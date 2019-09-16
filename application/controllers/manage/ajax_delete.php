<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax_delete extends CI_Controller {
	
	public function __construct()
	 {
		parent::__construct();
		$this->load->model('manage/admin_model');
		$this->admin_model->check_seesion(current_url(), array(ROLE_SUPPER_ADMIN,MAGAZINE_ADMIN));	
	 }
	
	public function index()
	{
		$data['tbl'] = $this->admin_model->Decryption($this->input->post('tbl'));
		
			$modified_status='Delete';	
			$csrf_check = $this->admin_model->csrfguard_validate_token($this->input->post('csrf_name'),$this->input->post('csrf_token'));
			if($csrf_check==true)
			{
				$id = $this->admin_model->update($data['tbl'],array('Status'=>$modified_status),array('Id'=>$this->input->post('id')));
				$message=array('code'=>1);
			}
			else
				$message=array('code'=>0);		
		
		$message['csrf_name']="CSRFGuard_".mt_rand(0,mt_getrandmax());
		$message['csrf_token']=$this->admin_model->csrfguard_generate_token($message['csrf_name']);
		
		header('Content-Type: application/json');
		echo json_encode($message);
		die();
	}
	public function get_commune()
	{
		header('Content-Type: application/json');
		$rid = $this->input->post('region');
		if(is_numeric($rid))
		{
			$communes =$this->admin_model->get_all_records('commune','Id,ShortName,Name',array('Status !='=>'Delete','RegionId'=>$rid),'Name')->result();
			echo json_encode(array('code'=>1,'communes'=>$communes));
		}
		else
			echo json_encode(array('code'=>0));
	}
	public function get_commune_list()
	{
		header('Content-Type: application/json');
		$rid = $this->input->post('region');
		if(is_numeric($rid))
		{
			$communes =$this->admin_model->get_all_records('commune','Id,ShortName,Name',array('Status !='=>'Delete','RegionId'=>$rid),'Name')->result();
			echo json_encode(array('code'=>1,'communes'=>$communes));
		}
		else
		{
			$communes =$this->admin_model->get_all_records('commune','Id,ShortName,Name',array('Status !='=>'Delete'),'Name')->result();
			echo json_encode(array('code'=>1,'communes'=>$communes));
		}
	}
}

/* End of file home.php */
/* Location: ./application/controllers/manage/home.php */