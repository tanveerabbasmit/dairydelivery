<?php

class smsLogController extends Controller
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

      /* $servername = "localhost";
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

          $result_data =array();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $result_data[] = $row ;
            }
        }



		$this->render('MessageLog',array(
            'data'=>json_encode($result_data)
		));
	}
	public function actionselectDateBaseMessage(){
        $todayDate = file_get_contents("php://input");


        $company_id = Yii::app()->user->getState('company_branch_id');

        date_default_timezone_set("Asia/Karachi");
        $time = date("H:i");


       /* $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "sms_log";*/

        $servername = "localhost";
        $username = "smslog";
        $password = "smslog12345";
        $dbname = "sms_log";


        $conn = new mysqli($servername, $username, $password, $dbname);

        $sql = "SELECT * FROM sms_record where company_id = '$company_id' and date = '$todayDate'";
        $result = $conn->query($sql);

        $result_data =array();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $result_data[] = $row ;
            }
        }

       echo json_encode($result_data) ;
    }


}
