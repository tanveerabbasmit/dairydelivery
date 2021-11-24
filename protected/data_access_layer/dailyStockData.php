<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 10:15 AM
 */

class dailyStockData{


    public static function getDailyStock($date=false)
    {
        $dailyStockList = array();
        if (!$date) {
            $date = date("Y-m-d");
        }
        $companyBranchId = Yii::app()->user->getState('company_branch_id');
        $sql = "SELECT ds.*,p.`name` as product_name, SUM(ds.`quantity`) AS total_quantity, SUM(ds.`wastage`) AS total_wastage, SUM(ds.`return_quantity`) AS total_return_quantity FROM daily_stock AS ds 
			INNER JOIN product AS p ON (p.`product_id`=ds.`product_id`) 
			WHERE ds.`company_branch_id`=$companyBranchId AND ds.`date`='$date'
			GROUP BY ds.`product_id`
			";

        $dailyStockList = Yii::app()->db->createCommand($sql)->queryAll();

        return $dailyStockList;
    }


    public static function getCompanyDateRangeStock($start_date,$end_date)
    {
        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="select p.name as product_name ,ifnull(sum(ds.quantity),0) as quantity ,  ifnull(sum(ds.wastage),0) as wastage 
            ,ifnull(sum(ds.return_quantity),0) as return_quantity  from daily_stock as ds
            left join product as p ON p.product_id =ds.product_id
            where ds.date between '$start_date' and '$end_date' and ds.company_branch_id='$company_id'
            group by ds.product_id ";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();

        $fianl_result= array();

         $total_quantity = 0;
         $total_wastage = 0;
         $total_return_quantity = 0;
         $total_net_stock = 0;
        foreach ($productList as $value){
            $oneObject = array();
            $oneObject['product_name'] = $value['product_name'];
            $oneObject['quantity'] = $value['quantity'];
            $oneObject['wastage'] = $value['wastage'];
            $oneObject['return_quantity'] = $value['return_quantity'];
            $oneObject['net_stock'] = $value['quantity']-$value['wastage']-$value['return_quantity'];

            $total_quantity = $total_quantity + $value['quantity'];

            $total_return_quantity = $total_return_quantity + $value['return_quantity'];

            $total_wastage = $total_wastage +  $value['wastage'];

            $total_net_stock = $total_net_stock +   $oneObject['net_stock'] ;

            $fianl_result[] = $oneObject;

        }

         $total_object = array();

        $total_object['quantity'] =  $total_quantity;
        $total_object['return_quantity'] =  $total_return_quantity;
        $total_object['wastage'] =  $total_wastage;
        $total_object['net_stock'] =  $total_net_stock;


        $result =array();
         $result['productList']=$fianl_result;
         $result['total']=$total_object;

        return $result;

    }
    public static function ProductList()
    {

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT '' as purchase_rate, p.product_id, p.name ,p.unit from   product as p 
                where p.company_branch_id =$company_id and bottle = 0 ";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();
        return ($productList);

    }
    public static function getProductList()
    {
        $res = array();
        $models = Product::model()->findAllByAttributes(array(
            'company_branch_id'=> Yii::app()->user->getState('company_branch_id')
        ));

        foreach ($models as $model) {

            $stockModel = array(
                'daily_stock_id' => 0,
                'product_id' => $model->product_id,
                'description' => '',
                'quantity' => '',
                'date' => date("Y-m-d"),
                'return_quantity' => 0,
                'select' => true,
            );

            $res [] = array(
                'product_id' => $model->product_id,
                'name' => $model->name,
                'stockModel' => $stockModel,
            );

        }
        return $res;
    }

    public static function riderReconcileStock($today ,$rider_id){

        $riderObject =Rider::model()->findByPk(intval($rider_id));

        $company_id = Yii::app()->user->getState('company_branch_id');

        $result = array();

        $result['rider_name'] = $riderObject['fullname'];

        $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_id and bottle = 0 ";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();
        $result['color'] = '' ;
             $productObject = array();
        foreach ($productList as $productValue){
               $oneProduct = array();
            $product_id = $productValue['product_id'];

            $stockQuery = "select ifnull(sum(s.quantity) ,0) as received ,ifnull(sum(s.return_quantity),0) as returned from rider_daily_stock as s
                where s.rider_id = $rider_id and s.product_id = $product_id and s.date = '$today'";

            $productList =  Yii::app()->db->createCommand($stockQuery)->queryAll();
            $productObject[] = $productList[0]['received'];

              $recive= $productList[0]['received'];
              $returned = $productList[0]['returned'];
            $delivery_Query = "select ifnull(sum(dd.quantity) ,0) as quantity from delivery as d
                    left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                    where d.rider_id= '$rider_id' and dd.product_id = '$product_id' and d.date ='$today' ";
            $delivery_result =  Yii::app()->db->createCommand($delivery_Query)->queryAll();
            $productObject[] = $delivery_result[0]['quantity'];
            $productObject[] = $productList[0]['returned'];
            $quantity = $delivery_result[0]['quantity'];
             if($recive > ($returned+$quantity )){
                 $result['color'] = 'red' ;
             }

            $productObject[] = $recive -($returned+$quantity );

        }
        $result['productList'] = $productObject ;


        return json_encode($result);

    }
    public static function riderReconcileStock2($data){
        $todate = $data['todate'];

        $DayNumber = -($data['DayNumber']);

        $get_cuurent_date = date('Y-m-d', strtotime($DayNumber.' day', strtotime($todate)));


        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT r.*  from rider as r
            where r.company_branch_id = $company_id
            order by r.fullname ASC ";

        $riderList =  Yii::app()->db->createCommand($query)->queryAll();

        $finalObject = array();
         $delvery_Record_object = array();
          $date_check = true ;
        foreach($riderList as $riderValue){

            $rider_id = $riderValue['rider_id'];
            $result = array();
            $result['rider_id'] = $riderValue['rider_id'];
            $result['rider_name'] = $riderValue['fullname'];
            if($date_check){
                $result['date'] = $get_cuurent_date;
            }else{
                $result['date'] = $get_cuurent_date;
            }
            $date_check = false;


            $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_id and bottle = 0 ";
            $productList =  Yii::app()->db->createCommand($query)->queryAll();


            $result['color'] = '' ;
            $productObject = array();

            foreach ($productList as $productValue){
                $oneProduct = array();
                $product_id = $productValue['product_id'];

                $stockQuery = "select 
                  ifnull(sum(s.quantity) ,0) as received ,
                  ifnull(sum(s.wastage_quantity) ,0) as wastage_quantity ,
                  ifnull(sum(s.return_quantity),0) as returned
                   from rider_daily_stock as s
                where s.rider_id = $rider_id and s.product_id = $product_id and s.date = '$get_cuurent_date'";

                $productList =  Yii::app()->db->createCommand($stockQuery)->queryAll();
                $productObject[] = $productList[0]['received'];

                $recive= $productList[0]['received'];
                $returned = $productList[0]['returned'];

                $delivery_Query = "select ifnull(sum(dd.quantity) ,0) as quantity
                    from delivery as d
                    left join delivery_detail as dd 
                    ON dd.delivery_id = d.delivery_id
                    where d.rider_id= '$rider_id' 
                    and dd.product_id = '$product_id' 
                    and d.date ='$get_cuurent_date' ";

                $delivery_result =  Yii::app()->db->createCommand($delivery_Query)->queryAll();
                $productObject[] = $delivery_result[0]['quantity'];
                $productObject[] = $productList[0]['returned'];
                $productObject[] = $productList[0]['wastage_quantity'];
                $quantity = $delivery_result[0]['quantity'];
                if($recive > ($returned+$quantity )){
                    $result['color'] = 'red' ;
                }

                $productObject[] = $recive -($returned+$quantity+$productList[0]['wastage_quantity']);

            }
            $result['productList'] = $productObject ;

            $delvery_Record_object[] =$result;

        }

        $finalObject['delvery_Record_object'] = $delvery_Record_object ;
        $finalObject['delvery_total'] =  dailyStockData::riderReconcileStock_total($get_cuurent_date) ;

        return json_encode($finalObject);


    }
    public static function riderReconcileStock2_month($data){

            $startDate = $data['startDate'];
            $endDate = $data['endDate'];

           $todate = date("y-m-d");
          $DayNumber = -($data['DayNumber']);
           $get_cuurent_date = date('Y-m-d', strtotime($DayNumber.' day', strtotime($todate)));


        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT r.*  from rider as r
               where r.company_branch_id = $company_id
                order by r.fullname ASC ";
        $riderList =  Yii::app()->db->createCommand($query)->queryAll();

        $finalObject = array();
         $delvery_Record_object = array();
          $date_check = true ;
        foreach($riderList as $riderValue){

            $rider_id = $riderValue['rider_id'];
            $result = array();
            $result['rider_id'] = $riderValue['rider_id'];
            $result['rider_name'] = $riderValue['fullname'];
            if($date_check){
                $result['date'] = $get_cuurent_date;
            }else{
                $result['date'] = $get_cuurent_date;
            }
            $date_check = false;


            $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_id and bottle = 0 ";
            $productList =  Yii::app()->db->createCommand($query)->queryAll();


            $result['color'] = '' ;
            $productObject = array();

            foreach ($productList as $productValue){
                $oneProduct = array();
                $product_id = $productValue['product_id'];

               $stockQuery = "select ifnull(sum(s.quantity) ,0) as received ,ifnull(sum(s.wastage_quantity) ,0) as wastage_quantity ,ifnull(sum(s.return_quantity),0) as returned from rider_daily_stock as s
                where s.rider_id = $rider_id and s.product_id = $product_id and s.date between '$startDate' and  '$endDate'";

                $productList =  Yii::app()->db->createCommand($stockQuery)->queryAll();
                $productObject[] = $productList[0]['received'];

                $recive= $productList[0]['received'];
                $returned = $productList[0]['returned'];

                $wastage_quantity = $productList[0]['wastage_quantity'];

                $delivery_Query = "select ifnull(sum(dd.quantity) ,0) as quantity from delivery as d
                    left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                    where d.rider_id= '$rider_id' and dd.product_id = '$product_id' and d.date between '$startDate' and  '$endDate'";


                $delivery_result =  Yii::app()->db->createCommand($delivery_Query)->queryAll();
                $productObject[] = $delivery_result[0]['quantity'];
                $productObject[] = $productList[0]['returned'];
                $quantity = $delivery_result[0]['quantity'];
                if($recive > ($returned+$quantity+$wastage_quantity )){
                    $result['color'] = 'red' ;
                }
                $productObject[] = $wastage_quantity;
                $productObject[] = $recive -($returned+$quantity +$wastage_quantity);

            }
            $result['productList'] = $productObject ;

            $delvery_Record_object[] =$result;

        }

        $finalObject['delvery_Record_object'] = $delvery_Record_object ;


        $finalObject['delvery_total'] =  dailyStockData::riderReconcileStock_total_month($startDate ,$endDate) ;


        return json_encode($finalObject);


    }
    public static function riderReconcileStock_total($today){

        $riderObject =riderDailyStockData::getRiderList();
          $rider_id = array();
           $rider_id[] =0;
        foreach($riderObject as $value){
            $rider_id[] = $value['rider_id'];
        }
        $riderID_list = implode(',',$rider_id);

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_id and bottle = 0 ";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();
             $productObject = array();
        foreach ($productList as $productValue){
               $oneProduct = array();
            $product_id = $productValue['product_id'];

            $stockQuery = "select ifnull(sum(s.quantity) ,0) as received ,ifnull(sum(s.return_quantity),0) as returned from rider_daily_stock as s
                where s.rider_id in  ($riderID_list) and s.product_id = $product_id and s.date = '$today'";

            $productList =  Yii::app()->db->createCommand($stockQuery)->queryAll();
            $productObject[] = $productList[0]['received'];

              $recive= $productList[0]['received'];
              $returned = $productList[0]['returned'];
            $delivery_Query = "select ifnull(sum(dd.quantity) ,0) as quantity from delivery as d
                    left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                    where d.rider_id in  ($riderID_list) and dd.product_id = '$product_id' and d.date ='$today' ";
            $delivery_result =  Yii::app()->db->createCommand($delivery_Query)->queryAll();
            $productObject[] = $delivery_result[0]['quantity'];
            $productObject[] = $productList[0]['returned'];
            $quantity = $delivery_result[0]['quantity'];


            $productObject[] = $recive -($returned+$quantity );

        }


           return  ($productObject);


    }
    public static function riderReconcileStock_total_month($startDate ,$endDate){

        $riderObject =riderDailyStockData::getRiderList();
          $rider_id = array();
           $rider_id[] =0;
        foreach($riderObject as $value){
            $rider_id[] = $value['rider_id'];
        }
        $riderID_list = implode(',',$rider_id);

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_id and bottle = 0 ";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();
             $productObject = array();
        foreach ($productList as $productValue){
            $oneProduct = array();
            $product_id = $productValue['product_id'];

           $stockQuery = "select ifnull(sum(s.quantity) ,0) as received ,
                ifnull(sum(s.wastage_quantity) ,0) as wastage_quantity ,ifnull(sum(s.return_quantity),0) as returned 
                from rider_daily_stock as s
                where s.rider_id in  ($riderID_list) and s.product_id = $product_id
                and s.date between '$startDate' and '$endDate' ";

            $productList =  Yii::app()->db->createCommand($stockQuery)->queryAll();
            $productObject[] = $productList[0]['received'];

              $recive= $productList[0]['received'];
              $returned = $productList[0]['returned'];
              $wastage_quantity = $productList[0]['wastage_quantity'];
          $delivery_Query = "select ifnull(sum(dd.quantity) ,0) as quantity from delivery as d
                    left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                    where d.rider_id in  ($riderID_list) and dd.product_id = '$product_id'  and d.date between '$startDate' and '$endDate'  ";

            $delivery_result =  Yii::app()->db->createCommand($delivery_Query)->queryAll();
            $productObject[] = $delivery_result[0]['quantity'];
            $productObject[] = $productList[0]['returned'];
            $productObject[] = $productList[0]['wastage_quantity'];
            $quantity = $delivery_result[0]['quantity'];


            $productObject[] = $recive -($returned+$quantity +$wastage_quantity);

        }

           return  ($productObject);


    }

    public static function riderReconcileStock_previousdate($today){
        $class_name = array (1=>'label label-success',2=>'label label-info',3=>'label label-success',4=>'label label-info',5=>'label label-success',6=>'label label-info',7=>'label label-success',8=>'label label-info',9=>'label label-success',10=>'label label-info',11=>'Nov',12=>'Dec');

        $arrayLenght = 10 ;
          $dateObject = array();
        for($arrayLenght ; $arrayLenght >0 ; $arrayLenght--){
             $x= -($arrayLenght);
               $select_date = date('Y-m-d', strtotime($x.' day', strtotime($today)));
              $oneObject = array();
              $oneObject['date'] = $select_date;
              $oneObject['class'] = $class_name[$arrayLenght];
              $dateObject[] = $oneObject;

        }
          return json_encode($dateObject);
    }


}