<?php


class customer_source
{
    public static function getCustomer_source_list(){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="select * from customer_source as t
                 where t.company_id ='$company_id'";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

        return json_encode($queryResult);
    }
}