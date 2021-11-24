<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class companyBranchData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

       public static function getCompanyBranchList(){
           $companyBranch = CompanyBranch::model()->findAll();
           $companyBranchList = array();
            foreach($companyBranch as $value){
                $companyBranchList[] = $value->attributes;
            }
            return json_encode($companyBranchList);
       }
       public static function get_sub_company_list(){

           $company_id = Yii::app()->user->getState('company_branch_id');


            $query = "SELECT 
                c.company_id ,
                c.company_name
                FROM sub_company AS sub 
                LEFT JOIN company AS c 
                ON c.company_id = sub.sub_company_ids
                WHERE sub.company_id = '$company_id' ";

           $rider_zone = Yii::app()->db->createCommand($query)->queryAll();

           return $rider_zone;



       }

}