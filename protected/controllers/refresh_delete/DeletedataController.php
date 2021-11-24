<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 10/31/2017
 * Time: 5:17 PM
 */
class DeletedataController extends Controller
{
    public function actionDeleteDelvery(){

           die("lock");
        $query="SELECT *  from delivery as d where d.company_branch_id=10 ";

        $delivery_data =  Yii::app()->db->createCommand($query)->queryAll();


        foreach ($delivery_data as $value){


            $delivery_id = $value['delivery_id'];

            $deliver_delete_object = DeliveryDetail::model()->deleteAllByAttributes([
                'delivery_id'=>$delivery_id
            ]);

            $object = Delivery::model()->findByPk($delivery_id);
            $object->delete();

            /* echo $delivery_id ;
              die();*/
        }
    }
    public function actionPayment_delete(){
         die("lock");
        $query="SELECT * FROM `payment_master` WHERE `company_branch_id` = 10";
        $delivery_data =  Yii::app()->db->createCommand($query)->queryAll();
        foreach ($delivery_data as $value){
            $payment_master_id =  $value['payment_master_id'];
            
            $payment_object  = PaymentDetail::model()->deleteAllByAttributes([
                'payment_master_id'=>$payment_master_id
            ]);
            
           $one_object = PaymentMaster::model()->findByPk($payment_master_id);
            $one_object->delete();
            
          echo        $payment_master_id;
          die();
           
           
        }
    }
}