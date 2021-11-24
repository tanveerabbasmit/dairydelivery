
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/monthlyReconcileStock/monthlyReconcileStock-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(  <?php echo $year ?>,  <?php echo $todayMonth ?> , <?php echo $lableObject ?> ,<?php echo $productList ?> ,<?php echo $todayData ?> ,"<?php echo Yii::app()->createAbsoluteUrl('dailyStock/getCurrentStock'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveDeliveryFromPortal');?>")'>

		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>

		<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li>
					<a href="#tab_1" data-toggle="tab" aria-expanded="false">
						Montly Reconcile Stock
					</a>
				</li>
			</ul>
		</div>
          <!--  {{productList}}
			{{todayData}}-->
		<div style="margin:10px;" class="col-lg-12">

			<!--<select ng-model="clientID" class="form-control" style="width: 30%; float: left" >
                <option value="">Select Customer </option>
              <option ng-repeat="list in clientList" value="{{list.client_id}}">{{list.fullname}}</option>
            </select>-->
			<div style="float: left;">

				<select class="form-control ng-pristine ng-untouched ng-valid ng-not-empty" ng-model="todayMonth">
					<option value="1">January</option>
					<option value="2">February</option>
					<option value="3">March</option>
					<option value="4">April</option>
					<option value="5">May</option>
					<option value="6">June</option>
					<option value="7">July</option>
					<option value="8">August</option>
					<option value="9">September</option>
					<option value="10">October</option>
					<option value="11">November</option>
					<option value="12">December</option>
				</select>
			</div>

			<div style="float: left;">
				<select class="form-control ng-pristine ng-untouched ng-valid ng-not-empty" ng-model="selectYear">
					<option value="2016">2016</option>
					<option value="2017">2017</option>
					<option value="2018">2018</option>
					<option value="2019">2019</option>
					<option value="2020">2020</option>
					<option value="2021">2021</option>
					<option value="2022">2022</option>
					<option value="2023">2023</option>
					<option value="2024">2024</option>
					<option value="2025">2025</option>
					<option value="2026">2026</option>
					<option value="2027">2027</option>
					<option value="2028">2028</option>
					<option value="2029">2029</option>
				</select>
			</div>
			<button class="btn btn-primary" style="float: left" ng-click="riderData3()"> <i class="fa fa-search"></i> Search</button>
			<img style="margin: 10px" ng-show="pageLoad" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
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


							<th>
								<a  href="#" ng-click="sortType = 'rider_name'; sortReverse = !sortReverse" >
								  	 Name
									<span ng-show="sortType == 'rider_name' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
									<span ng-show="sortType == 'rider_name' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
								</a>
							</th>
							<th ng-repeat="list in productList" colspan="4" style=" text-align: center">
								<a href="#"  >
									{{list.name}}
									<span ng-show="sortType == 'productName' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
									<span ng-show="sortType == 'productName' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
								</a>
							</th>
						</tr>
						<tr>

							<td></td>
							<td ng-repeat="list in lableObject" style=" text-align: center"><span ng-bind="list.quantity"></span> </td>
						</tr>
						</thead>
						<tbody>
						<tr ng-repeat="data in mainList.delvery_Record_object">

							<td style="color: {{data.color}}">{{data.rider_name}}</td>
							<td style="text-align: right" ng-repeat="product in  data.productList track by $index">{{product}} </td>
						</tr>
						<tr ng-show="totalSum">
							<td></td>
							<th colspan="4"><a href="#">Total</a> </th>
							<td style="text-align: right" ng-repeat="list in totalSum track by $index">{{list | number}}</td>
						</tr>
						<tr>

							<th><a href="#"> Total</a></th>
							<th style="text-align: right" ng-repeat="list in mainList.delvery_total track by $index"><a href="#"> {{list | number}}</a></th>

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
