<?php

class SiteController extends Controller
{
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page'=>array(
                'class'=>'CViewAction',
            ),
        );
    }

    public function actionDeleteRiderStock(){

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT r.* , cb.name as company_branch_name from rider as r
               LEFT JOIN company_branch as cb ON cb.company_branch_id = r.company_branch_id
               where r.company_branch_id = $company_id
                order by r.fullname ASC ";
        $queryResult =  Yiis::app()->db->createCommand($query)->queryAll();

        $riderList=riderData::getRiderList();
        foreach($queryResult as $value){
            $rider_id = $value['rider_id'];
            RiderDailyStock::model()->deleteallbyattributes(array('delivery_id'=>$rider_id));

        }
    }
    public function actionDeleteDelivery(){

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT * FROM `delivery` WHERE company_branch_id = 6 ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        foreach($queryResult as $value){
             $delivery_id = $value['delivery_id'];


            DeliveryDetail::model()->deleteallbyattributes(array('delivery_id'=>$delivery_id));
             $object=Delivery::model()->findByPk(intval($delivery_id));

             if($object->delete()){

             }else{
                 echo $delivery_id;
                  die();
             }


        }
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'

        $user_id = Yii::app()->user->getState('user_id');

        $allow_widget = dashbord_data::dashboard_widget_user($user_id);


        if($user_id==46){
            $this->redirect(Yii::app()->baseUrl."/cattleProduction/Production");
        }




        if(Yii::app()->user->getId()!=null) {
           $todayDate = date('Y-m-d');
           $company_id = Yii::app()->user->getState('company_branch_id');

             $days_ago_10 = date('Y-m-d', strtotime('-11 days', strtotime($todayDate)));

             $query_count_days_ago = "Select count(*) as totalResult from client as c
                       where date(c.created_at) > '$days_ago_10' and c.company_branch_id = $company_id";

              $fetch_count_days_ago = Yii::app()->db->createCommand($query_count_days_ago)->queryAll();
              $result_count_days_ago = $fetch_count_days_ago[0]['totalResult'];

            $companyObject = Company::model()->findByPk(intval($company_id));

            $query_total = "select count(*) totalCount from complain as c
                   where c.company_branch_id =$company_id and 
                         c.status_id in (1,2) ";

            $result_unResolved = Yii::app()->db->createCommand($query_total)->queryAll();
            $total_Result = $result_unResolved[0]['totalCount'];

            $query_Resolved = "select 
                               count(*) totalCount from complain as c
                               where c.company_branch_id = $company_id 
                               and c.status_id = 1";

            $result_Resolved = Yii::app()->db->createCommand($query_Resolved)->queryAll();
            $total_Resolved = $result_Resolved[0]['totalCount'];

            $query_totalCumtomer = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id";

            $fetch_totalCumtomer = Yii::app()->db->createCommand($query_totalCumtomer)->queryAll();

             $totalCustomer  =  ($fetch_totalCumtomer[0]['total_Client']);

            $query_ActiveCumtomer = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 1";

            $fetch_ActiveCumtomer = Yii::app()->db->createCommand($query_ActiveCumtomer)->queryAll();

             $totalActiveCustomer  =  ($fetch_ActiveCumtomer[0]['total_Client']);

            $query_onlineCumtomer = "Select count(*) as total_Client from client as c
                 where c.company_branch_id = $company_id and c.is_active = 1 and c.LastTime_login != '0000-00-00 00:00:00'";

            $fetch_onlineCumtomer = Yii::app()->db->createCommand($query_onlineCumtomer)->queryAll();
           $totalOnineCustomer  =  ($fetch_onlineCumtomer[0]['total_Client']);
            $result = array();
            $result['total'] = $total_Result;
            $result['unResolved'] = $total_Resolved;
            $result['totalCustomer'] = $totalCustomer;
            $result['totalActiveCustomer'] = $totalActiveCustomer;
            $result['totalOnineCustomer'] = $totalOnineCustomer;
            $result['result_count_days_ago'] = $result_count_days_ago;
            $result['allow_widget'] = $allow_widget;

            $this->render('index',
                array(
                    'data' =>$result
                )
            );
        }
        else{
            $this->redirect(array('/site/login'));
        }
    }
     public function actiongetNewcustomer(){


         $company_id = Yii::app()->user->getState('company_branch_id');
         $todayDate = date('Y-m-d');
         $days_ago_10 = date('Y-m-d', strtotime('-11 days', strtotime($todayDate)));

         $query = "Select  c.client_id , c.created_at , c.fullname , c.cell_no_1 ,z.name from client as c
                        left join zone as z ON z.zone_id = c.zone_id
                        where date(c.created_at) > '$days_ago_10' and c.company_branch_id =$company_id
                         order by c.created_at DESC";

         $query = "Select c.client_id , c.created_at , c.fullname , c.cell_no_1 ,z.name , c.view_by_admin ,c.login_form  from client as c
                        left join zone as z ON z.zone_id = c.zone_id
                        where c.company_branch_id =$company_id 
                          order by c.view_by_admin  DESC ,c.created_at  DESC
                           limit 5";
         $query_Result= Yii::app()->db->createCommand($query)->queryAll();

         $query_laptopsum = "Select IFNULL(COUNT(c.login_form) ,0) as sumlaptop from client as c
                        left join zone as z ON z.zone_id = c.zone_id
                        where c.company_branch_id ='$company_id' and c.login_form = 1 and c.view_by_admin = 1";
         $query_Result_laptopsum= Yii::app()->db->createCommand($query_laptopsum)->queryAll();
           $total_laptopsum = $query_Result_laptopsum[0]['sumlaptop'];


         $query_mobilesum = "Select IFNULL(count(c.login_form),0) as summobile from client as c
                        left join zone as z ON z.zone_id = c.zone_id
                        where c.company_branch_id ='$company_id' and c.login_form = 2 and c.view_by_admin = 1";
         $query_Result_mobilesum= Yii::app()->db->createCommand($query_mobilesum)->queryAll();
          $total_mobilesum = $query_Result_mobilesum[0]['summobile'];



         $m='08';
         $monthsName = array (1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');

           $finalResult = array();
         foreach($query_Result as $value){
            $result = array();
             $result['client_id'] = $value['client_id'];
             $result['created_at'] = $value['created_at'];
             $result['fullname'] = $value['fullname'];
             $result['cell_no_1'] = $value['cell_no_1'];
             $result['name'] = $value['name'];
             $result['view_by_admin'] = $value['view_by_admin'];
             $result['login_form'] = $value['login_form'];
           $Days = date('d', strtotime($value['created_at']));
             $m= intval(date('m', strtotime($value['created_at'])));
             $monthsName = array (1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
                $getMonthName = $monthsName[$m];
             $result['date'] =    $getMonthName." ".$Days;
             $finalResult[] = $result;
         }
           $resultObject = array();
          $resultObject['customerList'] = $finalResult ;
          $resultObject['total_laptopsum'] = $total_laptopsum ;
          $resultObject['total_mobilesum'] = $total_mobilesum ;
         echo json_encode($resultObject) ;
     }
     public function actiongetNewSchedule(){

         $company_id = Yii::app()->user->getState('company_branch_id');
         $todayDate = date('Y-m-d');
         $days_ago_10 = date('Y-m-d', strtotime('-11 days', strtotime($todayDate)));
         $query = "Select c.client_id , cpf.orderStartDate as created_at ,c.fullname , c.cell_no_1 ,z.name , c.company_branch_id from client_product_frequency as cpf
                    left join client as c ON c.client_id = cpf.client_id and c.company_branch_id = $company_id
                    Right join zone as z ON z.zone_id = c.zone_id
                    where c.company_branch_id = $company_id
                    order by cpf.orderStartDate DESC 
                    limit 5";

         $query = "Select c.client_id,cs.date as created_at ,c.fullname ,c.cell_no_1 ,z.name ,cs.change_form , cs.admin_view from change_scheduler_record cs
                left join client as c ON c.client_id = cs.client_id
                left join zone as z ON z.zone_id =c.zone_id
                where c.company_branch_id='$company_id'
                order by cs.admin_view DESC ,cs.date DESC
                limit 5 ";
         $query_Result= Yii::app()->db->createCommand($query)->queryAll();
         $m='08';
         $monthsName = array (1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
         $finalResult = array();
         foreach($query_Result as $value){
             $result = array();
             $result['client_id'] = $value['client_id'];
             $result['created_at'] = $value['created_at'];
             $result['fullname'] = $value['fullname'];
             $result['cell_no_1'] = $value['cell_no_1'];
             $result['name'] = $value['name'];
             $result['change_form'] = $value['change_form'];
             $result['admin_view'] = $value['admin_view'];
             $Days = date('d', strtotime($value['created_at']));
              $m= intval(date('m', strtotime($value['created_at'])));
             $monthsName = array (1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
             $getMonthName = $monthsName[$m];
             $result['date'] =    $getMonthName." ".$Days;
             $finalResult[] = $result;
         }
          $resultObject = array();
          $resultObject['finalResult'] = $finalResult;


         $query_laptopsum = "Select IFNULL(COUNT(*) ,0) as sumlaptop from change_scheduler_record as c
                       
                        where c.company_id ='$company_id' and c.change_form = 1 and c.admin_view = 1";
         $query_Result_laptopsum= Yii::app()->db->createCommand($query_laptopsum)->queryAll();
         $total_laptopsum = $query_Result_laptopsum[0]['sumlaptop'];


         $query_mobilesum = "Select IFNULL(count(*),0) as summobile from change_scheduler_record as c
                 
                        where c.company_id ='$company_id' and c.change_form = 2 and c.admin_view = 1";
         $query_Result_mobilesum= Yii::app()->db->createCommand($query_mobilesum)->queryAll();
         $total_mobilesum = $query_Result_mobilesum[0]['summobile'];

         $resultObject['total_laptopsum'] = $total_laptopsum ;
         $resultObject['total_mobilesum'] = $total_mobilesum ;
         echo json_encode($resultObject) ;
     }
     public function actiongetMonthWiseNewCustomer(){
         $company_id = Yii::app()->user->getState('company_branch_id');

         $query="SELECT p.product_id ,p.name   from   product as p 
                where p.company_branch_id =$company_id and bottle = 0 ";
         $productList =  Yii::app()->db->createCommand($query)->queryAll();


          $todayMonth = intval(date('m'));
           $selectedMOnth = date("Y-m-d", strtotime("-5 month"));
                $finalResult = array();
            $oneMontthRecored = array();
         array_push($oneMontthRecored, 'Cutomers');
         array_push($oneMontthRecored, 'New Customers');

         foreach($productList as $value){

             $product_name = $value['name'];
             $product_product_id = $value['product_id'];

           //  array_push($oneMontthRecored, $product_name);
         }


         $finalResult[] = $oneMontthRecored ;
           for($start = 1;$start<6;$start++){

                $oneMontthRecored = array();
                $time = strtotime($selectedMOnth);

                $selectedMOnth = date("Y-m-d", strtotime("+1 month", $time));



                $company_id = Yii::app()->user->getState('company_branch_id');
                //   echo  $year =  date("Y", strtotime($selectedMOnth));
                $year =  date("Y-m", strtotime($selectedMOnth));
                $selectMonthYear = $year."-%%";

                $query = "  select count(*) as total  from client as c
                where (c.new_create_date) like '$selectMonthYear' and c.company_branch_id = $company_id ";
                $result = Yii::app()->db->createCommand($query)->queryAll();
                $totalRegisteredCustomer = ($result[0]['total']);
                $monthName = date("M", strtotime("+1 month", $time));
                //  $oneMontthRecored['monthName']=$monthName ;
                // $oneMontthRecored['totalRegistered']=$totalRegisteredCustomer ;
                array_push($oneMontthRecored, $monthName);
                array_push($oneMontthRecored, intval($totalRegisteredCustomer));

               foreach($productList as $value){

                   $product_name = $value['name'];
                   $product_product_id = $value['product_id'];

                    $queryquatity = "SELECT sum(d.quantity) as quantity FROM delivery_detail as d
                          where d.date like '$selectMonthYear'  and d.product_id ='$product_product_id'";


                    $QueryResult = Yii::app()->db->createCommand($queryquatity)->queryAll();

                    $quantity = $QueryResult[0]['quantity'];


                  // array_push($oneMontthRecored, intval($quantity));
               }

                $finalResult[] = $oneMontthRecored ;
                //  echo $selectedMOnth ;
                //  $month
           }

           
           echo json_encode($finalResult);

     }

     public function actiongetMonthWiseNewCustomer_end_month(){
            $company_id = Yii::app()->user->getState('company_branch_id');
            $todayMonth = intval(date('m'));
            $selectedMOnth = date("Y-m-d", strtotime("-5 month"));



            $finalResult = array();
            $oneMontthRecored = array();
            array_push($oneMontthRecored, 'Cutomers');
            array_push($oneMontthRecored, 'New Customers');
            $finalResult[] = $oneMontthRecored ;
            for($start = 1;$start<6;$start++){
                $oneMontthRecored = array();
                $time = strtotime($selectedMOnth);
                $selectedMOnth = date("Y-m-d", strtotime("+1 month", $time));
                $company_id = Yii::app()->user->getState('company_branch_id');
                $year =  date("Y-m", strtotime($selectedMOnth));
                $selectMonthYear = $year."-31";


                $query = "  select count(*) as total  from client as c
                    where c.new_create_date<= '$selectMonthYear' 
                    and c.company_branch_id = $company_id  and c.is_active='1' ";

                $result = Yii::app()->db->createCommand($query)->queryAll();

                $totalRegisteredCustomer = ($result[0]['total']);

                $monthName = date("M", strtotime("+1 month", $time));


                array_push($oneMontthRecored, $monthName);
                array_push($oneMontthRecored, intval($totalRegisteredCustomer));

                $finalResult[] = $oneMontthRecored ;

           }

           echo json_encode($finalResult);

     }


    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        $error = Yii::app()->errorHandler->error;
        if( $error )
        {
            $this -> render( 'error', array( 'error' => $error ) );
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model=new ContactForm;
        if(isset($_POST['ContactForm']))
        {
            $model->attributes=$_POST['ContactForm'];
            if($model->validate())
            {
                $name='=?UTF-8?B?'.base64_encode($model->name).'?=';
                $subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
                $headers="From: $name <{$model->email}>\r\n".
                    "Reply-To: {$model->email}\r\n".
                    "MIME-Version: 1.0\r\n".
                    "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
                Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact',array('model'=>$model));
    }

    /**
     * Displays the login page
     */

    public function actionchangePassword(){

        $this->layout=false;
        $model= new LoginForm;

        $this->render('changePassword',array('model'=>$model));


    }

    public function actionconfirmPayment(){

        /* $post = file_get_contents("php://input");
         $data = CJSON::decode($post, TRUE);
         $status_code = $data['status_code'];
         $trans_ref_no = $data['trans_ref_no'];
         $order_id = $data['order_id'];
         $signature = $data['signature'];*/
        $dat =$_GET;
        $status_code = $dat['status_code'];
        $this->layout=false;
        if($status_code == 1){
            $this->render('conformPayment' ,
                array(

                    'data'=>$dat,
                ));
        }
    }
    public function actionconfirmJazzCashPayment(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $dat_get =$_GET;
        $data_post =$_POST;
        $this->layout=false;

        $this->render('confirmJazzCashPayment' ,
            array(

                'data'=>$data_post,
            ));

    }

    public function sendResponse($data)
    {
        echo  json_encode($data);
    }


    public function actionchangedPassword(){
        $this->layout=false ;
        $model = new LoginForm;
        if(isset($_POST['LoginForm'])){
            $userID = Yii::app()->user->getState('user_id');

            $query = "select * from user where user_id='$userID'" ;

            try{
                $user = Yii::app()->db->createCommand($query)->queryAll();

            }catch (Exception $e){}


            if($user){

                if(!empty($_POST['LoginForm']['newpassword'])){
                    $oldpassword = $_POST['LoginForm']['oldpassword'];
                    $newpassword = $_POST['LoginForm']['newpassword'];
                    $password = $user[0]['password'];
                    


                    if($password == $oldpassword){
                        $query2 = "Update user set password =". '"'.$newpassword. '"'." where user_id=".$userID;
                        try{
                            $change = Yii::app()->db->createCommand($query2)->queryAll();
                            Yii::app()->db->createCommand($query2)->queryAll();
                        }catch (Exception $e){

                        }

                        $this->redirect(array('site/logout'));

                    }else{
                        $model->addError('oldpassword',"Old password is wrong !");
                        $this->render('changePassword', array('model'=>$model));
                    }

                }else{
                    $model->addError('newpassword' , "New Password can't empty");
                    $this->render('changePassword', array('model'=>$model));
                }


                //  die($_POST['LoginForm']['oldpassword']);
            }else{
                Yii::app()->user->logout();
                $this->redirect(array('site/login'));
            }


        }
    }
    public function actiongetDeliveryStatus(){

        date_default_timezone_set("Asia/Karachi");
        $todayDate = Date('Y-m-d');
         $currentMonth = Date('Y-m').'-%%';


        $company_id = Yii::app()->user->getState('company_branch_id');

         $rider_query = "select r.rider_id , r.fullname from rider as r
            where r.company_branch_id = $company_id and r.is_active = 1";
         $rider_result = Yii::app()->db->createCommand($rider_query)->queryAll();
           $finalResult = array();
           $productList = Product::model()->findAllByAttributes(array('company_branch_id'=>$company_id ,'bottle'=>0));

          foreach($rider_result as $value){
               $rider_id = $value['rider_id'];
                $rider_name = $value['fullname'];

             foreach($productList as $product){
                  $product_id = $product['product_id'];
                   $oneProduct = array();
                   $oneProduct['rider_name'] = $rider_name ;

                $deliveryQery = "select IFNULL(sum(dd.amount) , 0) as totalAmount , IFNULL(sum(dd.quantity) ,0)  as totalQuantity from delivery as d
                   left join delivery_detail as dd ON d.delivery_id = dd.delivery_id
                   where d.rider_id =$rider_id and dd.product_id = '$product_id' and d.date = '$todayDate' ";

                  $dileryResult = Yii::app()->db->createCommand($deliveryQery)->queryAll();

                 $deliveryMonthQery = "select IFNULL(sum(dd.amount) , 0) as totalAmount , IFNULL(sum(dd.quantity) ,0)  as totalQuantity from delivery as d
                    left join delivery_detail as dd ON d.delivery_id = dd.delivery_id
                    where d.rider_id =$rider_id and dd.product_id = '$product_id' and d.date like '$currentMonth' ";

                   $dileryMonthResult = Yii::app()->db->createCommand($deliveryMonthQery)->queryAll();
                   $total_client_query = " Select  count(*)  as  totalClient  from rider_zone as rz
                        Right join client as c ON c.zone_id = rz.zone_id
                        where rz.rider_id = $rider_id  AND c.is_active = 1 ";

                    $total_cleint_result = Yii::app()->db->createCommand($total_client_query)->queryAll();

                    $oneProduct['product_name'] = $product['name'];
                    $oneProduct['totalQuantity'] = $dileryResult[0]['totalQuantity'];
                    $oneProduct['dileryMonthResult'] = $dileryMonthResult[0]['totalQuantity'];
                    $oneProduct['totalClient'] = $total_cleint_result[0]['totalClient'];
                    if($dileryMonthResult[0]['totalQuantity'] > 0){

                        $finalResult[] = $oneProduct ;
                    }


             }
          }

        echo json_encode($finalResult);
    }

    public function actiongetoutStandingBalnce(){
            $company_id = Yii::app()->user->getState('company_branch_id');
            $query = " select c.client_id from client as c
                where c.company_branch_id = '$company_id' ";
                $result = Yii::app()->db->createCommand($query)->queryAll();
            $clientListArry = array();

            foreach($result as $value){
             array_push($clientListArry , $value['client_id']);
            }
            $clientIdList=implode(",",$clientListArry);

            $query_balance = " select IFNULL(sum(c.partial_amount) , 0) as balanace from delivery as c
              where c.client_id in ($clientIdList)";


            $result_balnce = Yii::app()->db->createCommand($query_balance)->queryAll();
            $outstanding_balance =  $result_balnce[0]['balanace'];





            $queryDelivery ="select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                               where d.client_id in ($clientIdList) ";
            $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
            $totaldeliverySum = $deliveryResult[0]['deliverySum'];


             $queryDelivery ="Select sum(pm.amount_paid) as remainingAmount from payment_master as pm
            where pm.client_id in  ($clientIdList)";
            $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
            $totalRemaining = $deliveryResult[0]['remainingAmount'];
            $finalAmount =    $totaldeliverySum - $totalRemaining;







        $query_balance_clientList = "select IFNULL(sum(d.partial_amount) ,0) as balanace ,c.fullname  from delivery as d
                left join client as c ON c.client_id = d.client_id
                where d.client_id in ($clientIdList)
                group by c.client_id
                 order by sum(d.partial_amount) DESC  limit 5 ";

            $result_balnce_clientLIst = Yii::app()->db->createCommand($query_balance_clientList)->queryAll();

              $finalResult = array();
              $finalResult['outstanding_balance'] = $finalAmount;
              $finalResult['result_balnce_clientLIst'] = $result_balnce_clientLIst;

             echo json_encode($finalResult);

    }
    public  function actiongepaymentReciveToday(){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $todayDate = Date('Y-m-d');
        $query_onlinePayment = " select IFNULL(sum(pm.amount_paid) ,0) as totalOnline  from payment_master as pm
               where pm.payment_mode = 6 and pm.date = '$todayDate' and pm.company_branch_id = $company_id";
        $result_onlinePayment = Yii::app()->db->createCommand($query_onlinePayment)->queryAll();

        $total_OnlinePayment =  $result_onlinePayment[0]['totalOnline'];

        $query_cashPayment_for_app = " select IFNULL(sum(pm.amount_paid),0) as totalCsh  from payment_master as pm
               where pm.remarks = 1 and pm.payment_mode = 3 and  pm.date = '$todayDate' and pm.company_branch_id = $company_id";


        $result_cashPayment_fromApp = Yii::app()->db->createCommand($query_cashPayment_for_app)->queryAll();
         $total_cshPayment_form_app =  $result_cashPayment_fromApp[0]['totalCsh'];


        $query_chequePayment_for_app = " select IFNULL(sum(pm.amount_paid),0) as totalCsh  from payment_master as pm
               where pm.remarks = 1 and pm.payment_mode = 2 and  pm.date = '$todayDate' and pm.company_branch_id = $company_id";


        $result_chequePayment_fromApp = Yii::app()->db->createCommand($query_chequePayment_for_app)->queryAll();
        $total_chequePayment_from_app =  $result_chequePayment_fromApp[0]['totalCsh'];

              /* for Portal*/


        $query_cashPayment_for_portal = " select IFNULL(sum(pm.amount_paid),0) as totalCsh  from payment_master as pm
               where pm.remarks = 2 and pm.payment_mode = 3 and  pm.date = '$todayDate' and pm.company_branch_id = $company_id";


        $result_cashPayment_fromPortal = Yii::app()->db->createCommand($query_cashPayment_for_portal)->queryAll();
        $total_cshPayment_form_portal =  $result_cashPayment_fromPortal[0]['totalCsh'];




        $query_chequePayment_for_portal = " select IFNULL(sum(pm.amount_paid),0) as totalCsh  from payment_master as pm
               where pm.remarks = 2 and pm.payment_mode = 2 and  pm.date = '$todayDate' and pm.company_branch_id = $company_id";


        $result_chequePayment_fromPortral = Yii::app()->db->createCommand($query_chequePayment_for_portal)->queryAll();
        $total_chequePayment_from_portal =  $result_chequePayment_fromPortral[0]['totalCsh'];

         $finalResult = array();
        $finalResult['total_OnlinePayment'] = $total_OnlinePayment ;
        $finalResult['totalCash_from_app'] = $total_cshPayment_form_app ;
        $finalResult['totalcheque_from_app'] = $total_chequePayment_from_app ;
        $finalResult['total_cshPayment_form_portal'] = $total_cshPayment_form_portal ;
        $finalResult['total_chequePayment_from_portal'] = $total_chequePayment_from_portal ;
        echo json_encode($finalResult);

    }

    public function actionmobile_login(){
        $this->layout = false;
        $model=new LoginForm;

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login()){

                $this->redirect(Yii::app()->user->returnUrl);
            }

        }
        // display the login form
        $this->render('mobile_login',array('model'=>$model));
    }
    public function actionLogin()
    {
        $this->layout = false;
        $model=new LoginForm;

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login()){

                $this->redirect(Yii::app()->user->returnUrl);
            }

        }
        // display the login form
        $this->render('login',array('model'=>$model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        Yii::app()->session->clear();
        $this->redirect(Yii::app()->homeUrl);
    }

    public  function actionviewCustomer(){
        $post = file_get_contents("php://input");

        $client =  Client::model()->findByPk(intval($post));
        $client->view_by_admin = 0;
        $client->save();
        echo $client['login_form'] ;
    }

    public function actionget_daily_sale_graph(){

        //  echo $today_date= date("Y-m-d");
         $start_date  =  date('Y-m-d', strtotime(' -30 day'));
         $end_date  =  date('Y-m-d');



         echo  dashbord_graph_data::get_daily_stock($start_date ,$end_date);

       /* $day = date("d");
        $month = date("M");
        $m =date("m");
        $y =date("Y");
        $company_id = Yii::app()->user->getState('company_branch_id');
        $quantityObject = array();
        $amountObject = array();
        $oneObject_quantity = [$month, 'Quantity'];
        $oneObject_amount = [$month, 'Amount'];
        array_push($quantityObject ,$oneObject_quantity);
        array_push($amountObject ,$oneObject_amount);
         for($x=1 ;$x<=$day ;$x++){
             $todayDate =$y."-".$m."-".$x;
              $query = "SELECT SUM(dd.quantity) AS total_quantity ,SUM(dd.amount) as amount FROM delivery AS d
              LEFT JOIN delivery_detail AS dd ON dd.delivery_id = d.delivery_id
              WHERE d.DATE ='$todayDate' AND d.company_branch_id='$company_id'";

              $quantity = Yii::app()->db->createCommand($query)->queryAll();
              if(sizeof($quantity)>0){
                  $total_quantity = $quantity[0]['total_quantity'] ;
                  $amount = $quantity[0]['amount'] ;
                  $oneObject_quantity = [$todayDate, intval($total_quantity)];
                  $oneObject_amount = [$todayDate, intval($amount)];
                  array_push($quantityObject ,$oneObject_quantity);
                  array_push($amountObject ,$oneObject_amount);
              }
         }
         $result = array();
         $result['quantityObject'] = $quantityObject;
         $result['amountObject'] = $amountObject;
         echo json_encode($result);*/

    }

    public function actionget_new_Cutomer(){

        $start_date  =  date('Y-m-d', strtotime(' -30 day'));
        $end_date  =  date('Y-m-d');

        echo     dashbord_graph_data::get_new_Cutomer_data($start_date,$end_date);
    }
    public function actionget_year_sale_graph(){

        echo dashbord_graph_data::get_year_sale_graph_data();

     }

    public function actionview_manage_wiget_model_data_function(){
        $user_id = Yii::app()->user->getState('user_id');

        $query = "SELECT 
            l.dashboard_widget_list_id,
            l.dashboard_widget_list_name,
            u.dashboard_widget_user_id
            FROM dashboard_widget_list AS l
            LEFT JOIN dashboard_widget_user AS u 
                ON l.dashboard_widget_list_id =u.dashboard_widget_list_id 
                AND u.user_id ='$user_id' 
                order by  l.dashboard_widget_list_id";
        $result= Yii::app()->db->createCommand($query)->queryAll();
        $final_list =[];
        foreach ($result as $value){
            $one_object = [];
            $one_object['dashboard_widget_list_id'] = $value['dashboard_widget_list_id'];
            $one_object['dashboard_widget_list_name'] = $value['dashboard_widget_list_name'];
            $one_object['selected'] =false;
            if($value['dashboard_widget_user_id']){
                $one_object['selected'] =true;
            }
            $final_list[] = $one_object;
        }

        echo  json_encode($final_list);
    }

    public function actionview_manage_wiget_model_update_function(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $user_id = Yii::app()->user->getState('user_id');
        DashboardWidgetUser::model()->deleteAllByAttributes(
            [
                 'user_id'=>$user_id
            ]
        );
        foreach ($data as $value){
            $dashboard_widget_list_id = $value['dashboard_widget_list_id'];

             if($value['selected']){
                 $object = New DashboardWidgetUser();
                 $object->user_id = $user_id;
                 $object->dashboard_widget_list_id = $dashboard_widget_list_id;
                 $object->save();
             }
        }

    }



}