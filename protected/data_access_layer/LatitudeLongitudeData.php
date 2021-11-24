<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class LatitudeLongitudeData{

    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

     public static function actionSaveDimention($clientID,$latitude_new,$longitude_new){
         $clientObject = Client::model()->findByPk(intval($clientID));
         $latitude = $clientObject->latitude ;
         $longitude = $clientObject->longitude ;
          if($latitude==0 OR $longitude==0){
              $clientObject->latitude =$latitude_new ;
              $clientObject->longitude =$longitude_new ;
              if($clientObject->save()){
              }else{
                  $clientObject->getErrors();
              }
          }
     }
}
