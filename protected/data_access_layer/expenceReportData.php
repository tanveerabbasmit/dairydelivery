<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2018-04-15
 * Time: 9:25 AM
 */

class expenceReportData
{


    public static function getExpenceRecord($expenceType , $startDate,$endDate){
         $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT * FROM expence_report AS er
            left join expence_type as et ON er.expenses_type_id = et.expence_type
            where er.company_id = '$company_id' ";

        if($expenceType){
            $query .=" and  er.expenses_type_id = '$expenceType' ";
        }
        if($startDate){
            $query .=" and er.date between '$startDate' and '$endDate' ";
        }
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
        return json_encode($queryResult);
    }
}