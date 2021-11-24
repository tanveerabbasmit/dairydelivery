<?php

class CompanyNotificationController extends Controller
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

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new CompanyNotification;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CompanyNotification']))
		{
			$model->attributes=$_POST['CompanyNotification'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->company_notification_id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	 public  function actionMessage_company_wise3698214(){
         $this->render('message' , array(

             

         ));
     }

     public function actionbase_get_message(){

	     $messageObject = CompanyNotification::model()->findByPk(1)->attributes;



         $query="SELECT c.company_id ,c.company_name ,nc.company_notification_id AS company_selected FROM company AS c
                  LEFT JOIN notification_company AS nc ON c.company_id = nc.company_id
                  ORDER BY c.company_name ASC ";
         $result =  Yii::app()->db->createCommand($query)->queryAll();
         $finalResult = array();
         $finalResult['messageObject'] =$messageObject ;
         $finalResult['result'] =$result ;
         echo json_encode($finalResult);


     }

     public function actionbase_save_message(){
         $post = file_get_contents("php://input");
         $data = CJSON::decode($post, TRUE);

          $messageObjectData = $data['messageObject'];
          $company = $data['company'];

          $messageObject =CompanyNotification::model()->findByPk(1);
          $messageObject->company_id =1;
          $messageObject->heading =$messageObjectData['heading'];
          $messageObject->message =$messageObjectData['message'];
          $messageObject->end_date =$messageObjectData['end_date'];
          $messageObject->message_type =$messageObjectData['message_type'];

          if($messageObject->save()){

          }else{
              echo "<pre>";
              print_r($messageObject->getErrors());
              die();
          }

           NotificationCompany::model()->deleteAll();

           foreach ($company as $value){
                if($value['check_company']){

                    $obejct = New NotificationCompany();
                    $obejct->company_id = $value['company_id'];
                    $obejct->	company_notification_id = 1;

                    $obejct->save();
                }

           }
     }




}
