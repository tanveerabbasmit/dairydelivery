<?php


class item_list_data
{
    public static function get_item_List(){

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="select * from item as t
                 where t.company_id ='$company_id'";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

        return json_encode($queryResult);
    }
    public static function get_item_array(){

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="select * from item as t
                 where t.company_id ='$company_id'";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

        return $queryResult;
    }

}