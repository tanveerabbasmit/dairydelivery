
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/addBrokenBottle/addBrokenBottle-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $company_id ?> , <?php echo $riderList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('user/getCustomer'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('user/saveBottleFromPortal');?>")'>
		<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li>
					<a href="#tab_1" data-toggle="tab" aria-expanded="false">
						Add Broken Bottle
					</a>
				</li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					<input ng-show="false" style="float: left ; width: 20% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="todate" size="2">

					<select style="width: 20%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="selectRiderID" >
						<option value="">Select Rider</option>
						<option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}
						</option>
					</select>

					<button type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-primary btn-sm"> <i style="margin: 4px" class="fa fa-search"></i> Search</button>


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

					<table id="customers">
						<thead>
						<tr>
							<th><a href="">ID</a></th>
							<th>
								<a href="#" ng-click="sortType = 'fullname'; sortReverse = !sortReverse">
									Customer Name
									<span ng-show="sortType == 'fullname' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
									<span ng-show="sortType == 'fullname' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
								</a>
							</th>
							<th>
								<a href="#" ng-click="sortType = 'zone_name'; sortReverse = !sortReverse">
									 Zone
									<span ng-show="sortType == 'fullname' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
									<span ng-show="sortType == 'fullname' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
								</a>
							</th>

							<th>
								<a href="#" ng-click="sortType = 'address'; sortReverse = !sortReverse">
									Address
									<span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
									<span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
								</a>
							</th>

							<th>
								<a href="#" ng-click="sortType = 'productName'; sortReverse = !sortReverse">
									Broken
									<span ng-show="sortType == 'productName' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
									<span ng-show="sortType == 'productName' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
								</a>
							</th>

							<th>
								<a href="#" ng-click="sortType = 'productName'; sortReverse = !sortReverse">
									Perfect
									<span ng-show="sortType == 'productName' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
									<span ng-show="sortType == 'productName' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
								</a>
							</th>




							<th>
								<a href="#">
									Action
								</a>
							</th>
						</tr>
						</thead>
						<tbody>
						<tr ng-repeat="regularOrderList in todayDeliveryproductList | orderBy:sortType:!sortReverse track by $index">
							<td>{{regularOrderList.client_id}}</td>
							<td > <span ng-bind="regularOrderList.fullname"></span>
								<br> {{regularOrderList.cell_no_1}}</td >
							<td><span ng-bind="regularOrderList.zone_name"></span></td>
							<td><span ng-bind="regularOrderList.address"></span></td>
							<td>
								<input type="text"  ng-model="regularOrderList.bottle" style="width: 110px">
							</td>
							<td>
								<input type="text"  ng-model="regularOrderList.perfect" style="width: 110px">
							</td>
							<td>
								<button   href="" ng-click="SaveDelivery(regularOrderList)"  class = "btn btn-success btn-sm" title="Save"><i class="fa fa-save"></i></button>
						    	<img ng-show="regularOrderList.makeDeliveryLoader" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
							</td>

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
