<?php

class CompanymessageController extends Controller
{
	public function actionChange_sms_option()
	{


	    $data = $_GET;
        $company_id = Yii::app()->user->getState('company_branch_id');
	    if(isset($data['flage'])){
	         $flage = $data['flage'];
	         if($flage=='on'){
	             $company_object = Company::model()->findByPk($company_id);

                 $company_object->sms_mask='NA';
                 if($company_object->save()){

                 }else{
                     echo "<pre>";
                     print_r($company_object->getErrors());
                     die();
                 }
             }else{
                 $company_object = Company::model()->findByPk($company_id);
                 $company_object->sms_mask=$company_object['temporary_company_mask'];
                 $company_object->save();
             }

        }



        $company_object  = Company::model()->findByPk($company_id);

        $sms_mask =  $company_object['sms_mask'];
         $data = [];

         $data['message'] = 'Currently SMS Option is ON  ';
         $data['option'] = 'on';
         $data['icone'] = 'fa fa-check';
         if($sms_mask=='NA'){
             $data['message'] = 'Currently SMS Option is off  ';
             $data['option'] = 'off';
             $data['icone'] = 'fa fa-times';
         }

		$this->render('change_sms_option',[
            'data'=>$data
        ]);
	}


}