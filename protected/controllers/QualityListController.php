<?php

class QualityListController extends Controller
{
    public function filters()
    {
        $user_id = Yii::app()->user->getState('user_id');
        if(!$user_id){
         //   $this->redirect(Yii::app()->user->returnUrl);
        }

        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }




	public function actionQualityListManage()
	{
        $user_id = Yii::app()->user->getState('user_id');
        if(!$user_id){
            $this->redirect(Yii::app()->user->returnUrl);
        }
        Yii::app()->session["view"] = 0;
        $this->render('qualityListManage' , array(
            "zoneList"=>qualityListData::getqualityList(),
            "companyBranchList"=>companyBranchData::getCompanyBranchList(),
        ));
	}

    public function actioneditquality(){

        $user_id = Yii::app()->user->getState('user_id');
        if(!$user_id){
            $this->redirect(Yii::app()->user->returnUrl);
        }

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo qualityListData::editQualityFunction($data);
    }

    public function actiondelete(){

        $user_id = Yii::app()->user->getState('user_id');
        if(!$user_id){
            $this->redirect(Yii::app()->user->returnUrl);
        }

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo qualityListData::deleteFunction($data);
    }
    public function actionQuality_report_view_in_mobile(){

      $this->layout=false;
       $farm_id =2;
       $query = "SELECT 
            fq.date FROM farm_quality AS fq
            WHERE fq.farm_id='$farm_id'
            ORDER BY DATE DESC
            LIMIT 1 ";
        $date =  Yii::app()->db->createCommand($query)->queryscalar();


        $query="select  ql.quality_list_id ,
            ql.quality_name , ifnull(fq.quantity_value ,'') as quantity_value 
            from quality_list as ql
            left join farm_quality as fq on ql.quality_list_id = fq.quality_list_id
            and fq.date ='$date' and fq.farm_id = '$farm_id'
            where ql.company_id ='1' ";


        $data =  Yii::app()->db->createCommand($query)->queryAll();



        $this->render('quality_report_view_in_mobile' , array(
            'data'=>$data
        ));

    }





}