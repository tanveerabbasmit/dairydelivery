<?php

class dashboardController extends Controller
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
	

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */

	public function actionsaleGraph(){
               $flage =  $_GET;
               $id = $flage['id'] ;
                  $post = date("M-Y");
                  $lableObject = array();
                  strtoupper(date('M', strtotime($post. "-3 Month")));
                  $lableObject[] = strtoupper(date('M', strtotime($post. "-5 Month")));
                  $lableObject[] = strtoupper(date('M', strtotime($post. "-4 Month")));
                  $lableObject[] = strtoupper(date('M', strtotime($post. "-3 Month")));
                  $lableObject[] = strtoupper(date('M', strtotime($post. "-2 Month")));
                  $lableObject[] = strtoupper(date('M', strtotime($post. "-1 Month")));
                  $lableObject[] = strtoupper(date('M', strtotime($post. "-0 Month")));
               if($id == 1){
                 $mainHeadig = "Sale Graph Month And Total amount";
                   $data = dashbord_data::getCustomerData();
                }else{
                   $mainHeadig = "Sale Graph Month And Total Quantity";
                   $data = dashbord_data::getCustomerData2();
               }





        $this->render('saleGraph' , array(
             'lable'=>json_encode($lableObject),

             'customerObject'=>$data,
             'mainHeadig'=>$mainHeadig ,
             'id'=>$id

        ));
    }
    public  function actionRIderWiseDelivery(){
       $colorObject = ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"];

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT r.* , cb.name as company_branch_name from rider as r
               LEFT JOIN company_branch as cb ON cb.company_branch_id = r.company_branch_id
               where r.company_branch_id = $company_id
                order by r.fullname ASC ";
        $queryResultRiderList =  Yii::app()->db->createCommand($query)->queryAll();
              $labels = array();
            $backgroundColor = array();
             $deliveryData = array();
          foreach($queryResultRiderList as $key=>$value){
               $indexIndex = $key%5 ;
                $rider_id =$value['rider_id'];
              array_push($labels,$value['fullname']);
              array_push($backgroundColor,$colorObject[$indexIndex]);

              $query="select count(*) from delivery as d
                    where d.rider_id = $rider_id";
              $queryResultRiderList =  Yii::app()->db->createCommand($query)->queryscalar();
              array_push($deliveryData,$queryResultRiderList);

          }


        $this->render('RIderWiseDelivery' , array(

            'customerObject'=>json_encode($deliveryData),
            'lable'=>json_encode($labels),
            'backgroundColor'=>json_encode($backgroundColor),
        ));
    }

    public  function actionOutStandingBalance(){
       $colorObject = ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"];

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT c.client_id ,c.fullname   FROM delivery as d
                 left join client as c ON c.client_id = d.client_id
                 where d.company_branch_id = '$company_id'
                  group by d.client_id ";
        $queryResultRiderList =  Yii::app()->db->createCommand($query)->queryAll();

              $labels = array();
            $backgroundColor = array();
             $deliveryData = array();
          foreach($queryResultRiderList as $key=>$value){
               $indexIndex = $key%5 ;
                $rider_id =$value['client_id'];
              array_push($labels,$value['fullname']);
              array_push($backgroundColor,$colorObject[$indexIndex]);


              array_push($deliveryData,APIData::calculateFinalBalance($rider_id));



          }

          $arrayLength = sizeof($deliveryData)*10;
         if($arrayLength <1050){
             $arrayLength = 1050 ;
         }




        $this->render('OutstandingBalance' , array(

            'customerObject'=>json_encode($deliveryData),
            'lable'=>json_encode($labels),
            'backgroundColor'=>json_encode($backgroundColor),
            'arrayLength'=>$arrayLength,
        ));
    }
    public function actionriderZone(){

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="select * from rider as r
                where r.company_branch_id = $company_id";
        $queryResultRiderList =  Yii::app()->db->createCommand($query)->queryAll();

            $finalresult = array();
          foreach($queryResultRiderList as $value){

              $rider_id = $value['rider_id'];
              $fullname =  $value['fullname'];
              $queryZone = "select z.name  ,z.zone_id  from rider_zone as rz
                left join zone as z ON z.zone_id = rz.zone_id
                where rz.rider_id ='$rider_id'";
              $zoneList =  Yii::app()->db->createCommand($queryZone)->queryAll();

              foreach ($zoneList as $zoneValue){
                  $zoneName = $zoneValue['name'];
                  $zone_id = $zoneValue['zone_id'];
                  $oneObject = array();
                  array_push($oneObject,$zoneName);
                  array_push($oneObject,$fullname);
                  array_push($oneObject ," ");
                   array_push($finalresult ,$oneObject);

                  $queryClient = "select c.client_id , c.fullname from client as c 
                      where c.zone_id = '$zone_id'";
                  $ClientList =  Yii::app()->db->createCommand($queryClient)->queryAll();


                   foreach($ClientList as $clientValue){
                       $client_name = $clientValue['client_id'].":".$clientValue['fullname'];
                       $oneObject = array();
                       array_push($oneObject,$client_name);
                       array_push($oneObject,$zoneName);
                       array_push($oneObject ," ");
                       array_push($finalresult ,$oneObject);

                   }


              }


          }



        $this->render('riderZone' , array(
             'customerObject'=>json_encode($finalresult),
        ));
    }

    public function actiongetCustomerData(){
         $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
       echo  dashbord_data::getProductData($data);


    }


    public function actionReconcileStock_graph(){


        $post = date("M-Y");
        $lableObject = array();
        strtoupper(date('M', strtotime($post. "-3 Month")));
        $lableObject[] = strtoupper(date('F', strtotime($post. "-3 Month")));
        $lableObject[] = strtoupper(date('F', strtotime($post. "-2 Month")));
        $lableObject[] = strtoupper(date('F', strtotime($post. "-1 Month")));
        $lableObject[] = strtoupper(date('F', strtotime($post. "-0 Month")));

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT r.*  from rider as r
                   where r.company_branch_id = $company_id
                order by r.fullname ASC ";
        $queryResult_riderList =  Yii::app()->db->createCommand($query)->queryAll();
         foreach($queryResult_riderList as $value){
             echo $value['fullname'];
              die();
         }

        die();

        $this->render('ReconcileStock_graph',array(
            'lable'=>json_encode($lableObject),
            'customerObject'=>dashbord_data::getCustomerData(),
        ));
    }



}
