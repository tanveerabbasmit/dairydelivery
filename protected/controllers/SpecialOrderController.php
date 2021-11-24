<?php

class SpecialOrderController extends Controller
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


	public function actionOrder_hup_list(){

        $this->render('order_hup_list',array(
            'data' =>json_encode([]),
            'spcialOrderCount' =>json_encode([]),
        ));

    }

	public function actionmanageSpecialOrder()
	{
	    $get_data = $_GET;

	    if(isset($get_data['special_order_id'])){
           $special_order_id = $get_data['special_order_id'];
           if($special_order_id>0){
               $object = SpecialOrder::model()->findByPk($special_order_id);
               $object->delete();
           }
        }

		$this->render('manageSpecialOrder',array(
           'data' =>manageSpecialOrderDATA::getSpecialOrderList($page = false),
           'spcialOrderCount' =>manageSpecialOrderDATA::getSpecialOrderCount($page = false),
		));
	}

    public function actionnextPageForPagination(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo manageSpecialOrderDATA::getSpecialOrderList($data);
    }

    public function actionviewAll(){


        echo manageSpecialOrderDATA::getviewAllFunction($data = false);
    }

    public function actionnextPagePaginationViewAll(){
        $post = file_get_contents("php://input");

        echo manageSpecialOrderDATA::getviewAllFunction($post);
    }

    public function actionorder_hup_list_today_data(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo manageSpecialOrderDATA::getSpecialOrderList($data);
    }
    public function actionsearchDeliveryDate(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo manageSpecialOrderDATA::getSpecialOrderList($data);
    }


	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='special-order-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionpaymentMethodsave_spacial_order(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $client_id =  $data['client_id'];
        $product_id =  $data['product_id'];
        $product_id =  $data['product_id'];
        $startDate =  $data['startDate'];
        $endDate =  $data['endDate'];
        $amount_paid =  $data['amount_paid'];

        $special_order = new SpecialOrder();
        $special_order->client_id = $client_id ;
        $special_order->product_id = $product_id ;
        $special_order->quantity = $amount_paid ;
        $special_order->delivery_on = date("Y-m-d");
        $special_order->start_date = $startDate;
        $special_order->end_date =$endDate;
        $special_order->status_id =1;
        $special_order->preferred_time_id =1;
        $special_order->company_branch_id = $data['company_branch_id'];
        if($special_order->save()){
            die("her one");
        }else{
            var_dump($special_order->getErrors());
        }
    }
    public  function actionviewAll_orderhub_delivery(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $grand_total = $data['grand_total'];
        $client_object = $data['client_object'];
        $prouct_object = $data['prouct_object'];

        $total_price = $grand_total['total_price'];
        $company_id = Yii::app()->user->getState('company_branch_id');
        $delivery = New Delivery();

        $delivery->company_branch_id = $company_id;
        $delivery->rider_id = '0';
        $delivery->client_id = $client_object['client_id'];
        $delivery->date = date("Y-m-d");
        $delivery->time = date("H:i");
        $delivery->total_amount = $grand_total['total_price'];
        $delivery->amount = $grand_total['total_price'];
        $delivery->tax_percentage = 0;
        $delivery->discount_percentage = 0;
        $delivery->amount_with_tax = 0;
        if($delivery->save()){

             $delivery_id = $delivery->delivery_id;

             foreach ($prouct_object as $value){

                 if($value['selected']){

                     $special_order_id =  $value['special_order_id'];

                     $deltil_object = New DeliveryDetail();

                     $deltil_object->delivery_id =$delivery_id;
                     $deltil_object->product_id  =$value['product_id'];
                     $deltil_object->date  =date("Y-m-d");
                     $deltil_object->quantity  =$value['quantity'];
                     $deltil_object->amount  = $value['total_price'];
                     $deltil_object->adjust_amount  =0;
                     $deltil_object->jv_id  =0;
                     if($deltil_object->save()){

                         $object = SpecialOrder::model()->findByPk($special_order_id);

                         $object->is_delivered =1;

                         $object->save();

                     }else{
                         echo "<pre>";
                         print_r($deltil_object->getErrors());
                         die();
                     }

                 }

             }

        }else{
            echo "<pre>";
            print_r($delivery->getErrors());
            die();
        }



    }
    public function actioncreateNewSpacialOrder()
    {

         $get_date =$_GET;
         $client_object =[];
         if(isset($get_date['client_id'])){
             $client_id =$get_date['client_id'];
             $client_object = Client::model()->findByPk($client_id)->attributes;

         }
         $data =[];

         $data['client_id'] =isset($get_date['client_id'])?$get_date['client_id']:'';
         $data['product_id'] =isset($get_date['product_id'])?$get_date['product_id']:'';
         $data['client_object'] =$client_object;


        $company_id = Yii::app()->user->getState('company_branch_id');
        $query_discount = "SELECT * FROM product AS p 
            WHERE p.company_branch_id = '$company_id' ";
        $discount_type_result = Yii::app()->db->createCommand($query_discount)->queryAll();


        $todayMonth = Date('m');
        $todayYear = Date('Y');
        $this->render('createNewSpacialOrder',array(
            // 'clientList'=>clientData::getActiveClientList_forLedger(),
            'clientList'=>json_encode($data),
            'todayMonth'=>$todayMonth ,
            'todayYear'=>$todayYear ,
            'discount_type'=>json_encode($discount_type_result),
            'crud_role'=>crudRole::getCrudrole(16) ,
        ));
    }

}
