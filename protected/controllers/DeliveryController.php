<?php

class DeliveryController extends Controller
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


	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view' ,'getClientList' ,'deliveryStatusForAllCustomer'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','CustomerDeliveryReport','setDeliveryRoute'
                ,'saverearrangeOrderList'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionCustomerDeliveryReport(){
	    $customer = Client::model()->findAll();
         $CustomerList = array();
        foreach($customer as $value){
            $CustomerList[] = $value->attributes;
        }

	   $this->render('CustomerDeliveryReport' , array(
          'riderList'=>json_encode($CustomerList),
       ));
    }

    public function actionsetDeliveryRoute(){

        $getRiderList = riderData::getRiderList();
        $getRiderList = str_replace("'","/",$getRiderList);

        $this->render('setDeliveryRout' , array(
            "riderList"=>$getRiderList,
            "companyBranchList"=>companyBranchData::getCompanyBranchList(),
        ));
    }

    public function actiondeliveryStatusForAllCustomer(){

        date_default_timezone_set("Asia/Karachi");
        $todayDate = Date('Y-m-d');
        $currentMonth = Date('Y-m').'-%%';


        $company_id = Yii::app()->user->getState('company_branch_id');

        $rider_query = "select r.rider_id , r.fullname from rider as r
            where r.company_branch_id = $company_id and r.is_active = 1";
        $rider_result = Yii::app()->db->createCommand($rider_query)->queryAll();
        $finalResult = array();
        $productList = Product::model()->findAllByAttributes(array('company_branch_id'=>$company_id , 'bottle'=>0));

        foreach($rider_result as $value){
            $rider_id = $value['rider_id'];
            $rider_name = $value['fullname'];

            foreach($productList as $product){
                $product_id = $product['product_id'];
                $oneProduct = array();
                $oneProduct['rider_name'] = $rider_name ;

                $deliveryQery = "select IFNULL(sum(dd.amount) , 0) as totalAmount , IFNULL(sum(dd.quantity) ,0)  as totalQuantity from delivery as d
                 left join delivery_detail as dd ON d.delivery_id = dd.delivery_id
                  where d.rider_id =$rider_id and dd.product_id = '$product_id' and d.date = '$todayDate' ";

                $dileryResult = Yii::app()->db->createCommand($deliveryQery)->queryAll();

                $deliveryMonthQery = "select IFNULL(sum(dd.amount) , 0) as totalAmount , IFNULL(sum(dd.quantity) ,0)  as totalQuantity from delivery as d
                 left join delivery_detail as dd ON d.delivery_id = dd.delivery_id
                  where d.rider_id =$rider_id and dd.product_id = '$product_id' and d.date like '$currentMonth' ";

                $dileryMonthResult = Yii::app()->db->createCommand($deliveryMonthQery)->queryAll();
                $total_client_query = " Select  count(*)  as  totalClient  from rider_zone as rz
                        Right join client as c ON c.zone_id = rz.zone_id
                        where rz.rider_id = $rider_id  AND c.is_active = 1 ";

                $total_cleint_result = Yii::app()->db->createCommand($total_client_query)->queryAll();

                $oneProduct['product_name'] = $product['name'];
                $oneProduct['totalQuantity'] = $dileryResult[0]['totalQuantity'];
                $oneProduct['dileryMonthResult'] = $dileryMonthResult[0]['totalQuantity'];
                $oneProduct['totalClient'] = $total_cleint_result[0]['totalClient'];
                if($dileryMonthResult[0]['totalQuantity'] > 0){

                    $finalResult[] = $oneProduct ;
                }

            }
        }


        $this->render('deliveryStatusForAllCustomer' , array(
            "riderList"=>json_encode($finalResult),

        ));
    }
    public function actiongetClientList(){
            $post = file_get_contents("php://input");
            $data = json_decode($post,true);



            $riderId = $data['riderId'];

            $zone_order_by = $data['zone_order_by'];
            $Modulo = $zone_order_by%2;
            if($Modulo==0){
                $ordertype='ASC';
            }else{
                $ordertype='DESC';
            }

                $clientQuery = "Select  
            c.fullname ,
            c.cell_no_1,
            c.address ,
            z.name AS zone_name,
            c.client_id 
            from rider_zone as rz
            Right join client as c ON c.zone_id = rz.zone_id
            LEFT JOIN zone AS z ON z.zone_id =c.zone_id
            where rz.rider_id = $riderId  AND c.is_active ='1' ";

            if( $zone_order_by>0){
                $clientQuery .=" order BY z.name  $ordertype ";
            }else{
                $clientQuery .=" order by c.rout_order ASC ,c.fullname ASC ";
            }


        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
        echo json_encode($clientResult);
    }
    public function actionsaverearrangeOrderList(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,true);
        foreach($data as $value){
            $clientId = $value['client_id'];
            $clientObject = Client::model()->findByPk(intval($clientId));
            $clientObject->rout_order = $value['orderNo'];

            if($clientObject->save()){

            }else{
                var_dump($clientObject->getErrors());
            }

        }


    }



}
