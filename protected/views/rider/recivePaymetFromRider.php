
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/recivePaymetFromRider/recivePaymetFromRider-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo str_replace("'","&#39;",$data ); ?>,"<?php echo Yii::app()->createAbsoluteUrl('rider/pickAmountByAdmin'); ?>","<?php echo Yii::app()->createAbsoluteUrl('rider/searchNewAll'); ?>")'>

		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>

		<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li>
					<a href="#tab_1" data-toggle="tab" aria-expanded="false">
						Rider reconcile  Payment
					</a>
				</li>
			</ul>
			<!--  {{productList}}
              {{todayData}}-->


			<div class="tab-content">
				<div class=" active" id="tab_1" style="margin-bottom: 10px; margin: 10px">
					<input  style="float: left ; width: 25% ;margin-bottom: 10px;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="todate" size="2">

					<select ng-show="false" style="width: 25%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="selectRiderID" >
						<option value="0">Select Rider</option>
						<option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}
						</option>
					</select>

					<button style="margin-left: 10px;" type="button"  ng-click="searchNewAllFunction()" class="btn btn-primary btn-sm "> <i class="fa fa-search" style="margin: 4px"></i> Search</button>
					<button ng-show="!updateMode" style="margin-left: 5px;" type="button"  ng-click="updatefunction()" class="btn btn-primary btn-sm "> <i class="fa fa-money" style="margin: 4px"></i> Recive</button>
					<button ng-show="updateMode" style="margin-left: 5px;" type="button"  ng-click="savePickAmount()" class="btn btn-info btn-sm "> <i class="fa fa-save" style="margin: 4px"></i> Save</button>

					<a ng-show="false" ng-disabled="true" class="btn btn-primary btn-sm" href="<?php echo Yii::app()->createUrl('#')?>"><i class="fa fa-share" style="margin: 4px"></i> Export </a>
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
							<th><a href="#"> #</a></th>

							<th>
								<a href="#" ng-click="sortType = 'fullname'; sortReverse = !sortReverse" >
									 Name
									<span ng-show="sortType == 'fullname' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
									<span ng-show="sortType == 'fullname' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
								</a>
							</th>

							<th>
								<a href="#" ng-click="sortType = 'address'; sortReverse = !sortReverse" >
								     Payment Recive
									<span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
									<span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
								</a>
							</th>

							<th>
								<a href="#" ng-click="sortType = 'zone_name'; sortReverse = !sortReverse" >
									Payment submit
									<span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
									<span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
								</a>
							</th>
							<th>
								<a href="#" ng-click="sortType = 'zone_name'; sortReverse = !sortReverse" >
									Balance
									<span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
									<span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
								</a>
							</th>

						</tr>
						</thead>
						<tbody>

						<tr ng-repeat="data in data track by $index">
							<td>{{$index+1}}</td>
							<td style="color: {{data.color}}"> {{data.fullname}}</td>
							<td style="text-align: right">{{data.recive_amount | number : 2}}</td>
							<td style="text-align: right" width="21%">
							     <span ng-show="!data.update_mode"> {{data.submit_amount | number :2}} </span>
								 <input ng-change="" ng-show="data.update_mode" type="text" ng-model="data.pick_by_admin" class="form-control">
							</td>
							<td style="text-align: right">{{data.balance | number :2}}</td>
						</tr>
						<tr>
							<th colspan="2" style="text-align: right"><a href="#">Total</a></th>
							 <th style="text-align: right"><a href="#">{{total_recive_amount | number :2}}</a> </th>
							 <th style="text-align: right"><a href="#">{{total_submit_amount | number :2 }}</a> </th>
							 <th style="text-align: right"><a href="#">{{total_balance | number}}</a> </th>
						</tr>



						</tbody>
					</table>
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
