<?php

class SmslogController extends Controller
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
	public function accessRules()
	{
        $actionsList =appConstants::getActionList();
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','messageLog' ,'selectDateBaseMessage','testing'),
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
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */

	public function actiontesting(){
        die('ok');
    }
	public function actionMessageLog()
	{
         $company_id = Yii::app()->user->getState('company_branch_id');

        date_default_timezone_set("Asia/Karachi");
        $time = date("H:i");
        $todayDate = date("Y-m-d");

        /*$servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "sms_log";*/

       $servername = "localhost";
        $username = "dbusersms112";
        $password = "Provided@112";
        $dbname = "sms_log";

        $conn = new mysqli($servername, $username, $password, $dbname);

        $sql = "SELECT * FROM sms_record where company_id = '$company_id' and date = '$todayDate'";
        $result = $conn->query($sql);
           $sms_total = 0;
          $result_data =array();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                     $oneObejct = array();
                     $oneObejct['client_id'] =$row['client_id'];
                     $oneObejct['company_id'] =$row['company_id'];
                     $oneObejct['client_name'] =$row['client_name'];
                     $oneObejct['phone_number'] =$row['phone_number'];
                     $oneObejct['text_message'] =$row['text_message'];
                     $oneObejct['date'] =$row['date'];
                     $oneObejct['setTime'] =$row['setTime'];

                    $string_length =  strlen($row['text_message']);
                    $smsCount = ceil($string_length/160);
                    $oneObejct['smsCount'] =$smsCount;
                  $result_data[] = $oneObejct ;
            }
        }


		$this->render('MessageLog',array(
            'data'=>json_encode($result_data),
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
		));
	}
	public function actionselectDateBaseMessage(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $selectRiderID = $data['selectRiderID'];
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];


         if($selectRiderID >0){
             $clientQuery = "Select c.client_id from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           where rz.rider_id = $selectRiderID  ";
             $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

             $cientID = array();
             $cientID[] = 0;
             foreach($clientResult as $value){
                 $cientID[] =  $value['client_id'];
             }
             $client_id = implode(',',$cientID);
         }else{
             $client_id = $data['client_id'];
         }





        $company_id = Yii::app()->user->getState('company_branch_id');

        date_default_timezone_set("Asia/Karachi");
        $time = date("H:i");


        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "sms_log";

       $servername = "localhost";
        $username = "dbusersms112";
        $password = "Provided@112";
        $dbname = "sms_log";



        $conn = new mysqli($servername, $username, $password, $dbname);

        $sql = "SELECT * FROM sms_record where company_id = '$company_id' and date BETWEEN  '$startDate' and '$endDate' ";
        if($selectRiderID  > 0 || $client_id>0 ){

            $sql .= " and client_id in ($client_id)";
        }
       //  echo $sql ;
       //  die();
        $result = $conn->query($sql);

        $result_data =array();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {

                $oneObejct = array();
                $oneObejct['client_id'] =$row['client_id'];
                $oneObejct['company_id'] =$row['company_id'];
                $oneObejct['client_name'] =$row['client_name'];
                $oneObejct['phone_number'] =$row['phone_number'];
                $oneObejct['text_message'] =$row['text_message'];
                $oneObejct['date'] =$row['date'];
                $oneObejct['setTime'] =$row['setTime'];

                $string_length =  strlen($row['text_message']);
                $smsCount = ceil($string_length/160);
                $oneObejct['smsCount'] =$smsCount;
                $result_data[] = $oneObejct ;


            }
        }

       echo json_encode($result_data) ;
    }


}
