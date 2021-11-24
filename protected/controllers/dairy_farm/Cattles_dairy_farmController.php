<?php

class Cattles_dairy_farmController extends Controller
{


    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';

    /**
     * @return array action filters  raeg
     */






    public function sendResponse($data)
    {
        echo  json_encode($data);
    }

    public function actionget_month_avg_production(){
        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $company_id = $data['company_id'];
        $cattles_id = $data['cattles_id'];
       $current_date = $data['current_date'];

        $new_cattle_code = $data['cattle_code'];

        $start_date =  date("Y-m",strtotime($current_date))."-01";
        $end_date   =   date("Y-m",strtotime($current_date))."-31";



        $query = " SELECT 
                 ( cp.morning +  cp.afternoun + cp.evenining) as total_production
            FROM cattle_record AS cr 
            LEFT join cattle_production AS cp ON cr.cattle_record_id =cp.cattle_record_id
            WHERE cp.date BETWEEN '$start_date' AND '$end_date'
            AND cr.number like '$new_cattle_code' AND cr.company_id='$company_id'
               AND   ( cp.morning +  cp.afternoun + cp.evenining) >0 ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

        $total_record = sizeof($queryResult);
        $total_sum  =0;
        foreach ($queryResult as $value){
            $total_sum = $total_sum + $value['total_production'];
        }


         if($total_sum>0){
             echo   round(($total_sum/$total_record),2);
         }else{
             echo 0;
         }
    }

    public function actionCattles_production_tentativemilk(){
        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $new_cattle_code = $data['new_cattle_code'];
        $company_id = $data['company_id'];


         $start_date = date("Y-m")."-01";
         $end_date = date("Y-m-d");

        $query = " SELECT 
                 AVG( cp.morning +  cp.afternoun + cp.evenining) as total
            FROM cattle_record AS cr 
            LEFT join cattle_production AS cp ON cr.cattle_record_id =cp.cattle_record_id
            WHERE cr.number like '$new_cattle_code' 
            AND cr.company_id='$company_id'
             and cp.date between '$start_date' and '$end_date' ";



        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();

         if($queryResult){
             $this->sendResponse($queryResult);
         }else{
             $this->sendResponse(0);
         }



    }
    public function actionCattles_production_between_date_range(){
        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $new_cattle_code = $data['cattle_id'];
        $company_id = $data['company_id'];
         $start_date = $data['start_date'];
         $end_date = $data['end_date'];
        $query = " SELECT 
                 sum( cp.morning +  cp.afternoun + cp.evenining) as total
            FROM cattle_record AS cr 
            LEFT join cattle_production AS cp ON cr.cattle_record_id =cp.cattle_record_id
            WHERE cr.number like '$new_cattle_code' 
            AND cr.company_id='$company_id'
             and cp.date between '$start_date' and '$end_date' ";






        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();

         if($queryResult){
             $this->sendResponse($queryResult);
         }else{
             $this->sendResponse(0);
         }



    }

    public function actionproduction_for_culling_report(){
        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $new_cattle_code = $data['new_cattle_code'];
        $company_id = $data['company_id'];

        $start_date = $days_ago = date('Y-m-d', strtotime('-5 days', strtotime(date("Y-m-d"))));

        $end_date = date("Y-m-d");

        $query = " SELECT 
                 AVG( cp.morning +  cp.afternoun + cp.evenining) as total
            FROM cattle_record AS cr 
            LEFT join cattle_production AS cp ON cr.cattle_record_id =cp.cattle_record_id
            WHERE cr.number like '$new_cattle_code' 
            AND cr.company_id='$company_id'
             and cp.date between '$start_date' and '$end_date' ";



        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();

         if($queryResult){
             $this->sendResponse($queryResult);
         }else{
             $this->sendResponse(0);
         }



    }
    public function actionCattles_production_tentativemilk_all_company(){

        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $company_id = $data['company_id'];
         $start_date =$data['start_date'] ;
         $end_date = $data['end_date'];


        $now = strtotime($start_date);
        $your_date = strtotime($end_date);
        $datediff =$your_date - $now ;

         $days = round($datediff / (60 * 60 * 24));

       

        $query = " SELECT 
                 sum( cp.morning +  cp.afternoun + cp.evenining) as total
            FROM cattle_record AS cr 
            LEFT join cattle_production AS cp ON cr.cattle_record_id =cp.cattle_record_id
          
            AND cr.company_id='$company_id'
             and cp.date between '$start_date' and '$end_date' ";

        if($start_date=='2020-02-01'){
            $days = 29;
        }

        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();

         if($queryResult){
             $this->sendResponse($queryResult/$days);
         }else{
             $this->sendResponse(0);
         }



    }

    public function actionnew_caving_avg_tentativemilk(){

        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $new_cattle_code = $data['cattle_code'];
        $company_id = $data['company_id'];
        $date_of_birth = $data['date_of_birth'];


         $start_date = $date_of_birth;
         $end_date = date('Y-m-d', strtotime('+50 day', strtotime($start_date)));

        $query = " SELECT 
                 AVG( cp.morning +  cp.afternoun + cp.evenining) as total
            FROM cattle_record AS cr 
            LEFT join cattle_production AS cp ON cr.cattle_record_id =cp.cattle_record_id
            WHERE cr.number like '$new_cattle_code' 
            AND cr.company_id='$company_id'
             and cp.date between '$start_date' and '$end_date' ";



        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();

         if($queryResult){
             $this->sendResponse($queryResult);
         }else{
             $this->sendResponse(0);
         }



    }
    public function actioncattles_production_tentativemilk_insemination_date(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");

        $data = CJSON::decode($post, TRUE);

        $cattle_id = $data['cattle_id'];

        $latest_caving = $data['latest_caving'];
        $latest_caving = date("Y-m-d");

        $new_cattle_code = $data['new_cattle_code'];

        $company_id = $data['company_id'];

         $perious_date =  date('Y-m-d', strtotime('-15 day', strtotime($latest_caving)));

          $today_date=date("Y-m-d");

        $query = " SELECT 
                 sum( cp.morning +  cp.afternoun + cp.evenining) as total
            FROM cattle_record AS cr 
            LEFT join cattle_production AS cp ON cr.cattle_record_id =cp.cattle_record_id
            WHERE cr.number like '$new_cattle_code' 
            AND cr.company_id='$company_id'
             and cp.date between '$perious_date' and '$today_date' ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();

         if($queryResult){
             $this->sendResponse(($queryResult/15));
         }else{
             $this->sendResponse(0);
         }



    }
    public function actionLatest_cattle_product_latest_five_day(){

        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $company_id = $data['company_id'];

        $cattle_list = $data['cattle_list'];

        $data = [];
        $data['0'] = 0;
        foreach ($cattle_list as $value){
            $cattle_code =  $value['cattle_code'];
            $cattles_id =  $value['cattles_id'];
             $milk_count = Cattles_dairy_farmController::get_latest_production_of_cattle_latest_five($cattle_code,$company_id);
             if($milk_count>0){
                 $data[$cattles_id] =$milk_count;
             }
        }


        $this->sendResponse($data);
    }
    public function actionLatest_cattle_product(){

        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $company_id = $data['company_id'];

        $cattle_list = $data['cattle_list'];

        $data = [];
        $data['0'] = 0;
        foreach ($cattle_list as $value){
            $cattle_code =  $value['cattle_code'];
            $cattles_id =  $value['cattles_id'];
             $milk_count = Cattles_dairy_farmController::get_latest_production_of_cattle($cattle_code,$company_id);
             if($milk_count>0){
                 $data[$cattles_id] =$milk_count;
             }
        }


        $this->sendResponse($data);
    }


    public function actionCows_current_status_production(){
        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");

        $data = CJSON::decode($post, TRUE);
        $company_id =$data['company_id'];

        $pervious_date = date('Y-m-d', strtotime(' -1 day'));

        $query = "SELECT 
               cr.number,
            ( cp.morning +  cp.afternoun + cp.evenining) as production
            FROM cattle_record AS cr 
            LEFT join cattle_production AS cp ON cr.cattle_record_id =cp.cattle_record_id
            WHERE  cr.company_id='$company_id'  AND cp.date = '$pervious_date'  and ( cp.morning +  cp.afternoun + cp.evenining)>0";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        $final_data =[];
        foreach ($queryResult as $value){
            $number = $value['number'];
            $final_data[$number] = $value['production'];
        }

      echo json_encode($final_data);


    }
    public function actionDashbord_total_production(){
        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $company_id =$data['company_id'];
        
        $pervious_date = date('Y-m-d', strtotime(' -1 day'));
        $query = "SELECT 
            
            sum( cp.morning +  cp.afternoun + cp.evenining) as production
            FROM cattle_record AS cr 
            LEFT join cattle_production AS cp ON cr.cattle_record_id =cp.cattle_record_id
            WHERE  cr.company_id='$company_id'  AND cp.date = '$pervious_date'  and ( cp.morning +  cp.afternoun + cp.evenining)>0";
        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();
        echo $queryResult;
    }
    public function actionCattles_production(){


            date_default_timezone_set("Asia/Karachi");
            $post = file_get_contents("php://input");

             $data = CJSON::decode($post, TRUE);
             $result_cattle_list =$data['result_cattle_list'];


             $start_date  = $data['start_date'];
             $end_date =  $data['end_date'];

             $company_id =$data['company_id'];
             $final_result= array();
             foreach ($result_cattle_list as $value){
                 $cattle_code = $value['cattle_code'];

                   $production= Cattles_dairy_farmController::cattle_production(
                       $start_date,
                       $end_date,
                       $company_id,
                       $cattle_code
                   );
                 $final_result[$cattle_code]= $production;
             }

             $this->sendResponse($final_result);

    }
    public static function cattle_production( $start_date,$end_date,$company_id,$new_cattle_code){
        $query = " SELECT 
                 ( cp.morning +  cp.afternoun + cp.evenining)
            FROM cattle_record AS cr 
            LEFT join cattle_production AS cp ON cr.cattle_record_id =cp.cattle_record_id
            WHERE cp.date BETWEEN '$start_date' AND '$end_date'
            AND cr.number like '$new_cattle_code' AND cr.company_id='$company_id'
            order BY  cp.date  DESC
            LIMIT 1   ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();
          if($queryResult){
              return round($queryResult,2);
          } else{
              return '';
          }

    }

    public static function get_latest_production_of_cattle($new_cattle_code,$company_id){

        $current_date =date("Y-m-d");
        $perious_date =  date('Y-m-d', strtotime('-1 day', strtotime($current_date)));
        $query = " SELECT 
                 ( cp.morning +  cp.afternoun + cp.evenining)
            FROM cattle_record AS cr 
            LEFT join cattle_production AS cp ON cr.cattle_record_id =cp.cattle_record_id
            WHERE cr.number like '$new_cattle_code' AND cr.company_id='$company_id'
             and cp.date >= '$perious_date'
             order BY  cp.date  DESC
            LIMIT 1   ";


        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();
        if($queryResult){
            return round($queryResult,2);
        } else{
            return 0;
        }
    }
    public static function get_latest_production_of_cattle_latest_five($new_cattle_code,$company_id){

        $current_date =date("Y-m-d");
        $perious_date =  date('Y-m-d', strtotime('-5 day', strtotime($current_date)));
        $query = " SELECT 
                 ( cp.morning +  cp.afternoun + cp.evenining)
            FROM cattle_record AS cr 
            LEFT join cattle_production AS cp ON cr.cattle_record_id =cp.cattle_record_id
            WHERE cr.number like '$new_cattle_code' AND cr.company_id='$company_id'
            and cp.DATE >= '$perious_date'
            order BY  cp.date  DESC
            LIMIT 1   ";


        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();
        if($queryResult){
            return round($queryResult,2);
        } else{
            return 0;
        }
    }



}
