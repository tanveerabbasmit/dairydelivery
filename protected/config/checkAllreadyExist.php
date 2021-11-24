<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 7/18/2017
 * Time: 5:42 PM
 */
class checkAllreadyExist
{

    public static function checkAlredyExistClientFunction($data){
         $userName= $data['userName'];
         if(isset($data['client_id'])){}
         $clientid= $data['client_id'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $result = false ;
        if($userName){

            $query = " select count(*) as totalCount from client as c
                where c.userName = '$userName' and c.company_branch_id = '$company_id' and c.client_id != '$clientid' ";
            $Result =Yii::app()->db->createCommand($query)->queryAll();
               $resultCount = $Result[0]['totalCount'];
            if($resultCount != 0){
                $result = true;
             }
             
        }
        return $result ;
    }

}