<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//Dynamically add Javascript files to header page
if(!function_exists('add_js_header')){
    function add_js_header($file='')
    {
        $str = '';
        $ci = &get_instance();
        $header_js  = $ci->config->item('header_js');

        if(empty($file)){
            return;
        }
        if(is_array($file)){
            if(!is_array($file) && count($file) <= 0){
                return;
            }
            foreach($file AS $item){
                $header_js[] = $item;
            }
            $ci->config->set_item('header_js',$header_js);
        }else{
            $str = $file;
            $header_js[] = $str;
            $ci->config->set_item('header_js',$header_js);
        }
    }
}

if(!function_exists('add_js_footer')){
    function add_js_footer($file='')
    {
        $str = '';
        $ci = &get_instance();
        $footer_js  = $ci->config->item('footer_js');

        if(empty($file)){
            return;
        }
        if(is_array($file)){
            if(!is_array($file) && count($file) <= 0){
                return;
            }
            foreach($file AS $item){
                $footer_js[] = $item;
            }
            $ci->config->set_item('footer_js',$footer_js);
        }else{
            $str = $file;
            $footer_js[] = $str;
            $ci->config->set_item('footer_js',$footer_js);
        }
    }
}

if(!function_exists('add_css_page')){
    function add_css_page($file='')
    {
        $str = '';
        $ci = &get_instance();
        $header_css = $ci->config->item('header_css');

        if(empty($file)){
            return;
        }

        if(is_array($file)){
            if(!is_array($file) && count($file) <= 0){
                return;
            }
            foreach($file AS $item){
                $header_css[] = $item;
            }
            $ci->config->set_item('header_css',$header_css);
        }else{
            $str = $file;
            $header_css[] = $str;
            $ci->config->set_item('header_css',$header_css);
        }
    }
}
//Dynamically add CSS and JS files to header page
if(!function_exists('put_headers')){
    function put_headers()
    {
        $str = '';
        $ci = &get_instance();
        $all_css_with_url  = $ci->config->item('all_css_with_url');
        $all_js_with_url  = $ci->config->item('all_js_with_url');
        $header_css = $ci->config->item('header_css');
        $header_js  = $ci->config->item('header_js');

        foreach($header_css AS $item){
            if(key_exists($item,$all_css_with_url)){
                if(strpos(strtolower($all_css_with_url[$item]),'http') === 0 ){
                    $str .= '<link rel="stylesheet" href="'.$all_css_with_url[$item].'" type="text/css" />'."\n";
                }else{
                    $str .= '<link rel="stylesheet" href="'.site_url($all_css_with_url[$item]).'" type="text/css" />'."\n";
                }
            }
            else{
                $str .= '<link rel="stylesheet" href="'.$item.'" type="text/css" />'."\n";
            }
        }
        $str.= "<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src=\"https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js\"></script>
            <script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
        <![endif]-->";
        foreach($header_js AS $item){
            if(key_exists($item,$all_js_with_url)){
                if(strpos(strtolower($all_js_with_url[$item]),'http') === 0){
                    $str .= '<script type="text/javascript" src="'.$all_js_with_url[$item].'"></script>'."\n";
                }else{
                    $str .= '<script type="text/javascript" src="'.site_url($all_js_with_url[$item]).'"></script>'."\n";
                }
            }
            else{
                $str .= '<script type="text/javascript" src="'.$item.'"></script>'."\n";
            }
        }
        return $str;
    }
}
//Dynamically add JS files to footer page
if(!function_exists('put_footer')){
    function put_footer()
    {
        $str = '';
        $ci = &get_instance();
        $all_js_with_url  = $ci->config->item('all_js_with_url');
        $footer_js  = $ci->config->item('footer_js');
        foreach($footer_js AS $item){
            if(key_exists($item,$all_js_with_url)){
                if(strpos(strtolower($all_js_with_url[$item]),'http') === 0){
                    $str .= '<script type="text/javascript" src="'.$all_js_with_url[$item].'"></script>'."\n";
                }else{
                    $str .= '<script type="text/javascript" src="'.site_url($all_js_with_url[$item]).'"></script>'."\n";
                }
            }
            else{
                $str .= '<script type="text/javascript" src="'.$item.'"></script>'."\n";
            }
        }
        return $str;
    }
}
/* ./application/helpers/css_js_helper.php */
?>
