<style>
	.angularjs-datetime-picker{
		z-index: 99999 !important;
	}
</style>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/makePayment/makePayment-grid.js"></script>
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
	<div ng-controller="clintManagemaent" ng-init='init(<?php echo json_encode($required_name); ?> , <?php echo $collectionvault_list; ?>,<?php echo json_encode($date_object); ?>,<?php echo json_encode($disabled_result) ?> ,<?php echo $discount_type ?> ,<?php echo $crud_role ?> ,<?php echo $todayYear ?> , <?php echo $todayMonth ?> , <?php  echo $company_id  ?> , <?php echo $clientList ?> ,
	 "<?php echo Yii::app()->createAbsoluteUrl('client/paymentMethod'); ?>" ,
	  "<?php echo Yii::app()->createAbsoluteUrl('client/oneCustomerAmountList'); ?>" , "<?php echo Yii::app()->createAbsoluteUrl('api/checkAccountBalnce'); ?>")'>

		<ul class="nav nav-tabs nav-tabs-lg">
			<li>
				<a href="#tab_1" data-toggle="tab" aria-expanded="false">
					make Payment<span ng-show="showBalance" style="margin-left: 10px">  Current Balance : </span> <span style="color: #D3D3D3; margin-left: 2px"> {{OneCustomerOustaningBalance }} </span>
				</a>
			</li>
		</ul>
		<div class="panel-body">


            <div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ;z-index: 1;">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>message! </strong>
                {{taskMessage}}
            </div>

            <div  class="col-lg-12" ng-show="address"  style="margin-bottom: 5px">
                <div  class="col-lg-12" style="background-color: #FFF8DC; margin-top: 10px">
                    <h3> {{closing_balance}}</h3>
                </div>
            </div>
            <br>

			<form ng-submit="savePaymernt()" style="margin-top: 5px">
				<!--<div class="col-lg-12" style="padding: 5px;background-color: honeydew">
					<div class="col-lg-6" style="background-color: honeydew">

						<div class="col-lg-6 " style="padding-top: 10px" >
							<span style="font-weight: bold;font-size: 18px">Payment  Type :</span>
						</div>

						<div class="col-lg-6" style="padding-top: 5px">

                            <label class="radio-inline">
                                <input type="radio" name="payment_type" value="0" ng-model="paymentObject.payment_type">Payment
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="payment_type" value="1" ng-model="paymentObject.payment_type">Bad Debt
                            </label>

						</div>
					</div>

				</div>-->

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
						<span style="font-weight: bold;">Mode : </span>
					</div>
					<div class="col-lg-8">
						<select  ng-model="paymentObject.payment_mode" class="form-control" required>
							<option value="">Select</option>
							<option value="2">cheque</option>
							<option value="3">Cash</option>
							<option value="5">Bank Transaction</option>
							<option value="6">Card Transaction</option>
						</select>
					</div>
				</div>
				<div class="col-lg-6">

				</div>

				<div class="panel-body">
				</div>
				<div class="col-lg-6" style="background-color: 	honeydew">
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

				<div class="col-lg-6" style="background-color: 	honeydew">
					<div class="col-lg-4" style="padding-top: 10px">
						<span style="font-weight: bold;">Bill Month : </span>
					</div>
					<div class="col-lg-8">


                        <select style="float: left ; width: 50% ;" class="form-control input-sm" ng-model="paymentObject.bill_month">
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>

                        <!--<input style="float: left ; width: 22% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">-->
                        <!--<button  style="float: left" type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                        <input ng-disabled="false" style="float: left ; width: 22% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">-->

                        <select style="float: left ; width: 50% ;" class="form-control input-sm"  ng-model="paymentObject.bill_year">
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                        </select>
						<!--<input id="NoIconDemo" type="text" class="form-control" ng-model="paymentObject.bill_month"  style="width: 100%"/>-->
					</div>
				</div>


                <div class="panel-body">
                </div>


                <div class="col-lg-6" style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding-top: 10px">
                        <span style="font-weight: bold;">Collection Vault :</span>
                    </div>
                    <div class="col-lg-8">

                        <select class="form-control" ng-model="paymentObject.collection_vault_id"  ng-required="required_name=='required'">
                           <option value="">Select</option>
                            <option ng-repeat="list in collectionvault_list" value="{{list.collection_vault_id}}">{{list.collection_vault_name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6" style="background-color: 	#FFF0F5"></div>

                <div class="panel-body">
                </div>

                <div class="col-lg-12" style="background-color: 	#FFF0F5">
                    <div class="col-lg-3" style="padding-top: 10px">
                        <span style="font-weight: bold;">Discount :</span>
                    </div>
                    <div class="col-lg-9" style="padding-top: 10px">
                      <table  style=" border-collapse: collapse;width: 100%; border: 1px solid #D3D3D3;margin-bottom: 10px" >
                        <thead>
                        <tr >
                            <td style=" border: 1px solid #D3D3D3;padding: 5px">Type </td>
                            <td style=" border: 1px solid #D3D3D3;padding: 5px">Amount </td>
                            <td style=" border: 1px solid #D3D3D3"> </td>
                            <td style=" border: 1px solid #D3D3D3;width: 50px"> </td>
                        </tr>
                        </thead>
                        <tbody>
                            <tr style="background-color: white" ng-repeat="type in discount_type track by $index">
                                 <td style=" border: 1px solid #D3D3D3;margin: 5px;padding: 5px" >{{type.discount_type_name}}</td>
                                <td style=" border: 1px solid #D3D3D3;padding: 5px"><input  ng-change="changeDiscount()" style="margin: 5px" class="" ng-model="type.discount_amount"></td>
                                <td style=" border: 1px solid #D3D3D3;padding: 5px">
                                    <div class="checkbox">
                                        <label><input ng-change="changeDiscount()" ng-model="type.percentage" type="checkbox" value="">percentage</label>
                                    </div>
                                </td>
                                <td style=" border: 1px solid #D3D3D3;text-align: right;padding: 5px">
                                      {{type.calculated_discount}}
                                </td>
                            </tr>
                            <tr style="background-color: white">
                                <td style=" border: 1px solid #D3D3D3;padding: 5px">Total Amount</td>
                                <td style=" border: 1px solid #D3D3D3;text-align: right;padding: 5px">{{originalAmount}}</td>

                                <td style=" border: 1px solid #D3D3D3;padding: 5px">Total Discount</td>
                                <td style=" border: 1px solid #D3D3D3;text-align: right;padding: 5px">{{total_sum_discount}}</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>

                </div>

				<div class="panel-body">
				</div>
				<div class="col-lg-12" style="background-color: 	honeydew;" >
                    <div class="col-lg-9"></div>
                    <div class="col-lg-3">
                        <button   type="button" ng-click="get_today_payment_list()" class="btn btn-sm btn-primary next" > <i class="fa fa-eye"></i> View Today Payment </button>
                        <button  ng-disabled="imageLoader || allow_delete[1]" type="submit" class="btn btn-sm btn-info next" > <i class="fa fa-edit"></i> Save</button>
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

					<th>Collection Vault</th>

					<th>Received Amount</th>
					<th>Bill From Month</th>
					<th>Mode</th>

					<th>paid for</th>

                    <th>Discount</th>

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
                        <span ng-show="!product.update">{{product.reference_number}}</span>
                        <input ng-show="product.update" type="text" class="form-control" ng-model="product.reference_number">

                    </td>

                    <td>
                        <span ng-show="!product.update">{{product.collection_vault_name}}</span>

                        <select    ng-show="product.update"   class="form-control" ng-model="product.collection_vault_id">
                            <option value="">Select</option>
                            <option ng-repeat="list in collectionvault_list" value="{{list.collection_vault_id}}">{{list.collection_vault_name}}</option>
                        </select>
                    </td>


                    <td>
                        <span ng-show="!product.update">{{product.amount_paid | number :2}}</span>

                        <input ng-show="product.update" type="text" class="form-control" ng-model="product.amount_paid">
                    </td>
                    <td>
                        {{product.tomonth_delivery | number}}
                    </td>
					<td>
                        <span ng-show="!product.update"> {{product.payment_mode_text}}</span>
                         <select    ng-show="product.update"   class="form-control" ng-model="product.payment_mode">
                             <option value="">Select</option>
                             <option value="2">cheque</option>
                             <option value="3">Cash</option>
                             <option value="5">Bank Transaction</option>
                             <option value="6">Card Transaction</option>
                         </select>
                    </td>
					<td>

                       <span ng-show="!product.update"> {{product.bill_month_date | date:'MMMM, yyyy'}}</span>

                        <select ng-show="product.update" style="float: left ; width: 130px ;" class="form-control input-sm" ng-model="product.get_month">
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>

                        <!--<input style="float: left ; width: 22% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">-->
                        <!--<button  style="float: left" type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                        <input ng-disabled="false" style="float: left ; width: 22% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">-->

                        <select ng-show="product.update" style="float: left ; width: 130px ;" class="form-control input-sm"  ng-model="product.get_year">
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                        </select>

                    </td>

                    <td style="text-align: center">
                        <a href="" ng-click="view_discount_amount(product)"> <span ng-bind="product.discount_list.total_amount"></span></a>
                    </td>

					<td>

                        <ul class="table-options ">

                           <li><button ng-show="product.update" class="btn-success btn-xs"  ng-disabled="allow_delete[2]" ng-click="update_payment(product)"><i class="fa fa-save btn btn-info btn-xs"></i></button></li>

                           <li><button ng-show="!product.update" class="btn-xs"  ng-disabled="allow_delete[2]" ng-click="product.update=true"><i class="fa fa-edit btn btn-info btn-xs"></i></button></li>

                           <li><button class="btn-xs"  ng-disabled="allow_delete[2]" ng-click="deleteClientButton(product.payment_master_id)"><i class="fa fa-trash btn btn-info btn-xs"></i></button></li>
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

                <modal title="Discount List" visible="view_discount_model">
                    <form role="form" class="form-group" ng-submit="save_discount_function()">
                        <div class="col-lg-12 form-group" ng-repeat="list in discount_list" style="background-color: honeydew">
                            <div class="col-lg-4">
                                <span style="font-weight: bold;font-size: 13px; padding: 8px">{{list.discount_type_name}}</span>
                            </div>

                            <div class="col-lg-8">

                                <input type="text" id="security_code" ng-model="list.total_discount_amount" class="form-control"   required/>
                            </div>

                        </div>
                        <div class=" form-group ">

                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>

                            <button type="button" ng-click="change_discount_amount_function()" class="btn-success  btn-sm">Save</button>
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

