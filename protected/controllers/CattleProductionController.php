<?php

class CattleProductionController extends Controller
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

    public function actionProduction()
    {
         date_default_timezone_set("Asia/Karachi");
         $data_get = $_GET;
         if(isset($data_get['date'])){
             $todaydate  = $data_get['date'];
         }else{
             $todaydate = date("Y-m-d");
         }



        $this->render('production' ,array(
                "CattleList"=>cattleData::getCattleList_production($todaydate),
                "todaydate"=>json_encode($todaydate),
            )
        );
    }

    public function actionProductionComparative(){
        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $company_id = Yii::app()->user->getState('company_branch_id');
        $this->render('ProductionComparative',array(
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'company_id'=>$company_id,
            'fiveDayAgo'=>$fiveDayAgo,
        ));
    }

    public function actionproductionComprative_report(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);


        $startDate = $data['startDate'];
        $endDate = $data['endDate'];

        $select_all_cattle = $data['select_all_cattle'];


         $cattle_record_id_list = [];
         $cattle_record_id_list[] = 0;
        if(!$select_all_cattle){
            $resultList = $data['resultList'];
            foreach ($resultList as $value){
                if($value['selected']){
                    $cattle_record_id_list[] = $value['cattle_record_id'];
                }

            }
        }

        $cattle_record_id_string =implode(',',$cattle_record_id_list);



        $productionList = $cattlePtoduction = CattleProductionController::getProductionList($startDate ,$endDate);


        $x= strtotime($startDate);
        $y= strtotime($endDate);
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query_cattle ="SELECT c.cattle_record_id ,c.number FROM cattle_record as c
                         WHERE c.company_id ='$company_id' 
                          ";

        if(!$select_all_cattle){
            $query_cattle .=" AND c.cattle_record_id IN ($cattle_record_id_string) ";
        }
        $query_cattle .="  ORDER BY c.number ASC";



        $cattle_list =  Yii::app()->db->createCommand($query_cattle)->queryAll();

        $resultList = array();
        $lableList = array();
        foreach($cattle_list as $value){
            $oneObject = array();
            $x= strtotime($startDate);
            $y= strtotime($endDate);
            $number = $value['number'];
            $cattle_record_id = $value['cattle_record_id'];
            $oneObject['number'] = $number;
            $oneObject['cattle_record_id'] = $cattle_record_id;
            $date_count_production = array();
            $lableList = array();
            $perious_quantity =-1 ;
            $check_quantity_exist = false;

            while($x < ($y+8640)) {
                $selectDate = date("Y-m-d", $x);
                $lableList[] = $selectDate ;
                $x += 86400;
                $oneObject_quantity  = array();
                if(isset($productionList[$cattle_record_id][$selectDate])){
                    $oneObject_quantity['quantity'] =$productionList[$cattle_record_id][$selectDate];
                    if($oneObject_quantity['quantity']>0){
                        $check_quantity_exist = true;
                    }

                }else{
                    $oneObject_quantity['quantity'] =0;
                }
               if($perious_quantity >=0){
                  $diffrence_quantity = $perious_quantity - $oneObject_quantity['quantity'] ;
                  if($diffrence_quantity >4){
                      $oneObject_quantity['color'] ='DarkSalmon';
                  }
                  if($diffrence_quantity< -4){
                      $oneObject_quantity['color'] ='LightGreen';
                  }
               }
                $perious_quantity =  $oneObject_quantity['quantity'] ;
                $date_count_production[] =$oneObject_quantity;
            }
            $oneObject['date_count_production']=$date_count_production;
            if($check_quantity_exist){
               $resultList[] =$oneObject;
            }
        }

        $finalData = array();
        $finalData['lable'] = $lableList;
        $finalData['resultList'] = $resultList;
        $total =array();
        foreach($resultList as $value){
          foreach ($value['date_count_production'] as $key=>$valueCount){
              $total[$key] =0;
          }
        }
        foreach($resultList as $value){
            foreach ($value['date_count_production'] as $key=>$valueCount){
              $total[$key] =  $total[$key] + $valueCount['quantity'];
            }
        }

        $finalData['total'] = $total;

        echo json_encode($finalData);
    }
    public  static function getProductionList($startDate ,$endDate){
        $query = "SELECT  c.cattle_record_id , (c.morning +c.afternoun + c.evenining) AS total_sum ,c.date FROM
            cattle_production AS c
            WHERE c.DATE between  '$startDate' AND '$endDate'";

        $result =  Yii::app()->db->createCommand($query)->queryAll();

        $finalResult =  array();

        foreach ($result as $value){
            $cattle_record_id = $value['cattle_record_id'];
            $total_sum = $value['total_sum'];
            $date = $value['date'];
            $finalResult[$cattle_record_id][$date] =$total_sum ;
        }
        return $finalResult ;
    }
    public function actionproduction_searchProduction()
    {
        $todaydate = file_get_contents("php://input");
        echo   cattleData::getCattleList_production($todaydate);
    }

    public function actionproduction_saveproduction(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
         $production =$data['production'];
         $date =$data['date'];

         foreach ($production as $value){

             $cattle_record_id = $value['cattle_record_id'];

             $date =$data['date'];

             $morning = $value['morning'];
             $afternoun = $value['afternoun'];
             $evenining = $value['evenining'];

             $productionObject = CattleProduction::model()->findByAttributes(array('cattle_record_id'=>$cattle_record_id,'date'=>$date));
            // $total_production = $morning + $afternoun +$evenining;
            // if($total_production>0){
                 if($productionObject){
                     $productionObject->morning =$morning ;
                     $productionObject->afternoun =$afternoun ;
                     $productionObject->evenining =$evenining ;
                     $productionObject->save();
                 }else{
                     $productionObject =new CattleProduction();
                     $productionObject->cattle_record_id =$cattle_record_id ;
                     $productionObject->morning =$morning ;
                     $productionObject->afternoun =$afternoun ;
                     $productionObject->evenining =$evenining ;
                     $productionObject->date =$date ;
                     if($productionObject->save()){
                     }else{

                         var_dump($productionObject->getErrors());

                     }

                 }
            // }


         }

    }
    public function actionYear_wise_production(){

        date_default_timezone_set("Asia/Karachi");
        $data_get = $_GET;
        if(isset($data_get['date'])){
            $todaydate  = $data_get['date'];
        }else{
            $todaydate = date("Y-m-d");
        }
        $todaydate =date("Y");
        $this->render('year_wise_production' ,array(
                "CattleList"=>cattleData::getCattleList_production($todaydate),
                "todaydate"=>json_encode($todaydate),
            )
        );

    }

    public function actionproduction_year_wise_production(){
        $year = file_get_contents("php://input");
        //$data = CJSON::decode($post, TRUE);

        $months = array(
            '01'=>'January',
            '02'=>'February',
            '03'=>'March',
            '04'=>'April',
            '05'=>'May',
            '06'=>'June',
            '07'=>'July ',
            '08'=>'August',
            '09'=>'September',
            '10'=>'October',
            '11'=>'November',
            '12'=>'December',
        );

        $final_Result = [];
        foreach ($months as $key=>$value){
               $month_number =  $key;
              $start_date = $year.'-'.$month_number.'-01';
              $end_date = $year.'-'.$month_number.'-31';


            $company_id = Yii::app()->user->getState('company_branch_id');

            $query="SELECT 
                sum(p.morning) as  morning,
                sum(p.afternoun) as afternoun,
                sum(p.evenining) as evenining
                FROM cattle_production AS p
                LEFT JOIN cattle_record AS r ON r.cattle_record_id =p.cattle_record_id
                WHERE r.company_id ='$company_id'
                AND p.date BETWEEN '$start_date' AND '$end_date' ";

            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
            $total_production = 0;
            foreach ($queryResult as $value){

                $total_production =$value['morning'] +$value['afternoun'] +$value['evenining'];

            }
            $one_object = [];
            $one_object['name']= $months[$key];
            $one_object['total_production']= $total_production;

            $final_Result[] = $one_object;
        }
        echo json_encode($final_Result);
    }

}
