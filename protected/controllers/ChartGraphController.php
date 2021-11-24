<?php

class ChartGraphController extends Controller
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

	public function actiondailySaleQuantityGraph(){
        $start_date  =  date('Y-m-d', strtotime(' -30 day'));
        $end_date  =  date('Y-m-d');

        $select_date = array();
        $select_date['start_date'] = $start_date ;
        $select_date['end_date'] = $end_date ;

        date_default_timezone_set("Asia/Karachi");
        $today_date =Date("Y-m-d");
        $this->render('dailySaleQuantityGraph',array(
            'data'=>recivePaymetFromRider_data::get_payment_from_rider($today_date),
             'select_date'=>json_encode($select_date)
        ));
    }
	public function actiondailySaleAmountGraph(){
        $start_date  =  date('Y-m-d', strtotime(' -30 day'));
        $end_date  =  date('Y-m-d');

        $select_date = array();
        $select_date['start_date'] = $start_date ;
        $select_date['end_date'] = $end_date ;

        date_default_timezone_set("Asia/Karachi");
        $today_date =Date("Y-m-d");
        $this->render('dailySaleAmountGraph',array(
            'data'=>recivePaymetFromRider_data::get_payment_from_rider($today_date),
             'select_date'=>json_encode($select_date)
        ));
    }
	public function actionnewCustomerGraph(){
        $start_date  =  date('Y-m-d', strtotime(' -30 day'));
        $end_date  =  date('Y-m-d');

        $select_date = array();
        $select_date['start_date'] = $start_date ;
        $select_date['end_date'] = $end_date ;

        date_default_timezone_set("Asia/Karachi");
        $today_date =Date("Y-m-d");
        $this->render('newCustomerGraph',array(
            'data'=>recivePaymetFromRider_data::get_payment_from_rider($today_date),
             'select_date'=>json_encode($select_date)
        ));
    }

    public function actionbase_get_quantity_graph(){
       $post = file_get_contents("php://input");
       $data = CJSON::decode($post ,True);
       $start_date = $data['start_date'];
       $end_date = $data['end_date'];
       echo  dashbord_graph_data::get_daily_stock_main_page($start_date ,$end_date);
    }
    public function actionbase_get_newCustomer_graph(){
       $post = file_get_contents("php://input");
       $data = CJSON::decode($post ,True);
       $start_date = $data['start_date'];
       $end_date = $data['end_date'];
       echo  dashbord_graph_data::get_new_Cutomer_data_main_page($start_date ,$end_date);
    }
}
