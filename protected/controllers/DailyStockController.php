<?php

class DailyStockController extends Controller
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
				'actions'=>array('saveNewStock_add_todayStock','saveNewStock_add_select_date_range','viewDateRangeStock','saveNewStock_add','addDailyStock','index','view' , 'create','update', 'saveStock', 'searchStock',
                    'saveNewStock', 'stockDetail', 'deleteStock','reconcileStock',
                    'getCurrentStock','getCurrentStock2','getCurrentStockCount_total' ,'getCurrentStockprevious_date','getCurrentStock2_monthly'),
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
	}*//*public function accessRules()
	{
        $actionsList =appConstants::getActionList();
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('saveNewStock_add_todayStock','saveNewStock_add_select_date_range','viewDateRangeStock','saveNewStock_add','addDailyStock','index','view' , 'create','update', 'saveStock', 'searchStock',
                    'saveNewStock', 'stockDetail', 'deleteStock','reconcileStock',
                    'getCurrentStock','getCurrentStock2','getCurrentStockCount_total' ,'getCurrentStockprevious_date','getCurrentStock2_monthly'),
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

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new DailyStock;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DailyStock']))
		{
			$model->attributes=$_POST['DailyStock'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->daily_stock_id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionsaveNewStock_add_todayStock(){

         $post = file_get_contents("php://input");
         $data = CJSON::decode($post, true);

         $date =$data['date'];
        $farm_id =$data['farm_id'];
        $company_id = Yii::app()->user->getState('company_branch_id');

        $query ="select
            s.purchase_rate,
            s.daily_stock_id ,
            s.description ,
              p.name as product_name ,
              ifnull(f.farm_name, 'All')  as farm_name ,
            s.quantity ,
            s.wastage,
            s.return_quantity ,
            s.description from daily_stock as s
            left join farm as f ON f.farm_id =s.farm_id 
            left join product as p ON p.product_id =s.product_id
            where s.company_branch_id ='$company_id' and s.date = '$date'";
        if($farm_id >0){
          //  $query .= " and s.fram_id = '$farm_id'";

        }

        $productList =  Yii::app()->db->createCommand($query)->queryAll();

          $quantity = 0;
          $wastage = 0;
          $return_quantity = 0 ;
        foreach ($productList as $key => $value) {

             $quantity =$quantity + $value['quantity'];
             $wastage =$wastage + $value['wastage'];
             $return_quantity =$return_quantity + $value['return_quantity'];

        }

        $data = [];
        $data['list']= $productList;
        $data['quantity'] = $quantity;
        $data['wastage'] = $wastage;
        $data['return_quantity'] =$return_quantity;
        echo json_encode($data);

    }




	public function actiongetCurrentStock(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);
        date_default_timezone_set("Asia/Karachi");
        $rider_id = $data['riderID'];
        $todayDate = $data['todate'];
      echo   dailyStockData::riderReconcileStock($todayDate,$rider_id);
    }
    public function actiongetCurrentStock2(){
        $post = file_get_contents("php://input");

        $data = CJSON::decode($post, true);
      echo   dailyStockData::riderReconcileStock2($data);
    }

    public function actiongetCurrentStock2_monthly(){
        $post = file_get_contents("php://input");

        $data = CJSON::decode($post, true);
      echo   dailyStockData::riderReconcileStock2_month($data);
    }

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DailyStock']))
		{
			$model->attributes=$_POST['DailyStock'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->daily_stock_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionviewDailyStock()
	{

		$productList = dailyStockData::getProductList();
		$data = array(
			'dailyStockList' => dailyStockData::getDailyStock(),
			'productList' => $productList,
			'currentData' => date("Y-m-d"),
		);
		//echo "<pre>";print_r($data);exit;
		$this->render('viewDailyStock',array(
			'data'=>json_encode($data),
		));
	}

	public function actionsaveNewStock_add_updatWastageStock(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);

        $daily_stock_id = $data['daily_stock_id'];

        $daily_Stock= DailyStock::model()->findByPk($daily_stock_id);

        $daily_Stock->purchase_rate = $data['purchase_rate'];

        $daily_Stock->quantity = $data['quantity'];
        $daily_Stock->wastage = $data['wastage'];
        $daily_Stock->return_quantity = $data['return_quantity'];
        if($daily_Stock->save()){
            echo "save";
        }

    }

    public function actionaddDailyStock(){



        $company_id = Yii::app()->user->getState('company_branch_id');

        $this->render('addDailyStock',array(
            'productList'=>json_encode(dailyStockData::ProductList()),
            'company_id'=>$company_id,
            'farmlist'=>qualityListData::getFarmList(),
        ));

    }

    public function actionviewDateRangeStock(){

	     $get_Data = $_GET ;


         if(isset($get_Data['date'])){
             $today_date = $get_Data['date'];
         }else{
             $today_date= date("Y-m-d");
         }


        $company_id = Yii::app()->user->getState('company_branch_id');

        $this->render('viewDateRangeStock',array(
            'productList'=>json_encode(dailyStockData::getCompanyDateRangeStock($today_date,$today_date)),
            'company_id'=>$company_id,
            'today_date'=>json_encode($today_date),

        ));

    }
    public function actionsaveNewStock_add_select_date_range(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);

         $startDate = $data['startDate'];

         $endDate = $data['endDate'];
         echo json_encode(dailyStockData::getCompanyDateRangeStock($startDate,$endDate));
    }

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new DailyStock('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DailyStock']))
			$model->attributes=$_GET['DailyStock'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return DailyStock the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=DailyStock::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param DailyStock $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='daily-stock-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionSaveStock()
	{
		$res = array(
			'status' => false,
			'message' => '',
			'data'	=> array(),
		);
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);

        if (!empty($data)) {
    		$model = DailyStock::model()->findByPk($data['daily_stock_id']);
    		$model->wastage = $data['wastage'];
    		$model->return_quantity = $data['return_quantity'];
    		$model->quantity = $data['quantity'];
    		if ($model->save()) {
    			$res = array(
					'status' => true,
					'data' => array(
						'dailyStockList' => DailyStock::getDailyStock(),
					)
				);
    		} else {
    			$res = array(
					'status' => false,
					'message' => $model->getErrors(),
				);
    		}
        } 
        echo json_encode($res);
        exit;
	}

	public function actionSearchStock()
	{

		$res = array(
			'status' => false,
			'message' => '',
			'data'	=> array(),
		);
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);
        if (!empty($data)) {

        	$dailyStockList = dailyStockData::getDailyStock($data);
        	if (!empty($dailyStockList)) {
        		$res = array(
					'status' => true,
					'message' => '',
					'data'	=> array(
						'dailyStockList' => $dailyStockList,
					),
				);
        	} else {
     			$res = array(
					'status' => false,
					'message' => 'No records found.',
				);
        	}
        	
        } 
        echo json_encode($res);
        exit;
	}

	public function actionsaveNewStock_add_getProductionStock(){


        $company_id= Yii::app()->user->getState('company_branch_id');

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);

         $startDate = $data['startDate'];
         $farm_id = $data['farm_id'];

        $query="SELECT c.cattle_record_id FROM cattle_record AS c
          WHERE c.company_id ='$company_id'";
        $cattleList =  Yii::app()->db->createCommand($query)->queryAll();
         $cattle_record_id_array = array();
         $cattle_record_id_array[] =0;

         foreach ($cattleList as $value){
             $cattle_record_id_array[] = $value['cattle_record_id'];
         }

         $cattle_record_id_list = implode(',',$cattle_record_id_array);

         $query ="SELECT sum(p.morning + p.afternoun + p.evenining) AS total_production 
          FROM cattle_production AS p
          WHERE p.cattle_record_id IN ($cattle_record_id_list) AND  p.DATE = '$startDate'";



        $cattleList =  Yii::app()->db->createCommand($query)->queryscalar();
        echo $cattleList ;


    }

	public function actionsaveNewStock_add()
	{
        $company_id= Yii::app()->user->getState('company_branch_id');

		$res = array(
			'status' => true,
			'message' => '',
			'data'	=> array(),
		);

        $post = file_get_contents("php://input");

        $data = CJSON::decode($post, true);



        $date = $data['date'];
        $productList = $data['productList'];
        $farm_id = $data['farm_id'];
        foreach ($productList as $value){

            $product_id = $value['product_id'];
            $quantity = $value['quantity'];
            $return_quantity = $value['return_quantity'];
            $wastage = $value['wastage'];
            $description = $value['description'];

            $purchase_rate = $value['purchase_rate'];
              if($return_quantity){
              }else{
                  $return_quantity='';
              }
              if($wastage){
              }else{
                  $wastage='';
              }
              if($return_quantity){

              }else{
                  $return_quantity ='';
              }

             if($quantity !='' OR $return_quantity !='' OR $wastage !=''){

                 $model = new DailyStock();
                 $model->company_branch_id =$company_id;
                 $model->farm_id =$farm_id;
                 $model->product_id =$product_id;
                 $model->description =$description;
                 $model->quantity =$quantity;
                 $model->return_quantity =$return_quantity;
                 $model->wastage =$wastage;
                 $model->date =$date;
                 $model->purchase_rate =$purchase_rate;
                 $model->created_by =Yii::app()->user->getState('user_id');;
                 if($model->save()){

                     farm_and_vendor_message_manage::farm_purchase_payment($data);
                     $res = array(
                         'status' => true,
                         'message' => '',
                         'data'	=> array(),
                     );

                 } else{
                     $res = array(
                         'status' => false,
                         'message' => $model->getErrors(),
                         'data'	=> array(),
                     );
                 }

             }


        }

        echo json_encode($res);
        die();

	}

	public function actionStockDetail()
	{
		$res = array(
			'status' => false,
			'message' => '',
			'data'	=> array(),
		);
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);
        if (!empty($data)) {
        	$stockDetailList = DailyStock::getDailyStockByProductId($data['product_id'], $data['date']);
        	$res = array(
				'status' => true,
				'message' => '',
				'data'	=> array(
					'stockDetailList' => $stockDetailList,
				),
			);
        }  
        echo json_encode($res);
        exit;
	}

	public function actionDeleteStock()
	{
		$res = array(
			'status' => false,
			'message' => '',
			'data'	=> array(),
		);
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);
        if (!empty($data)) {
        	$model = DailyStock::model()->findByPk($data['daily_stock_id']);
        	if ($model->delete()) {
        		$stockDetailList = DailyStock::getDailyStockByProductId($data['product_id'], $data['date']);
        		$res = array(
					'status' => true,
					'data'	=> array(
						'stockDetailList' => $stockDetailList,
						'dailyStockList' => DailyStock::getDailyStock(),
					),
				);
        	} else {
    			$res = array(
					'status' => false,
					'message' => $model->getErrors(),
				);
        	}
        	
        }  
        echo json_encode($res);
        exit;
	}
	public function actionReconcileStock(){

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT p.product_id ,p.name ,p.unit  from   product as p 
                where p.company_branch_id =$company_id and bottle = 0  ";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();

        $lableObject = array();
        foreach($productList as $value){
            $oneObject = array();
            $oneObject['quantity'] = 'Picked' ;

            $lableObject[] = $oneObject;
            $oneObject['quantity'] = 'Delivered' ;

            $lableObject[] = $oneObject;
            $oneObject['quantity'] = 'Returned to Company' ;


            $lableObject[] = $oneObject;
            $oneObject['quantity'] = 'Wastage' ;

            $lableObject[] = $oneObject;

            $oneObject['quantity'] = 'Balance' ;
            $lableObject[] = $oneObject;
        }


        $this->render('reconcileStock',array(

            'todayData' =>json_encode([]),
            'productList'=>json_encode($productList),
            'lableObject'=>json_encode($lableObject),

        ));
    }
    public function actiongetCurrentStockCount_total(){

         $post = file_get_contents("php://input");
          dailyStockData::riderReconcileStock_total($post);
    }

    public function actionsaveNewStock_add_delete_daily_stock(){
         $post = file_get_contents("php://input");

         $dailyStock = DailyStock::model()->findByPk(intval($post));
         if($dailyStock->delete()){

         }else{
            var_dump($dailyStock->getErrors());
         }


    }

    public function actiongetCurrentStockprevious_date(){

         $post = file_get_contents("php://input");
        echo   dailyStockData::riderReconcileStock_previousdate($post);
    }
}

