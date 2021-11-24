<?php


class accounting_data
{
    public static $account_all_data = [];
    public static function account_list_array($list){

        foreach ($list as $value){
            $id = $value['id'];
            $code = $value['code'];
            $is_leaf = $value['is_leaf'];
            $name =  str_replace("'","/",$value['name']);;

            $one_object =[];
            $one_object['id'] = $id;

            $one_object['name'] = $name;
             if($is_leaf==1){
                 accounting_data::$account_all_data[] =$one_object;
              }

            $children = $value['children'];
            if(sizeof($children)>0){
               self::account_list_array($children);
            }
        }

    }
    public static function get_account_list(){


        $ch = curl_init();

// Set the url and data
        curl_setopt($ch, CURLOPT_URL, "https://jotter.logic-zone.net/demo/api/auth?ver=v1.2&");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "key=8ffb2a6a11855629671ccbe16dcff248&secret=5c70fd1d5e8a157029042167966fb7fbc8975bda2d3ec4&businessId=4&fiscalYearId=4");


// Set other options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute
        $data = curl_exec($ch);

// Close connection
        curl_close($ch);

// Print the data...

        $token_data = json_decode($data ,true);

        $code = $token_data['data']['token'];


// Set the url and data

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://jotter.logic-zone.net/demo/api/accounts?ver=v1.2&__businessId=4&__fiscalYearId=4");
     // $code = "eyJpZCI6MywibmFtZSI6IkFhbWVyIFVzbWFuIFdhaGVlZCIsImVtYWlsIjoiYXV3YWhlZWRAZ21haWwuY29tIiwiaXNfc3VwZXIiOjAsInJpZ2h0cyI6eyJzdXBlciI6eyJ1c2VycyI6eyJsaXN0IjpmYWxzZSwidmlldyI6ZmFsc2UsInByaW50IjpmYWxzZSwiYWRkIjpmYWxzZSwidXBkYXRlIjpmYWxzZSwiZGVsZXRlIjpmYWxzZSwicmlnaHRzIjpmYWxzZX0sImJ1c2luZXNzZXMiOnsibGlzdCI6ZmFsc2UsInZpZXciOmZhbHNlLCJwcmludCI6ZmFsc2UsImFkZCI6ZmFsc2UsInVwZGF0ZSI6ZmFsc2UsImRlbGV0ZSI6ZmFsc2V9LCJjdXJyZW5jaWVzIjp7Imxpc3QiOmZhbHNlLCJ2aWV3IjpmYWxzZSwicHJpbnQiOmZhbHNlLCJhZGQiOmZhbHNlLCJ1cGRhdGUiOmZhbHNlLCJkZWxldGUiOmZhbHNlfX0sImJ1c2luZXNzIjp7InF1b3RlcyI6eyJsaXN0IjpmYWxzZSwidmlldyI6ZmFsc2UsInByaW50IjpmYWxzZSwiYWRkIjpmYWxzZSwidXBkYXRlIjpmYWxzZSwiZGVsZXRlIjpmYWxzZSwiYXBwcm92ZSI6ZmFsc2UsInBvc3QiOmZhbHNlfSwiaW52b2ljZXMiOnsibGlzdCI6ZmFsc2UsInZpZXciOmZhbHNlLCJwcmludCI6ZmFsc2UsImFkZCI6ZmFsc2UsInVwZGF0ZSI6ZmFsc2UsImRlbGV0ZSI6ZmFsc2UsImFwcHJvdmUiOmZhbHNlLCJwb3N0IjpmYWxzZX0sInB1cmNoYXNlX29yZGVycyI6eyJsaXN0IjpmYWxzZSwidmlldyI6ZmFsc2UsInByaW50IjpmYWxzZSwiYWRkIjpmYWxzZSwidXBkYXRlIjpmYWxzZSwiZGVsZXRlIjpmYWxzZSwiYXBwcm92ZSI6ZmFsc2UsInBvc3QiOmZhbHNlfSwiYmlsbHNfdG9fcGF5Ijp7Imxpc3QiOmZhbHNlLCJ2aWV3IjpmYWxzZSwicHJpbnQiOmZhbHNlLCJhZGQiOmZhbHNlLCJ1cGRhdGUiOmZhbHNlLCJkZWxldGUiOmZhbHNlLCJhcHByb3ZlIjpmYWxzZSwicG9zdCI6ZmFsc2V9LCJleHBlbnNlcyI6eyJsaXN0IjpmYWxzZSwidmlldyI6ZmFsc2UsInByaW50IjpmYWxzZSwiYWRkIjpmYWxzZSwidXBkYXRlIjpmYWxzZSwiZGVsZXRlIjpmYWxzZSwiYXBwcm92ZSI6ZmFsc2UsInBvc3QiOmZhbHNlfX0sImFjY291bnRpbmciOnsiYWNjb3VudHMiOnsibGlzdCI6dHJ1ZSwidmlldyI6dHJ1ZSwicHJpbnQiOnRydWUsImFkZCI6dHJ1ZSwidXBkYXRlIjp0cnVlLCJkZWxldGUiOnRydWV9LCJ0YXhfaGVhZHMiOnsibGlzdCI6dHJ1ZSwidmlldyI6dHJ1ZSwicHJpbnQiOnRydWUsImFkZCI6dHJ1ZSwidXBkYXRlIjp0cnVlLCJkZWxldGUiOnRydWV9LCJ2b3VjaGVycyI6eyJsaXN0Ijp0cnVlLCJ2aWV3Ijp0cnVlLCJwcmludCI6dHJ1ZSwiYWRkIjp0cnVlLCJ1cGRhdGUiOnRydWUsImRlbGV0ZSI6dHJ1ZSwiYXBwcm92ZSI6dHJ1ZSwicG9zdCI6dHJ1ZX19LCJpbnZlbnRvcnkiOnsicHJvZHVjdHNfYmFzZSI6eyJsaXN0IjpmYWxzZSwidmlldyI6ZmFsc2UsInByaW50IjpmYWxzZSwiYWRkIjpmYWxzZSwidXBkYXRlIjpmYWxzZSwiZGVsZXRlIjpmYWxzZX0sInRyYW5zYWN0aW9ucyI6eyJsaXN0IjpmYWxzZSwidmlldyI6ZmFsc2UsInByaW50IjpmYWxzZSwiYWRkIjpmYWxzZSwidXBkYXRlIjpmYWxzZSwiZGVsZXRlIjpmYWxzZSwiYXBwcm92ZSI6ZmFsc2UsInBvc3QiOmZhbHNlfX0sInBhcmFtcyI6eyJmaXNjYWxfeWVhcnMiOnsibGlzdCI6dHJ1ZSwidmlldyI6dHJ1ZSwicHJpbnQiOnRydWUsImFkZCI6dHJ1ZSwidXBkYXRlIjp0cnVlLCJkZWxldGUiOnRydWV9LCJwYXJ0aWVzIjp7Imxpc3QiOnRydWUsInZpZXciOnRydWUsInByaW50Ijp0cnVlLCJhZGQiOnRydWUsInVwZGF0ZSI6dHJ1ZSwiZGVsZXRlIjp0cnVlfSwiZW1wbG95ZWVzIjp7Imxpc3QiOnRydWUsInZpZXciOnRydWUsInByaW50Ijp0cnVlLCJhZGQiOnRydWUsInVwZGF0ZSI6dHJ1ZSwiZGVsZXRlIjp0cnVlfSwibWVkaWEiOnsibGlzdCI6ZmFsc2UsInZpZXciOmZhbHNlLCJhZGQiOmZhbHNlLCJ1cGRhdGUiOmZhbHNlLCJkZWxldGUiOmZhbHNlfX0sInN5c3RlbSI6eyJzZXR0aW5ncyI6eyJ2aWV3Ijp0cnVlLCJ1cGRhdGUiOnRydWV9LCJiYWNrdXBfcmVzdG9yZSI6eyJsaXN0Ijp0cnVlLCJ2aWV3Ijp0cnVlLCJhZGQiOnRydWUsInVwZGF0ZSI6dHJ1ZSwiZGVsZXRlIjp0cnVlfSwiYXBpX2NyZWRlbnRpYWxzIjp7InZpZXciOnRydWUsInVwZGF0ZSI6dHJ1ZX19fSwidGltZXN0YW1wIjoxNjIyNjI3NjA1fQ==.c7abfa73ce63427c924223539975debc";
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' .$code,
        ]);

// Set other options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute
        $data = curl_exec($ch);

// Close connection
        curl_close($ch);

// Print the data...


        return json_decode($data,true);


    }
    public static function auth_token(){

        $ch = curl_init();

// Set the url and data
        curl_setopt($ch, CURLOPT_URL, "https://jotter.logic-zone.net/demo/api/auth?ver=v1.2&");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "key=fa4a654b1f01d27126bd9cb47009ae36&secret=8a005846f6ec158783282358288d1793536bd521294bec&businessId=14&fiscalYearId=14");


// Set other options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute
        $data = curl_exec($ch);

// Close connection
        curl_close($ch);

// Print the data...
        return $data;

    }

    public static function save_jv_with_sale($start_date,$product_id,$jv_id){
        $company_branch_id = Yii::app()->user->getState('company_branch_id');
        $query = "UPDATE delivery_detail SET jv_id = '$jv_id'
        where product_id ='$product_id' and date ='$start_date'";
        $result =  Yii::app()->db->createCommand($query)->execute();
    }
    public static function save_jv_with_customer_payment($start_date,$customer_list,$jv_id){

        foreach ($customer_list as $value){

           $payment_master_id = $value['payment_master_id'];
           $object =PaymentMaster::model()->findByPk($payment_master_id);

           $object->jv_create_date =$start_date;
           $object->jv_id =$jv_id;
           if($object->save()){

           }else{

           }

        }

    }

    public static function save_jv_with_vendor_payment($today_date,$customer_list ,$id){

        foreach ($customer_list as $value){
            $vendor_payment_id = $value['vendor_payment_id'];
            $object =VendorPayment::model()->findByPk($vendor_payment_id);
            $object->jv_id = $id;
            $object->jv_create_date = date("Y-m-d");
            $object->save();

        }



    }
    public static function save_jv_with_farm_payment($today_date,$customer_list ,$id){

        foreach ($customer_list as $value){

            $farm_payment_id = $value['farm_payment_id'];

            $object =FarmPayment::model()->findByPk($farm_payment_id);
            $object->jv_id = $id;
            $object->jv_create_date = date("Y-m-d");
            $object->save();

        }



    }
    public static function account_list($token){


// Open connection
        $ch = curl_init();

// Set the url and data
        curl_setopt($ch, CURLOPT_URL, "https://jotter.logic-zone.net/demo/api/accounts?ver=v1.2&__businessId=14&__fiscalYearId=14");

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' .$token,
        ]);

// Set other options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute
        $data = curl_exec($ch);

// Close connection
        curl_close($ch);

// Print the data...
        return $data;


    }

    public function find_payment_count($payment){
        $total_count = 0;
        foreach ($payment as $value){
          $amountpaid =  $value['amountpaid'];
          $total_count = $total_count + $amountpaid;
        }
        return $total_count;
    }


    public static function save_jv_with_farm_purchase($start_date,$customer_list,$jv_id){


        foreach ($customer_list as $value){


            $payment_master_id = $value['daily_stock_id'];
            $object =DailyStock::model()->findByPk($payment_master_id);

            $object->jv_create_date =$start_date;
            $object->jv_id =$jv_id;
            if($object->save()){

            }else{

            }

        }

    }
    public static function save_jv_with_vendor_purchase($start_date,$customer_list,$jv_id){


        foreach ($customer_list as $value){



            $bill_from_vendor_id = $value['bill_from_vendor_id'];
            $object =BillFromVendor::model()->findByPk($bill_from_vendor_id);

            $object->jv_create_date =$start_date;
            $object->jv_id =$jv_id;
            if($object->save()){

            }else{

            }

        }

    }

    public static function get_accoutn_name_by_id($debit_account_id,$account_list){

        foreach ($account_list as $value){
            $id = $value['id'];
            $name = $value['name'];
            if($debit_account_id == $id){
                return $name;
            }
        }
    }

    public static function get_assign_account_function($voucher_type_id){
         $object = AssignAccount::model()->findByAttributes([
             'voucher_type_id'=>$voucher_type_id
         ]);
         return $object;
    }
}
