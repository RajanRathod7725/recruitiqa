<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Dynamically add JS files to footer page
if(!function_exists('check_image')){
    function check_image($name,$dir='uploads/default',$size='thumb')
    {

        if(file_exists(APPPATH."../".$dir."/".$size."/".$name) && is_file(APPPATH."../".$dir."/".$size."/".$name)){
            return (site_url().$dir."/".$size."/".$name);
        }
        elseif (file_exists(APPPATH."../".$dir."/".$size."/default.png") && is_file(APPPATH."../".$dir."/".$size."/default.png")){
            return (site_url().$dir."/".$size."/default.png");
        }
        else{
            if($size != 'thumb')
                return (site_url()."uploads/default/default_big.png");
            else
                return (site_url()."uploads/default/default.png");
        }
    }
}
/* ./application/helpers/other_helper.php */
?>
