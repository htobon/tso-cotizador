<?php
namespace utils;


/**
 * Description of Post
 *
 * @author hftobon
 */
class Post {
    static function getVar($variable) {
        if(self::existe($variable)) {
            return filter_input(INPUT_POST, $variable, FILTER_SANITIZE_STRING);
        } else {
            return null;
        }
    }
    
    static function getVarAsURL($variable) {
        if(self::existe($variable)) {
            return filter_input(INPUT_POST, $variable, FILTER_SANITIZE_URL);
        } else {
            return null;
        }
    }
    
    static function getArray($variable) {
        if(self::existe($variable)) {
            return filter_input_array(INPUT_POST, $variable);
        } else {
            return null;
        }
    }
    
    static function existe($variable) {
        return filter_has_var(INPUT_POST, $variable);
    }
}
