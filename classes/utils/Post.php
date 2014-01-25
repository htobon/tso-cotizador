<?php
namespace utils;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Post
 *
 * @author hftobon
 */
class Post {
    static function getVar($variable) {
        if(self::existe($variable)) {
            filter_input(INPUT_POST, $variable);
        } else {
            return null;
        }
    }
    
    static function getArray($variable) {
        if(self::existe($variable)) {
            filter_input_array(INPUT_POST, $variable);
        } else {
            return null;
        }
    }
    
    static function existe($variable) {
        return filter_has_var(INPUT_POST, $variable);
    }
}
