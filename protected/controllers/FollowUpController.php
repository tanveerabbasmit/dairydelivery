<?php

class FollowUpController extends Controller
{
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

     public static $responce = ['success'=>false,'message'=>''];
	public function actiononeCustomerAmountList()
	{
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $query="SELECT * FROM follow_up as fu
          where fu.client_id = '$data' ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
         echo json_encode($queryResult);
	}

	public function actionSaveTage(){
         $post = file_get_contents("php://input");
         $data = CJSON::decode($post, TRUE);
         $client_id =$data['client_id'];
         $clientObject = Client::model()->findByPk(intval($client_id));
         $clientObject->tag_color_id = $data['tag_color_id'];
        if($clientObject->save()){
             $reponce = array(
                 'success'=>true
             );
            echo json_encode($reponce);
        }else{
            $reponce = array(
                'success'=>false
            );
            echo json_encode($reponce);
        }

    }

	public function actionnewClientReport(){
        $todayMonth = Date('m');
        $todayYear = Date('Y');
        $this->render('newClientReport',array(
            'clientList'=>json_encode(array()),
            'todayMonth'=>$todayMonth ,
            'todayYear'=>$todayYear ,
            "dropReasonList"=>dropClientReasonData::getReasonList(),
            "getCategoryList"=>categoryData::getCategoryList_group_by(),
        ));

    }
    public function actiononeCustomerAmountList_customerListapi(){
	    echo clientData::getActiveClientList_forLedger_sample();
    }

    public function actiongetNewCustomerList(){
	    
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);
        $new_Customer = clientData::getClientLedgherReportFunction_newClient($data);
        $drop_Customer = clientData::getClientLedgherReportFunction_dropCustomer($data);
        $sample_Customer = clientData::getClientLedgherReportFunction_sampleCustomer($data);
        $final_data = array();
        $final_data['new_Customer'] =$new_Customer;
        $final_data['drop_Customer'] =$drop_Customer;
        $final_data['sample_Customer'] =$sample_Customer;
        echo json_encode($final_data);
    }
    public function actionsaveTag(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);
        $client_id = $data['client_id'];
        $tag_color_id = $data['tag_color_id'];
        $clientObject =Client::model()->findByPk(intval($client_id));
        $clientObject->tag_color_id = $tag_color_id ;

        if($clientObject->save()){
            FollowUpController::$responce['success']=true;
        }else{
            FollowUpController::$responce['success'] =false;
        }
        echo json_encode(FollowUpController::$responce);
    }
    public function actionaddfollowUp()
    {
        $todayMonth = Date('m');
        $todayYear = Date('Y');

        $this->render('followUp',array(
            /*'clientList'=>clientData::getActiveClientList_forLedger_sample(),*/
            'clientList'=>json_encode(array()),

            'todayMonth'=>$todayMonth ,
            'todayYear'=>$todayYear ,
            "dropReasonList"=>dropClientReasonData::getReasonList(),
        ));
    }

    public function actionsaveFollowUp(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

         $followObject = New FollowUp();

        $followObject->client_id = $data['client_id'];
        $followObject->date = $data['startDate'];
        $followObject->remarks = $data['amount_paid'];
        if($followObject->save()){

        }else{
            var_dump($followObject->getErrors());
        }
    }
    public function actionsaveFollowUp_sample(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

         $followObject = New FollowUp();

        $followObject->client_id = $data['client_id'];
        $followObject->date = $data['date'];
        $followObject->remarks = $data['remarks'];
        if($followObject->save()){

        }else{
            var_dump($followObject->getErrors());
        }
    }

    public function actionsaveFollowUp_meakeRegualr(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $makeRegualr = New MakeRegualrDropClient();

        $makeRegualr->client_id = $data['client_id'];
        $makeRegualr->drop_or_regular = $data['flag'];
        $makeRegualr->company_id =  Yii::app()->user->getState('company_branch_id'); ;
        $makeRegualr->date = date("Y-m-d") ;
        $makeRegualr->	sample_client_drop_reason_id = 0;
        if($makeRegualr->save()){
              $clientObject = Client::model()->findByPk(intval($data['client_id']));
              $clientObject->client_type = 1;
              $clientObject->save();
              $clientList = clientData::getActiveClientList_forLedger_sample();
              echo $clientList;
        }else{
            var_dump($makeRegualr->getErrors());
        }
    }
    public function actionsaveFollowUp_meakeDrop(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $makeRegualr = New MakeRegualrDropClient();

        $makeRegualr->client_id = $data['client_id'];
        $makeRegualr->drop_or_regular = $data['flag'];
        $makeRegualr->company_id =  Yii::app()->user->getState('company_branch_id'); ;
        $makeRegualr->date = date("Y-m-d") ;
        $makeRegualr->	sample_client_drop_reason_id = $data['sample_client_drop_reason_id'];
        if($makeRegualr->save()){
             $clientObject = Client::model()->findByPk(intval($data['client_id']));
            $clientObject->	is_active = 0;
            $clientObject->save();

            $clientList = clientData::getActiveClientList_forLedger_sample();
            echo $clientList;

        }else{
            var_dump($makeRegualr->getErrors());
        }
    }



    /*new*/

    public function actionsaveFollowUp_meakeRegualr_new(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $makeRegualr = New MakeRegualrDropClient();

        $makeRegualr->client_id = $data['client_id'];
        $makeRegualr->drop_or_regular = $data['flag'];
        $makeRegualr->company_id =  Yii::app()->user->getState('company_branch_id'); ;
        $makeRegualr->date = date("Y-m-d") ;
        $makeRegualr->	sample_client_drop_reason_id = 0;
        if($makeRegualr->save()){
            $clientObject = Client::model()->findByPk(intval($data['client_id']));
            $clientObject->client_type = 1;
            $clientObject->save();
           // $clientList = clientData::getActiveClientList_forLedger_sample();
           // echo $clientList;
        }else{
            var_dump($makeRegualr->getErrors());
        }
    }
    public function actionsaveFollowUp_meakeDrop_new(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $makeRegualr = New MakeRegualrDropClient();

        $makeRegualr->client_id = $data['client_id'];
        $makeRegualr->drop_or_regular = $data['flag'];
        $makeRegualr->company_id =  Yii::app()->user->getState('company_branch_id'); ;
        $makeRegualr->date = date("Y-m-d") ;
        $makeRegualr->sample_client_drop_reason_id = $data['sample_client_drop_reason_id'];
        if($makeRegualr->save()){
            $clientObject = Client::model()->findByPk(intval($data['client_id']));
            $clientObject->	is_active = 0;
            $clientObject->save();

           // $clientList = clientData::getActiveClientList_forLedger_sample();
          //  echo $clientList;

        }else{
            var_dump($makeRegualr->getErrors());
        }
    }
}