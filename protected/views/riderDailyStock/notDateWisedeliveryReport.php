
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/notDateWisedeliveryReport/notDateWisedeliveryReport-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $lableObject ?> ,<?php echo str_replace("'","&#39;",$productList ); ?> ,<?php echo str_replace("'","&#39;",$todayData ); ?> , <?php echo str_replace("'","&#39;",$riderList ); ?>,"<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/not_getDialyDeliveryCustomer_report'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveDeliveryFromPortal');?>")'>

		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>

		<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li>
					<a href="#tab_1" data-toggle="tab" aria-expanded="false">
						Not delivery Report
					</a>
				</li>
			</ul>
          <!--  {{productList}}
			{{todayData}}-->


			<div class="tab-content">
				<div class=" active" id="tab_1" style="margin-bottom: 10px; margin: 10px">
					<input  style="float: left ; width: 25% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="todate" size="2">

					<select ng-show="true" style="width: 25%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="selectRiderID" >
						<option value="0">Select Rider</option>
						<option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}
						</option>
					</select>

					<button style="margin-left: 10px;" type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-primary btn-sm "> <i class="fa fa-search" style="margin: 4px"></i> Search</button>

					<a ng-disabled="true" class="btn btn-primary btn-sm" href="<?php echo Yii::app()->createUrl('#')?>"><i class="fa fa-share" style="margin: 4px"></i> Export </a>
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
							<th><a href="#">ID</a></th>
							<th>
								<a href="#" ng-click="sortType = 'fullname'; sortReverse = !sortReverse" >
									Customer Name
									<span ng-show="sortType == 'fullname' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
									<span ng-show="sortType == 'fullname' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
								</a>
							</th>

							<th>
								<a href="#" ng-click="sortType = 'address'; sortReverse = !sortReverse" >
									Address
									<span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
									<span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
								</a>
							</th>

							<th>
								<a href="#" ng-click="sortType = 'zone_name'; sortReverse = !sortReverse" >
									 Zone
									<span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
									<span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
								</a>
							</th>
                            <th>
                                <a href="#">Reason</a>
                            </th>
							<th ng-repeat="list in productList"  >
								<a href="#" ng-click="sortType = 'productName'; sortReverse = !sortReverse">
									{{list.name}}
									<span ng-show="sortType == 'productName' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
									<span ng-show="sortType == 'productName' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
								</a>
							</th>
						</tr>
						</thead>
						<tbody>

                          <tr ng-repeat="data in todayData track by $index">
							  <td>{{$index+1}}</td>
							  <td>{{data.client.client_id}}</td>
							  <td> {{data.client.fullname}}</td>
							  <td>{{data.client.address}}</td>
							  <td>{{data.client.zone_name}}</td>
							  <td>{{data.client.reasonType_name}}</td>
							   <td style="text-align: right" ng-repeat="product in  data.product">{{product.quantity |number:2}} </td>
						  </tr>

						   <tr ng-show="totalSum">
							   <td></td>
							   <th colspan="5"><a href="#">Total</a> </th>
							   <td style="text-align: right" ng-repeat="list in totalSum track by $index">{{list | number |number:2}}</td>
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
