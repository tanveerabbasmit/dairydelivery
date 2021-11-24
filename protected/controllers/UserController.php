<?php

class UserController extends Controller
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
				'actions'=>array('index','view'  ,'saveNewUser' ,'delete' ,'editUser' ,'addBrokenBottle' ,'saveBottleFromPortal',
                    'checkAlredyExist' ,'viewRole' ,'ReciveBillReport' ,'reciveBillReportReport' ,'getCustomer'),
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

    public function actionaddBrokenBottle(){

        $company_id = Yii::app()->user->getState('company_branch_id');
        $this->render('addBrokenBottle',array(
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'company_id'=>$company_id,
        ));
    }
    public function actiongetCustomer(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,true);
        $RiderID = $data['RiderID'];
        $clientQuery = "Select c.* from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           
                           where rz.rider_id = $RiderID  ";
            $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
             echo json_encode($clientResult);
    }
    public function actionsaveBottleFromPortal(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,true);
         $clientID =$data['client_id'];
        $broken_bottle =$data['bottle'];
        $riderID =$data['riderID'];
        $perfect =$data['perfect'];

        $totalBottle = $broken_bottle + $perfect ;
         if($totalBottle > 0){

         }else{
             die('okk');
         }

        date_default_timezone_set("Asia/Karachi");


        $latitude = 0;
        $longitude = 0;


        $selectDate = date("Y-m-d");

        $company_branch_id = $data['company_branch_id'];

        $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_branch_id and bottle = 1";
        $product =  Yii::app()->db->createCommand($query)->queryAll();
        //   var_dump($product[0]['price']);
        //   die();
        $product_id = $product[0]['product_id'];

        $selected_date = date("Y-m-d");



        $delivery = Delivery::model()->findByAttributes(array('client_id'=>$clientID, 'date'=>$selected_date));

        $delivery_id = $delivery['delivery_id'];
        $total_amount  = $delivery['total_amount'];
        $partial_amount  = $delivery['partial_amount'];


        $deliveryObject = Delivery::model()->findByAttributes(array('client_id'=>$clientID, 'date'=>$selected_date));

        if($broken_bottle >0){

            if($deliveryObject){
                $total_amount =  $deliveryObject['total_amount'];
                $partial_amount =  $deliveryObject['partial_amount'];
                $delivery->company_branch_id = $company_branch_id ;
                $delivery->client_id = $clientID;
                $delivery->rider_id = $riderID;
                $delivery->date = $selectDate ;
                $delivery->time = date("H:i") ;
                $delivery->tax_percentage = 0 ;
                $delivery->amount_with_tax = 0 ;
                $delivery->tax_amount = 0 ;
                $delivery->latitude = $latitude ;
                $delivery->longitude = $longitude ;
                $delivery->amount = 0 ;
                $delivery->discount_percentage = 0 ;
                $delivery->total_amount = $product[0]['price'] * $broken_bottle +$total_amount;
                $delivery->partial_amount = $product[0]['price'] * $broken_bottle + $total_amount;
                if($delivery->save()) {
                    $deliveryID = $delivery->delivery_id;

                    $deliveryDetail = new DeliveryDetail();
                    $deliveryDetail->delivery_id = $delivery_id ;
                    $deliveryDetail->product_id = $product_id ;
                    $deliveryDetail->date = $selectDate ;
                    $deliveryDetail->quantity = $broken_bottle ;
                    $deliveryDetail->amount = $product[0]['price'] * $broken_bottle;
                    $deliveryDetail->adjust_amount = 0 ;

                    if($deliveryDetail->save()){
                    }else{
                        var_dump($deliveryDetail->getErrors());
                    }
                }

            }else{

                $delivery = new Delivery();
                $delivery->company_branch_id = $company_branch_id ;
                $delivery->client_id = $clientID;
                $delivery->rider_id = $riderID;
                $delivery->date = $selectDate ;
                $delivery->time = date("H:i") ;
                $delivery->tax_percentage = 0 ;
                $delivery->amount_with_tax = 0 ;
                $delivery->tax_amount = 0 ;
                $delivery->latitude = $latitude ;
                $delivery->longitude = $longitude ;
                $delivery->amount = 0 ;
                $delivery->discount_percentage = 0 ;
                $delivery->total_amount = $product[0]['price'] * $broken_bottle;
                $delivery->partial_amount = $product[0]['price'] * $broken_bottle;

                if($delivery->save()) {

                    $deliveryID = $delivery->delivery_id;
                    $deliveryDetail = new DeliveryDetail();
                    $deliveryDetail->delivery_id = $deliveryID ;
                    $deliveryDetail->product_id = $product_id ;
                    $deliveryDetail->date = $selectDate ;
                    $deliveryDetail->quantity = $broken_bottle ;
                    $deliveryDetail->amount = $product[0]['price'] * $broken_bottle;
                    $deliveryDetail->adjust_amount = 0 ;
                    if($deliveryDetail->save()){
                    }else{

                    }
                }else{

                }
            }
        }

        $company_branch_id = $data['company_branch_id'];


        $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_branch_id and bottle = 1";
        $product =  Yii::app()->db->createCommand($query)->queryAll();
        //   var_dump($product[0]['price']);
        //   die();
        $product_id = $product[0]['product_id'];

        $totalBottle = $broken_bottle + $perfect ;
        $bottle_record = new  BottleRecord();
        $bottle_record->client_id = $clientID;
        $bottle_record->rider_id = $riderID ;
        $bottle_record->company_id = $company_branch_id;
        $bottle_record->broken = $broken_bottle;
        $bottle_record->perfect = $perfect ;
        $bottle_record->product_id =$product_id ;
        $bottle_record->date = date("Y-m-d");
        $bottle_record->time = date("H:i") ;
        $bottle_record->save();


    }

	public function actionmanageUser()
	{
	   /* $user = User::model()->findAll();

	    foreach ($user as $value){
	        $user =$value['user_id'];
	        $company_id =$value['company_id'];
	        $rider = Rider::model()->findAllByAttributes([
	            'company_branch_id'=>$company_id
            ]);
	        foreach ($rider as $value_rider){

	            $rider_id =$value_rider['rider_id'];
	            $object =new UserRiderRight();
                $object->company_id = $company_id;
                $object->user_id = $user;
                $object->rider_id = $rider_id;
                $object->save();

            }

        }*/

		$this->render('manageUser',array(
			'UserList'=>userData::getUSerList(),
			'rolList'=>userData::getRoleList(),
            "posShopList"=>colorTagData::getShopList(),
		));
	}



	public function actionreciveBillReportReport()
	{


        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $rider_id = $data['rider_id'];

        $payment_mode = $data['payment_mode'];


        $company_id = Yii::app()->user->getState('company_branch_id');
        if($rider_id =='0'){
           $query=" select  u.full_name as user_full_name, c.client_id ,c.fullname ,c.address , c.cell_no_1
            ,pm.date ,pm.amount_paid ,pm.remarks,pm.payment_mode  from payment_master as pm
            left join client as c ON c.client_id = pm.client_id
            left JOIN user AS u ON pm.user_id =u.user_id
            where pm.company_branch_id ='$company_id' and pm.date between '$startDate' and '$endDate'";
            if($payment_mode>0){
                $query.=" and  pm.payment_mode ='$payment_mode'";
            }

        }else{

            $clientQuery = "Select c.client_id ,c.fullname ,c.address from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           where rz.rider_id = '$rider_id'  ";
            $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();


            $client_id_object = array();
            $client_id_object[] =0;
            foreach ($clientResult as $value){
                $client_id_object[] = $value['client_id'];
            }

            $client_id_list =  implode(',',$client_id_object);

            $query=" select  u.full_name as user_full_name, c.client_id ,c.fullname ,c.address , c.cell_no_1
            ,pm.date ,pm.amount_paid ,pm.remarks,pm.payment_mode  from payment_master as pm
            left join client as c ON c.client_id = pm.client_id
            left JOIN user AS u ON pm.user_id =u.user_id
            where pm.client_id IN ($client_id_list) and pm.date between '$startDate' and '$endDate'";

            if($payment_mode>0){
                $query.=" and  pm.payment_mode ='$payment_mode'";
            }

        }


        $productList =  Yii::app()->db->createCommand($query)->queryAll();

        $client_ids_array =array();
        $client_ids_array[] =0;
        foreach ($productList as $value){
            $client_id =  $value['client_id'];
            $client_ids_array[] =$client_id ;
        }

        $client_ids_list = implode(',' ,$client_ids_array);

        $rider_query =" SELECT c.client_id ,r.fullname  FROM client AS c
            LEFT JOIN rider_zone AS rz ON rz.zone_id = c.zone_id
            LEFT JOIN rider AS r ON r.rider_id = rz.rider_id
            WHERE c.client_id in ($client_ids_list) AND r.fullname IS NOT NULL
            GROUP BY c.client_id ";
        $rider_list =  Yii::app()->db->createCommand($rider_query)->queryAll();

         $rider_object = array();

         foreach($rider_list as $value){
             $client_id = $value['client_id'];
             $rider_object[$client_id] =$value['fullname'];
         }
         $final_result = array();

         foreach($productList as $value){


             $oneObject = array();
             $client_id =$value['client_id'];
             $oneObject['user_full_name'] =$value['user_full_name'];
             $oneObject['client_id'] =$value['client_id'];
             $oneObject['fullname'] =$value['fullname'];
             $oneObject['address'] =$value['address'];
             $oneObject['cell_no_1'] =$value['cell_no_1'];
             $oneObject['date'] =$value['date'];
             $oneObject['amount_paid'] =$value['amount_paid'];
             $oneObject['remarks'] =$value['remarks'];
             $oneObject['payment_mode'] =$value['payment_mode'];

             if(isset($rider_object[$client_id])){
                 $oneObject['rider_fullname'] =$rider_object[$client_id];
             }else{
                 $oneObject['rider_fullname'] ='';
             }

             $final_result[]=$oneObject;
         }

        echo json_encode($final_result);


	}

	public function actionreciveBillReport()
	{
		$this->render('ReciveBillReport',array(
            'clientList'=>json_encode(array()),
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
		));
	}

    public function actionsaveNewUser(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo userData::saveNewUserFunction($data);
    }

    public function actiondelete(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo userData::deleteFunction($data);
    }
    public function actioneditUser(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo userData::editUserFunction($data);
    }

    public function actioncheckAlredyExist(){
        $post = file_get_contents("php://input");

        echo userData::checkAlredyExistFunction($post);
    }

    public function actionviewRole(){
        $post = file_get_contents("php://input");
        echo userData::viewRoleFunction($post);
    }




	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param User $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actioncheckPayment_export(){


        $query="SELECT 
        c.client_id,
        c.fullname,
        c.address,
        c.company_branch_id,
        co.company_name,
         p.payment_date
        FROM payment_detail AS p
        left JOIN client AS c ON c.client_id = p.client_id
        LEFT JOIN company AS co ON co.company_id =c.company_branch_id
        WHERE p.payment_date  = '2019-09-03'
        GROUP BY c.client_id ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();



        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=file.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo "client_id,fullname , address,company_branch_id,company_name,payment_date";
        echo  "\r\n" ;

        foreach($queryResult as $value){

            echo $value['client_id'].',';
            echo $value['fullname'].',';
            echo $value['address'].',';
            echo $value['company_branch_id'].',';
            echo $value['company_name'].',';
            echo $value['payment_date'].',';
            echo  "\r\n" ;
        }
    }
    public function actioncheck_sms(){



           $company_branch_id =14 ;
           $company_branch_id =12 ;
           $company_branch_id =4 ;
           $company_branch_id =6 ;
           $company_branch_id =15 ;
          // $company_branch_id =16 ;
           //$company_branch_id =1;
          $query="SELECT * FROM sms_record s
           WHERE s.company_id = '$company_branch_id' and s.date = '2019-12-02'
            AND s.text_message LIKE '%We have received Rs%'";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();



       // header("Content-type: text/csv");
       // header("Content-Disposition: attachment; filename=file.csv");
       // header("Pragma: no-cache");
       // header("Expires: 0");
       // echo "client_id,company_branch_id,payment";
       // echo  "\r\n" ;

         
        foreach($queryResult as $value){
           

            $text_message =  $value['text_message'];
            $array =   explode("Rs.",$text_message);

            $arr = explode(' ',trim($array[1]));
             $value['sms_record_id'].',';
              $client_id = $value['client_id'];
           
               $payment = $arr[0];






             $pay =New PaymentMaster();
             $pay->client_id = $client_id;
             $pay->company_branch_id = $company_branch_id;
             $pay->date = '2019-12-02';
             $pay->time = '4:05:01';
             $pay->bill_month_date = '2019-11-30';
             $pay->amount_paid = $payment;
             $pay->payment_mode = 3;
             $pay->remarks = 'no marks tan';

            if($pay->save()){
                   echo 1;
             }else{
                 echo "<pre>";
                  print_r($pay->getErrors());
                  die();
             }





        }
    }
    public function actioneditUseruser_wisr_rider(){
        $user_id = file_get_contents("php://input");
        //$data = CJSON::decode($post, TRUE);
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query = "SELECT 
            r.rider_id,
            r.fullname,
            u.user_id
            FROM rider AS r
           left JOIN user_rider_right AS u 
               ON u.rider_id = r.rider_id AND u.user_id ='$user_id'
            WHERE r.company_branch_id ='$company_id' AND r.is_active = '1' 
            ORDER BY r.fullname";


        $result =  Yii::app()->db->createCommand($query)->queryAll();
        $list =[];
        foreach ($result as $value){
           $user_id =  $value['user_id'];
           $value['selected'] = isset($user_id)?true:false;
           $list[]= $value;
        }

        echo json_encode($list);

    }
    public function actioneditUseruser_wisr_ridr_save(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $riderlist = $data['riderlist'];
        $company_id = Yii::app()->user->getState('company_branch_id');
        $user_id = $data['user_id'];

        UserRiderRight::model()->deleteAllByAttributes([
            'user_id'=>$user_id
        ]);
        foreach ($riderlist as $value){
              $selected =$value['selected'];
              if($selected){
                  $object = new UserRiderRight();
                  $object->rider_id =$value['rider_id'];
                  $object->user_id =$user_id;
                  $object->company_id =$company_id;
                  $object->save();
              }
        }

    }
}
