<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Setting_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();      
		$site_setting = array();
        $CI = &get_instance();
		$this->load->driver('session');
		//setting model data
		if ($this->config->item("useDatabaseConfig")) 
		{
			$this->db->select("fieldName,keytext,value");
            $pr = $this->db->get("setting")->result(); 
			foreach($pr as $setting)
			{
				$site_setting[$setting->keytext]=addslashes($setting->value);
			}			
        }
		else
        {
            $site_setting = (object) $CI->config->config;
        }           
        $CI->site_setting = (object) $site_setting;      
    } 
	
}
