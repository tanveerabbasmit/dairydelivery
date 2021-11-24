<style>
    .angularjs-datetime-picker{
        z-index: 99999 !important;
    }
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/makePayment/makePayment_bad_debt_grid.js"></script>


<link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/monthPickerFile/examples.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/monthPickerFile/MonthPicker.min.css" rel="stylesheet" type="text/css" />

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>
<?php

$company_id = Yii::app()->user->getState('company_branch_id');
$user_id = Yii::app()->user->getState('user_id');
$disabled_result = true;
if($company_id ==1){
    if($user_id!=2){
        $disabled_result = false;
    }
}
$date_object = [];
$date_object[]['date'] = date("Y-m-d");
$date_object[]['date'] = date('Y-m-d', strtotime('-1 day', strtotime(date("Y-m-d"))));
$date_object[]['date'] =date('Y-m-d', strtotime('-2 day', strtotime(date("Y-m-d"))));



?>



<div class="panel row" id="testContainer" style="display: none" ng-app="clintManagemaent">
    <div ng-controller="clintManagemaent" ng-init='init("<?php echo Yii::app()->createAbsoluteUrl('baddebtamount/base_'); ?>" ,<?php echo json_encode($required_name); ?> , <?php echo $collectionvault_list; ?>,<?php echo json_encode($date_object); ?>,<?php echo json_encode($disabled_result) ?> ,<?php echo $discount_type ?> ,<?php echo $crud_role ?> ,<?php echo $todayYear ?> , <?php echo $todayMonth ?> , <?php  echo $company_id  ?> , <?php echo $clientList ?> ,
	 "<?php echo Yii::app()->createAbsoluteUrl('baddebtamount/baddebtamount_save'); ?>" ,
	  "<?php echo Yii::app()->createAbsoluteUrl('client/oneCustomerAmountList'); ?>" , "<?php echo Yii::app()->createAbsoluteUrl('api/checkAccountBalnce'); ?>")'>

        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Bad Debt Payment<span ng-show="showBalance" style="margin-left: 10px">  Current Balance : </span> <span style="color: #D3D3D3; margin-left: 2px"> {{OneCustomerOustaningBalance }} </span>
                </a>
            </li>
        </ul>
        <div class="panel-body">


            <div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ;z-index: 1;">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>message! </strong>
                {{taskMessage}}
            </div>



            <form ng-submit="save_bad_payment()" style="margin-top: 5px">


                <div class="col-lg-12">
                    <div class="col-lg-6" style="background-color: honeydew">

                        <div class="col-lg-4 " style="padding-top: 10px" >
                            <span style="font-weight: bold;">Date :</span>
                        </div>

                        <div class="col-lg-8">

                            <input   class="form-control"  ng-show="disabled_result"   datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="paymentObject.startDate" size="2">

                            <select ng-show="!disabled_result" class="form-control" ng-model="paymentObject.startDate">
                                <option ng-repeat="list in date_object" value="{{list.date}}">{{list.date}}</option>
                            </select>

                        </div>
                    </div>
                    <div class="col-lg-6" style="background-color: honeydew">
                        <div class="col-lg-4" style="padding-top: 10px">
                            <span style="font-weight: bold;">Reference No.</span>
                        </div>
                        <div class="col-lg-8">
                            <input  type="text" ng-model="paymentObject.trans_ref_no" class="form-control" required>
                        </div
                    </div>
                </div>
                <div class="panel-body">
                </div>
                <div class="col-lg-6" style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding-top: 10px">
                        <span style="font-weight: bold;">Amount Paid :</span>
                    </div>
                    <div class="col-lg-8">
                        <input ng-change="saveOriginalAmount(paymentObject.amount_paid)" type="number" ng-model="paymentObject.amount_paid" class="form-control" required>
                    </div>
                </div>


                <div class="col-lg-6" style="background-color: 	#FFF0F5">

                    <div class="col-lg-4" style="padding-top: 10px">
                        <span style="font-weight: bold;">Customer : </span>
                    </div>
                    <div class="col-lg-8">
                        <div style="float: left;">
                            <button class="btn btn-default dropdown-toggle " ng-click="showDropDownList()" type="button" id="dropdownMenu1" data-toggle="dropdown">{{SelectedCustomer}} <span  class="caret"></span>
                            </button>
                            <ul style="width: 200px ;max-height: 500px ; overflow-x: hidden;overflow-y: scroll;" class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" >
                                <li role="presentation">
                                    <div class="input-group input-group-sm search-control"> <span class="input-group-addon">
									<span ></span>
									</span>
                                        <input autofocus type="text" class="form-control" placeholder="Search" ng-model="query" id="serachCustomerBar">
                                    </div>

                                </li >
                                <li  role="presentation" ng-click="abcd(item)" ng-repeat='item in clientList | filter:query'> <a href="#"> {{item.fullname}} </a>
                                </li>
                            </ul>
                        </div>
                        <img ng-show="loadClientLoader" style="margin: 10px" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">

                    </div>

                </div>

                <div class="panel-body">
                </div>


                <div class="col-lg-12" style="background-color: 	honeydew;" >
                    <div class="col-lg-9"></div>
                    <div class="col-lg-3">

                        <button  type="submit" class="btn btn-sm btn-info next" > <i class="fa fa-edit"></i> Save</button>
                        <img ng-show="imageLoader" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                    </div>


                </div>



            </form>

            <div  class="col-lg-12" ng-show="address" style="background-color: #FFF8DC; margin-top: 10px">
                <div style="float: left">
                    <span style="font-weight: bold;">Address :  </span> {{address}}
                </div>

                <div style="float: left ; margin-left: 20px">
                    <span style="font-weight: bold;">Contact Number :  </span> {{cell_no_1}}
                </div>

                <div style="float: left ; margin-left: 20px">
                    <span style="font-weight: bold;">Zone :  </span> {{zone_name}}
                </div>

                <div style="float: left ; margin-left: 20px">
                    <span style="font-weight: bold;">Payment Term  :  </span> {{payment_term}}
                </div>
            </div>

        </div>

        <div class="col-lg-12">

            <table style="margin-top:25px;" class="table table-striped nomargin" ng-show="!mainPageSwitch">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Customer </th>
                    <th>Received Date </th>
                    <th>Reference No. </th>
                    <th>Received Amount</th>


                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="product in OneCustomerPaymentData track by $index">
                    <td>{{$index +1}}</td>
                    <td style="background-color: {{product.color}}">{{product.fullname}}</td>
                    <td>
                        <span ng-show="!product.update">{{product.date}}</span>
                        <input ng-show="product.update" type="text" class="form-control" ng-model="product.date" datetime-picker date-only  date-format="yyyy-MM-dd">
                    </td>
                    <td>
                        <span ng-show="!product.update">{{product.reference_no}}</span>
                        <input ng-show="product.update" type="text" class="form-control" ng-model="product.reference_no">

                    </td>

                    <td>
                        <span ng-show="!product.update">{{product.amount | number :2}}</span>

                        <input ng-show="product.update" type="text" class="form-control" ng-model="product.amount">
                    </td>




                    <td>

                        <ul class="table-options ">

                            <li><button ng-show="product.update" class="btn-success btn-xs"  ng-click="update_payment(product)"><i class="fa fa-save btn btn-info btn-xs"></i></button></li>

                            <li><button ng-show="!product.update" class="btn-xs"   ng-click="product.update=true"><i class="fa fa-edit btn btn-info btn-xs"></i></button></li>

                            <li><button class="btn-xs"   ng-click="deleteClientButton(product.bad_debt_amount_id)"><i class="fa fa-trash btn btn-info btn-xs"></i></button></li>
                        </ul>
                    </td>

                </tr>
                </tbody>

                <modal title="Confirm" visible="add_security_code_model">
                    <form role="form" class="form-group" ng-submit="security_code_function()">
                        <div class="col-lg-12 form-group" style="background-color: honeydew">
                            <div class="col-lg-4">
                                <span style="font-weight: bold;font-size: 13px; padding: 8px">Security Code:</span>
                            </div>

                            <div class="col-lg-8">

                                <input type="password" id="security_code" ng-model="security_code" class="form-control"   required/>
                            </div>

                        </div>
                        <div class=" form-group ">


                            <button type="button" ng-click="security_code_function()" class="btn-success  btn-sm">OK</button>
                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </modal>
        </div>

        <style>
            .dropdown.dropdown-scroll .dropdown-menu {
                max-height: 200px;
                width: 60px;
                overflow: auto;
            }
        </style>
    </div>

