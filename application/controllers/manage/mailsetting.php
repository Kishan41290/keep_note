<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mailsetting extends CI_Controller {
	function __construct()
	{ 
		parent::__construct();
		$this->load->library(array('form_validation'));
		$this->load->model('manage/admin_model');
		$this->load->helper('form');
		$this->limit = @$this->site_setting->site_admin_rowperpage;
		$this->admin_model->check_seesion(current_url(), array(ROLE_SUPPER_ADMIN));
	}
	function index($msg='',$offset = 0)
	{
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);
		$search =urldecode($this->input->get('search'));
		
		$data = array('title'=>$this->lang->line('mail_settings'),'message'=>'', 'link_add'=>site_url('manage/mailsetting/add'), 'edit_link'=>site_url('manage/mailsetting/edit'),'tbl'=>'mailsettings','module'=>$this->lang->line('mail_setting') );
		// load data
		$data['action'] = site_url('manage/mailsetting/index');
		$data['list_records']=array();
		$value='Id,Title,Status';
		$this->total = $this->admin_model->count_all($data['tbl'],array('Status !='=>'Delete'));
		if($this->total !=0)
			$this->results= $this->admin_model->get_all_records($data['tbl'],$value,array('Status !='=>'Delete'),'Id DESC',$this->limit, $offset)->result();
		else 
			$data['no_data']=1;
		
		$data['list_records']= $this->results;
	  
		if($msg=='m')$data['message'] = $this->lang->line('cms_page_added');
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('manage/mailsetting/index/');
 		$config['total_rows'] =  $this->total;
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
		$this->load->view('manage/mailsetting');
		
	}
	 function edit($id='')
	{
		if(!is_numeric($id)) 
			$this->add();
		// set common properties
		$data = array('title'=>$this->lang->line('edit')." ".$this->lang->line('mail_settings'),'message'=>'', 'message'=>'', 'link_back'=>site_url('manage/mailsetting'), 'link_add'=>site_url('manage/mailsetting/add'),'tbl'=>'mailsettings');
		
		$data['action'] = site_url('manage/mailsetting/update/'.$id);
		$value='Id,FromText,Email,Title,Subject,Content';
		$result =  $this->admin_model->get_all_records($data['tbl'],$value,array('Id'=>$id,'Status !='=>'Delete'),'',1)->row();
		if(!empty($result))
		{
			$this->form_data->id = $result->Id;
			$this->form_data->title = $result->Title;
			$this->form_data->email = $result->Email;
			$this->form_data->from_text = $result->FromText;
			$this->form_data->subject = $result->Subject;
			$this->form_data->page_content = $result->Content;
			
			$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
			// load view
			$this->load->view('manage/includes/header', $data);
			$this->load->view('manage/mailsetting_edit');
		}
		else
		{
			 $this->session->set_flashdata('error',$this->lang->line('mail_not_exist'));	
		}
	}
	function update($id)
	{
		if(!is_numeric($id)) 
			$this->add();
		$data = array('title'=>$this->lang->line('edit')." ".$this->lang->line('mail_settings'),'message'=>'', 'message'=>'', 'link_back'=>site_url('manage/mailsetting'), 'link_add'=>site_url('manage/mailsetting/add'),'tbl'=>'mailsettings');
		
		$data['action'] = site_url('manage/mailsetting/update/'.$id);
		
		// set empty default form field values
		$this->_set_rules();
		
		if(is_array($this->input->post()))
			extract($this->input->post());
		$csrf_check = $this->admin_model->csrfguard_validate_token($csrf_name,$csrf_token);
		
		if($this->form_validation->run() === TRUE && $csrf_check==true)
		{
			$value_array = array(
				'Title' => $title,
				'Email' => $email,
				'FromText' => $from_text,
				'Subject' => $subject,
				'Content' => $page_content,
				'CreatedBy' => $this->session->userdata('adminid'),
				'CreatedDate'=>$this->ip_date->cur_date,
				'CreatedIp'=>$this->ip_date->ip,
			  );
			$this->admin_model->update($data['tbl'],$value_array,array('Id'=>$id));
			
		    $this->session->set_flashdata('notification',$this->lang->line('mail_succ_modified'));
            redirect(site_url('manage/mailsetting/index'));
			die();
		}
		else
		{
			if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');
			$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
			
			$this->load->view('manage/includes/header', $data);
			$this->load->view('manage/mailsetting_edit');
			
		}
	 }
	 
	// validation rules
	function _set_rules()
	{
		$this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|max_length[100]');
		$this->form_validation->set_rules('email', $this->lang->line('from_email'), 'trim|required|valid_email|max_length[100]');
		$this->form_validation->set_rules('from_text', $this->lang->line('from_text'), 'trim|required|max_length[100]');
		$this->form_validation->set_rules('subject', $this->lang->line('subject'), 'trim|required|max_length[100]');
		$this->form_validation->set_rules('page_content', $this->lang->line('mail_content'), '');
	}
	
}