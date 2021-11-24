<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/6/2017
 * Time: 2:16 PM
 */
class productData{

    public static function getproductList_for_mange_product($page){
        $company_id = Yii::app()->user->getState('company_branch_id');

        $offset = 0 ;
        if($page){
            $offset = $page * 10;
        }
        $query="SELECT * from product as p 
                LEFT JOIN product_category AS pc 
                ON pc.product_category_id =p.product_category_id
                where p.company_branch_id =$company_id and bottle = 0  ";


         //LIMIT 10 OFFSET $offset

        $productList =  Yii::app()->db->createCommand($query)->queryAll();
        return json_encode($productList);
    }
    public static function getproductList($page=0){
        $company_id = Yii::app()->user->getState('company_branch_id');


        $query="SELECT * from product as p 
                LEFT JOIN product_category AS pc 
                ON pc.product_category_id =p.product_category_id
                where p.company_branch_id =$company_id and bottle = 0
               and p.is_active='1'  ";


         //LIMIT 10 OFFSET $offset

        $productList =  Yii::app()->db->createCommand($query)->queryAll();
        return json_encode($productList);
    }
    public static function product_list(){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_id 
                and bottle = 0 and p.is_active='1' order by p.name";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();
        return $productList;
    }

    public static function getproductList_arrayForm($page=false){
        $company_id = Yii::app()->user->getState('company_branch_id');

        $offset = 0 ;
        if($page){
            $offset = $page * 10;
        }
        $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_id and bottle = 0  and p.is_active='1'
                 LIMIT 10 OFFSET $offset ";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();
        return ($productList);
    }

    public static function getproductListForlable($page){
        $company_id = Yii::app()->user->getState('company_branch_id');

        $offset = 0 ;
        if($page){
            $offset = $page * 10;
        }
        $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_id  and p.is_active='1'
                 LIMIT 10 OFFSET $offset ";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();

         $finalResult = array();

        foreach($productList as $value){
            array_push($finalResult,"Quantity","Amount");
        }
        return json_encode($finalResult);
    }

    public static function getproductCount(){

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT p.*  from   product as p  
                where p.company_branch_id =$company_id  and p.is_active='1' ";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();

          return count($productList);
    }

    public static function saveNewProductFunction($data){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $companBranchID =    Yii::app()->user->getState('company_branch_id');
        $loginID =    Yii::app()->user->getState('user_id');

        $product = new Product();
        $product->company_branch_id = $company_id;
        $product->name  =str_replace("'"," ",$data['name']);
        $product->unit  = $data['unit'];
        $product->unit  = $data['unit'];
        $product->price  = $data['price'];
        $product->order_type = $data['order_type'];
        $product->is_active = $data['is_active'];
        $product->is_deleted = $data['is_deleted'];

        $product->product_category_id = $data['product_category_id'];

        $product->created_by = $loginID;
        $product->updated_by = $loginID;
        if($product->save()){
            $productID = $product->product_id;
            $query="SELECT p.* , cb.name as companyBranchN from   product as p
                   LEFT JOIN company_branch as cb ON cb.company_branch_id = p.company_branch_id
                     where p.product_id = $productID ";
            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
            zoneData::$response['success']=true;
            zoneData::$response['message']='ok';
            zoneData::$response['product']=$queryResult;
        }else{
            zoneData::$response['success'] = false ;
            zoneData::$response['message'] = $product->getErrors() ;
        }
        return json_encode(zoneData::$response);
    }

    public static function editProductFunction($data){
        $companBranchID =    Yii::app()->user->getState('company_branch_id');
        $loginID =    Yii::app()->user->getState('user_id');
       $product =Product::model()->findByPk(intval($data['product_id']));
        $product->company_branch_id = $companBranchID;
        $product->name  = str_replace("'"," ",$data['name']);
        $product->unit  = $data['unit'];
        $product->price  = $data['price'];
        $product->order_type = $data['order_type'];
        $product->is_active = $data['is_active'];
        $product->is_deleted = $data['is_deleted'];
        $product->description = $data['description'];
        $product->product_category_id = $data['product_category_id'];
        $product->created_by = $loginID;

        if($product->save()){
            $productID = $product->product_id;

            zoneData::$response['success']=true;
            zoneData::$response['message']='ok';


        }else{
            zoneData::$response['success'] = false ;
            zoneData::$response['message'] = $product->getErrors() ;

        }
        return json_encode(zoneData::$response);

    }

    public static function deleteFunction($data){


        $product =Product::model()->findByPk(intval($data['product_id']));
        $product->is_deleted = 1;
        if($product->save()){
            $productID = $product->product_id;
            zoneData::$response['success']=true;
            zoneData::$response['message']='ok';
        }else{
            zoneData::$response['success'] = false ;
            zoneData::$response['message'] = $product->getErrors() ;
        }
        return json_encode(zoneData::$response);

    }

    public static  function searchProductFunction($data){
        $company_id = Yii::app()->user->getState('company_branch_id');
         $query="SELECT p.*  from   product as p
                  where p.company_branch_id = '$company_id' and p.name LIKE '$data%'";

        $productList =  Yii::app()->db->createCommand($query)->queryAll();

        return json_encode($productList);
    }

    public static  function checkAlredyExistProductFunction($data){

        $company_id = Yii::app()->user->getState('company_branch_id');



        $billTResult = Product::model()->findByattributes(array('name'=>$data ,'company_branch_id'=>$company_id));
        $result = 'no' ;
        if($billTResult){
            $result = 'yes';
        }
        return $result ;
    }
}