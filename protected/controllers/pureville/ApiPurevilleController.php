<?php

class ApiPurevilleController extends Controller
{



    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';

    /**
     * @return array action filters
     */


    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }



    public function sendResponse($data)
    {
        echo  json_encode($data);
    }

    public function actionBlockAndAreaList()
    {
         die("here one");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $company_id = $data['company_branch_id'];

        $query_block = "Select b.block_id , b.block_name from block as b 
            where  b.company_id ='$company_id' ";

        $blockList_result = Yii::app()->db->createCommand($query_block)->queryAll();

        $query_area = "select a.area_id , a.area_name from area as a 
            where  a.company_id ='$company_id' ";

        $areaList_result = Yii::app()->db->createCommand($query_area)->queryAll();

        $query_zone = "select z.zone_id ,z.name from zone as z 
            where z.company_branch_id ='$company_id' ";

        $zoneList_result = Yii::app()->db->createCommand($query_zone)->queryAll();


        $result = array();
        $result['arealist'] = $areaList_result;
        $result['blockList'] = $blockList_result;
        $result['zoneList'] = $zoneList_result;

        $response = array(

            'success' =>true,

            'data' =>$result,
        );

        $this->sendResponse($response);

    }


    public function actioncreateCustomer(){


        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $userName =   $data['userName'];
        $userPhoneNumber = $data['cell_no_1'];
        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id =  $data['company_branch_id'] ;
        $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];
        $unregister =  Client::model()->findByAttributes(array('cell_no_1'=>$userPhoneNumber , 'is_active'=>'0' ,'company_branch_id'=>$company_branch_id ));

        if($unregister){
            try{
                $unregister->delete();
            }catch (Exception $e){
                $IDArray = array();
                $IDArray['client_id'] = "" ;

                $response = array(
                    'code' => 401,
                    'company_branch_id'=>0,
                    'success' => false,
                    'alreadyExists'=>false ,
                    'message'=>"This Phone Number Already Register",
                    'data' => $IDArray
                );
                $this->sendResponse($response);
                die();
            }

        }


        $checkUserName = Client::model()->findByAttributes(array('userName'=>$userName, 'company_branch_id'=>$company_branch_id));
        $checkUserPhoneNumber = Client::model()->findByAttributes(array('cell_no_1'=>$userPhoneNumber , 'company_branch_id'=>$company_branch_id));

        if($checkUserName){
            $IDArray = array();
            $IDArray['client_id'] = "" ;
            $response = array(
                'code' => 401,
                'company_branch_id'=>0,
                'success' =>false,
                'alreadyExists'=>true ,
                'message'=>'This username already exists . Try another username',
                'data' =>$IDArray
            );
        }elseif($checkUserPhoneNumber){
            $IDArray = array();
            $IDArray['client_id'] = "" ;
            $response = array(
                'code' => 401,
                'company_branch_id'=>0,
                'success' =>false,
                'alreadyExists'=>true ,
                'message'=>'This phone No. already exists . Try another phone No.',
                'data' =>$IDArray ,
            );
        }else{
            $network_id = 0;
            if(isset($data['network_id'])){
                $network_id = $data['network_id'];
            }
            $client = new Client();
            $client->user_id = '2';



            $client->zone_id= $data['zone_id'];
            $client->network_id= $network_id;

            $block_id = $data['block_id'];

            $block_object =Block::model()->findByPk($block_id);

            $block_name = $block_object['block_name'];

            $area_id = $data['area_id'];

            $area_object = Area::model()->findByPk(intval($area_id));

            $area_name =  $area_object['area_name'];
            $fullName =  $data['house_no'].' '.$data['sub_no'].' '.$block_name.' '.$area_name;
            $client->fullname=$fullName;
            $client->userName= $data['userName'];
            $client->password= $data['password'];
            $client->company_branch_id= $data['company_branch_id'];
            $client->father_or_husband_name = ' ';
            $client->date_of_birth = $data['date_of_birth'];
            $client->email = $data['email'];
            $client->cnic = $data['cnic'];
            $client->cell_no_1 = $data['cell_no_1'];
            $client->client_type = $data['client_type'];
            $client->client_type = $data['client_type'];
            $client->block_id = $data['block_id'];
            $client->area_id = $data['area_id'];

            $client->house_no =$data['house_no'];
            $client->sub_no =$data['sub_no'];

            $client->cell_no_2 = '12334543';
            $client->residence_phone_no = '1234567';
            $client->city= 'no';
            $client->area= 'no';
            $client->address = $fullName;
            $client->is_active = 1;
            $client->is_deleted= 0;
            $client->created_by = '2';
            $client->updated_by = '2';
            $client->login_form = '2';
            $client->view_by_admin = '1';
            if($client->save()) {
                $clientID =$client->client_id ;
                $code = sprintf("%04s",mt_rand(0000, 9999));
                $authenticate = new Authentication();
                $authenticate->client_id = $clientID ;
                $authenticate->code = $code;
                $authenticate->SetTime = time();

                if($authenticate->save()){

                    $IDArray = array();
                    $IDArray['client_id'] = $clientID ;
                    $response = array(
                        'code' => 200,
                        'company_branch_id'=>0,
                        'success' =>true,
                        'alreadyExists'=>false,
                        'message'=>"Customer have created successfully .",
                        'data' =>$IDArray,
                    );
                    $message = "Your verification code for ".$companyTitle. " is ".$code;


                    // smsLog::saveSms($clientID ,$company_branch_id ,$data['cell_no_1'] ,$data['fullName'] ,$message);

                    // $this->sendSMS($data['cell_no_1'],$message , $companyMask , $company_branch_id ,$network_id);


                }
            }else{

                $IDArray = array();
                $IDArray['client_id'] = "" ;

                $response = array(
                    'code' => 401,
                    'company_branch_id'=>0,
                    'success' => false,
                    'alreadyExists'=>false ,
                    'message'=>$client->getErrors(),
                    'data' => $IDArray
                );
            }
        }
        $this->sendResponse($response);
    }

    public function actionSetDeactiveReason(){
        $query = "select * from client as c
            where  c.company_branch_id ='14' ";

        $client_result = Yii::app()->db->createCommand($query)->queryAll();

         foreach ($client_result as $value){
             $deactive_reason = $value['deactive_reason'];

              if(!empty($deactive_reason)){
                  $client_id = $value['client_id'];
                  $clientObject = Client::model()->findByPk(intval($client_id));

                  $reasonObject = SampleClientDropReason::model()->findByAttributes(
                      array(
                          'reason'=>$deactive_reason
                      )
                  );

                  if($reasonObject){

                      $sample_client_drop_reason_id = $reasonObject['sample_client_drop_reason_id'];
                      $clientObject->deactive_reason_id = $sample_client_drop_reason_id;

                      if($clientObject->save()){
                          echo $sample_client_drop_reason_id."<br>";
                      }
                  }else{
                      $deactiveOBject = New SampleClientDropReason();
                      $deactiveOBject->reason =$deactive_reason;
                      $deactiveOBject->company_branch_id ='14';
                      if($deactiveOBject->save()){
                          $sample_client_drop_reason_id= $deactiveOBject->sample_client_drop_reason_id;

                          $clientObject->deactive_reason_id = $sample_client_drop_reason_id;

                          if($clientObject->save()){
                              echo $sample_client_drop_reason_id."<br>";
                          }
                      }
                  }
              }



         }


    }


}
