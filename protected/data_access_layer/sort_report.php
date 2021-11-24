<?php

class sort_report
{
    public static function sort_type($type){
         $result ='ASC';
         if($type==2){
             $result ='DESC';
         }
         return $result;
    }
    public static function get_sort_by_icone($type){
        $result ='ASC';
        if($type=='up'){
            $result ='DESC';
        }
        return $result;
    }
}