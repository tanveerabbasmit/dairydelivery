<?php


class vendor_list_data
{
    public static function get_vendor_list_all_type(){
        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="select * from vendor as a
                     where a.company_id =$company_id 
                     order by a.vendor_name ASC ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();




        return json_encode($queryResult);
    }

    public static function get_employee_list_all_type_with_array(){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="select * from employee as a
                     where a.company_id =$company_id 
                     order by a.employee_name ASC ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();




        return $queryResult;
    }
    public static function get_other_income_source_with_array(){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="select * from other_income_source as a
                     where a.company_id =$company_id 
                     order by a.other_income_source_name ASC ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();





        return $queryResult;
    }
    public static function get_employee_list_all_type(){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="select * from employee as a
                     where a.company_id =$company_id 
                     order by a.employee_name ASC ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();




        return json_encode($queryResult);
    }
    public static function get_other_source_list(){

        $company_id = Yii::app()->user->getState('company_branch_id');


        $query="select * from other_income_source as a
                     where a.company_id =$company_id 
                     order by a.other_income_source_name ASC ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();




        return json_encode($queryResult);
    }

    public static function get_vendor_type_list($vendor_type){
        $company_id = Yii::app()->user->getState('company_branch_id');

        $result = [];
        foreach ($vendor_type as $value){
           $vendor_type_id = $value['vendor_type_id'];

            $query="select * from vendor as a
                     where a.company_id =$company_id 
                     and a.is_active = '1'
                     and  a.vendor_type_id ='$vendor_type_id'
                     order by a.vendor_name ASC ";

            $query_result =  Yii::app()->db->createCommand($query)->queryAll();

            $result[$vendor_type_id] =$query_result;

        }


       return $result;


    }
    public static function get_vendor_list(){
        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="select * from vendor as a
                     where a.company_id =$company_id and a.is_active = '1'
                     order by a.vendor_name ASC ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();




        return json_encode($queryResult);
    }
    public static function get_vendor_list_all(){
        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="select * from vendor as a
                     where a.company_id =$company_id and a.is_active = '1'
                     order by a.vendor_name ASC ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();




        return $queryResult;
    }

}