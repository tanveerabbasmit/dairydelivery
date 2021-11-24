<?php

class PaymenttransfercollectionvaultootherController extends Controller
{
	public function actionPaymenttransfercollectionvaultoother_form()
	{


        $collectionvault = blockData::get_collectionvault_array_list();


        $data = [];
        $data['date'] =date("Y-m-d");

        $data['collectionvault'] =$collectionvault;
       
        $data['base_url']  =  Yii::app()->createAbsoluteUrl('Paymenttransfercollectionvaultoother/base');

        $this->render('paymenttransfercollectionvaultoother_form',array(
            'data'=>json_encode($data),
        ));
	}
	public function actionbase_save_payment_transfer_to_other_vault(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);


        $payment_transfer_collection_vaul_to_other_id = $data['payment_transfer_collection_vaul_to_other_id'];

        if($payment_transfer_collection_vaul_to_other_id>0){
            $object = PaymentTransferCollectionVaulToOther::model()->findByPk($payment_transfer_collection_vaul_to_other_id);
        }else{
            $object = New PaymentTransferCollectionVaulToOther();
        }
        $object->collection_vault_id_from =$data['collection_vault_id_from'];
        $object->collection_vault_id_to =$data['collection_vault_id_to'];
        $object->action_date =$data['action_date'];
        $object->action_date =$data['action_date'];
        $object->amount =$data['amount'];
        $object->remarks =$data['remarks'];

        if($object->save()){
            $success = true;
            $message ='';
        }else{
            $success = true;
            $message = $object->getErrors();
        }



        $responce = [];
        $responce['success'] = $success;
        $responce['message'] = $message;

        echo json_encode($responce);
    }

    public function actionbase_get_new_payment_transfer_list(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $collection_vault_id_from =$data['collection_vault_id_from'];

        $query = "SELECT 
            p.payment_transfer_collection_vaul_to_other_id,
            p.collection_vault_id_from,
            p.collection_vault_id_to,
            p.amount,
            p.action_date,
            p.remarks,
            v_from.collection_vault_name AS collection_vault_name_from,
            v_to.collection_vault_name AS collection_vault_name_to
            FROM payment_transfer_collection_vaul_to_other AS p 
            LEFT JOIN collection_vault AS v_from ON p.collection_vault_id_from = v_from.collection_vault_id
            LEFT JOIN collection_vault AS v_to ON p.collection_vault_id_from = v_to.collection_vault_id
            WHERE p.collection_vault_id_from = '$collection_vault_id_from'
            order by  p.action_date DESC";

        $payment_list  = Yii::app()->db->createCommand($query)->queryAll();

        echo json_encode($payment_list);

    }

    public function actionbase_delete_transfer_amount(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $payment_transfer_collection_vaul_to_other_id = $data['payment_transfer_collection_vaul_to_other_id'];

        $object = PaymentTransferCollectionVaulToOther::model()->findByPk($payment_transfer_collection_vaul_to_other_id);

        $object->delete();

    }


}