<?php

class Product_purchase_summaryController extends Controller
{


    public function actionproduct_purchase_summary_report_view_export(){
        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: attachment; filename=product_purchase_summary_report_view.csv");
        header("Pragma: no-cache");
        header("Expires: 0");


        $data = $_GET;

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $farm_id = $data['farm_id'];
        $company_id = Yii::app()->user->getState('company_branch_id');

        $query = "SELECT 
        ds.date,
        ds.purchase_rate,
        ifnull((ds.quantity),0) AS quantity ,
        ifnull((ds.wastage),0) AS wastage,
        ifnull((ds.return_quantity),0) AS return_quantity,
        p.name AS product_name,
        
        f.farm_name 
        FROM daily_stock AS ds 
        LEFT JOIN  product AS p ON p.product_id = ds.product_id
        LEFT JOIN farm AS f ON f.farm_id  = ds.farm_id
        WHERE 
         ds.date BETWEEN 
        '$start_date' 
        AND '$end_date' 
        and ds.company_branch_id='$company_id' ";

        if($farm_id>0){
            $query .="  and f.farm_id ='$farm_id'  ";
        }
        // $query .="  GROUP BY ds.date,ds.product_id   ";





        $result = Yii::app()->db->createCommand($query)->queryAll();

        $fina_net = [];

        $total_result=[];
        $total_net_quantity = 0;
        $total_net_amount = 0;
        foreach ($result as $value) {

            $purchase_rate = $value['purchase_rate'];
            $total_quantity =$value['quantity'] -$value['wastage'] -$value['return_quantity'];
            $total_price = $total_quantity * $purchase_rate;
            $value['net_quantity'] = $total_quantity;
            $value['net_amount'] = $total_price;
            $fina_net[] =$value;

            $total_net_quantity = $total_net_quantity  +$total_quantity;

            $total_net_amount = $total_net_amount + $total_price;
        }
        $total_result['total_net_quantity'] =$total_net_quantity;
        $total_result['total_net_amount'] =$total_net_amount;

        $fina_result= [];
        $fina_result['list']= $fina_net;
        $fina_result['total_result']= $total_result;



        echo  "Sr#,Date, Product,Farm,Recived Quantity,Rate,Purchase Amount";
        echo "\r\n";

        foreach($fina_net as $key=>$value){
            echo ($key+1).",";
            echo $value['date'].",";
            echo $value['product_name'].",";
            echo $value['farm_name'].",";
            echo $value['net_quantity'].",";
            echo $value['purchase_rate'].",";
            echo $value['purchase_rate'].",";
            echo $value['net_amount'].",";
            echo "\r\n";
        }
    }
	public function actionProduct_purchase_summary_report_view(){
	    $data =[];
        $data['start_date'] = date('Y-m-').'01';

        $data['end_date'] = date('Y-m-d');
        $data['fram_list'] = qualityListData::getFarmList_for_drop_down();
		$this->render('product_purchase_summary_report_view',[
		    'data'=>json_encode($data)
        ]);
	}
	public function actionProduct_purchase_summary_report_view_all(){
	    $data =[];
        $data['start_date'] = date('Y-m-').'01';

        $data['end_date'] = date('Y-m-d');
        $data['fram_list'] = qualityListData::getFarmList_for_drop_down();

		$this->render('product_purchase_summary_report_view_all',[
		    'data'=>json_encode($data)
        ]);
	}

	public function actionbase_url_product_purchase_function(){


        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
       $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $farm_id = $data['farm_id'];
        $company_id = Yii::app()->user->getState('company_branch_id');

       $query = "SELECT 
        ds.date,
        ds.purchase_rate,
        ifnull((ds.quantity),0) AS quantity ,
        ifnull((ds.wastage),0) AS wastage,
        ifnull((ds.return_quantity),0) AS return_quantity,
        p.name AS product_name,
        
        f.farm_name 
        FROM daily_stock AS ds 
        LEFT JOIN  product AS p ON p.product_id = ds.product_id
        LEFT JOIN farm AS f ON f.farm_id  = ds.farm_id
        WHERE 
         ds.date BETWEEN 
        '$start_date' 
        AND '$end_date' 
        and ds.company_branch_id='$company_id' ";

         if($farm_id>0){
             $query .="  and f.farm_id ='$farm_id'  ";
         }
       // $query .="  GROUP BY ds.date,ds.product_id   ";





        $result = Yii::app()->db->createCommand($query)->queryAll();

        $fina_net = [];

        $total_result=[];
        $total_net_quantity = 0;
        $total_net_amount = 0;
        foreach ($result as $value) {

           $purchase_rate = $value['purchase_rate'];
           $total_quantity =$value['quantity'] -$value['wastage'] -$value['return_quantity'];
           $total_price = $total_quantity * $purchase_rate;
            $value['net_quantity'] = $total_quantity;
            $value['net_amount'] = $total_price;
            $fina_net[] =$value;

            $total_net_quantity = $total_net_quantity  +$total_quantity;

            $total_net_amount = $total_net_amount + $total_price;
        }
        $total_result['total_net_quantity'] =$total_net_quantity;
        $total_result['total_net_amount'] =$total_net_amount;

        $fina_result= [];
        $fina_result['list']= $fina_net;
        $fina_result['total_result']= $total_result;

        echo json_encode($fina_result);
    }
	public function actionbase_url_product_purchase_function_all(){


        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
       $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $farm_id = $data['farm_id'];
        $company_id = Yii::app()->user->getState('company_branch_id');

       $query = "SELECT 
        ds.date,
       sum(ds.purchase_rate * (ds.quantity)) AS total_price,
        ds.purchase_rate,
        sum(ds.quantity) AS quantity ,
        sum(ds.wastage) AS wastage,
        sum(ds.return_quantity) AS return_quantity,
        p.name AS product_name,
        
        f.farm_name 
        FROM daily_stock AS ds 
        LEFT JOIN  product AS p ON p.product_id = ds.product_id
        LEFT JOIN farm AS f ON f.farm_id  = ds.farm_id
        WHERE 
         ds.date BETWEEN 
        '$start_date' 
        AND '$end_date' 
        and ds.company_branch_id='$company_id' ";

         if($farm_id>0){
             $query .="  and f.farm_id ='$farm_id'  ";
         }
        $query .="  GROUP BY ds.product_id   ";







        $result = Yii::app()->db->createCommand($query)->queryAll();

        $fina_net = [];

        $total_result=[];
        $total_net_quantity = 0;
        $total_net_amount = 0;
        foreach ($result as $value) {

           $purchase_rate = $value['purchase_rate'];
           $total_quantity =$value['quantity'] -$value['wastage'] -$value['return_quantity'];
           $total_price = $total_quantity * $purchase_rate;
            $value['net_quantity'] = $total_quantity;
            $value['net_amount'] = round($value['total_price'],0);
            $value['purchase_rate'] = '0';
            if($total_quantity>0){
                $value['purchase_rate'] = round(($value['total_price']/$total_quantity),0);
            }

            $fina_net[] =$value;

            $total_net_quantity = $total_net_quantity  +$total_quantity;

            $total_net_amount = $total_net_amount + $value['total_price'];
        }
        $total_result['total_net_quantity'] =$total_net_quantity;
        $total_result['total_net_amount'] =round($total_net_amount,0);

        $fina_result= [];
        $fina_result['list']= $fina_net;
        $fina_result['total_result']= $total_result;

        echo json_encode($fina_result);
    }


}