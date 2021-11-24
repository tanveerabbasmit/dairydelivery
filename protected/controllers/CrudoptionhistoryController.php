<?php

class CrudoptionhistoryController extends Controller
{
	public function actioncrudoptionhistory_view()
	{
        $this->render('crudoptionhistory_view',array(
            'clientList'=>json_encode(array()),
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
        ));
	}

	public  function actionget_list(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

         $user_list =  userData::getUSerList();

        $user_list = json_decode($user_list,true);

          $user_object = [];

          foreach ($user_list as $value){
              $user_id = $value['user_id'];
              $user_object[$user_id] = $value['full_name'];
          }


         $startDate = $data['startDate'];
         $endDate = $data['endDate'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query = " SELECT 
            h.client_id,
            h.action_name,
            h.rearks,
            h.data_befour_action,
            h.action_date,
            h.action_time,
            h.new_value,
            c.fullname AS cleint_name,
            c.address ,
            u.full_name AS user_name
            FROM  crud_option_history AS h
            LEFT JOIN  client  AS c ON h.client_id = c.client_id
            LEFT JOIN user AS u ON u.user_id =h.user_id
            WHERE h.action_date BETWEEN '$startDate' AND '$endDate' 
            and h.company_id ='$company_id'
            ";



        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

        $final_result = [] ;
        foreach ($queryResult as $value){

            $value['new_value'] =round($value['new_value'],2);

            if($value['action_name']=='edit_delivery'){
              $data_befour_action =  json_decode($value['data_befour_action'],true);
              $value['orginal_value'] =  round($data_befour_action['data'][0]['deliveredQuantity'],2);
              $value['befour_date'] =  $data_befour_action['selectDate'] .' '.$data_befour_action['data'][0]['time'];
              $value['befour_user_name'] ='';
              $befour_rider_id = $data_befour_action['rider_id'];
              $object_rider =Rider::model()->findByPk($befour_rider_id);
              if($object_rider){
                  $value['befour_user_name'] =$object_rider['fullname']."(R)";
              }
            }

            if( $value['action_name']=='delete_delivery'){

              $data_befour_action =  json_decode($value['data_befour_action'],true);


              $value['orginal_value'] =  round($data_befour_action['data'][0]['deliveredQuantity'],2);
              $value['befour_date'] =  $data_befour_action['selectDate'] .' '.$data_befour_action['data'][0]['time'];
              $value['befour_user_name'] ='';
              $befour_rider_id = $data_befour_action['rider_id'];
              $object_rider =Rider::model()->findByPk($befour_rider_id);
              if($object_rider){
                  $value['befour_user_name'] =$object_rider['fullname']."(R)";
              }
              $value['new_value'] ='0';
            }


            if($value['action_name']=='edit_payment' || $value['action_name']=='delete_payment' ){
                $data_befour_action =  json_decode($value['data_befour_action'],true);

                if(isset($data_befour_action['time'])){
                    $date=date_create($data_befour_action['time']);

                    $time = date_format($date,"H:i");

                }else{
                    $time ='00-00';
                }



                $payment_master_id = $data_befour_action['payment_master_id'];

                $payment_object = PaymentMaster::model()->findByPk($payment_master_id);
                if($payment_object){
                   $user_id =  $payment_object['user_id'];
                   if(isset($user_object[$user_id])){
                        $value['befour_user_name'] =$user_object[$user_id];
                   }else{

                       $rider_object = Rider::model()->findByPk($payment_object['rider_id']);
                        if(isset($rider_object['fullname'])){
                            $value['befour_user_name'] =$rider_object['fullname'];
                        }

                   }
                }else{
                    $rider_object = Rider::model()->findByPk($payment_object['rider_id']);
                    if(isset($rider_object['fullname'])){
                        $value['befour_user_name'] =$rider_object['fullname'];
                    }
                }
                $value['orginal_value'] =  round($data_befour_action['amount_paid'],2);
                $value['befour_date'] = $data_befour_action['date']." ".$time;
            }
            if($value['action_name']=='delete_payment' ) {
                $value['new_value'] = '0';

                $data_befour_action =  json_decode($value['data_befour_action'],true);

                $rider_id =   $data_befour_action['rider_id'];
                if($rider_id>0){

                    $rider_object = Rider::model()->findByPk($rider_id);



                    $value['befour_user_name'] = $rider_object['fullname']."(R)";

                }else{
                    $user_id = $data_befour_action['user_id'];

                    $user_object = User::model()->findByPk($user_id);
                    $value['befour_user_name'] = $user_object['full_name'];

                }

            }


            if($value['action_name']=='edit_rate' ) {

                $befour_data = json_decode($value['data_befour_action'],true);

                $value['befour_user_name'] =$befour_data['add_user_name'];
                $value['orginal_value'] = $befour_data['allready_price'];
                $value['befour_date'] = $befour_data['created_at'];

            }
            if($value['action_name']=='farm_payment' ) {


                $befour_data = json_decode($value['data_befour_action'],true);



                $value['befour_user_name'] =$befour_data['action_date'];
                $value['orginal_value'] = $befour_data['amount'];
                $value['befour_date'] = $befour_data['action_date'];
                 $client_id = $value['client_id'];
                $farm_object = Farm::model()->findByPk($client_id);

                $value['cleint_name'] = $farm_object['farm_name'];

            }









            $final_result[] = $value;
        }

        echo  json_encode($final_result);

    }

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}