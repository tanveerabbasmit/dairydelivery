<?php

class Halt_orderController extends Controller
{
    public function actionhalt_order_create_delete(){

        $company_id = Yii::app()->user->getState('company_branch_id');


        $get =$_GET;

        $client_id = $get['client_id'];
        $product_id = $get['product_id'];

        $product_object=Product::model()->findByAttributes([
            'company_branch_id'=>$company_id,
            'product_id'=>$product_id,
        ]);

        if($product_object){
            $halt_object = HaltRegularOrders::model()->findByAttributes(
                [
                    'client_id'=>$client_id,
                    'product_id'=>$product_id,
                ]
            );
            $halt_object->delete();
        }
        $this->redirect(array('halt_order_create','client_id'=>$client_id,'product_id'=>$product_id));


    }
	public function actionHalt_order_create()
	{

         date_default_timezone_set("Asia/Karachi");

         $today_date =date('Y-m-d');

        $today_date = "SELECT * FROM halt_regular_orders
            WHERE end_date <'$today_date'";

        $halt_result =  Yii::app()->db->createCommand($today_date)->queryAll();

        foreach ($halt_result as $value){
            $halt_regular_orders_id = $value['halt_regular_orders_id'];

            $object =HaltRegularOrders::model()->findByPk($halt_regular_orders_id);
            $object->delete();
        }



         $company_id = Yii::app()->user->getState('company_branch_id');


        $get =$_GET;

        $client_id = $get['client_id'];
        $product_id = $get['product_id'];
       
         $product_object=Product::model()->findByAttributes([
             'company_branch_id'=>$company_id,
             'product_id'=>$product_id,
         ]);


         $halt_object = HaltRegularOrders::model()->findAllByAttributes(
             [
                 'client_id'=>$client_id,
                 'product_id'=>$product_id,
             ]
         );

        $halt_result = [] ;

        foreach ($halt_object as $value){
            $halt_result[] = $value->attributes;
        }


         $client = Client::model()->findByPk($client_id);

         $fullname = $client['fullname'];

        $data =[];
        $data['start_date'] =date("Y-m-d");
        $data['end_date'] =date("Y-m-d");
        $data['client_id'] =$client_id;
        $data['product_id'] =$product_id;

        $data['product_name'] =$product_object['name'];

        $data['client_name'] =$fullname;
        $data['halt_result'] =$halt_result;

		$this->render('halt_order_create',[
		    'data'=>json_encode($data)
        ]);
	}

	public function actionbase_cancel_order(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $client_id =  $data['client_id'];
        $product_id =  $data['product_id'];

         HaltRegularOrders::model()->deleteAllByAttributes([
            'client_id'=>$client_id,
            'product_id'=>$product_id
         ]);

        $object = New HaltRegularOrders();



        $object->client_id =$data['client_id'];
        $object->product_id =$data['product_id'];
        $object->start_date =$data['start_date'];
        $object->end_date =$data['end_date'];

        $object->save();

    }


}