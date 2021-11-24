<?php


class payment_in_out_data
{
    public static function total_delivery_between_date_range($data){
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query = "SELECT 
            p.name, 
            d.date, 
            d.delivery_id,
            dd.product_id,
            sum(dd.quantity) as quantity,
            sum(dd.amount) AS amount
            from delivery AS  d
            LEFT JOIN delivery_detail AS dd ON dd.delivery_id =d.delivery_id
            LEFT JOIN product AS p ON p.product_id =dd.product_id
            WHERE d.date between '$start_date' AND '$end_date'
            AND d.company_branch_id ='$company_id'
            group BY dd.product_id ";

        $product_list  =  Yii::app()->db->createCommand($query)->queryAll();

        $total_quantity = 0;
        $total_amount = 0;

        foreach ($product_list as $value){
             $value['quantity'];
             $value['amount'];
             $total_quantity  = $total_quantity + $value['quantity'];
             $total_amount = $total_amount + $value['amount'];
        }
        $data=[];
        $data['product_list']=$product_list;
        $data['total_quantity']=$total_quantity;
        $data['total_amount']= round($total_amount,2);
        return $data;

    }

    public static function make_total_payment($data){

        $start_date = $data['start_date'];

        $end_date = $data['end_date'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query = "SELECT 
              sum(p.amount_paid) AS  amount_paid
            FROM payment_master AS p
            WHERE p.company_branch_id ='$company_id'
            AND p.date between '$start_date' AND '$end_date' ";



        $product_list  =  Yii::app()->db->createCommand($query)->queryscalar();

        if($product_list){
           return round($product_list,0);
        }
        return  0;
    }
    public static function get_pos_sale_data($data){
        $start_date = $data['start_date'];

        $end_date = $data['end_date'];

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query = "SELECT 
            p.product_id,
            sum(p.quantity) AS quantity,
            sum(p.total_price) AS total_price,
            pro.name
            FROM pos AS p
            LEFT JOIN product  AS pro
            ON p.product_id = pro.product_id
            WHERE p.company_id = '$company_id'
            AND p.date BETWEEN '$start_date' AND '$end_date' 
            GROUP BY p.product_id  ";

        $product_list  =  Yii::app()->db->createCommand($query)->queryAll();


        $total_quantity = 0;
        $total_price = 0;

        foreach ($product_list as $value){

            $total_quantity = $total_quantity + $value['quantity'];
            $total_price   = $total_price   + $value['total_price'];
        }

        $result = [];
        $result['product_list'] = $product_list;
        $result['total_quantity'] = $total_quantity;
        $result['total_price'] = $total_price;

        return $result;

    }


    public static function get_expence_type($data){


        $start_date = $data['start_date'];

        $end_date = $data['end_date'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query = "SELECT 
            sum(ep.amount) AS amount,
            t.type
            FROM expence_report AS ep
            LEFT JOIN expence_type AS t 
            ON t.expence_type = ep.expenses_type_id
            WHERE ep.company_id ='$company_id'
            AND ep.date  
            BETWEEN '$start_date' AND '$end_date'
            GROUP BY ep.expenses_type_id ";

        $result  =  Yii::app()->db->createCommand($query)->queryAll();

        $total_amount = 0;

        foreach ($result as $value){
            $total_amount = $value['amount'] + $total_amount;
        }


        $final_result = [];
        $final_result['result'] =$result;
        $final_result['total_amount'] =$total_amount;

        return $final_result;


    }
    public static function vendor_bill_list($data){
        $start_date = $data['start_date'];

        $end_date = $data['end_date'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query = "SELECT 
            sum(b.gross_amount) as gross_amount ,
            b.bill_from_vendor_id,
            v.vendor_name 
            FROM item  AS i
            LEFT JOIN bill_from_vendor AS b 
            ON b.item_id = i.item_id
            LEFT JOIN  vendor AS v ON  v.vendor_id = b.vendor_id 
            WHERE i.company_id ='$company_id'
            AND b.action_date 
            BETWEEN '$start_date' AND '$end_date'
            GROUP BY v.vendor_id ";

        $result  =  Yii::app()->db->createCommand($query)->queryAll();

        $total_amount = 0;
        foreach($result as $value){
           $gross_amount =  $value['gross_amount'];

           $total_amount = $total_amount + $gross_amount;
        }

        $final_result = [];
        $final_result['list'] = $result;
        $final_result['total_amount'] = $total_amount;
        return $final_result;

    }
    public static function vendor_payment($data){

        $start_date = $data['start_date'];

        $end_date = $data['end_date'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query = "SELECT
            sum(v.amount) AS amount
            FROM  vendor_payment AS v
            WHERE v.company_id ='$company_id'
            AND v.action_date 
            BETWEEN '$start_date' AND '$end_date' ";



        $result  =  Yii::app()->db->createCommand($query)->queryscalar();

        if($result){
            return round($result,0);
        }
        return  0;

    }

    public static function farm_purchase($data){

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query = "SELECT 
            ds.date,
            ds.purchase_rate,
            ifnull(sum(ds.quantity),0) AS quantity ,
            ifnull(sum(ds.wastage),0) AS wastage,
            ifnull(sum(ds.return_quantity),0) AS return_quantity,
            p.name AS product_name,
            f.farm_name 
            FROM daily_stock AS ds 
            LEFT JOIN  product AS p ON p.product_id = ds.product_id
            LEFT JOIN farm AS f ON f.farm_id  = ds.farm_id
            WHERE  ds.date 
            BETWEEN '$start_date' AND '$end_date' 
            and 
            ds.company_branch_id='$company_id' 
            
            GROUP BY f.farm_name ";








        $result = Yii::app()->db->createCommand($query)->queryAll();

        $fina_net = [];

        $total_result=[];
        $total_net_quantity = 0;
        $total_net_amount = 0;
        foreach ($result as $value) {

            $purchase_rate = $value['purchase_rate'];
            $total_quantity =$value['quantity'] -$value['wastage'] -$value['return_quantity'];
            $total_price = $total_quantity * $purchase_rate;
            $value['net_quantity'] = $total_quantity;
            $value['net_amount'] = $total_price;
            $fina_net[] =$value;

            $total_net_quantity = $total_net_quantity  +$total_quantity;

            $total_net_amount = $total_net_amount + $total_price;
        }
        $total_result['total_net_quantity'] =$total_net_quantity;
        $total_result['total_net_amount'] =$total_net_amount;

        $fina_result= [];
        $fina_result['list']= $fina_net;
        $fina_result['total_result']= $total_result;



        return $fina_result;

    }


    public static function calculate_final_balance($result){




        $total_delivery = $result['delivery_list']['total_amount'];
        $pos = $result['pos']['total_price'];
        $expence_type = $result['expence_type']['total_amount'];

        $vendor_payment = $result['vendor_bill_amount']['total_amount'];
        $farm_purchase = $result['farm_purchase']['total_result']['total_net_amount'];

        $balance = $total_delivery + $pos + $expence_type + $vendor_payment + $farm_purchase;

        return round($balance,0);


    }

}