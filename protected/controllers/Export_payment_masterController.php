<?php

class Export_payment_masterController extends Controller
{
	public function actionExport_payment_master_view()
	{
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

        $this->render('export_payment_master_view',array(
            'data'=>json_encode($data),
        ));

	}

	public function actionexport_payment_master_view_download(){
	    $get_data = $_GET;

	    $start_date = $get_data['start_date'];
	    $end_date = $get_data['end_date'];

        $company_id = Yii::app()->user->getState('company_branch_id');

	    $query = "SELECT 
            c.client_id ,
            c.fullname ,
          c.cell_no_1,
            c.address,
            pm.amount_paid,
            pm.payment_mode
            FROM payment_master AS pm
            LEFT JOIN client AS c ON c.client_id = pm.client_id
            WHERE pm.company_branch_id = '$company_id'
            AND pm.date between '$start_date' AND '$end_date' " ;

        $payment_list =  Yii::app()->db->createCommand($query)->queryAll();

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=payment_list.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo "client_id ,fullname,phone Number,Address,amount_paid,payment_mode";
        echo  "\r\n" ;
        foreach ($payment_list as $value){

            echo $value['client_id'].',';

            echo str_replace(",","_",$value['fullname']).',';
            echo strval($value['cell_no_1']).',';
            echo str_replace(",","_",$value['address']).',';
            echo $value['amount_paid'].',';
            echo payment_mode::get_payment_mode_name($value['payment_mode']);
            echo  "\r\n" ;
        }


    }



}