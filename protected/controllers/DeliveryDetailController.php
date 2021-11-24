<?php

class DeliveryDetailController extends Controller
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

    public function actiondateRangeChangeRate()
    {
        //clientData::getActiveClientList_forLedger()
        $this->render('dateRangeChangeRate',array(
            'clientList'=>json_encode(array()),
            'productList'=>productData::getproductList($page =false),
        ));
    }

    public function actiongetClientDeliveryList_change_parmanant_rate(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $product_id = $data['product_id'];
        $new_rate = $data['new_rate'];
        $clientID = $data['clientID'];

        $object = ClientProductPrice::model()->findByAttributes([
            'product_id'=>$product_id,
            'client_id'=>$clientID

        ]);

        if($object){
            $object->price = $new_rate;
            $object->save();
        }else{
            $object = New ClientProductPrice();

            $object->price = $new_rate;
            $object->client_id = $clientID;
            $object->product_id = $product_id;

            $object->save();

        }


    }
    public function actiongetClientDeliveryList(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $clientID =$data['clientID'];
        $startDate =$data['startDate'];
        $endDate =$data['endDate'];

        $query=" SELECT d.date ,dd.quantity , (dd.amount/dd.quantity) AS rate ,dd.amount ,p.name ,dd.delivery_detail_id  FROM delivery AS d
            LEFT JOIN delivery_detail AS dd ON d.delivery_id = dd.delivery_id
            LEFT JOIN product AS p ON p.product_id =dd.product_id
            WHERE d.client_id ='$clientID' AND  d.date between '$startDate' AND '$endDate' ";

       /* $query=" SELECT d.date ,dd.quantity , (dd.amount/dd.quantity) AS rate ,dd.amount ,p.name ,dd.delivery_detail_id  FROM delivery AS d
            LEFT JOIN delivery_detail AS dd ON d.delivery_id = dd.delivery_id
            LEFT JOIN product AS p ON p.product_id =dd.product_id
            WHERE d.company_branch_id ='1' AND  d.date between '$startDate' AND '$endDate' ";*/

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
        echo json_encode($queryResult);
    }

    public function actiongetClientDeliveryList_change_rate(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

         $product_id = $data['product_id'];
         $new_rate = $data['new_rate'];
         $result = $data['result'];

         foreach($result as $value){

             $delivery_detail_id = $value['delivery_detail_id'];

             $dd_Object = DeliveryDetail::model()->findByPk(intval($delivery_detail_id));

              $product_id_get =$dd_Object['product_id'];

              $date = $dd_Object['date'];

              if($product_id_get ==$product_id){

                   $quantity = $value['quantity'];
                    $new_amount= $quantity*$new_rate ;

                   $dd_Object->amount = $new_amount ;

                   if($dd_Object->save()){
                       echo $new_amount ;
                        $delivery_id = $dd_Object->delivery_id ;
                        DeliveryDetailController::setPriceDifference($delivery_id);
                   }
              }
         }
    }

    public static  function setPriceDifference($delivery_id){

	    $dd_Object = DeliveryDetail::model()->findAllByAttributes([
	        'delivery_id'=>$delivery_id
        ]);
	    $total_amount = 0;
	    foreach ($dd_Object as $value){
            $total_amount = $total_amount + $value['amount'];
        }
	    $deliveryObject = Delivery::model()->findByPk(intval($delivery_id));
        $deliveryObject->total_amount = $total_amount;
        $deliveryObject->save();

    }

    public  function actionreturnSale()
    {

        //clientData::getActiveClientList_forLedger()
        $this->render('returnSale', array(
            'clientList' => json_encode(array()),
            'productList' => productData::getproductList($page = false),
        ));
    }

    public function actiongetClientDeliveryList_select_product_rate(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $client_id = $data['client_id'];
        $product_id = $data['product_id'];

        $query =" SELECT cp.price   FROM client_product_price AS cp
           WHERE cp.client_id ='$client_id' AND cp.product_id='$product_id' ";
        $result = Yii::app()->db->createCommand($query)->queryscalar();
        if($result){
            echo $result ;
        }else{
             $product = Product::model()->findByPk(intval($product_id));
             echo   $product['price'] ;
        }

    }

    public function actiongetClientDeliveryList_return_product(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $clientID = $data['client_id'];
        $product_id = $data['product_id'];
        $product_rate = $data['product_rate'];
        $selected_date = $data['startDate'];
        $quantity = $data['quantity'];

        $saleOption = $data['saleOption'];

        if($saleOption ==0){
            if($quantity >0){
                $quantity = -($quantity);
            }
        }else{
            if($quantity < 0){
                $quantity = -($quantity);
            }
        }


        $company_id = Yii::app()->user->getState('company_branch_id');
        $deliveryObject = Delivery::model()->findByAttributes(array('client_id'=>$clientID, 'date'=>$selected_date));

        if($deliveryObject){

               $delivery_id = $deliveryObject['delivery_id'] ;

                $deliveryDetail = new DeliveryDetail();
                $deliveryDetail->delivery_id = $delivery_id ;
                $deliveryDetail->product_id =$product_id ;
                $deliveryDetail->date = $selected_date ;
                $deliveryDetail->quantity = $quantity ;
                $deliveryDetail->amount =  $quantity * $product_rate;
                $deliveryDetail->adjust_amount = $quantity * $product_rate ;
                if($deliveryDetail->save()){
                    DeliveryDetailController::setPriceDifference($delivery_id);
                }else{
                    var_dump($deliveryDetail->getErrors());
                }



        }else{
            $delivery = new Delivery();

            $delivery->company_branch_id = $company_id ;
            $delivery->client_id = $clientID;
            $delivery->rider_id = 0 ;
            $delivery->date = $selected_date ;
            $delivery->time = date("H:i") ;
            $delivery->tax_percentage = 0 ;
            $delivery->amount_with_tax = 0 ;
            $delivery->tax_amount = 0 ;
            $delivery->latitude = 0 ;
            $delivery->longitude = 0 ;
            $delivery->amount = 0 ;
            $delivery->discount_percentage = 0 ;
            $delivery->total_amount = $quantity * $product_rate;
            $delivery->partial_amount = $quantity * $product_rate; ;

            if($delivery->save()){
                $deliveryID = $delivery->delivery_id ;

                $deliveryDetail = new DeliveryDetail();
                $deliveryDetail->delivery_id = $deliveryID ;
                $deliveryDetail->product_id =$product_id ;
                $deliveryDetail->date = $selected_date ;
                $deliveryDetail->quantity = $quantity ;
                $deliveryDetail->amount =  $quantity * $product_rate;
                $deliveryDetail->adjust_amount = 0 ;
                if($deliveryDetail->save()){
                    DeliveryDetailController::setPriceDifference($deliveryID);
                }else{
                    var_dump($deliveryDetail->getErrors());
                }


            }else{

            }
        }



    }

}
