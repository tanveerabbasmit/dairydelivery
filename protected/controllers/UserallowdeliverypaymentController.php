<?php

class UserallowdeliverypaymentController extends Controller
{
    public function actionsaveNewZoneget_rider_list_for_option(){

        $user_list = userData::getUSerList_except_sadmin();

        $final_data = [];
         foreach ($user_list as $value){

             $user_id = $value['user_id'];

             $one_object =[];
             $one_object['user'] = $value;
             $one_object['rider'] = riderData::rider_for_allow_right($user_id);
             $final_data[]  =$one_object;
         }

         echo  json_encode($final_data);

    }
	public function actionUser_allow_option()
	{
	   $user_list = userData::getUSerList_except_sadmin();
       $result =[];
       $user_id = Yii::app()->user->getState('user_id');
       $user_object = User::model()->findByPk($user_id);
       $supper_admin_user =  $user_object['supper_admin_user'];
       if($supper_admin_user==0){
           $this->render('not_allow',[
           ]);
           die();
       }
	   foreach ($user_list as $value){

           if($value['receipt_add']>0){
               $value['receipt_add'] =true;
           }else{
               $value['receipt_add'] =false;
           }

           if($value['receipt_edit']>0){
               $value['receipt_edit'] =true;
           }else{
               $value['receipt_edit'] =false;
           }

           if($value['receipt_delete']>0){
               $value['receipt_delete'] =true;
           }else{
               $value['receipt_delete'] =false;
           }

           if($value['delivery_add']>0){
               $value['delivery_add'] =true;
           }else{
               $value['delivery_add'] =false;
           }

           if($value['delivery_edit']>0){
               $value['delivery_edit'] =true;
           }else{
               $value['delivery_edit'] =false;
           }
           if($value['delivery_delete']>0){
               $value['delivery_delete'] =true;
           }else{
               $value['delivery_delete'] =false;
           }


           $result[] = $value;
       }


		$this->render('user_allow_option',[
		    'zoneList'=>json_encode($result),
		    'companyBranchList'=>json_encode([])
        ]);
	}

	public function actiondelete_allow_option(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);

        $user_id = $data['list']['user_id'];
        $falge = $data['falge'];
         $attributes = $data['list'][$falge];

        $object =User::model()->findByPk($user_id);
        $object->$falge = $attributes;
        $object->save();

    }

    public function actiondelete_change_rider_of_any_user(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);

        $list = $data['list'];
        $user_id = $data['user_id'];
        $rider_id =  $list['rider_id_origional'];


         if($list['allow_add']){
             $list['allow_add'] =1;
         }else{
             $list['allow_add'] =0;;
         }

        if($list['allow_edit']){
            $list['allow_edit'] =1;
        }else{
            $list['allow_edit'] =0;;
        }


        if($list['allow_delete']){
            $list['allow_delete'] =1;
        }else{
            $list['allow_delete'] =0;;
        }

          $object = RiderDeliveryRightOption::model()->findByAttributes([
              'rider_id'=>$rider_id,
              'user_id'=>$user_id,

          ]);
          if($object){



          }else{

              $object =new RiderDeliveryRightOption();
          }

          $object->rider_id = $list['rider_id_origional'];
          $object->user_id = $user_id;
          $object->allow_add = $list['allow_add'];
          $object->allow_edit = $list['allow_edit'];
          $object->password_for_edit_delete = $list['password_for_edit_delete'];
          $object->edit_past_days = $list['edit_past_days'];
          $object->allow_delete = $list['allow_delete'];
          $object->delete_past_days = $list['delete_past_days'];
          $object->add_past_days = $list['add_past_days'];

           if($object->save()){

           }else{

           }



    }

}