<?php


class portal_radier_daily_delivery_class
{

    public static function get_today_client_halt($todaydate,$company_id,$riderID){

        $query = "SELECT
            h.client_id
            FROM halt_regular_orders  AS h
            LEFT JOIN client AS c 
            ON c.client_id = h.client_id
            WHERE c.company_branch_id =$company_id
            AND 
            '$todaydate' BETWEEN h.start_date AND h.end_date" ;

           $productList =  Yii::app()->db->createCommand($query)->queryAll();

           $client_object = [];
          foreach ($productList as $value){

              $client_id = $value['client_id'];

              $client_object[$client_id] = true;

          }

          return $client_object;
    }

    public static function getRiderDialyDeliveryReport_for_portal($data){


        $company_id = Yii::app()->user->getState('company_branch_id');

        $company_object = Company::model()->findByPk($company_id);

        $show_only_schedual_product = $company_object['show_only_schedual_product'];

        $show_only_schedual_product =  $data['scheduled_customer'];

        $product_id =  $data['product_id'];

        $payment_term_id =  $data['payment_term_id'];

        $riderID =$data['RiderID'];
        $todaydate =  $data['date'];
        $halt_client_list = portal_radier_daily_delivery_class::get_today_client_halt($todaydate,$company_id,$riderID);
        $filter_by =  $data['filter_by'];
        $timestamp = strtotime($todaydate);
        $day = date('D', $timestamp);
        $todayfrequencyID = '';
        if($day == 'Mon'){
            $todayfrequencyID = 1 ;
        }elseif($day == 'Tue'){
            $todayfrequencyID = 2;
        }elseif($day == 'Wed'){
            $todayfrequencyID = 3 ;
        }elseif($day == 'Thu'){
            $todayfrequencyID = 4 ;
        }elseif($day == 'Fri'){
            $todayfrequencyID = 5 ;
        }elseif($day == 'Sat'){
            $todayfrequencyID = 6 ;
        }else{
            $todayfrequencyID = 7 ;
        }


        /* $jd = cal_to_jd(CAL_GREGORIAN,date("m"),date("d"),date("Y"));
        $todayDay=(jddayofweek($jd,1));
        $dayFrequency = Frequency::model()->findByAttributes(array('day_name'=>$todayDay));
        $todayfrequencyID = $dayFrequency['frequency_id'] ;*/
        //  $todaydate =  date("Y-m-d");


        $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_id and p.bottle = 0";
        if($product_id>0){
            $query .=" and p.product_id ='$product_id'";
        }
        $productList =  Yii::app()->db->createCommand($query)->queryAll();

        if(false){
            $todayDate = date("Y-m-d");
            $clientQuery = "Select c.created_at , c.is_active , c.client_type , c.client_id,c.address , c.cell_no_1 ,
                c.fullname ,z.name as zone_name from rider_zone as rz
                Right join client as c ON c.zone_id = rz.zone_id
                left join zone as z ON z.zone_id = c.zone_id
                where rz.rider_id = '$riderID' and c.client_type = 1
                union
                Select c.created_at , c.is_active , c.client_type , c.client_id,c.address , c.cell_no_1 ,
                c.fullname ,z.name as zone_name from rider_zone as rz
                Right join client as c ON c.zone_id = rz.zone_id
                left join zone as z ON z.zone_id = c.zone_id
                where rz.rider_id = '$riderID' and c.client_type =2 and date(c.created_at) ='$todayDate' ";

        }else{

            $clientQuery = "Select 
                          cg.category_name,
                          c.is_active , 
                          c.client_type ,
                           c.client_id,c.address , 
                           c.cell_no_1 ,
                           c.fullname ,
                          z.name as zone_name,
                          pt.payment_term_name
                           from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           LEFT JOIN customer_category AS cg 
                               ON cg.customer_category_id= c.customer_category_id

	                       LEFT JOIN payment_term AS pt 
	                           ON pt.payment_term_id = c.payment_term

                           where rz.rider_id = $riderID ";

            if($payment_term_id>0){
                $clientQuery .= " and c.payment_term ='$payment_term_id' ";
            }

            if($filter_by>0){
                if($filter_by ==1){
                    $clientQuery .= " order by c.fullname ASC";
                }
                if($filter_by ==2){
                    $clientQuery .= " order by c.rout_order ASC ";
                }
            }else{
                $clientQuery .= " order by c.rout_order ASC ,c.fullname ASC ";
            }





        }





        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
        $cientID = array();
        $cientID[] = 0;
        foreach($clientResult as $value){
            $cientID[] =  $value['client_id'];
        }

        $lientID_list = implode(',',$cientID);

        $future_date_quanitiy_object = array();

        if($todaydate >date("Y-m-d")){
            $future_date_quanitiy_object = setEffectiveDateSchedule::getFutureDateQuantity($lientID_list,$todaydate);


        }



        $intervalDefalutData_clientList = intervalDefalutData::intervalDefault($lientID_list);

        date_default_timezone_set("Asia/Karachi");
        $today_Server_Time =date("Y-m-d");
        if($todaydate > $today_Server_Time){
            $effectiveDate_intervat_clientList = effectiveDateScheduleData::effectiveDate_interval_Client_list($todaydate,$lientID_list);
            $effectiveDate_weekly_clientList = effectiveDateScheduleData::effectiveDate_weekly_Client_list($todaydate,$lientID_list);
        }




        setEffectiveDateSchedule::checkEffectiveDateSchedule($company_id);

        $notDelivery_finalResult = todayDeliveryQuantityCountForApi::getNotDeliveryTodayResult($lientID_list ,$todaydate);


        $clientPriceObject  = todayDeliveryQuantityCountForApi::getClientBaseProductPRize_portal($lientID_list,$todaydate);

        $clientTotalDeliveredToday = todayDeliveryQuantityCountForApi::getDeliveryTodayAllClient($lientID_list ,$todaydate);
        //   var_dump($clientTotalDeliveredToday);
        //      die();
        $final_view_result = array();



        foreach($clientResult as $clientValue){
            $duplicateClient = 0;

            $is_active = $clientValue['is_active'];

            foreach($productList as $productvalue){

                $effectiove_future_schedule = false;
                $client_id = $clientValue['client_id'];
                $product_id =  $productvalue['product_id'] ;
                $client_product = $client_id.$product_id ;

                if($todaydate > $today_Server_Time){
                    if(isset($effectiveDate_intervat_clientList[$client_product])){
                        $effectiove_future_schedule =true;
                    }
                    if(isset($effectiveDate_weekly_clientList[$client_product])){
                        $effectiove_future_schedule =true;
                    }
                }

                $product_incex = $clientValue['client_id']."_".$productvalue['product_id'];
                $one_array_result = array();
                $client_id = $clientValue['client_id'];
                $one_array_result['client_type'] = $clientValue['client_type'];
                $one_array_result['zone_name'] = $clientValue['zone_name'];
                $one_array_result['client_id'] = $clientValue['client_id'];
                $one_array_result['address'] = $clientValue['address'] ;
                $one_array_result['payment_term_name'] = $clientValue['payment_term_name'] ;
                if($client_id !=$duplicateClient ){
                    if($effectiove_future_schedule){
                        $one_array_result['fullname'] = $clientValue['fullname']."(#)" ;
                        $one_array_result['cell_no_1'] = $clientValue['cell_no_1'] ;
                    }else{
                        $one_array_result['fullname'] = $clientValue['fullname'] ;
                        $one_array_result['cell_no_1'] = $clientValue['cell_no_1'] ;
                    }
                }else{
                    $one_array_result['fullname'] = $clientValue['fullname'];
                    $one_array_result['cell_no_1'] = '';
                }

                $duplicateClient = $client_id ;
                $one_array_result['product_id'] = $productvalue['product_id'];
                $one_array_result['productName'] = $productvalue['name'];

                if(isset($clientPriceObject[$product_incex])){
                    $one_array_result['price'] = $clientPriceObject[$product_incex];
                }else{
                    $one_array_result['price'] = $productvalue['price'];
                }
                //  $totalInterval_quantity =  utill::getOneCustomerTodayIntervalSceduler_with_date( $clientValue['client_id'],$productvalue['product_id'] ,$todaydate);
                $totalInterval_quantity =  future_selet_date::getOneCustomerTodayIntervalSceduler_with_date_future_date($clientValue['client_id'],$productvalue['product_id'] ,$todaydate);

                if($totalInterval_quantity ==0){
                    if(isset($intervalDefalutData_clientList[$client_product])){
                        $totalInterval_quantity = $intervalDefalutData_clientList[$client_product];
                    }
                }
                $totalWeekly_quantity  =    todayDeliveryQuantityCountForApi::getTodayDeliveryCountWeeklyRegularAndSpecial($clientValue['client_id'] ,$productvalue['product_id'], $todaydate);
                if(isset($future_date_quanitiy_object[$client_product])){
                    $one_array_result['regularQuantity'] = $future_date_quanitiy_object[$client_product] ;
                }else{
                    $one_array_result['regularQuantity'] = $totalInterval_quantity + $totalWeekly_quantity ;
                }
                $totalSpecialToday_quantity  =    todayDeliveryQuantityCountForApi::getTodaySpecialOrder($clientValue['client_id'] ,$productvalue['product_id'] ,$todaydate);
                $one_array_result['totalSpecialQuantity'] = $totalSpecialToday_quantity;
                /*not delvery*/
                if(isset($clientTotalDeliveredToday[$product_incex])){
                    $one_array_result['deliveredQuantity'] = $clientTotalDeliveredToday[$product_incex]['deliveredQuantity'] ;
                    $one_array_result['time'] = $clientTotalDeliveredToday[$product_incex]['time'] ;
                    $one_array_result['price'] = $clientTotalDeliveredToday[$product_incex]['delivery_rate'] ;
                    $one_array_result['edit_by_user'] = $clientTotalDeliveredToday[$product_incex]['edit_by_user'] ;
                    if( $one_array_result['edit_by_user'] >0){
                        $one_array_result['edit_by_color'] ='yes';
                        $one_array_result['edit_by_color_name'] ='rosybrown';
                        $user =User::model()->findByPk(intval($one_array_result['edit_by_user']));
                        if($user){
                            $one_array_result['edit_by_name'] =$user['full_name'];
                        }else{
                            $one_array_result['edit_by_name'] ='No Found';
                        }
                    }else{
                        $one_array_result['edit_by_color'] ='not';
                        $one_array_result['edit_by_color_name'] ='';
                    }
                }else{
                    $one_array_result['deliveredQuantity'] = 0;
                    $one_array_result['edit_by_color'] ='not';
                }
                if(isset($notDelivery_finalResult[$client_id]) and $one_array_result['deliveredQuantity'] == 0 ){
                    // var_dump($notDelivery_finalResult[$client_id]['time']);
                    //  die();
                    $one_array_result['time'] = $notDelivery_finalResult[$client_id]['time'];
                    $one_array_result['reasonType_name'] = $notDelivery_finalResult[$client_id]['reasonType_name'];
                    $one_array_result['reject_delivery'] = true;

                }else{
                    //   $one_array_result['time'] = $value['time'] ;
                    $one_array_result['reasonType_name'] = '';
                    $one_array_result['reasonType_name'] = '';
                    $one_array_result['reject_delivery'] = false;
                }
                $one_array_result['category_name'] = $clientValue['category_name'];
                if($one_array_result['deliveredQuantity'] >0 OR $is_active==1 ){
                    if(!isset($halt_client_list[$client_id])){
                        $regularQuantity = $one_array_result['regularQuantity'] + $one_array_result['totalSpecialQuantity'];
                        if($show_only_schedual_product==0){

                            $final_view_result[] = $one_array_result;

                        }else{
                            if($regularQuantity>0){
                                $final_view_result[] = $one_array_result;
                            }
                        }
                    }
                }
            }
        }
        echo json_encode($final_view_result);
    }

}