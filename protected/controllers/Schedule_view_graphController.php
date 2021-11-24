<?php

class Schedule_view_graphController extends Controller
{
	public function actionSchedule_view_graph_view($id)
	{

	    $dats =[
	        'client_id'=>$id,
	        'start_date'=>'2021-05-01',
	        'end_date'=>date("Y-m").'-31',
        ];

	    $list_delivery = Schedule_view_graph_data_layyer::date_rage_delivery($dats);
        $company_id = Yii::app()->user->getState('company_branch_id');
        $object =Client::model()->findByPk($id);

        if($object['company_branch_id']!=$company_id){
                die('You are not allowed');
        }
        $final_result= [];
        foreach ($list_delivery as $value){
              $date =$value['date'];
              $product_name =$value['product_name'];
              $quantity =$value['quantity'];
              $one_object = [
                  'date'=>$date,
                  'title'=>$product_name."(".$quantity.")",
                
              ];
            $final_result[] =$one_object;
        }
        $product_list = productData::product_list();
         foreach ($product_list as $value){
             $product_id = $value['product_id'];
             $interval_type =Schedule_view_graph_data_layyer::interval_type($id,$product_id);

             if($interval_type==1){
                 $x= strtotime(date('Y-m-d'));
                 $y= strtotime('2023-01-01');

                 $wekly_schedual =Schedule_view_graph_data_layyer::get_weekly_schedule($id,$product_id);

                 while($x < ($y+8640)) {

                     $one_object = array();
                     $selectDate = date("Y-m-d", $x);
                     $data['startDate'] = $selectDate;
                     $x += 86400;
                     $one_object = [
                         'date'=>$selectDate,
                         'title'=>'good',
                     ];
                   //  $final_result[] =$one_object;
                 }

             }


         }


        $data = [];
        $data['base_url']= Yii::app()->createAbsoluteUrl('halt_order/base');
        $data['event_list'] =$final_result ;
        return $this->render('Schedule_view_graph_view', [
            'data' =>$data ,
        ]);


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