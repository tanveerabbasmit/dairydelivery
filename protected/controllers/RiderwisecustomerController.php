<?php

class RiderwisecustomerController extends Controller
{
	public function actionRiderwisecustomer_list()
	{

        $company_id = Yii::app()->user->getState('company_branch_id');
        $this->render('riderwisecustomer_list',array(
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'company_id'=>$company_id,
        ));
		//$this->render('riderwisecustomer_list');
	}

	public function actionbase_url_customer_list(){
        $post = file_get_contents("php://input");


        $query = "Select  c.client_id,c.address , 
                           c.cell_no_1 ,c.fullname ,z.name as zone_name 
                           from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           LEFT JOIN customer_category AS cg ON cg.customer_category_id= c.customer_category_id
                           where rz.rider_id ='$post'  
                           order by c.rout_order ";

        if($post>0){
            $clientResult =  Yii::app()->db->createCommand($query)->queryAll();
        }else{
            $clientResult =[];
        }


        echo json_encode($clientResult);
    }

    public function actionexport_list($id){

	    if($id>0){
            $query = "Select  c.client_id,c.address , 
                           c.cell_no_1 ,c.fullname ,z.name as zone_name 
                           from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           LEFT JOIN customer_category AS cg ON cg.customer_category_id= c.customer_category_id
                           where rz.rider_id ='$id'  
                           order by c.rout_order ";

            $clientResult =  Yii::app()->db->createCommand($query)->queryAll();

        }else{
            $clientResult=[];
        }
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=rout_wise_customer_list.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo "cleint_id,Full Name,Address";
        echo  "\r\n" ;
        foreach ($clientResult as $value){



            echo $value['client_id'].',';
            $fullname =str_replace(',','',$value['fullname']);
            echo str_replace(",","/",$fullname).',';



            echo str_replace(",","/",$value['address']).',';
            echo  "\r\n" ;


        }

    }

    public function actioncustomer_schedule(){
        $get_dat = $_GET;
        $company_id = Yii::app()->user->getState('company_branch_id');

         $weekly_days_list = [
           '1'=>1,
           '2'=>2,
           '3'=>3,
           '4'=>4,
           '5'=>5,
           '6'=>6,
           '7'=>7,
         ];




        $query_client="SELECT 
            c.client_id,
            c.fullname,
            c.address,
            c.cell_no_1
            FROM client AS c 
            LEFT JOIN zone AS z ON z.zone_id =c.zone_id
            WHERE c.company_branch_id ='$company_id'";




        $queryResult =  Yii::app()->db->createCommand($query_client)->queryAll();


        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=customer_list.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "cleint_id,Full Name,Address,Phone No.,order_type,inter,quantity,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday";
        echo  "\r\n" ;
        foreach ($queryResult as $value){
           $client_id =   $value['client_id'];
           $order =  clientData::getOrderAgainstClint($client_id);
           $order_product = [];
           foreach ($order as $order_value){
               $product_id = $order_value['product_id'];
               $order_product[$product_id] =$order_value['order_type'];
           }
          $product_list = productData::product_list();
          foreach ($product_list as $product_value){

              $product_id = $product_value['product_id'];

              echo $value['client_id'].',';
              $fullname =str_replace(',','',$value['fullname']);
              $fullname =str_replace('\r\n','',$fullname);
              echo str_replace(",","/",$fullname).',';
              // echo $value['userName'].',';
              $address = str_replace(",","/",$value['address']);
              $address = str_replace("\r\n","/",$address);
              echo $address.',';
              echo $value['cell_no_1'].',';

              $order_type ='';
              if(isset($order_product[$product_id])){

                  $flag =  $order_product[$product_id];
                  if($flag==1){

                      $order_type ='Weekly';

                  }else{
                      $order_type ='interval';
                  }


              }else{
                  $order_type ='non';
              }




              echo $order_type.',';


              $weekly_data = [];
              $weekly_data['clientID'] = $client_id;
              $weekly_data['productID'] =$product_id;

              $interval_object =  clientData::selectFrequencyForOrderFunction_interval_for_export($weekly_data);

              echo   $interval_object['interval_days'].',';
              echo   $interval_object['product_quantity'].',';

              $weekly_list =  clientData::selectFrequencyForOrderFunction_for_report($weekly_data);

              $weekly_list_frequency_id_wise = [];

              foreach ($weekly_list as $value_fre ){
                  $frequency_id = $value_fre['frequency_id'];
                  $weekly_list_frequency_id_wise[$frequency_id] =$value_fre['quantity'];
              }



              foreach ($weekly_days_list as $value){
                 if(isset($weekly_list_frequency_id_wise[$value])){
                     echo $weekly_list_frequency_id_wise[$value].',';
                 }else{
                     echo ',';
                 }
              }



              echo  "\r\n" ;
          }








        }
    }



}