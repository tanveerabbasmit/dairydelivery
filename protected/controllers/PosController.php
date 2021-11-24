<?php

class PosController extends Controller
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
    public function actionpointOfSale()
    {

         $user_id = Yii::app()->user->getState('user_id');

         $user_object = User::model()->findByPk(intval($user_id));
         $pos_shop_id = $user_object['pos_shop_id'];
         if($pos_shop_id == 0){
             $this->render('pointOfSale_no_select_shop',array(

             ));
         }else{
             $company_id = Yii::app()->user->getState('company_branch_id');

             $company_object =Company::model()->findByPk(intval($company_id));


             $company_name = $company_object['company_name'];
             $data = array();

             $data['company_name'] =$company_name ;

             if($company_id==15){
                 $data['line_1'] ='For any assistance, please contact Noor Customer Care
                     03 111 222 572' ;
                 $data['line_2'] ='A project of' ;
                 $data['line_3'] ='Pak National Dairies' ;
                 $data['line_4'] ='Thank you for visting Noor' ;
             }else{

             }


             $data['company_object'] =$company_object->attributes;

             $this->render('pointOfSale',array(
                 'productList'=>productData::getproductList($page =false),
                 'productCount'=>productData::getproductCount(),
                 'data'=>json_encode($data),
             ));
         }
    }

    public function actionpointOfSaleGetData(){

        $company_id = Yii::app()->user->getState('company_branch_id');
         $todayDate =date("Y-m-d");
        $query="SELECT  sum(ps.quantity) as quantity ,sum(ps.total_price) as total_price ,p.NAME AS product_name FROM pos AS ps
            LEFT JOIN product AS p ON p.product_id =ps.product_id 
            where  ps.company_id = '$company_id' and ps.date ='$todayDate'
            GROUP BY p.product_id ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

         echo json_encode($queryResult);
          
    }
    public function actiondateRangePos_data(){
        $post = file_get_contents("php://input");

        $todayData = posData::getposTodayData($post);
        echo $todayData ;
    }
    public function actionsaveNewProduct_getinvoiceData(){
         $post = file_get_contents("php://input");
         $todayData = posData::getposInvoiceData($post);
         echo $todayData ;
    }
    public function actionPosDateRang(){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $todayDate =date("y-m-d");
        //$todayData = posData::getposTodayData($todayDate);
        $todayData = json_encode([]);

        $this->render('PosDateRang',array(
            'riderList'=>$todayData,
            'todayData' =>json_encode([]),
            'productList'=>json_encode([]),
            'lableObject'=>json_encode([]),

        ));
    }
    public function actionsaveNewProduct_pos(){


         $responce = array();
         $responce['success'] =true;
         $responce['message'] =true;

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $customer_name =  $data['customer_name'];
        $discount_amount =  $data['discount_amount'];


        $user_id = Yii::app()->user->getState('user_id');
        $company_id = Yii::app()->user->getState('company_branch_id');
        $inviceNo = $data['inviceNo'];
        $receivedAmount = $data['receivedAmount'];
        $saleValueItem = $data['saleValueItem'];
        $user_object = User::model()->findByPk(intval($user_id));
        $pos_shop_id = $user_object['pos_shop_id'];
         foreach ($saleValueItem as $value){
             $posObject = new Pos();
             $posObject->user_id =$user_id ;
             $posObject->pos_shop_id =$pos_shop_id ;
             $posObject->discount =$discount_amount ;
             $posObject->customer =$customer_name ;
             $posObject->company_id =$company_id ;
             $posObject->unit_price =$value['price'] ;
             // die("ok");
             $posObject->quantity =$value['quantity'] ;
             $posObject->total_price =$value['total_price'] ;
             $posObject->product_id =$value['product_id'] ;
             $posObject->received_amount =$receivedAmount ;
             $posObject->invoice =$inviceNo ;
             $posObject->date =date("Y-m-d");
             $posObject->time = date("h:i:s");
             if($posObject->save()){
                 $responce['success'] =true;
                 $responce['message'] ="Save Successfully ";
             }else{
                 $responce['success'] =false;
                 $responce['message'] =$posObject->getErrors();
             }
         }
         echo json_encode($responce);
    }

    public function actionsaveNewProduct_pos_getinvoiceData(){
        $post = file_get_contents("php://input");
        $query="SELECT * FROM pos AS po
        LEFT JOIN product AS p ON p.product_id = po.product_id
        WHERE po.invoice =  '$post' ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

         echo json_encode($queryResult);

    }

    public function actionsaveDeliveryFromPortal_delete_pos_id(){
        $post = file_get_contents("php://input");

        $company_id = Yii::app()->user->getState('company_branch_id');
        $object =Pos::model()->findByPk($post);

        if($object['company_id'] ==$company_id){
            $object->delete();
        }
    }

    public function actionsaveDeliveryFromPortal_update_pos(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);



        $code = $data['code'];
        $responce = [];

        if($code=='44332211'){
            $pos_id = $data['pos_id'];
            $object =Pos::model()->findByPk($pos_id);
            $company_id = Yii::app()->user->getState('company_branch_id');
            if($object['company_id'] ==$company_id){
                $object->quantity = $data['quantity'];
                $object->total_price = $data['total_price'];
                $object->unit_price = ($data['total_price']/$data['quantity']);

                if($object->save()){
                    $responce['success'] =true;
                }else{
                    $responce['success'] =false;
                    $responce['message'] =$object->getErrors();

                }
            }
        }else{
            $responce['success'] =false;
            $responce['message'] ="security code is not correct";
        }

        echo  json_encode($responce);

    }
    public function actionBusiness_summary_sale(){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $data =[];
        $data['start_date'] =date("Y-m-d");



        $this->render('business_summary_sale',array(
            'data'=>json_encode($data),


        ));
    }
    public function actionbase_business_summary_sale_data(){

        $company_id = Yii::app()->user->getState('company_branch_id');
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        $product_list = productData::product_list();

        $final_data =[];

        $total_amount = 0;
        $total_quantity = 0;
        foreach ($product_list as $value){

            $name = $value['name'];

            $product_id = $value['product_id'];

            $queryTotalCount = " select ifnull(sum(dd.quantity) ,0) as quantity ,
                ifnull(sum(dd.amount) ,0) as amount  from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                where d.company_branch_id = '$company_id' and 
                d.date BETWEEN '$start_date' and '$end_date' 
                and dd.product_id ='$product_id' ";



            $queryResult =  Yii::app()->db->createCommand($queryTotalCount)->queryAll();

            $quantity = $queryResult[0]['quantity'];
            $amount = $queryResult[0]['amount'];
            $rate = 0;
            if($quantity >0){
                $rate = round(($amount/$quantity),2) ;
            }
            $one_object = [];

            $one_object['product_id'] =$product_id;
            $one_object['name'] =$name;
            $one_object['quantity'] =$quantity;
            $one_object['amount'] =round($amount,0);
            $one_object['rate'] =$rate;

            $total_amount = $total_amount + $amount;
            $total_quantity = $total_quantity + $quantity;

            if($quantity >0){
                $final_data[]= $one_object;
            }


        }

         $result = [];
         $result['list'] = $final_data;
         $result['total_amount'] = round($total_amount,0);
         $result['total_quantity'] = $total_quantity;

         echo  json_encode($result);

    }
}

