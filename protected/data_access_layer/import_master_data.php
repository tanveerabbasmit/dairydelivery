<?php


class import_master_data
{
   public static function master_block_name_save($block_name,$copany_id){

       $query = "SELECT  * FROM block AS c
                    WHERE c.block_name like '%$block_name%' 
                    and c.company_id=$copany_id";

       $object =  Yii::app()->db->createCommand($query)->queryRow();

       if($object){

           return   $object['block_id'];

       }

       $object = New Block();
       $object->block_name = $block_name;
       $object->company_id = $copany_id;
       if($object->save()){
           return $object->block_id;
       }else{
          return 0;
       }
   }
   public static function save_zone_name($zone_name,$company_id){


       $query = "SELECT  * FROM zone AS c
                    WHERE c.name like '%$zone_name%' 
                    and c.company_branch_id=$company_id";

       $client_object =  Yii::app()->db->createCommand($query)->queryRow();

       if($client_object){
          return   $client_object['zone_id'];

       }


       $object = New Zone();
       $object->name = $zone_name;
       $object->company_branch_id = $company_id;
       $object->commission = 0;
       $object->is_active = 1;
       $object->is_deleted = 0;
       if($object->save()){
          return $object->zone_id;
       }else{
          return 0;
       }
   }
   public static function save_customer_category($category_name,$company_id){

       $query = "SELECT  * FROM customer_category AS c
                    WHERE c.category_name like '%$category_name%' 
                    and c.company_branch_id=$company_id";

       $object =  Yii::app()->db->createCommand($query)->queryRow();

       if($object){

           return   $object['customer_category_id'];

       }

       $object = New CustomerCategory();
       $object->category_name=$category_name;
       $object->company_branch_id=$company_id;
       if($object->save()){
          return  $object->customer_category_id;
       }else{
           return 0;
          /* echo $category_name;
           echo "<pre>";
           print_r($object->getErrors());
           die();*/
       }
   }
}