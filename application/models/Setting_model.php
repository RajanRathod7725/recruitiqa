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
			$this->db->select("setting_fieldname,setting_keytext,setting_value,setting_type");
            $pr = $this->db->get("setting")->result(); 
			foreach($pr as $setting)
			{
				$site_setting[$setting->setting_keytext]=addslashes($setting->setting_value);
				
			}    
        }
		else
        {
            $site_setting = (object) $CI->config->config;
        }		
           
        $CI->site_setting = (object) $site_setting;

		/*category and top offer*/
        /*$this->db->select('category_id,category_name,parent_category_id,category_slug,category_image,category_tag');
        $this->db->where(array('status'=>'1'));
        $this->db->order_by("category_order", "asc");
        $CI->main_category = $this->db->get('categories')->result();


        $this->db->select('home_top_slider_title,home_top_slider_btn,home_top_slider_link,home_top_slider_class');
        $this->db->where(array('status'=>'1'));
        $this->db->order_by("display_order", "asc");
        $CI->home_top_slider = $this->db->get('home_top_slider')->result();*/
    } 	
}
