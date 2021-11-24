<?php

class ExpenceTypeController extends Controller
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

    public function actionmanageexpence(){

        $this->render('manageExpence' , array(
            "zoneList"=>dropClientReasonData::getExpenceList(),
            "companyBranchList"=>json_encode(array()),
        ));
    }

    public function actionsaveNewExpence(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo dropClientReasonData::saveNewExpence($data);
    }

    public function actioneditZone(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo dropClientReasonData::editExpencetypeFunction($data);
    }

    public function actiondelete(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo dropClientReasonData::deleteFunction_expencetype($data);
    }


    public function actionpaymentType()
    {
        //clientData::getActiveClientList_forLedger()
        $this->render('paymentType',array(
            'clientList'=>json_encode(array()),
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),

        ));
    }
    public function actiononeCustomerAmountListUpdate(){
        $post = file_get_contents("php://input");
        echo clientData::oneCustomerAmountListFunction($post);
    }

    public function actiononeCustomerAmountListUpdateallPaymentList(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        echo clientData::oneCustomerAmountListFunction_all($data);
    }

    public function actiononeCustomerAmountListUpdate_forMonth(){

         $post = file_get_contents("php://input");
         $data = CJSON::decode($post, TRUE);
         $month = $data['month'];
         $year = $data['year'];
         $payment_master_id = $data['payment_master_id'];
         $paymentObject =PaymentMaster::model()->findByPk(intval($payment_master_id));
         $formonthDate =$year.'-'.$month.'-01';
         $paymentObject->bill_month_date = $formonthDate ;
         if($paymentObject->save()){
             echo  $formonthDate ;
              die();
         }else{
             var_dump($paymentObject->getErrors());
         }




    }


}