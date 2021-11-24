<?php

class MilkDailyQuallityreportController extends Controller
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
        $user_id = Yii::app()->user->getState('user_id');
        if(!$user_id){
            $this->redirect(Yii::app()->user->returnUrl);
        }

        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }


	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('saveQuanlityReport_selectData','index','view','QualityReport' ,'saveQuanlityReport'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


	public function actionQualityReport()
	{

        $company_id = Yii::app()->user->getState('company_branch_id');

         $todayDate = date("Y-m-d");

          $listQuery = " select m.*,p.name from milk_daily_quallityreport as m
                        left join product as p ON p.product_id = m.animal_type
                        where m.company_branch_id = $company_id
                        order by m.date DESC 
                        limit 20";
          $listResult =  Yii::app()->db->createCommand($listQuery)->queryAll();
          $qualityreportObject = MilkDailyQuallityreport::model()->findByAttributes(array('company_branch_id'=>$company_id ,'date'=>$todayDate ,'animal_type'=>1));
           if($qualityreportObject){
               $qualityreport = array();
               $qualityreport['protein'] =$qualityreportObject['protein'];
               $qualityreport['lactose'] =$qualityreportObject['lactose'];
               $qualityreport['fat'] = $qualityreportObject['fat'];
               $qualityreport['salt'] =$qualityreportObject['salt'];
               $qualityreport['adulterants'] =$qualityreportObject['adulterants'];
               $qualityreport['antiboitics'] =$qualityreportObject['antiboitics'];
               $qualityreport['company_id'] = $company_id ;
               $qualityreport['date'] = $todayDate ;
           }else{
               $qualityreport = array();
               $qualityreport['protein'] ='';
               $qualityreport['lactose'] ='';
               $qualityreport['fat'] ='';
               $qualityreport['salt'] ='';
               $qualityreport['adulterants'] ='';
               $qualityreport['antiboitics'] ='';
               $qualityreport['company_id'] = $company_id ;
               $qualityreport['date'] = $todayDate ;
           }

            $this->render('QualityReport',array(
                   'qualityreport'=>json_encode($qualityreport),
                   'listResult'=>json_encode($listResult),
                   'todayDate'=>$todayDate,
                'productList'=>str_replace("'","&#39;",productData::getproductList($page =false) ) ,
            ));
	}

	public function actionsaveQuanlityReport(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $company_id = Yii::app()->user->getState('company_branch_id');
        $animal_type = $data['animal_type'];
        $todayDate = $data['todayDate'];
        $data = $data['qualityreport'];

        $qualityreportObject = MilkDailyQuallityreport::model()->findByAttributes(array('company_branch_id'=>$company_id ,'date'=>$todayDate ,'animal_type'=>$animal_type));
          if($qualityreportObject){
              $qualityreportObject->protein =$data['protein'];
              $qualityreportObject->lactose =$data['lactose'];
              $qualityreportObject->fat =$data['fat'];
              $qualityreportObject->salt =$data['salt'];
              $qualityreportObject->adulterants =$data['adulterants'];
              $qualityreportObject->antiboitics =$data['antiboitics'];
              $qualityreportObject->animal_type =$animal_type;
              $qualityreportObject->save();
              $company_id = Yii::app()->user->getState('company_branch_id');
              $qualityreportObject->date =$todayDate ;
              $listQuery = " select m.*,p.name from milk_daily_quallityreport as m
                        left join product as p ON p.product_id = m.animal_type
                        where m.company_branch_id = $company_id
                        order by m.date DESC 
                        limit 20 ";
              $listResult =  Yii::app()->db->createCommand($listQuery)->queryAll();

          }else{
              $qualityreportObject = new  MilkDailyQuallityreport();
              $qualityreportObject->protein =$data['protein'];
              $qualityreportObject->lactose =$data['lactose'];
              $qualityreportObject->fat =$data['fat'];
              $qualityreportObject->salt =$data['salt'];
              $qualityreportObject->adulterants =$data['adulterants'];
              $qualityreportObject->antiboitics =$data['antiboitics'];
              $qualityreportObject->company_branch_id =$company_id;
              $qualityreportObject->animal_type =$animal_type;
              $qualityreportObject->date =$todayDate ;
               if($qualityreportObject->save()){
                   $company_id = Yii::app()->user->getState('company_branch_id');

                   $todayDate = date("Y-m-d");

                   $listQuery = " select m.*,p.name from milk_daily_quallityreport as m
                        left join product as p ON p.product_id = m.animal_type
                        where m.company_branch_id = $company_id
                        order by m.date DESC 
                        limit 20  ";
                   $listResult =  Yii::app()->db->createCommand($listQuery)->queryAll();


               }else{
                   var_dump($qualityreportObject->getErrors());
               }
          }


        echo json_encode($listResult);

    }
	public function actionsaveQuanlityReport_selectData(){


        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $company_id = Yii::app()->user->getState('company_branch_id');
        $animal_type = $data['animal_type'];
        $todayDate = $data['todayDate'];


        $qualityreportObject = MilkDailyQuallityreport::model()->findByAttributes(array('company_branch_id'=>$company_id ,'date'=>$todayDate ,'animal_type'=>$animal_type));


        if($qualityreportObject){
            $qualityreport = array();
            $qualityreport['protein'] =$qualityreportObject['protein'];
            $qualityreport['lactose'] =$qualityreportObject['lactose'];
            $qualityreport['fat'] = $qualityreportObject['fat'];
            $qualityreport['salt'] =$qualityreportObject['salt'];
            $qualityreport['adulterants'] =$qualityreportObject['adulterants'];
            $qualityreport['antiboitics'] =$qualityreportObject['antiboitics'];
            $qualityreport['company_id'] = $company_id ;
            $qualityreport['date'] = $todayDate ;
        }else{
            $qualityreport = array();
            $qualityreport['protein'] ='';
            $qualityreport['lactose'] ='';
            $qualityreport['fat'] ='';
            $qualityreport['salt'] ='';
            $qualityreport['adulterants'] ='';
            $qualityreport['antiboitics'] ='';
            $qualityreport['company_id'] = $company_id ;
            $qualityreport['date'] = $todayDate ;
        }
        echo json_encode($qualityreport);

    }
}
