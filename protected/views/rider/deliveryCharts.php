
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/deliveryCharts/deliveryCharts-grid.js"></script>
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
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo json_encode($fiveDayAgo) ?>,<?php echo $riderList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('rider/lastDeliveryTime'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('rider/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('rider/saveDeliveryFromPortal');?>")'>



			<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li>
					<a href="#tab_1" data-toggle="tab" aria-expanded="false">
						Delivery Time
					</a>
				</li>
			</ul>

			<div class="tab-content">
				<div class=" active" id="tab_1" style="margin: 10px">
					<input style="float: left ; width: 18% ;margin-bottom: 10px" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
					<button  style="float: left" type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
					<input ng-disabled="false" style="float: left ; width: 18% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
                    <!-- <select style="margin-left :10px;margin-right:10px ;float: left ; width: 18% ;margin-bottom: 10px" class="form-control input-sm" ng-model="rider_id">
                         <option value="0">Select</option>
                         <option value="list.rider_id" ng-repeat="list in riderList">{{list.fullname}}</option>
                     </select>-->
					<button type="button"  ng-click="loadData()" class="btn btn-primary btn-sm"> <i class="fa fa-search" style="margin: 5px"></i> Search</button>
                    <a style="margin-left: 5px"  href="<?php echo Yii::app()->baseUrl; ?>/rider/deliveryduration"  type="button"   class="btn btn-info btn-sm"> <i class=""></i>Delivery Time Report</a>

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
							 <th><a href="#"> </a></th>
                             <th><a href="#"></a></th>
                             <th><a href="#"></a> </th>
							 <th><a href="#"></a></th>

						</tr>
						</thead>
						<tbody>
						<tr ng-repeat="list in todayDeliveryproductList track by $index">
                             <td>{{$index + 1}}</td>
                               <td>{{list.date}}</td>
                               <td>{{list.startDelivery}}</td>
                               <td>{{list.lastDelivery}}</td>

						</tr>

     					</tbody>
					</table>
				</div>
			</div>
                 <div style="margin: 10px">

                     <div  id="chart_div" style="width: 100%; height: 500px"></div>
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
