<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/CustomerDeliveryReport/CustomerDeliveryReport-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $riderList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/getDialyDeliveryCustomer'); ?>")'>
        <div class="tabbable">
				<ul class="nav nav-tabs nav-tabs-lg">
					<li>
						<a href="#tab_1" data-toggle="tab" aria-expanded="false">
							RIDER Daily Delivery Report
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
						<input style="float: left ; width: 25% ;" class="form-control"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="todate" size="2">

						<select style="width: 30%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control" ng-model="selectRiderID" >
								<option value="">Select Rider</option>
								<option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}
								</option>
						</select>

						<button type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-primary "> <i class="fa fa-search"></i> Search</button>
						<img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">

						<table class="table table-striped nomargin">
							<thead>
							<tr>
								<th>
									<a href="#" ng-click="sortType = 'address'; sortReverse = !sortReverse">
										Customer Name
										<span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
								<th>
									<a href="#" ng-click="sortType = 'address'; sortReverse = !sortReverse">
										Contact No.
										<span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
								<!--<th>
									<a href="#" ng-click="sortType = 'address'; sortReverse = !sortReverse">
										Address
										<span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
								<th>
									<a href="#" ng-click="sortType = 'cell_no_1'; sortReverse = !sortReverse">
										Zone
										<span ng-show="sortType == 'cell_no_1' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'cell_no_1' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>-->
								<th>
									<a href="#" ng-click="sortType = 'productName'; sortReverse = !sortReverse">
										Product
										<span ng-show="sortType == 'productName' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'productName' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>

								<th>
									<a href="#" ng-click="sortType = 'regularQuantity'; sortReverse = !sortReverse">
										Regular
										<span ng-show="sortType == 'regularQuantity' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'regularQuantity' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>

								<th width="15%">
									<a href="#">
										One-Time
										<span ng-show="sortType == 'totalSpecialQuantity' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'totalSpecialQuantity' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
								<th>
									<a href="#">
										Delivered
										<span ng-show="sortType == 'deliveredQuantity' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'deliveredQuantity' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
							</tr>
							</thead>
							<tbody>
							 	 <tr ng-repeat="regularOrderList in todayDeliveryproductList">
									<td > <span ng-bind="regularOrderList.fullname"></span></td >
									<td><span ng-bind="regularOrderList.cell_no_1"></span></td >

									<td><span ng-bind="regularOrderList.productName"></span></td>

									 <td><span ng-bind="regularOrderList.regularQuantity"></span></td>

									 <td><span ng-bind="regularOrderList.totalSpecialQuantity"></span></td>

									 <td><span ng-bind="regularOrderList.deliveredQuantity"></span></td>

						      	 </tr>
						     </tbody>
							<!--<tbody  ng-repeat="list in todayDeliveryproductList">
								<tr ng-repeat="regularOrderList in list.spacialorder">
									<td> <span ng-bind="regularOrderList.fullname"></span></td >
									<td> <span ng-bind="regularOrderList.cell_no_1"></span></td >
									<td> <span ng-bind="regularOrderList.address"></span></td >
									<td><span ng-bind="regularOrderList.ZoneName"></span></td>
									<td><span ng-bind="regularOrderList.productName"></span></td>
									<td>Special</td>
									<td><span ng-bind="regularOrderList.quantity"></span></td>
									<td></td>

						      	 </tr>
							 </tbody>
							<tbody ng-show="deliveredQuantityShowDive">
								<tr style="background-color: 	#FFEBCD">
									<th colspan="8">Deliverd Quantity</th>
								</tr>
							</tbody>
							<tbody  ng-repeat="list in todayDeliveryproductList">

								 <tr ng-repeat="regularOrderList in list.deliveredProductResult">
									<td> <span ng-bind="regularOrderList.fullname"></span></td >
									<td> <span ng-bind="regularOrderList.cell_no_1"></span></td >
									<td> <span ng-bind="regularOrderList.address"></span></td >
									<td><span ng-bind="regularOrderList.ZoneName"></span></td>
									<td><span ng-bind="regularOrderList.productName"></span></td>
									<td>Delivered </td>
									<td><span ng-bind="regularOrderList.quantity"></span></td>
									 <td></td>
						      	 </tr>

							</tbody>-->

						</table>
					</div>
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
