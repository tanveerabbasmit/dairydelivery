<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class deliveryDurationData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

       public static function dateRangeDeliveryTime($data){
           $company_id = Yii::app()->user->getState('company_branch_id');

            $rider_id =$data['RiderID'];
           $startDate = $data['startDate'];
           $endDate = $data['endDate'];
           $x= strtotime($startDate);
           $y= strtotime($endDate);
             $finalResult = array();
           while($x < ($y+8640)) {
               $oneDayData = array();
               $selectDate = date("Y-m-d", $x);

               $oneDayData['date'] = $selectDate;
               $x += 86400;
               $startDeliveryQuery = "SELECT d.time FROM delivery AS d
                WHERE d.rider_id ='$rider_id' AND d.DATE ='$selectDate'
                GROUP BY d.TIME ASC LIMIT 1 ";

                $startDeliveryResult =  Yii::app()->db->createCommand($startDeliveryQuery)->queryScalar();

               $oneDayData['startDelivery'] = $startDeliveryResult;

               $lastDeliveryQuery = "SELECT d.time FROM delivery AS d
                WHERE d.rider_id ='$rider_id' AND d.DATE ='$selectDate'
                GROUP BY d.TIME DESC LIMIT 1 ";
               $lastDeliveryResult =  Yii::app()->db->createCommand($lastDeliveryQuery)->queryScalar();

               $oneDayData['lastDelivery'] = $lastDeliveryResult;

               if($startDeliveryResult){
                   $finalResult[]=$oneDayData;
               }


           }
           echo json_encode($finalResult);
       }
       public static function dateRangeLastDeliveryTime_chart($data){
           $company_id = Yii::app()->user->getState('company_branch_id');


           $startDate = $data['startDate'];
           $endDate = $data['endDate'];

           $finalResult = array();

           $queryRider ="SELECT r.fullname , r.rider_id  FROM delivery  AS d
            LEFT JOIN  rider AS r ON d.rider_id = r.rider_id
            WHERE d.company_branch_id = '$company_id' and d.DATE BETWEEN '$startDate' and '$endDate'
            GROUP BY d.rider_id ";
           $rider_result =  Yii::app()->db->createCommand($queryRider)->queryAll();
           $oneObject =array();
           array_push($oneObject,'hour');
            foreach ($rider_result as $value){
               $rider_name = $value['fullname'];
                array_push($oneObject,$rider_name);
            }
            array_push($finalResult , $oneObject);
           $oneObject =array();
           array_push($oneObject,2010);



           $x= strtotime($startDate);
           $y= strtotime($endDate);

           while($x < ($y+8640)) {


               $oneDayData = array();
                $selectDate = date("Y-m-d", $x);


               $oneDayData['date'] = $selectDate;
               $x += 86400;
               $oneObject =array();
               array_push($oneObject,$selectDate);
               foreach ($rider_result as $value){
                   $rider_name = $value['fullname'];
                   $rider_id = $value['rider_id'];
                   $lastDeliveryQuery = "SELECT d.time FROM delivery AS d
                    WHERE d.rider_id ='$rider_id' AND d.DATE ='$selectDate'
                    GROUP BY d.TIME DESC LIMIT 1 ";

                  $lastDeliveryResult =  Yii::app()->db->createCommand($lastDeliveryQuery)->queryScalar();


                  if($lastDeliveryResult){
                      $time_impload = explode(':',$lastDeliveryResult);

                        $hour = $time_impload[0];
                        $mint = $time_impload[1];
                        $x_ratio = (60)/$mint;
                         $hundrenPart = (int)(100/$x_ratio);
                         $time =$hour.'.'.$hundrenPart;

                         array_push($oneObject,intval($time));
                  }else{
                      array_push($oneObject,intval(0));
                  }





               }

               array_push($finalResult , $oneObject);

           }
           echo json_encode($finalResult);
       }



}