<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Settings extends CI_Controller {
	var $limit = 10;
	
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('form_validation'));
		$this->load->model('manage/admin_model');
		$this->load->helper('form');
	//	$this->limit = @$this->site_setting->site_admin_rowperpage;
		$this->admin_model->check_seesion(current_url(),array(ROLE_SUPPER_ADMIN));		
	}
	function index($msg='',$offset = 0)
	{
		$data = array('title'=>'Settings','message'=>'','edit_link'=>site_url('manage/settings/edit'),'link_add'=>site_url('manage/settings/add'), 'tbl'=>'setting' ,'module'=>'Setting','link_info'=>site_url('manage/settings/index'));
		
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);
		
		$page_type=$_GET['section']==''?"General":$_GET['section'];
		$data['list_records'] = $this->admin_model->get_all_records($data['tbl'],'*',array('Status'=>'Enable'),'')->result();

		// load view
		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
		$this->load->view('manage/includes/header', $data);
		$this->load->view('manage/settings');
		$this->load->view('manage/includes/footer');
	}
	function edit($id='')
	{
		if(!is_numeric($id))
			site_url('manage/settings/index/?section='.$this->input->get('section'));
		
		$data = array('title'=>'Edit Setting','message'=>'', 'message'=>'', 'link_back'=>$_SERVER['HTTP_REFERER'],'tbl'=>'setting');
		$data['action'] = site_url('manage/settings/update/'.$id);
		$result = $this->admin_model->get_all_records($data['tbl'],'*',array('Id'=>$id),'',1)->row();
		$this->form_data->keytext = $result->Keytext;
		$this->form_data->title = $result->Fieldname;
		$this->form_data->value = $result->Value;
		$data['field'] = $result;
		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);	
		// load view
		$this->load->view('manage/includes/header', $data);
		$this->load->view('manage/settingsedit');
		$this->load->view('manage/includes/footer');


	}
	function update($id)
	{
		$data = array('title'=>'Edit Setting','message'=>'', 'message'=>'', 'link_back'=>site_url('manage/settings'));
		$data['action'] = site_url('manage/settings/update/'.$id);
		
		$this->_set_rules();
		if(is_array($this->input->post()))
		extract($this->input->post());
		$csrf_check = $this->admin_model->csrfguard_validate_token($csrf_name,$csrf_token);
		if($this->form_validation->run() === TRUE && $csrf_check==true)
		{
			// save data
			$value_array = array(
							'Value' => $this->input->post('value'),
							'Fieldname' => $this->input->post('title'),
							);
			$id = $this->admin_model->update('setting',$value_array,array('Id'=>$id));
		    $this->session->set_flashdata('notification',$this->lang->line('setting_succ_modified'));
            redirect(site_url('manage/settings/index'));
			die();
		}
		else
		{
			if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');
			$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);	
			$data['message'] = 'Enter All Fields';
			$this->load->view('manage/includes/header', $data);
			$this->load->view('manage/settingsedit');
			$this->load->view('manage/includes/footer');

		}
	 }
	 function add()
	 {
		 // SET COMMON PROPERTIES
		$data = array( 'title'=>'Add new Setting','message'=>'', 'action'=>site_url('manage/settings/insert'), 'link_back'=>site_url('manage/settings'),'link_add'=>site_url('manage/settings/add'),'tbl'=>'setting' ,'add'=>'Yes');
		 // load view
		$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
		$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);	
		$this->load->view('manage/includes/header', $data);
		$this->load->view('manage/settingsedit');
		$this->load->view('manage/includes/footer');

	 }
	function insert()
	{
		$data = array( 'title'=>'Add New Setting','message'=>'', 'action'=>site_url('manage/settings/insert'), 'link_back'=>site_url('manage/settings'), 'link_add'=>site_url('manage/settings/add'),'tbl'=>'setting' );
		
		// set validation properties
		$this->_set_rules();
		if(is_array($this->input->post()))
			extract($this->input->post());
		$csrf_check = $this->admin_model->csrfguard_validate_token($csrf_name,$csrf_token);
		if($this->form_validation->run() === TRUE && $csrf_check==true)
		{
			$this->ip_date = $this->admin_model->get_date_ip();
			// save data
			$value_array = array(
								'fieldName' => $this->input->post('title'),
								'keytext' => $this->input->post('keytext') ,
								'value' =>$this->input->post('value'),
								'status'=>'Enable',
								'CreatedDate'=> $this->ip_date->cur_date,
								'CreatedIp'=> $this->ip_date->ip,
							);
			$id = $this->admin_model->save($data['tbl'],$value_array);				
			if(!empty($error))
			{
				$this->session->set_flashdata('error',$this->lang->line('store_pro_image'));
				redirect(site_url('manage/settings/edit/'.$id));
			}
			else
			{
				$this->session->set_flashdata('notification',$this->lang->line('setting_succ_added'));
				redirect(site_url('manage/settings'));
				die();
			}
		}
		else
		{	
			if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');
			$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
			$this->load->view('manage/includes/header', $data);
			$this->load->view('manage/settingsedit');
			$this->load->view('manage/includes/footer');
		}
	}
	 function _set_fields()
	{
		$this->form_data->value = '';
	}
	
	// validation rules
	function _set_rules()
	{
		$this->form_validation->set_rules('value',$this->lang->line('value'), 'trim|required');
		$this->form_validation->set_rules('keytext', $this->lang->line('key'), 'trim|required|max_length[200]');
		$this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|max_length[200]');
    }
}
