<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class dashbord_graph_data{

     public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];


     public static function get_daily_stock($startDate ,$end_date){


         $x= strtotime($startDate);

         $y= strtotime($end_date);

         $day = date("d");
         $month = date("M");
       //  $m =date("m");
        // $y =date("Y");
         $company_id = Yii::app()->user->getState('company_branch_id');
         $quantityObject = array();
         $amountObject = array();
         $oneObject_quantity = [$month, 'Quantity'];
         $oneObject_amount = [$month, 'Amount'];
         array_push($quantityObject ,$oneObject_quantity);
         array_push($amountObject ,$oneObject_amount);
         while($x < ($y+8640)){


             $todayDate = date("Y-m-d", $x);

             $Year = date("Y", $x);
             $Month = date("M", $x);

             $new_date = $Year."(".$Month.")";

             $x += 86400;

             // $todayDate =$y."-".$m."-".$x;
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
         /* die();
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
         }*/
         $result = array();
         $result['quantityObject'] = $quantityObject;
         $result['amountObject'] = $amountObject;
         return json_encode($result);

     }
     public static function get_daily_stock_main_page($startDate ,$end_date){


         $x= strtotime($startDate);

         $y= strtotime($end_date);

         $day = date("d");
         $month = date("M");
       //  $m =date("m");
        // $y =date("Y");
         $company_id = Yii::app()->user->getState('company_branch_id');
         $quantityObject = array();
         $amountObject = array();

         $style_object =array();

         $style_object["role"] ="style";

         $oneObject_quantity = [$month, 'Quantity',$style_object];
         $oneObject_amount = [$month, 'Amount',$style_object];

         array_push($quantityObject ,$oneObject_quantity);
         array_push($amountObject ,$oneObject_amount);

         while($x < ($y+8640)){


             $todayDate = date("Y-m-d", $x);

             $Year = date("Y", $x);
             $Month = date("M", $x);

             $new_date = $Year."(".$Month.")";
             $x += 86400;
             // $todayDate =$y."-".$m."-".$x;
             $query = "SELECT SUM(dd.quantity) AS total_quantity ,SUM(dd.amount) as amount FROM delivery AS d
              LEFT JOIN delivery_detail AS dd ON dd.delivery_id = d.delivery_id
              WHERE d.DATE ='$todayDate' AND d.company_branch_id='$company_id'";

             $quantity = Yii::app()->db->createCommand($query)->queryAll();
             if(sizeof($quantity)>0){
                 $total_quantity = $quantity[0]['total_quantity'] ;
                 $amount = $quantity[0]['amount'] ;
                 $oneObject_quantity = [$todayDate, intval($total_quantity), 'stroke-color: #703593; stroke-width: 1; fill-color:  #76A7FA'];
                 $oneObject_amount = [$todayDate, intval($amount), 'stroke-color: #703593; stroke-width: 1; fill-color:  #76A7FA'];
                 array_push($quantityObject ,$oneObject_quantity);
                 array_push($amountObject ,$oneObject_amount);
             }

         }
         /* die();
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
         }*/
         $result = array();
         $result['quantityObject'] = $quantityObject;
         $result['amountObject'] = $amountObject;
         return json_encode($result);

     }

     public static function get_new_customer_notification($get_data){

         $page = $get_data['page'];
         $company_id = Yii::app()->user->getState('company_branch_id');

        $query = "Select c.client_id ,
            c.created_at ,
            c.fullname ,
            c.cell_no_1 ,
           
            c.view_by_admin ,
            c.login_form  
            from client as c
         
            where c.company_branch_id = '$company_id'
             
            order by c.created_at  DESC
            limit 0, 20";


         $customer_list = Yii::app()->db->createCommand($query)->queryAll();

         $query_drop = " SELECT count(*) FROM client AS c
                WHERE c.company_branch_id ='$company_id'
                AND c.view_by_admin =0 ";


        // $total = Yii::app()->db->createCommand($query_drop)->queryScalar();
         $result = [];
         $result['customer_list'] = $customer_list;
        // $result['total'] = $total;

         return $customer_list;

     }
     public static function get_new_Cutomer_data($startDate ,$end_date){

         $x= strtotime($startDate);
         $y= strtotime($end_date);


         $day = date("d");

         $Year = date("Y", $x);
         $month = date("M", $x);

        // $m =date("m");
        // $y =date("Y");

         $company_id = Yii::app()->user->getState('company_branch_id');

         $finalObject = array();
         $oneObject = [$month, 'Added','Dropped'];
        array_push($finalObject ,$oneObject);
         while($x < ($y+8640)){

             $todayDate = date("Y-m-d", $x);
             $Year = date("Y", $x);
             $Month = date("M", $x);
             $new_date = $Year."(".$Month.")";
             $x += 86400;
             $query = "SELECT count(*)  FROM client AS c
                WHERE c.company_branch_id ='$company_id' AND 
                date(c.created_at) ='$todayDate' AND c.company_branch_id='$company_id' ";
             $quantity = Yii::app()->db->createCommand($query)->queryScalar();




             $query_drop = " SELECT count(*) FROM client AS c
                WHERE c.company_branch_id ='$company_id'
                AND c.is_active =0 AND c.deactive_date = '$todayDate' ";

             $drop_result = Yii::app()->db->createCommand($query_drop)->queryScalar();


             $oneObject = [$todayDate, intval($quantity) ,intval($drop_result)];
             array_push($finalObject ,$oneObject);
         }

         return json_encode($finalObject);

     }
     public static function get_new_complain_list(){

         $company_id = Yii::app()->user->getState('company_branch_id');

         $query = "SELECT com.* ,
                   cl.fullname , 
                  s.status_name  ,
                  comT.name from  complain as com
                LEFT JOIN  client as cl ON cl.client_id = com.client_id
                LEFT JOIN status as s ON s.status_id = com.status_id
                LEFT JOIN complain_type as comT ON comT.complain_type_id = com.complain_type_id 
                where com.type = '1' 
                and com.company_branch_id = '$company_id' 
                order by com.complain_id DESC
                LIMIT 20 OFFSET 0 ";
         $customer_list = Yii::app()->db->createCommand($query)->queryAll();
         return $customer_list;
     }


     public static function get_new_Cutomer_data_main_page($startDate ,$end_date){



        $x= strtotime($startDate);

        $y= strtotime($end_date);

        $day = date("d");
        $month = date("M");
        //  $m =date("m");
        // $y =date("Y");
        $company_id = Yii::app()->user->getState('company_branch_id');
        $quantityObject = array();
        $amountObject = array();

        $style_object =array();

        $style_object["role"] ="style";

        $oneObject_quantity = [$month, 'Quantity', $style_object];
        $oneObject_amount = [$month, 'Added',"Dropped",$style_object];

        array_push($quantityObject ,$oneObject_quantity);
        array_push($amountObject ,$oneObject_amount);

        while($x < ($y+8640)){


            $todayDate = date("Y-m-d", $x);

            $Year = date("Y", $x);
            $Month = date("M", $x);
            $new_date = $Year."(".$Month.")";
            $x += 86400;
            // $todayDate =$y."-".$m."-".$x;

            $query = "SELECT count(*) as total FROM client AS c
                WHERE c.company_branch_id ='$company_id' AND 
                date(c.created_at) ='$todayDate' AND c.company_branch_id='$company_id' ";
            $quantity = Yii::app()->db->createCommand($query)->queryAll();

            $query_drop = " SELECT count(*) FROM client AS c
                WHERE c.company_branch_id ='$company_id'
                AND c.is_active =0 AND c.deactive_date = '$todayDate' ";


            $drop_result = Yii::app()->db->createCommand($query_drop)->queryScalar();

            if(sizeof($quantity)>0){
                $total_quantity = $quantity[0]['total'] ;
                $amount = $quantity[0]['total'] ;
                $oneObject_quantity = [$todayDate, intval($total_quantity),intval($drop_result), 'stroke-color: #703593; stroke-width: 4; fill-color:  #76A7FA'];
                $oneObject_amount = [$todayDate, intval($amount), intval($drop_result),'stroke-color: #703593; stroke-width: 2; fill-color:  #76A7FA'];
                array_push($quantityObject ,$oneObject_quantity);
                array_push($amountObject ,$oneObject_amount);
            }

        }

        $result = array();
        $result['quantityObject'] = $quantityObject;
        $result['amountObject'] = $amountObject;
        return json_encode($result);

    }

     public static function get_year_sale_graph_data(){
         $day = date("d");
         $month = date("M");
         $m =date("m");
         $y =date("Y");
         $company_id = Yii::app()->user->getState('company_branch_id');
         $finalObject_quantity = array();
         $finalObject_amount = array();
         $oneObject_quantity = [$month, 'quantity'];
         $oneObject_amount = [$month, 'amount'];
         array_push($finalObject_quantity ,$oneObject_quantity);
         array_push($finalObject_amount ,$oneObject_amount);
         for($x=12 ;$x>=0 ;$x--){

             /*  echo date('Y-m-d', strtotime(date('Y-m-d')." -".$x." month"));
                //echo $x."<br>";
                echo "<br>";*/

             $todayDate =date('Y-m-d', strtotime(date('Y-m-d')." -".$x." month"));
             $month_name =  date("M",strtotime($todayDate));
             $month =  date("m",strtotime($todayDate));
             $year =  date("Y",strtotime($todayDate));

             $new_date = $month_name.",".$year;
             $query = "SELECT SUM(dd.quantity) AS total_quantity ,SUM(dd.amount) as amount FROM delivery AS d
               LEFT JOIN delivery_detail AS dd ON dd.delivery_id = d.delivery_id
               WHERE month(d.DATE) ='$month' AND year(d.DATE) ='$year' AND d.company_branch_id='$company_id';";



             $quantity = Yii::app()->db->createCommand($query)->queryAll();

             if(sizeof($quantity)>0){
                 $total_quantity = $quantity[0]['total_quantity'] ;
                 $amount = $quantity[0]['amount'] ;
                 $oneObject_quantity = [$new_date, intval($total_quantity)];
                 $oneObject_amount = [$new_date, intval($amount)];

                 array_push($finalObject_quantity ,$oneObject_quantity);

                 array_push($finalObject_amount ,$oneObject_amount);
             }
         }
         $result =array();
         $result['finalObject_quantity'] = $finalObject_quantity ;
         $result['finalObject_amount'] = $finalObject_amount ;
         return json_encode($result);

     }



}