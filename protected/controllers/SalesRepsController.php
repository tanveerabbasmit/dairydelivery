<?php

class SalesRepsController extends Controller
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

    public function actionMangeSaleReps()
    {


        $this->render('mangeSaleReps' , array(
            "zoneList"=>saleRepsData::getSaleRepsList(),
             "companyBranchList"=>companyBranchData::getCompanyBranchList(),
        ));
    }

    public function actionsaveNewSalesReps_save_new()
    {
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);




       $sales_reps_id =  $data['sales_reps_id'];



        if($sales_reps_id >0){
              
            $zone = SalesReps::model()->findByPk($sales_reps_id);
            $zone->name = $data['name'];
            $zone->phone = $data['phone'];
            $zone->address = $data['address'];
            $zone->email = $data['email'];
            $zone->company_id = Yii::app()->user->getState('company_branch_id');


            if($zone->save()){
                zoneData::$response['success']=true;
                zoneData::$response['message']=0;
            }else{
                zoneData::$response['success'] = false ;
                zoneData::$response['message'] = $zone->getErrors() ;
            }
            echo json_encode(zoneData::$response);

        }else{

            $zone=new SalesReps();
            $zone->name = $data['name'];
            $zone->phone = $data['phone'];
            $zone->address = $data['address'];
            $zone->email = $data['email'];
            $zone->company_id = Yii::app()->user->getState('company_branch_id');

            if($zone->save()){
                zoneData::$response['success'] = true ;
                zoneData::$response['message'] = $zone->sales_reps_id ;

            }else{
                zoneData::$response['success'] = false ;
                zoneData::$response['message'] = $zone->getErrors() ;
            }
            echo json_encode(zoneData::$response);

        }


    }
    public function actiondelete(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $sales_reps_id = $data['sales_reps_id'];

        $zone = DiscountType::model()->findByPk($sales_reps_id);
        if($zone){

            if($zone->delete()){

                zoneData::$response['success'] = true ;

            }else{
                zoneData::$response['success'] = true ;
            }
        }else{
            zoneData::$response['success'] = true ;
        }


        echo json_encode(zoneData::$response);
    }
}