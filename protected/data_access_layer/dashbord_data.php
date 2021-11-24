<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 11/29/2017
 * Time: 5:31 PM
 */
class dashbord_data
{

    public static function dashboard_widget_user($user_id){

        $object =DashboardWidgetUser::model()->findAllByAttributes([
            'user_id'=>$user_id
        ]);

        $final_result = [];

        foreach ($object as $value){
            $dashboard_widget_list_id =$value['dashboard_widget_list_id'];
            $final_result[$dashboard_widget_list_id] = true;
        }
       return $final_result;

    }
    public static function getCustomerData(){

        $post = date("M-Y");

        $productList = productData::getproductList_arrayForm($page =false);

        $company_id = Yii::app()->user->getState('company_branch_id');
        $resultObject = array();
        for($x= 5; $x>=0;$x--){
            $getNO = -($x);

             $month =  strtoupper(date('m', strtotime($post. $getNO." Month")));


            $query = "select count(*) as total_customer from delivery as d 
               where month(d.date) = '$month'  and d.company_branch_id = '$company_id' ";
            $queryResult =  Yii::app()->db->createCommand($query)->queryScalar();

            $resultObject[] = ($queryResult);
        }
        $oneObject = array();
        $oneObject['label'] = 'Customer';
        $oneObject['backgroundColor'] = '#3e95cd';
        $oneObject['data'] = $resultObject;
         $final_result = array();
        array_push($final_result,$oneObject);

        foreach($productList as $key=>$value){
            $getColorName =dashbord_data::getColorName($key);
            $product_id = $value['product_id'];
            $product_name = $value['name'];

            $oneObject = array();
            $oneObject['label'] = $product_name ;
            $oneObject['backgroundColor'] = $getColorName;

            $resultObject = array();
            for($x= 5; $x>=0;$x--){
                $getNO = -($x);
                $month =  strtoupper(date('m', strtotime($post. $getNO." Month")));
                $query = "select sum(dd.amount) from delivery as d
                   left join delivery_detail as dd ON d.delivery_id =dd.delivery_id
                   where d.company_branch_id =$company_id and month(d.date) = $month and  dd.product_id =$product_id";
                $queryResult =  Yii::app()->db->createCommand($query)->queryScalar();
                $resultObject[] = $queryResult;
            }
            $oneObject['data'] = $resultObject;

            array_push($final_result,$oneObject);

        }

        return  json_encode($final_result);

    }
    public static function getCustomerData2(){

        $post = date("M-Y");

        $productList = productData::getproductList_arrayForm($page =false);

        $company_id = Yii::app()->user->getState('company_branch_id');
        $resultObject = array();
        for($x= 5; $x>=0;$x--){
            $getNO = -($x);

             $month =  strtoupper(date('m', strtotime($post. $getNO." Month")));


            $query = "select count(*) as total_customer from delivery as d 
               where month(d.date) = '$month'  and d.company_branch_id = '$company_id' ";
            $queryResult =  Yii::app()->db->createCommand($query)->queryScalar();

            $resultObject[] = ($queryResult);
        }
        $oneObject = array();
        $oneObject['label'] = 'Customer';
        $oneObject['backgroundColor'] = '#3e95cd';
        $oneObject['data'] = $resultObject;
         $final_result = array();
        array_push($final_result,$oneObject);

        foreach($productList as $key=>$value){
            $getColorName =dashbord_data::getColorName($key);
            $product_id = $value['product_id'];
            $product_name = $value['name'];

            $oneObject = array();
            $oneObject['label'] = $product_name ;
            $oneObject['backgroundColor'] = $getColorName;

            $resultObject = array();
            for($x= 5; $x>=0;$x--){
                $getNO = -($x);
                $month =  strtoupper(date('m', strtotime($post. $getNO." Month")));
                $query = "select sum(dd.quantity) from delivery as d
                   left join delivery_detail as dd ON d.delivery_id =dd.delivery_id
                   where d.company_branch_id =$company_id and month(d.date) = $month and  dd.product_id =$product_id";
                $queryResult =  Yii::app()->db->createCommand($query)->queryScalar();
                $resultObject[] = $queryResult;
            }
            $oneObject['data'] = $resultObject;

            array_push($final_result,$oneObject);

        }

        return  json_encode($final_result);

    }
    public static function getColorName($key){
        $colorObject = array();
        $colorObject[] = '#DEB887';
        $colorObject[] = '#8e5ea2';
        $colorObject[] = '#5F9EA0';
        $colorObject[] = '#D2691E';
         if(isset($colorObject[$key])){
             return $colorObject[$key];
         }else{
             return '#D2691E';
         }
    }
    public static function getProductData($data){

        $post = date("M-Y");
        $company_id = Yii::app()->user->getState('company_branch_id');
        $resultObject = array();
        for($x= 3; $x>=0;$x--){
            $getNO = -($x);
            $month =  strtoupper(date('m', strtotime($post. $getNO." Month")));


            $query = "select count(*) as total_customer from delivery as d 
               where month(d.date) = '$month'  and d.company_branch_id = '$company_id' ";

            $queryResult =  Yii::app()->db->createCommand($query)->queryScalar();

            $resultObject[] = '589';
        }
        $finalData = array();
        $finalData['label'] = $data['name'];
        $finalData['backgroundColor'] = $data['color'];
        $finalData['data'] = $resultObject;

        return  json_encode($finalData);

    }
}