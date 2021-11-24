<?php

class FarmController extends Controller
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

	public function actionIndex()
	{
		$this->render('index');
	}

    public function actionFarmManage()
    {
        Yii::app()->session["view"] = 0;
        $this->render('farmManage' , array(
            "zoneList"=>qualityListData::getFarmList_all(),
            "companyBranchList"=>json_encode(array()),
        ));
    }

    public function actioneditquality(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo qualityListData::editFarmFunction($data);
    }

    public function actiondelete(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo qualityListData::deleteFunction_farm($data);
    }

    public function actionfarms_payasble_summary(){
        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $company_id = Yii::app()->user->getState('company_branch_id');

        $copany_object = Company::model()->findByPk($company_id)->attributes;



        $data = [] ;
        $data['today_date'] = $today_date ;
        $data['five_day_ago'] = $fiveDayAgo ;
        $data['vendor_list'] = [];
        $data['company_id'] = $company_id ;
        $data['copany_object'] = $copany_object ;

        $this->render('farms_payasble_summary',array(
            'data'=>json_encode($data),
        ));
    }
    public function actionbase_get_farms_payasble_summary(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $start_date = $data['startDate'];
        $end_date = $data['endDate'];


        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="select * 
                          from farm as q
                         where q.company_id ='$company_id' ";
        $farm_list =  Yii::app()->db->createCommand($query)->queryAll();

        $final_data = [];
        $total_opening = 0;
        $total_bill_amount = 0;
        $total_payment = 0;
        $total_balance = 0;
        foreach($farm_list as $value){

            $farm_id = $value['farm_id'];
            $farm_name = $value['farm_name'];

            $purchse_opening =  farms_payasble_summary_data::get_farm_totlal_purchse_opening($start_date,$end_date,$farm_id);
            $payment_opening = farms_payasble_summary_data::get_farm_total_payment_opening($start_date,$end_date,$farm_id);
            $purchse_date_range = farms_payasble_summary_data::get_farm_totlal_purchse_date_range($start_date,$end_date,$farm_id);
            $payment_date_range = farms_payasble_summary_data::get_farm_total_payment_date_range($start_date,$end_date,$farm_id);

            $opening_stock =$purchse_opening-$payment_opening;
            $opening_date_range =$purchse_date_range-$payment_date_range;

            $balance = $opening_stock + $opening_date_range;
            $one_object = [];
            $one_object['farm_id'] =$farm_id;
            $one_object['farm_name'] =$farm_name;
            $one_object['opening_stock'] =$opening_stock;
            $one_object['purchse_date_range'] =$purchse_date_range;
            $one_object['payment_date_range'] =$payment_date_range;
            $one_object['balance'] =$balance;
            if($opening_stock !=0 ||$purchse_date_range!=0 || $purchse_date_range !=0 || $balance !=0){
                $final_data[] = $one_object;
            }



            $total_opening = $total_opening + $opening_stock;
            $total_bill_amount = $total_bill_amount + $purchse_date_range;
            $total_payment = $total_payment +$payment_date_range;
            $total_balance = $total_balance + $balance;
        }


        $result =[];
        $result['data'] =$final_data;
        $result['total_opening'] =$total_opening;
        $result['total_bill_amount'] =$total_bill_amount;
        $result['total_payment'] =$total_payment;
        $result['total_balance'] =$total_balance;
        echo  json_encode($result);


    }

    public function actionfarms_payasble_summary_export(){

        $data = $_GET;
        $start_date = $data['startDate'];
        $end_date = $data['endDate'];


        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="select * 
                          from farm as q
                         where q.company_id ='$company_id' ";
        $farm_list =  Yii::app()->db->createCommand($query)->queryAll();

        $final_data = [];
        $total_opening = 0;
        $total_bill_amount = 0;
        $total_payment = 0;
        $total_balance = 0;
        foreach($farm_list as $value){

            $farm_id = $value['farm_id'];
            $farm_name = $value['farm_name'];

            $purchse_opening =  farms_payasble_summary_data::get_farm_totlal_purchse_opening($start_date,$end_date,$farm_id);
            $payment_opening = farms_payasble_summary_data::get_farm_total_payment_opening($start_date,$end_date,$farm_id);
            $purchse_date_range = farms_payasble_summary_data::get_farm_totlal_purchse_date_range($start_date,$end_date,$farm_id);
            $payment_date_range = farms_payasble_summary_data::get_farm_total_payment_date_range($start_date,$end_date,$farm_id);

            $opening_stock =$purchse_opening-$payment_opening;
            $opening_date_range =$purchse_date_range-$payment_date_range;

            $balance = $opening_stock + $opening_date_range;
            $one_object = [];
            $one_object['farm_id'] =$farm_id;
            $one_object['farm_name'] =$farm_name;
            $one_object['opening_stock'] =$opening_stock;
            $one_object['purchse_date_range'] =$purchse_date_range;
            $one_object['payment_date_range'] =$payment_date_range;
            $one_object['balance'] =$balance;
            if($opening_stock !=0 ||$purchse_date_range!=0 || $purchse_date_range !=0 || $balance !=0){
                $final_data[] = $one_object;
            }



            $total_opening = $total_opening + $opening_stock;
            $total_bill_amount = $total_bill_amount + $purchse_date_range;
            $total_payment = $total_payment +$payment_date_range;
            $total_balance = $total_balance + $balance;
        }




        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: attachment; filename=farms_payable_summary.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo  "Fram Name,Opening,Bill amount,Payment,Balance";
        echo "\r\n";

        foreach ($final_data as $key=>$value){

            echo $value['farm_name'].",";
            echo $value['opening_stock'].",";
            echo $value['purchse_date_range'].",";
            echo $value['payment_date_range'].",";
            echo $value['balance'].",";

            echo "\r\n";

       }
        echo 'Total,';
        echo   $total_opening.',';
        echo $total_bill_amount.',';
        echo  $total_payment.',';
        echo $total_balance.',';
    }
}