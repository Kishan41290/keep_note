<?php
class Common_model extends CI_Model {
	public function __construct()
	{
		$this->lang->load("form_validation","english");	
	}
	
	///////////////////////////////////
	#######  COMMON FUNCTIONS  ########
	///////////////////////////////////
	function send_mail($to_email,$from_email,$subject,$from_text,$message,$password,$replyto=''){
			
			$config = Array(
					  'useragent' => 'CodeIgniter',
					  'protocol' => 'smtp',
					  'mailpath' => '/usr/sbin/sendmail',
					  'smtp_host' => $this->site_setting->smtp_host,
					  'smtp_user' => $this->site_setting->site_smtp_email,	
					  'smtp_pass' =>$this->site_setting->site_smtp_pwd,
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
			$this->email->print_debugger(); 
			return $this->res=$this->email->send();
	}
	## CUSTOM QUERY - mysql query ##
	public function custom_query($query)
	{
		return $this->db->query($query);
	}
	
	## GET ALL RECORD LIST  - table, where, orderby ##
	public function get_all_records($table, $select=' * ', $where='', $orderby='', $limit='', $offset=0,$likearray='',$order='',$or_where='')
	{
		$this->db->select($select);
		if($likearray!='')
			$this->db->where($likearray);	
		if(strlen($orderby)){$this->db->order_by($orderby);}  // ORDER BY  e.g 'Id desc, name asc'
		
		if(!empty($where)){	$this->db->where($where);}  // WHERE CONDITION r.g array('id'=>10, 'status !=' => 'Delete')  
		
		if(!empty($or_where)){	$this->db->or_where($or_where);}  // WHERE CONDITION r.g array('id'=>10, 'status !=' => 'Delete')  
		
		if(strlen($limit)){
			return $this->db->get($table, $limit, $offset);
		}else {
			return $this->db->get($table);
		}	
	}
	
	// GET ALL RECORDS COUNTER AS PER WHERE CONDITION
	function count_all($table,$where='',$likearray='')
	{   
	   if($likearray!='')
	   		$this->db->where($likearray);
	   return $this->db->where($where)->count_all_results($table);
	}
	
	// GET MAX VALUE OR LAST ID
	function select_maximun($table, $value, $where='')
	{
		$this->db->select_max($value);
		if(strlen($where)){$this->db->where($where);}  
		return $this->db->get($table);
	}
	
	// INSERT RECORD
	function insert($table, $values = array())
	{
		$this->db->insert($table, $values);  /// $values  = array('name'=>'Dinesh', 'surname'=>'Gajjar');
		return $this->db->insert_id();  // return last inserted id
	}
	
	//INSERT MULTIPLE RECORDS	
	function multiple_insert($table,$data)
	{
		$this->db->insert_batch($table, $data); 
		return $this->db->insert_id();
	}
	//multiple update
	function multiple_update($table,$data,$index)
	{
		$this->db->update_batch($table, $data,$index); 
	}
	//UPDATE RECORD
	function update($table, $values, $where='')
	{ 
		if(!empty($where)){$this->db->where($where);}    // array 
		$this->db->update($table,$values);  /// $values  = array('name'=>'Dinesh', 'surname'=>'Gajjar');
		return true;
	}
	
	// DELETE RECORDS
	function delete($table, $where='')
	{ 
		$this->db->delete($table,$where);
		return true;
	}
	
	// GET VALUES WITH "WHERE NOT  IN" 
	function where_not_in($table, $select, $where='', $where_in_key, $where_in_value, $param='')
	{
		$this->db->select($values);
		if(strlen($where)){$this->db->where($where);}    // array 
		$this->db->where_not_in($where_in_key, $where_in_value);
		return $this->db->get($table);
	}
	// GET VALUES WITH "WHERE IN" 
	function where_in($table, $select, $where='', $where_in_key, $where_in_value,$limit='',$orderby='')
	{
		$this->db->select($select);
		if($where!=''){$this->db->where($where);}    // array 
		if($limit!='')
			$this->db->limit($limit);
		if(strlen($orderby)){$this->db->order_by($orderby);}  	
		$this->db->where_in($where_in_key, $where_in_value);
		return $this->db->get($table);
	}
	
	// listing with join function
	public function get_joins($table,$value,$joins,$where='',$order_by,$order,$limit='',$offset=0,$distinct='',$likearray='',$where_in='',$wherincoumn='',$groupby='', $total='')
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
		if($where!='')
		$this->db->where($where);
		if($likearray!='')
			$this->db->where($likearray);
		if($distinct!=='')
			$this->db->distinct();
		if(!empty($where_in))	 
			$this->db->where_in($wherincoumn, $where_in);
		if($groupby!==''){	
			$this->db->group_by($groupby); 	
		}
		$this->db->stop_cache();	
		if($total==''){
			if(strlen($limit))
			 $this->db->limit($limit,$offset);
			$this->db->flush_cache();
			return $this->db->get($table);
		}
		else
		{
			$query['total_records']=$this->db->get($table)->num_rows();
			if(strlen($limit))
			 $this->db->limit($limit,$offset);
			$query['results']=$this->db->get($table)->result();
			$this->db->flush_cache();
			return $query;	
		}
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
	
	// GET PASSWORD SALT VALUE - md5 encrypted
	function create_pwd_salt($length = '3')
	{
		$string = md5(uniqid(rand(), true));
		return substr($string, 0, $length);
    }
	
	//GET IP AND MYSQL CURRENT DATE
	function get_date_ip()
	{
		return $this->db->query('SELECT INET_ATON("'.$this->input->ip_address().'") AS ip,  NOW() AS cur_date ')->row();
    }
	
	/*ENCRYPT ID*/
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
	function filterOutput($string){  //Kishan
	
		if(is_object($string)){ 
			foreach($string as $key => $val) { 
				$string->$key =trim(htmlspecialchars(stripslashes($val)));
			}
		} else {
			$string=trim(htmlspecialchars(stripslashes($string)));
		}
		return 	$string;
	}
	function replace($table,$value){ //Kishan for insert/replace depends on key
		$this->db->replace($table, $value);
		return $this->db->insert_id();
	}

	function clean_session($param=''){
		//$this->session->sess_destroy();
		$array_items = array('username'=>'', 'userid'=>'', 'user_logged_in'=>FALSE, 'email'=>'');
		$this->session->unset_userdata($array_items);
		$this->session->sess_destroy();
	}

	function generateToken($length = 40) {
        //fallback to mt_rand if php < 5.3 or no openssl available
        $characters = '0123456789';
        $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
        $charactersLength = strlen($characters)-1;
        $password = '';

        //select some random characters
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[mt_rand(0, $charactersLength)];
        }        
        return $password;
	}

	function check_record_exist($table,$select_value,$array)
	{
		$query=$this->db->select($select_value);
		$query=$this->db->limit(1);
		$query=$this->db->get_where($table,$array);
		return $query->row_array();	
	}
	
	//csrf
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
	///___________________ csrf ends_____________________ ////////
	function base64($val)
	{
		return rtrim(strtr(base64_encode($val), '+/', '-_'), '=');
	}
	function base64decode($val)
	{
		return base64_decode(str_pad(strtr($val, '-_', '+/'), strlen($val) % 4, '=', STR_PAD_RIGHT));
	}
	function show_date($date){ 
		setlocale(LC_TIME, 'es_ES'); // substitute your locale if not es_ES
		return  strftime("%d %b, %Y", strtotime($date)); 
	}
	function show_date_time($date){ 
		setlocale(LC_TIME, 'es_ES'); // substitute your locale if not es_ES
		return  strftime("%d %b, %Y %H:%i", strtotime($date)); 
	}


	function generateRandomKey($range=10)
	{
		$chars = "0123456789";
		srand((double)microtime()*1000000);
		$i = 0;
		$pass = '' ;
		while ($i <= $range) {
			$num = rand() % $range;
			$tmp = substr($chars, $num, 1);
			$pass = $pass . $tmp;
			$i++;
		}
		return $pass;
	}

	function get_auth_key($id){
		$r = md5($id.''.AUTH_KEY_CREATE);
		return $r;
	}

	function php_curl($url, $post_data){
		$curl = curl_init();
		$opts = array(
		    CURLOPT_URL             => $url,
		    CURLOPT_RETURNTRANSFER  => 1,
		    CURLOPT_CUSTOMREQUEST   => 'POST',
		    CURLOPT_POST            => 1,
		//	CURLOPT_HTTPHEADER		=> '',
			CURLOPT_POSTFIELDS		=> $post_data,
		);

		// Set curl options
		curl_setopt_array($curl, $opts);
		// Get the results
		$result = curl_exec($curl);
		// Close resource
		curl_close($curl);
		return $result;
	}

	public function setcookie($name, $value){ // KISHAN
        $cookie = array(
          'name'   => $name,
          'value'  => $value,
          'expire' => 2147483647 - time(),
          );
        $this->input->set_cookie($cookie); 
        return $value;
    }
    public function get_userid(){ // KISHAN
    	if($this->session->userdata('userid')!=''){
    		$uid = $this->session->userdata('userid');
    	}else{
    		$uid = '';
    		$pwd = $this->input->cookie('uuid', true);
    		if($pwd!=''){
    			$get_res = $this->get_all_records('users','id',array('password' => $pwd, 'status' => 'Pending'),'',1)->row();
    			if(!empty($get_res)){
    				if(!empty($get_res))
    					$uid = $get_res->id;		
    			}else{
    				return false;
    			}
    		}else{
    			return false;
    		}
    	}
//    	_pre('fsdf'.$uid);
    	return $uid;
    }

}
