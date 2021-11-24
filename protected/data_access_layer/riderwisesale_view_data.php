<?php


class riderwisesale_view_data
{
    public static function get_sale_list_of_rider($selectDate){

       $rider_list =  riderDailyStockData::getRiderList();

        $delivery_list =[];
       foreach ($rider_list as $value){
           $rider_id = $value['rider_id'];

           $clientQuery = "Select c.client_id,c.address , c.cell_no_1 ,c.fullname ,z.name as zone_name from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           where rz.rider_id = '$rider_id'  ";
           $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
           $clientIDListOBject = array();
           $clientIDListOBject[] = 0;
           foreach($clientResult as $clientValue){
               $clientIDListOBject[] = $clientValue['client_id'];
           }

           $clientIDList = implode(",",$clientIDListOBject);

           $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum 
                              ,IFNULL(sum(dd.quantity),0) AS total_quantity   from delivery as d
                             LEFT JOIN delivery_detail AS dd on  d.delivery_id = dd.delivery_id  
                              where d.client_id in ($clientIDList)  AND d.date = '$selectDate' ";


           $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
           $totaldeliverySum = round($deliveryResult[0]['deliverySum'],2);
           $total_quantity = round($deliveryResult[0]['total_quantity'],2);

           $one_object = [];
           $one_object['total_sale'] =$totaldeliverySum;
           $one_object['total_quantity'] =$total_quantity;
           $delivery_list[] = $one_object;
          // $delivery_list[] = $total_quantity;
          // $delivery_list[] = $totaldeliverySum;

       }

       return $delivery_list;
    }

    public static function sale_and_quantity_find_function($main_object){
        $mian_object =[];
        foreach($main_object as $value){
            $one_object = [] ;

            $date = $value['date'];

            $one_object[] = $date;

            $sale_list = $value['sale_list'];

            foreach ($sale_list as $value_1){
                $total_quantity = $value_1['total_quantity'];
                $total_sale = $value_1['total_sale'];

                $one_object[] = round($total_quantity,2);
                $one_object[] = round($total_sale,2);
            }

            $mian_object[] = $one_object;
        }
        return $mian_object;
    }
    public static function total_count($main_object){

           $grand_total =[];
           foreach ($main_object as $value){

              $sale_list =    $value['sale_list'];

              foreach ($sale_list as $key=>$value_2){
                  $grand_total[$key] =0;
              }

           }

          foreach ($main_object as $value){

            $sale_list =    $value['sale_list'];

            foreach ($sale_list as $key=>$value_2){
                $grand_total[$key] = $grand_total[$key] + $value_2['total_sale'];
            }
         }


          return $grand_total;
    }
    public static function total_count_quantity_amount($main_object){




           $grand_total =[];

           foreach ($main_object as $value_1){

               foreach ($value_1 as $key=>$value){
                   if($key>0){
                       $grand_total[$key] =0;
                   }
               }


           }

        foreach ($main_object as $value_1){

            foreach ($value_1 as $key=>$value){
                if($key>0){
                    $grand_total[$key] = $grand_total[$key] + $value;
                }
            }

        }

         return $grand_total;

    }
    public static function total_count_quantity_amount_quantity($main_object){




       $total_quantity = 0;
       $total_amount = 0;

        foreach ($main_object as $key=>$value){

            if($key % 2 == 1){
                $total_quantity =$total_quantity + $value;
            }else{
                $total_amount =$total_amount + $value;
            }

        }


        $result = [];
        $result['total_quantity'] = $total_quantity;
        $result['total_amount'] = $total_amount;

         return $result;

    }
}