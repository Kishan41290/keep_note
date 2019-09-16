<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Controller {
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
		$data = array('title'=>'User','message'=>'', 'link_add'=>site_url().'manage/user/add/', 'edit_link'=>site_url('manage/user/edit'),'info_link'=>site_url().'manage/user/information/' ,'tbl'=>'users','multi_action'=> site_url('manage/user/multiaction/'.$offset.'?search='.$search),'search_action'=> site_url().'manage/user/','module'=>$this->lang->line('admin_magazine_user'));
		$data['list_records']=array();
		$select_value= '*';
		if($search=='')
		{
			$this->total = $this->admin_model->count_all($data['tbl'],array('Status !='=>'Delete'));
			if($this->total!=0)
			{
				$data['list_records']= $this->admin_model->get_all_records($data['tbl'],$select_value,array('Status !='=>'Delete'),'Id DESC', $this->limit, $offset)->result();
				

			}
			else
			{
				 $data['no_data']=1;	
			}
		}
		else
		{
			$search_filter = $this->db->escape_like_str($search);
			$this->total = $this->admin_model->count_all($data['tbl'],array('Status !='=>'Delete'),"(Email Like '%".$search_filter."%' OR FirstName Like '%".$search_filter."%')");
			if($this->total!=0)
			{
				$data['list_records']= $this->admin_model->get_all_records($data['tbl'],$select_value,array('status !='=>'Delete'), 'FirstName ASC', $this->limit, $offset,"(Email Like '%".$search_filter."%' OR FirstName Like '%".$search_filter."%')")->result();
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
		$config['base_url'] = site_url('manage/user/index/');
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
		$this->load->view('manage/user');
		$this->load->view('manage/includes/footer');
	}
	
	
	public function information($id){
		$data = array('title'=>'User Information', 'tbl'=>'users', 'link_back'=>site_url('manage/user') );

		$data['result']= $this->admin_model->get_all_records('users','*',array('Status !='=>'Delete'),'Id DESC', 1)->row();

		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
		$this->load->view('manage/includes/header', $data);
		$this->load->view('manage/user_info');
		$this->load->view('manage/includes/footer');
	}

		
}
