<?php

class BillPrintController extends Controller
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


    public function actioncheck_date(){

    }
    public function actionallBill(){


        $company_id = Yii::app()->user->getState('company_branch_id');

        if($company_id ==1){

            $get_data = $_GET;
            $render = 'allBill_taza';
           // if(isset($get_data['new'])){
                $render = 'allBill_taza_new_design';
            //}

        }else if($company_id ==15){
           
            $render = 'allBill_noorMilk';
        }else if($company_id ==6){

            $render = 'allBill_dairy_craft';
        }else if($company_id ==16){
           $render = 'allBill_chishti';
        }else if($company_id ==19 ||$company_id ==2){

           $render = 'allBill_raej';
        }else if($company_id ==233 ||$company_id ==22 ||$company_id ==21 || $company_id ==20 || $company_id ==18 ){

            $render = 'safe_and_taste_food_farms';
        }else{
            $render = 'allBill_all';
        }

        $this->render($render,array(
            'clientList'=>json_encode([]),
            'riderList'=>riderData::getRiderList_withCustomer(),

            'payment_term_list'=>riderData::getPaymentTerm_withCustomer(),
        ));
    }
    public function actionset_default_value(){

        $company_id = Yii::app()->user->getState('company_branch_id');
        $this->render("set_default_value",array(
            'payment_term_list'=>json_encode([]),
            'clientList'=>json_encode([]),
            'riderList'=>riderData::getRiderList_withCustomer(),
        ));
    }


}