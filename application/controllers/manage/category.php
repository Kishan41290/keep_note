<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Category extends CI_Controller {
	private $limit = 10;
    function __construct()
	{  
		parent::__construct();		
		$this->load->model('manage/admin_model');		
		$this->load->helper('form');
		$this->load->library(array('form_validation'));
        $this->limit = @$this->site_setting->site_admin_rowperpage;
        $this->admin_model->check_seesion(current_url(), array(ROLE_SUPPER_ADMIN));
	}
	function index()
	{
        $uri_segment = 4;
        $offset = $this->uri->segment($uri_segment);
        $this->form_validation->set_rules('search','Search','trim|xss_clean');
        $search =urldecode($this->input->get('search'));
        $data = array('title'=>'Category','message'=>'', 'link_add'=>site_url().'manage_admin/category/add/', 'edit_link'=>site_url('manage/category/edit'),'info_link'=>site_url().'manage/category/information/' ,'tbl'=>'category','search_action'=> site_url().'manage/category/','module'=>'category');
        $data['list_records']=array();
        $select_value= 'Id,CategoryName,Status';
        if($search=='')
        {
            $this->total = $this->admin_model->count_all($data['tbl'],array('Status !='=>'Delete'));
            if($this->total!=0)
            {
                $data['list_records']= $this->admin_model->get_all_records($data['tbl'],$select_value,array('Status !='=>'Delete'),'CategoryName ASC', $this->limit, $offset)->result();
            }
            else
            {
                $data['no_data']=1;
            }
        }
        else
        {
            $search_filter = $this->db->escape_like_str($search);
            $this->total = $this->admin_model->count_all($data['tbl'],array('Status !='=>'Delete','Id !='=>$this->session->userdata('adminid')),"(CategoryName Like '%".$search_filter."%')");
            if($this->total!=0)
            {
                $data['list_records']= $this->admin_model->get_all_records($data['tbl'],$select_value,array('status !='=>'Delete','Id !='=>$this->session->userdata('adminid')), 'CategoryName ASC', $this->limit, $offset,"(CategoryName Like '%".$search_filter."%')")->result();
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
        $config['base_url'] = site_url('manage/category/index/');
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
        $this->load->view('manage/category');

    }
	function insert()
	{

      if(empty($_POST))
      {
          if($csrf_check==false) $data['csrf_error'] = $this->lang->line('csrf_error');
          $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
          $data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
          $data = array('title'=>'Add Category ','action'=>site_url('manage/category/insert'));
          $this->load->view('manage/includes/header', $data);
          $this->load->view('manage/category_edit');

      }
      else
      {
              $this->ip_date = $this->admin_model->get_date_ip();
              $value_array = array(
                  'CategoryName' => $this->input->post('category_name'),
                  'Status'=>'Enable',
                  'CreatedDate'=>$this->ip_date->cur_date,
                  'CreatedIp'=>$this->ip_date->ip,
              );
              $this->session->set_flashdata('notification','Category Add successfully ');
              $this->admin_model->save('category',$value_array);
              redirect(site_url('manage/category/index'));
      }

    }
    function edit($id='')
    {
        if(!is_numeric($id))
           $this->insert();

        $data = array('title'=>'edit city','message'=>'', 'message'=>'', 'link_back'=>site_url('manage/category'), 'link_add'=>site_url('manage/category/insert'));
        $data['method']="Edit";
        $data['action'] = site_url('manage/category/update/'.$id);

        $result = $this->admin_model->get_all_records('category','*',array('Id'=>$id,'Status !='=>'Delete'),'',1)->row();
       // redirect(site_url('manage/category/index'));
        if(!empty($result))
        {
            $this->form_data->id = $id;
            $this->form_data->categoryname = $result->CategoryName;
            $data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
            $data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
            $this->load->view('manage/includes/header', $data);
            $this->load->view('manage/category_edit');
        }
        else
        {
            $this->insert();
        }
    }


    function update($id)
    {



       $csrf_name=$_POST['csrf_name'];
       $csrf_token=$_POST['csrf_token'];
        $csrf_check = $this->admin_model->csrfguard_validate_token($csrf_name,$csrf_token);

        if($csrf_check==true)
        {
            $update_array =  array(
                'CategoryName' => $this->input->post('category_name'),
            );
            $this->admin_model->update('category',$update_array,array('Id'=>$id));
            $this->session->set_flashdata('notification','Category Update successfully ');
            redirect(site_url('manage/category'));
        }else
        {

            $this->session->set_flashdata('error','Category Not Update successfully ');
            redirect(site_url('manage/category'));

        }

    }


}

