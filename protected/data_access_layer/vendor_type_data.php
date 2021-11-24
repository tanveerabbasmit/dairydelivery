<?php


class vendor_type_data
{
    public static function get_vendor_type_list(){
        $result =[
             [
                 'vendor_type_id'=>1,
                  'vendor_type_name'=>'Vendor'
             ],
            [
                'vendor_type_id'=>2,
                'vendor_type_name'=>'Employee'
            ]

        ];
        return $result;
    }
}