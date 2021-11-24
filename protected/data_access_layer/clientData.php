<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class clientData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

    public static function getOneCutomerObject($client_id){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT c.* ,cb.name as company_branch_name , z.name as zone_name  from client as c
                   LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
                   LEFT JOIN zone  as z ON z.zone_id = c.zone_id
                   where  c.company_branch_id ='$company_id' and c.client_id = '$client_id'";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
        return ($queryResult[0]);

    }
    public static function getClientList($page ,$zone , $status,$sort_object=false){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $get_dat =   $_GET;
        $new_customer = false;
        if(isset($get_dat['new_customer'])){
            $new_customer = true;


        }

        $offset = 0 ;
        if($page){
            $page = $page -1 ;
            $offset = $page * 10;
        }
        $query="SELECT c.* ,u.user_name
             ,cb.name as company_branch_name ,
              z.name as zone_name  from client as c
                   LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
                   left join user as u on u.user_id = c.user_id
                   LEFT JOIN zone  as z ON z.zone_id = c.zone_id
                   where  c.company_branch_id =$company_id ";
        if($zone != 0){
            $query .= " and c.zone_id =$zone " ;
        }
        if($status == 1){
            $query .= " and c.is_active =1 and c.client_type = 1" ;
        }
        if($status == 2){
            $query .= " and c.is_active =0 and c.client_type = 1 " ;
        }
        if($status == 3){
            $query .= " and c.is_active =1 and c.client_type = 2" ;
        }
        if($status == 4){
            $query .= " and c.is_active =0 and c.client_type = 2 " ;
        }


        if($sort_object){

            $sort_typ = sort_report::sort_type($sort_object['fullname']['sort_type']);

            $query   .=" order by  c.fullname $sort_typ ";
        }else{
            if($new_customer){

                $query   .=" order by  c.created_at DESC ";
            }else{

                $query   .=" order by  c.is_active DESC ,c.LastTime_login DESC ";

            }
        }
        $query   .= " LIMIT 10 OFFSET $offset ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();




        $count_query ="SELECT count(*) as totalCount  from client as c
                   LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
                   LEFT JOIN zone  as z ON z.zone_id = c.zone_id
                   where  c.company_branch_id =$company_id ";
        if($zone != 0){
            $count_query .= " and c.zone_id =$zone " ;
        }
        if($status == 1){
            $count_query .= " and c.is_active =1 and c.client_type = 1" ;
        }
        if($status == 2){
            $count_query .= " and c.is_active =0 and c.client_type = 1 " ;
        }
        if($status == 3){
            $count_query .= " and c.is_active =1 and c.client_type = 2" ;
        }
        if($status == 4){
            $count_query .= " and c.is_active =0 and c.client_type = 2 " ;
        }

        $count_Result =  Yii::app()->db->createCommand($count_query)->queryAll();
        $totalCount = $count_Result[0]['totalCount'];
        $finalResult = array();
        $finalResult['clientList'] = $queryResult ;
        $finalResult['count'] = $totalCount ;


        return json_encode($finalResult);
    }
    public static function getClientList_new_sign_up($page ,$zone , $status){
        $company_id = Yii::app()->user->getState('company_branch_id');



        $get_dat =   $_GET;
        $new_customer = false;
        if(isset($get_dat['new_customer'])){
            $new_customer = true;


        }

        $offset = 0 ;
        if($page){
            $page = $page -1 ;
            $offset = $page * 10;
        }
        $query="SELECT c.* ,cb.name as company_branch_name , z.name as zone_name  from client as c
                   LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
                   LEFT JOIN zone  as z ON z.zone_id = c.zone_id
                   where  c.company_branch_id =$company_id and c.is_approved=0
                   ";
        if($zone != 0){
            $query .= " and c.zone_id =$zone " ;
        }
        if($status == 1){
            $query .= " and c.is_active =1 and c.client_type = 1" ;
        }
        if($status == 2){
            $query .= " and c.is_active =0 and c.client_type = 1 " ;
        }
        if($status == 3){
            $query .= " and c.is_active =1 and c.client_type = 2" ;
        }
        if($status == 4){
            $query .= " and c.is_active =0 and c.client_type = 2 " ;
        }
        if($new_customer){

            $query   .=" order by  c.created_at DESC  
                     LIMIT 10 OFFSET $offset ";
        }else{

            $query   .=" order by  c.is_active DESC ,c.LastTime_login DESC 
                     LIMIT 10 OFFSET $offset ";

        }

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();




        $count_query ="SELECT count(*) as totalCount  from client as c
                   LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
                   LEFT JOIN zone  as z ON z.zone_id = c.zone_id
                   where  c.company_branch_id =$company_id ";
        if($zone != 0){
            $count_query .= " and c.zone_id =$zone " ;
        }
        if($status == 1){
            $count_query .= " and c.is_active =1 and c.client_type = 1" ;
        }
        if($status == 2){
            $count_query .= " and c.is_active =0 and c.client_type = 1 " ;
        }
        if($status == 3){
            $count_query .= " and c.is_active =1 and c.client_type = 2" ;
        }
        if($status == 4){
            $count_query .= " and c.is_active =0 and c.client_type = 2 " ;
        }

        $count_Result =  Yii::app()->db->createCommand($count_query)->queryAll();
        $totalCount = $count_Result[0]['totalCount'];
        $finalResult = array();
        $finalResult['clientList'] = $queryResult ;
        $finalResult['count'] = $totalCount ;


        return json_encode($finalResult);
    }
    public static function getClientList_sample($page ,$zone , $status){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $get_dat =   $_GET;
        $new_customer = false;
        if(isset($get_dat['new_customer'])){
            $new_customer = true;

        }

        $offset = 0 ;
        if($page){
            $page = $page -1 ;
            $offset = $page * 10;
        }
        $query="SELECT c.sales_reps_id , s.name as sale_raps_name , c.* ,cb.name as company_branch_name , z.name as zone_name  from client as c
                   LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
                   LEFT JOIN zone  as z ON z.zone_id = c.zone_id
                   left join sales_reps as s ON s.sales_reps_id = c.sales_reps_id
                   where  c.company_branch_id =$company_id ";
        if($zone != 0){
            $query .= " and c.zone_id =$zone " ;
        }

        if($status == 1){
            $query .= " and c.is_active =1 and c.client_type = 1" ;
        }
        if($status == 2){
            $query .= " and c.is_active =0 and c.client_type = 1 " ;
        }
        if($status == 3){
            $query .= " and c.is_active =1 and c.client_type = 2" ;
        }
        if($status == 4){
            $query .= " and c.is_active =0 and c.client_type = 2 " ;
        }
        if($new_customer){

            $query   .=" order by  c.sales_reps_id ASC  
                     LIMIT 10 OFFSET $offset ";

        }else{




            $query   .=" order by  c.sales_reps_id ASC ,c.LastTime_login DESC 
                     LIMIT 10 OFFSET $offset ";

        }

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        $count_query ="SELECT count(*) as totalCount  from client as c
                   LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
                   LEFT JOIN zone  as z ON z.zone_id = c.zone_id
                   where  c.company_branch_id =$company_id and c.client_type = 2";
        if($zone != 0){
            $count_query .= " and c.zone_id =$zone " ;
        }
        if($status == 1){
            $count_query .= " and c.is_active =1 and c.client_type = 1" ;
        }
        if($status == 2){
            $count_query .= " and c.is_active =0 and c.client_type = 1 " ;
        }
        if($status == 3){
            $count_query .= " and c.is_active =1 and c.client_type = 2" ;
        }
        if($status == 4){
            $count_query .= " and c.is_active =0 and c.client_type = 2 " ;
        }



        $count_Result =  Yii::app()->db->createCommand($count_query)->queryAll();
        $totalCount = $count_Result[0]['totalCount'];
        $finalResult = array();
        $finalResult['clientList'] = $queryResult ;
        $finalResult['count'] = $totalCount ;

        return json_encode($finalResult);
    }
    public static function getClientList_sample__addFoolowUp($page ,$zone , $status){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $get_dat =   $_GET;
        $new_customer = false;
        if(isset($get_dat['new_customer'])){
            $new_customer = true;

        }

        $offset = 0 ;
        if($page){
            $page = $page -1 ;
            $offset = $page * 10;
        }
        $query="SELECT c.sales_reps_id , s.name as sale_raps_name , c.* ,cb.name as company_branch_name , z.name as zone_name  from client as c
                   LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
                   LEFT JOIN zone  as z ON z.zone_id = c.zone_id
                   left join sales_reps as s ON s.sales_reps_id = c.sales_reps_id
                   where  c.company_branch_id =$company_id and c.sales_reps_id!=0";
        if($zone != 0){
            $query .= " and c.zone_id =$zone " ;
        }

        if($status == 1){
            $query .= " and c.is_active =1 and c.client_type = 1" ;
        }
        if($status == 2){
            $query .= " and c.is_active =0 and c.client_type = 1 " ;
        }
        if($status == 3){
            $query .= " and c.is_active =1 and c.client_type = 2" ;
        }
        if($status == 4){
            $query .= " and c.is_active =0 and c.client_type = 2 " ;
        }
        if($new_customer){

            $query   .=" order by  c.sales_reps_id ASC  
                     LIMIT 10 OFFSET $offset ";

        }else{

            $query   .=" order by  c.sales_reps_id ASC ,c.LastTime_login DESC 
                     LIMIT 10 OFFSET $offset ";

        }

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        $count_query ="SELECT count(*) as totalCount  from client as c
                   LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
                   LEFT JOIN zone  as z ON z.zone_id = c.zone_id
                   where  c.company_branch_id =$company_id and c.client_type = 2";
        if($zone != 0){
            $count_query .= " and c.zone_id =$zone " ;
        }
        if($status == 1){
            $count_query .= " and c.is_active =1 and c.client_type = 1" ;
        }
        if($status == 2){
            $count_query .= " and c.is_active =0 and c.client_type = 1 " ;
        }
        if($status == 3){
            $count_query .= " and c.is_active =1 and c.client_type = 2" ;
        }
        if($status == 4){
            $count_query .= " and c.is_active =0 and c.client_type = 2 " ;
        }

        $count_Result =  Yii::app()->db->createCommand($count_query)->queryAll();
        $totalCount = $count_Result[0]['totalCount'];
        $finalResult = array();
        $finalResult['clientList'] = $queryResult ;
        $finalResult['count'] = $totalCount ;

        return json_encode($finalResult);
    }


    public static function getSampleClientList($page ,$zone , $status){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $get_dat =   $_GET;
        $new_customer = false;
        if(isset($get_dat['new_customer'])){
            $new_customer = true;


        }

        $offset = 0 ;
        if($page){
            $page = $page -1 ;
            $offset = $page * 10;
        }
        $query="SELECT  c.sales_reps_id , s.name as sale_raps_name ,c.* ,cb.name as company_branch_name , z.name as zone_name  from client as c
                   LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
                   LEFT JOIN zone  as z ON z.zone_id = c.zone_id
                   left join sales_reps as s ON s.sales_reps_id = c.sales_reps_id
                   where  c.company_branch_id =$company_id  and c.client_type = 2";
        if($zone != 0){
            $query .= " and c.zone_id =$zone " ;
        }
        if($status == 1){
            $query .= " and c.is_active =1 and c.client_type = 1" ;
        }
        if($status == 2){
            $query .= " and c.is_active =0 and c.client_type = 1 " ;
        }
        if($status == 3){
            $query .= " and c.is_active =1 and c.client_type = 2" ;
        }
        if($status == 4){
            $query .= " and c.is_active =0 and c.client_type = 2 " ;
        }
        if($new_customer){

            $query   .=" order by  c.sales_reps_id ASC  
                     LIMIT 10 OFFSET $offset ";

        }else{




            $query   .=" order by  c.sales_reps_id ASC ,c.LastTime_login DESC 
                     LIMIT 10 OFFSET $offset ";

        }

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        $count_query ="SELECT count(*) as totalCount  from client as c
                   LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
                   LEFT JOIN zone  as z ON z.zone_id = c.zone_id
                   where  c.company_branch_id =$company_id and c.client_type = 2 ";
        if($zone != 0){
            $count_query .= " and c.zone_id =$zone " ;
        }
        if($status == 1){
            $count_query .= " and c.is_active =1 and c.client_type = 1" ;
        }
        if($status == 2){
            $count_query .= " and c.is_active =0 and c.client_type = 1 " ;
        }
        if($status == 3){
            $count_query .= " and c.is_active =1 and c.client_type = 2" ;
        }
        if($status == 4){
            $count_query .= " and c.is_active =0 and c.client_type = 2 " ;
        }

        $count_Result =  Yii::app()->db->createCommand($count_query)->queryAll();
        $totalCount = $count_Result[0]['totalCount'];
        $finalResult = array();
        $finalResult['clientList'] = $queryResult ;
        $finalResult['count'] = $totalCount ;

        return json_encode($finalResult);
    }
    public static function addFollowUpClientList($page ,$zone , $status,$sales_reps_id,$sort_attributes,$sort_order){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $get_dat =   $_GET;
        $new_customer = false;
        if(isset($get_dat['new_customer'])){
            $new_customer = true;
        }
        $offset = 0 ;
        if($page){
            $page = $page -1 ;
            $offset = $page * 10;
        }
        $query="SELECT mr.date as drop_date,date(c.created_at) as created_date, c.sales_reps_id ,c.tag_color_id ,t.tag_color_name,t.tag_color_code,
                   s.name as sale_raps_name ,c.* ,cb.name as company_branch_name , z.name as zone_name  from client as c
                   LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
                   LEFT JOIN zone  as z ON z.zone_id = c.zone_id
                   left join sales_reps as s ON s.sales_reps_id = c.sales_reps_id
                    left join tag_color as t ON t.tag_color_id =c.tag_color_id
                      left join make_regualr_drop_client as mr ON mr.client_id = c.client_id and mr.drop_or_regular=2
                   where  c.company_branch_id =$company_id  and c.client_type = 2 and c.sales_reps_id!=0 ";
        if($zone != 0){
            $query .= " and c.zone_id =$zone " ;
        }
        if($sales_reps_id != 0){
            $query .= " and c.sales_reps_id =$sales_reps_id " ;
        }
        if($status == 1){
            $query .= " and c.is_active =1 and c.client_type = 1" ;
        }
        if($status == 2){
            $query .= " and c.is_active =0 and c.client_type = 1 " ;
        }
        if($status == 3){
            $query .= " and c.is_active =1 and c.client_type = 2" ;
        }
        if($status == 4){
            $query .= " and c.is_active =0 and c.client_type = 2 " ;
        }
        if($new_customer){


            $query   .=" order by  c.sales_reps_id ASC  
                     LIMIT 10 OFFSET $offset ";

        }else{

            if($sort_attributes =='0'){
                $query   .=" order by  c.sales_reps_id ASC ,c.LastTime_login DESC 
                     LIMIT 10 OFFSET $offset ";
            }else{

                $query   .=" order by  $sort_attributes $sort_order
                     LIMIT 10 OFFSET $offset ";
            }



        }



        $queryResult_reult =  Yii::app()->db->createCommand($query)->queryAll();

        $queryResult =array();

        foreach ($queryResult_reult as $value) {

            $client_id =$value['client_id'];

            /*$makedropandRegular = MakeRegualrDropClient::model()->findByAttributes(array(
                'client_id'=>$client_id,
                'drop_or_regular'=>2
            ));

            if($makedropandRegular){
              $dropDate =  $makedropandRegular['date'];

            }else{
                $dropDate = '';
            }*/




            $oneObject = array();
            $oneObject['drop_date'] = $value['drop_date'];
            $oneObject['created_date'] = $value['drop_date'];
            $oneObject['created_date'] = $value['created_date'];
            $oneObject['created_date'] = $value['created_date'];
            $oneObject['sale_raps_name'] = $value['sale_raps_name'];
            $oneObject['client_id'] = $value['client_id'];
            $oneObject['fullname'] = $value['fullname'];
            $oneObject['cell_no_1'] = $value['cell_no_1'];
            $oneObject['address'] = $value['address'];
            $oneObject['sale_raps_name'] = $value['sale_raps_name'];
            $oneObject['sales_reps_id'] = $value['sales_reps_id'];
            $oneObject['zone_name'] = $value['zone_name'];
            $oneObject['tag_color_name'] = $value['tag_color_name'];
            $oneObject['tag_color_name'] = $value['tag_color_name'];
            $oneObject['tag_color_id'] = $value['tag_color_id'];
            $oneObject['tag_color_code'] = $value['tag_color_code'];


            $remark_query = "select * from follow_up as f
                      where f.client_id = '$client_id'
                      order by f.date DESC
                      limit 1";

            $queryResult_reult =  Yii::app()->db->createCommand($remark_query)->queryAll();
            if($queryResult_reult){
                $oneObject['remarks'] = $queryResult_reult[0]['remarks'];
                $oneObject['followup_date'] = $queryResult_reult[0]['date'];
            }else{
                $oneObject['remarks'] = '';
                $oneObject['followup_date'] = '';
            }

            $queryResult[] =$oneObject ;

        }

        $count_query ="SELECT count(*) as totalCount  from client as c
                   LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
                   LEFT JOIN zone  as z ON z.zone_id = c.zone_id
                   where  c.company_branch_id =$company_id and c.client_type = 2 and c.sales_reps_id!=0";
        if($zone != 0){
            $count_query .= " and c.zone_id =$zone " ;
        }
        if($sales_reps_id != 0){
            $count_query .= " and c.sales_reps_id =$sales_reps_id " ;
        }
        if($status == 1){
            $count_query .= " and c.is_active =1 and c.client_type = 1" ;
        }
        if($status == 2){
            $count_query .= " and c.is_active =0 and c.client_type = 1 " ;
        }
        if($status == 3){
            $count_query .= " and c.is_active =1 and c.client_type = 2" ;
        }
        if($status == 4){
            $count_query .= " and c.is_active =0 and c.client_type = 2 " ;
        }

        $count_Result =  Yii::app()->db->createCommand($count_query)->queryAll();
        $totalCount = $count_Result[0]['totalCount'];
        $finalResult = array();
        $finalResult['clientList'] = $queryResult ;
        $finalResult['count'] = $totalCount ;

        return json_encode($finalResult);
    }
    public static function search_getClientList($page , $text ,$zone , $status){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $offset = 0 ;
        if($page){
            $page = $page -1 ;
            $offset = $page * 10;
        }
        $query="SELECT 
        c.* ,
        cb.name as company_branch_name , 
        z.name as zone_name  
        from client as c
        LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
        LEFT JOIN zone  as z ON z.zone_id = c.zone_id
        where  c.company_branch_id =$company_id and  (c.fullname LIKE '%$text%' OR c.address LIKE '%$text%'OR c.cell_no_1 LIKE '%$text%') ";
        if($zone != 0){
            $query .= " and c.zone_id =$zone " ;
        }
        if($status == 1){
            $query .= " and c.is_active =1" ;
        }
        if($status == 2){
            $query .= " and c.is_active =0 " ;
        }
        $query   .=" order by  c.is_active DESC ,c.LastTime_login DESC 
                     LIMIT 50 OFFSET $offset ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();





        $count_query ="SELECT count(*) as totalCount  from client as c
                   LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
                   LEFT JOIN zone  as z ON z.zone_id = c.zone_id
                   where  c.company_branch_id =$company_id and  (c.fullname LIKE '%$text%' OR c.address LIKE '%$text%') ";
        if($zone != 0){
            $count_query .= " and c.zone_id =$zone " ;
        }
        if($status == 1){
            $count_query .= " and c.is_active =1 " ;
        }
        if($status == 2){
            $count_query .= " and c.is_active =0 " ;
        }

        $count_Result =  Yii::app()->db->createCommand($count_query)->queryAll();
        $totalCount = $count_Result[0]['totalCount'];
        $finalResult = array();
        $finalResult['clientList'] = $queryResult ;
        $finalResult['count'] = $totalCount ;

        return json_encode($finalResult);
    }


    public static function getActiveClientLis_category_wise($customer_category_id){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT
                     c.client_id
                    from client as c
                  where c.customer_category_id = '$customer_category_id' and  c.company_branch_id =$company_id ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

        $array_cliend_ids = [];
        $array_cliend_ids[] = '-1';
        foreach ($queryResult as $value){
            $array_cliend_ids[] = $value['client_id'];
        }

        $ids = implode(',',$array_cliend_ids);
        return $ids;

    }
    public static function getActiveClientList(){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT c.*  from client as c
                  where c.is_active = 1 and  c.company_branch_id =$company_id
                 Order By c.fullname ASC ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        return json_encode($queryResult);
    }

    public static function getActiveClientList_forLedger_rider_wisr(){



        $company_id = Yii::app()->user->getState('company_branch_id');

        $user_id = Yii::app()->user->getState('user_id');
        $user_object = User::model()->findByPk($user_id);
        $supper_admin_user =  $user_object['supper_admin_user'];
        if($supper_admin_user==1){
        $query="SELECT
        c.* ,
        CONCAT(c.client_id , ':',c.fullname) as fullname ,
        z.name as zone_name 
        from client as c
        left join zone as z ON z.zone_id = c.zone_id
        where  c.company_branch_id =$company_id
        Order By c.fullname ASC ";
        }else{

            $getRiderList = CJSON::decode(riderData::get_rider_of_rider_delivery(),TRUE);
            $rider_ids =[];
            $rider_ids[] =-1;
            foreach ($getRiderList as $value){
                $rider_ids[] =$value['rider_id'];
            }
            $rider_id_list = implode(",",$rider_ids);

            $query = "Select  c.* ,
                CONCAT(c.client_id , ':',c.fullname) as fullname 
                       from rider_zone as rz
                Right join client as c ON c.zone_id = rz.zone_id 
                where rz.rider_id in ($rider_id_list)  ";
        }

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        return json_encode($queryResult);
    }
    public static function getActiveClientList_forLedger(){

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT c.* , CONCAT(c.client_id , ':',c.fullname) as fullname , z.name as zone_name from client as c
                  left join zone as z ON z.zone_id = c.zone_id
                  where  c.company_branch_id =$company_id
                  Order By c.fullname ASC ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        return json_encode($queryResult);
    }

    public static function getActiveClientList_forLedger_active_unactive($type){

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT c.* , CONCAT(c.client_id , ':',c.fullname) as fullname , z.name as zone_name from client as c
                  left join zone as z ON z.zone_id = c.zone_id
                  where  c.company_branch_id =$company_id and c.is_active =$type
                  Order By c.fullname ASC ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        return json_encode($queryResult);
    }
    public static function getActiveClientList_forLedger_active_unactive_colorTag($type){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT t.tag_color_id ,CONCAT(t.tag_color_name ,' #',COUNT(*)) as fullname  FROM client AS  c
                LEFT JOIN tag_color AS t ON t.tag_color_id =c.tag_color_id
                WHERE c.company_branch_id = $company_id
                 GROUP BY c.tag_color_id ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
        return json_encode($queryResult);
    }

    public static function getActiveClientList_forLedger_active($client_type){

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT c.* , CONCAT(c.client_id , ':',c.fullname) as fullname , z.name as zone_name from client as c
                  left join zone as z ON z.zone_id = c.zone_id
                  where  c.company_branch_id =$company_id  
                   ";

        if($client_type ==1){
            $query .=" and c.is_active =1 and c.client_type=1 ";
        }
        if($client_type ==2){
            $query .=" and c.is_active =0 and c.client_type=1 ";
        }
        if($client_type ==3){
            $query .=" and c.is_active =1 and c.client_type=2 ";
        }
        if($client_type ==4){
            $query .=" and c.is_active =0 and c.client_type=2 ";
        }
        $query .=" Order By c.fullname ASC ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        return json_encode($queryResult);
    }
    public static function getActiveClientList_forLedger_sample(){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT c.* , CONCAT(c.client_id , ':',c.fullname) as fullname , z.name as zone_name from client as c
                  left join zone as z ON z.zone_id = c.zone_id
                  where c.is_active = 1 and c.client_type =2 and  c.company_branch_id =$company_id
                 Order By c.fullname ASC ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

        return json_encode($queryResult);
    }

    public static function getClientCount(){
        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT c.*  from client as c where c.company_branch_id =$company_id ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        return count($queryResult);
    }


    public static function saveNewZoneFunction($data){

        $zone = new Zone();
        $zone->company_branch_id = $data['companyBranch'];
        $zone->name  = $data['name'];
        $zone->is_active = $data['active'];
        $zone->is_deleted = $data['delete'];
        if($zone->save()){
            $zoneID = $zone->zone_id;
            $query="SELECT z.* , cb.name as companyBranchName from zone as z
                  LEFT JOIN company_branch as cb ON cb.company_branch_id = z.company_branch_id
                     where z.zone_id = $zoneID ";
            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
            zoneData::$response['success']=true;
            zoneData::$response['message']='ok';
            zoneData::$response['zone']=$queryResult;

        }else{
            zoneData::$response['success'] = false ;
            zoneData::$response['message'] = $zone->getErrors() ;

        }
        return json_encode(zoneData::$response);

    }

    public static function getZoneList(){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT z.*  from zone as z
               
                  where z.company_branch_id = $company_id
                   order by z.name ASC ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        return json_encode($queryResult);

    }

    public static function getPreferedTimeList(){
        $zone =PreferredTime::model()->findAll();
        $zonelist = array();
        foreach ($zone as $value){
            $zonelist[] = $value->attributes;
        }
        return json_encode($zonelist);
    }

    public static function getFrequencyList(){
        $zone =Frequency::model()->findAll();
        $zonelist = array();
        foreach ($zone as $value){
            $zonelist[] = $value->attributes;
        }
        return json_encode($zonelist);
    }

    public static function  saveNewClientFunction($get_data){

        $data = $get_data['client_data'];
        $data = CJSON::decode($data, TRUE);

        $frequencyList = $get_data['frequencyList'];
        $product_id = $get_data['product_id'];


        date_default_timezone_set("Asia/Karachi");


        $clientProductObject = $data['clientProductObject'];
        $spacial_order_object = $get_data['spacial_order_object'];



        $companBranchID =    Yii::app()->user->getState('company_branch_id');


        $loginID =    Yii::app()->user->getState('user_id');
        $clint = new Client();
        $clint->company_branch_id =$companBranchID ;
        $clint->user_id =$loginID;

        if($companBranchID ==14){


            $block_id = $data['block_id'];

            $block_object =Block::model()->findByPk($block_id);

            if($block_object){
                $block_name = $block_object['block_name'];
            }else{
                $block_name = '';
            }
            $area_id = $data['area_id'];
            $area_object = Area::model()->findByPk(intval($area_id));
            if($area_object){
                $area_name =  $area_object['area_name'];
            }else{
                $area_name =  '';
            }
            $fullAddress_name =  $data['house_no'].' '.$data['sub_no'].' '.$block_name.' '.$area_name;
            $clint->fullname =$fullAddress_name;
            $clint->address =$fullAddress_name;
        }else{
            $clint->fullname =$data['fullname'];
            $clint->address =$data['address'];
        }

        $clint->userName =$data['userName'];
        $clint->password =$data['password'];
        $clint->father_or_husband_name=$data['father_or_husband_name'];
        $clint->date_of_birth=$data['date_of_birth'];
        $clint->email =$data['email'];
        $clint->cnic =$data['cnic'];

        $clint->house_no =$data['house_no'];
        $clint->sub_no =$data['sub_no'];

        $clint->area_id =$data['area_id'];
        $clint->block_id =$data['block_id'];

        $clint->cell_no_1 =$data['cell_no_1'];
        $clint->cell_no_2 =$data['cell_no_2'];
        $clint->residence_phone_no =$data['residence_phone_no'];

        $clint->zone_id =$data['zone_id'];
        $clint->network_id =$data['network_id'];
        $clint->security =$data['security'];
        $clint->city =$data['city'];


        $clint->is_active =$data['is_active'];
        $clint->payment_term =$data['payment_term'];
        $clint->is_deleted =$data['is_deleted'];
        $clint->daily_delivery_sms =$data['daily_delivery_sms'];
        $clint->alert_new_product =$data['alert_new_product'];
        $clint->created_by =$loginID;
        $clint->customer_category_id =$data['customer_category_id'];
        $clint->one_time_delivery =$data['one_time_delivery'];
        $clint->login_form = '1';
        $clint->view_by_admin = '0';

        $clint->is_approved = '1';

        $clint->client_type = $data['client_type'];

        $clint->created_at =date("Y-m-d h:i:sa");

        $clint->new_create_date =date("Y-m-d h:i:sa");

        $clint->receipt_alert =$data['receipt_alert'];

        $clint->grass_amount =$data['grass_amount'];


        $clint->last_name =$data['last_name'];

        $clint->notification_alert_allow_user =$data['notification_alert_allow_user'];


        $clint->security_type_customer =$data['security_type_customer'];

        $clint->customer_source_id =$data['customer_source_id'];
        $customer_source_id = '';
        if(!empty($data['customer_source_name'])){
            $data['customer_source_name'];

            $array_source = explode('-',$data['customer_source_name']);

            if(isset($array_source[0])){
                $customer_source_id =$array_source[0];
            }

        }

        $clint->customer_source_name =$data['customer_source_name'];

        if($data['client_type'] ==2){
            $clint->save_as_sample = '1';
        }

        if($data['is_active'] ==0){
            $clint->inactive_date=date("Y-m-d");
        }


        if($clint->save()){
            $clintID = $clint->client_id;

            $sechual_object = [];
            $sechual_object['productID'] = $product_id;
            $sechual_object['clientID'] =$clintID;
            $sechual_object['orderStartDate'] = date("Y-m-d");;
            $sechual_object['dayObject'] = $frequencyList;

            if($product_id>0){

                self::saveChangedayObjectQuantityFunction($sechual_object);
            }
            if($spacial_order_object['product_id']>0){
                self::save_new_spacial_order($clintID,$spacial_order_object);
            }


            $query="SELECT c.* ,cb.name as company_branch_name , z.name as zone_name  from client as c
                    LEFT JOIN company_branch as cb ON c.company_branch_id = cb.company_branch_id
                     LEFT JOIN zone  as z ON z.zone_id = c.zone_id
                     where c.client_id = $clintID ";
            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
            if($clientProductObject){
                foreach($clientProductObject as $value){
                    $clientProductObject = new ClientProductPrice();
                    if($value['clientProductPrice'] !=0){
                        $clientProductObject->client_id = $clintID ;



                        $clientProductObject->product_id = $value['product_id'];
                        $clientProductObject->price= $value['clientProductPrice'];
                        if($value['clientProductPrice']>0){
                            $clientProductObject->save();
                        }

                    }
                }
            }

            riderData::$response['success']=true;
            riderData::$response['message']='ok';
            riderData::$response['client']=$queryResult;

        }else{
            riderData::$response['success'] = false ;
            riderData::$response['message'] = $clint->getErrors() ;
            var_dump($clint->getErrors());
            die();

        }
        return json_encode(riderData::$response);

    }
    public static function editClientFunction($data){

        if(isset($data['clientProductObject'])){
            $clientProductObject = $data['clientProductObject'];
        }else{
            $clientProductObject = array();
        }
        $companBranchID =    Yii::app()->user->getState('company_branch_id');
        $loginID =    Yii::app()->user->getState('user_id');
        $clint =Client::model()->findByPk(intval($data['client_id']));

         $is_active_already  = $clint['is_active'];

        $clint->company_branch_id =$companBranchID ;
        $clint->user_id =$loginID;

        $get_client_id = $data['client_id'];
        if($companBranchID ==14){
            $block_id = $data['block_id'];
            $block_object =Block::model()->findByPk($block_id);
            if($block_object){
                $block_name = $block_object['block_name'];
            }else{
                $block_name = '';
            }
            $area_id = $data['area_id'];
            $area_object = Area::model()->findByPk(intval($area_id));
            if($area_object){
                $area_name =  $area_object['area_name'];
            }else{
                $area_name =  '';
            }
            $fullAddress_name =  $data['house_no'].' '.$data['sub_no'].' '.$block_name.' '.$area_name;
            $clint->fullname =$fullAddress_name;
            $clint->address =$fullAddress_name;
        }else{
            $clint->fullname =$data['fullname'];
            $clint->address =$data['address'];
        }
        $clint->userName =$data['userName'];
        $clint->password =$data['password'];
        $clint->father_or_husband_name=$data['father_or_husband_name'];
        $clint->date_of_birth=$data['date_of_birth'];
        $clint->email =$data['email'];
        $clint->cnic =$data['cnic'];

        $clint->house_no =$data['house_no'];
        $clint->sub_no =$data['sub_no'];

        $clint->area_id =$data['area_id'];
        $clint->block_id =$data['block_id'];

        $clint->cell_no_1 =$data['cell_no_1'];
        $clint->cell_no_2 =$data['cell_no_2'];
        $clint->residence_phone_no =$data['residence_phone_no'];

        $clint->zone_id =$data['zone_id'];
        $clint->network_id =$data['network_id'];
        $clint->security =$data['security'];
        $clint->city =$data['city'];
        $clint->area =$data['area'];
        $clint->is_active =$data['is_active'];
        $clint->is_deleted =$data['is_deleted'];
        $clint->created_by =$loginID;
        $clint->daily_delivery_sms =$data['daily_delivery_sms'];
        $clint->alert_new_product =$data['alert_new_product'];
        $clint->customer_category_id =$data['customer_category_id'];
        $clint->client_type = $data['client_type'];
        $clint->receipt_alert =$data['receipt_alert'];

        $clint->one_time_delivery =$data['one_time_delivery'];
        $clint->receipt_alert =$data['receipt_alert'];

        $clint->last_name =$data['last_name'];

        $clint->receipt_alert =$data['receipt_alert'];
        $clint->grass_amount =$data['grass_amount'];
        $clint->notification_alert_allow_user =$data['notification_alert_allow_user'];


        $clint->security_type_customer =$data['security_type_customer'];

        $clint->customer_source_id =$data['customer_source_id'];

        $clint->is_mobile_notification =$data['is_mobile_notification'];
        $clint->is_push_notification =$data['is_push_notification'];

        $customer_source_id = '';
        if(!empty($data['customer_source_name'])){
            $data['customer_source_name'];

            $array_source = explode('-',$data['customer_source_name']);

            if(isset($array_source[0])){
                $customer_source_id =$array_source[0];
            }
        }



        $clint->customer_source_name  = $data['customer_source_name'];

        $clint->payment_term =$data['payment_term'];

        if($data['is_active'] ==1){
            $clint->created_at =date("Y-m-d h:i:sa");
        }
        if(isset($data['inactive_reason'])){
            $reason_name = SampleClientDropReason::model()->findByPk(intval($data['inactive_reason']));
            $clint->deactive_reason = $reason_name['reason'];
            $clint->deactive_reason_id = $data['inactive_reason'];
        }

        $clint->deactive_date =  date("Y-m-d");

        if($data['client_type']==2){
            $clint->save_as_sample = '1';
        }
        if($data['is_active'] ==0){
            $clint->inactive_date=date("Y-m-d");

        }

        if($clint->save()){
            $clintID = $clint->client_id;
            if($companBranchID ==1){
               $is_active =   $data['is_active'];
               if($is_active_already ==0 and $is_active==1){

                   $company_branch_id =  $companBranchID;
                   $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
                   $companyMask = $companyObject['sms_mask'];
                   $companyTitle = $companyObject['company_title'];

                   $message =   'A customer "'.$data['fullname'].'" have been activated.' .$companyTitle;

                   smsLog::saveSms($data['client_id'], $company_branch_id, '+923214667127', 'Company Admin', $message);
                   utill::sendSMS2('+923400009454', $message, $companyMask, $company_branch_id, 1, $data['client_id']);
                   utill::sendSMS2('+923341118292', $message, $companyMask, $company_branch_id, 1, $data['client_id']);
                   utill::sendSMS2('+923021118292', $message, $companyMask, $company_branch_id, 1, $data['client_id']);

               }
            }
            riderData::$response['success']=true;
            riderData::$response['message']='ok';
            if(isset($data['clientProductObject'])){
                $clientObject = ClientProductPrice::model()->deleteAllByAttributes(array('client_id'=>$clintID));
                if($clientProductObject){
                    foreach($clientProductObject as $value){
                        $clientProductObject = new ClientProductPrice();
                        if($value['clientProductPrice'] !=0 and $value['clientProductPrice'] !=''){

                            self::check_rate_chage_function($get_client_id,$value['clientProductPrice'],$value['product_id']);

                            $clientProductObject->client_id = $clintID ;
                            $clientProductObject->product_id = $value['product_id'];
                            $clientProductObject->price= $value['clientProductPrice'];
                            if($value['clientProductPrice']>0){
                                $clientProductObject->save();
                            }

                        }
                    }
                }
            }
        }else{
            riderData::$response['success'] = false ;
            riderData::$response['message'] = $clint->getErrors() ;
        }
        return json_encode(riderData::$response);
    }
    public static function check_rate_chage_function($get_client_id,$clientProductPrice,$product_id){

        $action_name = 'edit_rate';

        $selected_date =date('Y-m-d');

        $modify_table_name ='client_product_price';

        $data_befour_action =[];

        $client_object = Client::model()->findByPk($get_client_id);



        $user_id =  $client_object['user_id'];
        $companBranchID =    Yii::app()->user->getState('company_branch_id');

        $data_befour_action['add_user_name'] ='';
        $data_befour_action['product_id'] =$product_id;
        $data_befour_action['created_at'] =$client_object['created_at'];

        $user_object = User::model()->findByAttributes([
            'user_id'=>$user_id,
            'company_id'=>$companBranchID
        ]);

        if($user_object){
            $data_befour_action['add_user_name'] = $user_object['full_name'];
        }



        $change_rate_object = ClientProductPrice::model()->findByAttributes([
            'client_id'=>11544,
            'product_id'=>10
        ]);
        $update_client_product_price = true;
        if($change_rate_object){
            $allready_price =  $change_rate_object['price'];
            if($allready_price!=$clientProductPrice){
                $data_befour_action['allready_price'] =$allready_price;
            }else{
                $update_client_product_price = false;
            }
        }else{
            $data_befour_action['allready_price'] =0;
        }

        $new_value = $clientProductPrice;
        $client_id = $get_client_id;
        $remarks='';
        $modify_id = Yii::app()->user->getState('user_id');
        $data_befour_action = json_encode($data_befour_action);
        if($update_client_product_price){
            save_every_crud_record::save_crud_record_date_waise(
                $action_name,
                $modify_table_name,
                $modify_id,
                $selected_date,
                $data_befour_action,
                $new_value,
                $client_id,
                $remarks
            );
        }


    }
    public static function oneCustomerAmountListFunction($clientID){

        $query = " select pm.payment_master_id , c.fullname ,  pm.date , pm.amount_paid ,pm.bill_month_date,pm.reference_number,
                    pm.payment_mode,
                    pm.time,
                    pm.edit_by_user_id,
                    pm.user_id,
                    pm.rider_id,
                    pm.payment_type,
                    pm.collection_vault_id,
                    cv.collection_vault_name
                    from payment_master as pm
                    left join client as c ON c.client_id = pm.client_id
                    LEFT JOIN collection_vault AS  cv ON cv.collection_vault_id = pm.collection_vault_id
                    where pm.client_id = $clientID 
                    order by pm.date DESC";




        $result = Yii::app()->db->createCommand($query)->queryAll();

        $final_result = [];
        foreach ($result as $value){

            $payment_master_id =  $value['payment_master_id'];
            $discount_list = discount_amount_data::discount_of_amount($payment_master_id);
            $bill_month_date =  $value['bill_month_date'];
            $array = explode('-', $bill_month_date);

            $year =  $array[0];

            $month =  $array[1];

            $query ="SELECT 
                SUM(d.total_amount) AS total_amount
                FROM delivery AS d
                
                WHERE d.client_id = '$clientID' AND 
                year(d.date) ='$year' AND MONTH(d.date) ='$month' ";



            $result = Yii::app()->db->createCommand($query)->queryscalar();

            $tomonth_delivery =   round($result,0);


            $payment_mode=$value['payment_mode'];

            /*  WHEN pm.payment_mode = 1 then 'Online'
                    WHEN pm.payment_mode = 2 then 'Cheque'
                    WHEN pm.payment_mode = 3 then 'Cash'
                    WHEN pm.payment_mode = 5 then 'Bank Transaction'
                    WHEN pm.payment_mode = 6 then 'Card Transaction'
                    ELSE 'Other'*/
            if($payment_mode ==1){
                $value["payment_mode_text"] ='Online';
            }elseif($payment_mode ==2){
                $value["payment_mode_text"] ='Cheque';
            }elseif($payment_mode ==3){
                $value["payment_mode_text"] ='Cash';
            }elseif($payment_mode ==5){
                $value["payment_mode_text"] ='Bank Transaction';
            }elseif($payment_mode ==6){
                $value["payment_mode_text"] ='Card Transaction';
            }else{
                $value["payment_mode_text"] ='Other';
            }

            $bill_month_date_get = $value['bill_month_date'];

            $month = date("m",strtotime($bill_month_date_get));
            $year = date("Y",strtotime($bill_month_date_get));

            $value['get_month'] =$month;
            $value['tomonth_delivery'] =$tomonth_delivery;
            $value['get_year'] =$year;
            if($value['edit_by_user_id']>0){
                $value['color'] ='#FF7F50';
            }
            if($value['payment_type']==0){
                $value['payment_type_text'] ='Payment';
            }else{
                $value['payment_type_text'] ='Bad Debt';
            }

            $value['discount_list'] =$discount_list;

            $final_result[] = $value;
        }

        return json_encode($final_result);
    }

    public static function oneCustomerAmountListFunction_all($data){


        $month = $data['month'];

        $year = $data['year'];
        $payment_mode = $data['payment_mode'];

        $companBranchID =    Yii::app()->user->getState('company_branch_id');


        $query = " select pm.payment_master_id , c.fullname ,  pm.date , pm.amount_paid ,pm.bill_month_date,pm.reference_number,
                CASE 
                  WHEN pm.payment_mode = 1 then 'Online'
                  WHEN pm.payment_mode = 2 then 'Cheque'
                  WHEN pm.payment_mode = 3 then 'Cash'
                   WHEN pm.payment_mode = 5 then 'Bank Transaction'
                   WHEN pm.payment_mode = 6 then 'Card Transaction'
                  ELSE 'Other'
                  END  as payment_mode
                 from payment_master as pm
                 left join client as c ON c.client_id = pm.client_id  
                 where  c.company_branch_id = '$companBranchID' and   month(pm.date) ='$month' and year(pm.date)='$year' 
                  ";

        if($payment_mode > 0){
            $query  .=" and pm.payment_mode = '$payment_mode' ";
        }

        $query  .=" order by pm.date DESC";


        $result = Yii::app()->db->createCommand($query)->queryAll();

        return json_encode($result);
    }


    public static function deleteFunction($client_id){
        $ClientProductFrequencyObject =  ClientProductFrequency::model()->findAllByAttributes(array('client_id'=>$client_id));
        foreach ($ClientProductFrequencyObject as $client_value){
            $cpfq_id = $client_value['client_product_frequency'];
            ClientProductFrequencyQuantity::model()->deleteAllByAttributes(array('client_product_frequency_id'=>$cpfq_id));
            ClientProductFrequency::model()->deleteByPk(intval($cpfq_id));
        }
        ClientProductPrice::model()->deleteAllByAttributes(array('client_id'=>$client_id));
        Complain::model()->deleteAllByAttributes(array('client_id'=>$client_id));
        $deliveryOBject = Delivery::model()->findAllByAttributes(array('client_id'=>$client_id));
        foreach($deliveryOBject as $deliveryValue){
            $deliveryID = $deliveryValue['delivery_id'];
            DeliveryDetail::model()->deleteAllByAttributes(array('delivery_id'=>$deliveryID));
            Delivery::model()->deleteByPK(intval($deliveryID));
        }
        HaltRegularOrders::model()->deleteAllByAttributes(array('client_id'=>$client_id));
        NotDeliveryRecord::model()->deleteAllByAttributes(array('client_id'=>$client_id));
        PaymentDetail::model()->deleteAllByAttributes(array('client_id'=>$client_id));
        PaymentMaster::model()->deleteAllByAttributes(array('client_id'=>$client_id));
        SpecialOrder::model()->deleteAllByAttributes(array('client_id'=>$client_id));


        if(Client::model()->deleteByPK(intval($client_id))){
            riderData::$response['success']=true;
            riderData::$response['message']='ok';
        }else{
            riderData::$response['success'] = false ;
        }
        return json_encode(riderData::$response);
    }

    public static function searchClientFunction($data){
        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT c.*  , z.name as zone_name  from   client as c
                   LEFT JOIN zone  as z ON z.zone_id = c.zone_id
                  where c.company_branch_id = '$company_id' and  (c.fullname LIKE '%$data%' OR c.address LIKE '%$data%') ";

        $productList =  Yii::app()->db->createCommand($query)->queryAll();

        return json_encode($productList);
    }


    public static function get_delivery_between_date_rang_api($data){

        $client_id = $data['client_id'];
        $product_id = $data['product_id'];
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        $query ="SELECT 
            d.date,
            dd.quantity
            FROM  delivery AS d
            LEFT JOIN delivery_detail AS dd
              ON dd.delivery_id =d.delivery_id
            WHERE d.client_id ='$client_id'
            AND dd.product_id ='$product_id'
            AND d.date BETWEEN 
            '$start_date' AND '$end_date' ";
       
        $orderList =  Yii::app()->db->createCommand($query)->queryAll();
        return $orderList ;

    }
    public static function getOrderAgainstClint_api($clientID){

        $query = "SELECT 
            cpf.orderStartDate AS start_date,
            'weekly'  as order_type,
            cpf.client_id , cpf.product_id  from 
            client_product_frequency as cpf
            where cpf.client_id = '$clientID'
        union
            SELECT 
            ic.start_interval_scheduler AS start_date,
            'interval' as order_type ,
            ic.client_id ,
            ic.product_id from interval_scheduler as ic
            where ic.client_id ='$clientID' ";

        $orderList =  Yii::app()->db->createCommand($query)->queryAll();
        return $orderList ;

    }
    public static function getOrderAgainstClint($clientID){

        $query = " select  1 as order_type,
            cpf.client_id , cpf.product_id  from 
            client_product_frequency as cpf
            where cpf.client_id = '$clientID'
            union
            select  2 as order_type ,
            list.client_id ,
            list.product_id from interval_scheduler as list
            where list.client_id ='$clientID' ";


        $orderList =  Yii::app()->db->createCommand($query)->queryAll();
        return $orderList ;

    }
    public static function getOrderAgainstClint_product_wise($data){

        $client_id = $data['clientID'];
        $product_id = $data['productID'];


        $query = " select  1 as order_type, cpf.client_id , cpf.product_id  from client_product_frequency as cpf
                where cpf.client_id = '$client_id' and  cpf.product_id = '$product_id'
                union
                select  2 as order_type , list.client_id , list.product_id from interval_scheduler as list
                where list.client_id ='$client_id' and  list.product_id = '$product_id'";


        $orderList =  Yii::app()->db->createCommand($query)->queryRow();
        return $orderList ;

    }


    public static function getproductList($page){
        $offset = 0 ;
        if($page){
            $offset = $page * 10;
        }
        $query="SELECT p.*  from   product as p 
             LIMIT 10 OFFSET $offset ";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();

        return ($productList);
    }
    public static function geClientBasetproductList($clientID){

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT p.product_id ,
            p.name ,
            p.unit ,
            IFNUll(cpp.price , p.price) as price from 
            product as p
            left join client_product_price as cpp
            ON cpp.product_id = p.product_id 
            AND cpp.client_id ='$clientID'
            where p.company_branch_id = '$company_id' and p.bottle = 0";



        $productList =  Yii::app()->db->createCommand($query)->queryAll();

        return ($productList);
    }

    public static function halt_regular_order_api_function($data){

        $clientID = $data['client_id'] ;
        $productID = $data['product_id'] ;

        $start_date_main = $data['start_date'] ;
        $end_date_main = $data['end_date'] ;
           /*   date*/
        $query ="SELECT 
          *
        FROM halt_regular_orders AS h
        WHERE h.client_id = '$clientID'
            AND h.product_id ='$productID' 
              ";

        /*AND h.start_date >='2017-08-04'
        AND h.end_date <='2019-06-30'*/
        $result =  Yii::app()->db->createCommand($query)->queryAll();

        $dates = array();
        foreach ($result as $value){
            $start_date =$value['start_date'];
            $end_date =$value['end_date'];
             //getDatesFromRange( '2010-10-01', '2010-10-05' );

            $current = strtotime($start_date);
            $last = strtotime($end_date);
            $output_format = 'Y-m-d' ;
            $step = '+1 day';
            while( $current <= $last ) {
                $get_date = date($output_format, $current);
                if($get_date>=$start_date_main && $get_date<=$end_date_main ){


                    $dates[] = $get_date;
                }

                $current = strtotime($step, $current);
            }

        }



        return $dates;
    }
    public static function manageSpecialOrder_function($data){


        $clientID = $data['client_id'] ;
        $productID = $data['product_id'] ;
        $start_date = $data['start_date'] ;
        $end_date = $data['end_date'] ;
         /*date and quantity*/
        $query ="SELECT 
            so.quantity,
            so.start_date as date 
            FROM special_order AS so
            WHERE so.product_id ='$productID'
            AND so.client_id ='$clientID'
            and   so.start_date BETWEEN '$start_date' 
                AND '$end_date' ";

        $result =  Yii::app()->db->createCommand($query)->queryAll();
        return $result;
    }
    public static function selectFrequencyForOrderFunction_api($order_type, $data){


        $clientID = $data['client_id'] ;
        $productID = $data['product_id'] ;
        $todaydate =date("Y-m-d");
        if($order_type =='weekly'){

            $query="SELECT 
            cf.*  ,
            cfq.*,
            f.day_name
            from client_product_frequency as cf
            LEFT JOIN  client_product_frequency_quantity as cfq 
            ON cfq.client_product_frequency_id = cf.client_product_frequency
            left JOIN frequency AS f ON f.frequency_id = cfq.frequency_id   
            where cf.client_id = $clientID and cf.product_id = $productID";


            $result_array =  Yii::app()->db->createCommand($query)->queryAll();

            $result =[];
            foreach ($result_array as $value){

                $one_object =[];
                $one_object['frequency_id'] =$value['frequency_id'];
                $one_object['day_name'] =$value['day_name'];
                $one_object['quantity'] =$value['quantity'];
                $result[] =$one_object;
            }



        }else{
            $query = "select
                IFNULL((select isi.is_halt from interval_scheduler as isi 
                where isi.client_id = '$clientID' and isi.product_id ='$productID' and '$todaydate'  between isi.halt_start_date and isi.halt_end_date
                limit 1 ) ,0) as is_halt ,ist.default_value,
                '$productID' as product_id ,IFNULL(ist.product_quantity ,'') as product_quantity  ,IFNULL(c.client_id ,0) as client_id  , IFNULL(ist.interval_days ,'') as interval_days
                ,IFNULL(ist.start_interval_scheduler ,'') as start_interval_scheduler,IFNULL(ist.halt_start_date ,0) as halt_start_date,IFNULL(ist.halt_end_date ,0) as halt_end_date   from client as c
                left join interval_scheduler as ist ON ist.client_id = '$clientID' and ist.product_id = '$productID'
                where c.client_id = '$clientID' ";

            $result_array =Yii::app()->db->createCommand($query)->queryRow();

            $result =[];


            $result['interval_days'] =$result_array['interval_days'];
            $result['quantity'] =$result_array['product_quantity'];



        }

        return $result;
    }
    public static function selectFrequencyForOrderFunction($data){
        $clientID = $data['clientID'] ;
        $productID = $data['productID'] ;
        $query="Select cf.*  , cfq.* from client_product_frequency as cf
                LEFT JOIN  client_product_frequency_quantity as cfq ON cfq.client_product_frequency_id = cf.client_product_frequency
                where cf.client_id = $clientID and cf.product_id = $productID";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();
        return json_encode($productList);
    }
    public static function selectFrequencyForOrderFunction_for_report($data){
        $clientID = $data['clientID'] ;
        $productID = $data['productID'] ;
        $query="Select cf.*  , cfq.* from client_product_frequency as cf
                LEFT JOIN  client_product_frequency_quantity as cfq ON cfq.client_product_frequency_id = cf.client_product_frequency
                where cf.client_id = $clientID and cf.product_id = $productID";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();
        return $productList;
    }

    public static function selectFrequencyForOrderFunction_interval($data){
        $clientID = $data['clientID'] ;
        $productID = $data['productID'] ;
        $todaydate = date("Y-m-d");
        $query = "select
                        IFNULL((select isi.is_halt from interval_scheduler as isi 
                        where isi.client_id = '$clientID' and isi.product_id ='$productID' and '$todaydate'  between isi.halt_start_date and isi.halt_end_date
                        limit 1 ) ,0) as is_halt ,ist.default_value,
              '$productID' as product_id ,IFNULL(ist.product_quantity ,'') as product_quantity  ,IFNULL(c.client_id ,0) as client_id  , IFNULL(ist.interval_days ,'') as interval_days
            ,IFNULL(ist.start_interval_scheduler ,'') as start_interval_scheduler,IFNULL(ist.halt_start_date ,0) as halt_start_date,IFNULL(ist.halt_end_date ,0) as halt_end_date   from client as c
            left join interval_scheduler as ist ON ist.client_id = '$clientID' and ist.product_id = '$productID'
            where c.client_id = '$clientID' ";

        $result =Yii::app()->db->createCommand($query)->queryAll();
        return json_encode($result[0]);
    }
    public static function selectFrequencyForOrderFunction_interval_for_export($data){

        $clientID = $data['clientID'] ;

        $productID = $data['productID'] ;
        $todaydate = date("Y-m-d");
        $query = "select
                        IFNULL((select isi.is_halt from interval_scheduler as isi 
                        where isi.client_id = '$clientID' and isi.product_id ='$productID' and '$todaydate'  between isi.halt_start_date and isi.halt_end_date
                        limit 1 ) ,0) as is_halt ,ist.default_value,
              '$productID' as product_id ,IFNULL(ist.product_quantity ,'') as product_quantity  ,IFNULL(c.client_id ,0) as client_id  , IFNULL(ist.interval_days ,'') as interval_days
            ,IFNULL(ist.start_interval_scheduler ,'') as start_interval_scheduler,IFNULL(ist.halt_start_date ,0) as halt_start_date,IFNULL(ist.halt_end_date ,0) as halt_end_date   from client as c
            left join interval_scheduler as ist ON ist.client_id = '$clientID' and ist.product_id = '$productID'
            where c.client_id = '$clientID' ";

        $result =Yii::app()->db->createCommand($query)->queryAll();
        return $result[0];
    }
    public static function save_new_spacial_order($clintID,$spacial_order_object){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $special_order = new SpecialOrder();
        $special_order->client_id = $clintID ;
        $special_order->product_id = $spacial_order_object['product_id'] ;
        $special_order->quantity = $spacial_order_object['quantity'] ; ;
        $special_order->delivery_on = date("Y-m-d");
        $special_order->start_date = $spacial_order_object['startDate'];
        $special_order->end_date =$spacial_order_object['endDate'];;
        $special_order->status_id =1;
        $special_order->preferred_time_id =1;
        $special_order->company_branch_id = $company_id;
        if($special_order->save()){

        }else{

        }
    }
    public static function saveChangedayObjectQuantityFunction($data){

        $clientID = $data['clientID'];
        $productID = $data['productID'];
        $orderStartDate = $data['orderStartDate'];

        date_default_timezone_set("Asia/Karachi");

        $today_date =date("Y-m-d");
        $dayObject = $data['dayObject'];


        if($orderStartDate<=$today_date){

            $clientFrequency =  ClientProductFrequency::model()->findByAttributes(array('client_id'=>$clientID , 'product_id'=>$productID));
            $clientFFID = $clientFrequency['client_product_frequency'];
            ClientProductFrequencyQuantity::model()->deleteAllByAttributes(array('client_product_frequency_id'=>$clientFFID));
            if(isset($clientFrequency)){
                // $clientFrequency->delete();
                $client_product_frequency = $clientFrequency['client_product_frequency'];
                $clientFrequency->orderStartDate =$orderStartDate ;
                $clientFrequency->save();
            }else{
                $ClientProductFrequency = new ClientProductFrequency();
                $ClientProductFrequency->client_id = $clientID ;
                $ClientProductFrequency->product_id = $productID ;
                $ClientProductFrequency->quantity = '0' ;
                $ClientProductFrequency->total_rate = '0' ;
                $ClientProductFrequency->frequency_id = '1' ;
                $ClientProductFrequency->orderStartDate =$orderStartDate ;
                $ClientProductFrequency->save();
                $client_product_frequency = $ClientProductFrequency['client_product_frequency'];
            }
            foreach($dayObject as $value){
                if($value['slectDayForProducy']){
                    $daySave =new ClientProductFrequencyQuantity();
                    $daySave->client_product_frequency_id = $client_product_frequency ;
                    $daySave->frequency_id= $value['frequency_id'] ;
                    $daySave->quantity= $value['quantity'] ;
                    $daySave->preferred_time_id = isset($value['preferred_time_id'])? $value['preferred_time_id']:'0';
                    $daySave->save();
                }
            }
            $clientSchedulerObject  = IntervalScheduler::model()->findByAttributes(array('client_id'=>$clientID , 'product_id'=>$productID));
            if($clientSchedulerObject){
                $clientSchedulerObject->delete();
            }
            $savechagescheduler =new ChangeSchedulerRecord();
            $savechagescheduler->client_id = $clientID;
            $savechagescheduler->company_id = Yii::app()->user->getState('company_branch_id');
            $savechagescheduler->date = date("Y-m-d"); ;
            $savechagescheduler->change_form= 1 ;
            $savechagescheduler->save();

        }else{

            $EffectiveDateSchedule =EffectiveDateSchedule::model()->findByAttributes(
                array('client_id'=>$clientID , 'product_id'=>$productID)
            );
            $effective_date_schedule_id = $EffectiveDateSchedule['effective_date_schedule_id'];

            EffectiveDateScheduleFrequency::model()->deleteAllByAttributes(
                array('effective_date_schedule_id'=>$effective_date_schedule_id)
            );
            if($EffectiveDateSchedule){
                $EffectiveDateSchedule->delete();
            }


            $effective_date_schedual =New EffectiveDateSchedule();
            $effective_date_schedual->client_id = $clientID;
            $effective_date_schedual->product_id =$productID ;

            $effective_date_schedual->date=$orderStartDate ;

            if($effective_date_schedual->save()){
                $effective_date_schedule_id = $effective_date_schedual->effective_date_schedule_id;
                foreach($dayObject as $value){
                    if($value['slectDayForProducy']){
                        $effective_date_schedule_frequency = new EffectiveDateScheduleFrequency();
                        $effective_date_schedule_frequency->effective_date_schedule_id =$effective_date_schedule_id;
                        $effective_date_schedule_frequency->frequency_id =$value['frequency_id'] ;
                        $effective_date_schedule_frequency->quantity =$value['quantity'];
                        if($effective_date_schedule_frequency->save()){
                        }else{

                            echo "<pre>";
                            print_r($effective_date_schedual->getErrors());
                            die();
                        }
                    }
                }
            }else{
                echo "<pre>";
                print_r($effective_date_schedual->getErrors());
                die();
            }
            EffectiveDateIntervalSchedule::model()->deleteAllByAttributes(
                array('client_id'=>$clientID)
            );
            /* echo "here one";
              die();
          $clientSchedulerObject  = IntervalScheduler::model()->findByAttributes(array('client_id'=>$clientID , 'product_id'=>$productID));
          if($clientSchedulerObject){
              $clientSchedulerObject->delete();
          }*/
        }


    }



    public static function saveChangedayObjectQuantityFunction_interval($data){


        $start_interval_scheduler = $data['start_interval_scheduler'];

        $today_date = date("Y-m-d");

        if($start_interval_scheduler<=$today_date){

            $client_id = $data['client_id'];
            $product_id = $data['product_id'];
            $intervalSchedule = IntervalScheduler::model()->findByAttributes(array("client_id"=>$client_id , "product_id"=>$product_id));
            if($intervalSchedule){
            }else{
                $intervalSchedule = new IntervalScheduler();
            }

            $intervalSchedule->client_id =$data['client_id'];
            $intervalSchedule->product_id =$data['product_id'];
            $intervalSchedule->interval_days = $data['interval_days'];
            $intervalSchedule->product_quantity = $data['product_quantity'];
            $intervalSchedule->start_interval_scheduler = $data['start_interval_scheduler'];
            $intervalSchedule->default_value = $data['default_value'];
            $intervalSchedule->is_halt =1;
            $intervalSchedule->halt_start_date   =date('Y-m-d', strtotime(' -1 day'));
            $intervalSchedule->halt_end_date   =date('Y-m-d', strtotime(' -1 day'));
            if($intervalSchedule->save()){

                $clientFrequency =  ClientProductFrequency::model()->findByAttributes(array('client_id'=>$client_id , 'product_id'=>$product_id));
                if($clientFrequency){

                    $clientFFID = (($clientFrequency['client_product_frequency']));

                    ClientProductFrequencyQuantity::model()->deleteAllByAttributes(array('client_product_frequency_id'=>$clientFFID));
                    $clientFrequency->delete();
                }

            }else{

            }

            $savechagescheduler =new ChangeSchedulerRecord();
            $savechagescheduler->client_id = $client_id;
            $savechagescheduler->company_id = Yii::app()->user->getState('company_branch_id');
            $savechagescheduler->date = date("Y-m-d"); ;
            $savechagescheduler->change_form= 1 ;
            $savechagescheduler->save();

        }else{
            $client_id = $data['client_id'];
            $product_id = $data['product_id'];
            $start_interval_scheduler = $data['start_interval_scheduler'];

            $interval_Objec = EffectiveDateIntervalSchedule::model()->findByAttributes(
                array(
                    'client_id'=>$client_id,
                    'product_id'=>$product_id,
                )
            );
            if($interval_Objec){

                $interval_Objec->product_quantity =$data['product_quantity'];
                $interval_Objec->interval_days =$data['interval_days'];
                $interval_Objec->start_interval_scheduler =$start_interval_scheduler;

                $interval_Objec->save();

            }else{

                $effective_date_interval_schedule = new EffectiveDateIntervalSchedule();
                $effective_date_interval_schedule->client_id =$client_id;
                $effective_date_interval_schedule->product_id =$product_id;
                $effective_date_interval_schedule->start_interval_scheduler =$start_interval_scheduler;
                $effective_date_interval_schedule->interval_days = $data['interval_days'];
                $effective_date_interval_schedule->product_quantity = $data['product_quantity'];
                $effective_date_interval_schedule->save();

            }

            EffectiveDateSchedule::model()->deleteAllByAttributes(
                array('client_id'=>$client_id)
            );


        }



    }

    public static function removeProductFormSchedualFunction($product){
        $clientID = $product['clientID'];
        $productID = $product['productID'];
        $clientProductFrequency = ClientProductFrequency::model()->findByAttributes(array('client_id'=>$clientID,'product_id'=>$productID));
        if($clientProductFrequency){
            $clientProductFrequencyID = ($clientProductFrequency['client_product_frequency']);
            ClientProductFrequencyQuantity::model()->deleteAllByattributes(array('client_product_frequency_id'=>$clientProductFrequencyID));
            $clientProductFrequency->delete();
        }

        $clientSchedulerObject  = IntervalScheduler::model()->findByAttributes(array('client_id'=>$clientID , 'product_id'=>$productID));
        if($clientSchedulerObject){
            $clientSchedulerObject->delete();
        }

    }

    public static function getClientLedgherReportFunction_newClient($data){


        $select_startDate = $data['startDate'];
        $select_endDate = $data['endDate'];
        $number_Of_date_between_two_day   = common_data::find_days_beteen_two_date($select_startDate,$select_endDate);
        $new_add_customer_list = common_data::new_add_customer_list($select_startDate,$select_endDate);
        $customer_category_id = $data['customer_category_id'];
        $company_branch_id = Yii::app()->user->getState('company_branch_id');

        $query_client ="select  cc.category_name ,ifnull(mrd.drop_or_regular ,0) as drop_or_regular
         , ifnull(sr.reason ,0) as reason , ifnull(mrd.date ,0) as rejectOracceptDate , c.client_id ,c.created_by , c.fullname ,z.name ,c.client_type ,date(c.created_at) as created_date ,c.cell_no_1 ,c.address  from client as c
                          left join zone as z ON z.zone_id = c.zone_id
                           left join make_regualr_drop_client as mrd ON mrd.client_id = c.client_id
                          left join sample_client_drop_reason as sr ON sr.sample_client_drop_reason_id = mrd.sample_client_drop_reason_id
                          LEFT JOIN customer_category AS cc ON cc.customer_category_id =c.customer_category_id
                          where c.is_active =1 and c.client_type=1 and c.company_branch_id = '$company_branch_id' and Date(c.new_create_date) between '$select_startDate' and '$select_endDate' ";
        if($customer_category_id >0){
            $query_client.= "  AND c.customer_category_id = $customer_category_id ";
        }

        // $query_client.="  GROUP BY c.fullname ASC";


        $clientResult = Yii::app()->db->createCommand($query_client)->queryAll();
        $product_query = "select * from product where company_branch_id = $company_branch_id  ";
        $productResult = Yii::app()->db->createCommand($product_query)->queryAll();
        $productprice =  $productResult[0]['price'];
        $client_List = array();
        $client_List[] = '0';
        foreach($clientResult as $value){
            $client_List[] = $value['client_id'];
        }
        $ClientIDlist =implode(',' ,$client_List);
        $query_client_product =" select * from client_product_price  
                                where client_id in ($ClientIDlist) ";
        $clientResult_productPrize = Yii::app()->db->createCommand($query_client_product)->queryAll();
        $productPriceOBject = array();
        foreach ($clientResult_productPrize as $value){
            $clientID = $value['client_id'];
            $productPriceOBject[$clientID] =$value['price'];
        }
        $productPrice = Product::model()->findByAttributes(array('company_branch_id'=>$company_branch_id));
        $master_product_price =  $productPrice['price'];
        $count_of_client =  sizeof($clientResult);
        $end_total_delivery_sum = 0;
        $finalResult = array();
        $result_total_avg_quantity =0;

        $total_price_sum = 0 ;
        $userList = clientData::get_user_list();
        $count_of_client=0;
        foreach($clientResult as $value){

            $created_by = $value['created_by'];
            $oneobject = array();
            $client_id = $value['client_id'] ;
            if(isset($userList[$created_by])){
                $oneobject['creted_by'] = $userList[$created_by] ;
            }
            $oneobject['client_id'] = $value['client_id'];
            $oneobject['client_id'] = $value['client_id'];
            $oneobject['fullname'] = $value['fullname'];
            $oneobject['address'] = $value['address'];
            /* first delivery*/
            $query_firsr_delivey = "SELECT * FROM delivery AS d
                      WHERE d.client_id='$client_id' AND d.DATE
                      BETWEEN '$select_startDate' AND '$select_endDate'
                      LIMIT 1";
            $result_first_delivery = Yii::app()->db->createCommand($query_firsr_delivey)->queryAll();
            $first_day = '';
            if(sizeof($result_first_delivery)>0){
                $first_day =  $result_first_delivery[0]['date'];
            }
            // $oneobject['created_date'] = $value['created_date'];
            $oneobject['created_date'] = $first_day ;


            /* last delivery delivery*/
            $query_last_delivey = "SELECT * FROM delivery AS d
                      WHERE d.client_id='$client_id' AND d.DATE
                      BETWEEN '$select_startDate' AND '$select_endDate'
                      ORDER BY d.delivery_id DESC
                      LIMIT 1";
            $result_last_delivery = Yii::app()->db->createCommand($query_last_delivey)->queryAll();
            $last_day = '';
            if(sizeof($result_last_delivery)>0){
                $last_day =  $result_last_delivery[0]['date'];

            }
            // $number_Of_date = common_data::find_days_beteen_two_date($first_day ,$last_day);
            // $oneobject['created_date'] = $value['created_date'];
            $oneobject['last_delivery'] = $last_day ;
            $oneobject['zone_name'] = $value['name'];
            $oneobject['cell_no_1'] = $value['cell_no_1'];
            $oneobject['category_name'] = $value['category_name'];
            $startDate = $value['created_date'];
            if($value['drop_or_regular'] >0){
                $endDate = $value['rejectOracceptDate'];
            }else{
                $endDate =  date("Y-m-d");
            }
            $query = "select  IFNULL(sum(dd.amount),0) as amount ,
                   IFNULL(sum(dd.quantity),0) as deliveryQuantity_sum  from delivery as d
                   left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                   where  d.client_id ='$client_id' and dd.date BETWEEN  '$select_startDate' AND '$select_endDate'";
            $quantity_Result = Yii::app()->db->createCommand($query)->queryAll();
            $amount = $quantity_Result[0]['amount'];

            $deliveryQuantity_sum =  $quantity_Result[0]['deliveryQuantity_sum'];

            if($deliveryQuantity_sum >0){
                $deliveryQuantity_sum_amount_rate = $amount / $deliveryQuantity_sum ;
            }else{
                $deliveryQuantity_sum_amount_rate =0 ;
            }


            $number_Of_date   = common_data::find_days_beteen_two_date($first_day,$last_day);



            $end_total_delivery_sum = $end_total_delivery_sum +$deliveryQuantity_sum;
            if(isset($new_add_customer_list[$client_id])){
                // $number_Of_date = $new_add_customer_list[$client_id];
            }
            if($number_Of_date >0){
                $oneobject['amount'] = $deliveryQuantity_sum/$number_Of_date ;
                $oneobject['quantity'] = $deliveryQuantity_sum/$number_Of_date;
                $result_total_avg_quantity = $result_total_avg_quantity + $deliveryQuantity_sum/$number_Of_date ;
            }else{
                $oneobject['amount'] = 0;
                $oneobject['quantity'] =0;
            }
            $oneobject['total_quantity'] = $deliveryQuantity_sum;


            if(isset( $productPriceOBject[$client_id])){
                $oneobject['deliveryQuantity_unit_price'] = $productPriceOBject[$client_id] ;
            }else{
                $oneobject['deliveryQuantity_unit_price'] = $master_product_price ;
            }

            $oneobject['deliveryQuantity_unit_price'] = $deliveryQuantity_sum_amount_rate ;
            $total_price_sum = $total_price_sum + $oneobject['deliveryQuantity_unit_price'] ;

            $query_followUP = "select * from follow_up fp
                  where fp.client_id = '$client_id'
                  order by fp.follow_up_id DESC limit 1";

            $resultFollowup = Yii::app()->db->createCommand($query_followUP)->queryAll();
            if($resultFollowup){
                $oneobject['follow_up_date'] = $resultFollowup[0]['date'];
                $oneobject['remarks'] = $resultFollowup[0]['remarks'];
            }else{
                $oneobject['follow_up_date'] ='' ;
                $oneobject['remarks'] = '';
            }
            if($value['client_type']==1){
                $oneobject['convert'] = 'Yes';
            }else{
                $oneobject['convert'] = 'No';
            }
            if($value['drop_or_regular'] == 1){
                $oneobject['make_order']= $value['rejectOracceptDate'];
            }
            if($value['drop_or_regular'] == 2){
                $oneobject['drop_reason'] =$value['reason'];
            }
            $oneobject['number_Of_date'] =$number_Of_date;

            $oneobject['show_data'] =true;

            if($deliveryQuantity_sum>0){
                $count_of_client++;
                $finalResult[] =$oneobject ;
            }

        }

        $oneobject = array();


        if($count_of_client>0){


            $oneobject['show_data'] = false;
            $oneobject['created_date'] = "2099-01-01";
            $oneobject['total_quantity'] = $end_total_delivery_sum/$count_of_client ;
            $oneobject['quantity'] = $result_total_avg_quantity ;
            $oneobject['address'] = "Total" ;
            $oneobject['fullname'] = '';
            $oneobject['deliveryQuantity_unit_price'] = $total_price_sum/$count_of_client;
            $finalResult[] =$oneobject ;
        }
        $data = array();

        $data['finalResult'] =$finalResult ;
        $box_data = array();
        $box_data['count_of_client'] = $count_of_client ;
        $box_data['end_total_delivery_sum'] = $end_total_delivery_sum ;
        if($count_of_client>0){
            $box_data['avg_sale_per_customer'] = $end_total_delivery_sum/$count_of_client ;
        }else{
            $box_data['avg_sale_per_customer'] =0 ;
        }

        if($number_Of_date_between_two_day >0){
            $box_data['avg_sale_per_day'] = $end_total_delivery_sum/$number_Of_date_between_two_day ;
        }else{
            $box_data['avg_sale_per_day'] = 0;
        }

        $data['box_data'] =$box_data ;
        return $data;
    }
    public static function getClientLedgherReportFunction_sampleCustomer($data){

        $select_startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $number_Of_date_between_two_day   = common_data::find_days_beteen_two_date($select_startDate,$endDate);
        $new_add_customer_list = common_data::new_add_customer_list($select_startDate,$endDate);
        $customer_category_id = $data['customer_category_id'];
        $company_branch_id = Yii::app()->user->getState('company_branch_id');

        $query_client ="select  cc.category_name ,ifnull(mrd.drop_or_regular ,0) as drop_or_regular ,
                          ifnull(sr.reason ,0) as reason , ifnull(mrd.date ,0) as rejectOracceptDate , c.client_id,c.created_by , c.fullname ,z.name ,c.client_type ,date(c.created_at) as created_date ,c.cell_no_1 ,c.address,c.deactive_reason  from client as c
                          left join zone as z ON z.zone_id = c.zone_id
                           left join make_regualr_drop_client as mrd ON mrd.client_id = c.client_id
                          left join sample_client_drop_reason as sr ON sr.sample_client_drop_reason_id = mrd.sample_client_drop_reason_id
                          LEFT JOIN customer_category AS cc ON cc.customer_category_id =c.customer_category_id
                          where  c.client_type=2 and c.company_branch_id = '$company_branch_id' and Date(c.new_create_date) between '$select_startDate' and '$endDate' ";

        if($customer_category_id >0){
            $query_client.= "  AND c.customer_category_id = '$customer_category_id' ";
        }
        $query_client.="  GROUP BY c.fullname ASC";


        $clientResult = Yii::app()->db->createCommand($query_client)->queryAll();
        $product_query = "select * from product where company_branch_id = $company_branch_id  ";
        $productResult = Yii::app()->db->createCommand($product_query)->queryAll();
        $productprice =  $productResult[0]['price'];
        $client_List = array();
        $client_List[] = '0';
        foreach($clientResult as $value){

            $client_List[] = $value['client_id'];
        }
        $ClientIDlist =implode(',' ,$client_List);

        $query_client_product =" select * from client_product_price  
                                where client_id in ($ClientIDlist) ";

        $clientResult_productPrize = Yii::app()->db->createCommand($query_client_product)->queryAll();

        $productPriceOBject = array();
        foreach ($clientResult_productPrize as $value){
            $clientID = $value['client_id'];

            $productPriceOBject[$clientID] =$value['price'];
        }

        $productPrice = Product::model()->findByAttributes(array('company_branch_id'=>$company_branch_id));

        $master_product_price =  $productPrice['price'];

        $count_of_client =  sizeof($clientResult);
        $end_total_delivery_sum = 0;
        $result_total_avg_quantity =0;
        $total_price_sum = 0;
        $finalResult = array();

        $userList = clientData::get_user_list();
        $count_of_client =0;
        foreach($clientResult as $value){

            $oneobject = array();
            $client_id = $value['client_id'] ;

            $created_by = $value['created_by'];
            if(isset($userList[$created_by])){
                $oneobject['creted_by'] = $userList[$created_by] ;
            }

            $oneobject['client_id'] = $value['client_id'];
            $oneobject['fullname'] = $value['fullname'];
            $oneobject['address'] = $value['address'];

            $query_firsr_delivey = "SELECT * FROM delivery AS d
                      WHERE d.client_id='$client_id' AND d.DATE
                      BETWEEN '$select_startDate' AND '$endDate'
                      LIMIT 1";

            $result_first_delivery = Yii::app()->db->createCommand($query_firsr_delivey)->queryAll();
            $first_day = '';
            if(sizeof($result_first_delivery)>0){
                $first_day =  $result_first_delivery[0]['date'];
            }
            // $oneobject['created_date'] = $value['created_date'];
            $oneobject['created_date'] = $first_day ;

            /* last delivery delivery*/
            $query_last_delivey = "SELECT * FROM delivery AS d
                      WHERE d.client_id='$client_id' AND d.DATE
                      BETWEEN '$select_startDate' AND '$endDate'
                      ORDER BY d.delivery_id DESC
                      LIMIT 1";


            $result_last_delivery = Yii::app()->db->createCommand($query_last_delivey)->queryAll();
            $last_day = '';
            if(sizeof($result_last_delivery)>0){
                $last_day =  $result_last_delivery[0]['date'];
            }
            // $oneobject['created_date'] = $value['created_date'];
            $oneobject['last_delivery'] = $last_day ;

            $number_Of_date   = common_data::find_days_beteen_two_date($first_day,$last_day);

            $oneobject['created_date'] = $value['created_date'];
            $oneobject['zone_name'] = $value['name'];
            $oneobject['cell_no_1'] = $value['cell_no_1'];
            $oneobject['category_name'] = $value['category_name'];
            $startDate = $value['created_date'];
            if($value['drop_or_regular'] >0){
                $endDate = $value['rejectOracceptDate'];

            }else{
                $endDate =  date("Y-m-d");
            }

            $query = "select  IFNULL(sum(dd.amount),0) as amount , IFNULL(sum(dd.quantity),0) as deliveryQuantity_sum  from delivery as d
                  left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                  where  d.client_id ='$client_id' and dd.date BETWEEN  '$select_startDate' AND '$endDate' ";
            $quantity_Result = Yii::app()->db->createCommand($query)->queryAll();

            $amount = $quantity_Result[0]['amount'];
            $deliveryQuantity_sum =  $quantity_Result[0]['deliveryQuantity_sum'];

            if($deliveryQuantity_sum >0){
                $deliveryQuantity_sum_amount_rate = $amount / $deliveryQuantity_sum ;
            }else{
                $deliveryQuantity_sum_amount_rate =0 ;
            }

            $end_total_delivery_sum = $end_total_delivery_sum +$deliveryQuantity_sum;
            $oneobject['amount'] = $amount ;
            if($number_Of_date>0){
                $oneobject['deliveryQuantity_sum'] = $deliveryQuantity_sum/$number_Of_date ;
                $result_total_avg_quantity = $result_total_avg_quantity+$deliveryQuantity_sum/$number_Of_date ;
            }else{
                $oneobject['deliveryQuantity_sum'] =0;
            }
            if(isset($new_add_customer_list[$client_id])){
                $number_Of_date = $new_add_customer_list[$client_id];
            }
            if($number_Of_date>0){
                $oneobject['amount'] = $deliveryQuantity_sum/$number_Of_date ;
                $oneobject['quantity'] = $deliveryQuantity_sum/$number_Of_date;
            }else{
                $oneobject['amount'] = 0;
                $oneobject['quantity'] = 0;
            }
            $oneobject['total_quantity'] = $deliveryQuantity_sum;
            if(isset( $productPriceOBject[$client_id])){
                $oneobject['deliveryQuantity_unit_price'] = $productPriceOBject[$client_id] ;
            }else{
                $oneobject['deliveryQuantity_unit_price'] = $master_product_price ;
            }

            $oneobject['deliveryQuantity_unit_price'] = $deliveryQuantity_sum_amount_rate ;

            $total_price_sum = $total_price_sum + $oneobject['deliveryQuantity_unit_price'] ;
            $query_followUP = "select * from follow_up fp
                  where fp.client_id = '$client_id'
                  order by fp.follow_up_id DESC limit 1";
            $resultFollowup = Yii::app()->db->createCommand($query_followUP)->queryAll();
            if($resultFollowup){
                $oneobject['follow_up_date'] = $resultFollowup[0]['date'];
                $oneobject['remarks'] = $resultFollowup[0]['remarks'];
            }else{
                $oneobject['follow_up_date'] ='' ;
                $oneobject['remarks'] = '';
            }

            $query_drop_reason = " SELECT sm.reason  FROM make_regualr_drop_client AS m
             LEFT JOIN sample_client_drop_reason AS sm ON m.sample_client_drop_reason_id = sm.sample_client_drop_reason_id
             WHERE m.client_id = '$client_id' AND m.drop_or_regular =2 ";

            $reason_reason = Yii::app()->db->createCommand($query_drop_reason)->queryAll();

            if(sizeof($reason_reason)>0){
                $oneobject['convert'] ='No';
                $oneobject['drop_reason'] =$reason_reason[0]['reason'];
            }else{
                $oneobject['convert'] ='';
                $oneobject['drop_reason'] =$value['deactive_reason'];
            }

            $oneobject['show_data'] = true;

            /*if($value['client_type']==1){
                $oneobject['convert'] = 'Yes';
            }else{
                $oneobject['convert'] = 'No';
            }
            if($value['drop_or_regular'] == 1){
                $oneobject['make_order']= $value['rejectOracceptDate'];
            }*/
            /*if($value['drop_or_regular'] == 2){
                $oneobject['drop_reason'] =$value['reason'];
            }*/
            // $finalResult[] =$oneobject ;

            if($deliveryQuantity_sum>0){
                $count_of_client++;
                $finalResult[] =$oneobject ;
            }
        }
        if($count_of_client){
            $oneobject = array();

            $oneobject['show_data'] = false;

            $oneobject['created_date'] = "2099-01-01";

            $oneobject['total_quantity'] = $end_total_delivery_sum/$count_of_client ;
            $oneobject['deliveryQuantity_sum'] = $result_total_avg_quantity ;
            $oneobject['address'] = "Total" ;
            $oneobject['fullname'] = '';
            $oneobject['deliveryQuantity_unit_price'] = $total_price_sum/$count_of_client;
            $finalResult[] =$oneobject ;
        }

        $data = array();

        $data['finalResult'] =$finalResult ;
        $box_data = array();
        $box_data['count_of_client'] = $count_of_client ;
        $box_data['end_total_delivery_sum'] = $end_total_delivery_sum ;
        if($count_of_client>0){
            $box_data['avg_sale_per_customer'] = $end_total_delivery_sum/$count_of_client ;
        }else{
            $box_data['avg_sale_per_customer'] = 0 ;
        }

        if($number_Of_date_between_two_day>0){
            $box_data['avg_sale_per_day'] = $end_total_delivery_sum/$number_Of_date_between_two_day ;
        }else{
            $box_data['avg_sale_per_day'] =0 ;
        }

        $data['box_data'] =$box_data ;
        return $data;


    }
    public static function getClientLedgherReportFunction_dropCustomer($data){

        $select_startDate = $data['startDate'];

        $select_endDate = $data['endDate'];

        $number_Of_date_between_two_day   = common_data::find_days_beteen_two_date($select_startDate,$select_endDate);

        $customer_category_id = $data['customer_category_id'];

        $company_branch_id = Yii::app()->user->getState('company_branch_id');


        $drop_query = "SELECT m.client_id FROM make_regualr_drop_client AS m
            LEFT JOIN client AS c ON c.client_id = m.client_id
            WHERE m.date BETWEEN '$select_startDate' AND '$select_endDate' AND m.company_id = '$company_branch_id'
             and m.drop_or_regular=2  AND c.is_active =0";




        $drop_result = Yii::app()->db->createCommand($drop_query)->queryAll();

        $client_OBject = array();
        $client_OBject[]=0;
        foreach ($drop_result as $value){
            $client_OBject[] = $value['client_id'];
        }

        $client_ids = implode(',',$client_OBject);
        $query_client ="select  cc.category_name ,ifnull(mrd.drop_or_regular ,0) as drop_or_regular , ifnull(sr.reason ,0) as reason , ifnull(mrd.date ,0) as rejectOracceptDate , c.client_id 
       , c.fullname,c.created_by ,z.name ,c.client_type ,date(c.created_at) as created_date ,c.cell_no_1 ,c.address,c.deactive_reason  from client as c
                          left join zone as z ON z.zone_id = c.zone_id
                           left join make_regualr_drop_client as mrd ON mrd.client_id = c.client_id
                          left join sample_client_drop_reason as sr ON sr.sample_client_drop_reason_id = mrd.sample_client_drop_reason_id
                          LEFT JOIN customer_category AS cc ON cc.customer_category_id =c.customer_category_id
                          where  (c.company_branch_id = '$company_branch_id' 
                         and c.is_active =0 and c.created_at  BETWEEN '$select_startDate' AND '$select_endDate')
                         OR (c.client_id in ($client_ids))";





        // and  c.is_active =0 and c.created_at  BETWEEN '$select_startDate' AND '$select_endDate' ";

        if($customer_category_id >0){
            $query_client.= "  AND c.customer_category_id = '$customer_category_id' ";
        }
        $query_client.="  GROUP BY c.fullname ASC";



        $clientResult = Yii::app()->db->createCommand($query_client)->queryAll();
        $product_query = "select * from product where company_branch_id = $company_branch_id  ";
        $productResult = Yii::app()->db->createCommand($product_query)->queryAll();
        $productprice =  $productResult[0]['price'];
        $client_List = array();
        $client_List[] = '0';
        foreach($clientResult as $value){

            $client_List[] = $value['client_id'];
        }
        $ClientIDlist =implode(',' ,$client_List);

        $query_client_product =" select * from client_product_price  
                                where client_id in ($ClientIDlist) ";

        $clientResult_productPrize = Yii::app()->db->createCommand($query_client_product)->queryAll();

        $productPriceOBject = array();
        foreach ($clientResult_productPrize as $value){
            $clientID = $value['client_id'];

            $productPriceOBject[$clientID] =$value['price'];
        }

        $productPrice = Product::model()->findByAttributes(array('company_branch_id'=>$company_branch_id));

        $master_product_price =  $productPrice['price'];

        $count_of_client =  sizeof($clientResult);
        $end_total_delivery_sum = 0;
        $result_total_avg_quantity = 0;
        $total_price_sum =0 ;
        $finalResult = array();

        $userList = clientData::get_user_list();
        $count_of_client=0;
        foreach($clientResult as $value){
            $oneobject = array();
            $client_id = $value['client_id'] ;

            $created_by = $value['created_by'];
            if(isset($userList[$created_by])){
                $oneobject['creted_by'] = $userList[$created_by] ;
            }

            $oneobject['client_id'] = $value['client_id'];
            $oneobject['fullname'] = $value['fullname'];
            $oneobject['address'] = $value['address'];
            $query_firsr_delivey = "SELECT * FROM delivery AS d
                      WHERE d.client_id='$client_id' AND d.DATE
                      BETWEEN '$select_startDate' AND '$select_endDate'
                      LIMIT 1";
            $result_first_delivery = Yii::app()->db->createCommand($query_firsr_delivey)->queryAll();
            $first_day = '';
            if(sizeof($result_first_delivery)>0){
                $first_day =  $result_first_delivery[0]['date'];
            }
            // $oneobject['created_date'] = $value['created_date'];
            $oneobject['created_date'] = $first_day ;


            /* last delivery delivery*/
            $query_last_delivey = "SELECT * FROM delivery AS d
                      WHERE d.client_id='$client_id' AND d.DATE
                      BETWEEN '$select_startDate' AND '$select_endDate'
                      ORDER BY d.delivery_id DESC
                      LIMIT 1";


            $result_last_delivery = Yii::app()->db->createCommand($query_last_delivey)->queryAll();
            $last_day = '';
            if(sizeof($result_last_delivery)>0){
                $last_day =  $result_last_delivery[0]['date'];
            }
            // $oneobject['created_date'] = $value['created_date'];
            $oneobject['last_delivery'] = $last_day ;

            $number_Of_date   = common_data::find_days_beteen_two_date($first_day,$last_day);

            $oneobject['zone_name'] = $value['name'];
            $oneobject['cell_no_1'] = $value['cell_no_1'];
            $oneobject['category_name'] = $value['category_name'];
            $startDate = $value['created_date'];
            if($value['drop_or_regular'] >0){
                $endDate = $value['rejectOracceptDate'];
            }else{
                $endDate =  date("Y-m-d");
            }
            $query = "select  IFNULL(sum(dd.amount),0) as amount , IFNULL(sum(dd.quantity),0) as deliveryQuantity_sum  from delivery as d
                  left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                  where  d.client_id ='$client_id' and dd.date BETWEEN  '$select_startDate' AND '$select_endDate'";

            $quantity_Result = Yii::app()->db->createCommand($query)->queryAll();

            $amount = $quantity_Result[0]['amount'];

            $deliveryQuantity_sum =  $quantity_Result[0]['deliveryQuantity_sum'];

            if($deliveryQuantity_sum >0){
                $deliveryQuantity_sum_amount_rate = $amount / $deliveryQuantity_sum ;
            }else{
                $deliveryQuantity_sum_amount_rate = 0 ;
            }



            $end_total_delivery_sum = $end_total_delivery_sum +$deliveryQuantity_sum;

            $oneobject['amount'] = $amount ;
            if($number_Of_date >0){
                $oneobject['quantity'] = $deliveryQuantity_sum/$number_Of_date;
                $result_total_avg_quantity = $result_total_avg_quantity +$deliveryQuantity_sum/$number_Of_date;

            }else{
                $oneobject['quantity'] = 0;
            }

            $oneobject['total_quantity'] = $deliveryQuantity_sum;

            if(isset( $productPriceOBject[$client_id])){
                $oneobject['deliveryQuantity_unit_price'] = $productPriceOBject[$client_id] ;

            }else{
                $oneobject['deliveryQuantity_unit_price'] = $master_product_price ;

            }

            $oneobject['deliveryQuantity_unit_price'] = $deliveryQuantity_sum_amount_rate ;

            $total_price_sum = $total_price_sum + $oneobject['deliveryQuantity_unit_price'] ;
            $query_followUP = "select * from follow_up fp
                  where fp.client_id = '$client_id'
                  order by fp.follow_up_id DESC limit 1";
            $resultFollowup = Yii::app()->db->createCommand($query_followUP)->queryAll();
            if($resultFollowup){
                $oneobject['follow_up_date'] = $resultFollowup[0]['date'];
                $oneobject['remarks'] = $resultFollowup[0]['remarks'];
            }else{
                $oneobject['follow_up_date'] ='' ;
                $oneobject['remarks'] = '';
            }
            /*if($value['client_type']==1){
                $oneobject['convert'] = 'Yes';
            }else{
                $oneobject['convert'] = 'No';
            }*/

            $query_drop_reason = " SELECT sm.reason  FROM make_regualr_drop_client AS m
             LEFT JOIN sample_client_drop_reason AS sm ON m.sample_client_drop_reason_id = sm.sample_client_drop_reason_id
             WHERE m.client_id = '$client_id' AND m.drop_or_regular =2 ";

            $reason_reason = Yii::app()->db->createCommand($query_drop_reason)->queryAll();

            if(sizeof($reason_reason)>0){
                $oneobject['convert'] ='No';
                $oneobject['drop_reason'] =$reason_reason[0]['reason'];
            }else{
                $oneobject['convert'] ='';
                $oneobject['drop_reason'] =$value['deactive_reason'];
            }


            if($value['drop_or_regular'] == 1){
                $oneobject['make_order']= $value['rejectOracceptDate'];
            }
            $oneobject['show_data'] =true;
            /*if($value['drop_or_regular'] == 2){
                $oneobject['drop_reason'] =$value['reason'];
            }*/
            if($deliveryQuantity_sum>0){
                $count_of_client++;
                $finalResult[] =$oneobject ;
            }



        }

        if($count_of_client>0){
            $oneobject = array();

            $oneobject['show_data'] = false;

            $oneobject['created_date'] = "2099-01-01";
            $oneobject['total_quantity'] = $end_total_delivery_sum/$count_of_client ;
            $oneobject['quantity'] = $result_total_avg_quantity ;
            $oneobject['address'] = "Total" ;
            $oneobject['fullname'] = '';
            $oneobject['deliveryQuantity_unit_price'] = $total_price_sum/$count_of_client;
            $finalResult[] =$oneobject ;
        }

        $data = array();

        $data['finalResult'] =$finalResult ;
        $box_data = array();
        $box_data['count_of_client'] = $count_of_client ;
        $box_data['end_total_delivery_sum'] = $end_total_delivery_sum;

        if($count_of_client>0){

            $box_data['avg_sale_per_customer'] = $end_total_delivery_sum/$count_of_client ;
        }else{
            $box_data['avg_sale_per_customer'] =0;

        }
        if($number_Of_date_between_two_day>0){
            $box_data['avg_sale_per_day'] = $end_total_delivery_sum/$number_Of_date_between_two_day ;
        }else{
            $box_data['avg_sale_per_day'] = 0 ;
        }



        $data['box_data'] =$box_data ;
        return $data;


    }
    public static function getClientLedgherReportFunction_for_mobile($data){

        $clientId = $data['clientID'];
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];

        $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
         where d.client_id = $clientId AND d.date < '$startDate' ";

        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totaldeliverySum = $deliveryResult[0]['deliverySum'];

        $queryDelivery ="Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
            where pm.client_id = $clientId AND pm.date < '$startDate' ";

        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totalRemaining = $deliveryResult[0]['remainingAmount'];
        $finalData = array();
        $finalData['openeningStock'] = $totaldeliverySum ;
        $finalData['totalRemaining'] = $totalRemaining ;

        $openingTotalBalance = $totalRemaining - $totaldeliverySum   ;



        $reportObject = array();
        $x= strtotime($startDate);
        $y= strtotime($endDate);
        $reportData = array();
        $oneDayData = array();
        $oneDayData['discription'] = 'OPENING BALANCE' ;
        $oneDayData['date'] = $startDate ;
        $oneDayData['delivery'] = (int)$totaldeliverySum ;
        $oneDayData['reciveAmount'] = (int)$totalRemaining ;
        $oneDayData['balance'] = (int)$openingTotalBalance ;
        $reportData[] = $oneDayData ;



        while($x < ($y+8640)){

            $oneDayData = array();
            $selectDate = date("Y-m-d", $x);

            $oneDayData['date'] = $selectDate ;
            $x += 86400;
            $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d 
                where d.client_id = $clientId AND d.date = '$selectDate' ";

            $queryDelivery ="select IFNULL(sum(dd.amount),0) as deliverySum ,p.name as product_name ,p.unit , IFNULL(sum(dd.quantity),0) as deliveryQuantity_sum  from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where d.date = '$selectDate' and d.client_id =$clientId
                group by dd.product_id ";

            $deliveryOneDayResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
            $todaydilverySum = 0 ;
            $deliveryProduct ='';
            foreach($deliveryOneDayResult as $value){
                if($deliveryProduct !=''){
                    $deliveryProduct .= ',';
                }
                $todaydilverySum = $todaydilverySum + $value['deliverySum'];


                $deliveryProduct .= $value['product_name']." ".$value['deliveryQuantity_sum']." ".$value['unit'];
            }




            //  $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];
            if($todaydilverySum !=0){

                //  $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];

                $todayPaymentSum = 0;
                $oneDayBalanceCount =$todayPaymentSum - $todaydilverySum;
                $openingTotalBalance = $openingTotalBalance + $oneDayBalanceCount;
                $oneDayData['discription'] = $deliveryProduct ;
                $oneDayData['reference_number'] = '';
                $oneDayData['delivery'] =  (int)$todaydilverySum ;
                $oneDayData['reciveAmount'] = (int)$todayPaymentSum ;
                $oneDayData['balance'] = (int)$openingTotalBalance ;
                $reportData[] = $oneDayData ;

            }
            $queryPayment ="Select IFNULL((pm.amount_paid) , 0) as payAmountSum ,pm.reference_number from payment_master as pm
                where pm.client_id ='$clientId' AND pm.date = '$selectDate'";

            $paymentOneDayResult = Yii::app()->db->createCommand($queryPayment)->queryAll();



            foreach($paymentOneDayResult as $value){

                $todayPaymentSum = ($value['payAmountSum']);
                if($todayPaymentSum != 0){
                    $todaydilverySum = 0;
                    $todayPaymentSum = ($value['payAmountSum']);
                    $oneDayBalanceCount = $todayPaymentSum - $todaydilverySum  ;
                    $openingTotalBalance = $oneDayBalanceCount + $openingTotalBalance;

                    $oneDayData['discription'] = 'Amount Received';

                    $oneDayData['reference_number'] = $value['reference_number'];
                    $oneDayData['delivery'] = (int)$todaydilverySum ;
                    $oneDayData['reciveAmount'] = (int)$todayPaymentSum ;
                    $oneDayData['balance'] = (int)$openingTotalBalance ;

                    $reportData[] = $oneDayData ;
                }

            }



        }


        $querySumery ="select IFNULL(sum(dd.amount),0) as deliverySum ,p.name as product_name ,p.unit , IFNULL(sum(dd.quantity),0) as deliveryQuantity_sum  from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where  d.client_id ='$clientId' and d.date between '$startDate' and '$endDate' and p.unit is not null
                group by dd.product_id ";

        $querySumeryResult = Yii::app()->db->createCommand($querySumery)->queryAll();

        $resultArray = array();
        $totalDelivery = 0;
        foreach ($reportData as $value){
            $totalDelivery = $totalDelivery +  $value['delivery'];
        }

        $resultArray['ledgerData'] = $reportData;
        $resultArray['sumery'] = $querySumeryResult;
        $resultArray['startDate'] = $startDate;
        $resultArray['endDate'] = $endDate;
        $resultArray['totalDelivery'] = $totalDelivery;

        return json_encode($resultArray);
    }
    public static function getClientLedgherReportFunction($data){
        //mit
        $clientId = $data['clientID'];
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];

        $bad_debt_opeening_amount = bad_debt_record_data::bad_debt_opeening_amount($data);

        $bad_debt_amount = bad_debt_record_data::bad_debt_record_list($data);



        $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
            where d.client_id = $clientId AND d.date < '$startDate' ";



        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totaldeliverySum = $deliveryResult[0]['deliverySum'];


        $queryDelivery ="Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
            where pm.client_id = $clientId AND pm.date < '$startDate' ";



        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totalRemaining = $deliveryResult[0]['remainingAmount'];
        $finalData = array();
        $finalData['openeningStock'] = $totaldeliverySum ;
        $finalData['totalRemaining'] = $totalRemaining ;


        $openingTotalBalance =  $totaldeliverySum - $totalRemaining -$bad_debt_opeening_amount ;


        $reportObject = array();
        $x= strtotime($startDate);
        $y= strtotime($endDate);
        $reportData = array();
        $oneDayData = array();
        $oneDayData['discription'] = 'OPENING BALANCE' ;
        $oneDayData['date'] = $startDate ;
        $oneDayData['delivery'] = $totaldeliverySum ;
        $oneDayData['reciveAmount'] = $totalRemaining ;
        $oneDayData['balance'] = $openingTotalBalance ;
        $reportData[] = $oneDayData ;



        while($x < ($y+8640)){

            $oneDayData = array();
            $selectDate = date("Y-m-d", $x);

            $oneDayData['date'] = $selectDate ;
            $x += 86400;

            $today_bad_debt = bad_debt_record_data::today_total_bad_debt($clientId,$selectDate);



            $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d 
                where d.client_id = $clientId AND d.date = '$selectDate' ";

            $queryDelivery ="select IFNULL(sum(dd.amount),0) as deliverySum ,p.name as product_name ,p.unit , IFNULL(sum(dd.quantity),0) as deliveryQuantity_sum  from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where d.date = '$selectDate' and d.client_id =$clientId
                group by dd.product_id ";

            $deliveryOneDayResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
            $todaydilverySum = 0 ;
            $deliveryProduct ='';
            foreach($deliveryOneDayResult as $value){
                if($deliveryProduct !=''){
                    $deliveryProduct .= ', ';
                }
                $todaydilverySum = $todaydilverySum + $value['deliverySum'];
                $deliveryProduct .= $value['product_name']." ".$value['deliveryQuantity_sum']." ".$value['unit'];
            }

            ;



            //  $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];
            if($todaydilverySum !=0){
                //  $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];
                $todayPaymentSum = 0;
                $oneDayBalanceCount =$todaydilverySum-$todayPaymentSum ;
                $openingTotalBalance = $openingTotalBalance + $oneDayBalanceCount;
                $oneDayData['discription'] = $deliveryProduct ;
                $oneDayData['reference_number'] = '';
                $oneDayData['delivery'] = $todaydilverySum ;
                $oneDayData['reciveAmount'] = $todayPaymentSum ;
                $oneDayData['balance'] = $openingTotalBalance ;
                $reportData[] = $oneDayData ;
            }
            $queryPayment ="Select IFNULL((pm.amount_paid) , 0) as payAmountSum ,pm.reference_number from payment_master as pm
                where pm.client_id ='$clientId' AND pm.date = '$selectDate'";

            $paymentOneDayResult = Yii::app()->db->createCommand($queryPayment)->queryAll();



            foreach($paymentOneDayResult as $value){

                $todayPaymentSum = ($value['payAmountSum']);


                if($todayPaymentSum != 0){
                    $todaydilverySum = 0;
                    $todayPaymentSum = ($value['payAmountSum']);
                    $oneDayBalanceCount = $todaydilverySum - $todayPaymentSum  ;
                    $openingTotalBalance = $oneDayBalanceCount + $openingTotalBalance;
                    $oneDayData['discription'] = 'RECEIVED' ;
                    $oneDayData['reference_number'] = $value['reference_number'];
                    $oneDayData['delivery'] = $todaydilverySum ;
                    $oneDayData['reciveAmount'] = $todayPaymentSum ;
                    $oneDayData['balance'] = $openingTotalBalance ;

                    $reportData[] = $oneDayData ;
                }

            }

            foreach ($today_bad_debt as $value){

                $todaydilverySum = 0;
                $todayPaymentSum = $value['amount'];
                $oneDayBalanceCount = $todaydilverySum - $todayPaymentSum  ;
                $openingTotalBalance = $oneDayBalanceCount + $openingTotalBalance;
                $oneDayData['discription'] = 'Bad Debt' ;
                $oneDayData['reference_number'] = $value['reference_no'];
                $oneDayData['delivery'] = $todaydilverySum ;
                $oneDayData['reciveAmount'] = $todayPaymentSum ;
                $oneDayData['balance'] = $openingTotalBalance ;

                $reportData[] = $oneDayData ;
            }



        }


        $querySumery ="select IFNULL(sum(dd.amount),0) as deliverySum ,p.name as product_name ,p.unit , IFNULL(sum(dd.quantity),0) as deliveryQuantity_sum  from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where  d.client_id ='$clientId' and d.date between '$startDate' and '$endDate' and p.unit is not null
                group by dd.product_id ";

        $querySumeryResult = Yii::app()->db->createCommand($querySumery)->queryAll();

        $resultArray = array();
        $totalDelivery = 0;
        foreach ($reportData as $value){
            $totalDelivery = $totalDelivery +  $value['delivery'];
        }

        $resultArray['ledgerData'] = $reportData;
        $resultArray['sumery'] = $querySumeryResult;
        $resultArray['startDate'] = $startDate;
        $resultArray['endDate'] = $endDate;
        $resultArray['totalDelivery'] = $totalDelivery;
        $resultArray['bad_debt_amount'] = $bad_debt_amount;

        return json_encode($resultArray);
    }

    public static function getClientLedgherReportFunction_PrintBill_all($data){


        $clientId = $data['clientID'];
        $clientObject_forPrint =Client::model()->findByPk(intval($clientId))->attributes;
        //  $currentBalance = APIData::calculateFinalBalance($clientId);
        $currentBalance = 0;
        $startDate = $data['startDate'];

        $endDate = $data['endDate'];
        $nextMonthDate = date('Y-m-d', strtotime('5 day', strtotime($endDate)));

        $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
            where d.client_id = $clientId AND d.date < '$startDate' ";
        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totaldeliverySum = $deliveryResult[0]['deliverySum'];

        $queryDelivery ="Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
            where pm.client_id = $clientId AND pm.date < '$startDate' ";
        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totalRemaining = $deliveryResult[0]['remainingAmount'];
        $finalData = array();

        $finalData['openeningStock'] = $totaldeliverySum ;
        $finalData['totalRemaining'] = $totalRemaining ;
        $openingTotalBalance =  $totaldeliverySum - $totalRemaining ;

        $reportObject = array();
        $x= strtotime($startDate);
        $y= strtotime($endDate);
        $reportData = array();
        $oneDayData = array();
        $oneDayData['discription'] = 'OPENING BALANCE' ;
        $oneDayData['date'] = $startDate ;

        $oneDayData['delivery'] = $totaldeliverySum ;
        $oneDayData['reciveAmount'] = $totalRemaining ;
        $oneDayData['balance'] = $openingTotalBalance ;
        $reportData[] = $oneDayData ;

        while($x < ($y+8640)){

            $oneDayData = array();
            $selectDate = date("Y-m-d", $x);

            $oneDayData['date'] = $selectDate ;
            $x += 86400;
            $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d 
                where d.client_id = $clientId AND d.date = '$selectDate' ";

            $queryDelivery ="select IFNULL(sum(dd.amount),0) as deliverySum ,p.name as product_name ,p.unit , IFNULL(sum(dd.quantity),0) as deliveryQuantity_sum  from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where d.date = '$selectDate' and d.client_id =$clientId
                group by dd.product_id ";

            $deliveryOneDayResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
            $todaydilverySum = 0 ;
            $deliveryProduct ='';
            foreach($deliveryOneDayResult as $value){
                if($deliveryProduct !=''){
                    $deliveryProduct .= ', ';
                }
                $todaydilverySum = $todaydilverySum + $value['deliverySum'];

                $deliveryProduct .= $value['product_name']." ".$value['deliveryQuantity_sum']." ".$value['unit'];
            }





            //  $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];
            if(true){
                //  $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];
                $todayPaymentSum = 0;
                $oneDayBalanceCount =$todaydilverySum-$todayPaymentSum ;
                $openingTotalBalance = $openingTotalBalance + $oneDayBalanceCount;
                $oneDayData['discription'] = $deliveryProduct ;
                $oneDayData['reference_number'] = '';
                $oneDayData['delivery'] = $todaydilverySum ;
                $oneDayData['reciveAmount'] = $todayPaymentSum ;
                $oneDayData['balance'] = $openingTotalBalance ;
                $currentBalance = $openingTotalBalance ;
                $reportData[] = $oneDayData ;
            }
            $queryPayment ="Select IFNULL((pm.amount_paid) , 0) as payAmountSum ,pm.reference_number from payment_master as pm
                where pm.client_id ='$clientId' AND pm.date = '$selectDate'";

            $paymentOneDayResult = Yii::app()->db->createCommand($queryPayment)->queryAll();



            foreach($paymentOneDayResult as $value){

                $todayPaymentSum = ($value['payAmountSum']);


                if($todayPaymentSum != 0){
                    $todaydilverySum = 0;
                    $todayPaymentSum = ($value['payAmountSum']);
                    $oneDayBalanceCount = $todaydilverySum - $todayPaymentSum  ;
                    $openingTotalBalance = $oneDayBalanceCount + $openingTotalBalance;
                    $oneDayData['discription'] = 'RECEIVED' ;
                    $oneDayData['reference_number'] = $value['reference_number'];
                    $oneDayData['delivery'] = $todaydilverySum ;
                    $oneDayData['reciveAmount'] = $todayPaymentSum ;
                    $oneDayData['balance'] = $openingTotalBalance ;
                    $currentBalance = $openingTotalBalance ;

                    $reportData[] = $oneDayData ;
                }

            }



        }


        $querySumery ="select IFNULL(sum(dd.amount),0) as deliverySum ,p.name as product_name ,p.unit , IFNULL(sum(dd.quantity),0) as deliveryQuantity_sum  from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where  d.client_id ='$clientId' and d.date between '$startDate' and '$endDate' and p.unit is not null
                group by dd.product_id ";

        $querySumeryResult = Yii::app()->db->createCommand($querySumery)->queryAll();

        $resultArray = array();
        $resultArray['ledgerData'] = $reportData;
        $resultArray['sumery'] = $querySumeryResult;
        $resultArray['clientObject'] = $clientObject_forPrint;
        $company_id = Yii::app()->user->getState('company_branch_id');
        $resultArray['company_object'] = Company::model()->findByPk(intval($company_id))->attributes;
        $resultArray['current_balance'] = $currentBalance;
        $resultArray['nextMonthDate'] = $nextMonthDate;


        return json_encode($resultArray);

    }
    public static function getClientLedgherReportFunction_safe_and_tast($data){


        $clientId = $data['clientID'];
        $clientObject_forPrint =Client::model()->findByPk(intval($clientId))->attributes;
        //  $currentBalance = APIData::calculateFinalBalance($clientId);
        $currentBalance = 0;
        $startDate = $data['startDate'];

        $endDate = $data['endDate'];
        $nextMonthDate = date('Y-m-d', strtotime('5 day', strtotime($endDate)));

        $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
            where d.client_id = $clientId AND d.date < '$startDate' ";
        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totaldeliverySum = $deliveryResult[0]['deliverySum'];

        $queryDelivery ="Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
            where pm.client_id = $clientId AND pm.date < '$startDate' ";
        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totalRemaining = $deliveryResult[0]['remainingAmount'];
        $finalData = array();

        $finalData['openeningStock'] = $totaldeliverySum ;
        $finalData['totalRemaining'] = $totalRemaining ;
        $openingTotalBalance =  $totaldeliverySum - $totalRemaining ;

        $reportObject = array();
        $x= strtotime($startDate);
        $y= strtotime($endDate);
        $reportData = array();
        $oneDayData = array();
        $oneDayData['discription'] = 'OPENING BALANCE' ;
        $oneDayData['date'] = $startDate ;

        $oneDayData['delivery'] = $totaldeliverySum ;
        $oneDayData['reciveAmount'] = $totalRemaining ;
        $oneDayData['balance'] = $openingTotalBalance ;
        $reportData[] = $oneDayData ;

        while($x < ($y+8640)){

            $oneDayData = array();
            $selectDate = date("Y-m-d", $x);

            $oneDayData['date'] = $selectDate ;
            $x += 86400;
            $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d 
                where d.client_id = $clientId AND d.date = '$selectDate' ";

            $queryDelivery ="select IFNULL(sum(dd.amount),0) as deliverySum ,p.name as product_name ,p.unit , IFNULL(sum(dd.quantity),0) as deliveryQuantity_sum  from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where d.date = '$selectDate' and d.client_id =$clientId
                group by dd.product_id ";

            $deliveryOneDayResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
            $todaydilverySum = 0 ;
            $deliveryProduct ='';
            foreach($deliveryOneDayResult as $value){
                if($deliveryProduct !=''){
                    $deliveryProduct .= ', ';
                }
                $todaydilverySum = $todaydilverySum + $value['deliverySum'];

                $deliveryProduct .= $value['product_name']." ".$value['deliveryQuantity_sum']." ".$value['unit'];
            }





            //  $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];
            if(true){
                //  $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];
                $todayPaymentSum = 0;
                $oneDayBalanceCount =$todaydilverySum-$todayPaymentSum ;
                $openingTotalBalance = $openingTotalBalance + $oneDayBalanceCount;
                $oneDayData['discription'] = $deliveryProduct ;
                $oneDayData['reference_number'] = '';
                $oneDayData['delivery'] = $todaydilverySum ;
                $oneDayData['reciveAmount'] = $todayPaymentSum ;
                $oneDayData['balance'] = $openingTotalBalance ;
                $currentBalance = $openingTotalBalance ;
                $reportData[] = $oneDayData ;
            }
            $queryPayment ="Select IFNULL((pm.amount_paid) , 0) as payAmountSum ,pm.reference_number from payment_master as pm
                where pm.client_id ='$clientId' AND pm.date = '$selectDate'";

            $paymentOneDayResult = Yii::app()->db->createCommand($queryPayment)->queryAll();



            foreach($paymentOneDayResult as $value){

                $todayPaymentSum = ($value['payAmountSum']);


                if($todayPaymentSum != 0){
                    $todaydilverySum = 0;
                    $todayPaymentSum = ($value['payAmountSum']);
                    $oneDayBalanceCount = $todaydilverySum - $todayPaymentSum  ;
                    $openingTotalBalance = $oneDayBalanceCount + $openingTotalBalance;
                    $oneDayData['discription'] = 'RECEIVED' ;
                    $oneDayData['reference_number'] = $value['reference_number'];
                    $oneDayData['delivery'] = $todaydilverySum ;
                    $oneDayData['reciveAmount'] = $todayPaymentSum ;
                    $oneDayData['balance'] = $openingTotalBalance ;
                    $currentBalance = $openingTotalBalance ;

                    $reportData[] = $oneDayData ;
                }

            }



        }


        $querySumery ="select IFNULL(sum(dd.amount),0) as deliverySum ,p.name as product_name ,p.unit , IFNULL(sum(dd.quantity),0) as deliveryQuantity_sum  from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where  d.client_id ='$clientId' and d.date between '$startDate' and '$endDate' and p.unit is not null
                group by dd.product_id ";

        $querySumeryResult = Yii::app()->db->createCommand($querySumery)->queryAll();

        $resultArray = array();
        $resultArray['ledgerData'] = $reportData;
        $resultArray['sumery'] = $querySumeryResult;
        $resultArray['clientObject'] = $clientObject_forPrint;
        $company_id = Yii::app()->user->getState('company_branch_id');
        $resultArray['company_object'] = Company::model()->findByPk(intval($company_id))->attributes;
        $resultArray['current_balance'] = $currentBalance;
        $resultArray['nextMonthDate'] = $nextMonthDate;


        return json_encode($resultArray);

    }
    public static function getClientLedgherReportFunction_PrintBill_for_safe_tast($data){


        $clientId = $data['clientID'];
        $client_query =" SELECT c.security ,c.notification_alert_allow_user , c.client_id,c.fullname,c.address,c.cell_no_1 ,z.name AS zone_name FROM client AS c
            LEFT JOIN zone AS z ON c.zone_id = z.zone_id
            WHERE c.client_id ='$clientId'  ";


        $clientObject_Result = Yii::app()->db->createCommand($client_query)->queryAll();
        if($clientObject_Result[0]){
            $clientObject_forPrint =$clientObject_Result[0];
        }else{
            $clientObject_forPrint=[];
        }

        $company_id = Yii::app()->user->getState('company_branch_id');


        // $clientObject_forPrint =Client::model()->findByPk(intval($clientId))->attributes;
        //  $currentBalance = APIData::calculateFinalBalance($clientId);
        $currentBalance = 0;
        $startDate = $data['startDate'];

        $endDate = $data['endDate'];
        $nextMonthDate = date('Y-m-d', strtotime('5 day', strtotime($endDate)));

        $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
            where d.client_id = $clientId AND d.date < '$startDate' ";
        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totaldeliverySum = $deliveryResult[0]['deliverySum'];

        $queryDelivery ="Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
            where pm.client_id = $clientId AND pm.date < '$startDate' ";
        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totalRemaining = $deliveryResult[0]['remainingAmount'];
        $finalData = array();

        $finalData['openeningStock'] = $totaldeliverySum ;
        $finalData['totalRemaining'] = $totalRemaining ;
        $openingTotalBalance_amount =  $totaldeliverySum - $totalRemaining ;
        $openingTotalBalance = 0;
        $reportObject = array();
        $x= strtotime($startDate);
        $y= strtotime($endDate);
        $reportData = array();
        $oneDayData = array();
        $oneDayData['discription'] = 'OPENING BALANCE' ;
        $oneDayData['date'] = $startDate ;

        $oneDayData['delivery'] = $totaldeliverySum ;
        $oneDayData['reciveAmount'] = $totalRemaining ;
        $oneDayData['balance'] = $openingTotalBalance ;
        $reportData[] = $oneDayData ;
        $reportData = array();
        $total_paid_payment_duration_this_date =0;
        while($x < ($y+8640)){

            $oneDayData = array();
            $selectDate = date("Y-m-d", $x);

            $oneDayData['date'] = $selectDate ;
            $oneDayData['just_date'] = date("d", $x); ;
            $x += 86400;
            $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d 
                where d.client_id = $clientId AND d.date = '$selectDate' ";

            $queryDelivery ="select IFNULL(sum(dd.amount),0) as deliverySum ,p.name as product_name ,p.unit , IFNULL(sum(dd.quantity),'') as deliveryQuantity_sum  from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where d.date = '$selectDate' and d.client_id =$clientId
                group by dd.product_id ";

            $deliveryOneDayResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
            $todaydilverySum = 0 ;
            $deliveryProduct ='';
            $deliveryProduct_quantity_sum ='';

            foreach($deliveryOneDayResult as $value){
                if($deliveryProduct !=''){
                    $deliveryProduct .= ', ';
                    $deliveryProduct_quantity_sum .= ', ';
                }
                $todaydilverySum = $todaydilverySum + $value['deliverySum'];

                if($company_id ==1){
                    $deliveryProduct .= $value['deliveryQuantity_sum'];
                }else{
                    $deliveryProduct .= $value['product_name']." ".$value['deliveryQuantity_sum']." ".$value['unit'];
                }


                if($value['deliveryQuantity_sum'] >0){
                    $deliveryProduct_quantity_sum .= $value['deliverySum']/$value['deliveryQuantity_sum'];

                }else{

                }

            }


            //  $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];
            if(true){
                //  $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];
                $todayPaymentSum = 0;
                $oneDayBalanceCount =$todaydilverySum-$todayPaymentSum ;
                $openingTotalBalance = $openingTotalBalance + $oneDayBalanceCount;
                $oneDayData['discription'] = $deliveryProduct ;
                $oneDayData['rate'] =$deliveryProduct_quantity_sum ;

                $oneDayData['reference_number'] = '';
                $oneDayData['delivery'] = $todaydilverySum ;
                $oneDayData['reciveAmount'] = $todayPaymentSum ;
                $oneDayData['balance'] = $openingTotalBalance ;
                $currentBalance = $openingTotalBalance ;
                $reportData[] = $oneDayData ;
            }
            $queryPayment ="Select IFNULL((pm.amount_paid) , 0) as payAmountSum ,pm.reference_number from payment_master as pm
                where pm.client_id ='$clientId' AND pm.date = '$selectDate'";

            $paymentOneDayResult = Yii::app()->db->createCommand($queryPayment)->queryAll();



            foreach($paymentOneDayResult as $value){
                $todayPaymentSum = ($value['payAmountSum']);
                if($todayPaymentSum != 0){
                    $todaydilverySum = 0;
                    $todayPaymentSum = ($value['payAmountSum']);
                    // $oneDayBalanceCount = $todaydilverySum - $todayPaymentSum  ;
                    // $openingTotalBalance = $oneDayBalanceCount + $openingTotalBalance;
                    $oneDayData['discription'] = 'RECEIVED' ;
                    $oneDayData['reference_number'] = $value['reference_number'];
                    $oneDayData['delivery'] = $todaydilverySum ;
                    $oneDayData['reciveAmount'] = $todayPaymentSum ;

                    $total_paid_payment_duration_this_date = $total_paid_payment_duration_this_date + $todayPaymentSum;
                    // $oneDayData['balance'] = $openingTotalBalance ;
                    // $currentBalance = $openingTotalBalance ;
                    // $reportData[] = $oneDayData ;
                }
            }
        }


        $querySumery ="select IFNULL(sum(dd.amount),0) as deliverySum ,
                p.name as product_name ,
                p.unit ,
                 IFNULL(sum(dd.quantity),0) as deliveryQuantity_sum
                   from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where  d.client_id ='$clientId' and d.date 
                between '$startDate' and '$endDate' 
                and p.unit is not null
                group by dd.product_id ";

        $querySumeryResult = Yii::app()->db->createCommand($querySumery)->queryAll();
        $resultArray = array();

        $reportData_new = [];

        $size_of_list =  sizeof($reportData);
        $half_size= round(($size_of_list/2),0);
        $half_size = $half_size-1;
        $x = 0;
        $step = 0;
        foreach($reportData as $key=>$value){

            $reportData_new[$x][$step] =$value;

            $x++;
            if($half_size==$key){
                $x = 0;
                $step = 1;
            }

        }


        $resultArray['ledgerData'] = $reportData_new;
        $resultArray['arrearer'] =0;
        $resultArray['collection'] =0;
        $fix_ob=$openingTotalBalance_amount;
        $openingTotalBalance_amount = $openingTotalBalance_amount- $total_paid_payment_duration_this_date;
        if($openingTotalBalance_amount>0){
            $resultArray['arrearer'] = $openingTotalBalance_amount;
        }else{
            $resultArray['collection'] = -($openingTotalBalance_amount);
            //  $resultArray['collection'] = 99;
        }


        $resultArray['area'] = "lahore";
        $resultArray['sumery'] = $querySumeryResult;
        $resultArray['clientObject'] = $clientObject_forPrint;

        $resultArray['company_object'] = Company::model()->findByPk(intval($company_id))->attributes;
        // $resultArray['current_balance'] = $currentBalance+$fix_ob;
        $resultArray['current_balance'] = $currentBalance+$openingTotalBalance_amount;
        $resultArray['nextMonthDate'] = $nextMonthDate;
        return json_encode($resultArray);
    }
    public static function getClientLedgherReportFunction_PrintBill($data){


        $clientId = $data['clientID'];

        $client_query =" SELECT c.security ,c.notification_alert_allow_user , c.client_id,c.fullname,c.address,c.cell_no_1 ,z.name AS zone_name FROM client AS c
            LEFT JOIN zone AS z ON c.zone_id = z.zone_id
            WHERE c.client_id ='$clientId'  ";


        $clientObject_Result = Yii::app()->db->createCommand($client_query)->queryAll();
        if($clientObject_Result[0]){
            $clientObject_forPrint =$clientObject_Result[0];
        }else{
            $clientObject_forPrint=[];
        }

        $company_id = Yii::app()->user->getState('company_branch_id');


        // $clientObject_forPrint =Client::model()->findByPk(intval($clientId))->attributes;
        //  $currentBalance = APIData::calculateFinalBalance($clientId);
        $currentBalance = 0;

        $startDate = $data['startDate'];
        $endDate = $data['endDate'];

        $total_discount_amount =   discount_amount::get_total_discount_amount($clientId,$startDate,$endDate);


        $nextMonthDate = date('Y-m-d', strtotime('5 day', strtotime($endDate)));

        $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
            where d.client_id = $clientId AND d.date < '$startDate' ";
        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totaldeliverySum = $deliveryResult[0]['deliverySum'];

        $queryDelivery ="Select IFNULL(sum(pm.amount_paid) , 0) as 
           remainingAmount from payment_master as pm
            where pm.client_id = $clientId AND pm.date < '$startDate' ";

        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totalRemaining = $deliveryResult[0]['remainingAmount'];
        $finalData = array();

        $finalData['openeningStock'] = $totaldeliverySum ;
        $finalData['totalRemaining'] = $totalRemaining ;
        $openingTotalBalance_amount =  $totaldeliverySum - $totalRemaining ;
        $openingTotalBalance = 0;
        $reportObject = array();
        $x= strtotime($startDate);
        $y= strtotime($endDate);
        $reportData = array();
        $oneDayData = array();
        $oneDayData['discription'] = 'OPENING BALANCE' ;
        $oneDayData['date'] = $startDate ;

        $oneDayData['delivery'] = $totaldeliverySum ;
        $oneDayData['reciveAmount'] = $totalRemaining ;
        $oneDayData['balance'] = $openingTotalBalance ;
        $reportData[] = $oneDayData ;
        $reportData = array();
        $total_paid_payment_duration_this_date =0;
        while($x < ($y+8640)){

            $oneDayData = array();
            $selectDate = date("Y-m-d", $x);

            $oneDayData['date'] = $selectDate ;
            $oneDayData['just_date'] = date("d", $x); ;
            $x += 86400;
            $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d 
                where d.client_id = $clientId AND d.date = '$selectDate' ";

            $queryDelivery ="select IFNULL(sum(dd.amount),0) as deliverySum ,p.name as product_name ,p.unit , IFNULL(sum(dd.quantity),'') as deliveryQuantity_sum  from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where d.date = '$selectDate' and d.client_id =$clientId
                group by dd.product_id ";

            $deliveryOneDayResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
            $todaydilverySum = 0 ;
            $deliveryProduct ='';
            $deliveryProduct_quantity_sum ='';

            foreach($deliveryOneDayResult as $value){
                if($deliveryProduct !=''){
                    $deliveryProduct .= ', ';
                    $deliveryProduct_quantity_sum .= ', ';
                }
                $todaydilverySum = $todaydilverySum + $value['deliverySum'];

                if($company_id ==1){
                    $deliveryProduct .= $value['deliveryQuantity_sum'];
                }else{
                    $deliveryProduct .= $value['product_name']." ".$value['deliveryQuantity_sum']." ".$value['unit'];
                }


                if($value['deliveryQuantity_sum'] >0){
                    $deliveryProduct_quantity_sum .= $value['deliverySum']/$value['deliveryQuantity_sum'];

                }else{

                }

            }


            //  $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];
            if(true){
                //  $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];
                $todayPaymentSum = 0;
                $oneDayBalanceCount =$todaydilverySum-$todayPaymentSum ;
                $openingTotalBalance = $openingTotalBalance + $oneDayBalanceCount;
                $oneDayData['discription'] = $deliveryProduct ;
                $oneDayData['rate'] =$deliveryProduct_quantity_sum ;

                $oneDayData['reference_number'] = '';
                $oneDayData['delivery'] = $todaydilverySum ;
                $oneDayData['reciveAmount'] = $todayPaymentSum ;
                $oneDayData['balance'] = $openingTotalBalance ;
                $currentBalance = $openingTotalBalance ;
                $reportData[] = $oneDayData ;
            }
            $queryPayment ="Select 
                               IFNULL((pm.amount_paid) , 0) as payAmountSum
                              ,pm.reference_number
                              from payment_master as pm
                              where pm.client_id ='$clientId'
                                AND pm.date = '$selectDate'";


            $paymentOneDayResult = Yii::app()->db->createCommand($queryPayment)->queryAll();



            foreach($paymentOneDayResult as $value){
                $todayPaymentSum = ($value['payAmountSum']);
                if($todayPaymentSum != 0){
                    $todaydilverySum = 0;
                    $todayPaymentSum = ($value['payAmountSum']);
                    // $oneDayBalanceCount = $todaydilverySum - $todayPaymentSum  ;
                    // $openingTotalBalance = $oneDayBalanceCount + $openingTotalBalance;
                    $oneDayData['discription'] = 'RECEIVED' ;
                    $oneDayData['reference_number'] = $value['reference_number'];
                    $oneDayData['delivery'] = $todaydilverySum ;
                    $oneDayData['reciveAmount'] = $todayPaymentSum ;

                    $total_paid_payment_duration_this_date = $total_paid_payment_duration_this_date + $todayPaymentSum;
                    // $oneDayData['balance'] = $openingTotalBalance ;
                    // $currentBalance = $openingTotalBalance ;
                    // $reportData[] = $oneDayData ;
                }
            }
        }


        $querySumery ="select IFNULL(sum(dd.amount),0) as deliverySum ,
                p.name as product_name ,
                p.unit ,
                 IFNULL(sum(dd.quantity),0) as deliveryQuantity_sum
                   from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where  d.client_id ='$clientId' and d.date 
                between '$startDate' and '$endDate' 
                and p.unit is not null
                group by dd.product_id ";

        $querySumeryResult = Yii::app()->db->createCommand($querySumery)->queryAll();
        $resultArray = array();
        $resultArray['ledgerData'] = $reportData;
        $resultArray['arrearer'] =0;
        $resultArray['collection'] =0;
        $fix_ob=$openingTotalBalance_amount;
        $openingTotalBalance_amount = $openingTotalBalance_amount- $total_paid_payment_duration_this_date;
        if($openingTotalBalance_amount>0){
            $resultArray['arrearer'] = $openingTotalBalance_amount;
        }else{
            $resultArray['collection'] = -($openingTotalBalance_amount);
            //  $resultArray['collection'] = 99;

        }

        $resultArray['area'] = "lahore";
        $resultArray['sumery'] = $querySumeryResult;
        $resultArray['clientObject'] = $clientObject_forPrint;

        $resultArray['company_object'] = Company::model()->findByPk(intval($company_id))->attributes;
        // $resultArray['current_balance'] = $currentBalance+$fix_ob;
        $resultArray['current_balance'] = $currentBalance+$openingTotalBalance_amount;
        $resultArray['nextMonthDate'] = $nextMonthDate;


        $resultArray['total_discount'] = $total_discount_amount;
        $next_month = date('Y-m-d', strtotime('+1 month', strtotime($startDate)));
        $month = date("M",strtotime($next_month));
        $year = date("Y",strtotime($next_month));
        $resultArray['due_date'] = '05-'.$month.'-'.$year;



        return json_encode($resultArray);
    }
    public static function getClientLedgherReportFunction_dairy_craft($data){


        $clientId = $data['clientID'];
        $client_query =" SELECT c.security ,c.notification_alert_allow_user , c.client_id,c.fullname,c.address,c.cell_no_1 ,z.name AS zone_name FROM client AS c
            LEFT JOIN zone AS z ON c.zone_id = z.zone_id
            WHERE c.client_id ='$clientId'  ";


        $clientObject_Result = Yii::app()->db->createCommand($client_query)->queryAll();
        if($clientObject_Result[0]){
            $clientObject_forPrint =$clientObject_Result[0];
        }else{
            $clientObject_forPrint=[];
        }

        $company_id = Yii::app()->user->getState('company_branch_id');


        // $clientObject_forPrint =Client::model()->findByPk(intval($clientId))->attributes;
        //  $currentBalance = APIData::calculateFinalBalance($clientId);
        $currentBalance = 0;
        $startDate = $data['startDate'];

        $endDate = $data['endDate'];
        $nextMonthDate = date('Y-m-d', strtotime('5 day', strtotime($endDate)));

        $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
            where d.client_id = $clientId AND d.date < '$startDate' ";
        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totaldeliverySum = $deliveryResult[0]['deliverySum'];

        $queryDelivery ="Select IFNULL(sum(pm.amount_paid) , 0) as 
           remainingAmount from payment_master as pm
            where pm.client_id = $clientId AND pm.bill_month_date < '$startDate' ";

        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totalRemaining = $deliveryResult[0]['remainingAmount'];
        $finalData = array();

        $finalData['openeningStock'] = $totaldeliverySum ;
        $finalData['totalRemaining'] = $totalRemaining ;
        $openingTotalBalance_amount =  $totaldeliverySum - $totalRemaining ;
        $openingTotalBalance = 0;
        $reportObject = array();
        $x= strtotime($startDate);
        $y= strtotime($endDate);
        $reportData = array();
        $oneDayData = array();
        $oneDayData['discription'] = 'OPENING BALANCE' ;
        $oneDayData['date'] = $startDate ;

        $oneDayData['delivery'] = $totaldeliverySum ;
        $oneDayData['reciveAmount'] = $totalRemaining ;
        $oneDayData['balance'] = $openingTotalBalance ;
        $reportData[] = $oneDayData ;
        $reportData = array();
        $total_paid_payment_duration_this_date =0;
        while($x < ($y+8640)){

            $oneDayData = array();
            $selectDate = date("Y-m-d", $x);

            $oneDayData['date'] = $selectDate ;
            $oneDayData['just_date'] = date("d", $x); ;
            $x += 86400;
            $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d 
                where d.client_id = $clientId AND d.date = '$selectDate' ";

            $queryDelivery ="select IFNULL(sum(dd.amount),0) as deliverySum ,p.name as product_name ,p.unit , IFNULL(sum(dd.quantity),'') as deliveryQuantity_sum  from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where d.date = '$selectDate' and d.client_id =$clientId
                group by dd.product_id ";

            $deliveryOneDayResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
            $todaydilverySum = 0 ;
            $deliveryProduct ='';
            $deliveryProduct_quantity_sum ='';

            foreach($deliveryOneDayResult as $value){
                if($deliveryProduct !=''){
                    $deliveryProduct .= ', ';
                    $deliveryProduct_quantity_sum .= ', ';
                }
                $todaydilverySum = $todaydilverySum + $value['deliverySum'];

                if($company_id ==1){
                    $deliveryProduct .= $value['deliveryQuantity_sum'];
                }else{
                    $deliveryProduct .= $value['product_name']." ".$value['deliveryQuantity_sum']." ".$value['unit'];
                }


                if($value['deliveryQuantity_sum'] >0){
                    $deliveryProduct_quantity_sum .= $value['deliverySum']/$value['deliveryQuantity_sum'];

                }else{

                }

            }


            //  $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];
            if(true){
                //  $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];
                $todayPaymentSum = 0;
                $oneDayBalanceCount =$todaydilverySum-$todayPaymentSum ;
                $openingTotalBalance = $openingTotalBalance + $oneDayBalanceCount;
                $oneDayData['discription'] = $deliveryProduct ;
                $oneDayData['rate'] =$deliveryProduct_quantity_sum ;

                $oneDayData['reference_number'] = '';
                $oneDayData['delivery'] = $todaydilverySum ;
                $oneDayData['reciveAmount'] = $todayPaymentSum ;
                $oneDayData['balance'] = $openingTotalBalance ;
                $currentBalance = $openingTotalBalance ;
                $reportData[] = $oneDayData ;
            }
            $queryPayment ="Select 
                               IFNULL((pm.amount_paid) , 0) as payAmountSum
                              ,pm.reference_number
                              from payment_master as pm
                              where pm.client_id ='$clientId'
                                AND pm.bill_month_date = '$selectDate'";


            $paymentOneDayResult = Yii::app()->db->createCommand($queryPayment)->queryAll();



            foreach($paymentOneDayResult as $value){
                $todayPaymentSum = ($value['payAmountSum']);
                if($todayPaymentSum != 0){
                    $todaydilverySum = 0;
                    $todayPaymentSum = ($value['payAmountSum']);
                    // $oneDayBalanceCount = $todaydilverySum - $todayPaymentSum  ;
                    // $openingTotalBalance = $oneDayBalanceCount + $openingTotalBalance;
                    $oneDayData['discription'] = 'RECEIVED' ;
                    $oneDayData['reference_number'] = $value['reference_number'];
                    $oneDayData['delivery'] = $todaydilverySum ;
                    $oneDayData['reciveAmount'] = $todayPaymentSum ;

                    $total_paid_payment_duration_this_date = $total_paid_payment_duration_this_date + $todayPaymentSum;
                    // $oneDayData['balance'] = $openingTotalBalance ;
                    // $currentBalance = $openingTotalBalance ;
                    // $reportData[] = $oneDayData ;
                }
            }
        }


        $querySumery ="select IFNULL(sum(dd.amount),0) as deliverySum ,
                p.name as product_name ,
                p.unit ,
                 IFNULL(sum(dd.quantity),0) as deliveryQuantity_sum
                   from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where  d.client_id ='$clientId' and d.date 
                between '$startDate' and '$endDate' 
                and p.unit is not null
                group by dd.product_id ";

        $querySumeryResult = Yii::app()->db->createCommand($querySumery)->queryAll();
        $resultArray = array();
        $resultArray['ledgerData'] = $reportData;
        $resultArray['arrearer'] =0;
        $resultArray['collection'] =0;
        $fix_ob=$openingTotalBalance_amount;
        $openingTotalBalance_amount = $openingTotalBalance_amount- $total_paid_payment_duration_this_date;
        if($openingTotalBalance_amount>0){
            $resultArray['arrearer'] = $openingTotalBalance_amount;
        }else{
            $resultArray['collection'] = -($openingTotalBalance_amount);
            //  $resultArray['collection'] = 99;

        }

        $resultArray['area'] = "lahore";
        $resultArray['sumery'] = $querySumeryResult;
        $resultArray['clientObject'] = $clientObject_forPrint;

        $resultArray['company_object'] = Company::model()->findByPk(intval($company_id))->attributes;
        // $resultArray['current_balance'] = $currentBalance+$fix_ob;
        $resultArray['current_balance'] = $currentBalance+$openingTotalBalance_amount;
        $resultArray['nextMonthDate'] = $nextMonthDate;
        return json_encode($resultArray);
    }


    public static function getClientLedgherReportFunction_export($data){

        $clientId = $data['clientID'];


        if($clientId ==''){
            $clientId = 0;
            $export_file_name = 'many_customer';

        }else{

            $export_file_name_object = Client::model()->findByPk(intval($clientId));

            $export_file_name_with_space =  $export_file_name_object['fullname'];

            $export_file_name =str_replace(" ","_",$export_file_name_with_space);

            $startDate = $data['startDate'];
            $endDate = $data['endDate'];
            $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
            where d.client_id = $clientId AND d.date < '$startDate' ";
            $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
            $totaldeliverySum = $deliveryResult[0]['deliverySum'];

            $queryDelivery ="Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
            where pm.client_id = $clientId AND pm.date < '$startDate' ";
            $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
            $totalRemaining = $deliveryResult[0]['remainingAmount'];
            $finalData = array();

            $finalData['openeningStock'] = $totaldeliverySum ;
            $finalData['totalRemaining'] = $totalRemaining ;
            $openingTotalBalance =  $totaldeliverySum - $totalRemaining ;

            $reportObject = array();
            $x= strtotime($startDate);
            $y= strtotime($endDate);
            $reportData = array();
            $oneDayData = array();
            $oneDayData['discription'] = 'OPENING BALANCE' ;
            $oneDayData['date'] = $startDate ;

            $oneDayData['delivery'] = $totaldeliverySum ;
            $oneDayData['reciveAmount'] = $totalRemaining ;
            $oneDayData['balance'] = $openingTotalBalance ;
            $reportData[] = $oneDayData ;

            while($x < ($y+8640)){

                $oneDayData = array();
                $selectDate = date("Y-m-d", $x);

                $oneDayData['date'] = $selectDate ;
                $x += 86400;
                $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d 
                where d.client_id = $clientId AND d.date = '$selectDate' ";

                $queryDelivery ="select IFNULL(sum(d.total_amount),0) as deliverySum ,p.name as product_name ,p.unit , IFNULL(sum(dd.quantity),0) as deliveryQuantity_sum  from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where d.date = '$selectDate' and d.client_id =$clientId
                group by dd.product_id ";

                $deliveryOneDayResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
                $todaydilverySum = 0 ;
                $deliveryProduct ='';
                foreach($deliveryOneDayResult as $value){
                    if($deliveryProduct !=''){
                        $deliveryProduct .= ', ';
                    }
                    $todaydilverySum = $todaydilverySum + $value['deliverySum'];

                    $deliveryProduct .= $value['product_name']." ".$value['deliveryQuantity_sum']." ".$value['unit'];
                }

                ;



                //  $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];
                if($todaydilverySum !=0){
                    //  $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];
                    $todayPaymentSum = 0;
                    $oneDayBalanceCount =$todaydilverySum-$todayPaymentSum ;
                    $openingTotalBalance = $openingTotalBalance + $oneDayBalanceCount;
                    $oneDayData['discription'] = $deliveryProduct ;
                    $oneDayData['reference_number'] = '';
                    $oneDayData['delivery'] = $todaydilverySum ;
                    $oneDayData['reciveAmount'] = $todayPaymentSum ;
                    $oneDayData['balance'] = $openingTotalBalance ;
                    $reportData[] = $oneDayData ;
                }
                $queryPayment ="Select IFNULL((pm.amount_paid) , 0) as payAmountSum ,pm.reference_number from payment_master as pm
                where pm.client_id ='$clientId' AND pm.date = '$selectDate'";

                $paymentOneDayResult = Yii::app()->db->createCommand($queryPayment)->queryAll();



                foreach($paymentOneDayResult as $value){

                    $todayPaymentSum = ($value['payAmountSum']);


                    if($todayPaymentSum != 0){
                        $todaydilverySum = 0;
                        $todayPaymentSum = ($value['payAmountSum']);
                        $oneDayBalanceCount = $todaydilverySum - $todayPaymentSum  ;
                        $openingTotalBalance = $oneDayBalanceCount + $openingTotalBalance;
                        $oneDayData['discription'] = 'RECEIVED' ;
                        $oneDayData['reference_number'] = $value['reference_number'];
                        $oneDayData['delivery'] = $todaydilverySum ;
                        $oneDayData['reciveAmount'] = $todayPaymentSum ;
                        $oneDayData['balance'] = $openingTotalBalance ;

                        $reportData[] = $oneDayData ;
                    }

                }



            }


            $querySumery ="select IFNULL(sum(dd.amount),0) as deliverySum ,p.name as product_name ,p.unit , IFNULL(sum(dd.quantity),0) as deliveryQuantity_sum  from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where  d.client_id ='$clientId' and d.date between '$startDate' and '$endDate' and p.unit is not null
                group by dd.product_id ";

            $querySumeryResult = Yii::app()->db->createCommand($querySumery)->queryAll();


            $resultArray = array();
            $resultArray['ledgerData'] = $reportData;
            $resultArray['sumery'] = $querySumeryResult;


            $client_query = "select * from client as c
                              left join zone as z ON z.zone_id =c.zone_id
                              where c.client_id = '$clientId' ";

            $clientObject = Yii::app()->db->createCommand($client_query)->queryAll();

            $fullname = $clientObject[0]['fullname'];
            $address = $clientObject[0]['address'];
            $zone = $clientObject[0]['name'];

            header("Content-type: application/vnd.ms-excel; name='excel'");
            header("Content-Disposition: attachment; filename=$export_file_name.csv");
            header("Pragma: no-cache");
            header("Expires: 0");

            echo ',Customer Name,'.$fullname;
            echo "\r\n";
            echo ',Zone,'.$zone;
            echo "\r\n";

            echo ',Address,'.$address;
            echo "\r\n";

            echo ',Date,'.$startDate.",To,".$endDate;
            echo "\r\n";

            echo "\r\n";
            echo '#,Date ,DESCRIPTION, REFERENCE No. ,DELIVERY , 	RECEIVED , BALANCE ';
            echo "\r\n";
            $x=0;
            foreach ($reportData as $value){


                $x++ ;
                echo $x.',';
                echo $value['date'].',';
                echo $value['discription'].',';
                if(isset($value['reference_number'])){
                    echo $value['reference_number'].',';
                }else{
                    echo ',';
                }

                echo $value['delivery'].',';
                echo $value['reciveAmount'].',';
                echo $value['balance'].',';

                echo "\r\n";
            }

        }
        echo "\r\n";
        echo 'Product Summary';
        echo "\r\n";
        foreach($querySumeryResult as $summary){
            echo    $summary['product_name'].',';
            echo    $summary['deliveryQuantity_sum'].' ';
            echo    $summary['unit'].',';
            echo    $summary['deliverySum'].',';
            echo "\r\n";
        }



    }


    public static function getClientLedgherReportFunction_bottle($data){

        $clientId = $data['clientID'];
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $product_id = $data['product_id'];
        $company_branch_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_branch_id and bottle = 2";
        //    $product =  Yii::app()->db->createCommand($query)->queryAll();
        //   var_dump($product[0]['price']);
        //   die();

        $product_id = 25;
        $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
            where d.client_id = $clientId AND d.date < '$startDate' ";
        $queryDelivery ="select IFNULL(Sum(dd.quantity) ,0) as deliverySum from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                where d.client_id ='$clientId' and dd.product_id = '$product_id' and d.date < '$startDate'";

        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totaldeliverySum = $deliveryResult[0]['deliverySum'];

        $queryDelivery ="Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
            where pm.client_id = $clientId AND pm.date < '$startDate' ";


        $queryDelivery ="select IFNULL(sum(br.broken) , 0) as broken , IFNULL(sum(br.perfect) ,0) as perfect,IFNULL(sum(br.broken + br.perfect) ,0) as remainingAmount from bottle_record as br
             where br.client_id ='$clientId'  and br.date < '$startDate' ";


        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totalRemaining = $deliveryResult[0]['remainingAmount'];
        $finalData = array();

        $finalData['openeningStock'] = $totaldeliverySum ;
        $finalData['totalRemaining'] = $totalRemaining ;
        $openingTotalBalance =  $totaldeliverySum - $totalRemaining ;

        $reportObject = array();
        $x= strtotime($startDate);
        $y= strtotime($endDate);
        $reportData = array();
        $oneDayData = array();
        $oneDayData['discription'] = 'OPENING BALANCE' ;
        $oneDayData['date'] = $startDate ;

        $oneDayData['delivery'] = $totaldeliverySum ;
        $oneDayData['reciveAmount'] = $totalRemaining ;

        $oneDayData['perfect'] = $deliveryResult[0]['perfect'];
        $oneDayData['broken'] = $deliveryResult[0]['broken'];


        $oneDayData['balance'] = $openingTotalBalance ;
        $reportData[] = $oneDayData ;

        while($x < ($y+8640)){

            $oneDayData = array();
            $selectDate = date("Y-m-d", $x);


            $oneDayData['date'] = $selectDate ;
            $x += 86400;
            $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d 
                where d.client_id = $clientId AND d.date = '$selectDate' ";


            $queryDelivery ="select IFNULL(Sum(dd.quantity) ,0) as deliverySum from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                where d.client_id ='$clientId' and dd.product_id = '$product_id' and d.date = '$selectDate' ";


            $deliveryOneDayResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
            $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];
            if($todaydilverySum !=0){
                $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];
                $todayPaymentSum = 0;
                $oneDayBalanceCount =$todaydilverySum-$todayPaymentSum ;
                $openingTotalBalance = $openingTotalBalance + $oneDayBalanceCount;
                $oneDayData['discription'] = 'BOTTLE DELIVERED' ;
                $oneDayData['delivery'] = $todaydilverySum ;
                $oneDayData['reciveAmount'] = $todayPaymentSum ;

                $oneDayData['perfect'] =0;
                $oneDayData['broken'] = 0;

                $oneDayData['balance'] = $openingTotalBalance ;
                $reportData[] = $oneDayData ;
            }

            $queryPayment ="Select IFNULL(sum(pm.amount_paid) , 0) as payAmountSum from payment_master as pm
                where pm.client_id ='$clientId' AND pm.date = '$selectDate'";


            $queryPayment ="select IFNULL(sum(br.broken) , 0) as broken , IFNULL(sum(br.perfect) ,0) as perfect,IFNULL(sum(br.broken + br.perfect) ,0) as payAmountSum from bottle_record as br
                   where br.client_id ='$clientId'  and br.date  = '$selectDate'";

            $paymentOneDayResult = Yii::app()->db->createCommand($queryPayment)->queryAll();

            $todayPaymentSum = ($paymentOneDayResult[0]['payAmountSum']);

            if($todayPaymentSum != 0){
                $todaydilverySum = 0;
                $todayPaymentSum = ($paymentOneDayResult[0]['payAmountSum']);
                $oneDayBalanceCount = $todaydilverySum - $todayPaymentSum  ;
                $openingTotalBalance = $oneDayBalanceCount + $openingTotalBalance;
                $oneDayData['discription'] = 'BOTTLE RECEIVED' ;
                $oneDayData['delivery'] = $todaydilverySum ;
                $oneDayData['reciveAmount'] = $todayPaymentSum ;

                $oneDayData['perfect'] = $paymentOneDayResult[0]['perfect'];
                $oneDayData['broken'] = $paymentOneDayResult[0]['broken'];

                $oneDayData['balance'] = $openingTotalBalance ;

                $reportData[] = $oneDayData ;
            }


        }


        return json_encode($reportData);
    }
    public static function getClientLedgherReportFunction_bill_taza($data){

        $clientId = $data['clientID'];

        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $productID = $data['productID'];
        $reportObject = array();
        $x= strtotime($startDate);
        $y= strtotime($endDate);

        $company_id = Yii::app()->user->getState('company_branch_id');


        $query="select  dd.product_id , p.name   from  delivery as d
                    left join delivery_detail as dd ON d.delivery_id = dd.delivery_id 
                    left join product as p ON p.product_id = dd.product_id
                    where d.client_id = '$clientId' and dd.date != 'NULL' and dd.date between '$startDate' and '$endDate'
                    group by dd.product_id ";


        $productList =  Yii::app()->db->createCommand($query)->queryAll();

        $headingResult = array();

        foreach($productList as $value){
            array_push($headingResult,"Quantity","Amount");
        }
        /*foreach($productList as $value){
            $product_id = $value['product_id'];
            $query = "select  p.name as product_name , IFNULL(d.total_amount/sum(dd.quantity) , 0) as rate , d.date , d.total_amount  ,
              ifnull(dd.quantity , 0) as delivered_quantity , ifnull(dd.amount  , 0) as one_product_amount ,
              sum(dd.quantity) as totalquantity_delivered  from delivery as d
              left join client as c ON c.client_id = d.client_id
              left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
              left join product as p ON p.product_id =dd.product_id
              where d.client_id = '$clientId' and p.product_id ='$product_id'  and d.date between '$startDate' and  '$endDate'
              group by dd.product_id , d.delivery_id ";
            $bill_result  = Yii::app()->db->createCommand($query)->queryAll();
             $reportList = array();
             foreach($reportList as $value){
                 $oneObject = array();
                 $oneObject['quantity'] = $value['quantity'];
                 $oneObject['amount'] = $value['amount'];

             }
            die();*/
        /*  $finalData  = array();
          foreach ($productList as $product){
              $oneProduct = array();
               $product_id = $product['product_id'];
              $oneProduct['produt_name'] = $product['name'];
               $deliveryRecord = array();
              while($x < ($y+8640)){
                  $oneDayData = array();
                  $selectDate = date("Y-m-d", $x);
                  $oneDayData['date'] = $selectDate ;
                  $x += 86400;
                  $oneday_query = "select ifnull(dd.quantity , 0) as quantity , ifnull(dd.amount , 0) as amount from delivery d
                      left join delivery_detail as dd ON d.delivery_id = dd.delivery_id
                      where dd.date = '$selectDate' and dd.product_id = '$product_id' and d.client_id = '$clientId' ";
                  $oneday_result  = Yii::app()->db->createCommand($oneday_query)->queryAll();
                    if(isset($oneday_result[0]['quantity'])){
                        $oneDayData['quantity'] = $oneday_result[0]['quantity'] ;
                        $oneDayData['amount'] = $oneday_result[0]['amount'] ;
                    }else{
                        $oneDayData['quantity'] = '0';
                        $oneDayData['amount'] = '0';
                    }


                  $deliveryRecord[] = $oneDayData;
              }
              $oneProduct['deliveryObject'] =  $deliveryRecord;
              array_push($finalData,$oneProduct);
          }*/





        $finalData  = array();
        while($x < ($y+8640)){
            $oneDayData = array();
            $selectDate = date("Y-m-d", $x);

            array_push($oneDayData , $selectDate);
            $x += 86400;
            foreach ($productList as $product){
                $product_id = $product['product_id'];
                $oneday_query = "select ifnull(dd.quantity , 0) as quantity , ifnull(dd.amount , 0) as amount from delivery d
                                 left join delivery_detail as dd ON d.delivery_id = dd.delivery_id 
                                 where dd.date = '$selectDate' and dd.product_id = '$product_id' and d.client_id = '$clientId' ";

                $oneday_result  = Yii::app()->db->createCommand($oneday_query)->queryAll();

                if(isset($oneday_result[0]['quantity'])){
                    $quantity = $oneday_result[0]['quantity'] ;
                    $amount = $oneday_result[0]['amount'] ;

                    array_push($oneDayData , $amount/$quantity);
                    array_push($oneDayData , $quantity);
                    array_push($oneDayData , $amount);

                }else{
                    array_push($oneDayData ,'');
                    array_push($oneDayData , '0');
                    array_push($oneDayData , '0');
                }

            }




            $finalData[] = $oneDayData;
        }

        $query = " select z.name from client as c
                  left join zone as z ON z.zone_id = c.zone_id
                  where c.client_id = '$clientId' ";
        $reult = Yii::app()->db->createCommand($query)->queryScalar();

        $finalArray = array();
        $finalArray['finalData'] = $finalData ;
        $finalArray['productList'] = $productList ;
        $finalArray['headingResult'] = $headingResult ;
        $finalArray['Zone'] = $reult ;

        echo  json_encode($finalArray);
        die();
        while($x < ($y+8640)){

            $oneDayData = array();
            echo $selectDate.'<br>';

        }
        die();
        return json_encode($reportData);
    }
    public static function getClientLedgherReportFunction_bill($data){

        $clientId = $data['clientID'];

        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $productID = $data['productID'];
        $reportObject = array();
        $x= strtotime($startDate);
        $y= strtotime($endDate);

        $company_id = Yii::app()->user->getState('company_branch_id');


        $query="select  dd.product_id , p.name   from  delivery as d
                    left join delivery_detail as dd ON d.delivery_id = dd.delivery_id 
                    left join product as p ON p.product_id = dd.product_id
                    where d.client_id = '$clientId' and dd.date != 'NULL' and dd.date between '$startDate' and '$endDate'
                    group by dd.product_id ";


        $productList =  Yii::app()->db->createCommand($query)->queryAll();

        $headingResult = array();

        foreach($productList as $value){
            array_push($headingResult,"Quantity","Amount");
        }
        /*foreach($productList as $value){
            $product_id = $value['product_id'];
            $query = "select  p.name as product_name , IFNULL(d.total_amount/sum(dd.quantity) , 0) as rate , d.date , d.total_amount  ,
              ifnull(dd.quantity , 0) as delivered_quantity , ifnull(dd.amount  , 0) as one_product_amount ,
              sum(dd.quantity) as totalquantity_delivered  from delivery as d
              left join client as c ON c.client_id = d.client_id
              left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
              left join product as p ON p.product_id =dd.product_id
              where d.client_id = '$clientId' and p.product_id ='$product_id'  and d.date between '$startDate' and  '$endDate'
              group by dd.product_id , d.delivery_id ";
            $bill_result  = Yii::app()->db->createCommand($query)->queryAll();
             $reportList = array();
             foreach($reportList as $value){
                 $oneObject = array();
                 $oneObject['quantity'] = $value['quantity'];
                 $oneObject['amount'] = $value['amount'];

             }
            die();*/
        /*  $finalData  = array();
          foreach ($productList as $product){
              $oneProduct = array();
               $product_id = $product['product_id'];
              $oneProduct['produt_name'] = $product['name'];
               $deliveryRecord = array();
              while($x < ($y+8640)){
                  $oneDayData = array();
                  $selectDate = date("Y-m-d", $x);
                  $oneDayData['date'] = $selectDate ;
                  $x += 86400;
                  $oneday_query = "select ifnull(dd.quantity , 0) as quantity , ifnull(dd.amount , 0) as amount from delivery d
                      left join delivery_detail as dd ON d.delivery_id = dd.delivery_id
                      where dd.date = '$selectDate' and dd.product_id = '$product_id' and d.client_id = '$clientId' ";
                  $oneday_result  = Yii::app()->db->createCommand($oneday_query)->queryAll();
                    if(isset($oneday_result[0]['quantity'])){
                        $oneDayData['quantity'] = $oneday_result[0]['quantity'] ;
                        $oneDayData['amount'] = $oneday_result[0]['amount'] ;
                    }else{
                        $oneDayData['quantity'] = '0';
                        $oneDayData['amount'] = '0';
                    }


                  $deliveryRecord[] = $oneDayData;
              }
              $oneProduct['deliveryObject'] =  $deliveryRecord;
              array_push($finalData,$oneProduct);
          }*/





        $finalData  = array();
        while($x < ($y+8640)){
            $oneDayData = array();
            $selectDate = date("Y-m-d", $x);

            array_push($oneDayData , $selectDate);
            $x += 86400;
            foreach ($productList as $product){
                $product_id = $product['product_id'];
                $oneday_query = "select ifnull(dd.quantity , 0) as quantity , ifnull(dd.amount , 0) as amount from delivery d
                                 left join delivery_detail as dd ON d.delivery_id = dd.delivery_id 
                                 where dd.date = '$selectDate' and dd.product_id = '$product_id' and d.client_id = '$clientId' ";

                $oneday_result  = Yii::app()->db->createCommand($oneday_query)->queryAll();

                if(isset($oneday_result[0]['quantity'])){
                    $quantity = $oneday_result[0]['quantity'] ;
                    $amount = $oneday_result[0]['amount'] ;

                    array_push($oneDayData , $quantity);
                    array_push($oneDayData , $amount);

                }else{
                    array_push($oneDayData , '0');
                    array_push($oneDayData , '0');
                }

            }




            $finalData[] = $oneDayData;
        }

        $query = " select z.name from client as c
                  left join zone as z ON z.zone_id = c.zone_id
                  where c.client_id = '$clientId' ";
        $reult = Yii::app()->db->createCommand($query)->queryScalar();

        $finalArray = array();
        $finalArray['finalData'] = $finalData ;
        $finalArray['productList'] = $productList ;
        $finalArray['headingResult'] = $headingResult ;
        $finalArray['Zone'] = $reult ;

        echo  json_encode($finalArray);
        die();
        while($x < ($y+8640)){

            $oneDayData = array();
            echo $selectDate.'<br>';

        }
        die();
        return json_encode($reportData);
    }


    public static function getProductPriceListFunction($client_id){
        $company_id = Yii::app()->user->getState('company_branch_id');

        $productQuery = "select p.product_id , p.name ,p.price ,IFNULL(cpp.price , 0)  as clientProductPrice from product as p
                         left join client_product_price as cpp ON p.product_id = cpp.product_id AND cpp.client_id = '$client_id'
                         where p.company_branch_id = '$company_id'";

        $productResult = Yii::app()->db->createCommand($productQuery)->queryAll();

        return json_encode($productResult);
    }

    public static function get_user_list(){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $productQuery = "SELECT u.full_name ,u.user_id FROM user AS u
                  WHERE u.company_id ='$company_id'";



        $productResult = Yii::app()->db->createCommand($productQuery)->queryAll();



        $result = array();
        foreach ($productResult as $value){
            $user_id = $value['user_id'];
            $full_name = $value['full_name'];

            $result[$user_id] =$full_name ;
        }

        return $result;



    }

    public static function test_date($data){


        $clientId = 100;
        $clientObject_forPrint =Client::model()->findByPk(intval($clientId))->attributes;
        //  $currentBalance = APIData::calculateFinalBalance($clientId);
        $currentBalance = 0;
        $startDate = "2019-07-01";

        $endDate = "2019-07-04";
        $nextMonthDate = date('Y-m-d', strtotime('5 day', strtotime($endDate)));

        $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
            where d.client_id = $clientId AND d.date < '$startDate' ";
        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totaldeliverySum = $deliveryResult[0]['deliverySum'];

        $queryDelivery ="Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
            where pm.client_id = $clientId AND pm.date < '$startDate' ";
        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totalRemaining = $deliveryResult[0]['remainingAmount'];
        $finalData = array();

        $finalData['openeningStock'] = $totaldeliverySum ;
        $finalData['totalRemaining'] = $totalRemaining ;
        $openingTotalBalance =  $totaldeliverySum - $totalRemaining ;

        $reportObject = array();
        $x= strtotime($startDate);
        $y= strtotime($endDate);
        $reportData = array();
        $oneDayData = array();
        $oneDayData['discription'] = 'OPENING BALANCE' ;
        $oneDayData['date'] = $startDate ;

        $oneDayData['delivery'] = $totaldeliverySum ;
        $oneDayData['reciveAmount'] = $totalRemaining ;
        $oneDayData['balance'] = $openingTotalBalance ;
        $reportData[] = $oneDayData ;

        while($x < ($y+8640)) {

            $oneDayData = array();
            $selectDate = date("Y-m-d", $x);

            $x += 86400;

            echo    $oneDayData['date'] = $selectDate;
            echo "<br>";
        }
    }

}