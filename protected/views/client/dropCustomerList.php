
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/dropCustomerList/dropCustomerList-grid.js"></script>
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
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $data; ?>, <?php echo json_encode($fiveDayAgo) ?> , <?php echo $company_id ?> , <?php echo $riderList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('PaymentMaster/dropCustomerList_getData'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveDeliveryFromPortal');?>")'>



			<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li>
					<a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        Drop Customer List
					</a>
				</li>
			</ul>

			<div class="tab-content">
				<div class=" active" id="tab_1" style="margin: 10px">

                    <select ng-model="deactive_reason_id" ng-disabled="false" style="float: left ; width: 20% ;margin-bottom: 10px; margin-right: 15px" class="form-control input-sm ng-pristine ng-valid ng-not-empty ng-touched" >
                        <option value="0">All Reason</option>
                        <option value="{{list.sample_client_drop_reason_id}}" ng-repeat="list in getReasonList">{{list.reason}}</option>

                    </select>
                    <select ng-model="client_type" ng-disabled="false" style="float: left ; width: 20% ;margin-bottom: 10px; margin-right: 15px" class="form-control input-sm ng-pristine ng-valid ng-not-empty ng-touched" >
                        <option value="0">All</option>
                        <option value="1">Regular</option>
                        <option value="2">Sample</option>

                    </select>


					<input style="float: left ; width: 20% ;margin-bottom: 10px" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
					<button  style="float: left" type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
					<input ng-disabled="false" style="float: left ; width: 20% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">

					<button type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-primary btn-sm"> <i class="fa fa-search" style="margin: 5px"></i> Search</button>
					<!--<button ng-disabled="true" type="button"  ng-click="printFunction()" class="btn btn-primary btn-sm"> <i class="fa fa-print" style="margin: 5px"></i> Print</button>

					<button ng-disabled="true" class="btn btn-info btn-sm" style="margin-left: 5px" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share" style="margin: 5px"></i> Export</button>-->

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

							<th style="width: 150px"><a href="#">Drop Date</a></th>
							<th><a href="#">Name</a></th>
							<th><a href="#">Phone Number</a></th>
							<th><a href="#">Address</a></th>
							<th><a href="#">Reason</a></th>


						</tr>
						</thead>
						<tbody>
						<tr ng-repeat="regularOrderList in todayDeliveryproductList | orderBy:sortType:!sortReverse  track by $index">
                             <td>{{$index + 1}}</td>

							<td>{{regularOrderList.deactive_date}}</td >
							<td>{{regularOrderList.fullname}}</td >
							<td>{{regularOrderList.cell_no_1}}</td >
							<td>{{regularOrderList.address}}</td >
							<td>{{regularOrderList.deactive_reason}}</td >


						</tr>

     					</tbody>
					</table>
				</div>
			</div>






		</div>
	</div>
</div>



<style type="text/css">
	.angularjs-datetime-picker {
		z-index: 99999 !important;
	}
</style>
