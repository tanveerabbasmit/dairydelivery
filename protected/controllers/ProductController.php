<?php

class ProductController extends Controller
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



	public function actionmanageProduct()
	{
	    $page = '';
	    $product_category =zoneData::get_product_category_list();




		$this->render('manageProduct',array(

			'product_category'=>zoneData::get_product_category_list(),

			'productList'=>productData::getproductList_for_mange_product($page =false),
			'productCount'=>productData::getproductCount(),

		));
		
	}

	public function actionManageProduct_update_iamge(){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $product_id =  $_POST['product_id'];

        $pictureName = basename($_FILES["image"]['name']);
        $extention_object = (explode('.', $pictureName));

        $extention = end($extention_object);

        $target_dir = Yii::app()->basePath.'/../themes/milk/images/product/';
        $random_name = rand(0,99999).rand(0,99999).rand(0,99999);

        $fullName = $random_name.'.'.$extention;


        $object =Product::model()->findByPk($product_id);

        $object->image = $fullName;

        $object->save();

        $packingSlipName_targetFile = $target_dir.$fullName ;

        move_uploaded_file($_FILES["image"]["tmp_name"], $packingSlipName_targetFile);

        $this->redirect(array('/product/manageProduct/'));
    }


    public function actionProductSummary()
    {
        date_default_timezone_set("Asia/Karachi");
        $today_date = date("Y-m-d");

        $data= array();
        $page = '';
        $this->render('ProductSummary',array(
            'productList'=>json_encode($data),
            'productCount'=>productData::getproductCount(),
            'today_date'=>json_encode($today_date),
        ));
    }

    public function actionpayment_transaction_report()
    {
        date_default_timezone_set("Asia/Karachi");
        $today_date = date("Y-m-d");

        $data= array();
        $page = '';
        $this->render('payment_transaction_report',array(
            'productList'=>json_encode($data),
            'productCount'=>productData::getproductCount(),
            'today_date'=>json_encode($today_date),
        ));
    }
    public function actioncustomerWithRate()
    {

         $get_data = $_GET;



        date_default_timezone_set("Asia/Karachi");
        $today_date = date("Y-m-d");

        $data= array();
        $page = '';
        $this->render('customerWithRate',array(
            'productList'=>json_encode($data),
            'productCount'=>productData::getproductCount(),
            'today_date'=>json_encode($today_date),
            'get_data'=>json_encode($get_data),


        ));
    }

    public function actionnextPageForpagination(){
        $post = file_get_contents("php://input");
        echo productData::getproductList($post);
    }

    public function actionsaveNewProduct(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo productData::saveNewProductFunction($data);

    }

    public function actioneditProduct(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo productData::editProductFunction($data);

    }

    public function actiondelete(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);



        echo productData::deleteFunction($data);
    }

    public function actionsearchProduct(){

        $post = file_get_contents("php://input");

        echo productData::searchProductFunction($post);
    }

    public function actioncheckAlredyExistProduct(){

        $post = file_get_contents("php://input");

        echo productData::checkAlredyExistProductFunction($post);
    }
    public function actionpayment_transaction_report_data(){
        $post = file_get_contents("php://input");

        $data = CJSON::decode($post, TRUE);

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query = " SELECT 
                pm.payment_master_id ,
                c.fullname ,
                c.address ,
                date(pm.created_date_time) as created_date_time,
                pm.date , 
                pm.amount_paid ,
                pm.	payment_mode ,
                pm.bill_month_date,
                pm.reference_number,
                pm.payment_mode,
                pm.time,
                pm.edit_by_user_id,
                pm.user_id,
                pm.rider_id
                from payment_master as pm
                left join client as c ON c.client_id = pm.client_id  
                WHERE date(pm.created_date_time) between '$start_date' and '$end_date'  
                 and pm.company_branch_id ='$company_id' 
                order by pm.date DESC";




        $result = Yii::app()->db->createCommand($query)->queryAll();

        $final_result = [];
        foreach ($result as $value){

            $payment_mode=$value['payment_mode'];

            /*  WHEN pm.payment_mode = 1 then 'Online'
                    WHEN pm.payment_mode = 2 then 'Cheque'
                    WHEN pm.payment_mode = 3 then 'Cash'
                    WHEN pm.payment_mode = 5 then 'Bank Transaction'
                    WHEN pm.payment_mode = 6 then 'Card Transaction'
                    ELSE 'Other'*/
            if($payment_mode ==1){
                $value["payment_mode_text"] ='Online';
            }elseif($payment_mode ==2){
                $value["payment_mode_text"] ='Cheque';
            }elseif($payment_mode ==3){
                $value["payment_mode_text"] ='Cash';
            }elseif($payment_mode ==5){
                $value["payment_mode_text"] ='Bank Transaction';
            }elseif($payment_mode ==6){
                $value["payment_mode_text"] ='Card Transaction';
            }else{
                $value["payment_mode_text"] ='Other';
            }

            $bill_month_date_get = $value['bill_month_date'];

            $month = date("m",strtotime($bill_month_date_get));
            $year = date("Y",strtotime($bill_month_date_get));

            $value['get_month'] =$month;
            $value['get_year'] =$year;
            if($value['edit_by_user_id']>0){
                $value['color'] ='#FF7F50';
            }

            $final_result[] = $value;
        }

        echo  json_encode($final_result);

    }
    public function actiongetdateWiseDataDeliveryData(){

        $post = file_get_contents("php://input");

        $data = CJSON::decode($post, TRUE);

      //  echo  json_encode(productSummaryData::getproductSummary($data));
        echo  json_encode(productSummaryData::customerWithRate_data2($data));
    }
    public function actioncustomerWithRate_list(){

        $post = file_get_contents("php://input");

        $data = CJSON::decode($post, TRUE);

        echo  json_encode(productSummaryData::customerWithRate_data($data));
    }


}
