<?php

class CompanyController extends Controller
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
	/*public function accessRules()
	{
        $actionsList =appConstants::getActionList();
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'  , 'saveNewCompany' ,'editCompany' ,'delete','viewcompanystock' ,'getCompanyStockList'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>$actionsList,
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
	}*/


	public function actionmanageCompany()
	{
		$this->render('manageCompany',array(
			'zoneList'=>"",
			'companyList'=>companyData::getCompanyList(),
		));
	}
    public function actionsaveNewCompany(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo companyData::saveNewCompanyFunction($data);
    }

    public function actioneditCompany(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo companyData::editCompanyFunction($data);
    }

    public function actiondelete(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo companyData::deleteFunction($data);
    }

     public function actionviewcompanystock(){

         $this->render('viewcompanystock',array(
             'productList'=>json_encode(riderDailyStockData::getProductList()),
         ));
     }

     public function actionviewcompanystock_ledger(){

         $farmList=qualityListData::getFarmList();

         $this->render('viewcompanystock_ledger',array(
             "farmList"=>qualityListData::getFarmList(),
             'productList'=>json_encode(riderDailyStockData::getProductList()),
         ));
     }

    public function actiongetCompanyStockList(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $company_id = Yii::app()->user->getState('company_branch_id');
            $clientID = $data['clientID'];
            $startDate = $data['startDate'];
            $endDate = $data['endDate'];
            $product_id = $data['product_id'];

            $reportObject = array();
            $x= strtotime($startDate);
            $y= strtotime($endDate);
             $finalData = array();
             $grand_total_recived = 0;
             $grand_total_wastage = 0;
             $grand_total_return_quantity = 0;
             $grand_total_total_rider = 0;
             while($x < ($y+8640)){
                 $oneObject = array();
                 $selectDate = date("Y-m-d", $x);
                 $oneObject['Date'] = $selectDate ;
                 $x += 86400;
                  $querystck = "select ifnull(sum(ds.quantity) ,0) as total_recive 
                   ,ifnull(sum(ds.wastage),0) as wastage ,ifnull(sum(ds.return_quantity) ,0) as return_quantity from daily_stock as ds
                     where ds.date = '$selectDate'  and ds.product_id = '$product_id'";
                  $deliveryOneDayResult = Yii::app()->db->createCommand($querystck)->queryAll();
                  $total_recive  = $deliveryOneDayResult[0]['total_recive'];
                  $wastage  = $deliveryOneDayResult[0]['wastage'];
                  $return_quantity  = $deliveryOneDayResult[0]['return_quantity'];
                 $oneObject['total_recive'] = $total_recive;
                 $oneObject['total_return'] = $return_quantity;


                 $grand_total_recived = $grand_total_recived + $total_recive;
                 $grand_total_return_quantity = $grand_total_return_quantity + $return_quantity;
                 $grand_total_wastage = $grand_total_wastage +$wastage ;

                 $querystck_rider= "SELECT ifnull((sum(ds.quantity) -sum(ds.return_quantity) -sum(ds.wastage_quantity)) ,0) as quantity,
                   sum(ds.wastage_quantity) as total_wastage_quantity  FROM rider_daily_stock as  ds
                    where ds.date = '$selectDate'  and ds.product_id = '$product_id'";




                 $deliveryOneDayResult_rider = Yii::app()->db->createCommand($querystck_rider)->queryAll();



                 $total_rider_quantity  = $deliveryOneDayResult_rider[0]['quantity'];

                $total_rider_wastage_quantity  = $deliveryOneDayResult_rider[0]['total_wastage_quantity'];


                 $oneObject['total_rider'] = $total_rider_quantity;
                 $oneObject['wastage'] = $wastage+$total_rider_wastage_quantity;
                 $oneObject['net'] = $total_recive + $return_quantity -$total_rider_quantity -$wastage -$total_rider_wastage_quantity;


                 $grand_total_total_rider = $grand_total_total_rider + $total_rider_quantity ;

                 $finalData[]= $oneObject ;
             }
              $grandr_totao_object = array();
              $grandr_totao_object['Date'] = 'Total';
              $grandr_totao_object['total_recive'] = $grand_total_recived;
              $grandr_totao_object['total_return'] = $grand_total_return_quantity;
              $grandr_totao_object['wastage'] = $grand_total_wastage;

              $grandr_totao_object['total_rider'] = $grand_total_total_rider;

              $grandr_totao_object['net'] = $grand_total_recived +$grand_total_return_quantity -$grand_total_total_rider-$grand_total_wastage ;

              $finalData[] = $grandr_totao_object;
           echo  json_encode($finalData);
    }

    public function actiongetCompanyStockList_summary(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        echo "pre";
        print_r($data);
        die();
    }

    public function actiongetCompanyStockList_ledger(){

            $post = file_get_contents("php://input");
            $data = CJSON::decode($post, TRUE);


            
            $company_id = Yii::app()->user->getState('company_branch_id');

            $clientID = $data['clientID'];

            $startDate = $data['startDate'];
            $endDate = $data['endDate'];
            $product_id = $data['product_id'];
            $farm_id = $data['farm_id'];

            $reportObject = array();
            $x= strtotime($startDate);
            $y= strtotime($endDate);
             $finalData = array();

            $querystock_opening = "select 
                ifnull(sum(ds.quantity) ,0) as total_recive,
                ifnull(sum(ds.wastage),0) as wastage ,
                ifnull(sum(ds.return_quantity) ,0) as return_quantity
                from daily_stock as ds
                where ds.date < '$startDate' 
                and ds.product_id = '$product_id'";

             if($farm_id >0){
               $querystock_opening .= " and ds.farm_id ='$farm_id' ";
             }
             $openoning_result = Yii::app()->db->createCommand($querystock_opening)->queryAll();


            $total_recive  = $openoning_result[0]['total_recive'];
            $wastage  = $openoning_result[0]['wastage'];
            $return_quantity  = $openoning_result[0]['return_quantity'];

            $rider_wastage_query = "SELECT 
              ifnull(sum(rds.wastage_quantity),0) AS wastage_quantity
              FROM rider_daily_stock AS rds
              WHERE  rds.product_id = '$product_id' and 
              rds.date < '$startDate' 
              ";

            $raider_wastage = Yii::app()->db->createCommand($rider_wastage_query)->queryAll();

            $rider_wastage_query =  $raider_wastage[0]['wastage_quantity'];




            $oneObject = array();
            $balance = 0;
            $oneObject['Date'] = "Opening Stock" ;
            $oneObject['total_recive'] = $total_recive;

            $oneObject['total_return'] = $return_quantity;


            $oneObject['wastage'] = $wastage + $rider_wastage_query;

           //$rider_wastage_query

        /*$querystck_rider_opening= "SELECT ifnull((sum(ds.quantity) -sum(ds.return_quantity)) ,0) as quantity  FROM rider_daily_stock as  ds
                    where ds.date < '$startDate'  and ds.product_id = '$product_id'";*/


        $querystck_rider_opening= "select ifnull(sum(d.quantity) ,0) as quantity from delivery_detail as d
                         where d.product_id ='$product_id' and d.date< '$startDate'";

        $deliveryOneDayResult_rider_opening = Yii::app()->db->createCommand($querystck_rider_opening)->queryAll();
        $total_rider_quantity  = $deliveryOneDayResult_rider_opening[0]['quantity'];
        $oneObject['total_rider'] = $total_rider_quantity;
        $oneObject['net'] = $total_recive - $return_quantity -$total_rider_quantity -$wastage ;
        $balance = $balance + $oneObject['net'];
        $oneObject['balance'] = $balance ;

                $finalData[]= $oneObject ;


                $grand_total_recived = 0;
                $grand_total_wastage = 0;
                $grand_total_return_quantity = 0;
                $grand_total_total_rider = 0;
             while($x < ($y+8640)){
                    $oneObject = array();
                    $selectDate = date("Y-m-d", $x);
                    $oneObject['Date'] = $selectDate ;
                    $x += 86400;

                    $querystck = "select 
                     ifnull(sum(ds.quantity) ,0) as total_recive 
                    ,ifnull(sum(ds.wastage),0) as wastage ,
                    ifnull(sum(ds.return_quantity) ,0) 
                      as return_quantity from daily_stock as ds
                    where ds.date = '$selectDate'  
                    and ds.product_id = '$product_id'
                    ";
                    if($farm_id >0){
                    $querystck .= "   AND ds.farm_id ='$farm_id' ";
                    }
                    $deliveryOneDayResult = Yii::app()->db->createCommand($querystck)->queryAll();
                    $total_recive  = $deliveryOneDayResult[0]['total_recive'];
                    $wastage  = $deliveryOneDayResult[0]['wastage'];
                    $return_quantity  = $deliveryOneDayResult[0]['return_quantity'];
                    $oneObject['total_recive'] = $total_recive;
                    $oneObject['total_return'] = $return_quantity;

                    $rider_wastage_query = "SELECT 
                    ifnull(sum(rds.wastage_quantity),0) AS wastage_quantity
                    FROM rider_daily_stock AS rds
                    WHERE  rds.product_id = '$product_id' and 
                    rds.date ='$selectDate'  ";

                    $raider_wastage = Yii::app()->db->createCommand($rider_wastage_query)->queryAll();

                    $rider_wastage_query =  $raider_wastage[0]['wastage_quantity'];
                    $oneObject['wastage'] = $wastage + $rider_wastage_query;

                    $grand_total_recived = $grand_total_recived + $total_recive;
                    $grand_total_return_quantity = $grand_total_return_quantity + $return_quantity;
                    $grand_total_wastage = $grand_total_wastage + $oneObject['wastage'] ;

                    $querystck_rider= "SELECT ifnull((sum(ds.quantity) -sum(ds.return_quantity)) ,0) as quantity  FROM rider_daily_stock as  ds
                    where ds.date = '$selectDate'  and ds.product_id = '$product_id'";


                    $querystck_rider= "select ifnull(sum(d.quantity) ,0) as quantity from delivery_detail as d
                    where d.product_id ='$product_id' and d.date='$selectDate'";

                    $deliveryOneDayResult_rider = Yii::app()->db->createCommand($querystck_rider)->queryAll();
                    $total_rider_quantity  = $deliveryOneDayResult_rider[0]['quantity'];

                    $oneObject['total_rider'] = $total_rider_quantity;

                    $oneObject['net'] = $total_recive - $return_quantity -$total_rider_quantity - $oneObject['wastage'] ;
                   $grand_total_total_rider = $grand_total_total_rider + $total_rider_quantity ;
                    $balance = $balance + $oneObject['net'];
                    $oneObject['balance'] = $balance ;
                     $finalData[]= $oneObject ;
             }
              $grandr_totao_object = array();
              $grandr_totao_object['Date'] = 'Total';
              $grandr_totao_object['total_recive'] = $grand_total_recived;
              $grandr_totao_object['total_return'] = $grand_total_return_quantity;
              $grandr_totao_object['wastage'] = $grand_total_wastage;

              $grandr_totao_object['total_rider'] = $grand_total_total_rider;

              $grandr_totao_object['net'] = $grand_total_recived +$grand_total_return_quantity -$grand_total_total_rider-$grand_total_wastage ;

             // $finalData[] = $grandr_totao_object;
           echo  json_encode($finalData);
    }

    public function actionbusiness_summary_stock(){

        $this->render('business_summary_stock',array(
           /* 'productList'=>json_encode(riderDailyStockData::getProductList()),*/
            'productList'=>json_encode([]),
        ));

    }

    public function actionbusiness_summary_stock_list(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $startDate = $data['startDate'];
        $endDate = $data['endDate'];


        $product_list = productData::product_list();

        $product_data =[];
        foreach ($product_list as $value){
            $product_id = $value['product_id'];
            $name = $value['name'];
            $unit = $value['unit'];
            $opening_stock = business_summary_stock_data::opening_stock($startDate ,$product_id);
            $total_purchased = business_summary_stock_data::total_purchased_stock($startDate ,$endDate,$product_id);
            $total_sold = business_summary_stock_data::total_sold_stock($startDate ,$endDate,$product_id);
            $rider_wastage = business_summary_stock_data::total_rider_wastage($startDate ,$endDate,$product_id);

            $one_onject = [];
            $one_onject['product_id'] =$product_id;
            $one_onject['name'] =$name;
            $one_onject['unit'] =$unit;

            $one_onject['opening_stock'] =$opening_stock;
            $one_onject['total_purchased'] =$total_purchased;
            $one_onject['total_in_hand'] =$opening_stock+$total_purchased;
            $one_onject['total_sold'] =$total_sold;
            $one_onject['rider_wastage'] =$rider_wastage;
            $one_onject['closing'] =$opening_stock+$total_purchased-$total_sold-$rider_wastage;
            $product_data[] = $one_onject;
        }

        $final_result = [];
        $final_result['list_data'] = $product_data;

        echo json_encode($final_result);
    }

}
