
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/shopstock/shop_issue_stock_grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $data ?> ,"<?php echo Yii::app()->createAbsoluteUrl('Shopstock/base'); ?>")'>

		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>

		<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li>
					<a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        Shop Issue Stock
					</a>
				</li>
			</ul>


			<div class="tab-content">
				<div class=" active" id="tab_1" style="margin-bottom: 10px; margin: 10px">

                    <ul class="breadcrumb">
                        <li><a href="<?php echo Yii::app()->baseUrl; ?>/PosShop/crudPosShop">Pos Shop</a></li>
                        <li><a href="<?php echo Yii::app()->baseUrl; ?>/pos/pointOfSale">Sale Form</a></li>
                        <li><a href="<?php echo Yii::app()->baseUrl; ?>/PosStockReceived/PosStockTransfered">Stock Transfer To Shop</a></li>
                        <li><a href="<?php echo Yii::app()->baseUrl; ?>/pos/PosDateRang">Daily sales summary</a></li>
                    </ul>


					<input  style="float: left ; width: 20% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="today_date" size="2">



					<select ng-show="true" style="width: 20%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="pos_shop_id" >
						<option value="0">Select Shop</option>
						<option ng-repeat="list in shop_list" value="{{list.pos_shop_id}}">{{list.shop_name}}</option>
					</select>

					<!--<button style="margin-left: 10px;" type="button"  ng-click="selectRiderOnChange(todate)" class="btn btn-primary btn-sm "> <i class="fa fa-search" style="margin: 4px"></i> Search</button>

					<a ng-disabled="true" class="btn btn-primary btn-sm" href="<?php /*echo Yii::app()->createUrl('#')*/?>"><i class="fa fa-share" style="margin: 4px"></i> Export </a>
					<img ng-show="imageLoading" src="<?php /*echo Yii::app()->theme->baseUrl; */?>/images/loader-transparent.gif" alt="" class="loading">
-->
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

                     {{product_list}}
					<table id="customers" style="margin-top: 6px">
						<thead>
						<tr style="background-color: #F0F8FF">
							<th><a href="#">#id</a></th>
							<th><a href="#">Product Name</a></th>

							<th ><a href="#">Quantity</a></th>


						</tr>
						</thead>
						<tbody>

                        <tr ng-repeat="list in product_list">
							  <td>{{list.product_id}}</td>
							  <td>{{list.name}}</td>
							  <td style="width: 30%">
                                  <input type="text" class="form-control">
                                  {{list.quantity}}
                              </td>
						  </tr>

                        <tr>
                            <td colspan="2"></td>
                            <td>
                                <button style="margin-left: 10px;float: right" type="button"  ng-click="save_stock()" class="btn btn-primary btn-sm ">  Save</button>
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
