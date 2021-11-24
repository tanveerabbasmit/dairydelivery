<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 7/26/2017
 * Time: 12:14 PM
 */
class ManageApiController extends Controller
{
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

    public function actionSetAmount(){

        die(2);
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $client_id = $data['client_id'];
        $query = "select * from delivery as d
       left join delivery_detail as dd ON dd.delivery_id =d.delivery_id 
       where d.client_id = '$client_id' and d.date  between '2017-07-01' and '2017-08-19' " ;

        $result = Yii::app()->db->createCommand($query)->queryAll();

        foreach($result as $value){
            $quantity  = $value['quantity'];
            $Countamount = $quantity * 85 ;
            $deliveryID =$value['delivery_id'];
            $deliveryDetailID = $value['delivery_detail_id'];
            $deliveryObject = Delivery::model()->findByPk(intval($deliveryID));
            $deliveryObject->total_amount = $Countamount;
            $deliveryObject->save();
            $deliveryDetailOBject = DeliveryDetail::model()->findByPk(intval($deliveryDetailID));
            $deliveryDetailOBject->amount = $Countamount;
            $deliveryDetailOBject->save();
        }
    }


    public function actionSetamountnew(){



        $client_id = 3555;
        $startdate = '2018-07-01';
        $startenddate = '2018-07-31';
        $newPrice = 95;
        $product_id = 30;
        $query = "select * from delivery as d
       left join delivery_detail as dd ON dd.delivery_id =d.delivery_id 
       where d.client_id = '$client_id' and d.date  between '$startdate' and '$startenddate' and dd.product_id ='$product_id' " ;

        $result = Yii::app()->db->createCommand($query)->queryAll();

        //  echo '<pre>';
        //  print_r($result);
        //  echo '</pre>';
        // die();

        $count = 0;
        foreach($result as $value){
            $quantity  = $value['quantity'];
            $Countamount = $quantity * $newPrice ;
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
    public function actionSetamountnew_byExport(){



        $file = realpath(Yii::app()->basePath.'/../images/changeRate2018-12-04.csv');


        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, ",")) !== FALSE) {
                if (!is_null($data) and !empty($data)) {


                    $client_id = $data[0];
                    echo   $client_id."=";
                    $startdate = '2018-11-01';
                    $startenddate = '2018-11-30';
                   $newPrice = $data[1];

                    $product_id = 30;
                    $query = "select * from delivery as d
                    left join delivery_detail as dd ON dd.delivery_id =d.delivery_id 
                    where d.client_id = '$client_id' and d.date  between '$startdate' and '$startenddate' and dd.product_id ='$product_id' " ;
                    $result = Yii::app()->db->createCommand($query)->queryAll();
                    $count = 0;
                    foreach($result as $value){
                        $quantity  = $value['quantity'];
                        $Countamount = $quantity * $newPrice ;
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
                    echo  $count++;
                    echo '<br>';
                }
            }
        }



        echo   $count.' records updated';
    }


    public function actiondeleteDelivery(){


        die(1);

        $query_client = "SELECT *  FROM `client` WHERE `company_branch_id` = '1'  " ;

        $result_client = Yii::app()->db->createCommand($query_client)->queryAll();


        foreach ($result_client as $client){

            $client_id = $client['client_id'];

            $query = "SELECT *  FROM `delivery` WHERE `client_id` = '$client_id' and date <='2017-12-31' " ;

            $result = Yii::app()->db->createCommand($query)->queryAll();
            if($result){
                // echo  json_encode($result);
                // die();
            }

            foreach($result as $value){
                $delivery_id = $value['delivery_id'];

                DeliveryDetail::model()->deleteAllByAttributes(array('delivery_id'=>$delivery_id));

                Delivery::model()->deleteByPk(intval($delivery_id));


            }



        }


    }

    public function actiondeletePayment(){

        $query_client = "SELECT *  FROM `client` WHERE `company_branch_id` = '1'  " ;

        $result_client = Yii::app()->db->createCommand($query_client)->queryAll();


        foreach ($result_client as $client){

            $client_id = $client['client_id'];

            $query = "SELECT *  FROM `payment_master` WHERE `client_id` = '$client_id' and bill_month_date <='2017-12-31' " ;

            $result = Yii::app()->db->createCommand($query)->queryAll();




            foreach($result as $value){
                $payment_master_id= $value['payment_master_id'];

                PaymentDetail::model()->deleteAllByAttributes(array('payment_master_id'=>$payment_master_id));

                PaymentMaster::model()->deleteByPk(intval($payment_master_id));


            }

            if($result){
                //   echo json_encode($result) ;
                //  die();
            }

        }


    }


    public function actionupdateDelivery(){

        $query = "SELECT *  FROM `delivery2` WHERE `client_id` = '906' and date >'2017-12-31' " ;


        $result = Yii::app()->db->createCommand($query)->queryAll();


        $x = 0;
        foreach($result as $value){

            $delivery_id = $value['delivery_id'];

            // echo json_encode($value);

            $delivery = new Delivery();
            $delivery->company_branch_id = $value['company_branch_id'];
            $delivery->client_id = $value['client_id'];
            $delivery->rider_id = $value['rider_id'];
            $delivery->date = $value['date'];
            $delivery->time = $value['time'];
            $delivery->tax_percentage = $value['tax_percentage'];
            $delivery->amount_with_tax = $value['amount_with_tax'];
            $delivery->tax_amount = $value['tax_amount'];
            $delivery->amount = $value['amount'];
            $delivery->discount_percentage = $value['discount_percentage'];
            $delivery->total_amount = $value['total_amount'];
            $delivery->latitude = $value['latitude'];
            $delivery->longitude = $value['longitude'];
            $delivery->payment_flage = $value['payment_flage'];
            $delivery->partial_amount = $value['partial_amount'];

            $delivery->save();

            $delivery_id2 = $delivery->delivery_id;

            $query_detaile = "SELECT *  FROM `delivery_detail2` WHERE `delivery_id` = '$delivery_id' " ;


            $result_detail = Yii::app()->db->createCommand($query_detaile)->queryAll();

            foreach ($result_detail as $detail){

                $query_detaile =new DeliveryDetail();

                $query_detaile->delivery_id=$delivery_id2;
                $query_detaile->product_id=$detail['product_id'];
                $query_detaile->date=$detail['date'];
                $query_detaile->quantity=$detail['quantity'];
                $query_detaile->amount=$detail['amount'];
                $query_detaile->adjust_amount=$detail['adjust_amount'];

                if($query_detaile->save()){
                    echo  $x++;
                    echo '<br>';
                }else{

                }
            }







        }
    }
}
