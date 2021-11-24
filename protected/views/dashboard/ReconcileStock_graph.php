
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/saleGraph/RIderWiseDelivery-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">

	<div ng-controller="manageZone" ng-init='init(<?php echo $backgroundColor ?>  , <?php echo $customerObject ?>  , <?php echo $lable ?> ,"<?php echo Yii::app()->createAbsoluteUrl('dashboard/getCustomerData'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/editZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/deleteRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/search'); ?>")'>
		<ul class="nav nav-tabs nav-tabs-lg">
			<li>
				<a href="#tab_1" data-toggle="tab" aria-expanded="false">
					Sale Graph
				</a>
			</li>

		</ul>

		<div class="" style="margin: 10px">

			<div style ="width:90%" >
				<canvas id="bar-chart" width="800" height="450"></canvas>
			</div>

		</div>


	</div>
</div>

