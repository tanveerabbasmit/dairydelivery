<?php

class EffectiveDateScheduleController extends Controller
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

    public function actionDeleteEffectiveSchedule(){
        $getDat = $_GET;
        $client_id = $getDat['client_id'];
        $product_id = $getDat['product_id'];

        $EffectiveDateSchedule =EffectiveDateSchedule::model()->findByAttributes(
            array('client_id'=>$client_id , 'product_id'=>$product_id)
        );
        $effective_date_schedule_id = $EffectiveDateSchedule['effective_date_schedule_id'];

        EffectiveDateScheduleFrequency::model()->deleteAllByAttributes(
            array('effective_date_schedule_id'=>$effective_date_schedule_id)
        );
        if($EffectiveDateSchedule){
            $EffectiveDateSchedule->delete();
        }
       // Yii::$app->redirect('/EffectiveDateSchedule/futureScheduled?client_id='.$client_id);
        // Yii::$app->response->redirect(['site/dashboard','id' => 1, 'var1' => 'test']);
        $this->redirect(array('/EffectiveDateSchedule/futureScheduled?client_id='.$client_id));
       // $this->redirect(array('/site/login'));


    }
	public function actionfutureScheduled(){

       /* Yii::app()->request->redirect('/path/to/url');*/


	        $getDat = $_GET;
            $company_id = Yii::app()->user->getState('company_branch_id');

	         if(isset($getDat['client_id'])){

                 $client_id = $getDat['client_id'];
                 $query_interval="select ei.* ,c.fullname ,c.address , p.name as product_name from effective_date_interval_schedule ei
                    left join client as c ON c.client_id =ei.client_id
                    left join product as p ON p.product_id = ei.product_id
                    where ei.client_id = '$client_id' ";

                $query_weekly= " SELECT e.client_id,
                    c.fullname ,
                    p.product_id ,
                    p.NAME as product_name ,
                    e.date ,
                    ed.quantity,
                    f.day_name
                    FROM effective_date_schedule AS e
                    INNER  JOIN effective_date_schedule_frequency AS ed 
                    ON e.effective_date_schedule_id =ed.effective_date_schedule_id
                    INNER  JOIN product as p ON p.product_id =e.product_id 
                    left JOIN frequency AS f ON f.frequency_id = ed.frequency_id
                    LEFT JOIN client AS c ON c.client_id = e.client_id
                    WHERE e.client_id ='$client_id' ";

             }else{

                 $query_interval="select ei.* ,c.fullname ,c.address , p.name as product_name from effective_date_interval_schedule ei
                    left join client as c ON c.client_id =ei.client_id
                    left join product as p ON p.product_id = ei.product_id
                     where c.company_branch_id ='$company_id' ";
             }
             $interval_result = Yii::app()->db->createCommand($query_interval)->queryAll();
             $weekly_result = Yii::app()->db->createCommand($query_weekly)->queryAll();

              $data = array();
             $interval_size = sizeof($interval_result);
             $weekly_size = sizeof($weekly_result);

             $data['interval_size'] =$interval_size ;
             $data['weekly_size'] =$weekly_size ;
             $data['weekly_result'] =$weekly_result ;


             $this->render('futureScheduled' , array(
                "zoneList"=>json_encode($interval_result),
                "data"=>json_encode($data),
                "companyBranchList"=>companyBranchData::getCompanyBranchList(),
             ));

    }

    public function actiondelete(){
        $post = file_get_contents("php://input");

        $data = CJSON::decode($post, TRUE);

        $responce = array('success'=>false);

        $effective_date_interval_schedule_id = $data['effective_date_interval_schedule_id'];

        $delete =EffectiveDateIntervalSchedule::model()->deleteByPk(intval($effective_date_interval_schedule_id));

        if($delete){
              $responce['success'] = true;
        }

        echo json_encode($responce);


    }
}
