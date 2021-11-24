<?php

class ShopstockController extends Controller
{
	public function actionShop_issue_stock()
	{
        $company_id = Yii::app()->user->getState('company_branch_id');
        $todayDate =date("y-m-d");
        $data = [];
        $data['today_date'] =$todayDate;
        $data['shop_list'] = shop_list_data::get_shop_list();
        $data['product_list'] = productData::product_list();



        $this->render('shop_issue_stock',array(
             'data'=>json_encode($data)
        ));
	}


}