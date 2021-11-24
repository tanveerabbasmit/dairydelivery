
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/reconcileStock/reconcileStock-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $lableObject ?> ,<?php echo $productList ?> ,<?php echo $todayData ?> ,"<?php echo Yii::app()->createAbsoluteUrl('dailyStock/getCurrentStock'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveDeliveryFromPortal');?>")'>

		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>

		<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li>
					<a href="#tab_1" data-toggle="tab" aria-expanded="false">
						Reconcile Stock
					</a>
				</li>
			</ul>
		</div>
          <!--  {{productList}}
			{{todayData}}-->
				<div class=" active" id="tab_1" style="margin-bottom: 10px; margin: 10px">
					<input  style="float: left ; width: 25% ;margin-bottom: 10px" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="todate" size="2">

					<button ng-disabled="showProgressBar" style="margin-left: 10px;" type="button"  ng-click="SearchNewDate(todate)" class="btn btn-primary btn-sm "> <i class="fa fa-search" style="margin: 4px"></i> Search</button>

					<a ng-disabled="true" class="btn btn-primary btn-sm" href="<?php echo Yii::app()->createUrl('#')?>"><i class="fa fa-share" style="margin: 4px"></i> Export </a>
					<img ng-show="showProgressBar" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">


					<select ng-disabled="showProgressBar" style="float: right" ng-change="getPreviousData()" ng-model="pervousDate_count">
						<option style="margin: 13px" value="1">1</option>
						<option style="margin: 13px" value="2">2</option>
						<option style="margin: 13px" value="3">3</option>
						<option style="margin: 13px" value="4">4</option>
						<option style="margin: 13px" value="5">5</option>
						<option style="margin: 13px" value="6">6</option>
						<option style="margin: 13px" value="7">7</option>
						<option style="margin: 13px" value="8">8</option>
						<option style="margin: 13px" value="9">9</option>
						<option style="margin: 13px" value="10">10</option>

						<option style="margin: 13px" value="11">11</option>
						<option style="margin: 13px" value="12">12</option>
						<option style="margin: 13px" value="13">13</option>
						<option style="margin: 13px" value="14">14</option>
						<option style="margin: 13px" value="15">15</option>

						<option style="margin: 13px" value="16">16</option>
						<option style="margin: 13px" value="17">17</option>
						<option style="margin: 13px" value="18">19</option>
						<option style="margin: 13px" value="19">19</option>
						<option style="margin: 13px" value="20">20</option>

						<option style="margin: 13px" value="21">21</option>
						<option style="margin: 13px" value="22">22</option>
						<option style="margin: 13px" value="23">23</option>
						<option style="margin: 13px" value="24">24</option>
						<option style="margin: 13px" value="25">25</option>

						<option style="margin: 13px" value="26">26</option>
						<option style="margin: 13px" value="27">27</option>
						<option style="margin: 13px" value="28">28</option>
						<option style="margin: 13px" value="29">29</option>
						<option style="margin: 13px" value="30">30</option>
					</select>
					<span style="float: right ;margin:  4px" class="label label-default">Get previous Date </span>
                </div>

			   <div class="col-lg-12" >
				   <div class="progress  ">
					   <div class="progress-bar progress-bar-success" role="progressbar"  aria-valuemin="0"  style="width:{{loadPerCentage}}%">
						   {{loadPerCentage | number:0}}% Complete (Load Data)
					   </div>
				   </div>
			   </div>



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

				<div class="col-lg-12" >

					<table id="customers" style="margin-bottom: 26px; " ng-repeat="mainList in riderStockListloading">
						<thead>
						<tr style="background-color: #F0F8FF">
							<th><a href="#">Date</a></th>

							<th>
								<a  href="#" ng-click="sortType = 'rider_name'; sortReverse = !sortReverse" >
								  	 Name
									<span ng-show="sortType == 'rider_name' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
									<span ng-show="sortType == 'rider_name' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
								</a>
							</th>
							<th ng-repeat="list in productList" colspan="5" style=" text-align: center">
								<a href="#"  >
									{{list.name}}
									<span ng-show="sortType == 'productName' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
									<span ng-show="sortType == 'productName' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
								</a>
							</th>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td ng-repeat="list in lableObject" style=" text-align: center"><span ng-bind="list.quantity"></span> </td>
						</tr>
						</thead>
						<tbody>
						<tr ng-repeat="data in mainList.delvery_Record_object">
							<td>{{data.date}}</td>
							<td style="color: {{data.color}}">{{data.rider_name}}</td>
							<td style="text-align: right" ng-repeat="product in  data.productList track by $index">{{product | number :2}} </td>
						</tr>
						<tr ng-show="totalSum">
							<td></td>
							<th colspan="4"><a href="#">Total</a> </th>
							<td style="text-align: right" ng-repeat="list in totalSum track by $index">{{list | number :2}}</td>
						</tr>
						<tr>
							<th></th>
							<th><a href="#"> Total</a></th>
							<th style="text-align: right" ng-repeat="list in mainList.delvery_total track by $index"><a href="#"> {{list | number :2}}</a></th>

						</tr>
					</tbody>
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
