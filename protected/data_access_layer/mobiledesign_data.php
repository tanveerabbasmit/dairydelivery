<?php


class mobiledesign_data
{
    public static function get_opening_stock($selectDate,$product_id){

        $querystck = "select
            ifnull(sum(ds.quantity) ,0) as total_recive 
            ,ifnull(sum(ds.wastage),0) as wastage
            ,ifnull(sum(ds.return_quantity) ,0) as return_quantity 
            from daily_stock as ds
            where ds.date < '$selectDate'  
              and ds.product_id = '$product_id'";


        $deliveryOneDayResult = Yii::app()->db->createCommand($querystck)->queryAll();
        $total_recive  = $deliveryOneDayResult[0]['total_recive'];
        $wastage  = $deliveryOneDayResult[0]['wastage'];
        $return_quantity  = $deliveryOneDayResult[0]['return_quantity'];

        $net_purchase = $total_recive - $wastage - $return_quantity;


        /* $querystck_rider= "SELECT

           ifnull((sum(ds.quantity) -sum(ds.return_quantity) -sum(ds.wastage_quantity)) ,0) as quantity,
          sum(ds.wastage_quantity) as total_wastage_quantity  FROM rider_daily_stock as  ds
          where ds.date < '$selectDate'  and ds.product_id = '$product_id'";*/


        $queryTotalCount = "SELECT SUM(dd.quantity) AS quantity
                    FROM delivery AS d 
                    LEFT JOIN delivery_detail AS dd 
                    ON dd.delivery_id =d.delivery_id
                    WHERE  d.date < '$selectDate' 
                    
                    AND  dd.product_id ='$product_id' ";


        $total_sale = Yii::app()->db->createCommand($queryTotalCount)->queryscalar();









        $net= $net_purchase - $total_sale;
        return $net;
    }

    public static function get_total_recived($selectDate,$product_id){

        $querystck = "select
                ifnull(sum(ds.quantity) ,0) as total_recive 
                ,ifnull(sum(ds.wastage),0) as wastage
                ,ifnull(sum(ds.return_quantity) ,0) as return_quantity 
                from daily_stock as ds
                where ds.date = '$selectDate'  
                and ds.product_id = '$product_id'";


        $deliveryOneDayResult = Yii::app()->db->createCommand($querystck)->queryAll();
        $total_recive  = $deliveryOneDayResult[0]['total_recive'];
        $wastage  = $deliveryOneDayResult[0]['wastage'];
        $return_quantity  = $deliveryOneDayResult[0]['return_quantity'];
        $net = $total_recive-$wastage-$return_quantity;
        return  round($net,0);
    }

    public static function one_date_total_sale($selectDate,$product_id){
        $query = "SELECT SUM(dd.quantity) AS quantity
                    FROM delivery AS d 
                    LEFT JOIN delivery_detail AS dd 
                    ON dd.delivery_id =d.delivery_id
                    WHERE  d.date = '$selectDate' 
                    
                    AND  dd.product_id ='$product_id' ";

        $result = Yii::app()->db->createCommand($query)->queryscalar();


        if($result){

            return $result;
        }else{
            return  0;
        }

    }
    public static function stock_out($selectDate,$product_id){

        $querystck_rider= "SELECT ifnull((sum(ds.quantity) -sum(ds.return_quantity) -sum(ds.wastage_quantity)) ,0) as quantity,
                   sum(ds.wastage_quantity) as total_wastage_quantity  FROM rider_daily_stock as  ds
                    where ds.date = '$selectDate'  and ds.product_id = '$product_id'";




        $deliveryOneDayResult_rider = Yii::app()->db->createCommand($querystck_rider)->queryAll();



        $total_rider_quantity  = $deliveryOneDayResult_rider[0]['quantity'];

        return $total_rider_quantity;
    }

    public static function payment_list($data){

        $start_date = $data['start_date'];

        $end_date = $data['end_date'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query = " select 
                  c.fullname ,
                 pm.amount_paid
                 from payment_master as pm
                 left join client as c ON c.client_id = pm.client_id  
                 where pm.date between  '$start_date' and '$end_date'
                   AND pm.company_branch_id ='$company_id'
                  order by pm.date DESC";


        $result = Yii::app()->db->createCommand($query)->queryAll();

        return $result;
    }
    public static function payment_list_main($selected_date){



        $company_id = Yii::app()->user->getState('company_branch_id');
        $query = " select 
            sum(pm.amount_paid) as amount_paid
            from payment_master as pm
            WHERE pm.date = '$selected_date' and  pm.company_branch_id = '$company_id' ";


        $result = Yii::app()->db->createCommand($query)->queryscalar();


        return $result;
    }
    public static function payment_list_total_count_opening($data){

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query = " select 
        sum(pm.amount_paid) as amount_paid
        from payment_master as pm
        where pm.date <  '$start_date' 
        and pm.company_branch_id ='$company_id'";



        $result = Yii::app()->db->createCommand($query)->queryscalar();

        return $result;
    }

    public static function get_one_payment($date){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query = " select 
                sum(pm.amount_paid) as amount_paid
                from payment_master as pm
               
                    where pm.date =  '$date' 
                    AND pm.company_branch_id ='$company_id'";



        $result = Yii::app()->db->createCommand($query)->queryscalar();



        return $result;
    }

    public static function total_payment_out_openig($start_date){


        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT 
              
                SUM(er.amount) as total_amount
             FROM expence_report AS er
          
            where er.company_id = '$company_id' 
              and er.date < '$start_date' ";


        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();


        $query_vendor = "SELECT 
            sum(p.amount)
            FROM vendor_payment AS p
            WHERE p.company_id = '$company_id'
            AND p.action_date < '$start_date' ";

        $vendor_result  = Yii::app()->db->createCommand($query_vendor)->queryscalar();


        $query_farm = "SELECT 
            
            sum(p.amount)
            FROM farm_payment AS p
           
            WHERE p.company_id = '$company_id'
            and p.action_date < '$start_date' ";



        $payment_farm = Yii::app()->db->createCommand($query_farm)->queryscalar();

        $net = $queryResult + $vendor_result + $payment_farm;
        return $net;
    }
    public static function total_payment_out_one_day($start_date){




        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT 
              
                SUM(er.amount) as total_amount
             FROM expence_report AS er
          
            where er.company_id = '$company_id' 
              and er.date = '$start_date' ";



        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();



        $query_vendor = "SELECT 
           
            sum(p.amount)
            FROM vendor_payment AS p
         
            WHERE p.company_id = '$company_id'
            AND p.action_date = '$start_date' ";




        $vendor_result  = Yii::app()->db->createCommand($query_vendor)->queryscalar();

        $query_farm = "SELECT 
            
            sum(p.amount)
            FROM farm_payment AS p
           
            WHERE p.company_id = '$company_id'
            and p.action_date = '$start_date' ";



        $payment_farm = Yii::app()->db->createCommand($query_farm)->queryscalar();

        $net = $queryResult + $vendor_result + $payment_farm;

        return $net;
    }

    public static function category_wise_sale($product_id,$selectDate,$category_list){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $final_list = [];
        $total_sale =0;




        foreach ($category_list as $value){

            $client_ids = $value['client_ids'];

            $queryTotalCount = "SELECT SUM(dd.quantity) AS total_amount
                    FROM delivery AS d 
                    LEFT JOIN delivery_detail AS dd 
                    ON dd.delivery_id =d.delivery_id
                    WHERE d.company_branch_id ='$company_id' 
                    AND d.date ='$selectDate' 
                    
                    AND d.client_id in ($client_ids)  AND dd.product_id ='$product_id' ";




            $queryResult =  Yii::app()->db->createCommand($queryTotalCount)->queryscalar();


            $value['sale_amount'] =0;
            if($queryResult){

                $value['sale_amount'] = round($queryResult,0);
            }



            $total_sale =$total_sale + $queryResult;
            if($value['sale_amount']>0){

                $final_list[] =$value;
            }

        }

        $result =[];
        $result['list'] =$final_list;
        $result['total_sale'] =round($total_sale,0);

        return $result;

    }
    public static function farm_wise_purchase($product_id,$selectDate,$category_list){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $final_list = [];
        $total_sale =0;



        foreach ($category_list as $value){




            $farm_id = $value['farm_id'];

            $query = "SELECT 
               
                sum(ds.quantity) AS quantity ,
                sum(ds.wastage) AS wastage,
                sum(ds.return_quantity) AS return_quantity
              
                FROM daily_stock AS ds 
              
                WHERE 
                ds.date ='$selectDate' and ds.company_branch_id='$company_id'
                and  ds.farm_id ='$farm_id'
                and ds.product_id ='$product_id' ";



            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


            $total_quantity =$queryResult[0]['quantity'] -$queryResult[0]['wastage'] -$queryResult[0]['return_quantity'];

            $value['sale_amount'] = $total_quantity;




            $total_sale =$total_sale + $total_quantity;
            if($value['sale_amount']>0){

                $final_list[] =$value;
            }

        }

        $result =[];
        $result['list'] =$final_list;
        $result['total_sale'] =round($total_sale,0);

        return $result;

    }

    public static function total_expence_between_date_range($data){

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT 
            et.type ,
            er.amount 
            FROM expence_report AS er
            left join expence_type as et 
                ON er.expenses_type_id = et.expence_type
            where er.company_id = '$company_id' 
            and er.date between '$start_date' and '$end_date'";




        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
        return $queryResult;

    }

    public static function farm_payment_list_between_date_range($data){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $start_date = $data['start_date'];

        $end_date = $data['end_date'];

        $object = "SELECT 
            'farm' AS type_name,
            p.amount,
            f.farm_name
            FROM farm_payment AS p
            LEFT join farm AS f ON p.farm_id = f.farm_id
            WHERE p.company_id = '$company_id'
            and p.action_date  between '$start_date' AND '$end_date'";



        $payment = Yii::app()->db->createCommand($object)->queryAll();

        return $payment;

    }
    public static function vendor_payment_list_between_date_range($data){

        $company_id = Yii::app()->user->getState('company_branch_id');
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $object = "SELECT 
            'vendor' as type_name,
            p.amount,
            f.vendor_name
            FROM vendor_payment AS p
            LEFT join vendor AS f ON p.vendor_id = f.vendor_id
            WHERE p.company_id = '$company_id'
            AND p.action_date between '$start_date' AND '$end_date' ";



        $payment = Yii::app()->db->createCommand($object)->queryAll();

        return $payment;

    }

    public static function find_total_count(
        $list_expence,
        $farm_payment,
        $vendor_payment)
    {
        $total_amount = 0;
        foreach ($list_expence as $value){
            $total_amount = $total_amount+  $value['amount'];
        }

        foreach ($farm_payment as $value){
            $total_amount = $total_amount  +  $value['amount'];
        }

        foreach ($vendor_payment as $value){

            $total_amount = $total_amount  + $value['amount'];
        }
        return $total_amount;

    }

}