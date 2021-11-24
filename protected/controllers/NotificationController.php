<?php

class NotificationController extends Controller
{
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('get_new_complain_notification','view'),
                'users'=>array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('get_new_complain_notification','update'),
                'users'=>array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('get_new_complain_notification','delete'),
                'users'=>array('admin'),
            ),

        );
    }
    public function actiontotal_new_customer(){

        $get_data = $_GET;
        $type = $get_data['type'];
        $company_id = Yii::app()->user->getState('company_branch_id');
        if($type==1){
            $query = " SELECT count(*) FROM client AS c
                WHERE c.company_branch_id ='$company_id'
                AND c.view_by_admin =0 ";

        }else{
            $query ="SELECT count(*) from  complain AS c
              where c.company_branch_id='$company_id' AND c.view_by_admin =0";
        }

        $total = Yii::app()->db->createCommand($query)->queryScalar();
        if($total>0){
            echo  $total;
        }else{
            echo  '';
        }


    }
    public function actionget_new_complain_notification(){
        $get_data = $_GET;

        $page =$get_data['page'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $user_id = Yii::app()->user->getState('user_id');

        $user_object = User::model()->findByPk($user_id);

        if($user_object['supper_admin_user']==1){
            $query = "UPDATE complain
                SET view_by_admin = '1'
                WHERE view_by_admin = 0 
                and 	company_branch_id = '$company_id' ";
            Yii::app()->db->createCommand($query)->query();
        }



        $query = "SELECT com.* ,
                   cl.fullname , 
                  s.status_name  ,
                  comT.name from  complain as com
                LEFT JOIN  client as cl ON cl.client_id = com.client_id
                LEFT JOIN status as s ON s.status_id = com.status_id
                LEFT JOIN complain_type as comT ON comT.complain_type_id = com.complain_type_id 
                where com.type = '1' 
                and com.company_branch_id = 1 
                order by com.complain_id DESC
                LIMIT 15 OFFSET $page ";
        $customer_list = Yii::app()->db->createCommand($query)->queryAll();



        $notification ='';
        foreach ($customer_list as $value){

            $notification .= '<li style="">
                <div class="col-lg-12" style="border-bottom: 1px dotted #008B8B;padding: 5px;background-color: #F5FFFA">
                <a href="">
                    <div class="col-lg-2 col-sm-2 col-2 text-center">
                         <i class="fa fa-envelope" style="font-size:20px;color:#5F9EA0"></i>
                    </div>
                    <div class="col-lg-9 col-sm-9 col-9">
                        <strong class="text-info">'.$value['fullname'].'</strong>
                        <br>
                        <small class="text-warning">'.$value['name'].'</small>
                         <br>
                        <small class="text-warning">'.$value['created_on'].'</small>
                    </div>
                    </a>
                </div>
            </li>';
        }
        if(sizeof($customer_list)>0){
            $notification .=' <li style="" id="view_more_id">
                                    <div class="col-lg-12" style="border-bottom: 1px dotted #008B8B;padding: 5px;background-color: #F5FFFA">

                                            <div class="col-lg-12 col-sm-12 col-12 text-center">
                                                <strong class="text-info">
                                                    <a onclick="view_more_complain(1)" href="#">View More</a>
                                                </strong>
                                            </div>
                                    </div>
                            </li>';
        }


        $result['customer_list'] = $notification;

        echo $notification;
    }

    public function actionget_new_cutomer_notification(){

        $get_data = $_GET;
        $company_id = Yii::app()->user->getState('company_branch_id');

        $user_id = Yii::app()->user->getState('user_id');

        $user_object = User::model()->findByPk($user_id);

        if($user_object['supper_admin_user']==1){
            $query = "UPDATE `client` SET 
            `view_by_admin` = '1'
            WHERE view_by_admin = 0 
            and 	company_branch_id = '$company_id' ";
            Yii::app()->db->createCommand($query)->query();
        }



        $result =      dashbord_graph_data::get_new_customer_notification($get_data);
        $customer_list = $result['customer_list'];

        $notification ='';
        foreach ($customer_list as $value){

            $notification .= '<li style="">
                <div class="col-lg-12" style="border-bottom: 1px dotted #008B8B;padding: 5px;background-color: #F5FFFA">
                <a href="">
                    <div class="col-lg-2 col-sm-2 col-2 text-center">
                         <i class="fa fa-user" style="font-size:24px;color:#5F9EA0"></i>
                    </div>
                    <div class="col-lg-9 col-sm-9 col-9">
                        <strong class="text-info">'.$value['fullname'].'</strong>
                        <br>
                        <small class="text-warning">'.$value['created_at'].'</small>
                    </div>
                    </a>
                </div>
            </li>';
        }
        if(sizeof($customer_list)>0){
            $notification .=' <li style="" id="view_more_id">
                                    <div class="col-lg-12" style="border-bottom: 1px dotted #008B8B;padding: 5px;background-color: #F5FFFA">

                                            <div class="col-lg-12 col-sm-12 col-12 text-center">
                                                <strong class="text-info">
                                                    <a onclick="view_more()" href="#">View More</a>
                                                </strong>
                                            </div>
                                    </div>
                            </li>';
        }


        $result['customer_list'] = $notification;

        echo $notification;
    }
}