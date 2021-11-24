<?php

class PushnotificationController extends Controller
{

    public function actionSaveMessagingToken(){
            date_default_timezone_set("Asia/Karachi");

            $post = file_get_contents("php://input");
            $data = CJSON::decode($post , true);



            $client_id = $data['client_id'];
            $messaging_token = $data['messaging_token'];




            $object = SaveMessageToken::model()->findByAttributes([
                'client_id'=>$client_id
            ]);
            if(!$object){

                $object = New SaveMessageToken();
            }

            $object->type=$data['type'];
            $object->messaging_token=$messaging_token;
            $object->client_id=$client_id;
            // $object->token=$token;

            if($object->save()){
                $succes = true;
                $message = 'token saved';
            }else{
                $succes = false;
                $message = $object->getErrors();
            }

            $response = array(

            'success' => $succes,
            'message'=>$message,

            );

            echo json_encode($response);

    }

    public function actionRemoveMessagingToken(){
        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);



        $client_id = $data['client_id'];
        $messaging_token = $data['messaging_token'];





        SaveMessageToken::model()->deleteAllByAttributes(
            [
                'client_id'=>$client_id
            ]
        );

        $response = array(

            'success' => true,
            'message'=>'deleted',

        );

        echo json_encode($response);
    }

}
