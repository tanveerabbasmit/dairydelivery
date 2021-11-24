
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/vendor/vendor_payasble_summary_grd.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>
<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $data ?> ,"<?php echo Yii::app()->createAbsoluteUrl('vendor/base'); ?>")'>
			<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li>
					<a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        Vendor Payasble Summary
					</a>
				</li>
			</ul>
			<div class="tab-content">

				<div class=" active" id="tab_1" style="margin: 10px">
					<input style="float: left ;  width: 17% ;margin-bottom: 10px" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
					<button  style="float: left" type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
					<input ng-disabled="false"   style="float: left ; width: 17% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">

                  <!--  <select ng-model="vendor_id" class="form-control input-sm" style="float: left ; width: 17% ;margin-left: 10px;margin-right: 10px">
                       <option value="0">Select payees</option>
                       <option value="{{list.vendor_id}}" ng-repeat="list in vendor_list">{{list.vendor_name}}</option>
                    </select>-->
					<button  ng-disabled="imageLoading" type="button"  ng-click="select_shop_ledger()" class="btn btn-primary btn-sm"> <i class="fa fa-search" style="margin: 5px"></i> Search</button>
                    <div class="dropdown" style="f ;margin-left: 5px">
                        <button class="btn btn-info dropdown-toggle btn-sm" type="button" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-download-alt"></span>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li ng-click="printFunction()"><a href="#">Print</a></li>
                            <li onclick="javascript:xport.toCSV('customers');"><a href="#">Export CSV</a></li>
                        </ul>
                    </div>

                    <!--<button class="btn btn-info btn-sm" style="margin-left: 5px" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share" style="margin: 5px"></i> Export</button>-->
					<img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                     <!-- {{todayDeliveryproductList}}-->
					<style>
						#customers {
							border-collapse: collapse;
							width: 100%;
						}
						#customers td, #customers th {
							border: 1px solid #ddd;
							padding: 8px;
							color: black;
						}
						#customers tr:nth-child(even){background-color: #F8F8FF;}
						#customers tr:hover {background-color: #FAFAD2;}
						#customers th {
							padding-top: 12px;
							padding-bottom: 12px;
							text-align: left;
							color: white;
						}
					</style>

					<table id="customers">
						<thead>
						<tr>
							 <th><a href="#"> Fram Name</a></th>
							<th><a href="#">Opening</a></th>
							<th><a href="#">Bill amount</a></th>
							<th><a href="#">Payment</a></th>
							<th><a href="#">Balance</a></th>

						</tr>
						</thead>
						<tbody>
						<tr ng-repeat="list in list_data.data">

							<td><span ng-bind="list.vendor_name"></span> </td >
							<td><span ng-bind="list.opening_stock"></span> </td >
							<td><span ng-bind="list.purchse_date_range"></span> </td >
							<td><span ng-bind="list.vendor_payment"></span> </td >
							<td><span ng-bind="list.balance"></span> </td >

						</tr>
                        <tr>
                            <th style="color: black">Total</th>
                            <td ><span ng-bind="list_data.total_opening | number"></span></td>
                            <td><span ng-bind="list_data.total_bill_amount | number"></span></td>
                            <td><span ng-bind="list_data.total_payment | number"></span></td>
                            <td><span ng-bind="list_data.total_balance | number"></span></td>
                        </tr>

     					</tbody>
					</table>
				</div>
			</div>



            <div  ng-show="false" id="printTalbe">

                <div style="width:100% ">
                    <div style="width: 50%;float: left">
                        <div> <p style="text-align: center;font-weight: bold;font-size:20px;"> {{copany_object.company_name}}</p></div>
                        <div> <p style="text-align: center;font-weight: bold;font-size:12px;"> {{copany_object.phone_number}}</p></div>
                    </div>
                    <div style="width: 50%;float: left">
                        <div style="text-align: center;">
                            <img style="line-height: 60%;width: 50px ;height: 40px" src="<?php echo Yii::app()->theme->baseUrl; ?>/company_logo/{{copany_object.company_logo}}" alt="" class="media-object img-circle">
                        </div>
                    </div>
                </div>
                <div style="width:100%"></div>

                <div style="width: 100%;float: left"> <p style="text-align: center;font-weight: bold;font-size:16px;">
                        Farms Payasble Summary
                    </p></div>


                <div style="width:100%;float: left">
                     <span style="font-weight: bold;">From :</span>
                     <span> {{startDate}}</span>

                    <span style="font-weight: bold;">To :</span>
                     <span>{{endDate}} </span>
                </div>
                <br>

                <table width="100%" style="border-collapse: collapse; border: 1px solid black;">

                    <tr>

                        <td style=" border: 1px solid black;">Fram Name</td>
                        <td style=" border: 1px solid black;">Opening</td>
                        <td style=" border: 1px solid black;">Bill amount</td>
                        <td style=" border: 1px solid black;">Payment</td>

                        <td style=" border: 1px solid black;">Balance</td>

                    </tr>
                    <tr ng-repeat="list in list_data.data">
                        <td style=" border: 1px solid black;"><span ng-bind="list.vendor_name"></span> </td >
                        <td style=" border: 1px solid black;"><span ng-bind="list.opening_stock"></span> </td >
                        <td style=" border: 1px solid black;"><span ng-bind="list.purchse_date_range"></span> </td >
                        <td style=" border: 1px solid black;"><span ng-bind="list.vendor_payment"></span> </td >
                        <td style=" border: 1px solid black;"><span ng-bind="list.balance"></span> </td >
                    </tr>
                    <tr>
                        <th style="color: black">Total</th>
                        <td style=" border: 1px solid black;"><span ng-bind="list_data.total_opening | number"></span></td>
                        <td style=" border: 1px solid black;"><span ng-bind="list_data.total_bill_amount | number"></span></td>
                        <td style=" border: 1px solid black;"><span ng-bind="list_data.total_payment | number"></span></td>
                        <td style=" border: 1px solid black;"><span ng-bind="list_data.total_balance | number"></span></td>
                    </tr>
                </table>
            </div>





		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {
		$('#searchDate').datepicker({
			format: "yyyy-mm-dd"
		});
		$('#stockDate').datepicker({
			format: "yyyy-mm-dd"
		});
	});
</script>

<style type="text/css">
	.angularjs-datetime-picker {
		z-index: 99999 !important;
	}
</style>
