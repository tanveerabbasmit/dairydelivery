
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/dailySaleQuantity_graph/dailySaleAmountGraph_graph-grid.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/chart_api/loader.js"></script>
<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>



<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init( <?php echo $select_date;  ?> ,<?php echo str_replace("'","&#39;",$data ); ?>,"<?php echo Yii::app()->createAbsoluteUrl('index.php/ChartGraph/base'); ?>")'>

		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>

		<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li>
					<a href="#tab_1" data-toggle="tab" aria-expanded="false">
                       Amount  Graph
					</a>
				</li>
			</ul>
			<!--  {{productList}}
              {{todayData}}-->


			<div class="tab-content">
				<div class=" active" id="tab_1" style="margin-bottom: 10px; margin: 10px">
					<input  style="float: left ; width: 25% ;margin-bottom: 10px;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="start_date" size="2">
                    <button style="float: left" type="button" ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                    <input  style="float: left ; width: 25% ;margin-bottom: 10px;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="end_date" size="2">

					<button style="margin-left: 10px;" type="button"  ng-click="get_today_graph()" class="btn btn-primary btn-sm "> <i class="fa fa-search" style="margin: 4px"></i> Search</button>

					<img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">


                    <div style="margin-top: 12px">
                        <div id="columnchart_values" style="width: 100%; height: 400px;"></div>
                    </div>


				</div>
			</div>

			<!--Company Limit Model-->



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
