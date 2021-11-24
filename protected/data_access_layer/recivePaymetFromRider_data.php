<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 11/20/2017
 * Time: 11:39 AM
 */
class recivePaymetFromRider_data
{
  public static function get_payment_from_rider($today_date){

      $company_id = Yii::app()->user->getState('company_branch_id');

      $query_riderList="SELECT r.* , cb.name as company_branch_name from rider as r
               LEFT JOIN company_branch as cb ON cb.company_branch_id = r.company_branch_id
               where r.company_branch_id = $company_id
                order by r.fullname ASC ";

      $result_riderList =  Yii::app()->db->createCommand($query_riderList)->queryAll();
         $final_result = array();
        foreach ($result_riderList as $riderValue){
          $RiderID = $riderValue['rider_id'];


                  $oneObject = array();
            $oneObject['rider_id'] = $RiderID;
            $oneObject['fullname'] = $riderValue['fullname'];

            $clientQuery = "Select c.client_id from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           where rz.rider_id = $RiderID  ";

            $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

            $cientID = array();
            $cientID[] = 0;
            foreach($clientResult as $value){
                $cientID[] =  $value['client_id'];
            }
              $client_id = implode(',',$cientID);


            $paid_query = " select IFNULL(sum(pm.amount_paid),0) as totalCsh  from payment_master as pm
               where pm.remarks = 1  and  pm.date = '$today_date' and pm.client_id in ($client_id) ";


            $paid_reslult =  Yii::app()->db->createCommand($paid_query)->queryAll();

              if($paid_reslult){

                  $oneObject['recive_amount'] =  $paid_reslult[0]['totalCsh'];
              }else{
                  $oneObject['recive_amount'] = 0;
              }

            $submit_payment_query = "SELECT ifnull(sum(a.submit_amount) ,0) as submit_amount FROM recive_payment_by_admin as a
                            where a.rider_id = '$RiderID' and a.date = '$today_date'";

            $submit_payment_result =  Yii::app()->db->createCommand($submit_payment_query)->queryAll();

            if($submit_payment_result){

                $oneObject['submit_amount'] =  $submit_payment_result[0]['submit_amount'];
            }else{
                $oneObject['submit_amount'] = 0;
            }
            $oneObject['update_mode'] = false;
            $oneObject['pick_by_admin'] = '';
            $oneObject['color'] = '';
            $oneObject['balance'] = $oneObject['recive_amount']-  $oneObject['submit_amount'];
            if($oneObject['balance'] > 0){
                $oneObject['color'] = 'red';
            }

            $final_result[] = $oneObject ;
        }

      return json_encode($final_result);

  }
}