<?php

namespace App\Models;


class Helper {


 public static  function validate_image($file){
        if(!empty($file)){
                // exit;
            $ex = explode('?',$file);
            $file = $ex[0];
            $param =  isset($ex[1]) ? '?'.$ex[1]  : '';
            if(is_file(base_app.$file)){
                return base_url.$file.$param;
            }else{
                return base_url.'dist/img/no-image-available.png';
            }
        }else{
            return base_url.'dist/img/no-image-available.png';
        }
    }
   public static  function isMobileDevice(){
        $aMobileUA = array(
            '/iphone/i' => 'iPhone', 
            '/ipod/i' => 'iPod', 
            '/ipad/i' => 'iPad', 
            '/android/i' => 'Android', 
            '/blackberry/i' => 'BlackBerry', 
            '/webos/i' => 'Mobile'
        );
    
        //Return true if Mobile User Agent is detected
        foreach($aMobileUA as $sMobileKey => $sMobileOS){
            if(preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])){
                return true;
            }
        }
        //Otherwise return false..  
        return false;
    }

}