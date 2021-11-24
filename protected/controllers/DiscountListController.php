<?php

class DiscountListController extends Controller
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
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view' ,'CutomerDiscountReport','getCustomerLedger' ,'discountReport'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
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

	public function actiondiscountReport(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $company_id = Yii::app()->user->getState('company_branch_id');


        $year = $data['year'];
        $monthNum = $data['monthNum'];

        $RiderID = $data['RiderID'];
        $company_id = Yii::app()->user->getState('company_branch_id');
        if($RiderID >0){
        $clientQuery = "Select c.client_id,c.address , c.cell_no_1 ,c.fullname ,z.name as zone_name from rider_zone as rz
                       Right join client as c ON c.zone_id = rz.zone_id 
                       left join zone as z ON z.zone_id = c.zone_id
                       where rz.rider_id = $RiderID   
                        order by c.fullname ASC  ";
        }else{
        $clientQuery = "select c.client_id,c.address , c.cell_no_1 ,c.fullname from client as c
                       where c.company_branch_id = '$company_id'    
                       order by c.fullname ASC ";
        }
        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();


        $client_list = array();
        $client_list[] = 0;
        foreach ($clientResult as $value){
        $client_list[] =$value['client_id'];
        }

        $lientID_ids = implode(',',$client_list);
        $query_report = "select pm.payment_master_id , pm.amount_paid ,c.client_id ,c.fullname ,c.cell_no_1 from payment_master as pm
        left join client as c ON c.client_id = pm.client_id
        where  pm.client_id in ($lientID_ids) and month(pm.bill_month_date) = '$monthNum' and year(pm.bill_month_date) = '$year' ";

        $report_Result =  Yii::app()->db->createCommand($query_report)->queryAll();

        $payment_master_id = array();
        $payment_master_id[] ='0';
        foreach ($report_Result as $value){
        $payment_master_id[] = $value['payment_master_id'];
        }
        $payment_ids = implode(',',$payment_master_id);

        $query_discount_list = "select dl.total_discount_amount ,dl.percentage_amount,dl.discount_type_id, dl.payment_master_id from discount_list  as dl
        where dl.payment_master_id in ($payment_ids) ";

        $discount_list_Result =  Yii::app()->db->createCommand($query_discount_list)->queryAll();

        $discoun_list = array();
        foreach ($discount_list_Result as $value){
           $discount_type_id =$value['discount_type_id'];
           $payment_master_id =$value['payment_master_id'];
           $discoun_list[$payment_master_id][$discount_type_id]=$value['total_discount_amount']; ;
        }

        $final_data= array();

        $query_discont_type = "select * from discount_type as dt
          where dt.company_id ='$company_id'";
        $discount_type =  Yii::app()->db->createCommand($query_discont_type)->queryAll();
         $oneRider_discount_sum = array();
        foreach ($discount_type as $value) {
            $discount_type_id = $value['discount_type_id'];
            $oneRider_discount_sum[$discount_type_id]=0;
        }

        foreach ($report_Result as $value){
            $payment_master_id = $value['payment_master_id'];
             $oneObject =array();
            $oneObject['fullname'] = $value['fullname'];
            $oneObject['cell_no_1'] = $value['cell_no_1'];
            $oneObject['client_id'] = $value['client_id'];
            $oneObject['amount_paid'] = $value['amount_paid'];
            $discount = array();
             $given_discount = false ;
             $given_discount_sum = 0 ;
            foreach ($discount_type as $value){
                $discount_type_id = $value['discount_type_id'];
                if(isset($discoun_list[$payment_master_id][$discount_type_id])){
                     if( $discoun_list[$payment_master_id][$discount_type_id] > 0){
                         $given_discount = true ;
                     }
                    $given_discount_sum =$given_discount_sum + $discoun_list[$payment_master_id][$discount_type_id] ;
                    $discount[] = $discoun_list[$payment_master_id][$discount_type_id];
                     $discount_amount = $discoun_list[$payment_master_id][$discount_type_id];
                    $oneRider_discount_sum[$discount_type_id] =  $oneRider_discount_sum[$discount_type_id] + $discount_amount;
                }else{
                    $discount_amount = '0';
                    $discount[] =0;
                }
             }
            $oneObject['discount'] =$discount;
            $oneObject['total_discount'] =$given_discount_sum;
            if($given_discount){

                $final_data[] = $oneObject;

            }

        }

        $one_rider_discount_wise_sum = array();
        foreach ($discount_type as $value) {
            $discount_type_id = $value['discount_type_id'];
            $one_rider_discount_wise_sum[] = $oneRider_discount_sum[$discount_type_id] ;
        }

        $data = array();
        $data['report'] = $final_data;
        $data['one_rider_discount_wise_sum'] = $one_rider_discount_wise_sum;


        echo json_encode($data);



    }
	public function actiondiscountReport_copy(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $company_id = Yii::app()->user->getState('company_branch_id');


        $year = $data['year'];
        $monthNum = $data['monthNum'];

        $RiderID = $data['RiderID'];
        $company_id = Yii::app()->user->getState('company_branch_id');
        if($RiderID >0){
            $clientQuery = "Select c.client_id,c.address , c.cell_no_1 ,c.fullname ,z.name as zone_name from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           where rz.rider_id = $RiderID   
                            order by c.fullname ASC  ";
        }else{
            $clientQuery = "select c.client_id,c.address , c.cell_no_1 ,c.fullname from client as c
                           where c.company_branch_id = '$company_id'    
                           order by c.fullname ASC ";
        }
        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();


        $client_list = array();
        $client_list[] = 0;
        foreach ($clientResult as $value){
            $client_list[] =$value['client_id'];
        }

        $lientID_ids = implode(',',$client_list);
       $query_report = "select pm.payment_master_id , pm.amount_paid ,c.client_id ,c.fullname ,c.cell_no_1 from payment_master as pm
           left join client as c ON c.client_id = pm.client_id
           where  pm.client_id in ($lientID_ids) and month(pm.date) = '$monthNum' and year(pm.date) = '$year' ";

        $report_Result =  Yii::app()->db->createCommand($query_report)->queryAll();

        $payment_master_id = array();
        foreach ($report_Result as $value){
            $payment_master_id[] = $value['payment_master_id'];
        }
        $payment_ids = implode(',',$payment_master_id);

        $query_discount_list = "select dl.total_discount_amount ,dl.percentage_amount,dl.discount_type_id, dl.payment_master_id from discount_list  as dl
         where dl.payment_master_id in ($payment_ids) ";

          $discount_list_Result =  Yii::app()->db->createCommand($query_discount_list)->queryAll();

            $discoun_list = array();
           foreach ($discount_list_Result as $value){
               $discount_type_id =$value['discount_type_id'];
               $payment_master_id =$value['payment_master_id'];
               $discoun_list[$payment_master_id][$discount_type_id]=$value['total_discount_amount']; ;
           }


        $arranged_result= array();
        foreach ($report_Result as $value){
            $oneObject =array();
            $oneObject['fullname'] = $value['fullname'];
            $oneObject['cell_no_1'] = $value['cell_no_1'];
            $oneObject['client_id'] = $value['client_id'];
            $oneObject['discount_type_name'] = $value['discount_type_name'];
            $discount_type_name = $value['discount_type_name'];
            $oneObject['amount_paid'] = $value['amount_paid'];
            $amount_paid = $value['amount_paid'];
            $percentage = $value['percentage'];
            $total_discount_amount = $value['total_discount_amount'];
            if($percentage >0){
                $count_discount = ($total_discount_amount/100)*$amount_paid ;
                $oneObject['total_discount_amount'] =floor($count_discount);
            }else{
                $oneObject['total_discount_amount'] = $value['total_discount_amount'];
            }


            $arranged_result[$discount_type_name]['reportData'][] =$value;

            if(!isset($arranged_result[$discount_type_name]['amount'])){
                $arranged_result[$discount_type_name]['amount'] = 0;
            }
            if(!isset($arranged_result[$discount_type_name]['recived_amount'])){
                $arranged_result[$discount_type_name]['recived_amount'] =0;
            }
           $arranged_result[$discount_type_name]['amount'] =$arranged_result[$discount_type_name]['amount'] + $oneObject['total_discount_amount'];
           $arranged_result[$discount_type_name]['recived_amount'] =$arranged_result[$discount_type_name]['recived_amount'] + $oneObject['amount_paid'];
        }

         $last_object = array();
         $total_amount = 0;
         $amount_paid = 0;
        foreach ($arranged_result as $key=>$value){
             $oneObject =array();

             $oneObject['discount_type_name'] =$key;
             $oneObject['result'] =$arranged_result[$key]['reportData'];
             $oneObject['total'] =$arranged_result[$key]['amount'];
             $oneObject['amount_paid'] =$arranged_result[$key]['recived_amount'];
            $last_object[]=$oneObject ;
            $total_amount = $total_amount + $arranged_result[$key]['amount'] ;
            $amount_paid = $amount_paid + $arranged_result[$key]['recived_amount'] ;
        }

         $final_data =array();
         $final_data['report'] =$last_object ;
         $final_data['totalamount'] =$total_amount ;
         $final_data['amount_paid'] =$amount_paid ;
        echo json_encode($final_data);



    }

    public function actionCutomerDiscountReport(){
        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $monthNum  = date('m');
        $year = intval(date('Y'));

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query_discont_type = "select * from discount_type as dt
          where dt.company_id ='$company_id'";
        $discount_type =  Yii::app()->db->createCommand($query_discont_type)->queryAll();


        $query_rider_type = "select r.rider_id , r.fullname from rider as r
          where r.company_branch_id ='$company_id'";
        $riderList_type =  Yii::app()->db->createCommand($query_rider_type)->queryAll();

        $this->render('CutomerDiscountReport',array(
            'riderList'=>json_encode($riderList_type),
            'discount_type'=>json_encode($discount_type),
            'company_id'=>$company_id,
            'fiveDayAgo'=>$fiveDayAgo,
            'monthNum'=>$monthNum,
            'year'=>$year,

        ));

    }
}
