<?php

class ClientController extends Controller
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



    public function actionmanageClient()
    {

            $get_dat =   $_GET;
            $selectClientObject = array();

           if(isset($get_dat['edit'])){
               $selectClientObject['edit'] = true;
                $oneObject = clientData::getOneCutomerObject($get_dat['client_id']);
               $selectClientObject['oneSchoolObject'] =$oneObject;


           }else{
               $selectClientObject['edit'] = false;
           }
           if(isset($get_dat['client_id'])){
              $client_id = $get_dat['client_id'];
               $clintObject = Client::model()->findByPk(intval($client_id));
               $selectClientObject['dashBordaction'] =  true;
               $selectClientObject['client_id'] =  $client_id;
               $selectClientObject['client_name'] =  $clintObject['fullname'];
           }else{
               $selectClientObject['dashBordaction'] =  false;
               $selectClientObject['client_id'] =  '';
               $selectClientObject['client_name'] =  '';
           }
        $company_id = Yii::app()->user->getState('company_branch_id');

        $sub_company_list = companyBranchData::get_sub_company_list();



          /* query_Rider_zone Start*/

        $query_Rider_zone = "SELECT r.fullname ,rz.zone_id FROM rider AS r
           LEFT JOIN rider_zone AS rz ON r.rider_id = rz.rider_id
          WHERE r.company_branch_id =$company_id AND r.is_active =1 ";


        $rider_zone = Yii::app()->db->createCommand($query_Rider_zone)->queryAll();

         $rider_zone_object = array();
         foreach($rider_zone as $value){
             $zone_id = $value['zone_id'];
             $fullname = $value['fullname'];
             $rider_zone_object[$zone_id] =$fullname;
         }

        /* query_Rider_zone End*/
        $query_onlineCumtomer = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 1 and c.LastTime_login != '0000-00-00 00:00:00'";
       $fetch_onlineCumtomer_activeCustomer = Yii::app()->db->createCommand($query_onlineCumtomer)->queryScalar();
        $query_offLneCusytomer_activeCustomer = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 1 and c.LastTime_login = '0000-00-00 00:00:00'";

       $fetch_offlieCumtomer_activeCustomer = Yii::app()->db->createCommand($query_offLneCusytomer_activeCustomer)->queryScalar();

         $query_onlineCustomer__inactive = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 0 and c.LastTime_login != '0000-00-00 00:00:00' ";

        $fetch_onlineCumtomer_inactiveCustomer = Yii::app()->db->createCommand($query_onlineCustomer__inactive)->queryScalar();


        $query_offlineCustomer__inactive = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 0 and c.LastTime_login = '0000-00-00 00:00:00' ";

        $fetch_offlineCumtomer_inactiveCustomer = Yii::app()->db->createCommand($query_offlineCustomer__inactive)->queryScalar();

         $CustomerData = array();
          $CustomerData['fetch_onlineCumtomer_activeCustomer'] = $fetch_onlineCumtomer_activeCustomer;
          $CustomerData['fetch_offlieCumtomer_activeCustomer'] = $fetch_offlieCumtomer_activeCustomer;
          $CustomerData['fetch_onlineCumtomer_inactiveCustomer'] =$fetch_onlineCumtomer_inactiveCustomer ;
          $CustomerData['fetch_offlineCumtomer_inactiveCustomer'] = $fetch_offlineCumtomer_inactiveCustomer;

        $active = "select count(*) as taotal from client where is_active = 1 and  company_branch_id = $company_id ";
        $activeResult = Yii::app()->db->createCommand($active)->queryAll();
         $activeRecord = ($activeResult[0]['taotal']);
        $inactive = " select count(*) as taotal from client where is_active = 0 and  company_branch_id = $company_id ";
        $inactiveResult = Yii::app()->db->createCommand($inactive)->queryAll();
        $inactiveCount = ($inactiveResult[0]['taotal']);
        $page = '';

        $data = [];
        $payment_term_list =    zoneData::get_payment_term();


        $product_list = productData::getproductList(1);



        $this->render('manageClient',array(
             'product_list'=>$product_list,
             'sub_company_list'=>json_encode($sub_company_list),
             'preferedTimeList'=>clientData::getPreferedTimeList(),
             'clientList'=>clientData::getClientList($page = 1 ,$zone_id =0 ,$status=0),
             'clientCount'=>json_encode([]),
             'zoneList'=>clientData::getZoneList(),
             'blockList'=>blockData::getBlockList(),
             'areaList'=>areaData::getAreaList(),
             'frequencyList'=>clientData::getFrequencyList(),
             'productCount'=>productData::getproductCount(),
             'activeRecord'=>$activeRecord,
             'inactiveCount'=>$inactiveCount,
             'CustomerData' =>$CustomerData ,
             'ClientOneObject' =>json_encode($selectClientObject),
             'companyID'=>Yii::app()->user->getState('company_branch_id'),
              "CategoryList"=>categoryData::getCategoryList(),
              "reasonList"=>dropClientReasonData::getReasonList(),
              "company_id"=> $company_id,
              "rider_zone_object"=> json_encode($rider_zone_object),
              "payment_term_list"=> $payment_term_list,
              "customer_source_list"=> customer_source::getCustomer_source_list()
        ,
        ));

    }

    public function actionnew_sign_up_customer_approve(){

       $company_id = Yii::app()->user->getState('company_branch_id');

       $get_data = $_GET;
       $client_id =  $get_data['client_id'];

       $object = Client::model()->findByPk($client_id);

       if($object['company_branch_id'] !=$company_id ){
           $this->redirect(['error']);
       }

       $object->is_approved = 1 ;

        if($object->save()){

            $company = Company::model()->findByPk($company_id);

            $companyMask = $company['sms_mask'];

             $userName = $object['userName'];
            $password = $object['password'];
            $message = 'Your account request has been accepted. ';

            $message .= ' user : '.$userName ;
            $message .= ' password : '.$password ;


            smsLog::saveSms($client_id, $company_id, $object['cell_no_1'],$object['fullname'], $message);
            utill::sendSMS2($object['cell_no_1'], $message, $companyMask, $company_id, 1, $client_id);


        }else{
            echo "<pre>";
            print_r($object->getErrors());
            die();
        }

        $this->redirect(['new_sign_up_customer']);
    }

    public function actionnew_sign_up_customer()
    {

            $get_dat =   $_GET;
            $selectClientObject = array();

           if(isset($get_dat['edit'])){
               $selectClientObject['edit'] = true;
                $oneObject = clientData::getOneCutomerObject($get_dat['client_id']);
               $selectClientObject['oneSchoolObject'] =$oneObject;


           }else{
               $selectClientObject['edit'] = false;
           }
           if(isset($get_dat['client_id'])){
              $client_id = $get_dat['client_id'];
               $clintObject = Client::model()->findByPk(intval($client_id));
               $selectClientObject['dashBordaction'] =  true;
               $selectClientObject['client_id'] =  $client_id;
               $selectClientObject['client_name'] =  $clintObject['fullname'];
           }else{
               $selectClientObject['dashBordaction'] =  false;
               $selectClientObject['client_id'] =  '';
               $selectClientObject['client_name'] =  '';
           }
          $company_id = Yii::app()->user->getState('company_branch_id');

        $sub_company_list = companyBranchData::get_sub_company_list();



          /* query_Rider_zone Start*/

        $query_Rider_zone = "SELECT r.fullname ,rz.zone_id FROM rider AS r
           LEFT JOIN rider_zone AS rz ON r.rider_id = rz.rider_id
          WHERE r.company_branch_id =$company_id AND r.is_active =1 ";


        $rider_zone = Yii::app()->db->createCommand($query_Rider_zone)->queryAll();

         $rider_zone_object = array();
         foreach($rider_zone as $value){
             $zone_id = $value['zone_id'];
             $fullname = $value['fullname'];
             $rider_zone_object[$zone_id] =$fullname;
         }

        /* query_Rider_zone End*/
        $query_onlineCumtomer = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 1 and c.LastTime_login != '0000-00-00 00:00:00'";
       $fetch_onlineCumtomer_activeCustomer = Yii::app()->db->createCommand($query_onlineCumtomer)->queryScalar();
        $query_offLneCusytomer_activeCustomer = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 1 and c.LastTime_login = '0000-00-00 00:00:00'";

       $fetch_offlieCumtomer_activeCustomer = Yii::app()->db->createCommand($query_offLneCusytomer_activeCustomer)->queryScalar();

         $query_onlineCustomer__inactive = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 0 and c.LastTime_login != '0000-00-00 00:00:00' ";

        $fetch_onlineCumtomer_inactiveCustomer = Yii::app()->db->createCommand($query_onlineCustomer__inactive)->queryScalar();


        $query_offlineCustomer__inactive = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 0 and c.LastTime_login = '0000-00-00 00:00:00' ";

        $fetch_offlineCumtomer_inactiveCustomer = Yii::app()->db->createCommand($query_offlineCustomer__inactive)->queryScalar();

         $CustomerData = array();
          $CustomerData['fetch_onlineCumtomer_activeCustomer'] = $fetch_onlineCumtomer_activeCustomer;
          $CustomerData['fetch_offlieCumtomer_activeCustomer'] = $fetch_offlieCumtomer_activeCustomer;
          $CustomerData['fetch_onlineCumtomer_inactiveCustomer'] =$fetch_onlineCumtomer_inactiveCustomer ;
          $CustomerData['fetch_offlineCumtomer_inactiveCustomer'] = $fetch_offlineCumtomer_inactiveCustomer;

        $active = "select count(*) as taotal from client where is_active = 1 and  company_branch_id = $company_id ";
        $activeResult = Yii::app()->db->createCommand($active)->queryAll();
         $activeRecord = ($activeResult[0]['taotal']);
        $inactive = " select count(*) as taotal from client where is_active = 0 and  company_branch_id = $company_id ";
        $inactiveResult = Yii::app()->db->createCommand($inactive)->queryAll();
        $inactiveCount = ($inactiveResult[0]['taotal']);
        $page = '';

        $data = [];
        $payment_term_list =    zoneData::get_payment_term();



        $this->render('new_sign_up_customer',array(
             'sub_company_list'=>json_encode($sub_company_list),
             'preferedTimeList'=>clientData::getPreferedTimeList(),
             'clientList'=>clientData::getClientList_new_sign_up($page = 1 ,$zone_id =0 ,$status=0),
             'clientCount'=>json_encode([]),
             'zoneList'=>clientData::getZoneList(),
             'blockList'=>blockData::getBlockList(),
             'areaList'=>areaData::getAreaList(),
             'frequencyList'=>clientData::getFrequencyList(),
             'productCount'=>productData::getproductCount(),
             'activeRecord'=>$activeRecord,
             'inactiveCount'=>$inactiveCount,
             'CustomerData' =>$CustomerData ,
             'ClientOneObject' =>json_encode($selectClientObject),
             'companyID'=>Yii::app()->user->getState('company_branch_id'),
              "CategoryList"=>categoryData::getCategoryList(),
              "reasonList"=>dropClientReasonData::getReasonList(),
              "company_id"=> $company_id,
              "rider_zone_object"=> json_encode($rider_zone_object),
              "payment_term_list"=> $payment_term_list,
              "customer_source_list"=> customer_source::getCustomer_source_list()
        ,
        ));

    }
    public function actionsampleClient()
    {
            $get_dat =   $_GET;
            $selectClientObject = array();
           if(isset($get_dat['edit'])){
               $selectClientObject['edit'] = true;
                $oneObject = clientData::getOneCutomerObject($get_dat['client_id']);
               $selectClientObject['oneSchoolObject'] =$oneObject;


           }else{
               $selectClientObject['edit'] = false;
           }
           if(isset($get_dat['client_id'])){
              $client_id = $get_dat['client_id'];
               $clintObject = Client::model()->findByPk(intval($client_id));
               $selectClientObject['dashBordaction'] =  true;
               $selectClientObject['client_id'] =  $client_id;
               $selectClientObject['client_name'] =  $clintObject['fullname'];
           }else{
               $selectClientObject['dashBordaction'] =  false;
               $selectClientObject['client_id'] =  '';
               $selectClientObject['client_name'] =  '';
           }
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query_onlineCumtomer = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 1 and c.LastTime_login != '0000-00-00 00:00:00'";
       $fetch_onlineCumtomer_activeCustomer = Yii::app()->db->createCommand($query_onlineCumtomer)->queryScalar();
        $query_offLneCusytomer_activeCustomer = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 1 and c.LastTime_login = '0000-00-00 00:00:00'";

       $fetch_offlieCumtomer_activeCustomer = Yii::app()->db->createCommand($query_offLneCusytomer_activeCustomer)->queryScalar();

         $query_onlineCustomer__inactive = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 0 and c.LastTime_login != '0000-00-00 00:00:00' ";

        $fetch_onlineCumtomer_inactiveCustomer = Yii::app()->db->createCommand($query_onlineCustomer__inactive)->queryScalar();


        $query_offlineCustomer__inactive = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 0 and c.LastTime_login = '0000-00-00 00:00:00' ";

        $fetch_offlineCumtomer_inactiveCustomer = Yii::app()->db->createCommand($query_offlineCustomer__inactive)->queryScalar();

         $CustomerData = array();
          $CustomerData['fetch_onlineCumtomer_activeCustomer'] = $fetch_onlineCumtomer_activeCustomer;
          $CustomerData['fetch_offlieCumtomer_activeCustomer'] = $fetch_offlieCumtomer_activeCustomer;
          $CustomerData['fetch_onlineCumtomer_inactiveCustomer'] =$fetch_onlineCumtomer_inactiveCustomer ;
          $CustomerData['fetch_offlineCumtomer_inactiveCustomer'] = $fetch_offlineCumtomer_inactiveCustomer;

        $active = "select count(*) as taotal from client where is_active = 1 and  company_branch_id = $company_id ";
        $activeResult = Yii::app()->db->createCommand($active)->queryAll();
         $activeRecord = ($activeResult[0]['taotal']);
         $inactive = " select count(*) as taotal from client where is_active = 0 and  company_branch_id = $company_id ";
        $inactiveResult = Yii::app()->db->createCommand($inactive)->queryAll();
        $inactiveCount = ($inactiveResult[0]['taotal']);

        $page = '';
        $this->render('sampleClient',array(
             'preferedTimeList'=>clientData::getPreferedTimeList(),
             'clientList'=>clientData::getSampleClientList($page = 1 ,$zone_id =0 ,$status=0),
             'clientCount'=>json_encode([]),
             'zoneList'=>clientData::getZoneList(),
             'saleRepsList'=>saleRepsData::getSaleRepsList(),
             'blockList'=>blockData::getBlockList(),
             'areaList'=>areaData::getAreaList(),
             'frequencyList'=>clientData::getFrequencyList(),
             'productCount'=>productData::getproductCount(),
             'activeRecord'=>$activeRecord,
             'inactiveCount'=>$inactiveCount,
             'CustomerData' =>$CustomerData ,
             'ClientOneObject' =>json_encode($selectClientObject),
             'companyID'=>Yii::app()->user->getState('company_branch_id'),
              "CategoryList"=>categoryData::getCategoryList(),
              "reasonList"=>dropClientReasonData::getReasonList(),
        ));

    }
    public function actionaddFollowUp()
    {
            $get_dat =   $_GET;
            $selectClientObject = array();
           if(isset($get_dat['edit'])){
               $selectClientObject['edit'] = true;
                $oneObject = clientData::getOneCutomerObject($get_dat['client_id']);
               $selectClientObject['oneSchoolObject'] =$oneObject;


           }else{
               $selectClientObject['edit'] = false;
           }
           if(isset($get_dat['client_id'])){
              $client_id = $get_dat['client_id'];
               $clintObject = Client::model()->findByPk(intval($client_id));
               $selectClientObject['dashBordaction'] =  true;
               $selectClientObject['client_id'] =  $client_id;
               $selectClientObject['client_name'] =  $clintObject['fullname'];
           }else{
               $selectClientObject['dashBordaction'] =  false;
               $selectClientObject['client_id'] =  '';
               $selectClientObject['client_name'] =  '';
           }
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query_onlineCumtomer = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 1 and c.LastTime_login != '0000-00-00 00:00:00'";
       $fetch_onlineCumtomer_activeCustomer = Yii::app()->db->createCommand($query_onlineCumtomer)->queryScalar();
        $query_offLneCusytomer_activeCustomer = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 1 and c.LastTime_login = '0000-00-00 00:00:00'";

       $fetch_offlieCumtomer_activeCustomer = Yii::app()->db->createCommand($query_offLneCusytomer_activeCustomer)->queryScalar();

         $query_onlineCustomer__inactive = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 0 and c.LastTime_login != '0000-00-00 00:00:00' ";

        $fetch_onlineCumtomer_inactiveCustomer = Yii::app()->db->createCommand($query_onlineCustomer__inactive)->queryScalar();


        $query_offlineCustomer__inactive = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 0 and c.LastTime_login = '0000-00-00 00:00:00' ";

        $fetch_offlineCumtomer_inactiveCustomer = Yii::app()->db->createCommand($query_offlineCustomer__inactive)->queryScalar();

         $CustomerData = array();
          $CustomerData['fetch_onlineCumtomer_activeCustomer'] = $fetch_onlineCumtomer_activeCustomer;
          $CustomerData['fetch_offlieCumtomer_activeCustomer'] = $fetch_offlieCumtomer_activeCustomer;
          $CustomerData['fetch_onlineCumtomer_inactiveCustomer'] =$fetch_onlineCumtomer_inactiveCustomer ;
          $CustomerData['fetch_offlineCumtomer_inactiveCustomer'] = $fetch_offlineCumtomer_inactiveCustomer;

        $active = "select count(*) as taotal from client where is_active = 1 and  company_branch_id = $company_id ";
        $activeResult = Yii::app()->db->createCommand($active)->queryAll();
         $activeRecord = ($activeResult[0]['taotal']);
         $inactive = " select count(*) as taotal from client where is_active = 0 and  company_branch_id = $company_id ";
        $inactiveResult = Yii::app()->db->createCommand($inactive)->queryAll();
        $inactiveCount = ($inactiveResult[0]['taotal']);

        $page = '';


        $this->render('addFollowUp',array(
             'todayDate'=>json_encode(date("Y-m-d")),
             'preferedTimeList'=>clientData::getPreferedTimeList(),
             'clientList'=>clientData::addFollowUpClientList($page = 1 ,$zone_id =0 ,$status=3,'0','0','0'),
             'clientCount'=>json_encode([]),
             'zoneList'=>clientData::getZoneList(),
             'saleRepsList'=>saleRepsData::getSaleRepsList(),
             'blockList'=>blockData::getBlockList(),
             'areaList'=>areaData::getAreaList(),
             'frequencyList'=>clientData::getFrequencyList(),
             'productCount'=>productData::getproductCount(),
             'activeRecord'=>$activeRecord,
             'inactiveCount'=>$inactiveCount,
             'CustomerData' =>$CustomerData ,
             'ClientOneObject' =>json_encode($selectClientObject),
             'companyID'=>Yii::app()->user->getState('company_branch_id'),
              "CategoryList"=>categoryData::getCategoryList(),
              "reasonList"=>dropClientReasonData::getReasonList(),
            "dropReasonList"=>dropClientReasonData::getReasonList(),
            "ColorTagList"=>colorTagData::getColorTagList(),
        ));

    }
    public function actiondeleteClient()
    {
             $get_dat =   $_GET;
             $selectClientObject = array();
           if(isset($get_dat['client_id'])){
              $client_id = $get_dat['client_id'];
               $clintObject = Client::model()->findByPk(intval($client_id));
               $selectClientObject['dashBordaction'] =  true;
               $selectClientObject['client_id'] =  $client_id;
               $selectClientObject['client_name'] =  $clintObject['fullname'];
           }else{
               $selectClientObject['dashBordaction'] =  false;
               $selectClientObject['client_id'] =  '';
               $selectClientObject['client_name'] =  '';
           }
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query_onlineCumtomer = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 1 and c.LastTime_login != '0000-00-00 00:00:00'";
       $fetch_onlineCumtomer_activeCustomer = Yii::app()->db->createCommand($query_onlineCumtomer)->queryScalar();
        $query_offLneCusytomer_activeCustomer = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 1 and c.LastTime_login = '0000-00-00 00:00:00'";

       $fetch_offlieCumtomer_activeCustomer = Yii::app()->db->createCommand($query_offLneCusytomer_activeCustomer)->queryScalar();

         $query_onlineCustomer__inactive = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 0 and c.LastTime_login != '0000-00-00 00:00:00' ";

        $fetch_onlineCumtomer_inactiveCustomer = Yii::app()->db->createCommand($query_onlineCustomer__inactive)->queryScalar();


        $query_offlineCustomer__inactive = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 0 and c.LastTime_login = '0000-00-00 00:00:00' ";

        $fetch_offlineCumtomer_inactiveCustomer = Yii::app()->db->createCommand($query_offlineCustomer__inactive)->queryScalar();

         $CustomerData = array();
          $CustomerData['fetch_onlineCumtomer_activeCustomer'] = $fetch_onlineCumtomer_activeCustomer;
          $CustomerData['fetch_offlieCumtomer_activeCustomer'] = $fetch_offlieCumtomer_activeCustomer;
          $CustomerData['fetch_onlineCumtomer_inactiveCustomer'] =$fetch_onlineCumtomer_inactiveCustomer ;
          $CustomerData['fetch_offlineCumtomer_inactiveCustomer'] = $fetch_offlineCumtomer_inactiveCustomer;

        $active = "select count(*) as taotal from client where is_active = 1 and  company_branch_id = $company_id ";
        $activeResult = Yii::app()->db->createCommand($active)->queryAll();
         $activeRecord = ($activeResult[0]['taotal']);
         $inactive = " select count(*) as taotal from client where is_active = 0 and  company_branch_id = $company_id ";
        $inactiveResult = Yii::app()->db->createCommand($inactive)->queryAll();
        $inactiveCount = ($inactiveResult[0]['taotal']);

        $page = '';
        $this->render('deleteClient',array(
             'preferedTimeList'=>clientData::getPreferedTimeList(),
             'clientList'=>clientData::getClientList($page = 1 ,$zone_id =0 ,$status=0),
             'clientCount'=>json_encode([]),
             'zoneList'=>clientData::getZoneList(),
             'frequencyList'=>clientData::getFrequencyList(),
             'productCount'=>productData::getproductCount(),
             'activeRecord'=>$activeRecord,
             'inactiveCount'=>$inactiveCount,
             'CustomerData' =>$CustomerData ,
             'ClientOneObject' =>json_encode($selectClientObject),
             'companyID'=>Yii::app()->user->getState('company_branch_id')
        ));

    }


	public function actioncustomerLedger()
	{
         //clientData::getActiveClientList_forLedger()

        $get_data = $_GET;
        $client_id = isset($get_data['client_id'])?$get_data['client_id']:0;
        $one_client_object =[];
        if($client_id>0){
            $one_client_object = Client::model()->findByPk($client_id)->attributes;

        }

        $client_object = [];
        $client_object['client_object'] = $one_client_object;
        $client_object['client_id'] =$client_id;


		$this->render('customerLedger',array(

            'clientList'=>json_encode(array()),

            'client_object'=>json_encode($client_object)

		));
	}
	public function actioncustomerBottleLedger()
	{


		$this->render('customerBottleLedger',array(
            'clientList'=>clientData::getActiveClientList(),
            'productList'=>productData::getproductList($page =false),
		));

	}

	public function actionmakePayment()
	{
        $company_id = Yii::app()->user->getState('company_branch_id');



        $query_discount = "select * from discount_type as dt
                 where dt.company_id ='$company_id'";

        $discount_type_result = Yii::app()->db->createCommand($query_discount)->queryAll();

        $collectionvault_list = blockData::get_collectionvault();



         $final_discount = array();
         $number =0;
         foreach ($discount_type_result as $value){
             $number++;
             $oneObject = array();
             $oneObject['discount_type_id'] = $value['discount_type_id'];
             $oneObject['discount_type_name'] = $value['discount_type_name'];
             $oneObject['discount_amount'] = '';
             $oneObject['discount_percentage'] = '';
             $oneObject['percentage'] = false;
             $oneObject['calculated_discount'] = '';
             $final_discount[] =$oneObject;
         }
	     $todayMonth = Date('m');

	     $todayYear = Date('Y');

	     $required_name ='';
	     if($company_id==18 or $company_id==20 or $company_id==21 or $company_id==22 ){
             $required_name='required';
         }



          $this->render('makePayment',array(
               // 'clientList'=>clientData::getActiveClientList_forLedger(),
                 'clientList'=>json_encode(array()),
                 'todayMonth'=>$todayMonth ,
                 'todayYear'=>$todayYear ,

                 'required_name'=>$required_name ,

                 'discount_type'=>json_encode($final_discount),
                 'collectionvault_list'=>$collectionvault_list,
                 'crud_role'=>crudRole::getCrudrole(16) ,
            ));
	}
	public function actionmakePayment_bad_debt()
	{
        $company_id = Yii::app()->user->getState('company_branch_id');



        $query_discount = "select * from discount_type as dt
                 where dt.company_id ='$company_id'";

        $discount_type_result = Yii::app()->db->createCommand($query_discount)->queryAll();

        $collectionvault_list = blockData::get_collectionvault();



         $final_discount = array();
         $number =0;
         foreach ($discount_type_result as $value){
             $number++;
             $oneObject = array();
             $oneObject['discount_type_id'] = $value['discount_type_id'];
             $oneObject['discount_type_name'] = $value['discount_type_name'];
             $oneObject['discount_amount'] = '';
             $oneObject['discount_percentage'] = '';
             $oneObject['percentage'] = false;
             $oneObject['calculated_discount'] = '';
             $final_discount[] =$oneObject;
         }
	     $todayMonth = Date('m');

	     $todayYear = Date('Y');

	     $required_name ='';
	     if($company_id==18 or $company_id==20 or $company_id==21 or $company_id==22 ){
             $required_name='required';
         }
          $this->render('makePayment_bad_debt',array(
               // 'clientList'=>clientData::getActiveClientList_forLedger(),
                 'clientList'=>json_encode(array()),
                 'todayMonth'=>$todayMonth ,
                 'todayYear'=>$todayYear ,

                 'required_name'=>$required_name ,

                 'discount_type'=>json_encode($final_discount),
                 'collectionvault_list'=>$collectionvault_list,
                 'crud_role'=>crudRole::getCrudrole(16) ,
            ));
	}

	public function actiongetProductPriceList_recovery_report_view_or_hide(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $client_id = $data['client_id'];
        $recovery_report_view_or_hide = $data['recovery_report_view_or_hide'];

        $object = Client::model()->findByPk($client_id);
        $object->recovery_report_view_or_hide = $recovery_report_view_or_hide;
        $object->save();
    }
	public function actiononeCustomerAmountList_update_payment(){

         $post = file_get_contents("php://input");
         $data = CJSON::decode($post, TRUE);

         $remarks =  $data['remarks'];


         $user_id =  Yii::app()->user->getState('user_id');

         $user_object = User::model()->findByPk($user_id);

         $allow_option_id = $user_object['delivery_payment_edit_delete_option'];

         if($user_object['receipt_edit']==0){
              echo false;
              die();
         }



         $payment_master_id = $data['payment_master_id'];

         $defour_object = PaymentMaster::model()->findByPk(intval($payment_master_id))->attributes;



        $action_name = 'edit_payment';
        $modify_table_name = 'payment_master';
        $modify_id = $data['payment_master_id'];
        $selected_date = $data['date'];
        $collection_vault_id = $data['collection_vault_id'];
       $data_befour_action = json_encode($defour_object);

        $new_value =$data['amount_paid'];
        $payment_master_id = $data['payment_master_id'];
        $object = PaymentMaster::model()->findByPk($payment_master_id);


        $client_id =$object['client_id'];

         /*mit*/
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





         $object = PaymentMaster::model()->findByPk($payment_master_id);
         $object->date = $data['date'];
         $object->amount_paid = $data['amount_paid'];
         $object->collection_vault_id =$collection_vault_id;
         $object->reference_number = $data['reference_number'];
         $object->payment_mode = $data['payment_mode'];
         $object->bill_month_date = $data['get_year']."-".$data['get_month']."-01";
         $object->edit_by_user_id = Yii::app()->user->getState('user_id');
         $object->save();
         $responce=[];
         $responce['success']=true;
         echo json_encode($responce);
    }
	public function actionsecurityReturn()
	{
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query_discount = "select * from discount_type as dt
                 where dt.company_id ='$company_id'";
        $discount_type_result = Yii::app()->db->createCommand($query_discount)->queryAll();

         $final_discount = array();
         $number =0;
         foreach ($discount_type_result as $value){
             $number++;
             $oneObject = array();
             $oneObject['discount_type_id'] = $value['discount_type_id'];
             $oneObject['discount_type_name'] = $value['discount_type_name'];
             $oneObject['discount_amount'] = '';
             $oneObject['discount_percentage'] = '';
             $oneObject['percentage'] = false;
             $oneObject['calculated_discount'] = '';
             $final_discount[] =$oneObject;
         }
	      $todayMonth = Date('m');
	      $todayYear = Date('Y');
        $this->render('securityReturn',array(
           // 'clientList'=>clientData::getActiveClientList_forLedger(),
             'clientList'=>json_encode(array()),
             'todayMonth'=>$todayMonth ,
             'todayYear'=>$todayYear ,
             'discount_type'=>json_encode($final_discount),
             'crud_role'=>crudRole::getCrudrole(16) ,
        ));
	}




	public function actiononeCustomerAmountListallCustomerList_rider_wise(){
        echo  clientData::getActiveClientList_forLedger_rider_wisr();
    }

	public function actiononeCustomerAmountListallCustomerList()
	{

       echo  clientData::getActiveClientList_forLedger();
	}

    public function actiononeCustomerAmountListallCustomerList_active()
    {
        $post = file_get_contents("php://input");


       // echo  clientData::getActiveClientList_forLedger();
        echo  clientData::getActiveClientList_forLedger_active($post);
    }

	public function actionnextPagePagination(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $page = $data['page'];
        $zone_id = $data['serach_zone_id'];
        $status_id = $data['serach_status_id'];
        $sort_object =false;
        if(isset($data['sort_object'])){
            $sort_object = $data['sort_object'];
        }


        echo clientData::getClientList($page ,$zone_id ,$status_id,$sort_object );
    }
	public function actionnextPagePagination_sample(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $page = $data['page'];
        $zone_id = $data['serach_zone_id'];
        $status_id = $data['serach_status_id'];

        echo clientData::getClientList_sample($page ,$zone_id ,$status_id );
    }
	public function actionnextPagePagination_sample_addFoolowUp(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $sales_reps_id = $data['sales_reps_id'];
        $attribute = $data['attribute'];
        $sort_type = $data['sort_type'];
        $page = $data['page'];
        $zone_id = $data['serach_zone_id'];
        $status_id = $data['serach_status_id'];

        echo clientData::addFollowUpClientList($page ,$zone_id ,$status_id ,$sales_reps_id,$attribute,$sort_type);
    }

    public function actionPnextPagePagination(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $page = $data['page'];
        $SelectClientID = $data['SelectClientID'];

        $order =  clientData::getOrderAgainstClint($SelectClientID);
        $product =   clientData::getproductList($page);
        $result = array();
        $result['order'] = $order ;
        $result['product'] = $product ;
        echo json_encode($result) ;


    }

    public function actionsaveNewClient(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo clientData::saveNewClientFunction($data);
    }
    public function actionsaveNewClient_sample(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $client_id =$data['client_id'];
        $sales_reps_id =$data['sales_reps_id'];

        $client_object =Client::model()->findByPk(intval($client_id));

        $client_object->sales_reps_id = $sales_reps_id;

        if($client_object->save()){
            riderData::$response['success']=true;
        }else{
            riderData::$response['success']=false;
        }

        echo   json_encode(riderData::$response);
    }

    public function actionEditClient(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo clientData::editClientFunction($data);

    }
    public function actiononeCustomerAmountList_today_payment(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $start_date = $data['startDate'];
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query = " SELECT 
                pm.payment_master_id ,
                c.fullname ,
                pm.date , 
                pm.amount_paid ,
                pm.bill_month_date,
                pm.reference_number,
                pm.payment_mode,
                pm.time,
                pm.edit_by_user_id,
                pm.user_id,
                pm.rider_id
                from payment_master as pm
                left join client as c ON c.client_id = pm.client_id  
                WHERE date(pm.created_date_time) = '$start_date' 
                 and pm.company_branch_id ='$company_id' 
                order by pm.date DESC";



        $result = Yii::app()->db->createCommand($query)->queryAll();

        $final_result = [];
        foreach ($result as $value){

            $payment_mode=$value['payment_mode'];

            /*  WHEN pm.payment_mode = 1 then 'Online'
                    WHEN pm.payment_mode = 2 then 'Cheque'
                    WHEN pm.payment_mode = 3 then 'Cash'
                    WHEN pm.payment_mode = 5 then 'Bank Transaction'
                    WHEN pm.payment_mode = 6 then 'Card Transaction'
                    ELSE 'Other'*/
            if($payment_mode ==1){
                $value["payment_mode_text"] ='Online';
            }elseif($payment_mode ==2){
                $value["payment_mode_text"] ='Cheque';
            }elseif($payment_mode ==3){
                $value["payment_mode_text"] ='Cash';
            }elseif($payment_mode ==5){
                $value["payment_mode_text"] ='Bank Transaction';
            }elseif($payment_mode ==6){
                $value["payment_mode_text"] ='Card Transaction';
            }else{
                $value["payment_mode_text"] ='Other';
            }

            $bill_month_date_get = $value['bill_month_date'];

            $month = date("m",strtotime($bill_month_date_get));
            $year = date("Y",strtotime($bill_month_date_get));

            $value['get_month'] =$month;
            $value['get_year'] =$year;
            if($value['edit_by_user_id']>0){
                $value['color'] ='#FF7F50';
            }

            $final_result[] = $value;
        }

        echo  json_encode($final_result);

    }
    public function actiononeCustomerAmountList(){
           $post = file_get_contents("php://input");

           echo clientData::oneCustomerAmountListFunction($post);
    }

    public function actiononeCustomerAmountList_security_amount(){
         $post = file_get_contents("php://input");

         $clinetObject = Client::model()->findByPk(intval($post));

         if($clinetObject){
            echo $clinetObject['security'];
         }else{
             echo 0;
         }

    }

    public function actionpaymentMethodreturn_security_save(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

            $responce = [];

            $startDate =$data['startDate'];
            $security_amount =$data['security_amount'];

            $client_id =$data['client_id'];

            $client_object = Client::model()->findByPk(intval($client_id));

            $client_security = $client_object['security'];
            $left_security =$client_security - $security_amount ;

            $client_object->security = $left_security;

            $client_object->security_return_date = $startDate;


            if($client_object->save()){

            }



    }

    public function actiondelete(){


        $post = file_get_contents("php://input");

        $delivery = Delivery::model()->findByAttributes(
            array(
                'client_id'=>$post
            )
        );


        if($delivery){
            riderData::$response['success']=false;
            riderData::$response['message']='You can not deletet this Customer';

          echo     json_encode(riderData::$response);
        }else{
            echo clientData::deleteFunction($post);
        }


    }
    public function actiononeCustomerAmountListdelete_master_payment(){

        $user_id =  Yii::app()->user->getState('user_id');

        $user_object = User::model()->findByPk($user_id);

        if($user_object['receipt_delete']>0){
            $post = file_get_contents("php://input");
            $data = json_decode($post ,true);

            $payment_master_id = $data['payment_master_id'];
            $remarks = $data['remarks'];

            $payment_master_object = PaymentMaster::model()->findByPk($payment_master_id);

             $object =  $payment_master_object->attributes;



            PaymentDetail::model()->deleteAllByAttributes(array('payment_master_id'=>$payment_master_id));
            PaymentMaster::model()->deleteByPk($payment_master_id);





            $action_name = 'delete_payment';
            $modify_table_name = 'payment_master';
            $modify_id = $object['client_id'];
            $client_id = $object['client_id'];
            $selected_date = $object['date'];
            $data_befour_action = json_encode($object);
            $new_value =$object['amount_paid'];

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




            echo true;
        }else{

            echo false;
        }




    }


    public function actioncheckAlredyExistClient(){
        $post = file_get_contents("php://input");
        $data =CJSON::decode($post , true);

        echo checkAllreadyExist::checkAlredyExistClientFunction($data);
    }
    public function actioncheckAlredyExistClient_checkName(){
        $post = file_get_contents("php://input");
        $data =CJSON::decode($post , true);
        $fullName = $data['fullname'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $clientObject = Client::model()->findAllByAttributes(
             array('fullname'=>$fullName ,'company_branch_id'=>$company_id)
        );
        if($clientObject){
             echo true;
        }else{
             echo  false;
        }
    }

    public function actionsearchClient(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $text = $data['text'];
        $zone_id = $data['zone_id'];
        $status_id = $data['status_id'];


        echo clientData::search_getClientList($page = false , $text ,$zone_id ,$status_id );

     //   $post = file_get_contents("php://input");
      //  echo clientData::searchClientFunction($post);
    }

    public function actiongetProductList(){

         $ClientID = file_get_contents("php://input");
         $order =  clientData::getOrderAgainstClint($ClientID);

         $product =   clientData::geClientBasetproductList($ClientID);
         $result = array();
         $result['order'] = $order ;
         $result['product'] = $product ;
          echo json_encode($result) ;

    }

    public function actionselectFrequencyForOrder(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        echo clientData::selectFrequencyForOrderFunction($data);

    }

    public function actionselectFrequencyForOrder_interval(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo clientData::selectFrequencyForOrderFunction_interval($data);
    }

    public function actionsaveChangedayObjectQuantity(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo clientData::saveChangedayObjectQuantityFunction($data);

    }

    public function actionsaveChangedayObjectQuantity_interval(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo clientData::saveChangedayObjectQuantityFunction_interval($data);
    }
    public function actionremoveProductFormSchedual(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
         echo clientData::removeProductFormSchedualFunction($data);
    }

    public function actiongetClientLedgherReport(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);
        echo clientData::getClientLedgherReportFunction($data);
    }
    public function actiongetClientLedgherReport_PrintBill(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);
        $company_id = Yii::app()->user->getState('company_branch_id');

        if($company_id == 1 || $company_id == 16){
            echo clientData::getClientLedgherReportFunction_PrintBill($data);
        }else if($company_id == 15){
            echo milkkhasData::getClientLedgherReportFunction_PrintBill_milkkhas($data);
        }else if($company_id == 6){

            // echo clientData::getClientLedgherReportFunction_PrintBill($data);
            // echo clientData::getClientLedgherReportFunction_dairy_craft($data);
            echo dairy_craft_bill_print_data::getClientLedgherReportFunction_dairy_craft($data);
        }else if($company_id == 19 |$company_id ==2){
            echo raej_data::getClientLedgherReportFunction_raej_company($data);
        }else if($company_id ==25 ||$company_id ==22 ||$company_id ==21 || $company_id ==20 || $company_id ==18 ){

            //echo clientData::getClientLedgherReportFunction_PrintBill_for_safe_tast($data);

            echo safe_tast_print_report_data::getClientLedgherReportFunction_for_safe_tast($data);
        }else{
            echo clientData::getClientLedgherReportFunction_PrintBill_all($data);
        }
    }
    public function actionget_test_date_list(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);

        echo clientData::test_date($data);

    }

    public function actiongetClientLedgherReport_bottle(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);
        echo clientData::getClientLedgherReportFunction_bottle($data);
    }

    public function actiongetClientLedgherReport_bill(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);
        $company_id = Yii::app()->user->getState('company_branch_id');
         if($company_id ==1){
             echo clientData::getClientLedgherReportFunction_bill_taza($data);
         }else{
             echo clientData::getClientLedgherReportFunction_bill($data);
         }

    }
     public function actiongetProductPriceList(){
         $post = file_get_contents("php://input");
          echo clientData::getProductPriceListFunction($post);
     }

     public function actionpaymentMethod(){
          $post = file_get_contents("php://input");
          $data_get = CJSON::decode($post ,True);
          $responce = [];
          $data =  $data_get['mayment'];
          $data_discount =  $data_get['discount'];

         $check_allow = advance_right_data::check_advance_right_funation('receipt_add');

         if(!$check_allow){
             $response = array(
                 'success' => false,
                 'message'=>'You are not allowed to perform this action',
             );
             echo json_encode($response);
             die();
         }

         $response = array(
             'code' =>200,
             'company_branch_id'=>0,
             'success' =>true,
             'message'=>'Your payment has been processed successfully',
             'data' => ''
         );


          $user_id = Yii::app()->user->getState('user_id');


         $client_object = Client::model()->findByPk($user_id);



         $finalBillMothDate =$data["bill_year"]."-".$data["bill_month"]."-".'01';
         $client_id =   $data['client_id'];
         $result = PaymentMaster::model()->findAllByAttributes(array('bill_month_date'=>$finalBillMothDate,'client_id'=>$client_id));
         $company_id = Yii::app()->user->getState('company_branch_id');
         echo conformPayment::conformPaymentMethodFromPortal($company_id , $data,$data_discount);
     }

     public function actioncustomerLedgerExport(){
         $data = $_GET;


         echo clientData::getClientLedgherReportFunction_export($data);
     }
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='client-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionpaymentMethodcheckPayment(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
          $cleint_Id  = $data["client_id"];
        $finalBillMothDate =$data["bill_year"]."-".$data["bill_month"]."-".'01';
        $result= PaymentMaster::model()->findAllByAttributes(array('bill_month_date'=>$finalBillMothDate,'client_id'=>$cleint_Id));
        if($result){
            $reponce = true;
        }else{
            $reponce = false;
        }
       echo  $reponce ;
    }

    public function actiondropCustomerList(){
	    $get = $_GET;
	    $data = array();

        $getReasonList=dropClientReasonData::getReasonList_of_dropCustomer();

        $data['getReasonList']  = $getReasonList;
	    if(isset($get['start_date'])){

	            $start_date =$get['start_date'];
	            $end_date =$get['end_date'];
	            $deactive_reason_id =$get['deactive_reason_id'];

                $data['start_date'] = $start_date;
                $data['end_date'] = $end_date;
                $data['deactive_reason_id'] = $deactive_reason_id;
                $data['pieChartFilter'] = true;

        }else{

            $data['start_date'] = '';
            $data['end_date'] = '';
            $data['deactive_reason_id'] = '';
            $data['pieChartFilter'] = false;

        }


	    $today_date = date("Y-m-d");
	    $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $company_id = Yii::app()->user->getState('company_branch_id');
        $this->render('dropCustomerList',array(
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'company_id'=>$company_id,
            'fiveDayAgo'=>$fiveDayAgo,
            'data'=>json_encode($data),

        ));
    }

    public function actiononeCustomerAmountList_spacial_order(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);

         echo "<pre>";
         print_r($data);
         die();

    }
    public function actiongetClientLedgherReportsave_default_quantity(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);

         $clientID = $data['clientID'];
         $quantity = $data['quantity'];

         $interval_scheduler =IntervalScheduler::model()->findByAttributes([
             'client_id'=>$clientID
         ]);

        $responce = array();

        if($interval_scheduler){

            $interval_scheduler->default_value = $quantity;
            $interval_scheduler->save();
            $responce['success']=true;
            $responce['message']="Updated Successfully";

        }else{
            $responce['success']=false;
            $responce['message']="Thic customer has No scheduled ";
        }
        echo  json_encode($responce);

    }

    public function actiononeCustomerAmountList_closing_balnce_of_one_day(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);

        $action_date = $data['startDate'];

        $days= -(date("d",strtotime($action_date)));

        $action_date = date('Y-m-d', strtotime($days.' day', strtotime($action_date)));

        $client_id = $data['client_id'];
        $closing_balance =  spacial_date_closing_balance::closing_balance($action_date , $client_id);
        echo 'Balance as on date '.$action_date.' : '.$closing_balance;
        die();
    }

    public function actiononeCustomerAmountList_payment_term_of_client(){

        $post = file_get_contents("php://input");

        $object_client = Client::model()->findByPk($post);

        $payment_term = $object_client['payment_term'];

        $objec_pament = PaymentTerm::model()->findByPk($payment_term);

        if($objec_pament){
            echo $objec_pament['payment_term_name'];
        }

    }
    public function actiononeCustomerAmountList_change_date_select(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);

        $startDate =  $data['startDate'];

        $array =  explode("-",$startDate);

        $year = $array[0];
        $month = $array[1];
        $company_id = Yii::app()->user->getState('company_branch_id');
        $result = [];
        $result['year'] = $year;
        $result['month'] = intval($month);
        $result['company_id'] = $company_id;

        echo  json_encode($result);

    }
    public static function actionEditClient_search_cutomer_customer_source(){
        $post = file_get_contents("php://input");
        //$data = CJSON::decode($post ,True);

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT CONCAT(c.client_id ,'-',c.fullname) AS name, c.client_id ,c.fullname
            from client as c
            where  c.company_branch_id ='$company_id' 
            and c.fullname like '%$post%' OR c.client_id like '%$post%' limit 10";


        $query_result =  Yii::app()->db->createCommand($query)->queryAll();

        echo json_encode($query_result);

    }

    public function actionsaveChangedayObjectQuantitycustomer_transfer_to_other_company(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);


        $selected_company_id = $data['selected_company_id'];
        $client_id = $data['selected_client_object']['client_id'];

        $object = Client::model()->findByPk($client_id);
        $object->company_branch_id= $selected_company_id;

        $responce = [];


        if($object->save()){
            $responce['success'] = true;
        }else{
            $responce['success'] = false;
            $responce['message'] = $object->getErrors();
        }

        echo json_encode($responce);
    }

    public function actiononeCustomerAmountList_change_discount_amount_function(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);


        foreach ($data as $value){
              $discount_list_id =$value['discount_list_id'];
              $total_discount_amount =$value['total_discount_amount'];

               if($discount_list_id>0){
                   $object =DiscountList::model()->findByPk($discount_list_id);
                   $object->total_discount_amount =$total_discount_amount;
                   $object->save();
               }else{
                   $object =New DiscountList();
                   $object->discount_type_id =$value['discount_type_id'];
                   $object->percentage =0;
                   $object->percentage_amount =0;
                   $object->payment_master_id =$value['payment_master_id'];
                   $object->total_discount_amount =$total_discount_amount;
                   $object->save();
               }

        }
    }

}
