<?php

class CattleRecordController extends Controller
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


	public function actionManageCattle()
	{

		$this->render('manageCattle' ,array(
                "CattleList"=>cattleData::getCattleList(),
                "companyBranchList"=>json_encode(array()),
             )
        );
	}


	public function actiondeleteCattle($id){
	    CattleRecord::model()->deleteByPk(intval($id));

        $this->redirect(array('/cattleRecord/manageCattle'));
    }
	public function actionaddCattle($id)
	{
		$this->render('addCattle' ,array(
                "addData"=>cattleData::addCattle($id),
                "cattle_id"=>$id,
                "date"=>date("Y-m-d"),
          )

        );
	}

	public function actionsaveCattle()
	{


          $company_id = Yii::app()->user->getState('company_branch_id');

          $pictureName = basename($_FILES["picture"]['name']);
          $extention_object = (explode('.', $pictureName));

          $extention = end($extention_object);

          $target_dir = Yii::app()->basePath.'/../themes/milk/images/cattle/';
          $random_name = rand(0,99999).rand(0,99999).rand(0,99999);

          $fullName = $random_name.'.'.$extention;

          $packingSlipName_targetFile = $target_dir.$fullName ;

          move_uploaded_file($_FILES["picture"]["tmp_name"], $packingSlipName_targetFile);

          $data = $_POST;

           /* echo "<pre>";
             print_r($data);
             die();*/

          $cattle_record_id = $data['cattle_record_id'];

          $milking_on_off_date =$data['milking_on_off_date'];


          if($cattle_record_id==0){


              $cattle = New CattleRecord();
              $cattle->number =  $data['number'];
              $cattle->milking =  $data['milking'];
              $cattle->is_active =  $data['is_active'];
              $cattle->type =  $data['type'];
              $cattle->create_date =  $data['create_date'];
              $cattle->company_id = $company_id;
              $cattle->milking_time_morning = isset($data['milking_time_morning'])?$data['milking_time_morning']:'0';
              $cattle->milking_time_afternoun = isset($data['milking_time_afternoun'])?$data['milking_time_afternoun']:'0';
              $cattle->milking_time_evening = isset($data['milking_time_evening'])?$data['milking_time_evening']:'0';
              if(!empty($pictureName)) {
                  $cattle->picture = $fullName;
              }else{
                  $cattle->picture ='noImage.jpg';
              }

              if($cattle->save()){

                     $cattle_record_id = $cattle->cattle_record_id;

                     if($data['milking'] =='0'){

                         milkingDuration::add_new_cattle_milking_recored($cattle_record_id,$data['milking'],$milking_on_off_date);
                     }else{

                         milkingDuration::add_new_cattle_milking_recored_on($cattle_record_id,$data['milking'],$milking_on_off_date);

                     }




                  $this->redirect(array('/cattleRecord/addCattle/'.$cattle->cattle_record_id));

              }else{
                  var_dump($cattle->getErrors());
                  die();
              }
          }else{


              $cattle =CattleRecord::model()->findByPk(intval($cattle_record_id));
              $cattle->number =  $data['number'];
              $cattle->milking =  $data['milking'];
              $cattle->type =  $data['type'];
              $cattle->is_active =  $data['is_active'];
              $cattle->company_id = $company_id;
              $cattle->milking_time_morning = isset($data['milking_time_morning'])?$data['milking_time_morning']:'0';
              $cattle->milking_time_afternoun = isset($data['milking_time_afternoun'])?$data['milking_time_afternoun']:'0';
              $cattle->milking_time_evening = isset($data['milking_time_evening'])?$data['milking_time_evening']:'0';
              if(!empty($pictureName)){

                  $cattle->picture = $fullName;

              }
              if($cattle->save()){
                  milkingDuration::edit_new_cattle_milking_recored($cattle_record_id,$data['milking'],$milking_on_off_date);

                  $this->redirect(array('/cattleRecord/addCattle/'.$cattle->cattle_record_id));
              }else{
                  var_dump($cattle->getErrors());
              }
          }









	}


}