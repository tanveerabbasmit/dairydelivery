<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 4/24/2017
 * Time: 8:03 PM
 */
class customerListData
{
     public static function getcompanyLimitcustomerList($checkAll=null , $todaydate){

         $company_id = Yii::app()->user->getState('company_branch_id');

        $limitAmount = Company::model()->findByPK(intval($company_id));

         $findLimitamount = $limitAmount['limit_amount'];



       /* $clientQuery ="SELECT c.* ,cb.name as company_branch_name , z.name as zone_name  from client as c
            LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
            LEFT JOIN zone  as z ON z.zone_id = c.zone_id
            LIMIT 10 OFFSET $offset";*/

         if($checkAll){
             $clientQuery ="SELECT c.* ,cb.name as company_branch_name , z.name as zone_name  from client as c
            LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
            LEFT JOIN zone  as z ON z.zone_id = c.zone_id 
             where c.company_branch_id = $company_id";
         }else{
             $clientQuery  = "select c.* , z.name as zone_name from payment_master pm
                left join client as c ON c.client_id = pm.client_id
                LEFT JOIN zone  as z ON z.zone_id = c.zone_id 
                where pm.date >= '$todaydate' and  c.company_branch_id = $company_id ";
         }


        $clientList = Yii::app()->db->createCommand($clientQuery)->queryAll();

        $requiredClientList = array();
        foreach($clientList as $value){
            $list = array();
            $clientId =  $value['client_id'];
             $banlanceAmount = APIData::calculateFinalBalance($clientId);
            if($checkAll){
                $list['fullname'] = $value['fullname'];
                $list['cell_no_1'] = $value['cell_no_1'];
                $list['address'] = $value['address'];
                $list['zone_name'] = $value['zone_name'];
                $list['area'] = $value['area'];
                $list['cnic'] = $value['cnic'];
                $list['banlanceAmount'] = $banlanceAmount;
                $requiredClientList[] = $list ;
            }else{
                if($banlanceAmount >$findLimitamount){

                    $list['fullname'] = $value['fullname'];
                    $list['cell_no_1'] = $value['cell_no_1'];
                    $list['address'] = $value['address'];
                    $list['zone_name'] = $value['zone_name'];
                    $list['area'] = $value['area'];
                    $list['cnic'] = $value['cnic'];
                    $list['banlanceAmount'] = $banlanceAmount;
                    $requiredClientList[] = $list ;
                }
            }


        }

        return json_encode($requiredClientList);
     }
     public static function getcompanyLimitcustomerList_all($checkAll=null , $todaydate){

         $company_id = Yii::app()->user->getState('company_branch_id');

        $limitAmount = Company::model()->findByPK(intval($company_id));

         $findLimitamount = $limitAmount['limit_amount'];



       /* $clientQuery ="SELECT c.* ,cb.name as company_branch_name , z.name as zone_name  from client as c
            LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
            LEFT JOIN zone  as z ON z.zone_id = c.zone_id
            LIMIT 10 OFFSET $offset";*/

             $clientQuery ="SELECT c.* ,cb.name as company_branch_name , z.name as zone_name  from client as c
            LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
            LEFT JOIN zone  as z ON z.zone_id = c.zone_id 
             where c.company_branch_id = $company_id";


        $clientList = Yii::app()->db->createCommand($clientQuery)->queryAll();

        $requiredClientList = array();
        foreach($clientList as $value){
            $list = array();
            $clientId =  $value['client_id'];
             $banlanceAmount = APIData::calculateFinalBalance($clientId);
            if($checkAll){
                $list['fullname'] = $value['fullname'];
                $list['cell_no_1'] = $value['cell_no_1'];
                $list['address'] = $value['address'];
                $list['zone_name'] = $value['zone_name'];
                $list['area'] = $value['area'];
                $list['cnic'] = $value['cnic'];
                $list['banlanceAmount'] = $banlanceAmount;
                $requiredClientList[] = $list ;
            }else{
                if($banlanceAmount >$findLimitamount){

                    $list['fullname'] = $value['fullname'];
                    $list['cell_no_1'] = $value['cell_no_1'];
                    $list['address'] = $value['address'];
                    $list['zone_name'] = $value['zone_name'];
                    $list['area'] = $value['area'];
                    $list['cnic'] = $value['cnic'];
                    $list['banlanceAmount'] = $banlanceAmount;
                    $requiredClientList[] = $list ;
                }
            }


        }

        return json_encode($requiredClientList);
     }

     public static function getCommpanyLimit(){

         $company_id = Yii::app()->user->getState('company_branch_id');

         $limitAmount = Company::model()->findByPK(intval($company_id));

         $findLimitamount = $limitAmount['limit_amount'];

         return $findLimitamount;

     }
}