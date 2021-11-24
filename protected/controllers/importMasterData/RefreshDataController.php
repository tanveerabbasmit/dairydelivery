<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 10/31/2017
 * Time: 5:17 PM
 */
class RefreshDataController extends Controller
{
    public function actionRemoveData(){
        $query="SELECT c.*  from client as c
                  where c.company_branch_id =15
                 Order By c.fullname ASC ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        foreach($queryResult as $value){

            $client_id = $value['client_id'];
            ClientProductFrequency::model()->deleteAllByAttributes(array('client_id'=>$client_id));

            echo   $client_id = $value['client_id'];
            echo "<br>";

            $deliveryObject = Delivery::model()->findByAttributes(array('client_id'=>$client_id));
            if($deliveryObject){
                $delivery_id = $deliveryObject['delivery_id'];
                DeliveryDetail::model()->deleteAllByAttributes(array('delivery_id'=>$delivery_id));
                echo   Delivery::model()->deleteByPk(intval($delivery_id));
            }else{
            }
            NotDeliveryRecord::model()->deleteAllByAttributes(array('client_id'=>$client_id));
        }

    }
    public function actioncheckPayment(){
        $query="SELECT * FROM delivery AS d 
          LEFT JOIN  client AS c ON c.client_id = d.client_id
             ORDER BY d.delivery_id DESC
          limit 1,200000 ";

        $query="SELECT * FROM delivery AS d 
               GROUP BY d.delivery_id DESC
             limit 2 ,50000";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

        foreach($queryResult as $value){

            $delivery_amount = $value['total_amount'];
            $delivery_id = $value['delivery_id'];
            $deliveryDetail = DeliveryDetail::model()->findAllByAttributes(
                array('delivery_id'=>$delivery_id)
            );
            $amount_total =0;

            if($deliveryDetail){
                foreach($deliveryDetail as $value){
                    $amount = $value['amount'];
                    $amount_total = $amount_total + $amount;
                }
            }

            if($delivery_amount !=$amount_total){
                echo   $delivery_id;
                echo "<br>";
            }
        }
    }

    public function actioncheckPayment_add(){

        $query="SELECT * FROM delivery AS d 
          LEFT JOIN  client AS c ON c.client_id = d.client_id
          limit 400000,100000";

        $query="SELECT * FROM delivery AS d 
               GROUP BY d.delivery_id DESC
             limit 2,50000";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
        foreach($queryResult as $value){
            $delivery_amount = $value['total_amount'];
            $delivery_id = $value['delivery_id'];
            $deliveryDetail = DeliveryDetail::model()->findAllByAttributes(
                array('delivery_id'=>$delivery_id)
            );
            $amount_total =0;
            if($deliveryDetail){
                foreach($deliveryDetail as $value){
                    $amount = $value['amount'];
                    $amount_total = $amount_total + $amount;
                }
            }
            if($delivery_amount !=$amount_total){
                echo   $delivery_id;
                $delivery_object = Delivery::model()->findByPk(intval($delivery_id));
                $delivery_object->total_amount = $amount_total ;

                $delivery_object->save();

            }
        }
    }
    public function actioncheckPayment_export(){

        $query_client="SELECT c.client_id FROM client AS c
          WHERE c.company_branch_id =4 LIMIT 1, 300";

        $queryResult =  Yii::app()->db->createCommand($query_client)->queryAll();
        $id =array();
        foreach($queryResult as $value){

            $id[] = $value['client_id'] ;

        }
        $arrayList = implode(',' , $id);



        $query="SELECT d.delivery_id ,d.total_amount ,c.client_id ,c.fullname FROM delivery AS d 
          LEFT JOIN  client AS c ON c.client_id = d.client_id
          WHERE d.client_id in ($arrayList) ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=file.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo "cleint_id, name , system amount, actual amount";
        echo  "\r\n" ;

        foreach($queryResult as $value){
            $client_id = $value['client_id'];
            $fullname = $value['fullname'];

            $delivery_amount = $value['total_amount'];
            $delivery_id = $value['delivery_id'];
            $deliveryDetail = DeliveryDetail::model()->findAllByAttributes(
                array('delivery_id'=>$delivery_id)
            );
            $amount_total =0;
            if($deliveryDetail){
                foreach($deliveryDetail as $value){
                    $amount = $value['amount'];
                    $amount_total = $amount_total + $amount;
                }
            }

            if($delivery_amount !=$amount_total){

                echo $client_id.','.$fullname.','.$delivery_amount.','.$amount_total ;
                echo  "\r\n" ;

            }
        }
    }
    public function actioncompany_wise_export(){

         $get_dat = $_GET;
        $company_id = Yii::app()->user->getState('company_branch_id');

        $query_client="SELECT 
             c.client_id,
             c.fullname,
             c.last_name,
             c.cell_no_1,
             c.address,
             c.address,
             c.is_active,
             z.name,
             pt.payment_term_name,
             c.password,
             c.userName,
             cc.category_name
             FROM client AS c
            LEFT JOIN zone AS z ON z.zone_id =c.zone_id
            LEFT JOIN payment_term AS pt ON c.payment_term = pt.payment_term_id 
            LEFT JOIN customer_category AS cc ON cc.customer_category_id = c.customer_category_id

            WHERE c.company_branch_id ='$company_id'
            order by c.rout_order ASC";



        $queryResult =  Yii::app()->db->createCommand($query_client)->queryAll();

        $product_list =  productData::product_list();

        $product_name = '';

        foreach ($product_list as $value){
            $product_name .=  $value['name'].',';
        }

         header("Content-type: text/csv");
         header("Content-Disposition: attachment; filename=customer_list.csv");
         header("Pragma: no-cache");
         header("Expires: 0");
        echo "cleint_id,Full Name,Address,Phone No.,Status,Zone,Payment Term,$product_name user_name,password,category_name";
        echo  "\r\n" ;

        foreach ($queryResult as $value){



              $client_id =   $value['client_id'];




            $client_price_query = "SELECT 
                ifnull(cp.price,0) as new_price ,
                p.product_id ,
                p.name AS product_name 
                FROM product AS p 
                LEFT JOIN client_product_price AS cp
                ON p.product_id =cp.product_id AND cp.client_id='$client_id'
                WHERE p.company_branch_id ='$company_id' 
                and p.is_active='1' order by p.name";

            $client_price_result =  Yii::app()->db->createCommand($client_price_query)->queryAll();

            $new_price ='';

            foreach ($client_price_result as $value_1){
                $new_price .= $value_1['new_price'].',';
            }


             echo $value['client_id'].',';
             $fullname =str_replace(',','',$value['fullname'].$value['last_name']);
             echo str_replace(",","/",$fullname).',';
            // echo $value['userName'].',';


            echo str_replace(",","/",$value['address']).',';

             echo $value['cell_no_1'].',';

             if($value['is_active'] ==1){
                 echo "Active,";
             }else{
                 echo "Deactive,";
             }


            echo str_replace(",","/",$value['name']).',';
            echo str_replace(",","/",$value['payment_term_name']).',';
            echo $new_price;
            echo str_replace(",","/",$value['password']).',';
            echo str_replace(",","/",$value['userName']).',';
            echo str_replace(",","/",$value['category_name']).',';
            echo  "\r\n" ;

        }

    }
    public function actioncheckDuplicate_delivery(){

        $query_client="SELECT COUNT(*) ,d.delivery_id ,d.date FROM delivery_detail AS d
           GROUP BY d.delivery_id ,d.product_id
               HAVING COUNT(*)>1";

        $queryResult =  Yii::app()->db->createCommand($query_client)->queryAll();
        $id =array();
        foreach($queryResult as $value){

            echo $value['date']."__";
            echo $value['delivery_id'];

            echo "<br>";

        }

    }
    public function actioncheckDuplicate_delivery_delete(){

        $query_client="SELECT COUNT(*) ,d.delivery_id ,d.date FROM delivery_detail AS d
           GROUP BY d.delivery_id ,d.product_id
               HAVING COUNT(*)>1";

        $queryResult =  Yii::app()->db->createCommand($query_client)->queryAll();
        $id =array();
        foreach($queryResult as $value){

            $delivery_id = $value['delivery_id'];

            $query_one_delivery = "SELECT * from delivery_detail  AS dd
                WHERE dd.delivery_id='$delivery_id' LIMIT 1 ";

            $query_one_Result =  Yii::app()->db->createCommand($query_one_delivery)->queryAll();

            $delivery_detail_id =  $query_one_Result[0]['delivery_detail_id'];

            $dd = DeliveryDetail::model()->findByPk(intval($delivery_detail_id));
            if($dd->delete()){
                echo "delete";
            }

            echo $value['date']."__";
            echo $value['delivery_id'];


        }

    }
    public static function actionChangeRate(){
        $deliveryOBject = Delivery::model()->findAllByAttributes([
            'client_id'=>'9635'
        ]);

        foreach ($deliveryOBject as $value){
            $delivery_id = $value['delivery_id'];

            $deliveryDetailObject = DeliveryDetail::model()->findAllByAttributes([
                'delivery_id'=>$delivery_id
            ]);

            $totalAmount = 0;
            foreach ($deliveryDetailObject as $object){
                $delivery_detail_id =  $object['delivery_detail_id'];
                $quantity =  $object['quantity'];
                $updateObject = DeliveryDetail::model()->findByPk($delivery_detail_id);
                $priceAmount = $quantity * 90;
                $totalAmount =$totalAmount + $priceAmount;
                $updateObject->amount = $priceAmount;
                $updateObject->save();
            }

            $deliveryObject = Delivery::model()->findByPk($delivery_id);
            $deliveryObject->total_amount = $priceAmount ;
            $deliveryObject->save();

            echo $priceAmount."<br>";
        }
    }
    public function actionset_spacial_rate(){

        die();
        $query = " SELECT cp.client_product_price_id , cp.client_id,cp.product_id,cp.price 
                    FROM client_product_price AS cp
                   LEFT JOIN client AS c ON cp.client_id = c.client_id
                   WHERE c.company_branch_id =1 ";

        $Result_price =  Yii::app()->db->createCommand($query)->queryAll();

        foreach ($Result_price as $value){

            $client_product_price_id = $value['client_product_price_id'];
            echo   $price = $value['price'];

            $client_product_price = ClientProductPrice::model()->findByPk(intval($client_product_price_id));

            $client_product_price->price = ($price + 10);
            if($client_product_price->save()){

            }
        }

    }

    public static function actionChangeRate_default_rate(){

        die();
        $query =" SELECT c.fullname ,  cp.product_id , c.client_id ,c.company_branch_id  FROM client AS c
         LEFT JOIN client_product_price AS cp ON c.client_id = cp.client_id  
          WHERE c.company_branch_id =1 AND cp.price IS  null ";

        $client_List =  Yii::app()->db->createCommand($query)->queryAll();

        foreach($client_List as $value){
            $client_id = $value['client_id'];

            $query_delivery = "SELECT * FROM delivery AS d
                WHERE d.client_id ='$client_id' AND d.DATE >= '2019-07-01' ";

            $delivery_result =  Yii::app()->db->createCommand($query_delivery)->queryAll();
            foreach ($delivery_result as $delivery_value){
                $delivery_id =$delivery_value['delivery_id'];

                $deliveryDetailObject = DeliveryDetail::model()->findAllByAttributes([
                    'delivery_id'=>$delivery_id
                ]);

                $totalAmount = 0;
                foreach ($deliveryDetailObject as $object){
                    $delivery_detail_id =  $object['delivery_detail_id'];
                    $quantity =  $object['quantity'];
                    $updateObject = DeliveryDetail::model()->findByPk($delivery_detail_id);
                    $priceAmount = $quantity * 120;
                    $totalAmount =$totalAmount + $priceAmount;
                    $updateObject->amount = $priceAmount;
                    $updateObject->save();
                }

                $deliveryObject = Delivery::model()->findByPk($delivery_id);
                $deliveryObject->total_amount = $priceAmount ;
                $deliveryObject->save();

                echo  $client_id."=".$priceAmount;
                echo "<br>";



            }
        }

    }

    public function actionRider_wise_delivery()
    {

        $query_client = "SELECT 
            c.fullname AS client_name,
				d.delivery_id ,
            d.date,
            d.rider_id,
            dd.quantity, 
            dd.amount,
            d.rider_id,
            r.fullname ,
            d.client_id
            from delivery AS d
            LEFT JOIN delivery_detail AS dd ON d.delivery_id =dd.delivery_id
            LEFT JOIN rider AS r ON r.rider_id = d.rider_id
            LEFT JOIN client AS c ON c.client_id = d.client_id
            
            WHERE d.date BETWEEN '2019-11-01' 
            AND '2019-11-30' 
            AND d.rider_id IN (42)";

        $queryResult = Yii::app()->db->createCommand($query_client)->queryAll();

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=file.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo "Rider name,cstomer name,customer_id,date ,quantity,amount";
        echo  "\r\n" ;


        foreach ($queryResult as $value){

            $client_id = $value['client_id'];
            $fullname_rider =  str_replace(",","_",$value['fullname']);
            $client_name = str_replace(",","_",$value['client_name']);
            $client_id = $value['client_id'];
            $quantity = $value['quantity'];
            $amount = $value['amount'];
            $date = $value['date'];

            echo $fullname_rider.",".$client_name.",".$client_id.",";
            echo $date.",".$quantity.",".$amount;
            echo  "\r\n" ;
        }
    }

    public function actionexport_payment_list(){

        $company_id = Yii::app()->user->getState('company_branch_id');

    }

}