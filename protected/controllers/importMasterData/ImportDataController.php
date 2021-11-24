<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 10/19/2017
 * Time: 11:04 AM
 */
class ImportDataController extends Controller
{


      public function actiondelete_delivery(){
          $client_object = Client::model()->findAllByAttributes([
              'company_branch_id'=>19
          ]);
          die('locked');
          foreach ($client_object as $value){

             $client_id = $value['client_id'];

              $query="SELECT * FROM delivery AS d
               WHERE d.client_id='$client_id' AND d.date <='2020-06-30' ";
              $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

              foreach($queryResult as $delivery){
                   $delivery_id = $delivery['delivery_id'];

                   DeliveryDetail::model()->deleteAllByAttributes([
                       'delivery_id'=>$delivery_id
                   ]);

                   $object_delivery = Delivery::model()->findByPk($delivery_id);
                   $object_delivery->delete();
              }





            $query_mayent = "SELECT 
                *
                FROM payment_master AS p
                WHERE p.bill_month_date <='2020-06-30' 
                and p.client_id = '$client_id' ";

              $payment_result =  Yii::app()->db->createCommand($query_mayent)->queryAll();

              foreach ($payment_result as $value){

                  $payment_master_id = $value['payment_master_id'];

                  PaymentDetail::model()->deleteAllByAttributes([
                             'payment_master_id'=>$payment_master_id
                  ]);

                  $payment_object = PaymentMaster::model()->findByPk($payment_master_id);

                  $payment_object->delete();

              }




          }
      }

    public function actionInsert_Payment_for_raej(){


        $file = realpath(Yii::app()->basePath.'/master_file_date/raej/opening_balance_30_june.csv');
        if (($handle = fopen($file, "r")) !== FALSE) {
            $recordCount = 0;
            while (($data = fgetcsv($handle, ",")) !== FALSE){


                echo   $client_id =  $data[0];
                echo "_";

                echo   $balance = -(str_replace(",","",$data[4]));
                echo "<br>";

                $object = array();
                $object['amount_paid'] = intval($balance);
                $object['client_id'] = $client_id ;
                $object['payment_mode'] = 3;
                $object['remarks'] = 2;
                $object['startDate'] = '2020-06-30';
                $object['trans_ref_no'] = '2017-10-31_'.$client_id;

                if($balance!=0){
                    echo   conformPayment::conformPaymentMethodFromPortal(19, $object,[]);
                    $recordCount++;


                }


            }

        }
        echo   $recordCount." record are added";
    }

    public function actionInsertPayment22(){


        $file = realpath(Yii::app()->basePath.'/master_file_date/greenLandPayment/copyOfGreenLand2.csv');

        if (($handle = fopen($file, "r")) !== FALSE) {
            $recordCount = 0;
            while (($data = fgetcsv($handle, ",")) !== FALSE){

                echo   $client_id =  $data[0];
                echo "_";
                echo   $balance = -(str_replace(",","",$data[1]));
                echo "<br>";

                $object = array();
                $object['amount_paid'] = intval($balance);
                $object['client_id'] = $client_id ;
                $object['payment_mode'] = 3;
                $object['remarks'] = 2;
                $object['startDate'] = '2017-10-31';
                $object['trans_ref_no'] = '2017-10-31_'.$client_id;

                if($balance<0){
                    echo   conformPayment::conformPaymentMethodFromPortal(7 , $object);
                    $recordCount++;
                }


            }

        }
        echo   $recordCount." record are added";
    }

      public function actionImportZoneList(){


          $file = realpath(Yii::app()->basePath.'/master_file_date/Zone_List.csv');

          if (($handle = fopen($file, "r")) !== FALSE) {
                   $recordCount = 0;
              while (($data = fgetcsv($handle, ",")) !== FALSE){

                  $zoneObject = new Zone();
                  $zoneObject->company_branch_id = 7;
                  $zoneObject->name = $data[0] ;
                  $zoneObject->is_active =1;
                  $zoneObject->is_deleted =1;

                  if ($zoneObject->save()){
                      $recordCount++;
                      echo 'save';
                  }else{
                     var_dump($zoneObject->getErrors());
                  }
              }

          }
       echo   $recordCount." record are added";
      }

      public function actionFixedPrice(){


         $file = realpath(Yii::app()->basePath.'/master_file_date/price/list1_for_price_fixes_id.csv');

          if (($handle = fopen($file, "r")) !== FALSE) {
                   $recordCount = 0;
              while (($data = fgetcsv($handle, ",")) !== FALSE){
                  $recordCount++;

                     $customer_id =   $data[0];

                  $delivery_Object = Delivery::model()->findByAttributes(array('client_id'=>$customer_id ,'date'=>'2017-10-25'));

                   if($delivery_Object){

                       $delivery_id  = $delivery_Object['delivery_id'];


                       $deliver_detail =DeliveryDetail::model()->findByAttributes(array('delivery_id'=>$delivery_id));

                       $quantity = $deliver_detail['quantity'];
                       $total_prize = $quantity * 95 ;

                       $delivery_Object->total_amount = $total_prize;
                       $delivery_Object->partial_amount = $total_prize;
                       if($delivery_Object->save()){
                           echo 'yes'.$customer_id;
                           echo "<br>";
                           $deliver_detail->amount = $total_prize;
                           $deliver_detail->save();

                       }else{
                           echo 'not delievry'.$customer_id;
                           echo "<br>";
                       }
                   }


              }

          }
       echo   $recordCount." record are added";
      }

      public function actionFixedPrice_rateCahange(){

          $query="SELECT * FROM delivery AS d
               WHERE d.client_id='10093' AND d.DATE >='2019-03-15' ";
          $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

          foreach ($queryResult as $value){


              $delivery_id  = $value['delivery_id'];

              $deliver_detail =DeliveryDetail::model()->findByAttributes(array('delivery_id'=>$delivery_id));
              $quantity = $deliver_detail['quantity'];
              $total_prize = $quantity * 88.55 ;
              $deliver_detail->amount = $total_prize;

              if($deliver_detail->save()){
                  echo 'yes'.$value['client_id'];
                  echo "<br>";

              }else{
                  echo 'not delievry'.$value['client_id'];
                  echo "<br>";
              }

              echo $total_prize;
              echo "<br>";
          }




      }

    public function actioncustomerList_for_import_milkman(){

             die();

          $file = realpath(Yii::app()->basePath.'/master_file_date/milk_man/eme_file_1.csv');


          if (($handle = fopen($file, "r")) !== FALSE) {
                   $recordCount = 0;
                   $recordCount_forPassword = 0;
              while (($data = fgetcsv($handle, ",")) !== FALSE){


                      $recordCount_forPassword++;

                      $fullname = $data[0];
                      $username = $data[1];

                      $password = $data[2];
                      $quantity = $data[3];


                      $array_object = explode("-",$quantity);


                      $address = $data[4];

                      $zone = $data[5];

                      $zone_object = Zone::model()->findByAttributes([
                          'name'=>$zone,
                          'company_branch_id'=>25,
                      ]);

                      if($zone_object){
                          $zone_id = $zone_object->zone_id;

                      }else{
                          $zone_object = New Zone();
                          $zone_object->name = $zone;
                          $zone_object->company_branch_id =25;
                          $zone_object->save();
                          $zone_id = $zone_object->zone_id;

                      }
                      $phone = $data[6];

                      $payment_term = $data[9];
                      $price = $data[10];
                      $product_id = $data[12];

                      $remove_dash = str_replace("-","",$phone);

                      $final_no =  "+92".ltrim($remove_dash, '0');

                      //$username = strtolower(str_replace(' ', '', $fullname));

                      $newDate = date("Y-m-d H:i:s");

                        $customerObject = New Client();
                        $customerObject->user_id = 2;
                        $customerObject->company_branch_id = 25;
                        $customerObject->zone_id = $zone_id;
                        $customerObject->fullname = $fullname;
                        $customerObject->userName = $username;
                        $customerObject->password = $password;
                        $customerObject->cell_no_1 = $final_no;
                        $customerObject->city = '' ;
                        $customerObject->address = $address ;
                        $customerObject->created_by = 2 ;
                        $customerObject->updated_by = 2 ;
                        $customerObject->daily_delivery_sms = 0;
                        $customerObject->alert_new_product = 0;
                        $customerObject->created_at =$newDate;
                        $customerObject->payment_term =0;

                        if($customerObject->save()){

                               $client_id = $customerObject->client_id ;

                               $clientProduct = new ClientProductPrice();
                               $clientProduct->client_id =$client_id ;

                               $clientProduct->product_id =$product_id;

                               $clientProduct->price =$price;
                               if($price){

                                   $clientProduct->save();

                               }


                               if(sizeof($array_object)==2){
                                   if($array_object[0]=='d'){
                                       $new_quantity =  $array_object[1];



                                       $clientProduct = new ClientProductPrice();
                                       $clientProduct->client_id = $client_id;

                                       $clientProduct->product_id = $product_id;

                                       $clientProduct->price = $price;

                                       $clientProduct->save();


                                       $cleintProductFrequency = new ClientProductFrequency();
                                       $cleintProductFrequency->client_id = $client_id;
                                       $cleintProductFrequency->frequency_id = 1;
                                       $cleintProductFrequency->product_id = $product_id;
                                       $cleintProductFrequency->quantity = 0;
                                       $cleintProductFrequency->total_rate = intval(0);
                                       $cleintProductFrequency->orderStartDate = date("y-m-d");
                                       if ($cleintProductFrequency->save()) {
                                           $client_product_frequency_id = $cleintProductFrequency->client_product_frequency;
                                           $weekDay = 1;


                                           for ($weekDay; $weekDay <= 7; $weekDay++) {
                                               $new_quantity = $new_quantity;

                                               $client_product_frequency_quantity_object = new ClientProductFrequencyQuantity();
                                               $client_product_frequency_quantity_object->client_product_frequency_id = $client_product_frequency_id;
                                               $client_product_frequency_quantity_object->frequency_id = $weekDay;
                                               $client_product_frequency_quantity_object->quantity = $new_quantity;
                                               $client_product_frequency_quantity_object->preferred_time_id = 1;
                                               $client_product_frequency_quantity_object->isSelected = 1;
                                               if ($client_product_frequency_quantity_object->save()) {
                                               } else {
                                                   var_dump($client_product_frequency_quantity_object->getErrors());

                                               }
                                           }


                                       } else {
                                           var_dump($cleintProductFrequency->getErrors());
                                       }


                                   } else {
                                       var_dump($customerObject->getErrors());
                                   }

                                   if($array_object[0]=='alt'){
                                       $new_quantity =  $array_object[1];
                                       $start_interval_scheduler = new IntervalScheduler();
                                       $start_interval_scheduler->client_id =$client_id;
                                       $start_interval_scheduler->product_id =$product_id;
                                       $start_interval_scheduler->interval_days =2;
                                       $start_interval_scheduler->is_halt =1;
                                       $start_interval_scheduler->product_quantity =$new_quantity;
                                       $start_interval_scheduler->start_interval_scheduler = date("y-m-d");
                                       $start_interval_scheduler->halt_start_date = date("y-m-d");
                                       $start_interval_scheduler->halt_end_date = date("y-m-d");
                                       if($start_interval_scheduler->save()){

                                       }else{
                                           var_dump($start_interval_scheduler->getErrors());
                                       }


                                   }



                               }

                        }else{
                              var_dump($customerObject->getErrors());
                       }




              }

          }

      }
    public function actioncustomerList_for_import(){


          $file = realpath(Yii::app()->basePath.'/master_file_date/milkCow/Book2.csv');


          if (($handle = fopen($file, "r")) !== FALSE) {
                   $recordCount = 0;
                   $recordCount_forPassword = 0;
              while (($data = fgetcsv($handle, ",")) !== FALSE){

                      $recordCount_forPassword++;

                      $fullname = $data[0];
                      $address = $data[1];
                      $phone = $data[2];
                      $price = $data[3];

                      $remove_dash = str_replace("-","",$phone);

                      $final_no =  "+92".ltrim($remove_dash, '0');

                      $username = strtolower(str_replace(' ', '', $fullname));

                      $newDate = date("Y-m-d H:i:s");

                        $customerObject = New Client();
                        $customerObject->user_id = 2;
                        $customerObject->company_branch_id = 8;
                        $customerObject->zone_id = 287;
                        $customerObject->fullname = $fullname;
                        $customerObject->userName = $username;
                        $customerObject->password = "12345";
                        $customerObject->cell_no_1 = $final_no;
                        $customerObject->city = '' ;
                        $customerObject->address = $address ;
                        $customerObject->created_by = 2 ;
                        $customerObject->updated_by = 2 ;
                        $customerObject->daily_delivery_sms = 0;
                        $customerObject->alert_new_product = 0;
                        $customerObject->created_at =$newDate;

                        if($customerObject->save()){

                              echo  $client_id = $customerObject->client_id ;
                               $clientProduct = new ClientProductPrice();
                               $clientProduct->client_id =$client_id ;

                               $clientProduct->product_id =40;

                               $clientProduct->price =$price;

                               $clientProduct->save();

                              echo "<br>";



                       }else{
                              var_dump($customerObject->getErrors());
                       }




              }

          }

      }
     /*mit tanver*/
    public function actioncustomerList_for_Milkful(){


        $file = realpath(Yii::app()->basePath.'/master_file_date/Milkful/list_csv_main.csv');


        if (($handle = fopen($file, "r")) !== FALSE) {
            $recordCount = 0;
            $recordCount_forPassword = 0;
            while (($data = fgetcsv($handle, ",")) !== FALSE) {


                $recordCount_forPassword++;

                $company_id = 29;
                $fullname = $data[0];
                if (!empty($fullname)) {


                    $address = $data[0];
                    $zone_id = $data[3];
                    $product_id = $data[4];


                    $price = $data[5];

                    $schedual_quantity =[];
                    $schedual_quantity['1'] = $data['7'];
                    $schedual_quantity['2'] = $data['8'];
                    $schedual_quantity['3'] = $data['9'];
                    $schedual_quantity['4'] = $data['10'];
                    $schedual_quantity['5'] = $data['11'];
                    $schedual_quantity['6'] = $data['12'];
                    $schedual_quantity['7'] = $data['13'];



                    $phone = '03990000000';

                    $remove_dash = str_replace("-", "", $phone);
                    $final_no = "+92" . ltrim($remove_dash, '0');
                    $username = strtolower(str_replace(' ', '', $fullname));
                    $newDate = date("Y-m-d H:i:s");

                    $customerObject = new Client();
                    $customerObject->user_id = 2;
                    $customerObject->company_branch_id = $company_id;
                    $customerObject->zone_id = $zone_id;
                    $customerObject->fullname = $fullname;
                    $customerObject->userName = $username;
                    $customerObject->password = "12345";
                    $customerObject->cell_no_1 = $final_no;
                    $customerObject->city = 'milk_full';
                    $customerObject->address = $address;
                    $customerObject->created_by = 2;
                    $customerObject->updated_by = 2;
                    $customerObject->daily_delivery_sms = 0;
                    $customerObject->alert_new_product = 0;
                    $customerObject->created_at = $newDate;

                    if ($customerObject->save()) {

                        $client_id = $customerObject->client_id;



                        $clientProduct = new ClientProductPrice();
                        $clientProduct->client_id = $client_id;

                        $clientProduct->product_id = $product_id;

                        $clientProduct->price = $price;

                        $clientProduct->save();


                        $cleintProductFrequency = new ClientProductFrequency();
                        $cleintProductFrequency->client_id = $client_id;
                        $cleintProductFrequency->frequency_id = 1;
                        $cleintProductFrequency->product_id = $product_id;
                        $cleintProductFrequency->quantity = 0;
                        $cleintProductFrequency->total_rate = intval(0);
                        $cleintProductFrequency->orderStartDate = date("y-m-d");
                        if ($cleintProductFrequency->save()) {
                            $client_product_frequency_id = $cleintProductFrequency->client_product_frequency;
                            $weekDay = 1;


                            for ($weekDay; $weekDay <= 7; $weekDay++) {
                                $new_quantity = $schedual_quantity[$weekDay];

                                $client_product_frequency_quantity_object = new ClientProductFrequencyQuantity();
                                $client_product_frequency_quantity_object->client_product_frequency_id = $client_product_frequency_id;
                                $client_product_frequency_quantity_object->frequency_id = $weekDay;
                                $client_product_frequency_quantity_object->quantity = $new_quantity;
                                $client_product_frequency_quantity_object->preferred_time_id = 1;
                                $client_product_frequency_quantity_object->isSelected = 1;
                                if ($client_product_frequency_quantity_object->save()) {
                                } else {
                                    var_dump($client_product_frequency_quantity_object->getErrors());

                                }
                            }


                        } else {
                            var_dump($cleintProductFrequency->getErrors());
                        }


                    } else {
                        var_dump($customerObject->getErrors());
                    }


                }

            }

        }

    }
    public function actioncustomerList_for_ahla(){


          $file = realpath(Yii::app()->basePath.'/master_file_date/aala/customer_list.csv');


          if (($handle = fopen($file, "r")) !== FALSE) {
                   $recordCount = 0;
                   $recordCount_forPassword = 0;
              while (($data = fgetcsv($handle, ",")) !== FALSE){



                           die();

                      $recordCount_forPassword++;

                      $company_id = 23;
                      $fullname = $data[1];
                      $address = $data[2];
                      $zone = $data[3];
                      $phone = $data[4];
                      $price = $data[5];


                      $zone_object = Zone::model()->findByAttributes([
                          'name'=>$zone,
                          'company_branch_id'=>25,
                      ]);

                      if($zone_object){
                          $zone_id = $zone_object->zone_id;

                      }else{
                          $zone_object = New Zone();
                          $zone_object->name = $zone;
                          $zone_object->company_branch_id =23;
                          $zone_object->save();
                          $zone_id = $zone_object->zone_id;

                      }

                      $remove_dash = str_replace("-","",$phone);
                      $final_no =  "+92".ltrim($remove_dash, '0');
                      $username = strtolower(str_replace(' ', '', $fullname));
                      $newDate = date("Y-m-d H:i:s");

                        $customerObject = New Client();
                        $customerObject->user_id = 2;
                        $customerObject->company_branch_id = $company_id;
                        $customerObject->zone_id = $zone_id;
                        $customerObject->fullname = $fullname;
                        $customerObject->userName = $username;
                        $customerObject->password = "12345";
                        $customerObject->cell_no_1 = $final_no;
                        $customerObject->city = '' ;
                        $customerObject->address = $address ;
                        $customerObject->created_by = 2 ;
                        $customerObject->updated_by = 2 ;
                        $customerObject->daily_delivery_sms = 0;
                        $customerObject->alert_new_product = 0;
                        $customerObject->created_at =$newDate;

                        if($customerObject->save()){

                            /*  echo  $client_id = $customerObject->client_id ;
                               $clientProduct = new ClientProductPrice();
                               $clientProduct->client_id =$client_id ;

                               $clientProduct->product_id =40;

                               $clientProduct->price =$price;

                               $clientProduct->save();

                              echo "<br>";*/



                       }else{
                              var_dump($customerObject->getErrors());
                       }




              }

          }

      }
    public function actioncustomerList_for_import_blaze(){

         $file = realpath(Yii::app()->basePath.'/master_file_date/blaze/new_customer_list.csv');


                 die('locked');
          if (($handle = fopen($file, "r")) !== FALSE) {
                   $recordCount = 0;
                   $recordCount_forPassword = 0;
              while (($data = fgetcsv($handle, ",")) !== FALSE){


                      $recordCount_forPassword++;

                   $client_id = $data[0];

                  $payment_amount = $data[2];



                  $payment  = new PaymentMaster();
                  $payment->client_id = $client_id;
                  $payment->date ="2017-02-07";
                  $payment->amount_paid =-$payment_amount;
                  $payment->remarks ="0";
                  $payment->company_branch_id =10;
                  $payment->time ="7:03";
                  $payment->bill_month_date ="2017-02-07";
                  $payment->payment_mode='4';
                  if($payment->save()){

                  }else{
                      var_dump($payment->getErrors());
                  }





              }

          }

      }

    public function actioncustomerList_for_import2(){


          $file = realpath(Yii::app()->basePath.'/master_file_date/blaze/customerList.csv');

          if (($handle = fopen($file, "r")) !== FALSE) {
                   $recordCount = 0;
                   $recordCount_forPassword = 0;
              while (($data = fgetcsv($handle, ",")) !== FALSE){
                  $recordCount_forPassword++;

                      $newDate = date("Y-m-d H:i:s");
                     $fullname = $data[0];
                     $full_address = $data[2];

                      $contact_number = $data[1];

                  $zone_id = $data[3];

                    $contact_number_without_sc = str_replace("-","" , $contact_number);
                    $contact_number_without_zero = intval($contact_number_without_sc);
                     $fullNumber_contact_number = '+92'.$contact_number_without_zero ;

                    $demand = $data[4];

                    $delivery_day = $data[5];


                      if($delivery_day == 1){

                                    $customerObject = New Client();

                                    $customerObject->user_id = 2;
                                    $customerObject->company_branch_id = 10;
                                    $customerObject->zone_id = $zone_id;
                                    $customerObject->fullname = $fullname;
                                    $customerObject->userName = $fullname.$recordCount_forPassword."7";
                                    $customerObject->password = "12345";
                                    $customerObject->cell_no_1 = $fullNumber_contact_number;
                                    $customerObject->city = '' ;
                                    $customerObject->address = $full_address ;
                                    $customerObject->created_by = 2 ;
                                    $customerObject->updated_by = 2 ;
                                    $customerObject->daily_delivery_sms = 0;
                                    $customerObject->alert_new_product = 0;
                                    $customerObject->created_at =$newDate;

                                    if($customerObject->save()){

                                        $client_id =$customerObject->client_id ;
                                        $cleintProductFrequency = New ClientProductFrequency();
                                        $cleintProductFrequency->client_id = $client_id ;
                                        $cleintProductFrequency->frequency_id =1;
                                        $cleintProductFrequency->product_id =42;
                                        $cleintProductFrequency->quantity =0;
                                        $cleintProductFrequency->total_rate =intval(0);
                                        $cleintProductFrequency->orderStartDate =date("y-m-d");
                                        if($cleintProductFrequency->save()){
                                          $client_product_frequency_id =  $cleintProductFrequency->client_product_frequency;
                                            $weekDay = 1;
                                           for($weekDay ; $weekDay<=7 ;$weekDay++){
                                               $client_product_frequency_quantity_object =new ClientProductFrequencyQuantity();
                                               $client_product_frequency_quantity_object->client_product_frequency_id = $client_product_frequency_id;
                                               $client_product_frequency_quantity_object->frequency_id = $weekDay;
                                               $client_product_frequency_quantity_object->quantity = $demand;
                                               $client_product_frequency_quantity_object->preferred_time_id =1;
                                               $client_product_frequency_quantity_object->isSelected =1;
                                               if($client_product_frequency_quantity_object->save()){
                                               }else{
                                                   var_dump($client_product_frequency_quantity_object->getErrors());

                                               }
                                           }


                                        }else{
                                          var_dump($cleintProductFrequency->getErrors());
                                         }

                                   }else{

                                   }

                      }elseif($delivery_day=='2'){

                          $customerObject = New Client();

                          $customerObject->user_id = 2;
                          $customerObject->company_branch_id = 10;
                          $customerObject->zone_id = $zone_id;
                          $customerObject->fullname = $fullname;
                          $customerObject->userName = $fullname.$recordCount_forPassword."7";
                          $customerObject->password = "12345";
                          $customerObject->cell_no_1 = $fullNumber_contact_number;
                          $customerObject->city = '' ;
                          $customerObject->address = $full_address ;
                          $customerObject->created_by = 2 ;
                          $customerObject->updated_by = 2 ;
                          $customerObject->daily_delivery_sms = 0;
                          $customerObject->alert_new_product = 0;
                          $customerObject->created_at =$newDate;
                          if($customerObject->save()){
                               $client_id =$customerObject->client_id ;
                                $start_interval_scheduler = new IntervalScheduler();
                                 $start_interval_scheduler->client_id =$client_id;
                                 $start_interval_scheduler->product_id =42;
                                 $start_interval_scheduler->interval_days =2;
                                 $start_interval_scheduler->is_halt =1;
                                 $start_interval_scheduler->product_quantity =$demand;
                                 $start_interval_scheduler->start_interval_scheduler = date("y-m-d");
                                 $start_interval_scheduler->halt_start_date = date("y-m-d");
                                 $start_interval_scheduler->halt_end_date = date("y-m-d");
                                 if($start_interval_scheduler->save()){

                                 }else{
                                     var_dump($start_interval_scheduler->getErrors());
                                 }
                          }



                      }else{
                          $customerObject = New Client();

                          $customerObject->user_id = 2;
                          $customerObject->company_branch_id = 10;
                          $customerObject->zone_id = $zone_id;
                          $customerObject->fullname = $fullname;
                          $customerObject->userName = $fullname.$recordCount_forPassword."7";
                          $customerObject->password = "12345";
                          $customerObject->cell_no_1 = $fullNumber_contact_number;
                          $customerObject->city = '' ;
                          $customerObject->address = $full_address ;
                          $customerObject->created_by = 2 ;
                          $customerObject->updated_by = 2 ;
                          $customerObject->daily_delivery_sms = 0;
                          $customerObject->alert_new_product = 0;
                          $customerObject->created_at =$newDate;
                          if($customerObject->save()){

                          }else{
                              var_dump($customerObject->getErrors());
                          }
                      }


              }
                   echo 'ok';
          }

    }
    public function actioncustomerList_for_afentaste(){

           //tanveer _milk

          $file = realpath(Yii::app()->basePath.'/master_file_date/safe/customer_list.csv');



          if (($handle = fopen($file, "r")) !== FALSE) {
                   $recordCount = 0;
                   $recordCount_forPassword = 0;
              while (($data = fgetcsv($handle, ",")) !== FALSE) {
                  $recordCount_forPassword++;
                  $newDate = date("Y-m-d H:i:s");
                  $fullname = $data[0];
                  $user_name = $data[1];
                  $password = $data[2];
                  $father_name = $data[3];
                  $full_address = $data[5] . $data[6] . $data[7];
                  $cnic = $data[9];
                  $cell_no_1 = $data[11];
                  $cell_no_2 = $data[12];
                  $payment_term = $data[16];



                  $customerObject = New Client();
                  $customerObject->user_id = 2;
                  $customerObject->company_branch_id = 18;
                  $customerObject->zone_id = 703;
                  $customerObject->fullname = $fullname;
                  $customerObject->userName = $fullname.$recordCount_forPassword."7";
                  $customerObject->password = "12345";
                  $customerObject->cell_no_1 = $cell_no_1;
                  $customerObject->cell_no_2 = $cell_no_2;
                  $customerObject->city = '' ;
                  $customerObject->address = $full_address ;
                  $customerObject->created_by = 2 ;
                  $customerObject->updated_by = 2 ;
                  $customerObject->daily_delivery_sms = 1;
                  $customerObject->alert_new_product = 1;
                  $customerObject->created_at =$newDate;

                  if($customerObject->save()){

                  }else{
                      echo "<pre>";
                      print_r($customerObject->getErrors());
                      die();
                  }


              }
          }

    }
    public function actionUpdate_customerList_for_afentaste(){


          $file = realpath(Yii::app()->basePath.'/master_file_date/safe/customer_list.csv');



          if (($handle = fopen($file, "r")) !== FALSE) {
                   $recordCount = 0;
                   $recordCount_forPassword = 0;
              while (($data = fgetcsv($handle, ",")) !== FALSE) {
                  $customer_name = $data[0];

                  $data['15'];


                  $client_object = Client::model()->findByAttributes([
                      'fullname'=>$customer_name,
                      'company_branch_id'=>18
                  ]);


                  if($client_object){
                      $client_object->zone_id =  $data['15'];
                      $client_object->save();
                  }



              }
          }

    }
    public function actionSet_schedual_for_afentaste(){

            die('lock');
          $file = realpath(Yii::app()->basePath.'/master_file_date/safe/product_schedual.csv');



          if (($handle = fopen($file, "r")) !== FALSE) {
                   $recordCount = 0;
                   $recordCount_forPassword = 0;
              while (($data = fgetcsv($handle, ",")) !== FALSE) {
                  $recordCount_forPassword++;

                   $customer_name = $data[0];



                   $client_object = Client::model()->findByAttributes([
                       'fullname'=>$customer_name,
                       'company_branch_id'=>18
                   ]);


                  if($client_object){



                      $client_id = $client_object['client_id'];

                      $cleintProductFrequency = New ClientProductFrequency();
                      $cleintProductFrequency->client_id = $client_id;
                      $cleintProductFrequency->frequency_id = 1;
                      $cleintProductFrequency->product_id = 68;
                      $cleintProductFrequency->quantity = 0;
                      $cleintProductFrequency->total_rate = intval(0);
                      $cleintProductFrequency->orderStartDate = date("y-m-d");

                      if($cleintProductFrequency->save()){
                          $client_product_frequency_id =  $cleintProductFrequency->client_product_frequency;
                          $weekDay = 1;
                          for($weekDay ; $weekDay<=7 ;$weekDay++){

                               $day_number=$weekDay+4;

                               if($weekDay==7){
                                   $day_number=4;
                               }


                              $client_product_frequency_quantity_object =new ClientProductFrequencyQuantity();
                              $client_product_frequency_quantity_object->client_product_frequency_id = $client_product_frequency_id;
                              $client_product_frequency_quantity_object->frequency_id = $weekDay;
                              $client_product_frequency_quantity_object->quantity = $data[$day_number];
                              $client_product_frequency_quantity_object->preferred_time_id =1;
                              $client_product_frequency_quantity_object->isSelected =1;
                              if($client_product_frequency_quantity_object->save()){

                              }else{
                                 // var_dump($client_product_frequency_quantity_object->getErrors());

                              }
                          }

                         // die('saved');
                      }
                  }


              }
          }

    }

    public function actionSet_create_date(){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT c.client_id from client as c ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

        foreach ($queryResult as $value){

              $client_id = $value['client_id'];

             $querydelivery  ="SELECT * from delivery AS d
             WHERE d.client_id = '$client_id'
             LIMIT 1";
            $delivery_Result =  Yii::app()->db->createCommand($querydelivery)->queryAll();
             if(sizeof($delivery_Result)>0){
               $delivery_date = $delivery_Result[0]['date'];
               $client_object =Client::model()->findByPk(intval($client_id));
                $client_object->new_create_date =$delivery_date;
                if($client_object->save()){
                    echo  $client_id;
                    echo "<br>";

                }else{
                }
             }
        }

    }

    public function actioncustomerlist_for_raej(){


        $file = realpath(Yii::app()->basePath.'/master_file_date/raej/customer_master_list_csv.csv');


        if (($handle = fopen($file, "r")) !== FALSE) {
            $recordCount = 0;
            $recordCount_forPassword = 0;
            while (($data = fgetcsv($handle, ",")) !== FALSE) {

                $recordCount_forPassword++;
                $newDate = date("Y-m-d H:i:s");
                $fullname = $data[1];
                $cell_no_1 = str_replace("'","",$data[9]);
                $cell_no_2 =$data[10];
                $full_address = $data[3].$data[4].$data[6];
                $block = $data[5];
                $block_id =import_master_data::master_block_name_save($block,19);
                $zone = $data[7];
                $zone_id =import_master_data::save_zone_name($zone,19);
                $delivery_time = $data[11];
                $customer_category_id =import_master_data::save_customer_category($delivery_time,19);



                $opening_date = $data[16];

                $customerObject = New Client();
                $customerObject->user_id = 2;
                $customerObject->company_branch_id = 19;
                $customerObject->zone_id = $zone_id;
                $customerObject->block_id = $block_id;
                $customerObject->fullname = $fullname;
                $customerObject->customer_category_id = $customer_category_id;
                $customerObject->userName = $fullname.$recordCount_forPassword."19";
                $customerObject->password = "12345";
                $customerObject->cell_no_1 = $cell_no_1;
                $customerObject->cell_no_2 = $cell_no_2;
                $customerObject->city = '' ;
                $customerObject->address = $full_address ;
                $customerObject->created_by = 2 ;
                $customerObject->updated_by = 2 ;
                $customerObject->daily_delivery_sms = 1;
                $customerObject->alert_new_product = 1;
                $customerObject->created_at =$opening_date;

                if($customerObject->save()){

                }else{
                    echo "<pre>";
                    print_r($customerObject->getErrors());
                    die();
                }


            }
        }

    }
    public function actioncustomerlist_of_apprial_delivery_for_raej(){

         die('locked');
        $file = realpath(Yii::app()->basePath.'/master_file_date/raej/delivery_20_to_30_april_of_raej.csv');
        if (($handle = fopen($file, "r")) !== FALSE) {
            $recordCount = 0;
            $recordCount_forPassword = 0;
            while (($data = fgetcsv($handle, ",")) !== FALSE) {




                 $customer_name =  (explode("-",$data[0]));

                $client_name ='';
                if(isset($customer_name[1])){
                    $client_name =   $customer_name[1];
                }



                $query = "SELECT  * FROM client AS c
                    WHERE c.fullname like '%$client_name%' 
                    and c.company_branch_id=19 ";

                $client_object =  Yii::app()->db->createCommand($query)->queryRow();

                if($client_object){
                      $client_id = $client_object['client_id'];

                       $client_product_object = ClientProductPrice::model()
                           ->findByAttributes([
                               'client_id'=>$client_id
                           ]);
                       $new_rate = 130;
                       if($client_product_object){
                            $new_rate = $client_product_object['price'];

                       }


                    foreach($data as $key=>$value){
                       if($key>0){
                         if(intval($value)>0){

                              if($key==10){
                                  $selected_date = '2020-04-30';
                              }else{
                                  $selected_date = '2020-04-2'.$key;
                              }


                              $delivery = new Delivery();
                              $delivery->company_branch_id =19;
                              $delivery->client_id =$client_id;
                              $delivery->rider_id =0;
                              $delivery->date =$selected_date;
                              $delivery->time =date("h:i:s");
                              $delivery->amount =0;
                              $delivery->discount_percentage =0;
                              $delivery->total_amount =110*$value;
                              $delivery->latitude =19;
                              $delivery->longitude =19;
                              $delivery->jv_id =20;
                              $delivery->tax_percentage =0;
                              $delivery->amount_with_tax =0;
                              if($delivery->save()){
                                  $delivery_id = $delivery->delivery_id;
                                  $object = New DeliveryDetail();
                                  $object->delivery_id = $delivery_id;
                                  $object->product_id = 84;
                                  $object->date =$selected_date;
                                  $object->quantity = $value;
                                  $object->amount = 110*$value;
                                  $object->jv_id = 0;
                                  if($object->save()){
                                  }else{
                                      echo "<pre>";
                                      print_r($object->getErrors());
                                      die();
                                  }


                              }else{
                                  echo "<pre>";
                                  print_r($delivery);
                                  die();
                              }

                         }
                       }
                    }

                }else{
                    echo $client_name."<br>";
                }



            }
        }

    }
    public function actioncustomerlist_update_address_for_raej(){


        $file = realpath(Yii::app()->basePath.'/master_file_date/raej/customer_master_list_csv.csv');

        if (($handle = fopen($file, "r")) !== FALSE) {
            $recordCount = 0;
            $recordCount_forPassword = 0;
            while (($data = fgetcsv($handle, ",")) !== FALSE) {




                $client_name =   $data[1];
                 $new_name =   $data[0]."-".$data[1];


                $query = "SELECT  * FROM client AS c
                    WHERE c.fullname like '%$client_name%' 
                    and c.company_branch_id=19 ";

                $client_object =  Yii::app()->db->createCommand($query)->queryRow();

                if($client_object){

                    $full_address =$data[3].' '.$data[4].' '.$data[5].', '.$data[6].', '.$data[7];


                   $client_id = $client_object['client_id'];
                    $object = Client::model()->findByPk($client_id);

                    $object->address =$full_address;
                    $object->fullname =$new_name;
                    if($object->save()){

                    }else{
                        echo "<pre>";
                        print_r($client_object->getErrors());
                        die();
                    }

                }else{
                  echo   $client_name;
                   die();
                }



            }
        }

    }
    public function actioncustomerlist_update_phase_for_raej(){

               die('sdsd');
        $file = realpath(Yii::app()->basePath.'/master_file_date/raej/customer_master_list_csv.csv');

        if (($handle = fopen($file, "r")) !== FALSE) {
            $recordCount = 0;
            $recordCount_forPassword = 0;
            while (($data = fgetcsv($handle, ",")) !== FALSE) {




                $client_name =   $data[1];
                $new_name =   $data[0]."-".$data[1];




                $query = "SELECT  * FROM client AS c
                    WHERE c.fullname like '%$client_name%' 
                    and c.company_branch_id=19 ";

                $client_object =  Yii::app()->db->createCommand($query)->queryRow();

                if($client_object){

                    $full_address =$data[3].' '.$data[4].' '.$data[5].', '.$data[6].', '.$data[7];

                    $phase = $data[6];
                   $client_id = $client_object['client_id'];
                    $object = Client::model()->findByPk($client_id);

                    $object->phase =$phase;

                    if($object->save()){

                    }else{
                        echo "<pre>";
                        print_r($client_object->getErrors());
                        die();
                    }

                }else{
                  echo   $client_name;
                   die();
                }



            }
        }

    }
    public function actioncustomerlist_update_rate_for_raej(){


        $file = realpath(Yii::app()->basePath.'/master_file_date/raej/delivery_march_for_raej.csv');

        if (($handle = fopen($file, "r")) !== FALSE) {
            $recordCount = 0;
            $recordCount_forPassword = 0;
            while (($data = fgetcsv($handle, ",")) !== FALSE) {

                $client_name =   $data[0];
                $new_rate =   $data[1];
               if($new_rate>0){
                   $query = "SELECT  * FROM client AS c
                    WHERE c.fullname like '%$client_name%' 
                    and c.company_branch_id=19 ";

                   $client_object =  Yii::app()->db->createCommand($query)->queryRow();

                   if($client_object){
                       $client_id = $client_object['client_id'];

                       $query = "SELECT 
                    d.delivery_id
                    FROM delivery AS d
                    WHERE d.date 
                    BETWEEN '2020-03-01' AND '2020-03-31' 
                    AND d.client_id ='$client_id' ";

                       $client_object =  Yii::app()->db->createCommand($query)->queryAll();

                       foreach ($client_object as $value){
                           $delivery_id = $value['delivery_id'];

                           $deliveryDetail = DeliveryDetail::model()
                               ->findByAttributes([
                                   'delivery_id'=>$delivery_id
                               ]);

                           $new_amount = $deliveryDetail['quantity'] * $new_rate;
                           $deliveryDetail->amount =$new_amount;
                           $deliveryDetail->save();

                           $delivery = Delivery::model()->findByPk(intval($delivery_id));

                           $delivery->total_amount = $new_amount;
                           $delivery->save();

                       }

                   }else{
                       echo   $client_name."<br>";

                   }
               }





            }
        }

    }
    public function actionchange_parmananet_rate(){


        $file = realpath(Yii::app()->basePath.'/master_file_date/raej/delivery_march_for_raej.csv');

        if (($handle = fopen($file, "r")) !== FALSE) {
            $recordCount = 0;
            $recordCount_forPassword = 0;
            while (($data = fgetcsv($handle, ",")) !== FALSE) {

                $client_name =   $data[0];
                $new_rate =   $data[1];
               if($new_rate>0){
                   $query = "SELECT  * FROM client AS c
                    WHERE c.fullname like '%$client_name%' 
                    and c.company_branch_id=19 ";

                   $client_object =  Yii::app()->db->createCommand($query)->queryRow();

                   if($client_object){
                        $client_id = $client_object['client_id'];

                        $object = new ClientProductPrice();

                        $object->client_id =$client_id;
                        $object->product_id =84;
                        $object->price =$new_rate;
                       $object->save();



                   }else{
                       echo   $client_name."<br>";

                   }
               }





            }
        }

    }
    public function actioncustomerlist_of_make_payment_for_raej(){


            die("ocked");
        $file = realpath(Yii::app()->basePath.'/master_file_date/raej/payment_10_march_20_march.csv');
        if (($handle = fopen($file, "r")) !== FALSE) {
            $recordCount = 0;
            $recordCount_forPassword = 0;
            while (($data = fgetcsv($handle, ",")) !== FALSE) {
              //  $customer_name =  (explode("-",$data[0]));
                $client_name =   $data[0];
                 $payment =   $data[2];
                $query = "SELECT  * FROM client AS c
                    WHERE c.fullname like '%$client_name%' 
                    and c.company_branch_id=19 ";

                $client_object =  Yii::app()->db->createCommand($query)->queryRow();

                if($client_object){
                       $client_id = $client_object['client_id'];
                       if(intval($payment)!=0){

                            $object = New PaymentMaster();
                            $object->client_id = $client_id;
                            $object->company_branch_id = 19;
                            $object->date = '2020-03-20';
                            $object->time = '00:00';
                            $object->payment_mode =3;
                            $object->amount_paid =$payment;
                            $object->remarks ='20 march payment';
                            $object->bill_month_date ='2020-03-20';
                            if($object->save()){

                            }else{
                                echo "<pre>";
                                print_r($object->getErrors());
                                die();
                            }
                       }

                }else{

                }



            }
        }

    }

    public function actioncopy_data(){
          die("locked");
        $query = "SELECT  * FROM client AS c
                    WHERE c.company_branch_id=18";

        $client_object =  Yii::app()->db->createCommand($query)->queryAll();


        foreach($client_object as $value){
           $object = new Client();
           $client_id = $value['client_id'];
           $object->user_id=60;
           $object->company_branch_id=20;
           $object->zone_id=1174;
           $object->fullname=$value['fullname'];
           $object->userName=$value['userName'];
           $object->password=$value['password'];
           $object->cell_no_1=$value['cell_no_1'];
           $object->cell_no_2=$value['cell_no_2'];
           $object->address=$value['address'];
           $object->daily_delivery_sms=$value['daily_delivery_sms'];
           $object->deactive_reason=$value['deactive_reason'];

            $object->created_by = 2 ;
            $object->updated_by = 2 ;
            $object->daily_delivery_sms = 1;
            $object->alert_new_product = 1;
            $object->created_at =$value['created_at'];

           if($object->save()){
                  $client_id_new =$object->client_id;

                  $client_product_frequency_object = ClientProductFrequency::model()
                      ->findByAttributes([
                          'client_id'=>$client_id,
                          'product_id'=>79
                      ]);

                  $client_product_frequency = $client_product_frequency_object['client_product_frequency'];

                  $client_product_frequency_quantity = ClientProductFrequencyQuantity::model()
                      ->findAllByAttributes([
                          'client_product_frequency_id'=>$client_product_frequency
                      ]);


                  $new_client_product_frequency = New ClientProductFrequency();

                 $new_client_product_frequency->client_id = $client_id_new;
                 $new_client_product_frequency->frequency_id = 1;
                 $new_client_product_frequency->quantity = 1;
                 $new_client_product_frequency->product_id =85;
                 $new_client_product_frequency->total_rate = 0;
                 $new_client_product_frequency->orderStartDate = date("Y-m-d");
                 if($new_client_product_frequency->save()){
                     $client_product_frequency_new =  $new_client_product_frequency->client_product_frequency;
                     foreach($client_product_frequency_quantity as $value){
                         $quantity_object = New ClientProductFrequencyQuantity();

                         $quantity_object->client_product_frequency_id = $client_product_frequency_new;
                         $quantity_object->frequency_id = $value['frequency_id'];
                         $quantity_object->quantity = $value['quantity'];
                         $quantity_object->preferred_time_id = $value['preferred_time_id'];
                         $quantity_object->isSelected = $value['isSelected'];
                         if($quantity_object->save()){

                         }else{
                            echo "<pre>";
                            print_r($quantity_object->getErrors());
                            die();
                         }
                     }

                 }else{
                     echo "<pre>";
                     print_r($new_client_product_frequency->getErrors());
                     die();
                 }

           }else{
               echo "<pre>";
               print_r($object->getErrors());
               die();
           }

        }

    }


    public function actioncustomerlist_for_alla(){


        $file = realpath(Yii::app()->basePath.'/master_file_date/aala_company/alla_company.csv');




        if (($handle = fopen($file, "r")) !== FALSE) {
            $recordCount = 0;
            $recordCount_forPassword = 0;
            while (($data = fgetcsv($handle, ",")) !== FALSE) {



                $recordCount_forPassword++;
                $newDate = date("Y-m-d H:i:s");
                $fullname = $data[1];
                $user_name = $data[2];
                $password= $data[3];
                $father_name= $data[4];
                $address= $data[6]." ".$data[7]." ".$data[8]." ".$data[9];
               // $cell_no_1 = str_replace("'","",$data[9]);
                $cell_no_1 = $data[12];
                $cell_no_2 =$data[13];
                $email =$data[15];
                $zone_id ='1202';

                $new_price = $data['20'];

                $customerObject = New Client();
                $customerObject->user_id = 2;
                $customerObject->company_branch_id = 23;
                $customerObject->zone_id = $zone_id;
                $customerObject->block_id = 0;
                $customerObject->fullname = $fullname;
                $customerObject->customer_category_id = 0;
                $customerObject->userName = $user_name;
                $customerObject->password = $password;
                $customerObject->cell_no_1 = $cell_no_1;
                $customerObject->cell_no_2 = $cell_no_2;
                $customerObject->city = '' ;
                $customerObject->address = $address ;
                $customerObject->created_by = 2 ;
                $customerObject->updated_by = 2 ;
                $customerObject->daily_delivery_sms = 1;
                $customerObject->alert_new_product = 1;
                $customerObject->created_at ='2020-06-23 20:33:03';

                if($customerObject->save()){

                    $client_id =  $customerObject->client_id;

                }else{
                    echo "<pre>";
                    print_r($customerObject->getErrors());
                    die();
                }


            }
        }

    }

    public function actionUpdate_bill_for_month(){
          $query = "SELECT * FROM `payment_master` WHERE `bill_month_date` = '0000-00-00' ORDER BY `bill_month_date` ASC ";

          $result =  Yii::app()->db->createCommand($query)->queryAll();

         foreach ($result as $value){
            $payment_master_id = $value['payment_master_id'];

            $date = $value['date'];
            $object = PaymentMaster::model()->findByPk($payment_master_id);
            $object->bill_month_date =$date;
             if($object->save()){

             }else{
                 echo "<pre>";
                 print_r($object->getErrors());
                 die();
             }

         }
    }



}