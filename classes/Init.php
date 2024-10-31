<?php
/**
* @package semplisio
*/


namespace Semplisio;


class Init {
    public static $PLUGIN;
    public static $PLUGIN_DIR;
    public static $PLUGIN_URL;
    public static $API_URL = 'https://api.semplisio.it';
    public static $STATIC_URL;
    public static $strings = null;
    
    private static $classes = array(
        Admin::class
    );


    public static function _t($str){
        echo wp_kses(Init::_tne($str), wp_kses_allowed_html(  ));
    }
    
    public static function _tne($str){
        if(isset(Init::$strings->{$str})){	
            return Init::$strings->{$str};
        } else {
            return $str;
        }
    }
     

    public static function register(){
        $tmp = file_get_contents(Init::$STATIC_URL.'string.json');
        Init::$strings = json_decode($tmp);
        $tmp = null;
        foreach(self::$classes as $clazz){
            $c = new $clazz();
            $c->register();
        }
    }

    public static function unregister(){
        foreach(self::$classes as $clazz){
            $c = new $clazz();
            $c->unregister();
        }
    }
}