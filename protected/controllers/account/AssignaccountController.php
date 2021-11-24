<?php
class AssignaccountController extends Controller
{
    public function filters(){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $company_object = Company::model()->findByPk($company_id);
        if($company_object['show_accounting']==0){
            die('You are not Allowed');
        }

    }
   public function actionVoucherAccount(){

       $voucher_type_array = [
           ['voucher_type_id'=>1,'debit_account_id'=>'0','credit_account_id'=>'0','name'=>'Product Sale Voucher','update'=>false],
           ['voucher_type_id'=>2,'debit_account_id'=>'0','credit_account_id'=>'0','name'=>'Receipt Customer','update'=>false],
           ['voucher_type_id'=>3,'debit_account_id'=>'0','credit_account_id'=>'0','name'=>'Vendor Payment','update'=>false],
           ['voucher_type_id'=>4,'debit_account_id'=>'0','credit_account_id'=>'0','name'=>'farm payment','update'=>false],
           ['voucher_type_id'=>5,'debit_account_id'=>'0','credit_account_id'=>'0','name'=>'Farm Purchase','update'=>false],
           ['voucher_type_id'=>6,'debit_account_id'=>'0','credit_account_id'=>'0','name'=>'Vendor Purchase','update'=>false],
       ];
       $company_id = Yii::app()->user->getState('company_branch_id');

       $voucher_type_array_final = [];

       foreach ($voucher_type_array as $value){
          $object = AssignAccount::model()->findByAttributes([
              'company_id'=>$company_id,
              'voucher_type_id'=>$value['voucher_type_id'],
          ]);
          if($object){
              $value['debit_account_id'] = $object['debit_account_id'];
              $value['credit_account_id'] = $object['credit_account_id'];
          }

           $voucher_type_array_final[] =$value;

       }


        $result = accounting_data::get_account_list();



        $list =  $result['data'];


        accounting_data::account_list_array($list);

        $account_all_data =  accounting_data::$account_all_data;


       $this->render('voucher_account' , array(
           "zoneList"=>json_encode($account_all_data),
           "companyBranchList"=>json_encode($voucher_type_array_final),
       ));
   }
   public function actionbaseSave_account_of_voucher(){
       $post = file_get_contents("php://input");
       $get_data = CJSON::decode($post, TRUE);

       $account_list = $get_data['account_list'];
       $data = $get_data['list'];

       $company_id = Yii::app()->user->getState('company_branch_id');

       $object=AssignAccount::model()->findByAttributes([
           'company_id'=>$company_id,
           'voucher_type_id'=>$data['voucher_type_id'],
       ]);

       if(!$object){
           $object = New AssignAccount();
       }



       $object->voucher_type_name =$data['name'];
       $object->voucher_type_id =$data['voucher_type_id'];
       $object->debit_account_name =accounting_data::get_accoutn_name_by_id($data['debit_account_id'],$account_list);
       $object->debit_account_id =$data['debit_account_id'];
       $object->credit_account_name =accounting_data::get_accoutn_name_by_id($data['credit_account_id'],$account_list);
       $object->credit_account_id =$data['credit_account_id'];
       $object->company_id =$company_id;
       if($object->save()){
              $success = true;
              $message ='';
       }else{
           $success = false;
           $message = $object->getErrors();

       }
       $responce = [];
       $responce['success'] = $success;
       $responce['messgae'] = $message;
       echo  json_encode($responce);


   }


}