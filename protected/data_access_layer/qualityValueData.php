<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class qualityValueData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

    public static function get_farm_quality($data){

         $farm_id = $data['farm_id'];
         $date = $data['date'];
        $company_id = Yii::app()->user->getState('company_branch_id');
         $query="select '$date' as date ,
            '$farm_id' as farm_id ,
            ql.quality_list_id ,
            ql.quality_name , ifnull(fq.quantity_value ,'') as quantity_value 
            from quality_list as ql
            left join farm_quality as fq on ql.quality_list_id = fq.quality_list_id
            and fq.date ='$date' and fq.farm_id = '$farm_id'
            where ql.company_id ='$company_id' ";


        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        return json_encode($queryResult);
    }
    public static function farm_quality_save($getData){

         foreach ($getData as $data){

             $farm_id = $data['farm_id'];
             $date = $data['date'];
             $quality_list_id = $data['quality_list_id'];
             $quantity_value = $data['quantity_value'];

             $object = FarmQuality::model()->findByAttributes(array(
                 'farm_id'=>$farm_id,
                 'date'=>$date,
                 'quality_list_id'=>$quality_list_id,
             ));
             if($object){
                 $object->quality_list_id = $quality_list_id;
                 $object->farm_id = $farm_id;
                 $object->date = $date;
                 $object->quantity_value = $quantity_value;
                 if($object->save()){

                 }else{
                     var_dump($object->getErrors());
                 }
             }else{
                 $object = new FarmQuality();
                 $object->quality_list_id = $quality_list_id;
                 $object->farm_id = $farm_id;
                 $object->date = $date;
                 $object->quantity_value = $quantity_value;
                 if($object->save()){

                 }else{
                     var_dump($object->getErrors());
                 }

             }
         }



    }

}