<?php

class RiderController extends Controller
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
    public  function actiononeYearDeduction(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
         $year = $data['year'];
         $rider_id = $data['RiderID'];

         $deductionObject = RiderDeduction::model()->findAllByAttributes(array('year'=>$year,'rider_id'=>$rider_id));
            $month_wise_object = array();
          foreach ($deductionObject as $value){
              $month = $value['month'];
              $month_wise_object[$month] = $value['deduction_amount'];
          }

         $finalResult = array();
         $finalResult[] = array('update'=>false,'month_id'=>1,'month_name'=>'January','deduction_amount'=>isset($month_wise_object[1])?$month_wise_object[1]:'0');
         $finalResult[] = array('update'=>false,'month_id'=>2,'month_name'=>'February','deduction_amount'=>isset($month_wise_object[2])?$month_wise_object[1]:'0');
         $finalResult[] = array('update'=>false,'month_id'=>3,'month_name'=>'March','deduction_amount'=>isset($month_wise_object[3])?$month_wise_object[1]:'0');
         $finalResult[] = array('update'=>false,'month_id'=>4,'month_name'=>'April','deduction_amount'=>isset($month_wise_object[4])?$month_wise_object[1]:'0');
         $finalResult[] = array('update'=>false,'month_id'=>5,'month_name'=>'May','deduction_amount'=>isset($month_wise_object[5])?$month_wise_object[1]:'0');
         $finalResult[] = array('update'=>false,'month_id'=>6,'month_name'=>'June','deduction_amount'=>isset($month_wise_object[6])?$month_wise_object[1]:'0');
         $finalResult[] = array('update'=>false,'month_id'=>7,'month_name'=>'July','deduction_amount'=>isset($month_wise_object[7])?$month_wise_object[1]:'0');
         $finalResult[] = array('update'=>false,'month_id'=>8,'month_name'=>'August','deduction_amount'=>isset($month_wise_object[8])?$month_wise_object[1]:'0');
         $finalResult[] = array('update'=>false,'month_id'=>9,'month_name'=>'September','deduction_amount'=>isset($month_wise_object[9])?$month_wise_object[1]:'0');
         $finalResult[] = array('update'=>false,'month_id'=>10,'month_name'=>'October','deduction_amount'=>isset($month_wise_object[10])?$month_wise_object[1]:'0');
         $finalResult[] = array('update'=>false,'month_id'=>11,'month_name'=>'October','deduction_amount'=>isset($month_wise_object[11])?$month_wise_object[1]:'0');
         $finalResult[] = array('update'=>false,'month_id'=>12,'month_name'=>'December','deduction_amount'=>isset($month_wise_object[12])?$month_wise_object[1]:'0');

           echo json_encode($finalResult);

    }
	public function actiondeduction(){

        $year = intval(date('Y'));
        $company_id = Yii::app()->user->getState('company_branch_id');
        $this->render('riderDeduction',array(
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'company_id'=>$company_id,
             'year'=>$year,
        ));
        die();

        $riderId =  $_GET['riderId'];

        $company_id = Yii::app()->user->getState('company_branch_id');

         $riderObject = Rider::model()->findByAttributes(array('rider_id'=>$riderId ,'company_branch_id'=>$company_id));

	    if(isset($riderObject)){
            $monthNum  = date('m');
            $year = intval(date('Y'));
            for($i=1;$i<13;$i++){
                echo $i;
            }
            var_dump($riderObject->fullname);
        }else{
            $this->redirect('manageRider');
        }
    }
	public function actionmanageRider()
	{
		$this->render('manageRider',array(
             'riderList'=>riderData::getRiderList(),
              'ZoneList'=>zoneData::getZoneList(),
              "posShopList"=>colorTagData::getShopList(),
		));
	}
    public function actiondeliveryduration()
    {
      //  $deliveryDeration =riderData::getdeliveryDerationData();
        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $company_id = Yii::app()->user->getState('company_branch_id');
        $this->render('deliveryduration',array(
            'riderList'=>riderData::getRiderList(),
            'company_id'=>$company_id,
            'fiveDayAgo'=>$fiveDayAgo,
        ));
    }
    public function actiondeliveryCharts()
    {

        //  $deliveryDeration =riderData::getdeliveryDerationData();
        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $company_id = Yii::app()->user->getState('company_branch_id');
        $this->render('deliveryCharts',array(
            'riderList'=>riderData::getRiderList(),
            'company_id'=>$company_id,
            'fiveDayAgo'=>$fiveDayAgo,
        ));

    }

	public function actiondailyRecoveryReport(){

        $user_list  =  userData::getUSerList_array();
        $rider_list  = riderData::getRiderList_array();

        $rider_user_list = [];
        foreach ($user_list as $value){
           $full_name =  $value['full_name'];
           $rider_user_list[] = $full_name;
        }
        foreach ($rider_list as $value){

           $rider_user_list[] = $value['fullname'];
        }



        $company_id = Yii::app()->user->getState('company_branch_id');

        $this->render('dailyRecoveryReport',array(
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'rider_user_list'=>json_encode($rider_user_list),
            'company_id'=>$company_id,
        ));

    }
	public function actionrecivePaymetFromRider()
	{
        date_default_timezone_set("Asia/Karachi");
         $today_date =Date("Y-m-d");
        $this->render('recivePaymetFromRider',array(
            'data'=>recivePaymetFromRider_data::get_payment_from_rider($today_date),

        ));

	}


    public function actionsaveNewRider(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo riderData::saveNewRiderFunction($data);
    }

    public function actionDateRangeRiderDeliveryDuration_getData(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

       echo   deliveryDurationData::dateRangeDeliveryTime($data);
    }

    public function actionlastDeliveryTime(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
       echo   deliveryDurationData::dateRangeLastDeliveryTime_chart ($data);


    }
    public function actiongetDialyRecovery_export(){
        $data = $_GET;

        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: attachment; filename=dailyRecoveryReport.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $startDate  =  $data['startDate'];
        $endDate  =  $data['endDate'];
        $RiderID = $data['selectRiderID'];
        $payment_mode = $data['payment_mode'];

        if($RiderID>0){
            $rider_object = Rider::model()->findByPk($RiderID);
            $rider_name = $rider_object['fullname'];
        }else{
            $rider_name ='All Rider';
        }

        $paymnet_mode =[
            '0'=>'All Mode',
            '2'=>'Cheque',
            '3'=>'Cash',
            '5'=>'Bank Transaction ',
            '6'=>'Card Transaction ',
        ];


       // $payment_mode_name =$paymnet_mode[];


        //echo 'start date,$startDate,end date,$endDate,rider,$rider_name';
       // echo "\r\n";

        echo '#,ID,CUSTOMER NAME,phone No.,ADDRESS,Enter BY,Date,Mode of Payment,Refernce NO.,Gross Amount,Net Amount';
        echo "\r\n";



        $payment_type = $data['payment_type'];
        $enter_by = $data['enter_by'];

        $rider_user_object =   riderData::get_user_rider_name();
        $user_object = $rider_user_object['user_object'];
        $rider_object = $rider_user_object['rider_object'];
        $company_id = Yii::app()->user->getState('company_branch_id');
        if($RiderID >0){

            $clientQuery = "Select c.cell_no_1 ,c.client_id,c.address , c.cell_no_1 ,c.fullname ,z.name as zone_name from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           where rz.rider_id = $RiderID   
                            order by c.fullname ASC ";
        }else{

            $clientQuery = "select c.cell_no_1 , c.client_id,c.address , c.cell_no_1 ,c.fullname from client as c
                           where c.company_branch_id = '$company_id'  
                           order by c.fullname ASC ";


        }
        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

        $client_array = [];
        $client_array[]= '-1';
        foreach ($clientResult as $value){
            $client_id =  $value['client_id'];
            $client_array[] = $client_id;
        }

        $client_ids = implode(',',$client_array);

        $get_data = daily_recovery_report_data::get_payment_of_client_list_and_discount($client_ids,$data);



        $payment_list = $get_data['final_payment'];

        $discount_list = $get_data['discount_list'];

        $discount_list_string = $get_data['discount_list_string'];



        $finalData = array();
        $count = 0;
        $totol_discount = 0;


        foreach($clientResult as $value) {


            $oneObject = array();
            $client_id = $value['client_id'];
            $oneObject['client_id'] = $value['client_id'];
            $oneObject['address'] = $value['address'];
            $oneObject['fullname'] = $value['fullname'];
            $oneObject['cell_no_1'] = $value['cell_no_1'];

            $query_makePayment = "SELECT pm.date , pm.payment_master_id, pm.user_id,
                   pm.rider_id,pm.reference_number,
                    pm.payment_mode ,IFNULL((pm.amount_paid) ,0)  as amount_paid FROM payment_master as pm
                   where pm.client_id='$client_id' 
                   and pm.date between '$startDate' and '$endDate'
                   and pm.payment_type=0";
            if($payment_mode>0){
                $query_makePayment .=" and pm.payment_mode='$payment_mode' ";
            }

            $totalMake_object = [];
            if(isset($payment_list[$client_id])){

                $totalMake_object = $payment_list[$client_id];
            }

            foreach ($totalMake_object as $value){




                $totalMake_Payment = $value['amount_paid'];
                $payment_mode_new =$value['payment_mode'];

                $date =$value['date'];


                $payment_master_id = $value['payment_master_id'];
                $reference_number = $value['reference_number'];


                $oneObject['payment_master_id'] = $payment_master_id;

                $oneObject['discount_amount'] = 0;

                $oneObject['discount_amount_total_of_one_payment'] ='0';

                $oneObject['discount_list_string'] ='';

                if(isset($discount_list[$payment_master_id])){
                    $oneObject['discount_amount_total_of_one_payment'] = $discount_list[$payment_master_id];
                }
                if(isset($discount_list_string[$payment_master_id])){

                    $oneObject['discount_list_string'] = $discount_list_string[$payment_master_id];

                }


                $oneObject['amountpaid'] = $totalMake_Payment;
                $oneObject['reference_number'] = $reference_number;


                if($totalMake_Payment >0){
                    if($payment_mode_new==2){
                        $oneObject['payment_mode'] ="Check";
                    }elseif($payment_mode_new==3){
                        $oneObject['payment_mode'] ="Cash";
                    }elseif($payment_mode_new==5){
                        $oneObject['payment_mode'] ="Bank Transaction";
                    }elseif($payment_mode_new==6){
                        $oneObject['payment_mode'] ="Card Transaction";
                    }else{
                        $oneObject['payment_mode'] = "Other";
                    }


                    $user_id =$value['user_id'];
                    $rider_id =$value['rider_id'];
                    $oneObject['payment_user_name'] ='';
                    $oneObject['payment_rider_name'] ='';
                    if($user_id>0){
                        if(isset($user_object[$user_id])){
                            $oneObject['payment_user_name'] = $user_object[$user_id];
                        }
                    }
                    if($rider_id>0){
                        if(isset($rider_object[$rider_id])){
                            $oneObject['payment_rider_name'] = $rider_object[$rider_id];
                        }
                    }


                    if($oneObject['payment_rider_name']==$enter_by OR $oneObject['payment_user_name']==$enter_by ){
                        $oneObject['date'] =$date;
                        // $oneObject['discount_amount'] =riderData::get_discount_amount($payment_master_id);
                        $oneObject['discount_amount'] =$oneObject['discount_amount_total_of_one_payment'];


                        $totol_discount = $totol_discount +  $oneObject['discount_amount'];
                        $count = $count +  $totalMake_Payment;


                        $oneObject['net_amount'] = $totalMake_Payment - $oneObject['discount_amount'];

                        if($oneObject['discount_amount']==0){
                            $oneObject['discount_amount']='-';
                        }



                        $finalData[] =$oneObject;
                    }


                }

            }



        }
        $result= array();
        $result['data'] = $finalData;
        $result['count'] = $count;
        $result['totol_discount'] = $totol_discount;
        $result['totol_net'] = $count -$totol_discount;

       foreach ($finalData as $key=>$value){
           echo ($key+1).',';
           echo $value['client_id'].',';
           echo  str_replace(",","",$value['fullname']).',';
           echo str_replace(",","",$value['cell_no_1']).',';
           echo str_replace(",","",$value['address']).',';
           echo $value['payment_user_name'].',';
           echo $value['date'].',';
           echo $value['payment_mode'].',';
           echo $value['reference_number'].',';
           echo $value['amountpaid'].',';
           echo $value['net_amount'];
           echo "\r\n";
       }

     die();

    }
    public function actiongetDialyRecovery(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);


        $startDate  =  $data['startDate'];
        $endDate  =  $data['endDate'];
        $RiderID = $data['RiderID'];
        $payment_mode = $data['payment_mode'];
        $payment_type = $data['payment_type'];
        $enter_by = $data['enter_by'];

        $rider_user_object =   riderData::get_user_rider_name();

        $user_object = $rider_user_object['user_object'];
        $rider_object = $rider_user_object['rider_object'];


        $company_id = Yii::app()->user->getState('company_branch_id');
        if($RiderID >0){

            $clientQuery = "Select c.cell_no_1 ,c.client_id,c.address , c.cell_no_1 ,c.fullname ,z.name as zone_name from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           where rz.rider_id = $RiderID   
                            order by c.fullname ASC ";
        }else{

            $clientQuery = "select c.cell_no_1 , c.client_id,c.address , c.cell_no_1 ,c.fullname from client as c
                           where c.company_branch_id = '$company_id'  
                           order by c.fullname ASC ";


        }
        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

        $client_array = [];
        $client_array[]= '-1';
        foreach ($clientResult as $value){
           $client_id =  $value['client_id'];
            $client_array[] = $client_id;
        }

        $client_ids = implode(',',$client_array);

        $get_data = daily_recovery_report_data::get_payment_of_client_list_and_discount($client_ids,$data);



        $payment_list = $get_data['final_payment'];

        $discount_list = $get_data['discount_list'];

        $discount_list_string = $get_data['discount_list_string'];



        $finalData = array();
        $count = 0;
        $totol_discount = 0;


        foreach($clientResult as $value) {


            $oneObject = array();
            $client_id = $value['client_id'];
            $oneObject['client_id'] = $value['client_id'];
            $oneObject['address'] = $value['address'];
            $oneObject['fullname'] = $value['fullname'];
            $oneObject['cell_no_1'] = $value['cell_no_1'];

            $query_makePayment = "SELECT pm.date , pm.payment_master_id, pm.user_id,
                   pm.rider_id,pm.reference_number,
                    pm.payment_mode ,IFNULL((pm.amount_paid) ,0)  as amount_paid FROM payment_master as pm
                   where pm.client_id='$client_id' 
                   and pm.date between '$startDate' and '$endDate'
                   and pm.payment_type=0";
            if($payment_mode>0){
                $query_makePayment .=" and pm.payment_mode='$payment_mode' ";
            }

            $totalMake_object = [];
            if(isset($payment_list[$client_id])){

                $totalMake_object = $payment_list[$client_id];
            }

            foreach ($totalMake_object as $value){




                $totalMake_Payment = $value['amount_paid'];
                $payment_mode_new =$value['payment_mode'];

                $date =$value['date'];


                $payment_master_id = $value['payment_master_id'];
                 $reference_number = $value['reference_number'];


                $oneObject['payment_master_id'] = $payment_master_id;

                $oneObject['discount_amount'] = 0;

                $oneObject['discount_amount_total_of_one_payment'] ='0';

                $oneObject['discount_list_string'] ='';

                if(isset($discount_list[$payment_master_id])){
                    $oneObject['discount_amount_total_of_one_payment'] = $discount_list[$payment_master_id];
                }
                if(isset($discount_list_string[$payment_master_id])){

                    $oneObject['discount_list_string'] = $discount_list_string[$payment_master_id];

                }


                $oneObject['amountpaid'] = $totalMake_Payment;
                $oneObject['reference_number'] = $reference_number;


                if($totalMake_Payment >0){
                    if($payment_mode_new==2){
                        $oneObject['payment_mode'] ="Check";
                    }elseif($payment_mode_new==3){
                        $oneObject['payment_mode'] ="Cash";
                    }elseif($payment_mode_new==5){
                        $oneObject['payment_mode'] ="Bank Transaction";
                    }elseif($payment_mode_new==6){
                        $oneObject['payment_mode'] ="Card Transaction";
                    }else{
                        $oneObject['payment_mode'] = "Other";
                    }


                    $user_id =$value['user_id'];
                    $rider_id =$value['rider_id'];
                    $oneObject['payment_user_name'] ='';
                    $oneObject['payment_rider_name'] ='';
                    if($user_id>0){
                        if(isset($user_object[$user_id])){
                            $oneObject['payment_user_name'] = $user_object[$user_id];
                        }
                    }
                    if($rider_id>0){
                        if(isset($rider_object[$rider_id])){
                            $oneObject['payment_rider_name'] = $rider_object[$rider_id];
                        }
                    }


                  if($oneObject['payment_rider_name']==$enter_by OR $oneObject['payment_user_name']==$enter_by ){
                      $oneObject['date'] =$date;
                      // $oneObject['discount_amount'] =riderData::get_discount_amount($payment_master_id);
                      $oneObject['discount_amount'] =$oneObject['discount_amount_total_of_one_payment'];


                      $totol_discount = $totol_discount +  $oneObject['discount_amount'];
                      $count = $count +  $totalMake_Payment;


                      $oneObject['net_amount'] = $totalMake_Payment - $oneObject['discount_amount'];

                      if($oneObject['discount_amount']==0){
                          $oneObject['discount_amount']='-';
                      }



                      $finalData[] =$oneObject;
                  }


                }

            }



        }
        $result= array();
        $result['data'] = $finalData;
        $result['count'] = $count;
        $result['totol_discount'] = $totol_discount;
        $result['totol_net'] = $count -$totol_discount;
        echo json_encode($result);
         die();
    }

    public function actionpickAmountByAdmin(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        riderData::savePickAmount($data);
        $todayDate = $data['todayDate'];
      echo   recivePaymetFromRider_data::get_payment_from_rider($todayDate);

    }
    public function actionsearchNewAll(){

        $post = file_get_contents("php://input");

      echo   recivePaymetFromRider_data::get_payment_from_rider($post);

    }

    public function actiongetZoneAgainstRider(){
        $post = file_get_contents("php://input");
        echo riderData::getZoneAgainstRiderFunction($post);
    }

    public function actioneditRider(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo riderData::editRiderFunction($data);
    }

    public function actiondelete(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo riderData::deleteFunction($data);
    }

    public function actioncheckDuplicateRiderUseName(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,true);
        $company_id = Yii::app()->user->getState('company_branch_id');
        $userName = $data['userName'];
        $rider_id = $data['rider_id'];

        $query = " select count(*) as totalNumber from rider as r
        where r.rider_id != '$rider_id' and r.userName ='$userName' and r.company_branch_id ='$company_id' ";

        $result = Yii::app()->db->createCommand($query)->queryAll();
        $resultNumber = $result[0]['totalNumber'];
        $sendData = true ;
        if($resultNumber == 0){
           $sendData = false ;
        }

         echo  $sendData ;

    }

}
