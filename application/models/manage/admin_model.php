<?php class Admin_model extends CI_Model {

	public function __construct()
	{
		$this->lang->load("form_validation","English");
		error_reporting(0);

		// $val = "order.*,adminmaster.Name as adminname, product.ProductName, category.CategoryName";
		// $join = array(
		// 	array(
		// 		'table' => 'adminmaster',
		// 		'condition' => 'order.UserId = adminmaster.Id',
		// 		'join_type' => 'LEFT'
		// 	),
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
		// );
		// $CI = &get_instance();
		// $CI->order_notification = $this->get_joins('order',$val,$join,array('order.Status !='=>'Delete','order.OrderStatus'=>'Pending', 'adminmaster.RoleId' => ROLE_SUPER_ADMIN),'order.Id','DESC')->result();

	}
	function send_mail($to_email,$from_email,$from_text,$subject,$message,$replyto=''){
			$config = Array(
					  'useragent' => 'CodeIgniter',
					  'protocol' => 'smtp',
					  'mailpath' => '/usr/sbin/sendmail',
					  'smtp_host' => $this->site_setting->smtp_host,
					  'smtp_user' => $this->site_setting->site_smtp_email,	
					  'smtp_pass' => $this->site_setting->site_smtp_pwd,
					  'smtp_port' => $this->site_setting->smtp_port,
					  'smtp_timeout' =>15,
					  'wordwrap' => TRUE,
					  'mailtype' => 'html',
					  'charset' => 'utf-8',
					  'validate' =>FALSE,
					  'priority' => 3,
					  'newline' => "\r\n",
					  'bcc_batch_mode' => FALSE,
					  'bcc_batch_size' => 200,
					  //'smtp_crypto' => 'ssl',
				  );
	
			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");
			$this->email->from($from_email,$from_text);
			if($replyto!='')
			$this->email->reply_to($replyto,'');					   
			$this->email->to($to_email);
			$this->email->subject($subject);   // should be subject as required and use admin message file to write subject so we can use multi language
			$this->email->message($message); 
			return $this->res=$this->email->send();
			//echo $this->email->print_debugger();
	}	
	
	

	function delete($table,$where, $in_key='' ,$in_array=array())
	{ 
		$this->db->where($where);
		if($in_key!='' && !empty($in_array))
		$this->db->where_in($in_key,$in_array);
		$this->db->delete($table);
		return $this->db->affected_rows(); 
	}
	public function get_joins($table,$value,$joins,$where,$order_by,$order,$limit='',$offset='',$distinct='',$likearray='',$groupby='',$whereinvalue='',$whereinarray='')
	{
		$this->db->select($value);
		if (is_array($joins) && count($joins) > 0)
		{
		 foreach($joins as $k => $v)
		   {
			$this->db->join($v['table'], $v['condition'], $v['jointype']);
		   }
		}
		$this->db->order_by($order_by,$order);
		$this->db->where($where);
		if($distinct!=='')
			$this->db->distinct();
		if($likearray!='')	
			$this->db->where($likearray);	
		
		if($groupby!='')
			$this->db->group_by($groupby);
		if(!empty($whereinvalue) && $whereinvalue!='')	
			$this->db->where_in($whereinvalue,$whereinarray);
		if($limit!='' && $offset!='')
			return $this->db->get($table,$limit,$offset);
		else
			return $this->db->get($table);
	}
	function insert_modified($values,$table='modified_log') //Mayank
	{
		$this->ip_date = $this->admin_model->get_date_ip();
		$ModifiedBy = $this->session->userdata('adminid');
		$ModifiedDate=$this->ip_date->cur_date;
		$ModifiedIp=$this->ip_date->ip;
		 //echo count($values);
		if (is_array($values[0]))
		{
			$i=0;
			foreach($values as $val)
			{
				$values[$i]['ModifiedBy']=$ModifiedBy;
				$values[$i]['ModifiedDate']=$ModifiedDate;
				$values[$i]['ModifiedIp']=$ModifiedIp;
				$i++;
			}
			$this->db->insert_batch($table,$values);
		}
		else{
			$values['ModifiedBy']=$ModifiedBy;
			$values['ModifiedDate']=$ModifiedDate;
			$values['ModifiedIp']=$ModifiedIp;
			$this->db->insert($table, $values);
		}
		return $this->db->insert_id();
	}
	//check session	
	function check_seesion($target_url='', $access='')
	{
		if($this->session->userdata('admin_logged_in') == FALSE)
		{
			$data = array('title'=>$this->lang->line('admin')." ". $this->lang->line('login'));
			$data['target'] = $target_url!=''?$target_url:'manage/home'; 
			$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
			$data['token'] = $this->csrfguard_generate_token($data['unique_form_name']);
		   	$this->load->view('manage/login', $data);
			echo $this->output->get_output(); 
			exit;
		}
		else
		{
			if(is_array($access))
			{
				if(!in_array($this->session->userdata('admin_role'),$access) )
				{
					$data = array('title'=>'Not accessible','msg' => 'You are not allowed to access this page.');
					$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
					$data['token'] = $this->csrfguard_generate_token($data['unique_form_name']);
					$this->load->view('manage/includes/header', $data);
					$this->load->view('manage/not_accessible');
				//	$this->load->view('manage/includes/footer');
					echo $this->output->get_output(); 
					exit;
				}
			}
			else
			{
				if($access!='' && ($access != $this->session->userdata('admin_role')))
				{
					$data = array('msg' => 'You are not allowed to access this page.','title'=>'Not accessible');
					$data['unique_form_name'] = "CSRFGuard_".mt_rand(0,mt_getrandmax());
					$data['token'] = $this->admin_model->csrfguard_generate_token($data['unique_form_name']);
					$this->load->view('manage/includes/header', $data);
					$this->load->view('manage/not_accessible');
				//	$this->load->view('manage/includes/footer');
					echo $this->output->get_output(); 
					exit;
				}
			}
			
			//check status
			$result = $this->db->select('Id')->from('adminmaster')->where('Id',$this->session->userdata('adminid'))->where('Status','Enable')->limit(1)->get()->row();
			if($result->Id =='')
			{	
				$array_items = array('adminname'=>'', 'adminid'=>'', 'admin_logged_in'=>FALSE);
				$this->session->unset_userdata($array_items);
				redirect(site_url('manage/login'));
			}
			
						
		}
	}
	
	// CHECK ADMIN LOGIN EMAIL - 26th nov
	function check_login($table, $where_array)
	{
		$query = $this->db->get_where($table, $where_array);
		return $query->row_array();	
	}
	function csrfguard_generate_token($unique_form_name ) //kishan
	{
		if (function_exists("hash_algos") and in_array("sha512",hash_algos()))
		{
			$token=hash("sha512",mt_rand(0,mt_getrandmax()));
		}
		else
		{
			$token=' ';
			for ($i=0;$i<128;++$i)
			{
				$r=mt_rand(0,35);
				if ($r<26)
				{
					$c=chr(ord('a')+$r);
				}
				else
				{ 
					$c=chr(ord('0')+$r-26);
				} 
				$token.=$c;
			}
		}
		
		$this->store_in_session($unique_form_name,$token);
		return $token;
	}
	function save($table,$admin){
		$this->db->insert($table, $admin);
		return $this->db->insert_id();
	}
	function clean_session($param='')
	{
		$array_items = array('adminname'=>'', 'adminid'=>'', 'admin_logged_in'=>FALSE,'admin_role'=>'',);
		$this->session->unset_userdata($array_items);
	}
	function create_pwd_salt($length = '3')
	{
		$string = md5(uniqid(rand(), true));
		return substr($string, 0, $length);
    }
	function csrfguard_validate_token($unique_form_name,$token_value) // kishan
	{
		if($unique_form_name=='')
			return false;
		$token=$this->get_from_session($unique_form_name);
		if ($token===false)
		{
			$result=false;
		}
		elseif ($token===$token_value)
		{
			$result=true;
		}
		else
		{ 
			$result=false;
		} 
		$this->unset_session($unique_form_name);
		return $result;
	}
	function store_in_session($key,$value)  //kishan
	{	
		$this->session->set_userdata($key,$value);
	}
	function unset_session($key) //kishan
	{
		$this->session->unset_userdata($key); //kishan
	}
	function get_from_session($key) //kishan
	{
		if ($this->session->userdata($key)!='')
		{
			return $this->session->userdata($key);
		}
		else
		{  
			return false; 
		} 
	}
	function generateToken($length = 40) {
       
        $characters = '0123456789';
        $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
        $charactersLength = strlen($characters)-1;
        $password = '';

  		for ($i = 0; $i < $length; $i++) 				 //select some random characters
		{
            $password .= $characters[mt_rand(0, $charactersLength)];
        }        
        
        return $password;
	}
	function get_date_ip()
	{
		//return $this->db->query('SELECT INET_ATON("'.$this->input->ip_address().'") AS ip,  NOW() AS cur_date ')->row();
		return $this->db->query('SELECT 3132312 AS ip,  NOW() AS cur_date ')->row();
    }
	function filterOutput($string){  
	
		if(is_object($string)){ 
			foreach($string as $key => $val) { 
				$string->$key =trim(htmlspecialchars(stripslashes($val)));
			}
		} else {
			$string=trim(htmlspecialchars(stripslashes($string)));
		}
		
		return 	$string;
	}
	function count_all($table,$where,$likearray='')
	{   
	   if($likearray!='')
	   		$this->db->where($likearray);
	   return $this->db->where($where)->count_all_results($table);
	}
	function get_all_records($table, $select=' * ', $where='', $orderby='', $limit='',$offset=0,$likearray='',$order='',$groupby='')
	{

		$this->db->select($select);
		
		if($likearray!='')
			$this->db->where($likearray);	
		
		if(strlen($orderby)){$this->db->order_by($orderby,$order);}  // ORDER BY  e.g 'Id desc, name asc'
		
		if(!empty($where)){	$this->db->where($where);}  // WHERE CONDITION r.g array('id'=>10, 'status !=' => 'Delete')  
		if($groupby!='')
		$this->db->group_by($groupby);
		if(strlen($limit)){
			return $this->db->get($table, $limit, $offset);
		}else {
			return $this->db->get($table);
		}	
	}
	function Encryption($val)  // kishan URI freindly encryption
	{
		$letter1 = ucfirst(chr(rand(97,122)));
		$letter2 = ucfirst(chr(rand(97,122)));
		$letter3 = ucfirst(chr(rand(97,122)));
		$letter4 = ucfirst(chr(rand(97,122)));
		$str1=$letter1.$letter4."#";
		$str2="#".$letter2.$letter3;
		 return rtrim(strtr(base64_encode($str1.$val.$str2), '+/', '-_'), '='); 
		//return base64_encode($str1.$val.$str2);
	}
	
	/*DECRYPT ID*/
	function Decryption($val) // kishan URI freindly decryption
	{
		$exp = explode("#",base64_decode(str_pad(strtr($val, '-_', '+/'), strlen($val) % 4, '=', STR_PAD_RIGHT)));
		return $exp[1];
	}
	public function get_joinlist($table,$value,$joins,$where,$order_by,$order,$limit,$offset)
	{
		$this->db->start_cache();
		$this->db->select($value);
		if (is_array($joins) && count($joins) > 0)
		{
		 foreach($joins as $k => $v)
		   {
			$this->db->join($v['table'], $v['condition'], $v['jointype']);
		   }
		}
		$this->db->order_by($order_by,$order);
		$this->db->where($where);
		$this->db->stop_cache();
		
		$query_result['total_records']=$this->db->get($table)->num_rows();
		$query_result['results']=$this->db->get($table, $limit, $offset)->result();
		$this->db->flush_cache();
		return $query_result;	
	}
	function create_unique_slug($string,$table,$field='Slug',$key=NULL,$value=NULL)
	{
		$t =& get_instance();
		$slug = url_title($string);
		$slug = strtolower($slug);
		$i = 0;
		$params = array ();
		$params[$field] = $slug;
		
		if($key)$params["$key !="] = $value;
		
		while ($t->db->where($params)->get($table)->num_rows())
		{  
			if (!preg_match ('/-{1}[0-9]+$/', $slug ))
			$slug .= '-' . ++$i;
			else
			$slug = preg_replace ('/[0-9]+$/', ++$i, $slug );
			
			$params [$field] = $slug;
		}  
		return $slug;  
	}
	function replace($table,$value){ //kishan for insert/replace depends on key
		$this->db->replace($table, $value);
		return $this->db->insert_id();
	}
	function update($table,$array,$where)
	{ 
		$this->db->where($where);
		$this->db->update($table,$array);
	}
	public function custom_query($query)
	{
		return $this->db->query($query);
	}
	function update_set($table,$coulmn,$value,$where)
	{
		$this->db->where($where);
		$this->db->set($coulmn, $value, FALSE);
		$this->db->update($table);
	}
	function check_record_exist($table,$select_value,$array)
	{
		$query=$this->db->select($select_value);
		$query=$this->db->limit(1);
		$query=$this->db->get_where($table,$array);
		return $query->row_array();	
	}
	public function get_joinlist_like($table,$value,$joins,$where,$order_by,$order,$limit,$offset,$likearray)// DEPRECATED 
     {
	    $this->db->start_cache();
		$this->db->select($value);
		
		 if (is_array($joins) && count($joins) > 0)
		  {
			 foreach($joins as $k => $v)
			   {
				$this->db->join($v['table'], $v['condition'], $v['jointype']);
			   }
		  }
		$this->db->order_by($order_by,$order);
		$this->db->where($where);
		$this->db->where($likearray);
		$this->db->stop_cache();
		
		$query['total_records']=$this->db->get($table)->num_rows();
		$query['results']=$this->db->get($table, $limit, $offset)->result();
		$this->db->flush_cache();
		return $query;
     }
	 function check_image($name,$size='') //mayank
	{
		if(file_exists(APPPATH."../".$name) && is_file(APPPATH."../".$name))
		 	return (site_url().$name);
	  	else{
			if($size==1)
				return (site_url()."uploads/default/default_big.png");
			else
				return (site_url()."uploads/default/default.png");	 
		}
	}
	function getDates($startDate, $endDate)    //kishan
	{
		$return = array($startDate);
		$start = $startDate;
		$i=1;
		if (strtotime($startDate) < strtotime($endDate))
		{
		   while (strtotime($start) < strtotime($endDate))
			{
				$start = date('Y-m-d', strtotime($startDate.'+'.$i.' days'));
				$return[] = $start;
				$i++;
			}
		}
		return $return;
	}



}
