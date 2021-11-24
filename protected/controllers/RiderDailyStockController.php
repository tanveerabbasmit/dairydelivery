<?php

class RiderDailyStockController extends Controller
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

    public function actionsaveDeliveryFromPortal(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $deliveredQuantity = $data['deliveredQuantity'];
        if($deliveredQuantity>0){
            $check_allow = advance_right_data::check_advance_right_funation('delivery_edit');
        }else{
            $check_allow = advance_right_data::check_advance_right_funation('delivery_add');
        }


          if(!$check_allow){
              $response = array(
                  'success' => false,
                  'message'=>'You are not allowed to perform this action',
              );
              echo json_encode($response);
               die();
          }



        // $selectDate = $data['selectDate'];
        // $data['data'][0]['deliveredQuantity'];
        $selectedDate = $data['selectDate'];
        $todayDate = date("Y-m-d");
        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'];
        $productObject = $data['data'];
        $totalAmount = 0 ;
        foreach($productObject as $value){
            $totalAmount = $totalAmount + ($value['price'] * $value['quantity']);
        }
        $companyObject  =  utill::get_companyTitle($company_branch_id);
        $roleID =  Yii::app()->user->getState('role_id');

        $roleObject =Role::model()->findByPk($roleID);

        $sadmin_yes_or_no =  $roleObject['sadmin_yes_or_no'];

        $response = array(
            'code' => 200,
            'company_branch_id'=>0,
            'delivery_time'=>date("H:i"),
            'success' => false,
            'message'=>'You are not Allow to edit previous date',
            'data' => []
        );

        $roleID =  Yii::app()->user->getState('user_id');
        $user_object = User::model()->findByPk($roleID);

        if($company_branch_id ==1){

           $supper_admin_user = $user_object['supper_admin_user'];

           if($supper_admin_user==0 && $selectedDate < $todayDate && $data['data'][0]['deliveredQuantity']>0){
              echo  json_encode($response);
              die();
           }

           if($supper_admin_user==0 && $selectedDate < $todayDate && $data['data'][0]['deliveredQuantity']=='0'){

                $client_id =  $data['client_id'];
                $client_object =  Client::model()->find($client_id);

                $zone_id =  $client_object['zone_id'];

                $rider_zone = RiderZone::model()->findByAttributes([
                    'zone_id'=>$zone_id
                ]);

               $rider_id =  $rider_zone['rider_id'];


               $response = array(
                   'code' => 200,
                   'company_branch_id'=>0,
                   'delivery_time'=>date("H:i"),
                   'success' => false,
                   'message'=>'You are not Allow to Add previous date',
                   'data' => []
               );

               $diff = strtotime($todayDate) - strtotime($selectedDate);


               $days_between_two_date =  abs(round($diff / 86400));


               if($roleID !='46' && $days_between_two_date>3){

                    echo  json_encode($response);
                    die();


                }

            }

        }else{
            if($user_object['delivery_edit'] ==0  && $selectedDate < $todayDate){
                echo  json_encode($response);
                die();
            }
        }

        echo    mangeDelivery::saveDeliveryForPortal($data ,$totalAmount , $companyObject);
    }
    public function actionsaveDeliveryFromPortal_delete_delivery(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $check_allow = advance_right_data::check_advance_right_funation('delivery_delete');



        if(!$check_allow){
            $response = array(
            'success' => false,
            'message'=>'You are not allowed to perform this action',
            );
            echo json_encode($response);
            die();
        }

        if($data['code'] !='44332211'){

              $responce = [];
              $responce['success'] =false;
              $responce['message'] ="Code is not correct.";
              echo json_encode($responce);
               die();
        }



        $roleID =  Yii::app()->user->getState('user_id');
        $user_object = User::model()->findByPk($roleID);

        if($user_object['delivery_delete']==0){
            $responce = [];
            $responce['success'] =false;
            $responce['message'] ="Are you not allowed to delete this.";
            echo json_encode($responce);
            die();
        }


        $action_name ='delete_delivery';
        $modify_table_name ='delivery_detail';
        $modify_id = $data['client_id'];
        $client_id = $data['client_id'];
        $selected_date = $data['selectDate'];
        $remarks = $data['remarks'];
        $data_befour_action =json_encode($data);
        //deleted value
        $new_value = $data['data'][0]['deliveredQuantity'];



        save_every_crud_record::save_crud_record_date_waise(
            $action_name,
            $modify_table_name,
            $modify_id,
            $selected_date,
            $data_befour_action,
            $new_value,
            $client_id,
            $remarks
        );


        $selectedDate = $data['selectDate'];
        $client_id = $data['client_id'];
       $company_branch_id = $data['company_branch_id'];
        $productObject = $data['data'];

        $product_id = $productObject[0]['product_id'];

        $delivery_object = Delivery::model()->findByAttributes([
            'client_id'=>$client_id,
            'date'=>$selectedDate,
        ]);

        $delivery_id =$delivery_object['delivery_id'];

        $delivery_detail = DeliveryDetail::model()->findAllByAttributes([
            'delivery_id'=>$delivery_id
        ]);

        if(sizeof($delivery_detail)==1){
            $delivery_detail_id = $delivery_detail[0]['delivery_detail_id'];
            DeliveryDetail::model()->deleteByPk($delivery_detail_id);
            Delivery::model()->deleteByPk($delivery_id);
        }

        if(sizeof($delivery_detail)>1){
            foreach ($delivery_detail as $value){

                $delivery_product = $value['product_id'];

                if($delivery_product ==$product_id){
                   $delivery_detail_id =  $value['delivery_detail_id'];
                    DeliveryDetail::model()->deleteByPk($delivery_detail_id);
                }
            }

            setTotalAmount::total_amount($delivery_id);
        }

        $responce = [];
        $responce['success'] =true;

        echo json_encode($responce);

    }

    public function actionriderDialyDilivery(){


        $company_id = Yii::app()->user->getState('company_branch_id');

        $product_list = productData::getproductList(0);

        $default_product_id = check_default_product::get_product_id();

        $payment_term = zoneData::get_payment_term();

        $getRiderList = riderData::get_rider_of_rider_delivery();
        $getRiderList = str_replace("'","/",$getRiderList);


        $this->render('riderdialyDilivery',array(
            'riderList'=>$getRiderList,
            'company_id'=>$company_id,
            'product_list'=>$product_list,
            'default_product_id'=>$default_product_id,
            'payment_term'=>$payment_term,
        ));

    }

    public function actiondateWisedeliveryReport(){

        $get_data = $_GET;



         if(isset($get_data['date'])){
             $selectDate = $get_data['date'] ;
             $client_id = $get_data['client_id'] ;
         }else{
             $selectDate = date("Y-m-d");
             $client_id = false ;
         }

         $end_date =date("Y-m-d");

         if(isset($get_data['start_date'])){

            $selectDate = $get_data['start_date'];

            $end_date = $get_data['end_date'];

         }

        $date_object = [];

        $date_object['start_date'] = $selectDate;

        $date_object['end_date'] = $end_date;


        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT p.product_id ,p.name ,p.unit  from   product as p 
                where p.company_branch_id =$company_id and bottle = 0  ";

        $productList =  Yii::app()->db->createCommand($query)->queryAll();
       
        $lableObject = array();

        foreach($productList as $value){
            $oneObject = array();
            $oneObject['quantity'] = 'Rate' ;
            $lableObject[] = $oneObject;
            $oneObject['quantity'] = 'Quantity' ;
            $lableObject[] = $oneObject;
            $oneObject['quantity'] = 'Amount' ;
            $lableObject[] = $oneObject;
        }

        $this->render('dateWisedeliveryReport',array(
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'todayData' =>json_encode([]),
            'productList'=>json_encode($productList),
            'lableObject'=>json_encode($lableObject),
            'selectDate'=>json_encode($date_object),

        ));
    }
    public function actiondateWiseRiderSampleDelivery(){

         $product = productData::product_list();

         $this->render('dateWiseRiderSampleDelivery',array(
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'todayData' =>dateWisedeliveryReport_data::dateWisedeliveryReport_allData($selectDate = false , $client_id = false),
            'productList'=>json_encode($product),
            'lableObject'=>json_encode(array()),

        ));
    }
    public function actionnotDateWisedeliveryReport(){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT p.product_id ,p.name ,p.unit  from   product as p 
                where p.company_branch_id =$company_id and bottle = 0  ";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();

        $lableObject = array();
        foreach($productList as $value){
            $oneObject = array();
            $oneObject['quantity'] = 'Quantity' ;

            $lableObject[] = $oneObject;
            $oneObject['quantity'] = 'Amount' ;
            $lableObject[] = $oneObject;
        }


        $this->render('notDateWisedeliveryReport',array(
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'todayData' =>json_encode([]),
            'productList'=>json_encode($productList),
            'lableObject'=>json_encode($lableObject),

        ));
    }

    public function actiongetDialyDeliveryCustomer_report(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,true);
        $RiderID = $data['RiderID'];
        if($RiderID ==0){

            $client_id = false;
        }else{

            $clientQuery = "Select c.client_id from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           where rz.rider_id = $RiderID  ";

            $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

            $cientID = array();
            $cientID[] = 0;
            foreach($clientResult as $value){
                $cientID[] =  $value['client_id'];
            }
            $client_id = implode(',',$cientID);
        }

        $selectDate = $data['date'];

        echo dateWisedeliveryReport_data::dateWisedeliveryReport_allData($selectDate , $client_id);
    }
    public function actiongetDialyDeliveryCustomer_report2(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,true);
        $RiderID = $data['RiderID'];
        if($RiderID ==0){

            $client_id = 'no';

        }else{

            $clientQuery = "SELECT d.client_id from delivery AS d 
                          WHERE d.rider_id ='$RiderID'  ";
            $clientQuery = "Select c.client_id,c.address , c.cell_no_1 ,c.fullname ,z.name as zone_name from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           where rz.rider_id = '$RiderID'  ";

            $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
            $cientID = array();

            $cientID[] = 0;

            foreach($clientResult as $value){
                $cientID[] =  $value['client_id'];
            }
            $client_id = implode(',',$cientID);
        }
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $product_id = $data['product_id'];
        echo dateWisedeliveryReport_data::dateWisedeliveryReport_allData_of_one_product($startDate ,$endDate, $client_id,$product_id);
    }


    public function actiongetDialyDeliveryCustomer_report2_shop_data(){


        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,true);

        $startDate = $data['startDate'];
        $endDate = $data['endDate'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query =" SELECT u.full_name, p.name ,shp.shop_name , sum(ps.quantity) AS quantity , SUM(ps.total_price) AS total_price  
            FROM pos AS ps 
            LEFT JOIN pos_shop AS shp ON ps.pos_shop_id =shp.pos_shop_id
            LEFT JOIN product AS p ON p.product_id = ps.product_id
            LEFT JOIN user AS u ON u.user_id = ps.user_id
            WHERE ps.company_id = '$company_id' AND ps.DATE BETWEEN '$startDate' AND '$endDate'
            GROUP BY ps.product_id ,ps.pos_shop_id ";




        $clientResult =  Yii::app()->db->createCommand($query)->queryAll();


         echo json_encode($clientResult);


    }

    public function actiongetDateWiseRiderSampleDelivery_report(){


        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,true);

        $spacial_order_list = manageSpecialOrderDATA::getviewAllFunction_spacial_order($data);
        $sampleCustomer_new = dateWisedeliveryReport_data::dateWisedeliveryReport_sampleCustomer_new($data);
        $result  =[];
        $result['spacial_order_list']  =$spacial_order_list;
        $result['sampleCustomer_new']  =$sampleCustomer_new;

         echo json_encode($result);

    }
    public function actionnot_getDialyDeliveryCustomer_report(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,true);
        echo dateWisedeliveryReport_data::not_dateWisedeliveryReport_allData($data);
    }

    public function actiongetDialyDeliveryCustomer(){

       $post = file_get_contents("php://input");
       $data = CJSON::decode($post ,true);
       echo portal_radier_daily_delivery_class::getRiderDialyDeliveryReport_for_portal($data);

    }

    public function actionexportDialyDeliveryCustomer(){

        $date = $_GET['date'];

        $riderID = $_GET['riderID'];

        $product_id = $_GET['product_id'];
        $payment_term_id = $_GET['payment_term_id'];



        if(empty($riderID)){
            $company_id = Yii::app()->user->getState('company_branch_id');
            $this->render('riderdialyDilivery',array(
                'riderList'=>json_encode(riderDailyStockData::getRiderList()),
                'company_id'=>$company_id,
            ));
        }else{
            riderDailyStockData::ExportgetRiderDialyDeliveryReport($date ,$riderID,$product_id,$payment_term_id);
        }

    }


    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new RiderDailyStock;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['RiderDailyStock']))
        {
            $model->attributes=$_POST['RiderDailyStock'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->rider_daily_stock_id));
        }

        $this->render('create',array(
            'model'=>$model,
        ));
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

        if(isset($_POST['RiderDailyStock']))
        {
            $model->attributes=$_POST['RiderDailyStock'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->rider_daily_stock_id));
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
    public function actionriderStock()
    {

        $data = array(
            'riderList' => riderDailyStockData::getRiderList_and_pic_quantity(date("Y-m-d")),
            'productList' => riderDailyStockData::getProductList(),
            'currentDate' => date("Y-m-d"),

            'ridersListForGrid' => RiderDailyStock::getDailyRiderStock(date("Y-m-d"))
        );

        //echo "<pre>";print_r($data);exit;
        $this->render('riderStock',array(
            'data'=>json_encode($data),
        ));
    }

    public function actionSaveNewStock()
    {
        $res = array(
            'status' => false,
            'message' => '',
            'data'	=> array(),
        );
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);
        if (!empty($data)) {
            foreach ($data['riderProductList'] as $row) {
                if ($row['select'] == 'true') {
                    $model = new RiderDailyStock();
                    $model->rider_id = $data['rider_id'];
                    $model->product_id = $row['product_id'];
                    $model->date = date("Y-m-d");
                    $model->quantity = $row['allocateStock'];
                    $model->return_quantity = 0;
                    $model->save();
                }
            }
            $res = array(
                'status' => true,
                'message' => '',
                'data'	=> array(
                    'ridersListForGrid' => RiderDailyStock::getDailyRiderStock()
                ),
            );
        }
        echo json_encode($res);
        exit;
    }

    public function actionsaveRiderDialyStock(){
        $company_id = Yii::app()->user->getState('company_branch_id');

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);
        $productID = $data['productID'];
        $currentDate = $data['currentDate'];
        $riderProductList= $data['riderProductList'];
        foreach($riderProductList as $value){
            if(isset($value['selectQuantity'])){
                if($value['selectQuantity'] !=''){
                    $riderID =  $value['rider_id'];
                    $todayDate  = $currentDate;


                    $riderdaiLStockObject = RiderDailyStock::model()->findbyattributes(array('rider_id'=>$riderID , 'date'=>$todayDate,'product_id'=>$productID));
                    if($riderdaiLStockObject){

                        $quantity_data =$riderdaiLStockObject['quantity'] + $value['selectQuantity'];
                        $riderdaiLStockObject->quantity=$quantity_data;
                        if($riderdaiLStockObject->save()){

                            $Result = array(
                                'status' => true,
                                'message' => '',
                                'data'	=>[],
                            );
                        }


                    }else{

                        $model = new RiderDailyStock();
                        $model->rider_id = $value['rider_id'];
                        $model->product_id = $productID;
                        $model->date = $currentDate;
                        $model->quantity = $value['selectQuantity'];
                        $model->return_quantity = 0;
                        $model->company_branch_id = $company_id;
                        if($model->save()){
                            $Result = array(
                                'status' => true,
                                'message' => '',
                                'data'	=>[],
                            );
                        }
                    }

                }
            }
            if(isset($value['wastage_quantity'])){
                if($value['wastage_quantity'] !=''){



                    $riderID =  $value['rider_id'];
                    $todayDate  = $currentDate;


                    $riderdaiLStockObject = RiderDailyStock::model()->findbyattributes(array('rider_id'=>$riderID , 'date'=>$todayDate,'product_id'=>$productID));
                    if($riderdaiLStockObject){

                        $wastage_quantity =$riderdaiLStockObject['wastage_quantity'] + $value['wastage_quantity'];
                        $riderdaiLStockObject->wastage_quantity=$wastage_quantity;
                        if($riderdaiLStockObject->save()){

                            $Result = array(
                                'status' => true,
                                'message' => '',
                                'data'	=>[],
                            );
                        }


                    }else{

                        $model = new RiderDailyStock();
                        $model->rider_id = $value['rider_id'];
                        $model->product_id = $productID;
                        $model->date = $currentDate;
                        $model->quantity = 0;
                        $model->wastage_quantity = $value['wastage_quantity'];
                        $model->return_quantity = 0;
                        $model->company_branch_id = $company_id;
                        if($model->save()){
                            $Result = array(
                                'status' => true,
                                'message' => '',
                                'data'	=>[],
                            );
                        }
                    }

                }
            }

        }
        echo json_encode($Result);
        exit;

    }

    public function actionReturnSaveDetail(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);
        $company_id = Yii::app()->user->getState('company_branch_id');
        $productID = $data['productID'];
        $currentDate = $data['currentDate'];
        $riderProductList= $data['riderProductList'];
        foreach($riderProductList as $value){
            if(isset($value['selectQuantity'])){
                if($value['selectQuantity'] !=''){
                    $model = new RiderDailyStock();
                    $model->rider_id = $value['rider_id'];
                    $model->product_id = $productID;
                    $model->date = $currentDate;
                    $model->quantity = 0;
                    $model->return_quantity = $value['selectQuantity'];
                    $model->company_branch_id = $company_id;
                    if($model->save()){
                        $Result = array(
                            'status' => true,
                            'message' => '',
                            'data'	=>[],
                        );
                    }
                }
            }
        }
        echo json_encode($Result);
        exit;

    }


    public function actionGetRiderProducts()
    {
        $res = array(
            'status' => false,
            'message' => '',
            'data'	=> array(),
        );
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);
        if (isset($data['rider_id'])) {
            $riderDailyStockRequired = Product::getRiderDailyStockRequired($data['rider_id']);
            $res = array(
                'status' => true,
                'message' => '',
                'data'	=> array(
                    'riderDailyStockRequired' => $riderDailyStockRequired,
                ),
            );
        } else {
            $res = array(
                'status' => false,
                'message' => 'Select Rider',
                'data'	=> array(),
            );
        }
        echo json_encode($res);
        exit;
    }

    public function actionRiderStockDetail()
    {
        $res = array(
            'status' => false,
            'message' => '',
            'data'	=> array(),
        );
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);
        if (isset($data['rider_id'])) {
            $res = array(
                'status' => true,
                'message' => '',
                'data'	=> array(
                    'riderStockDetailList' => RiderDailyStock::getRiderDailyStockDetails($data['rider_id'], $data['date']),
                ),
            );
        } else {
            $res = array(
                'status' => false,
                'message' => 'Select Rider',
                'data'	=> array(),
            );
        }
        echo json_encode($res);
        exit;
    }

    public function actionSaveRiderStock()
    {
        $res = array(
            'status' => false,
            'message' => '',
            'data'	=> array(),
        );
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);
        if (!empty($data)) {
            $model = RiderDailyStock::model()->findByPk($data['rider_daily_stock_id']);
            $model->attributes = $data;
            if ($model->save()) {
                $res = array(
                    'status' => true,
                    'data' => array(
                        'ridersListForGrid' => RiderDailyStock::getDailyRiderStock()
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

    public function actiongetRiderDailyStock(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);
        $rider_id =$data['riderID'];
        $currentDate =$data['currentDate'];
        $query ="Select
                 p.name ,
                 sum(rds.quantity) as recive ,
                 sum(rds.wastage_quantity) as wastage_quantity ,
                 sum(rds.return_quantity) as return_stock ,
                 (sum(rds.quantity) - sum(rds.return_quantity) -sum(rds.wastage_quantity)) as net_stock 
                 from rider_daily_stock rds
                 left join product as 
                     p ON p.product_id = rds.product_id
                 where rds.rider_id = '$rider_id' 
                     AND rds.date = '$currentDate'
                 Group by rds.product_id";


        $queryResult = Yii::app()->db->createCommand($query)->queryAll();
        echo  json_encode($queryResult);
    }

    public function actionDeleteRiderStock()
    {
        $res = array(
            'status' => false,
            'message' => '',
            'data'	=> array(),
        );
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);
        if (!empty($data)) {
            //echo "<pre>";print_r($data);exit;
            $model = RiderDailyStock::model()->findByPk($data['rider_daily_stock_id']);
            if ($model->delete()) {
                $res = array(
                    'status' => true,
                    'data' => array(
                        'ridersListForGrid' => RiderDailyStock::getDailyRiderStock($data['date']),
                        'riderStockDetailList' => RiderDailyStock::getRiderDailyStockDetails($data['rider_id'], $data['date']),
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

    public function actionSearch()
    {

        $res = array(
            'status' => false,
            'message' => '',
            'data'	=> array(),
        );
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);
        if (!empty($data)) {
            $ridersListForGrid = RiderDailyStock::getDailyRiderStock($data);
            if (!empty($ridersListForGrid)) {
                $res = array(
                    'status' => true,
                    'message' => '',
                    'data'	=> array(
                        'ridersListForGrid' => $ridersListForGrid
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

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model=new RiderDailyStock('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['RiderDailyStock']))
            $model->attributes=$_GET['RiderDailyStock'];
        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return RiderDailyStock the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=RiderDailyStock::model()->findByPk($id);
        if($model===null)
          //  throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param RiderDailyStock $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='rider-daily-stock-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionsaveDeliveryFromPortal_save_new_rate(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);
        $client_id = $data['client_id'];
        $product_id = $data['product_id'];
        $client_product_price =ClientProductPrice::model()->findByAttributes(
            array('client_id'=>$client_id,'product_id'=>$product_id)
        );
        if($client_product_price){
            $client_product_price->price = $data['new_product_rate'];
            if($client_product_price->save()){

            }else{
                var_dump($client_product_price->getErrors());
            }
        }else{
            $client_product_price = new ClientProductPrice();
            $client_product_price->client_id = $client_id;
            $client_product_price->product_id = $data['product_id'];
            $client_product_price->price = $data['new_product_rate'];
            if($client_product_price->save()){

            }else{
                var_dump($client_product_price->getErrors());
            }
        }
    }
    public function actionsaveDeliveryFromPortal_get_user_currect_balance(){
        $post = file_get_contents("php://input");
        $amount =  APIData::calculateFinalBalance($post);
        echo round($amount,0);
    }
}
