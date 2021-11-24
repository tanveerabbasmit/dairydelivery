<?php


class bad_debt_record_data
{


      public static function  today_total_bad_debt($clientId,$selectDate){


          $query = "SELECT b.amount ,b.reference_no FROM bad_debt_amount b
            WHERE b.client_id = '$clientId'
            and b.date = '$selectDate'  ";


          $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

          return $queryResult;
      }
      public static function bad_debt_opeening_amount_till_toady($data){

          $startDate = $data['startDate'];
          $endDate = $data['endDate'];
          $clientID = $data['clientID'];

          $query = "SELECT sum(b.amount) as total_amount FROM bad_debt_amount b
            WHERE b.client_id = '$clientID'
            and b.date <= '$endDate'  ";

          $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();

          return intval($queryResult);
      }
      public static function bad_debt_opeening_amount($data){

          $startDate = $data['startDate'];
          $endDate = $data['endDate'];
          $clientID = $data['clientID'];

          $query = "SELECT sum(b.amount) as total_amount FROM bad_debt_amount b
            WHERE b.client_id = '$clientID'
            and b.date < '$startDate'  ";

          $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();

          return intval($queryResult);
      }
      public  static  function bad_debt_record_list($data){


          $startDate = $data['startDate'];
          $endDate = $data['endDate'];
          $clientID = $data['clientID'];

         $query = "SELECT sum(b.amount) as total_amount FROM bad_debt_amount b
            WHERE b.client_id = '$clientID'
            and b.date BETWEEN '$startDate' AND '$endDate' ";

          $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();

          return intval($queryResult);
      }

      public static function total_bad_debt_amount_client_wise(){
          $company_id = Yii::app()->user->getState('company_branch_id');

        $query = "SELECT 
            SUM(a.amount) as amount,
             a.client_id
            FROM bad_debt_amount AS a
            WHERE a.company_id ='$company_id'
            GROUP BY a.client_id ";

          $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

          $final_object = [];
          foreach ($queryResult as $value){
              $client_id =$value['client_id'];
              $final_object[$client_id] = $value['amount'];

          }

         return $final_object;

      }

}