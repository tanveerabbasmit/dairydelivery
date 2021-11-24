<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 9/6/2017
 * Time: 5:44 PM
 */
class monthlyReport_data
{
  public static function getMonthlyReport($data){



      $check_today = date("Y-m-d");
       if($data){
         //  $selectDate = $data['selectMonth'];
          // $slectDate = explode(" " , $selectDate);
           $RiderId = $data['riderId'];
           //  $monthString = $slectDate[0];
           $month =   $data['selectMonth'];
           $year = $data['selectYear'];
           $client_type = $data['client_type'];
           $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $page = $data['page'];
           $page = $page - 1;
           $offset = $page * 20;
       }else{
           $client_type = '0';
           $month = date("m");
           $year = date("Y");
           $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
           $today = date("Y-d-m");
           $RiderId = '0';
           $offset = 0;
       }
      $company_id = Yii::app()->user->getState('company_branch_id');
      $zoneList_query = "select rz.zone_id from rider_zone as rz
                   where rz.rider_id = $RiderId ";
      $zoneList_result = Yii::app()->db->createCommand($zoneList_query)->queryAll();
      $zone_id = array();
      $zone_id[] = 0;
      foreach($zoneList_result as $value){
          $zone_id[] = $value['zone_id'];
      }
      $imploadZoneID = implode(',' , $zone_id);


     /* $clientQuery = "Select   c.client_id from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id
                           where c.company_branch_id =$company_id ";
      if($RiderId != '0'){
          $clientQuery .=" and rz.rider_id = $RiderId ";
      }
      $clientQuery .="  group by c.client_id LIMIT 10 OFFSET $offset";*/

      $clientQuery_count = " select count(*) as totalClient from client as c
                        where c.company_branch_id =$company_id and c.is_active=1 and c.client_type=1 ";
      if($RiderId != '0'){
          $clientQuery_count .=" and c.zone_id in ($imploadZoneID)";
      }

      $clientQuery_count_result = Yii::app()->db->createCommand($clientQuery_count)->queryScalar();

      $clientQuery = "select c.client_id , c.fullname , z.name as zone_name , c.zone_id ,c.address , c.cell_no_1 from client as c
                      left join zone as z ON z.zone_id = c.zone_id 
                      where c.company_branch_id =$company_id and c.is_active=1  ";
      if($client_type != '0'){
          $clientQuery .=" and c.client_type='$client_type'  ";
      }
      if($RiderId != '0'){
          $clientQuery .=" and c.zone_id in ($imploadZoneID)";
      }
      $clientQuery .="  group by c.client_id order by c.fullname LIMIT 20 OFFSET $offset";

      $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

      $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);

      $query_product = "SELECT p.product_id , p.name  from   product as p 
                where p.company_branch_id =$company_id and p.bottle=0";
      $productList =  Yii::app()->db->createCommand($query_product)->queryAll();

      $all_month_recored = array();

      $final_total_Count_month_report = array();

      foreach ($clientResult as $value){
          foreach($productList as $productValue){
              $one_customer_record = array();
              $one_day_recored = array();
              $one_day_recored['client_id'] = $value['client_id'];
              $one_day_recored['fullname'] = $value['fullname'];
              $one_day_recored['zone_name'] = $value['zone_name'];
              $one_day_recored['address'] = $value['address'];
              $one_day_recored['product_name'] = $productValue['name'];

              $one_day_recored['cell_no_1'] = str_replace("+92","\t0", $value['cell_no_1']);
              $one_row_data = array();
                  $one_week_delivery =0;
              for($d=1; $d<=$number; $d++){
                  $time = mktime(12, 0, 0, $month, $d, $year);
                  $today = date('Y-m-d', $time);
                  $todayDay_name = date('D', strtotime($today));
                  $client_id =  $value['client_id'];
                  $product_id = $productValue['product_id'];



                  if($todayDay_name == 'Sun' and $d !=1){
                      $oneObject = array();
                      $oneObject['delivery'] = $one_week_delivery;
                      $oneObject['color'] = '#FF00FF';
                      $one_row_data[] = $oneObject;
                      $one_week_delivery =0;
                  }
                  $query_delivery = " select IFNULL(sum(dd.quantity) ,0) as totalDelivery from delivery as d
                          left join delivery_detail as dd ON d.delivery_id = dd.delivery_id
                       where d.client_id = '$client_id' and dd.product_id = '$product_id' and d.date = '$today' ";
                  $result_delivery = Yii::app()->db->createCommand($query_delivery)->queryAll();
                  $total_delivery_count = $result_delivery[0]['totalDelivery'];
                  $one_week_delivery = intval($one_week_delivery) + intval($total_delivery_count);
                  if($today <= $check_today){
                      $oneObject = array();
                      $oneObject['delivery'] = $total_delivery_count;
                      $oneObject['color'] = '';
                      $one_row_data[] = $oneObject;

                  }else{


                      $totalInterval_quantity =  future_selet_date::getOneCustomerTodayIntervalSceduler_with_date_future_date($client_id,$product_id ,$today);
                      $totalWeekly_quantity  =    todayDeliveryQuantityCountForApi::getTodayDeliveryCountWeeklyRegularAndSpecial($client_id ,$product_id, $today);
                      $totalSpecialToday_quantity  =    todayDeliveryQuantityCountForApi::getTodaySpecialOrder($client_id ,$product_id ,$today);

                      $oneObject = array();
                      $oneObject['delivery'] = $totalInterval_quantity + $totalWeekly_quantity +$totalSpecialToday_quantity;
                      $oneObject['color'] = '#0000FF';
                      $one_row_data[] = $oneObject;
                  }


              }
              $one_day_recored['row_data'] =$one_row_data ;
              $all_month_recored[] = $one_day_recored ;
         }
      }

         $month_lable_list = array();
      for($d=1; $d<=$number; $d++) {
          $time = mktime(12, 0, 0, $month, $d, $year);
           $oneDayArray = array();
          $today = date('Y-m-d', $time);
           $todayDay_name = date('D', strtotime($today));
           $todayDate_name = date('M', strtotime($today));
          if($todayDay_name == 'Sun' and $d !=1){
              $oneDayArray['day_name'] = 'Total' ;
              $oneDayArray['day_Date'] = '' ;
              $month_lable_list[] = $oneDayArray;
              $oneDayArray = array();
          }
          $oneDayArray['day_name'] = $todayDay_name ;
          $oneDayArray['day_Date'] = $todayDate_name." ".$d ;
          $month_lable_list[] = $oneDayArray;
      }

       $finalResult_data = array();
       $finalResult_data['lable'] = $month_lable_list;
       $finalResult_data['recordCount'] = $clientQuery_count_result;
       $finalResult_data['data'] = $all_month_recored;


       return json_encode($finalResult_data);
  }
  public static function MonthlyReportExport($data){

      $check_today = date("Y-m-d");
       if($data){

         //  $selectDate = $data['selectMonth'];
          // $slectDate = explode(" " , $selectDate);
           $RiderId = $data['riderID'];
           //  $monthString = $slectDate[0];
           $month =   $data['month'];
           $year = $data['year'];
           $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);



       }else{

           $month = date("m");
           $year = date("Y");
           $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
           $today = date("Y-d-m");
           $RiderId = '0';
           $offset = 0;


       }
      $company_id = Yii::app()->user->getState('company_branch_id');
      $zoneList_query = "select rz.zone_id from rider_zone as rz
                   where rz.rider_id = $RiderId ";
      $zoneList_result = Yii::app()->db->createCommand($zoneList_query)->queryAll();
      $zone_id = array();
      $zone_id[] = 0;
      foreach($zoneList_result as $value){
          $zone_id[] = $value['zone_id'];
      }
      $imploadZoneID = implode(',' , $zone_id);


     /* $clientQuery = "Select   c.client_id from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id
                           where c.company_branch_id =$company_id ";
      if($RiderId != '0'){
          $clientQuery .=" and rz.rider_id = $RiderId ";
      }
      $clientQuery .="  group by c.client_id LIMIT 10 OFFSET $offset";*/

      $clientQuery_count = " select count(*) as totalClient from client as c
                        where c.company_branch_id =$company_id ";
      if($RiderId != '0'){
          $clientQuery_count .=" and c.zone_id in ($imploadZoneID)";
      }

      $clientQuery_count_result = Yii::app()->db->createCommand($clientQuery_count)->queryScalar();

      $clientQuery = "select c.client_id , c.fullname , z.name as zone_name , c.zone_id ,c.address , c.cell_no_1 from client as c
                      left join zone as z ON z.zone_id = c.zone_id 
                      where c.company_branch_id =$company_id ";
      if($RiderId != '0'){
          $clientQuery .=" and c.zone_id in ($imploadZoneID)";
      }
      $clientQuery .="  group by c.client_id order by c.fullname ";

      $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

      $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);

      $query_product = "SELECT p.product_id , p.name  from   product as p 
                where p.company_branch_id =$company_id and p.bottle=0";
      $productList =  Yii::app()->db->createCommand($query_product)->queryAll();

      $all_month_recored = array();

      $final_total_Count_month_report = array();

      foreach ($clientResult as $value){
          foreach($productList as $productValue){
              $one_customer_record = array();
              $one_day_recored = array();
              $one_day_recored['client_id'] = $value['client_id'];
              $one_day_recored['fullname'] = $value['fullname'];
              $one_day_recored['zone_name'] = $value['zone_name'];
              $one_day_recored['address'] = $value['address'];
              $one_day_recored['product_name'] = $productValue['name'];
              $one_day_recored['cell_no_1'] = $value['cell_no_1'];
              $one_row_data = array();
                  $one_week_delivery =0;
              for($d=1; $d<=$number; $d++){
                  $time = mktime(12, 0, 0, $month, $d, $year);
                  $today = date('Y-m-d', $time);
                  $todayDay_name = date('D', strtotime($today));
                  $client_id =  $value['client_id'];
                  $product_id = $productValue['product_id'];



                  if($todayDay_name == 'Sun' and $d !=1){
                      $oneObject = array();
                      $oneObject['delivery'] = $one_week_delivery;
                      $oneObject['color'] = 'red';
                      $one_row_data[] = $oneObject;
                      $one_week_delivery =0;
                  }
                  $query_delivery = " select IFNULL(sum(dd.quantity) ,0) as totalDelivery from delivery as d
                          left join delivery_detail as dd ON d.delivery_id = dd.delivery_id
                       where d.client_id = '$client_id' and dd.product_id = '$product_id' and d.date = '$today' ";
                  $result_delivery = Yii::app()->db->createCommand($query_delivery)->queryAll();
                  $total_delivery_count = $result_delivery[0]['totalDelivery'];
                  $one_week_delivery = intval($one_week_delivery) + intval($total_delivery_count);
                  if($today <= $check_today){
                      $oneObject = array();
                      $oneObject['delivery'] = $total_delivery_count;
                      $oneObject['color'] = '';
                      $one_row_data[] = $oneObject;

                  }else{


                      $totalInterval_quantity =  future_selet_date::getOneCustomerTodayIntervalSceduler_with_date_future_date($client_id,$product_id ,$today);
                      $totalWeekly_quantity  =    todayDeliveryQuantityCountForApi::getTodayDeliveryCountWeeklyRegularAndSpecial($client_id ,$product_id, $today);
                      $totalSpecialToday_quantity  =    todayDeliveryQuantityCountForApi::getTodaySpecialOrder($client_id ,$product_id ,$today);

                      $oneObject = array();
                      $oneObject['delivery'] = $totalInterval_quantity + $totalWeekly_quantity +$totalSpecialToday_quantity;
                      $oneObject['color'] = '#0000FF';
                      $one_row_data[] = $oneObject;
                  }


              }
              $one_day_recored['row_data'] =$one_row_data ;
              $all_month_recored[] = $one_day_recored ;
         }
      }

         $month_lable_list = array();
      for($d=1; $d<=$number; $d++) {
          $time = mktime(12, 0, 0, $month, $d, $year);
           $oneDayArray = array();
          $today = date('Y-m-d', $time);
           $todayDay_name = date('D', strtotime($today));
           $todayDate_name = date('M', strtotime($today));
          if($todayDay_name == 'Sun' and $d !=1){
              $oneDayArray['day_name'] = 'Total' ;
              $oneDayArray['day_Date'] = '' ;
              $month_lable_list[] = $oneDayArray;
              $oneDayArray = array();
          }
          $oneDayArray['day_name'] = $todayDay_name ;
          $oneDayArray['day_Date'] = $todayDate_name." ".$d ;
          $month_lable_list[] = $oneDayArray;
      }

       $finalResult_data = array();
       $finalResult_data['lable'] = $month_lable_list;
       $finalResult_data['recordCount'] = $clientQuery_count_result;
       $finalResult_data['data'] = $all_month_recored;


       return ($finalResult_data);
  }



  public static  function getMonthNumber($m){
        /*if($m=='January'){
            return "1";
        }else if($m=='February'){
            return "2";
        }else if($m=='March'){
            return "3";
        }else if($m=='April'){
            return "4";
        }else if($m=='May'){
            return "5";
        }else if($m=='June'){
            return "6";
        }else if($m=='July'){
            return "7";
        }else if($m=='August'){
            return "8";
        }else if($m=='September'){
            return "9";
        }else if($m=='October'){
            return "10";
        }else if($m=='November'){
            return "11";
        }else if($m=='December'){
            return "12";
        }*/


      if($m==1){
          return "January";
      }else if($m==2){
          return "February";
      }else if($m==3){
          return "March";
      }else if($m==4){
          return "April";
      }else if($m==5){
          return "May";
      }else if($m==6){
          return "June";
      }else if($m==7){
          return "July";
      }else if($m==8){
          return "August";
      }else if($m==9){
          return "September";
      }else if($m==10){
          return "October";
      }else if($m==11){
          return "November";
      }else if($m==12){
          return "December";
      }
   }




}