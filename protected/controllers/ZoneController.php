<?php

class ZoneController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
    public function filters()
    {
        $user_id = Yii::app()->user->getState('user_id');
        if(!$user_id){
            $this->redirect(Yii::app()->user->returnUrl);
        }

        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }


	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */

    public function actioneditZone_zone_list(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        echo $list =zoneData::getZoneList_sort_function($data);
    }
	public function actioneditZone_SaveCommission(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $zone_id = $data['zone_id'];

        $productList =   $data['productList'];
         foreach($productList as $value){

             $product_id = $value['product_id'];
             $commissionObject = ZoneWiseCommission::model()->findByAttributes(array('zone_id'=>$zone_id , 'product_id'=> $product_id));
             if($commissionObject){
                 $commissionObject->amount=$value['amount'];
                 $commissionObject->save();
             }else{
                 $commission = new ZoneWiseCommission();

                 $commission->zone_id=$zone_id ;
                 $commission->product_id=$product_id ;
                 $commission->amount=$value['amount'] ;
                 if($commission->save()){

                 }else{
                     var_dump($commission->getErrors());
                 }
             }


         }


    }

	public function actionriderWiseCommission(){


        $company_id = Yii::app()->user->getState('company_branch_id');


        $query="SELECT p.name ,p.product_id   from   product as p 
                where p.company_branch_id =$company_id and bottle = 0 ";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();


        $query="SELECT z.* from zone as z
                  LEFT JOIN company_branch as cb ON cb.company_branch_id = z.company_branch_id
                 where z.company_branch_id = $company_id
                   order by z.name ASC ";
        $queryResult_zone =  Yii::app()->db->createCommand($query)->queryAll();


        $zoneResult = array();
        foreach ($queryResult_zone as $value){
            $oneObject = array();
            $zone_id = $value['zone_id'];
            $oneObject['zone_id'] = $value['zone_id'];
            $oneObject['zone_name'] = $value['name'];
            $oneObject['updateMode'] = false;

            $query="
                select  p.product_id , p.name ,ifnull(zwc.amount ,0) as amount   from product as p
                left join zone_wise_commission as zwc ON zwc.product_id = p.product_id and zwc.zone_id ='$zone_id'
               where p.company_branch_id ='$company_id'
             ";
            $productList =  Yii::app()->db->createCommand($query)->queryAll();

            $oneObject['productList'] = $productList;
            $zoneResult[] = $oneObject;
        }

        $this->render('zoneWiseCommission' , array(
            'productList'=>json_encode($productList),
            "zoneList"=>json_encode($zoneResult),
            "companyBranchList"=>companyBranchData::getCompanyBranchList(),
        ));

    }
	public function actiongetCommisionReport(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $year = $data['year'];
        $monthNum = $data['monthNum'];

        $RiderID = $data['RiderID'];

        $zone_id = $data['zone_id'];



        $company_id = Yii::app()->user->getState('company_branch_id');

        if( $zone_id >0){

           $clientQuery = "Select z.zone_id ,z.name ,z.commission  from zone as z
                      where z.zone_id ='$zone_id' ";

        }else{

            $clientQuery = "Select z.zone_id ,z.name ,z.commission  from rider_zone as rz
                        left join zone as z ON z.zone_id = rz.zone_id
                        where rz.rider_id ='$RiderID' ";

            $clientQuery = "SELECT 
                d.delivery_id,
                d.client_id,
                d.rider_id,
                c.fullname,
                z.zone_id,
                z.name,
                z.commission
                
                FROM delivery AS d
                LEFT JOIN client AS c ON c.client_id =d.client_id
                LEFT JOIN zone AS z ON z.zone_id = c.zone_id
                WHERE d.rider_id ='$RiderID' 
                and month(d.date) ='$monthNum' 
                and year(d.date)='$year'
                GROUP BY z.zone_id";

        }

        $zoneResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

         $final_result = array();
         $grand_toal     = 0;
         $grand_quantity = 0;

         foreach($zoneResult as $value){
              $oneObject = array();
              $zone_id = $value['zone_id'];

             $oneObject['zone_id'] = $zone_id;
             $oneObject['zone_name'] =$value['name'];
             $oneObject['commission'] =$value['commission'];

             $query_delivery = "select c.zone_id , dd.date ,sum(dd.quantity) as quantity from delivery as d     
                        left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                        left join client as c ON c.client_id = d.client_id
                       where c.zone_id ='$zone_id' and month(dd.date) ='$monthNum' and year(dd.date)='$year'
                       group by dd.date ";

             $result_delivery =  Yii::app()->db->createCommand($query_delivery)->queryAll();
             $one_delivery_object = array();
             $total_quantity = 0;
             foreach ($result_delivery as $value){
                 $quantity = $value['quantity'];
                 $total_quantity = $total_quantity +$quantity ;
             }

             $oneObject['delivery'] = $result_delivery;
             $oneObject['total_quantity'] = $total_quantity;
             $oneObject['total_commission'] = $total_quantity * $oneObject['commission'];
             $grand_toal = $grand_toal + $oneObject['total_commission'];
             $grand_quantity = $grand_quantity + $total_quantity;
             $final_result[] = $oneObject ;


         }

         $data = array();
         $data['report'] = $final_result;
         $data['grand'] = $grand_toal;
         $data['grand_quantity'] = $grand_quantity;

        echo json_encode($data);
        die();

        $client_list = array();
        $client_list[] = 0;
        foreach ($clientResult as $value){
            $client_list[] =$value['client_id'];
        }

       $client_list_ids = implode(",",$client_list);
        $query_delivery = "select zwc.amount, dd.date ,dd.quantity , p.name as product_name from delivery as d
                  left join delivery_detail as dd ON d.delivery_id = dd.delivery_id 
                  left join product as p ON p.product_id = dd.product_id
                   left join client as c on d.client_id = c.client_id
                  left join zone_wise_commission as zwc On 
						zwc.product_id = dd.product_id and zwc.zone_id = c.zone_id
                  where d.client_id in ($client_list_ids) and month(d.date) ='$monthNum' and year(d.date) ='$year'
                  and dd.date is not null ";


        $delivery_Result =  Yii::app()->db->createCommand($query_delivery)->queryAll();

         echo json_encode($delivery_Result);


    }
    public function actioncommissionReport(){
        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $monthNum  = date('m');
        $year = intval(date('Y'));
        $zoneList=zoneData::getZoneList_zoneName();
        $company_id = Yii::app()->user->getState('company_branch_id');
        $this->render('commissionReport',array(
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'company_id'=>$company_id,
            'fiveDayAgo'=>$fiveDayAgo,
            'monthNum'=>$monthNum,
            'year'=>$year,
            'zoneList'=>$zoneList,
        ));

    }
    public function actionmanageZone(){

        Yii::app()->session["view"] = 0;
        $this->render('manageZone' , array(
            "zoneList"=>json_encode([]),
            "companyBranchList"=>companyBranchData::getCompanyBranchList(),
        ));

    }


    public function actionweeklyQuantity(){

        $lientID_list = weeklyQuantityCount::getClientListAgainstCompany();

        $todaydate =  date("Y-m-d");

        $timestamp = strtotime($todaydate);

       $day = date('D', $timestamp);

         $fineNextWeekDate = 0;
        $todayfrequencyID = '';
        if($day == 'Mon'){
            $todayfrequencyID = 1 ;
            $fineNextWeekDate = 0;

        }elseif($day == 'Tue'){
            $todayfrequencyID = 2;
            $fineNextWeekDate = 6;
        }elseif($day == 'Wed'){
            $todayfrequencyID = 3 ;
            $fineNextWeekDate = 5;
        }elseif($day == 'Thu'){
            $todayfrequencyID = 4 ;
            $fineNextWeekDate = 4;
        }elseif($day == 'Fri'){
            $todayfrequencyID = 5 ;
            $fineNextWeekDate = 3;
        }elseif($day == 'Sat'){
            $todayfrequencyID = 6 ;
            $fineNextWeekDate = 2;
        }else{

            $todayfrequencyID = 7 ;
            $fineNextWeekDate = 1;

        }




        $company_id = Yii::app()->user->getState('company_branch_id');
        $productQuery = "select p.product_id ,p.name ,p.unit from product as p
                    where p.company_branch_id = $company_id and p.bottle =0";

        $productLIst =  Yii::app()->db->createCommand($productQuery)->queryAll();
          $resultProductList = array();


        foreach($productLIst as $productValue){
            $product_id = $productValue['product_id'];
            $product_name  = $productValue['name'];
              //  $regularWeeklyCount = weeklyQuantityCount::getWeeklySchedulequantityToday($startWeakDate,$product_id );
              // $regularInterCount = weeklyQuantityCount::getRegularInterQuantity($lientID_list ,$product_id, $startWeakDate  );

              if(isset($_GET['startWeakDate'])){

                  $startWeakDate = $_GET['startWeakDate'];
                  $endtWeakDate = $_GET['endWeakDate'];
                  $selectRiderID = $_GET['selectRiderID'];
                  if($selectRiderID >0){
                      $lientID_list = zoneData::getClintIdsListAgainstRider($selectRiderID);


                  }else{
                      $lientID_list = false;
                  }



             }else{

                //  $startWeakDate =  date('Y-m-d', strtotime($fineNextWeekDate.' day', strtotime($todaydate)));

               //   $endtWeakDate =  date('Y-m-d', strtotime('6 day', strtotime($startWeakDate)));

                 $startWeakDate =  date("Y-m-d");
                  $endtWeakDate = date("Y-m-d");
                  $selectRiderID = '0';
              }



            $start_date_strtotime = strtotime($startWeakDate);

            $end_date_strtotime = strtotime($endtWeakDate);

            $different_time = $end_date_strtotime - $start_date_strtotime;

            $no_of_days_increment = floor($different_time / (60 * 60 * 24));


                 $countTotal = 0;
            while($start_date_strtotime <($end_date_strtotime+8640)){

                  $selectDate = date("Y-m-d", $start_date_strtotime);

                $start_date_strtotime += 86400;

                $totalInterval_quantity =  count_demand_quantity_for_future::getOneCustomerTodayIntervalSceduler_with_date_future_date_for_demand($product_id ,$selectDate,$lientID_list);
                $totalWeekly_quantity =  count_demand_quantity_for_future::getTodayDeliveryCountWeeklyRegularAndSpecial_demandCount($product_id ,$selectDate ,$lientID_list);
                $totalSpecialToday_quantity =  count_demand_quantity_for_future::getTodaySpecialOrder_demandCount($product_id ,$selectDate,$lientID_list);
                $countTotal = $countTotal + $totalInterval_quantity + $totalWeekly_quantity + $totalSpecialToday_quantity ;
              //  $totalWeekly_quantity  =    todayDeliveryQuantityCountForApi::getTodayDeliveryCountWeeklyRegularAndSpecial($product_id, $todaydate);

             //   $one_array_result['regularQuantity'] = $totalInterval_quantity + $totalWeekly_quantity ;

             //   $totalSpecialToday_quantity  =    todayDeliveryQuantityCountForApi::getTodaySpecialOrder($product_id ,$todaydate);
            }


            $oneProductObject = array();
            $oneProductObject['product_name'] =  $product_name;
            $oneProductObject['unit'] =  $productValue['unit'];
            $oneProductObject['quantity'] =  $countTotal ;

            $resultProductList[] = $oneProductObject ;
        }



         $finalData = array();
        $finalData['startWeakDate'] = $startWeakDate;
        $finalData['endWeakDate'] = $endtWeakDate;
        $finalData['selectRiderID'] = $selectRiderID;
        $finalData['product'] = $resultProductList;


        $this->render('weeklyQuantity' , array(
             "data"=>json_encode($finalData),
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),

        ));
    }
    public function actionmonthlyReport(){
        $monthNum  = date('m');
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
       $monthName = $dateObj->format('F'); // March
       $monthName = 'okkk'; // March

       $year = intval(date('Y'));

        $todayDate = date("Y-m-d");


        //'reportData'=>monthlyReport_data::getMonthlyReport($data = null),

       $this->render('monthlyReport' , array(
           'riderList'=>riderData::getRiderList(),
          'reportData'=>json_encode(array()),
           'todayMonth'=>$monthNum,
            'year' =>$year,
             'todayDate'=>$todayDate
       ));
    }
    public function actionmonthlyReconcileStock(){

        $monthNum  = date('m');
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March
        $monthName = 'okkk'; // March
        $year = intval(date('Y'));

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT p.product_id ,p.name ,p.unit  from   product as p 
                where p.company_branch_id =$company_id and bottle = 0  ";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();

        $lableObject = array();
        foreach($productList as $value){
            $oneObject = array();
            $oneObject['quantity'] = 'Picked' ;
            $lableObject[] = $oneObject;
            $oneObject['quantity'] = 'Delivered' ;
            $lableObject[] = $oneObject;
            $oneObject['quantity'] = 'Returned' ;
            $lableObject[] = $oneObject;
            $oneObject['quantity'] = 'Wastage' ;
            $lableObject[] = $oneObject;
            $oneObject['quantity'] = 'Balance' ;
            $lableObject[] = $oneObject;
        }



        $this->render('monthlyReconcileStock' , array(
            'riderList'=>riderData::getRiderList(),
            'reportData'=>monthlyReport_data::getMonthlyReport($data = null),
            'todayMonth'=>$monthNum,
            'year' =>$year,
            'todayData' =>json_encode([]),
            'productList'=>json_encode($productList),
            'lableObject'=>json_encode($lableObject),
        ));

    }


    public function actionsaveNewZone(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo zoneData::saveNewZoneFunction($data);
    }

    public function actionbillReport(){


       $company_id = Yii::app()->user->getState('company_branch_id');

       if($company_id==1){
           $view_page ='billReport_taza';
       }else{
           $view_page ='billReport';
       }

       $this->render($view_page , array(
           'clientList'=>json_encode([]),
           'productList'=>productData::getproductList($page =false),
           'LableList'=>productData::getproductListForlable($page =false),
       ));
    }

    public function actiongetOneMonthlyReport(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo  monthlyReport_data::getMonthlyReport($data);
    }

    public function actionexportRecord(){
          $data= $_GET;
          $report_data = monthlyReport_data::MonthlyReportExport($data);
          $lable  = $report_data['lable'];
          $record  = $report_data['data'];

        $month_number =   $data['month'];
        $months = array (1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
         $months[$month_number];


           $year = $data['year'];
          header("Content-type: application/vnd.ms-excel; name='excel'");
         header("Content-Disposition: attachment; filename=Monthly_Delivery_Report.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

               echo 'Date,';
                echo  $months[$month_number]." ".$year;
               echo "\r\n";
                echo "ID ,Name , Address , Pnone No. ,Zone,Product,";
               // echo
            $end_total = array();
            $count_increment = 0;
          foreach($lable as $lablevalue){

              $count_increment++;
              $end_total[$count_increment] = 0 ;
            echo    $lablevalue['day_name']." ".$lablevalue['day_Date'].',';

           }
           echo "\r\n";
          $setValue_increment = 0 ;
        foreach ($record as $value){

            echo str_replace(","," ",$value['client_id']).',';
            echo str_replace(","," ",$value['fullname']).',';

            $address = str_replace(","," ",$value['address']);

              echo  str_replace("-"," ",$address).',';

            echo "\t".str_replace("+92","0",$value['cell_no_1']).',';
            echo str_replace(","," ",$value['zone_name']).',';
            echo str_replace(","," ",$value['product_name']).',';

               foreach($value['row_data'] as $x){
                   $setValue_increment++;
                   $end_total[$setValue_increment] =$end_total[$setValue_increment] + $x['delivery'] ;
                   echo $x['delivery'].',';
               }
            echo "\r\n";
        }
        echo ",,,,,Total,";
        foreach ($end_total as $total){
             echo $total.',' ;
        }


    }

    public function actionsmsPortal(){

        $this->render('smsPortal' , array(
            "zoneList"=>str_replace("'","&#39;", zoneData::getZoneList()),
            'clientList'=>json_encode(array()),
           /* 'clientList'=>str_replace("'","&#39;",clientData::getActiveClientList() ),*/
            "zoneList"=>str_replace("'","&#39;",zoneData::getZoneList() ),
            'riderList'=>str_replace("'","&#39;",riderData::getRiderList() ),
        ));
    }
    public function actionSendSMS_selectCustomer(){
        $post = file_get_contents("php://input");

         if($post =='3_inactive'){
             echo  clientData::getActiveClientList_forLedger_active_unactive(0);
         }else{
             echo  clientData::getActiveClientList_forLedger_active_unactive(1);
         }

       clientData::getActiveClientList_forLedger();
    }

     public function actionSendSMS_selectCustomer_tagColor(){
         echo  clientData::getActiveClientList_forLedger_active_unactive_colorTag(0);
     }
    public function actioneditZone(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo zoneData::editZoneFunction($data);
    }

    public function actiondelete(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo zoneData::deleteFunction($data);
    }
    public function actionSendSMS(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

         $option = $data['optionName'];




        $sms = $data['message'];

        $company_id = Yii::app()->user->getState('company_branch_id');
        $companyObject  =  utill::get_companyTitle($company_id);
        $companyMask = $companyObject['sms_mask'];
        $getmessage = $companyObject['company_title'];

        $message = $sms."\n\n".$getmessage;
         if($option == '3_inactive' || $option == '3_active'){

           $client_id = $data['customerID'];
             $clientObject = Client::model()->findByPk(intval($client_id));

              $phoneNo =  $clientObject['cell_no_1'];
             $fullname = $clientObject['fullname'];
             $network_id = $clientObject['network_id'];

             smsLog::saveSms($client_id ,$company_id ,$phoneNo ,$fullname ,$message);
             utill::sendSMS2($phoneNo , $message , $companyMask ,$company_id , $network_id,$client_id);
         }

         if($option == 1){


             $company_id = Yii::app()->user->getState('company_branch_id');

            $clientObject = Client::model()->findAll(array("condition"=>"company_branch_id =$company_id"));
             foreach($clientObject as $value){
                 $phoneNo = $value['cell_no_1'];
                 $fullname = $value['fullname'];
                 $client_id = $value['client_id'];
                 $network_id = $value['network_id'];
                 smsLog::saveSms($client_id ,$company_id ,$phoneNo ,$fullname ,$message);

                 utill::sendSMS2($phoneNo , $message , $companyMask ,$company_id ,$network_id,$client_id);

             }
         }

        if($option == 2){
              $zoneId = array();
             $zoneList = $data['zoneList'];
             foreach ($zoneList as $value){
                 if($value['is_selected']){
                     $zoneId[] = $value['zone_id'];
                 }
              }

              $idList =  implode("," , $zoneId);
             $query = "select c.client_id , c.cell_no_1 , c.network_id , c.fullname from client as c
            where c.zone_id in ($idList) and c.company_branch_id = $company_id AND c.is_active = 1";
            $clientObject = Yii::app()->db->createCommand($query)->queryAll();
            foreach($clientObject as $value){
                $phoneNo = $value['cell_no_1'];
                $fullname = $value['fullname'];
                $client_id = $value['client_id'];
                $network_id = $value['network_id'];
                smsLog::saveSms($client_id ,$company_id ,$phoneNo ,$fullname ,$message);
               utill::sendSMS2($phoneNo , $message , $companyMask , $company_id ,$network_id,$client_id);

            }

        }

        if($option == 4){
            $todaydate = date("Y-m-d");
            $company_id = Yii::app()->user->getState('company_branch_id');
            $query = "select c.cell_no_1 ,c.client_id, c.network_id ,c.fullname from client as c
            where  c.company_branch_id = $company_id AND c.is_active = 1" ;
            $clientObject = Yii::app()->db->createCommand($query)->queryAll();

            $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_id ";
            $productList =  Yii::app()->db->createCommand($query)->queryAll();

            foreach($clientObject as $value){
              $client_id =  $value['client_id'];
                  $checkDeliver_sms = false ;
                foreach($productList as $productvalue) {
                     $product_id =$productvalue['product_id'];
                    $phoneNo = $value['cell_no_1'];
                   $totalWeekly_quantity  =    todayDeliveryQuantityCountForApi::getTodayDeliveryCountWeeklyRegularAndSpecial($client_id ,$product_id, $todaydate);
                    if($totalWeekly_quantity == 0){
                         $totalInterval_quantity =  utill::getOneCustomerTodayIntervalSceduler( $client_id,$product_id);

                         if($totalInterval_quantity == 0){
                             $totalSpecialToday_quantity  =    todayDeliveryQuantityCountForApi::getTodaySpecialOrder($client_id ,$product_id ,$todaydate);
                             if($totalSpecialToday_quantity >0){
                                 $checkDeliver_sms = true;
                             }
                         }else{
                             $checkDeliver_sms = true;

                         }

                    }else{
                        $checkDeliver_sms = true;
                    }





                }
               if($checkDeliver_sms){
                   $phoneNo = $value['cell_no_1'];
                   $fullname = $value['fullname'];
                   $client_id = $value['client_id'];
                   $phoneNo = $value['cell_no_1'];
                   $network_id = $value['network_id'];
                   smsLog::saveSms($client_id ,$company_id ,$phoneNo ,$fullname ,$message);
                  utill::sendSMS2($phoneNo , $message , $companyMask , $company_id , $network_id,$client_id);
               }


            }

        }
        if($option == 5){
             $riderID =  $data['rider_id'];
                    $clientQuery = "Select c.client_id,c.address , c.cell_no_1 , c.network_id,c.fullname from rider_zone as rz
                               Right join client as c ON c.zone_id = rz.zone_id 
                               where rz.rider_id = $riderID  AND c.is_active = 1 ";
                       $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
                    foreach($clientResult as $value){
                       $phoneNo = $value['cell_no_1'];
                       $fullname = $value['fullname'];
                       $client_id = $value['client_id'];
                       $phoneNo = $value['cell_no_1'];
                       $network_id = $value['network_id'];
                      smsLog::saveSms($client_id ,$company_id ,$phoneNo ,$fullname ,$message);
                      utill::sendSMS2($phoneNo , $message , $companyMask , $company_id , $network_id,$client_id);
                    }
        }
        if($option == 6){
                  $riderID =  $data['rider_id'];

                       $clientQuery = "Select c.client_id,c.address , c.cell_no_1 , c.network_id,c.fullname from rider_zone as rz
                       Right join client as c ON c.zone_id = rz.zone_id 
                       where rz.rider_id = $riderID  AND c.is_active = 1 ";

                       $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

                    foreach($clientResult as $value){



                        $client_id = $value['client_id'];

                        $phoneNo = $value['cell_no_1'];
                        $fullname = $value['fullname'];
                        $client_id = $value['client_id'];
                        $phoneNo = $value['cell_no_1'];
                        $network_id = $value['network_id'];
                        $lastdayePayment = smsData::getLastPaymentRecord($client_id);
                        if($lastdayePayment){
                             $currentBalance = APIData::calculateFinalBalance($client_id);

                            if($currentBalance>0){
                                smsLog::saveSms($client_id ,$company_id ,$phoneNo ,$fullname ,$message);
                                utill::sendSMS2($phoneNo , $message , $companyMask , $company_id , $network_id,$client_id);
                            }

                        }

                    }
        }
        if($option == 7){
           $tag_color_id = $data['tag_color_id'];
            $company_id = Yii::app()->user->getState('company_branch_id');
            $clientQuery = "SELECT * FROM client AS c
                       WHERE c.company_branch_id ='$company_id' AND c.tag_color_id =$tag_color_id ";
            $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
            foreach($clientResult as $value){
                $phoneNo = $value['cell_no_1'];
                $fullname = $value['fullname'];
                $client_id = $value['client_id'];
                $phoneNo = $value['cell_no_1'];
                $network_id = $value['network_id'];
                smsLog::saveSms($client_id ,$company_id ,$phoneNo ,$fullname ,$message);
                utill::sendSMS2($phoneNo , $message , $companyMask , $company_id , $network_id,$client_id);
            }

        }

       echo 'okk';
    }
}
