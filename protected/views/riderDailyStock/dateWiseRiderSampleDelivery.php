
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/dateWiseRiderSampleDelivery/dateWiseRiderSampleDelivery-grid.js"></script>

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
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $lableObject ?> ,<?php echo $productList ?> ,<?php echo $todayData ?> , <?php echo $riderList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/getDateWiseRiderSampleDelivery_report'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveDeliveryFromPortal');?>")'>
		<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li>
					<a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        Date Wise Rider Sample Delivery
					</a>
				</li>
			</ul>
          <!--  {{productList}}
			{{todayData}}-->

			<div class="tab-content">
				<div class=" active" id="tab_1" style="margin-bottom: 10px;  margin: 10px">
					<input style="float: left ; width: 16% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
					<button style="float: left" type="button" ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
					<input style="float: left ; width: 16% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">


					<select  style="width: 16%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="product_id" >
						<option value="0">Product</option>
						<option ng-repeat="list in productList" value="{{list.product_id}}">{{list.name}}
						</option>
					</select>

                    <select  style="width: 16%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="selectRiderID" >
						<option value="0">All Rider</option>
						<option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}
						</option>
					</select>
					<button style="margin-left: 5px;" type="button"  ng-click="selectRiderOnChange(1)" class="btn btn-primary  btn-sm"> <i class="fa fa-search" style="margin: 4px"></i> Search</button>

					<button class="btn btn-primary  btn-sm " style=" margin-left: 5px" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share"></i> Export</button>
					<img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
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

					<table id="customers" style="margin-top: 6px">
						<thead>
						<tr style="background-color: #F0F8FF">
							<th><a href="#">#</a></th>
							<th><a href="#"> ID</a></th>
							<th><a href="#"> Customer Name</a></th>
							<th><a href="#"> Address</a></th>
							<th><a href="#"> Quantity</a></th>
							<th><a href="#"> Amount</a></th>
						</tr>
                       </thead>
						<tbody>
						<tr ng-repeat="list in customer_list">
                           <td>{{$index+1}}</td>
                           <td> {{list.client_id}}</td>
                           <td>{{list.fullname}}</td>
                           <td>{{list.address}}</td>
                           <td>{{list.quantity}}</td>
                           <td style="text-align: right">{{list.amount |number :2}}</td>
                        </tr>
                        <tr>
                            <th colspan="4"><a href="#">Total</a></th>
                            <th ><a href="#">{{total_quantity_sum |number :2}}</a></th>
                            <th style="text-align: right"><a href="#">{{total  |number :2}}</a></th>
                        </tr>
                        <tr>
                            <th style="text-align: center" colspan="6"><a href=""> Spacial Orders</a></th>
                        </tr>
                        <tr ng-repeat="list in spacial_order_list.spacial_order">
                            <td>{{$ist+1}}</td>
                            <td> {{list.client_id}}</td>
                            <td>{{list.fullname}}</td>
                            <td>{{list.address}}</td>
                            <td>{{list.name}}:{{list.quantity}}</td>
                            <td></td>
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
