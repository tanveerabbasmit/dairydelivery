<?php

class RoutineController extends Controller
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

	public function actionchange_rateof_customer(){
        $clientQuery = "Select  * from client AS  c   where c.zone_id in (656,1297,657) ";

         die();
        $result = Yii::app()->db->createCommand($clientQuery)->queryAll();

         foreach ($result as $value){
             $client_id = $value['client_id'];

             $object =ClientProductPrice::model()->findByAttributes([
                 'client_id'=>$client_id,
                 'product_id'=>'66'
             ]);

            if($object){
                $object->price =120;
                $object->save();
            }else{
                $object = new ClientProductPrice();
                $object->client_id = $client_id;
                $object->price =120;
                $object->product_id =66;
                $object->save();
            }


         }
    }

    public function actionSetNewPrice(){
          die();


        $client_id = 3555;
        $startdate = '2018-07-01';
        $startenddate = '2018-07-31';
        $newPrice = 100;
        $product_id = 57;
        $query = "select * from delivery as d
       left join delivery_detail as dd ON dd.delivery_id =d.delivery_id and dd.product_id ='$product_id'
          where  d.client_id = '2399' and dd.delivery_detail_id is not null";

        $result = Yii::app()->db->createCommand($query)->queryAll();

        //  echo '<pre>';
        //  print_r($result);
        //  echo '</pre>';
        // die();

        $count = 0;
        foreach($result as $value){

            $quantity  = $value['quantity'];
            $Countamount = 55 ;

            $deliveryID =$value['delivery_id'];

            $deliveryDetailID = $value['delivery_detail_id'];
            $deliveryObject = Delivery::model()->findByPk(intval($deliveryID));


            $deliveryObject->total_amount = $Countamount;
            $deliveryObject->save();
            $deliveryDetailOBject = DeliveryDetail::model()->findByPk(intval($deliveryDetailID));
            $deliveryDetailOBject->amount = $Countamount;
            $deliveryDetailOBject->save();

            $count++;
        }

        echo   $count.' records updated';
    }

	 public function actionremovedelivery(){
	      die();
         $productQuery = "SELECT * FROM  delivery as d
                       WHERE d.company_branch_id = 12 ";

         $productLIst =  Yii::app()->db->createCommand($productQuery)->queryAll();
         foreach($productLIst as $value){
              $date = $value['date'];
               $delivery_id =   $value['delivery_id'];
               $object = DeliveryDetail::model()->deleteAllByAttributes(array('delivery_id'=>$delivery_id));
                Delivery::model()->deleteByPk(intval($delivery_id));
         }

     }

    public function actionsetPrice(){
	      die('ok');
        $productQuery = "SELECT * FROM  delivery as d
                       WHERE d.client_id =  and d.date <='2018-02-28' ";

        $productLIst =  Yii::app()->db->createCommand($productQuery)->queryAll();
        foreach($productLIst as $value){
            $date = $value['date'];
            $delivery_id =   $value['delivery_id'];
            $object = DeliveryDetail::model()->deleteAllByAttributes(array('delivery_id'=>$delivery_id));
            Delivery::model()->deleteByPk(intval($delivery_id));
        }

    }

    public function actionremovepayment(){
	       die('ok');
        $productQuery = "SELECT * FROM  payment_master as d
                 WHERE d.company_branch_id = 12 ";

        $productLIst =  Yii::app()->db->createCommand($productQuery)->queryAll();
        foreach($productLIst as $value){
            $date = $value['date'];
             $delivery_id =   $value['payment_master_id'];

             $object = PaymentDetail::model()->deleteAllByAttributes(array('payment_master_id'=>$delivery_id));

            PaymentMaster::model()->deleteByPk(intval($delivery_id));
        }

    }

    public function actionmakePaymentZero(){
           die();
        $productQuery = "SELECT * FROM  delivery as d
                          WHERE d.client_id = 2395 ";
        $productLIst =  Yii::app()->db->createCommand($productQuery)->queryAll();

        foreach($productLIst as $value){
           $delivery_id = $value['delivery_id'];
            $delivery_object = Delivery::model()->findByPk(intval($delivery_id));
            $delivery_object->total_amount =0;
            $delivery_object->save();

            $object_delivery_detail = DeliveryDetail::model()->findAllByAttributes(array('delivery_id'=>$delivery_id));
            foreach ($object_delivery_detail as $value){
                // var_dump($value['amount']);
                 // die();
                $delivery_detail_id = $value['delivery_detail_id'];
                 $detailObject = DeliveryDetail::model()->findByPk($delivery_detail_id);
                $detailObject->amount = 0;
                $detailObject->save();

            }


        }

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
             }else{

                //  $startWeakDate =  date('Y-m-d', strtotime($fineNextWeekDate.' day', strtotime($todaydate)));

               //   $endtWeakDate =  date('Y-m-d', strtotime('6 day', strtotime($startWeakDate)));


                 $startWeakDate =  date("Y-m-d");


                  $endtWeakDate = date("Y-m-d");
              }



            $start_date_strtotime = strtotime($startWeakDate);

            $end_date_strtotime = strtotime($endtWeakDate);

            $different_time = $end_date_strtotime - $start_date_strtotime;

            $no_of_days_increment = floor($different_time / (60 * 60 * 24));


                 $countTotal = 0;
            while($start_date_strtotime <($end_date_strtotime+8640)){

                  $selectDate = date("Y-m-d", $start_date_strtotime);

                $start_date_strtotime += 86400;

                $totalInterval_quantity =  count_demand_quantity_for_future::getOneCustomerTodayIntervalSceduler_with_date_future_date_for_demand($product_id ,$selectDate);
                $totalWeekly_quantity =  count_demand_quantity_for_future::getTodayDeliveryCountWeeklyRegularAndSpecial_demandCount($product_id ,$selectDate);
                $totalSpecialToday_quantity =  count_demand_quantity_for_future::getTodaySpecialOrder_demandCount($product_id ,$selectDate);
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
        $finalData['product'] = $resultProductList;


        $this->render('weeklyQuantity' , array(
             "data"=>json_encode($finalData),

        ));
    }
    public function actionmonthlyReport(){
        $monthNum  = date('m');
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
       $monthName = $dateObj->format('F'); // March
       $monthName = 'okkk'; // March

          $year = intval(date('Y'));



       $this->render('monthlyReport' , array(
           'riderList'=>riderData::getRiderList(),
          'reportData'=>monthlyReport_data::getMonthlyReport($data = null),
           'todayMonth'=>$monthNum,
            'year' =>$year
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


       $this->render('billReport' , array(
           'clientList'=>clientData::getActiveClientList(),
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
            "zoneList"=>zoneData::getZoneList(),
            'clientList'=>clientData::getActiveClientList(),
            "zoneList"=>zoneData::getZoneList(),
            'riderList'=>riderData::getRiderList(),
        ));
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
         if($option == 3){

           $client_id = $data['customerID'];
             $clientObject = Client::model()->findByPk(intval($client_id));

              $phoneNo =  $clientObject['cell_no_1'];
             $fullname = $clientObject['fullname'];
             $network_id = $clientObject['network_id'];

             smsLog::saveSms($client_id ,$company_id ,$phoneNo ,$fullname ,$message);
             utill::sendSMS2($phoneNo , $message , $companyMask ,$company_id , $network_id);
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

                 utill::sendSMS2($phoneNo , $message , $companyMask ,$company_id ,$network_id);

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
               utill::sendSMS2($phoneNo , $message , $companyMask , $company_id ,$network_id);

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
                  utill::sendSMS2($phoneNo , $message , $companyMask , $company_id , $network_id);
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
                 utill::sendSMS2($phoneNo , $message , $companyMask , $company_id , $network_id);
            }

        }
       echo 'okk';
    }
}
