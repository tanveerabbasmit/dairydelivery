
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/DateRangeRiderledger/DateRangeRiderledger-grid.js"></script>
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
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo json_encode($fiveDayAgo) ?> , <?php echo $company_id ?> , <?php echo $riderList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('PaymentMaster/DateRangeRiderledger_getData'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveDeliveryFromPortal');?>")'>
			<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li>
					<a href="#tab_1" data-toggle="tab" aria-expanded="false">
						Date Range Customer Ledger
					</a>
				</li>
			</ul>
			<div class="tab-content">
				<div class=" active" id="tab_1" style="margin: 10px">
					<input style="float: left ; width: 22% ;margin-bottom: 10px" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
					<button  style="float: left" type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
					<input ng-disabled="false" style="float: left ; width: 22% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
                    <select ng-model="paid_month" class="form-control input-sm" style="float: left ; width: 22% ;margin-left: 10px;margin-right: 10px">
                       <option value="date">Paid Date</option>
                       <option value="bill_month_date">Paid Month</option>
                    </select>
					<button type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-primary btn-sm"> <i class="fa fa-search" style="margin: 5px"></i> Search</button>
					<button type="button"  ng-click="printFunction()" class="btn btn-primary btn-sm"> <i class="fa fa-print" style="margin: 5px"></i> Print</button>
					<button class="btn btn-info btn-sm" style="margin-left: 5px" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share" style="margin: 5px"></i> Export</button>
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
							 <th><a href="#"> #</a></th>
							<th><a href="#">Customer Name</a></th>
							<th><a href="#">Opening Balance</a> </th>
							<th><a href="#"> Delivery</a></th>
							<th> <a href="#"> Amount Paid</a></th>
							<th><a href="#"> Outstanding Balance</a></th>
						</tr>
						</thead>
						<tbody>
						<tr ng-repeat="regularOrderList in todayDeliveryproductList | orderBy:sortType:!sortReverse  track by $index">
                             <td>{{$index + 1}}</td>

							<td> <a href="<?php echo Yii::app()->createUrl('PaymentMaster/RiderWiseDateRangeCustomerLedger')?>?rider_id={{regularOrderList.rider_id}}&startDate={{startDate}}&endDate={{endDate}}&paid_month={{paid_month}}">{{regularOrderList.fullname}} </a></td >


							<td style="text-align: right"><span ng-bind="regularOrderList.OpeningBlance | number :2"></span></td>
							<td style="text-align: right"><span ng-bind="regularOrderList.totalMakeDelivery | number :2"></span></td>


							<td style="text-align: right"><span ng-bind="regularOrderList.totalMakePayment | number :2"></span></td>
							<td style="text-align: right"><span ng-bind="regularOrderList.balance | number :2"></span></td>
						</tr>
						<tr>

							 <td></td>
							<th ><a href="#">Total</a></th>
							<td style="text-align: right">{{total_OpeningBlance | number :2}}</td>
							<td style="text-align: right">{{total_totalMakeDelivery | number :2}}</td>
							<td style="text-align: right">{{amountPaid | number}}</td>
							<td style="text-align: right">{{totalOutStandingBalance | number :2}}</td>
						</tr>
     					</tbody>
					</table>
				</div>
			</div>

			<table width="100%" ng-show="false" id="printTalbe" style="border-collapse: collapse; border: 1px solid black;">
                   <tr>
					   <td style=" border: 1px solid black;" colspan="3">Date Rang: </td>
					   <td style=" border: 1px solid black;" colspan="5">{{startDate}} TO {{endDate}} </td>
				   </tr>
                  <tr>
					   <td style=" border: 1px solid black;" colspan="3">Rider </td>
					   <td style=" border: 1px solid black;" colspan="5">{{selectedRider}}</td>
				   </tr>
				  <tr>
					 <td style=" border: 1px solid black;">#</td>
					 <td style=" border: 1px solid black;">ID</td>
					 <td style=" border: 1px solid black;">Customer Name</td>
					 <td style=" border: 1px solid black;">Address</td>



					 <td style=" border: 1px solid black;">Opening Balance</td>
					 <td style=" border: 1px solid black;">Delivery</td>

					 <td style=" border: 1px solid black;">Amount Paid</td>
					 <td style=" border: 1px solid black;"> Outstanding Balance</td>
				 </tr>
				<tr ng-repeat="regularOrderList in todayDeliveryproductList | orderBy:sortType:!sortReverse  track by $index">
					<td style=" border: 1px solid black;">{{$index + 1}}</td>
					<td style=" border: 1px solid black;"><span ng-bind="regularOrderList.client_id"></span></td>
					<td style="color: {{regularOrderList.color}};border: 1px solid black;" > <span ng-bind="regularOrderList.fullname"></span>
						<br> {{regularOrderList.cell_no_1}}</td >





					<td style=" border: 1px solid black;"><span ng-bind="regularOrderList.address"></span></td>
					<td style="text-align: right;border: 1px solid black;"><span ng-bind="regularOrderList.OpeningBlance | number"></span></td>
					<td style="text-align: right;border: 1px solid black;"><span ng-bind="regularOrderList.totalMakeDelivery | number"></span></td>
					<td style="text-align: right;border: 1px solid black;"><span ng-bind="regularOrderList.totalMakePayment | number"></span></td>
					<td style="text-align: right;border: 1px solid black;"><span ng-bind="regularOrderList.balance | number"></span></td>

				</tr>
				<tr>
					<td style=" border: 1px solid black;"></td>
					<th style=" border: 1px solid black;" colspan="3">Total</th>
					<td style="text-align: right ;border: 1px solid black;">{{total_OpeningBlance | number :2}}</td>
					<td style="text-align: right;border: 1px solid black;">{{total_totalMakeDelivery | number :2}}</td>
					<td style="text-align: right;border: 1px solid black;">{{amountPaid | number}}</td>
					<td style="text-align: right;border: 1px solid black;">{{totalOutStandingBalance | number:2}}</td>
				</tr>


			</table>

			<!--Company Limit Model-->
			<modal title="Set Company Limit" visible="limitModelShow">
				<div class="row">
					<?php
						$form = $this->beginWidget(
							'CActiveForm',
							array(
								'id' => 'agreement-form',
								'enableAjaxValidation' => false,
							)
						);
					?>
					<div class="col-sm-12">
						<div class="col-sm-12">
							<label for="email" style="font-weight: bold;float: left;margin: 10px">Company Limit :</label>
							<input style="float: left; width: 40%" type="number" class="form-control" name="companyLimit" placeholder="" ng-model="companyLimit" required />
						</div>
						<div class="col-sm-12">
							<div style="margin: 12px">
								<button  type="submit" class="btn-success  btn-sm">Save</button>
								<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
							</div>
						</div>
						<?php $this->endWidget(); ?>
					</div>
			</modal>


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
