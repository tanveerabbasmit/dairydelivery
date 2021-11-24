<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 10/19/2017
 * Time: 11:04 AM
 */
class PurevilleController extends Controller
{


      public function actionimportBlock(){
          die("");

            $file = realpath(Yii::app()->basePath.'/master_file_date/pureville/block.csv');

          if (($handle = fopen($file, "r")) !== FALSE) {
                   $recordCount = 0;
              while (($data = fgetcsv($handle, ",")) !== FALSE){

                    $block_name = $data[0];
                    $block_object =Block::model()->findByAttributes(array(
                     'block_name'=>$block_name
                    ));
                    if($block_object){

                    }else{
                        $block = New Block();
                        $block->block_name = $block_name;
                        $block->company_id = 14;
                        $block->save();

                        $recordCount=$recordCount+1;
                    }
              }

          }
       echo   $recordCount." record are added";
      }
      public function actionimportArea(){

                   die("");
            $file = realpath(Yii::app()->basePath.'/master_file_date/pureville/area.csv');

          if (($handle = fopen($file, "r")) !== FALSE) {
                   $recordCount = 0;
              while (($data = fgetcsv($handle, ",")) !== FALSE){

                    $area_name = $data[0];
                    $block_object =Area::model()->findByAttributes(array(
                     'area_name'=>$area_name
                    ));
                    if($block_object){

                    }else{
                        $area = New Area();
                        $area->area_name = $area_name;
                        $area->company_id = 14;
                        $area->save();

                        $recordCount=$recordCount+1;
                    }
              }

          }
       echo   $recordCount." record are added";
      }
      public function actionimportCustomer(){

                die("remove");
            $file = realpath(Yii::app()->basePath.'/master_file_date/pureville/active_regular_customer.csv');

          if (($handle = fopen($file, "r")) !== FALSE) {
                   $recordCount = 0;
              while (($data = fgetcsv($handle, ",")) !== FALSE){

                $house_no = $data[0];
                $sub_no = $data[1];
                $block = $data[2];
                $area = $data[3];
                $zone = $data[4];
                $fullname = $house_no.' '.$sub_no;
                $number = (str_replace("-","", $data[5]));
                $num=$number;

                if(substr($num, 0, 2) == "03"){
                  $number = '+923' . substr($num, 2);
                }else if(substr($num, 0, 1) == "3"){
                  $number = '+923' . substr($num, 1);
                }

                $clientOBject = new Client();
                $clientOBject->house_no =$house_no;
                $clientOBject->sub_no =$sub_no;

                 $blockObjetc = Block::model()->findByAttributes(array(
                     'block_name'=>$block
                 ));

                 if($blockObjetc){
                      $blockObjetc =$blockObjetc->attributes;

                      $block_id = $blockObjetc['block_id'];
                 }else{
                   $block_id =36;
                  }



                 $areaObject = Area::model()->findByAttributes(array(
                     'area_name'=>$area
                 ));
                 if($areaObject){
                     $areaObject = $areaObject->attributes;
                     $area_id = $areaObject['area_id'];
                 }else{
                     $area_id =10;
                 }


                 $clientOBject->block_id = $block_id;
                 $clientOBject->user_id = 2;
                 $clientOBject->created_by = 2;

                 $clientOBject->fullname = $fullname;

                 $clientOBject->area_id = $area_id;

                  $zoneObject = Zone::model()->findByAttributes(array(
                      'name'=>$zone
                  ));
                  if($zoneObject){
                       if(isset($zoneObject->attributes)){
                           $zoneObject =  $zoneObject->attributes;
                       }else{
                           echo $zone;
                            die();
                       }


                  }else{
                       echo $zone;
                      die("herone");
                  }
                 $zone_id = $zoneObject['zone_id'];

                 $clientOBject->zone_id = $zone_id;
                 $clientOBject->is_active = 1;
                 $clientOBject->client_type = 1;
                 $clientOBject->company_branch_id = 14;
                 if($clientOBject->save()){
                     $recordCount++;
                 }else{
                     var_dump($clientOBject->getErrors());
                 }




              }

          }
       echo   $recordCount." record are added";
      }
      public function actionimportactioveCustomer(){

            $file = realpath(Yii::app()->basePath.'/master_file_date/pureville/file/active_custoer.csv');

          if (($handle = fopen($file, "r")) !== FALSE) {
                   $recordCount = 0;
              while (($data = fgetcsv($handle, ",")) !== FALSE){



                $house_no = $data[0];
                $sub_no = $data[1];
                $block = $data[2];
                $area = $data[3];
                $zone = $data[4];
                $fullname = $house_no.' '.$sub_no.' '.$block.' '.$area;
              //  $fullname = "tanveer mit";
                $number = (str_replace("-","", $data[5]));
                $num=$number;

                if(substr($num, 0, 2) == "03"){
                  $number = '+923' . substr($num, 2);
                }else if(substr($num, 0, 1) == "3"){
                  $number = '+923' . substr($num, 1);
                }

                $clientOBject = new Client();
                $clientOBject->house_no =$house_no;
                $clientOBject->sub_no =$sub_no;

                 $blockObjetc = Block::model()->findByAttributes(array(
                     'block_name'=>$block
                 ));

                 if($blockObjetc){
                      $blockObjetc =$blockObjetc->attributes;

                      $block_id = $blockObjetc['block_id'];
                 }else{
                   $block_id =36;
                  }



                 $areaObject = Area::model()->findByAttributes(array(
                     'area_name'=>$area
                 ));
                 if($areaObject){
                     $areaObject = $areaObject->attributes;
                     $area_id = $areaObject['area_id'];
                 }else{
                     $area_id =10;
                 }


                 $clientOBject->block_id = $block_id;
                 $clientOBject->user_id = 2;
                 $clientOBject->created_by = 2;

                 $clientOBject->fullname = $fullname;

                 $clientOBject->area_id = $area_id;

                  $zoneObject = Zone::model()->findByAttributes(array(
                      'name'=>$zone
                  ));
                  if($zoneObject){
                       if(isset($zoneObject->attributes)){
                           $zoneObject =  $zoneObject->attributes;
                       }else{
                           echo $zone;
                            die();
                       }


                  }else{

                  }
                 $zone_id = $zoneObject['zone_id'];

                 $clientOBject->zone_id = $zone_id;
                 $clientOBject->is_active = 1;
                 $clientOBject->client_type = 1;
                 $clientOBject->company_branch_id = 14;
                 if($clientOBject->save()){
                      $client_id = $clientOBject->client_id;

                      PurevilleController::actionupdateDelivery($client_id ,$data);

                      $openingBlance =$data[7];

                     $maymentMaster =new PaymentMaster();
                     $maymentMaster->client_id =$client_id ;
                     $maymentMaster->company_branch_id =14;
                     $maymentMaster->date ='2018-12-15';
                     $maymentMaster->payment_mode =3;
                     $maymentMaster->amount_paid =-($openingBlance);
                     $maymentMaster->bill_month_date ='2018-12-15';
                     $maymentMaster->time ='4:15';
                     $maymentMaster->remarks ='No marks';
                     if($maymentMaster->save()){
                         echo "save";
                     }else{
                         var_dump($maymentMaster->getErrors());
                     }
                     if(is_numeric($data[14])){

                         $maymentMaster =new PaymentMaster();
                         $maymentMaster->client_id =$client_id ;
                         $maymentMaster->company_branch_id =14;
                         $maymentMaster->date ='2018-12-16';
                         $maymentMaster->payment_mode =3;
                         $maymentMaster->amount_paid =$data[14];
                         $maymentMaster->bill_month_date ='2018-12-15';
                         $maymentMaster->time ='4:15';
                         $maymentMaster->remarks ='No marks';
                         if($maymentMaster->save()){
                             echo "save";
                         }else{
                             var_dump($maymentMaster->getErrors());
                         }

                     }
                     if(is_numeric($data[15])){

                         $maymentMaster =new PaymentMaster();
                         $maymentMaster->client_id =$client_id ;
                         $maymentMaster->company_branch_id =14;
                         $maymentMaster->date ='2018-12-17';
                         $maymentMaster->payment_mode =3;
                         $maymentMaster->amount_paid =$data[15];
                         $maymentMaster->bill_month_date ='2018-12-15';
                         $maymentMaster->time ='4:15';
                         $maymentMaster->remarks ='No marks';
                         if($maymentMaster->save()){
                             echo "save";
                         }else{
                             var_dump($maymentMaster->getErrors());
                         }

                     }
                     if(is_numeric($data[16])){

                         $maymentMaster =new PaymentMaster();
                         $maymentMaster->client_id =$client_id ;
                         $maymentMaster->company_branch_id =14;
                         $maymentMaster->date ='2018-12-18';
                         $maymentMaster->payment_mode =3;
                         $maymentMaster->amount_paid =$data[16];
                         $maymentMaster->bill_month_date ='2018-12-15';
                         $maymentMaster->time ='4:15';
                         $maymentMaster->remarks ='No marks';
                         if($maymentMaster->save()){
                             echo "save";
                         }else{
                             var_dump($maymentMaster->getErrors());
                         }

                     }
                     if(is_numeric($data[17])){

                         $maymentMaster =new PaymentMaster();
                         $maymentMaster->client_id =$client_id ;
                         $maymentMaster->company_branch_id =14;
                         $maymentMaster->date ='2018-12-19';
                         $maymentMaster->payment_mode =3;
                         $maymentMaster->amount_paid =$data[17];
                         $maymentMaster->bill_month_date ='2018-12-15';
                         $maymentMaster->time ='4:15';
                         $maymentMaster->remarks ='No marks';
                         if($maymentMaster->save()){
                             echo "save";
                         }else{
                             var_dump($maymentMaster->getErrors());
                         }

                     }
                     if(is_numeric($data[18])){

                         $maymentMaster =new PaymentMaster();
                         $maymentMaster->client_id =$client_id ;
                         $maymentMaster->company_branch_id =14;
                         $maymentMaster->date ='2018-12-20';
                         $maymentMaster->payment_mode =3;
                         $maymentMaster->amount_paid =$data[18];
                         $maymentMaster->bill_month_date ='2018-12-15';
                         $maymentMaster->time ='4:15';
                         $maymentMaster->remarks ='No marks';
                         if($maymentMaster->save()){
                             echo "save";
                         }else{
                             var_dump($maymentMaster->getErrors());
                         }

                     }
                     if(is_numeric($data[19])){

                         $maymentMaster =new PaymentMaster();
                         $maymentMaster->client_id =$client_id ;
                         $maymentMaster->company_branch_id =14;
                         $maymentMaster->date ='2018-12-21';
                         $maymentMaster->payment_mode =3;
                         $maymentMaster->amount_paid =$data[19];
                         $maymentMaster->bill_month_date ='2018-12-01';
                         $maymentMaster->time ='4:15';
                         $maymentMaster->remarks ='No marks';
                         if($maymentMaster->save()){
                             echo "save";
                         }else{
                             var_dump($maymentMaster->getErrors());
                         }

                     }


                     $recordCount++;
                 }else{
                     var_dump($clientOBject->getErrors());
                 }


              }

          }
       echo   $recordCount." record are added";
      }

       public static function actionupdateDelivery($client_id ,$data){
          echo $client_id;
           $product_id =64;
           $rider_id =183 ;
           $rate =100;
           $check_payment =false;

           die('1');

           if(is_numeric($data[8])){
               $date = '2018-12-16';
               $quantity = $data[8];
               $amount = $data[8] *100;
               $deliveryobject = new Delivery();

               $deliveryobject->company_branch_id ='14';
               $deliveryobject->client_id =$client_id;
               $deliveryobject->rider_id =$rider_id;
               $deliveryobject->date =$date;
               $deliveryobject->time ='4:15';
               $deliveryobject->total_amount =$amount;
               $deliveryobject->tax_percentage ='0';
               $deliveryobject->amount_with_tax ='0';
               $deliveryobject->amount ='0';
               $deliveryobject->discount_percentage ='0';

               if($deliveryobject->save()){
                   $delivery_id  = $deliveryobject->delivery_id;

                   $deliveryDetail = new DeliveryDetail();
                   $deliveryDetail->delivery_id=$delivery_id;
                   $deliveryDetail->product_id=$product_id;
                   $deliveryDetail->date=$date;
                   $deliveryDetail->amount=$amount;
                   $deliveryDetail->quantity=$quantity;
                   $deliveryDetail->adjust_amount=$amount;
                    if($deliveryDetail->save()){

                    }else{
                        var_dump($deliveryDetail->getErrors());
                    }
               }else{
                   var_dump($deliveryobject->getErrors());
               }




           }
           if(is_numeric($data[9])){
               $date = '2018-12-17';
               $quantity = $data[9];
               $amount = $data[9] *100;
               $deliveryobject = new Delivery();

               $deliveryobject->company_branch_id ='14';
               $deliveryobject->client_id =$client_id;
               $deliveryobject->rider_id =$rider_id;
               $deliveryobject->date =$date;
               $deliveryobject->time ='4:15';
               $deliveryobject->total_amount =$amount;
               $deliveryobject->tax_percentage ='0';
               $deliveryobject->amount_with_tax ='0';
               $deliveryobject->amount ='0';
               $deliveryobject->discount_percentage ='0';

               if($deliveryobject->save()){
                   $delivery_id  = $deliveryobject->delivery_id;
                   $deliveryDetail = new DeliveryDetail();
                   $deliveryDetail->delivery_id=$delivery_id;
                   $deliveryDetail->product_id=$product_id;
                   $deliveryDetail->date=$date;
                   $deliveryDetail->amount=$amount;
                   $deliveryDetail->quantity=$quantity;
                   $deliveryDetail->adjust_amount=$amount;
                   if($deliveryDetail->save()){

                   }else{
                       var_dump($deliveryDetail->getErrors());
                   }
               }else{
                   var_dump($deliveryobject->getErrors());
               }




           }
           if(is_numeric($data[10])){
               $date = '2018-12-18';
               $quantity = $data[10];
               $amount = $data[10] *100;
               $deliveryobject = new Delivery();

               $deliveryobject->company_branch_id ='14';
               $deliveryobject->client_id =$client_id;
               $deliveryobject->rider_id =$rider_id;
               $deliveryobject->date =$date;
               $deliveryobject->time ='4:15';
               $deliveryobject->total_amount =$amount;
               $deliveryobject->tax_percentage ='0';
               $deliveryobject->amount_with_tax ='0';
               $deliveryobject->amount ='0';
               $deliveryobject->discount_percentage ='0';

               if($deliveryobject->save()){
                   $delivery_id  = $deliveryobject->delivery_id;
                   $deliveryDetail = new DeliveryDetail();
                   $deliveryDetail->delivery_id=$delivery_id;
                   $deliveryDetail->product_id=$product_id;
                   $deliveryDetail->date=$date;
                   $deliveryDetail->amount=$amount;
                   $deliveryDetail->quantity=$quantity;
                   $deliveryDetail->adjust_amount=$amount;
                   if($deliveryDetail->save()){

                   }else{
                       var_dump($deliveryDetail->getErrors());
                   }
               }else{
                   var_dump($deliveryobject->getErrors());
               }




           }
           if(is_numeric($data[11])){
               $date = '2018-12-19';
               $quantity = $data[11];
               $amount = $data[11] *100;
               $deliveryobject = new Delivery();

               $deliveryobject->company_branch_id ='14';
               $deliveryobject->client_id =$client_id;
               $deliveryobject->rider_id =$rider_id;
               $deliveryobject->date =$date;
               $deliveryobject->time ='4:15';
               $deliveryobject->total_amount =$amount;
               $deliveryobject->tax_percentage ='0';
               $deliveryobject->amount_with_tax ='0';
               $deliveryobject->amount ='0';
               $deliveryobject->discount_percentage ='0';

               if($deliveryobject->save()){
                   $delivery_id  = $deliveryobject->delivery_id;
                   $deliveryDetail = new DeliveryDetail();
                   $deliveryDetail->delivery_id=$delivery_id;
                   $deliveryDetail->product_id=$product_id;
                   $deliveryDetail->date=$date;
                   $deliveryDetail->amount=$amount;
                   $deliveryDetail->quantity=$quantity;
                   $deliveryDetail->adjust_amount=$amount;
                   if($deliveryDetail->save()){

                   }else{
                       var_dump($deliveryDetail->getErrors());
                   }
               }else{
                   var_dump($deliveryobject->getErrors());
               }




           }
           if(is_numeric($data[12])){
               $date = '2018-12-20';
               $quantity = $data[12];
               $amount = $data[12] *100;
               $deliveryobject = new Delivery();

               $deliveryobject->company_branch_id ='14';
               $deliveryobject->client_id =$client_id;
               $deliveryobject->rider_id =$rider_id;
               $deliveryobject->date =$date;
               $deliveryobject->time ='4:15';
               $deliveryobject->total_amount =$amount;
               $deliveryobject->tax_percentage ='0';
               $deliveryobject->amount_with_tax ='0';
               $deliveryobject->amount ='0';
               $deliveryobject->discount_percentage ='0';

               if($deliveryobject->save()){
                   $delivery_id  = $deliveryobject->delivery_id;
                   $deliveryDetail = new DeliveryDetail();
                   $deliveryDetail->delivery_id=$delivery_id;
                   $deliveryDetail->product_id=$product_id;
                   $deliveryDetail->date=$date;
                   $deliveryDetail->amount=$amount;
                   $deliveryDetail->quantity=$quantity;
                   $deliveryDetail->adjust_amount=$amount;
                   if($deliveryDetail->save()){

                   }else{
                       var_dump($deliveryDetail->getErrors());
                   }
               }else{
                   var_dump($deliveryobject->getErrors());
               }




           }
           if(is_numeric($data[13])){
               $date = '2018-12-21';
               $quantity = $data[13];
               $amount = $data[13] *100;
               $deliveryobject = new Delivery();

               $deliveryobject->company_branch_id ='14';
               $deliveryobject->client_id =$client_id;
               $deliveryobject->rider_id =$rider_id;
               $deliveryobject->date =$date;
               $deliveryobject->time ='4:15';
               $deliveryobject->total_amount =$amount;
               $deliveryobject->tax_percentage ='0';
               $deliveryobject->amount_with_tax ='0';
               $deliveryobject->amount ='0';
               $deliveryobject->discount_percentage ='0';

               if($deliveryobject->save()){
                   $delivery_id  = $deliveryobject->delivery_id;
                   $deliveryDetail = new DeliveryDetail();
                   $deliveryDetail->delivery_id=$delivery_id;
                   $deliveryDetail->product_id=$product_id;
                   $deliveryDetail->date=$date;
                   $deliveryDetail->amount=$amount;
                   $deliveryDetail->quantity=$quantity;
                   $deliveryDetail->adjust_amount=$amount;
                   if($deliveryDetail->save()){

                   }else{
                       var_dump($deliveryDetail->getErrors());
                   }
               }else{
                   var_dump($deliveryobject->getErrors());
               }




           }

           echo "<br>";


       }

    public function actionimportDeactiveCustomer_reason(){


        $file = realpath(Yii::app()->basePath.'/master_file_date/pureville/file/deactiveCustomer.csv');

        if (($handle = fopen($file, "r")) !== FALSE) {
            $recordCount = 0;
            while (($data = fgetcsv($handle, ",")) !== FALSE){

                $reason_type =  $data[5];
                $deactiveReson = SampleClientDropReason::model()->findByAttributes(
                    array(
                        'reason'=>$reason_type
                    )
                );

                if($deactiveReson){
                    echo $reason_type."<br>" ;
                }else{

                    $object =new SampleClientDropReason();
                    $object->reason = $reason_type;
                    $object->company_branch_id=14;
                    $object->save();

                }

              /*  if($clientOBject->save()){
                    $recordCount++;
                }else{
                    var_dump($clientOBject->getErrors());
                }*/




            }

        }
        echo   $recordCount." record are added";
    }
    public function actionimportActiveSampleCustomer(){
                die('1');

        $file = realpath(Yii::app()->basePath.'/master_file_date/pureville/file/active_sample.csv');

        if (($handle = fopen($file, "r")) !== FALSE) {
            $recordCount = 0;
            while (($data = fgetcsv($handle, ",")) !== FALSE){

                /* echo "<pre>";
                 print_r($data);
                 die();*/
                $house_no = $data[0];
                $sub_no = $data[1];
                $block = $data[2];
                $area = $data[3];

                $fullname = $house_no.' '.$sub_no.' '.$block.' '.$area;
                $number = (str_replace("-","", $data[4]));
                $num=$number;

                if(substr($num, 0, 2) == "03"){
                    $number = '+923' . substr($num, 2);
                }else if(substr($num, 0, 1) == "3"){
                    $number = '+923' . substr($num, 1);
                }

                $clientOBject = new Client();
                $clientOBject->house_no =$house_no;
                $clientOBject->sub_no =$sub_no;

                $blockObjetc = Block::model()->findByAttributes(array(
                    'block_name'=>$block
                ));

                if($blockObjetc){
                    $blockObjetc =$blockObjetc->attributes;

                    $block_id = $blockObjetc['block_id'];
                }else{
                    $block_id =36;
                }



                $areaObject = Area::model()->findByAttributes(array(
                    'area_name'=>$area
                ));
                if($areaObject){
                    $areaObject = $areaObject->attributes;
                    $area_id = $areaObject['area_id'];
                }else{
                    $area_id =10;
                }


                $clientOBject->block_id = $block_id;
                $clientOBject->user_id = 2;
                $clientOBject->created_by = 2;

                $clientOBject->fullname = $fullname;


                $clientOBject->area_id = $area_id;

               /* $zoneObject = Zone::model()->findByAttributes(array(
                    'name'=>$zone
                ));

                if($zoneObject){
                    if(isset($zoneObject->attributes)){
                        $zoneObject =  $zoneObject->attributes;
                    }else{
                        echo $zone;
                        die();
                    }


                }else{
                    echo $zone;
                    die("herone");
                }
                $zone_id = $zoneObject['zone_id'];*/

                $zone_id =633;
               // $zone_id =536;

                $clientOBject->zone_id = $zone_id;
                $clientOBject->is_active = 1;
                $clientOBject->client_type = 2;
                $clientOBject->company_branch_id = 14;
                if($clientOBject->save()){
                    $recordCount++;
                }else{
                    var_dump($clientOBject->getErrors());
                }




            }

        }
        echo   $recordCount." record are added";
    }
    public function actionimportDeactiveSampleCustomer(){

            die("her one");

        $file = realpath(Yii::app()->basePath.'/master_file_date/pureville/file/deactive_Sample.csv');

        if (($handle = fopen($file, "r")) !== FALSE) {
            $recordCount = 0;
            while (($data = fgetcsv($handle, ",")) !== FALSE){

                 $deactiveDate = date("Y-m-d", strtotime($data[0]));

                $house_no = $data[1];
                $sub_no = $data[2];
                $block = $data[3];
                $area = $data[4];

                $fullname = $house_no.' '.$sub_no.' '.$block.' '.$area;
                $number = (str_replace("-","", $data[5]));
                $num=$number;

                if(substr($num, 0, 2) == "03"){
                    $number = '+923' . substr($num, 2);
                }else if(substr($num, 0, 1) == "3"){
                    $number = '+923' . substr($num, 1);
                }

                $clientOBject = new Client();
                $clientOBject->house_no =$house_no;
                $clientOBject->sub_no =$sub_no;

                $blockObjetc = Block::model()->findByAttributes(array(
                    'block_name'=>$block
                ));

                if($blockObjetc){
                    $blockObjetc =$blockObjetc->attributes;

                    $block_id = $blockObjetc['block_id'];
                }else{
                    $block_id =36;
                }



                $areaObject = Area::model()->findByAttributes(array(
                    'area_name'=>$area
                ));
                if($areaObject){
                    $areaObject = $areaObject->attributes;
                    $area_id = $areaObject['area_id'];
                }else{
                    $area_id =10;
                }


                $clientOBject->block_id = $block_id;
                $clientOBject->user_id = 2;
                $clientOBject->created_by = 2;

                $clientOBject->fullname = $fullname;
                $clientOBject->deactive_reason = $data[7];
                $clientOBject->deactive_date = $deactiveDate;


                $clientOBject->area_id = $area_id;

               /* $zoneObject = Zone::model()->findByAttributes(array(
                    'name'=>$zone
                ));

                if($zoneObject){
                    if(isset($zoneObject->attributes)){
                        $zoneObject =  $zoneObject->attributes;
                    }else{
                        echo $zone;
                        die();
                    }


                }else{
                    echo $zone;
                    die("herone");
                }
                $zone_id = $zoneObject['zone_id'];*/

               // $zone_id =633;
                $zone_id =536;

                $clientOBject->zone_id = $zone_id;
                $clientOBject->is_active = 0;
                $clientOBject->client_type = 2;
                $clientOBject->company_branch_id = 14;
                if($clientOBject->save()){
                    $recordCount++;
                }else{
                    var_dump($clientOBject->getErrors());
                }




            }

        }
        echo   $recordCount." record are added";
    }
    public function actionsetQuantity(){
        $query="select * from delivery_detail as d
         where d.product_id ='64' ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
             $x =0;
        foreach ($queryResult as $value){
           $amount =  $value['amount'];
           $delivery_detail_id =  $value['delivery_detail_id'];
           $delivery_detail = DeliveryDetail::model()->findByPk(intval($delivery_detail_id));
            $delivery_detail->quantity = ($amount/100);
            $delivery_detail->save();
            $x++;

        }

        echo $x ;
    }


}