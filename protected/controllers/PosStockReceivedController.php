<?php

class PosStockReceivedController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
    public function filters()
    {
        $user_id = Yii::app()->user->getState('user_id');
        if(!$user_id){
            $this->redirect(Yii::app()->user->returnUrl);
        }

        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }


	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */


	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionPosStockTransfered()
	{

        $company_id = Yii::app()->user->getState('company_branch_id');
        $get_data = $_GET;

        $type =  $get_data['type'];
        if($get_data['type']==1){

            $report_name = 'Stock Issu';

        }elseif($get_data['type']==2){
            $report_name  ='Stock Return';
        }elseif($get_data['type']==3){
            $report_name  ='Stock Demage';
        }else{
            $this->render('not_allown_this_page',array());
        }
        $data = [];
        $data['type'] =$type;

        $this->render('PosStockTransfered',array(
            'data'=>json_encode($data),
            'productList'=>json_encode(dailyStockData::ProductList()),
            'company_id'=>$company_id,
            'report_name'=>$report_name,
            'farmlist'=>colorTagData::getShopList(),
        ));
	}

	public function actionpos_stock_return()
	{
        $company_id = Yii::app()->user->getState('company_branch_id');

        $this->render('PosStockTransfered',array(
            'productList'=>json_encode(dailyStockData::ProductList()),
            'company_id'=>$company_id,
            'farmlist'=>colorTagData::getShopList(),
        ));
	}

    public function actionPosDateRangeStockRecived()
    {
        $company_id = Yii::app()->user->getState('company_branch_id');

        $this->render('PosDateRangeStockRecived',array(
            'productList'=>json_encode(dailyStockData::ProductList()),
            'company_id'=>$company_id,
            'farmlist'=>colorTagData::getShopList(),
        ));
    }

	public function actionPosSaveNewStock_add(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $company_id = Yii::app()->user->getState('company_branch_id');
        $user_id = Yii::app()->user->getState('user_id');

         $pos_shop_id = $data['pos_shop_id'];
         $date = $data['date'];
         $type = $data['type'];
         if($type==1){
             $value_type = 'quantity';
         }
        if($type==2){
            $value_type = 'stock_return';
        }
        if($type==3){
            $value_type = 'stock_damage';
        }
         $productList = $data['productList'];
         foreach($productList as $value){
              $product_id = $value['product_id'];
              $quantity = $value['quantity'];

              $posStockReceived = New PosStockReceived();
             $posStockReceived->product_id =$product_id;
             $posStockReceived->pos_shop_id =$pos_shop_id;
             $posStockReceived->$value_type =$quantity;
             $posStockReceived->transfer_user_id =$user_id;
             $posStockReceived->date =$date;
             if($posStockReceived->save()){
               $reponce = array();
                $reponce['success'] = true;
             }else{
                 $reponce = array();
                 $reponce['success'] = false;
                 $reponce['message'] = $posStockReceived->getErrors();

             }
         }

         echo json_encode($reponce);
    }
    public function actiondateRangePosStock(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $startDate =$data['startDate'];
        $endDate =$data['endDate'];
        $pos_shop_id =$data['pos_shop_id'];
        $product_id =$data['product_id'];

        $query =" SELECT p.date ,SUM(p.quantity) AS quantity ,p.product_id ,pro.name ,pro.unit  from pos_stock_received AS p
            LEFT JOIN product AS pro ON p.product_id =pro.product_id
            WHERE p.DATE BETWEEN '$startDate' AND '$endDate'
            and p.product_id ='$product_id' AND p.pos_shop_id ='$pos_shop_id'
           GROUP BY p.product_id ,p.date ";

        $productList =  Yii::app()->db->createCommand($query)->queryAll();
        echo json_encode($productList);


    }

    public function actionshop_stock_ledger(){
        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $company_id = Yii::app()->user->getState('company_branch_id');
        $shop_list = shop_list_data::get_shop_list();
        $product_list = productData::product_list();

        $data = [] ;
        $data['today_date'] = $today_date ;
        $data['five_day_ago'] = $fiveDayAgo ;
        $data['shop_list'] = $shop_list ;
        $data['company_id'] = $company_id ;
        $data['product_list'] = $product_list ;


        $this->render('shop_stock_ledger',array(
            'data'=>json_encode($data),
        ));
    }

    public function actionbase_get_pos_shop_ledger(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $pos_shop_id = $data['pos_shop_id'];
        $opening_sale = pos_stock_ledger::pos_opening_sale($data,1);
        $opening_issue =  pos_stock_ledger::pos_opening_issue_return_demage($data,1);

        $quantity_issue = $opening_issue['quantity'];
        $stock_return = $opening_issue['stock_return'];
        $stock_damage = $opening_issue['stock_damage'];

        $final_result = [];

        $one_object= [];
        $one_object['opening_sale'] = $opening_sale;
        $one_object['quantity_issue'] = $quantity_issue;
        $one_object['stock_return'] = $stock_return;
        $one_object['stock_damage'] = $stock_damage;
        $one_object['stock_sale'] = $opening_sale;



        $balance = $quantity_issue-$opening_sale-$stock_return-$stock_damage;

        $one_object['balance'] =$balance;
        $one_object['date'] ='Opening';

        $final_result[] =$one_object;

        $net_balance = $balance;


       /* $shop_list = shop_list_data::get_shop_list($pos_shop_id);

        $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d

            where d.client_id in ()  AND d.date < '$startDate' ";

        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();*/

        $x= strtotime($startDate);
        $y= strtotime($endDate);

        while($x < ($y+8640)) {
            $oneDayData = array();
             $selectDate = date("Y-m-d", $x);

             $data['startDate'] = $selectDate;
            $x += 86400;

            $opening_sale = pos_stock_ledger::pos_opening_sale($data ,2);
            $opening_issue =  pos_stock_ledger::pos_opening_issue_return_demage($data,2);


            $quantity_issue = $opening_issue['quantity'];
            $stock_return = $opening_issue['stock_return'];
            $stock_damage = $opening_issue['stock_damage'];


            $one_object= [];
            $one_object['opening_sale'] = $opening_sale;
            $one_object['quantity_issue'] = $quantity_issue;
            $one_object['stock_return'] = $stock_return;
            $one_object['stock_damage'] = $stock_damage;
            $one_object['stock_sale'] = $opening_sale;

            $balance = $quantity_issue-$opening_sale-$stock_return-$stock_damage;

            $net_balance =$net_balance -$balance;
            $one_object['balance'] =$net_balance;
            $one_object['date'] =$selectDate;

            $final_result[] =$one_object;

        }


        $data = [];
        $data['list']= $final_result;

        echo json_encode($data);
    }
}
