<?php

class MobiledesignController extends Controller
{
    public $layout=false;
	public function actionStocksummary_view()
	{

        if(Yii::app()->user->getId()==null) {
            $this->redirect(array('/site/mobile_login'));
        }

        $default_product_id = check_default_product::get_product_id();



	    $product_list = productData::product_list();

	    $data = [];
	    $data['start_date']=date("Y-m-").'01';
	    $data['end_date']=date("Y-m-d");
	    $data['product_list']=$product_list;
	    $data['default_product_id']=$default_product_id;
		$this->render('stocksummary_view',[
		    'data'=>$data
        ]);
	}

	public function actionstock_summary_list_stock_summary(){


        if(Yii::app()->user->getId()==null) {
            $this->redirect(array('/site/mobile_login'));
        }

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $product_id = $data['product_id'];
        $x= strtotime($start_date);
        $y= strtotime($end_date);
        $balance = mobiledesign_data::get_opening_stock($start_date,$product_id);
        $main_list = [];
        $one_object = [];
        $one_object['date'] ='Opening Stock';
        $one_object['stock_in'] ='';
        $one_object['stock_out'] ='';
        $one_object['stock_hand'] =round($balance,0);

        $main_list[] =$one_object;

        while($x < ($y+8640)) {

            $one_object = [];
            $selectDate = date("Y-m-d", $x);
            $total_recived = mobiledesign_data::get_total_recived($selectDate,$product_id);
            $one_object['date'] = $selectDate;
            $one_object['stock_in'] =$total_recived;

            $stock_out = mobiledesign_data::one_date_total_sale($selectDate,$product_id);

            $one_object['stock_out'] =$stock_out;

            $balance = $balance + $total_recived-$stock_out;
            $one_object['stock_hand'] =round($balance,0);
            $x += 86400;
            $main_list[] = $one_object;

        }

        $result = [];
        $result['main_list'] = $main_list;

        echo json_encode($result);
    }
    public function actioncash_in_detail(){

        if(Yii::app()->user->getId()==null) {
            $this->redirect(array('/site/mobile_login'));
        }

        $data = [];

        $get_data = $_GET;



        $data['start_date']=date("Y-m-").'01';
        $data['end_date']=date("Y-m-d");
        if(isset($get_data['date'])){
            $data['start_date']=$get_data['date'];
            $data['end_date']=$get_data['date'];
        }
        $data['product_list']=[];
        $this->render('cash_in_detail',[
            'data'=>$data
        ]);
    }
    public function actioncash_in_main(){
        $data = [];
        $data['start_date']=date("Y-m-").'01';
        $data['end_date']=date("Y-m-d");
        $data['product_list']=[];
        $this->render('cash_in_main',[
            'data'=>$data
        ]);
    }

    public function actionstock_summary_list_cash_in_detail_data(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);

       $main_list = mobiledesign_data::payment_list($data);
       $total_amount =0;
       foreach ($main_list as $value){
          $amount_paid =  $value['amount_paid'];
           $total_amount = $total_amount +  $amount_paid;
       }

      $result = [];
      $result['main_list'] = $main_list;
      $result['total_amount'] = $total_amount;


      echo json_encode($result);

    }
    public function actionstock_summary_list_cash_in_main_data(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        $x= strtotime($start_date);
        $y= strtotime($end_date);
        $main_list = [];
        while($x < ($y+8640)) {

              $selected_date = date("Y-m-d", $x);

              $amount = mobiledesign_data::payment_list_main($selected_date);

              $one_object = [];
              $one_object['selected_date'] =$selected_date;
              $one_object['amount'] =round($amount,0);

              if($amount>0){
                  $main_list[] = $one_object;
              }

              $x += 86400;

        }
        $result = [];
        $result['main_list'] = $main_list;
        $result['total_amount'] = [];


        echo json_encode($result);
    }

    public function actioncash_flow_view(){

        if(Yii::app()->user->getId()==null) {
            $this->redirect(array('/site/mobile_login'));
        }

        $data = [];
        $data['start_date']=date("Y-m-").'01';
        $data['end_date']=date("Y-m-d");
        $data['product_list']=[];
        $this->render('cash_flow_view',[
            'data'=>$data
        ]);
    }
    public function actionstock_summary_list_cash_flow_view_list(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        $x= strtotime($start_date);
        $y= strtotime($end_date);
        $balance = 0;


        $total_opening_payment = mobiledesign_data::payment_list_total_count_opening($data);



        $total_expence = mobiledesign_data::total_payment_out_openig($start_date);



        $one_object = [];
        $one_object['date'] ='Opening Balance';

       // $one_object['cash_out'] ='';
       // $one_object['cash_in'] ='';

        $one_object['cash_in_hand'] =$total_opening_payment-$total_expence;

        $balance = $total_opening_payment - $total_expence;

        $main_list[] = $one_object;


        while($x < ($y+8640)) {


            $selectDate = date("Y-m-d", $x);
            $one_day_payment = mobiledesign_data::get_one_payment($selectDate);
            $total_expence_one_day = mobiledesign_data::total_payment_out_one_day($selectDate);

            $balance = $balance + $one_day_payment-$total_expence_one_day;

            $one_object = [];
            $one_object['date'] =$selectDate;
            $one_object['cash_in'] =($one_day_payment)?$one_day_payment:'0';
            $one_object['cash_out'] =($total_expence_one_day)?$total_expence_one_day:'0';


            $one_object['cash_in_hand'] = $balance ;
            if(intval($one_day_payment)>0 || intval($total_expence_one_day)>0){
                $main_list[] = $one_object;
            }

            $x += 86400;
        }

        $result = [];
        $result['main_list'] =$main_list;
        $result['total_object'] =[];

        echo  json_encode($result);
    }

    public function actionstock_out_detail()
    {
        $product_list = productData::product_list();
        $get_data = $_GET;

        $data = [];


        $data['start_date']=date("Y-m-").'01';
        $data['end_date']=date("Y-m-d");

        $data['product_list']=$product_list;

        $data['product_id']=$product_list[0]['product_id'];



        if(isset($get_data['date'])){
            $data['start_date']=$get_data['date'];
            $data['end_date']=$get_data['date'];
            $data['product_id'] = $get_data['product_id'];
        }


        $this->render('stock_out_detail',[
            'data'=>$data
        ]);
    }
    public function actionCash_out_detail()
    {
        $product_list = productData::product_list();
        $data = [];

        $get_data = $_GET;

        $data['start_date']=date("Y-m-").'01';
        $data['end_date']=date("Y-m-d");

        if(isset($get_data['date'])){
            $data['start_date']=$get_data['date'];
            $data['end_date']=$get_data['date'];
        }

        $data['product_list']=$product_list;
        $this->render('cash_out_detail',[
            'data'=>$data
        ]);
    }



    public function actionstock_in_detail()
    {
        $product_list = productData::product_list();

        $data = [];
        $data['start_date']=date("Y-m-").'01';
        $data['end_date']=date("Y-m-d");

        $get_data = $_GET;

        $data['product_id'] = $product_list[0]['product_id'];

        if(isset($get_data['date'])){
            $data['start_date']=$get_data['date'];
            $data['end_date']=$get_data['date'];
            $data['product_id'] =$get_data['product_id'];
        }

        $data['product_list']=$product_list;
        $this->render('stock_in_detail',[
            'data'=>$data
        ]);
    }

    public function actionstock_summary_list_stock_in_detail_list(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $product_id = $data['product_id'];

        $x= strtotime($start_date);
        $y= strtotime($end_date);

        $category_list_object =qualityListData::getFarmList_for_drop_down() ;

        // $size_of_category = sizeof($category_list_object);


        $main_list=[];

        while($x < ($y+8640)) {
            $selectDate = date("Y-m-d", $x);
            $one_object =[];


            $get_data=  mobiledesign_data::farm_wise_purchase($product_id,$selectDate,$category_list_object);



            $category_list =  $get_data['list'];

            $show_object = true;
            foreach ($category_list as $value){



                $one_object['show_row'] =$show_object;
                $one_object['date'] =$selectDate;

                $one_object['size_of_category'] =sizeof($category_list);
                $one_object['total_sale'] =$get_data['total_sale'];
                $one_object['category_name'] =$value['farm_name'];
                $one_object['sale'] =$value['sale_amount'];
                $main_list[] = $one_object;

                $show_object = false;
            }


            $x += 86400;
        }

        $result = [];
        $result['main_list'] =$main_list;
        $result['total_object'] =[];

        echo  json_encode($result);
    }

    public function actionstock_summary_list_stock_out_detail_list(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $product_id = $data['product_id'];

        $x= strtotime($start_date);
        $y= strtotime($end_date);

        $category_list_object = categoryData::getCategoryList_array();

        // $size_of_category = sizeof($category_list_object);





        $main_list=[];

        while($x < ($y+8640)) {
            $selectDate = date("Y-m-d", $x);
            $one_object =[];


            $get_data=  mobiledesign_data::category_wise_sale($product_id,$selectDate,$category_list_object);

            $category_list =  $get_data['list'];

            $show_object = true;
            foreach ($category_list as $value){


                $one_object['show_row'] =$show_object;
                $one_object['date'] =$selectDate;

                $one_object['size_of_category'] =sizeof($category_list);
                $one_object['total_sale'] =$get_data['total_sale'];
                $one_object['category_name'] =$value['category_name'];
                $one_object['sale'] =$value['sale_amount'];
                $main_list[] = $one_object;

                $show_object = false;
            }


            $x += 86400;
        }

        $result = [];
        $result['main_list'] =$main_list;
        $result['total_object'] =[];

        echo  json_encode($result);
    }

    public static function actionstock_summary_list_cash_out_detail_list(){


        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        $list_expence= mobiledesign_data::total_expence_between_date_range($data);
        $farm_payment= mobiledesign_data::farm_payment_list_between_date_range($data);
        $vendor_payment= mobiledesign_data::vendor_payment_list_between_date_range($data);

        $grand_total = mobiledesign_data::find_total_count(
            $list_expence,
            $farm_payment,
            $vendor_payment
        );

        $result = [] ;
        $result['list_expence'] = $list_expence ;

        $result['farm_payment'] = $farm_payment ;

        $result['vendor_payment'] = $vendor_payment ;

        $result['total_amount'] = $grand_total ;

        echo  json_encode($result);
    }
}