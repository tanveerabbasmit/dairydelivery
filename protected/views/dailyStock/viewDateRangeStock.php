


<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/viewDateRangeStock/viewDateRangeStock-grid.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $today_date; ?>  ,<?php echo $company_id ?> , <?php echo $productList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('dailyStock/saveNewStock_add'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('dailyStock/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('dailyStock/saveDeliveryFromPortal');?>")'>
		<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li>
					<a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        Add Daily Stock
					</a>
				</li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					<input style="float: left ; width: 20% ;margin-bottom: 10px" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
                    <button class="btn btn-info btn-sm" style="float: left"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                    <input style="float: left ; width: 20% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
                    <button type="button"  ng-click="selectDateRangeStock()" class="btn btn-primary btn-sm"> <i style="margin: 4px" class="fa fa-search"></i> Search</button>
                    <a style="margin-left: 20px" href="<?php echo Yii::app()->baseUrl; ?>/dailyStock/addDailyStock"  type="button"   class="btn btn-primary btn-sm"> Add Stock </a>
					<!--<select style="width: 20%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="selectRiderID" >
						<option value="0">All Rider</option>
						<option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}
						</option>
					</select>
					<button type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-primary btn-sm"> <i style="margin: 4px" class="fa fa-search"></i> Search</button>
					<a ng-show="false" class="btn btn-primary btn-sm " href="<?php /*echo Yii::app()->createUrl('riderDailyStock/exportDialyDeliveryCustomer')*/?>?date={{todate}}&riderID={{selectRiderID}}"><i style="margin: 4px" class="fa fa-share"></i> Export 20 </a>
                    <button ng-show="false" class="btn btn-info btn-sm" style="float: left; margin-left: 5px" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share"></i> Export 2</button>
                    <button class="btn btn-primary btn-sm " style="margin-left: 5px" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share" style="margin: 5px"></i> Export</button>-->
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

                    <style>

                        [data-tooltip] {
                            position: relative;
                            z-index: 2;
                            cursor: pointer;
                        }

                        /* Hide the tooltip content by default */
                        [data-tooltip]:before,
                        [data-tooltip]:after {
                            visibility: hidden;
                            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
                            filter: progid: DXImageTransform.Microsoft.Alpha(Opacity=0);
                            opacity: 0;
                            pointer-events: none;
                        }

                        /* Position tooltip above the element */
                        [data-tooltip]:before {
                            position: absolute;
                            bottom: 150%;
                            left: 50%;
                            margin-bottom: 5px;
                            margin-left: -80px;
                            padding: 7px;
                            width: 160px;
                            -webkit-border-radius: 3px;
                            -moz-border-radius: 3px;
                            border-radius: 3px;
                            background-color: #000;
                            background-color: hsla(0, 0%, 20%, 0.9);
                            color: #fff;
                            content: attr(data-tooltip);
                            text-align: center;
                            font-size: 14px;
                            line-height: 1.2;
                        }

                        /* Triangle hack to make tooltip look like a speech bubble */
                        [data-tooltip]:after {
                            position: absolute;
                            bottom: 150%;
                            left: 50%;
                            margin-left: -5px;
                            width: 0;
                            border-top: 5px solid #000;
                            border-top: 5px solid hsla(0, 0%, 20%, 0.9);
                            border-right: 5px solid transparent;
                            border-left: 5px solid transparent;
                            content: " ";
                            font-size: 0;
                            line-height: 0;
                        }

                        /* Show tooltip content on hover */
                        [data-tooltip]:hover:before,
                        [data-tooltip]:hover:after {
                            visibility: visible;
                            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
                            filter: progid: DXImageTransform.Microsoft.Alpha(Opacity=100);
                            opacity: 1;
                        }

                    </style>

					<table id="customers">
						<thead>
						<tr>
							<th><a href="">Product</a></th>
							<th><a href="">Quantity</a></th>
							<th><a href="">Return</a></th>
							<th><a href="">Wastage</a></th>
							<th><a href="">Net Stock</a></th>

                        </tr>
						</thead>
						<tbody>
						<tr ng-repeat="List in productList">
							<td >{{List.product_name}}</td>
							<td style="text-align: right">{{List.quantity | number :2}}</td>
							<td style="text-align: right">{{List.return_quantity  | number :2}}</td>
							<td style="text-align: right"> {{List.wastage | number :2}}</td>
							<td style="text-align: right">{{List.net_stock | number :2}}</td>


						</tr>
                        <tr>
                            <th style="text-align: right"><a href="#">Total</a> </th>

                             <th style="text-align: right"><a href="#">{{total.quantity |number:2 }}</a> </th>
                             <th style="text-align: right"><a href="#">{{total.return_quantity |number:2 }}</a> </th>
                             <th style="text-align: right"><a href="#">{{total.wastage |number :2}}</a> </th>
                             <th style="text-align: right"><a href="#">{{total.net_stock |number:2 }}</a> </th>

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
