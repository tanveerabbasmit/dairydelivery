<?php

class MilkStockReportController extends Controller
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


    public function actionmilkStockReport(){

        $company_branch_id =  Yii::app()->user->getState('company_branch_id');

        if($company_branch_id!=1){
             echo  'You are not allow this page';
             throw new CHttpException(404,'The requested page does not exist.');
        }
        

        $finalResult = array();
        $finalResult['date'] =date("Y-m-d");
        $this->render('milkStockReport',array(
            'data'=>json_encode($finalResult),
        ));
    }

    public function actionbase_production(){
        $post = file_get_contents("php://input");

        $revious_date =  date('Y-m-d', strtotime('-1 day', strtotime($post)));


        $query_pre = "SELECT  sum(cp.morning) AS morning ,sum(cp.afternoun) AS afternoun , sum(cp.evenining) AS evenining FROM cattle_record AS cr
                 LEFT JOIN cattle_production AS cp ON cp.cattle_record_id =cr.cattle_record_id
                 WHERE cp.DATE ='$revious_date'";

        $productList_pre =  Yii::app()->db->createCommand($query_pre)->queryAll();


        /*echo '<pre>';
        print_r($productList_pre);
        die();

        echo json_encode($productList[0]);*/

        $query_today = "SELECT  sum(cp.morning) AS morning ,sum(cp.afternoun) AS afternoun , sum(cp.evenining) AS evenining FROM cattle_record AS cr
                 LEFT JOIN cattle_production AS cp ON cp.cattle_record_id =cr.cattle_record_id
                 WHERE cp.DATE ='$post'";

        $productList_today =  Yii::app()->db->createCommand($query_today)->queryAll();

        $finalResult = array();

        $finalResult['morning'] = $productList_today[0]['morning'];
        $finalResult['afternoun'] = $productList_pre[0]['afternoun'];
        $finalResult['evenining'] = $productList_pre[0]['evenining'];
        $finalResult['start_date_p'] = $revious_date;

        echo json_encode($finalResult);


    }
    public function actionbase_production_fram(){

        // $framList = qualityListData::getFarmList();
        $post1 = file_get_contents("php://input");

        $post =  date('Y-m-d', strtotime('-1 day', strtotime($post1)));

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query = " SELECT f.farm_name , sum(ifnull(ds.quantity,0) - ifnull(ds.return_quantity,0) - ifnull(ds.wastage,0))  AS quantity  from farm AS f
                  LEFT JOIN  daily_stock AS ds ON f.farm_id = ds.farm_id AND ds.DATE ='$post' 
                  WHERE f.company_id ='$company_id' and f.farm_id != 2
                  GROUP BY f.farm_id " ;
        $productList =  Yii::app()->db->createCommand($query)->queryAll();
        echo json_encode($productList);

    }

    public function actionbase_credit_stock(){

        $post1 = file_get_contents("php://input");

        $post =  date('Y-m-d', strtotime('-1 day', strtotime($post1)));

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query = "SELECT  sum(dd.quantity) 	 FROM delivery AS  d
        LEFT JOIN delivery_detail AS dd ON d.delivery_id = dd.delivery_id
        WHERE d.company_branch_id =$company_id AND d.DATE = '$post'";



        $credit_sale =  Yii::app()->db->createCommand($query)->queryScalar();

        $client_query = " SELECT c.fullname ,d.DATE , sum(dd.quantity) AS quantity	FROM delivery AS d 
        LEFT JOIN delivery_detail AS dd ON d.delivery_id = dd.delivery_id
        LEFT JOIN client AS c ON c.client_id = d.client_id
        WHERE d.client_id IN  (3861 ,3864,9544) AND d.DATE = '$post'
        GROUP BY d.client_id ";

        $client_query ="SELECT c.client_id ,c.fullname , IFNULL(sum(dd.quantity),0) AS quantity  ,dd.date  FROM client AS c 
        LEFT JOIN delivery AS d ON d.client_id = c.client_id 
        LEFT JOIN delivery_detail AS dd ON dd.delivery_id =d.delivery_id AND dd.DATE ='$post'
        WHERE c.client_id IN (9546,3861 ,3864,9544) 
        GROUP BY  c.client_id";

        $client_sale =  Yii::app()->db->createCommand($client_query)->queryAll();


        $query_wasteg = "SELECT sum(ds.wastage) AS wastage 
                   FROM daily_stock AS ds 
                   WHERE ds.company_branch_id='$company_id' AND ds.DATE ='2019-05-03'";

        $wasteg =  Yii::app()->db->createCommand($query_wasteg)->queryScalar();


        $object =MilkStockReport::model()->findByAttributes([
            'date'=>$post1
        ]);
        if($object){

            $todayData= true ;
        }else{
            $todayData= false ;
        }

        $final_result = array();

        $final_result['credit_sale'] = $credit_sale ;
        $final_result['client_sale'] = $client_sale ;
        $final_result['wasteg'] = $wasteg ;
        $final_result['todayData'] = $todayData ;

        echo json_encode($final_result);

    }

    public function actionbase_saveReport(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $date =  $data['start_date'] ;

        $object_report = MilkStockReport::model()->findByAttributes([
            'date'=>$date
        ]);

        if($object_report){
          $milk_stock_report_id = $object_report['milk_stock_report_id'];

          $object = MilkStockReport::model()->findByPk(intval($milk_stock_report_id));

        }else{
            $object = new MilkStockReport();
        }



        $production = $data['productionList'];


        $object->carry_forworded = $data['carry_forworded'];
        $object->carry_forworded = $data['carry_forworded'];
        $object->available_for_sale = $data['available_for_sale'];
        $object->credit_sale = $data['credit_sale'];
        $object->closing_stock = $data['closing_stock'];
        if(isset($data['reason'])){
            $object->reason = $data['reason'];
        }else{
            $object->reason = '';
        }

        if(isset($data['actual_stock'])){
            $object->actual_stock = $data['actual_stock'];
        }else{
            $object->actual_stock='';
        }


        $object->date = $data['start_date'];
        $object->morning_milking = $production['morning'];
        $object->afternoon_milking = $production['afternoun'];
        $object->evening_milking = $production['evenining'];
        $object->farm_stock = $data['total_farm_stock'];
        if($object->save()){
            echo 'save';
        }else{
            echo json_encode($object->getErrors());
        }
    }

    public function actionMiilkStockReport_view(){

        date_default_timezone_set("Asia/Karachi");
        $today_date =Date("Y-m-d");
        $this->render('MiilkStockReport_view',array(
            'data'=>json_encode(array()),

        ));
    }


    public function actionbase_getMilkReport(){
        $post = file_get_contents("php://input");
        $query = "SELECT * FROM milk_stock_report AS mr
         WHERE mr.DATE = '$post'";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
         echo json_encode($queryResult);
    }

    public function actionbasetoday_remarks(){
         $post = file_get_contents("php://input");
         $object =MilkStockReport::model()->findByAttributes([
             'date'=>$post
         ]);
         if($object){
             echo json_encode($object->attributes);
         }
    }


    public function actionbase_carryForworded(){
         $post_data = file_get_contents("php://input");

         $post =  date('Y-m-d', strtotime('-1 day', strtotime($post_data)));
        $company_id = Yii::app()->user->getState('company_branch_id');

       //  $post = file_get_contents("php://input");

        $object =MilkStockReport::model()->findByAttributes([
            'date'=>$post
        ]);
        if($object){

            echo $object['actual_stock'];
        }else{
            echo 0;
        }


/*
        $query_production = "SELECT  sum(cp.morning) AS morning ,sum(cp.afternoun) AS afternoun , sum(cp.evenining) AS evenining FROM cattle_record AS cr
                 LEFT JOIN cattle_production AS cp ON cp.cattle_record_id =cr.cattle_record_id
                 WHERE cp.date>'2019-05-25' and  cp.DATE<'$post' and cr.company_id =$company_id";

        $List_production =  Yii::app()->db->createCommand($query_production)->queryRow();
        if($List_production){
              $totalPRoduction = $List_production['morning'] + $List_production['afternoun'] +$List_production['evenining'] ;
        }
        $query_stock = " SELECT sum(ifnull(d.quantity,0) - ifnull(d.return_quantity,0) - ifnull(d.wastage,0)) AS stock FROM  daily_stock AS d
        WHERE d.company_branch_id ='$company_id' AND d.date < '$post' and d.date>'2019-05-25'  ";
       $Result_stock =  Yii::app()->db->createCommand($query_stock)->queryscalar();
        $query_sale = "SELECT sum(dd.quantity) FROM client AS c
            LEFT JOIN delivery AS d ON c.client_id = d.client_id 
            LEFT JOIN delivery_detail AS dd ON dd.delivery_id =d.delivery_id 
            WHERE c.company_branch_id =$company_id AND d.DATE < '$post'
             and d.date>'2019-05-25' " ;
         $sale_result =  Yii::app()->db->createCommand($query_sale)->queryscalar();

        echo   $totalPRoduction + $Result_stock - $sale_result;*/

    }
}
