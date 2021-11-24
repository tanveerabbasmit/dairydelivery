<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/lastReceipt/lastReceipt-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>

<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $riderList_list;  ?> ,<?php echo $company_id ?> , <?php echo $riderList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('PaymentMaster/getRecivePaymentCustomer'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveDeliveryFromPortal');?>")'>
		<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li>
					<a href="#tab_1" data-toggle="tab" aria-expanded="false">
						 Rider Wise Receipt
					</a>
				</li>
			</ul>

			<div class="tab-content">
				<div class=" active" id="tab_1" style="margin: 10px">

                    <input style="float: left ; width: 20% ;" class="form-control input-sm"   ng-change="chagneNumber(search)"   type="text" placeholder="Put Days" required ng-model="search" size="2">

                    <select style="width: 20%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="rider_id" >
                        <option value="0">All Rider</option>
                        <option ng-repeat="list in riderList_list" value="{{list.rider_id}}">{{list.fullname}}
                        </option>
                    </select>
                    <button type="button"  ng-click="SearchCustomer()" class="btn btn-primary btn-sm"> <i style="margin: 4px" class="fa fa-search"></i> Search</button>
                    <a ng-show="false" class="btn btn-primary btn-sm " href="<?php echo Yii::app()->createUrl('riderDailyStock/exportDialyDeliveryCustomer')?>?date={{todate}}&riderID={{selectRiderID}}"><i style="margin: 4px" class="fa fa-share"></i> Export 20 </a>
                    <button ng-show="false" class="btn btn-info btn-sm" style="float: left; margin-left: 5px" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share"></i> Export 2</button>
                    <button class="btn btn-primary btn-sm " style="margin-left: 5px" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share" style="margin: 5px"></i> Export</button>

					<img style="margin: 7px"  ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
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

							color: white;getRecivePaymentCustomer_LastReceiptCustomer
						}
					</style>

					<table id="customers">
						<thead>
						<tr>
							 <th><a href="#"> #</a></th>
							 <th><a href="#"> ID</a></th>
							 <th><a href="#"> Customer Name</a></th>
							 <th><a href="#"> Address</a></th>
							 <th><a href="#"> Received on</a></th>
							 <th><a href="#"> Days elapsed </a></th>
							 <th><a href="#"> Amount Received </a></th>
							 <th><a href="#"> Current Balance</a></th>

						</tr>
						</thead>
						<tbody>
						<tr  ng-show="regularOrderList.days >= search" ng-repeat="regularOrderList in lastReceiptCustomerList  ">
                             <td>{{$index + 1}}</td>
							<td > <span ng-bind="regularOrderList.client_id"></span>
							<td > <span ng-bind="regularOrderList.fullname"></span>
							<br> {{regularOrderList.cell_no_1}}</td >
							<td><span ng-bind="regularOrderList.address"></span></td>
							<td><span ng-bind="regularOrderList.date"></span></td>
							<td><span ng-bind="regularOrderList.days"></span></td>
							<td style="text-align: right"><span ng-bind="regularOrderList.amount_paid | number :2"></span></td>

							<td style="text-align: right"><span ng-bind="regularOrderList.balance | number:2"></span></td>

						</tr>


						</tbody>


					</table>
				</div>
			</div>

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
