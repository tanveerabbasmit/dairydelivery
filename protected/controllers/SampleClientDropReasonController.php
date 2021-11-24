<?php

class SampleClientDropReasonController extends Controller
{

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
    public function accessRules()
    {
        $actionsList =appConstants::getActionList();
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('pieChartData','pieChart','index','manageReason','saveNewReason','editZone','dropCustomer'),
                'users'=>array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>$actionsList ,
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

    public function actionmanageReason(){

        $this->render('manageReason' , array(
            "zoneList"=>dropClientReasonData::getReasonList(),
            "companyBranchList"=>json_encode(array()),
        ));
    }

    public function actionpieChartData(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
          $start_date = $data['start_date'];
          $end_date = $data['end_date'];
        $company_id = Yii::app()->user->getState('company_branch_id');

        $query = "select c.deactive_reason_id  , count(*) as total ,  ifnull(r.reason,'No Reason') as reason from client as c
        left join sample_client_drop_reason as r ON r.sample_client_drop_reason_id =c.deactive_reason_id
        where  (c.deactive_date BETWEEN '$start_date' AND '$end_date')  and  c.company_branch_id ='$company_id' and c.is_active =0
        group by c.deactive_reason_id 
        ORDER BY COUNT(*) DESC";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
         $totalCustomer = 0;
        foreach ($queryResult as $value){
            $totalCustomer = $totalCustomer + $value['total'];
        }

        $finalObject = array();
        foreach ($queryResult as $value){

             $deactive_reason_id =$value['deactive_reason_id'];
             $total =$value['total'];
             $reason =substr($value['reason'],0,18);
              $percentage = round(($total/$totalCustomer)*100,2);
             $oneObject = array();
            $oneObject['deactive_reason_id'] =$deactive_reason_id ;
            $oneObject['total'] =$total ;
            $oneObject['reason'] =$reason.":".$total."(".$percentage.")" ;
            $oneObject['reason_button'] =$reason.(".$total.");

            $finalObject[] = $oneObject ;

        }

         /*$finalResult = array();

        $oneObject[] = 'Task';
        $oneObject[] = 'Hours per Day';
        $finalResult[] =$oneObject;

        foreach ($queryResult as $value){
            $oneObject =array();

            $oneObject[] = $value['reason'];
            $oneObject[] = $value['total'];
            $finalResult[] =$oneObject;


        }
        $finalResult =[
            ["Task" ,"Hours per Day"],
            ["Task" ,34]
        ];*/
       echo json_encode($finalObject);
    }

    public function actionpieChart()
    {

        $this->render('pieChart' , array(
            "zoneList"=>json_encode(array()),
            "companyBranchList"=>json_encode(array()),
        ));

    }
    public function actiondropCustomer(){

        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));

        $this->render('pieChart' , array(
            "zoneList"=>dropClientReasonData::DropCustomerList(),
            "companyBranchList"=>json_encode(array()),
            "end_date"=>json_encode($today_date),
            "start_date"=>json_encode($fiveDayAgo),
        ));
    }




    public function actionsaveNewReason(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo dropClientReasonData::saveNewReason($data);
    }








    public function actioneditZone(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo dropClientReasonData::editReasonFunction($data);
    }

    public function actiondelete(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo dropClientReasonData::deleteFunction($data);
    }
    public function actionSendSMS(){



        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $option = $data['optionName'];


        $sms = $data['message'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $companyObject  =  utill::get_companyTitle($company_id);
        $companyMask = $companyObject['sms_mask'];
        $getmessage = $companyObject['company_title'];

        $message = $sms."\n\n".$getmessage;
        if($option == 3){

            $client_id = $data['customerID'];
            $clientObject = Client::model()->findByPk(intval($client_id));

            $phoneNo =  $clientObject['cell_no_1'];
            $fullname = $clientObject['fullname'];
            $network_id = $clientObject['network_id'];

            smsLog::saveSms($client_id ,$company_id ,$phoneNo ,$fullname ,$message);
            utill::sendSMS2($phoneNo , $message , $companyMask ,$company_id , $network_id);
        }

        if($option == 1){
            $company_id = Yii::app()->user->getState('company_branch_id');

            $clientObject = Client::model()->findAll(array("condition"=>"company_branch_id =$company_id"));
            foreach($clientObject as $value){
                $phoneNo = $value['cell_no_1'];
                $fullname = $value['fullname'];
                $client_id = $value['client_id'];
                $network_id = $value['network_id'];
                smsLog::saveSms($client_id ,$company_id ,$phoneNo ,$fullname ,$message);

                utill::sendSMS2($phoneNo , $message , $companyMask ,$company_id ,$network_id);

            }
        }

        if($option == 2){
            $zoneId = array();
            $zoneList = $data['zoneList'];
            foreach ($zoneList as $value){
                if($value['is_selected']){
                    $zoneId[] = $value['zone_id'];
                }
            }

            $idList =  implode("," , $zoneId);
            $query = "select c.client_id , c.cell_no_1 , c.network_id , c.fullname from client as c
            where c.zone_id in ($idList) and c.company_branch_id = $company_id AND c.is_active = 1";
            $clientObject = Yii::app()->db->createCommand($query)->queryAll();
            foreach($clientObject as $value){
                $phoneNo = $value['cell_no_1'];
                $fullname = $value['fullname'];
                $client_id = $value['client_id'];
                $network_id = $value['network_id'];
                smsLog::saveSms($client_id ,$company_id ,$phoneNo ,$fullname ,$message);
                utill::sendSMS2($phoneNo , $message , $companyMask , $company_id ,$network_id);

            }

        }

        if($option == 4){
            $todaydate = date("Y-m-d");
            $company_id = Yii::app()->user->getState('company_branch_id');
            $query = "select c.cell_no_1 ,c.client_id, c.network_id ,c.fullname from client as c
            where  c.company_branch_id = $company_id AND c.is_active = 1" ;
            $clientObject = Yii::app()->db->createCommand($query)->queryAll();

            $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_id ";
            $productList =  Yii::app()->db->createCommand($query)->queryAll();

            foreach($clientObject as $value){
                $client_id =  $value['client_id'];
                $checkDeliver_sms = false ;
                foreach($productList as $productvalue) {
                    $product_id =$productvalue['product_id'];
                    $phoneNo = $value['cell_no_1'];
                    $totalWeekly_quantity  =    todayDeliveryQuantityCountForApi::getTodayDeliveryCountWeeklyRegularAndSpecial($client_id ,$product_id, $todaydate);
                    if($totalWeekly_quantity == 0){
                        $totalInterval_quantity =  utill::getOneCustomerTodayIntervalSceduler( $client_id,$product_id);

                        if($totalInterval_quantity == 0){
                            $totalSpecialToday_quantity  =    todayDeliveryQuantityCountForApi::getTodaySpecialOrder($client_id ,$product_id ,$todaydate);
                            if($totalSpecialToday_quantity >0){
                                $checkDeliver_sms = true;
                            }
                        }else{
                            $checkDeliver_sms = true;

                        }

                    }else{
                        $checkDeliver_sms = true;
                    }





                }
                if($checkDeliver_sms){
                    $phoneNo = $value['cell_no_1'];
                    $fullname = $value['fullname'];
                    $client_id = $value['client_id'];
                    $phoneNo = $value['cell_no_1'];
                    $network_id = $value['network_id'];
                    smsLog::saveSms($client_id ,$company_id ,$phoneNo ,$fullname ,$message);
                    utill::sendSMS2($phoneNo , $message , $companyMask , $company_id , $network_id);
                }


            }

        }
        if($option == 5){

            $riderID =  $data['rider_id'];

            $clientQuery = "Select c.client_id,c.address , c.cell_no_1 , c.network_id,c.fullname from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           where rz.rider_id = $riderID  AND c.is_active = 1 ";

            $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

            foreach($clientResult as $value){
                $phoneNo = $value['cell_no_1'];
                $fullname = $value['fullname'];
                $client_id = $value['client_id'];
                $phoneNo = $value['cell_no_1'];
                $network_id = $value['network_id'];
                smsLog::saveSms($client_id ,$company_id ,$phoneNo ,$fullname ,$message);
                utill::sendSMS2($phoneNo , $message , $companyMask , $company_id , $network_id);
            }

        }
        echo 'okk';
    }
}