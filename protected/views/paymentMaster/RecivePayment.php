<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/table/table_style.css">

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/RecivePayment/RecivePayment-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $company_id ?> , <?php echo $riderList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('PaymentMaster/getRecivePaymentCustomer'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveDeliveryFromPortal');?>")'>
		<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li style="padding: 5px">
					Rider Wise Receipt
				</li>
			</ul>

			<div class="tab-content">
				<div class=" active" id="tab_1" style="margin: 10px">
					<input style="float: left ; width: 22% ;" class="form-control"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
					<button  style="float: left" type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info ">TO</button>
					<input style="float: left ; width: 22% ;" class="form-control"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
					<select style="width: 30%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control" ng-model="selectRiderID" >
						<option value="">Select Rider</option>
						<option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}
						</option>
					</select>
					<button type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-primary "> <i class="fa fa-search"></i> Search</button>

					<a ng-disabled="false"  onclick="javascript:xport.toCSV('customers');" class="btn btn-primary " ><i class="fa fa-share"></i> Export</a>
					<img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                     <!-- {{todayDeliveryproductList}}-->



					<table id="customers" class="table table-fixed">
						<thead>
						<tr>
							 <th class="col-xs-2"><a href="#"> #</a></th>
							 <th class="col-xs-4"><a href="#"> Customer Name</a></th>
							 <th class="col-xs-4"><a href="#"> Address</a></th>
							 <th class="col-xs-2"><a href="#"> Amount</a></th>

						</tr>
						</thead>
						<tbody>
						<tr ng-repeat="regularOrderList in todayDeliveryproductList | orderBy:sortType:!sortReverse  track by $index">
                            <td class="col-xs-2">{{$index + 1}}</td>
							<td class="col-xs-4"> <span ng-bind="regularOrderList.fullname"></span>
								<br> {{regularOrderList.cell_no_1}}</td >

							<td class="col-xs-4"><span ng-bind="regularOrderList.address"></span></td>
							<td class="col-xs-2" style="text-align: right"><span ng-bind="regularOrderList.total_recive | number :2"></span></td>

						</tr>
						<tr>
                            <td class="col-xs-2"></td>
                            <td class="col-xs-4"></td>
                            <td class="col-xs-4"><a href="#">Total</a></td>
                            <td class="col-xs-2" style="text-align: right">{{totalSum | number :2}}</td>
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
