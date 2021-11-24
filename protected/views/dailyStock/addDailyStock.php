


<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>




<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/addDailyStock/addDailyStock-grid.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>


<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css2" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>



<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $farmlist;  ?> ,<?php echo $company_id ?> , <?php echo $productList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('dailyStock/saveNewStock_add'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('dailyStock/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('dailyStock/saveDeliveryFromPortal');?>")'>

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
                    <div class="col-md-2">
                        <input  style="margin-bottom: 10px" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
                    </div>


                    <!--<select  style="width: 20%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="farm_id" >
                        <option value="0">All Farm</option>
                        <option ng-repeat="list in farmlist" value="{{list.farm_id}}">{{list.farm_name}}
                        </option>
                    </select>-->

                    <div class="col-md-2">
                        <select  style="width: 100%" ng-model="farm_id" id="farm_id_value"  class="form-control select2 input-sm" style="width: 100%; height: 34px !important;" >
                            <option value="0">Select</option>
                            <option value="{{list.farm_id}}" ng-repeat="list in farmlist">{{list.farm_name}}</option>

                        </select>
                    </div>



                    <select  style="width: 20%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="searchproduct" >

                        <option ng-repeat="list in productList" value="{{list.name}}">{{list.name}}
                        </option>
                    </select>

                    <button ng-disabled="imageLoading" type="button"  ng-click="todayStock()" class="btn btn-primary btn-sm"> <i style="margin: 4px" class="fa fa-search"></i> Search</button>
                    <a style="margin-left: 10px" href="<?php echo Yii::app()->baseUrl; ?>/dailyStock/viewDateRangeStock"  type="button"   class="btn btn-primary btn-sm">  View Date Range Stock </a>

                    <button  type="button"  ng-click="editReturn()" class="btn btn-default btn-sm"> <i style="margin: 4px" class="fa fa-edit"></i> Edit Return</button>
                    <button  type="button"  ng-click="editWastage()" class="btn btn-default btn-sm"> <i style="margin: 4px" class="fa fa-edit"></i> Edit Wastage</button>

                    <!-- <input style="float: left ; width: 20% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">-->
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
                     <!-- {{productList}}-->

                    <div ng-show="today_production_quantity_data_laod" class="col-sm-12" style="background-color: #FAEBD7">
                            <span>
                              <span style="font-weight: 1000;  ">Production :</span>
                               <span style="margin-left:35px"> {{today_production_quantity | number :2}}</span>
                             </span>

                    </div>
                    
                    


					<table id="customers">
						<thead>
						<tr>
							<th><a href="">Product</a></th>
							<th><a href="">Purchase Rate</a></th>
							<th><a href="">Quantity</a></th>
							<th><a href="">Return</a></th>
							<th><a href="">Wastage</a></th>
							<th><a href="">Remarks</a></th>
                        </tr>
						</thead>
						<tbody>
						<tr ng-repeat="List in productList | filter:searchproduct:strict">
							<td>{{List.name}}</td>
							<td><input type="number" ng-model="List.purchase_rate" class="form-control"></td>
							<td><input type="number" class="form-control" ng-model="List.quantity"></td>
							<td><input ng-disabled="List.update_return_quantity" type="number" class="form-control" ng-model="List.return_quantity"></td>
							<td><input ng-disabled="List.update_wastage" type="number" class="form-control" ng-model="List.wastage"></td>
							<td><input type="text" class="form-control" ng-model="List.description"></td>

						</tr>
						<tr>
							<th colspan="5"> <a href="">  </a></th>
							 <th style="text-align: right">
                                 <img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">

                                 <button ng-disabled="imageLoading" type="button"  ng-click="saveStockFunction()" class="btn btn-primary btn-sm"> <i style="margin: 4px" class="fa fa-save"></i> Save</button>
                             </th>
						</tr>
						</tbody>


					</table>
				</div>
              
                

                <table  width="100%" border="1">
                    <thead>
                    <tr>
                        <th><span style="margin: 10px">Product</span></th>
                        <th><span style="margin: 10px"> Farm</span></th>
                        <th><span style="margin: 10px">Rate</span></th>
                        <th><span style="margin: 10px">Quantity</span></th>

                        <th><span style="margin: 10px">Return</span></th>
                        <th><span style="margin: 10px">Wastage</span></th>
                        <th><span style="margin: 10px">Remarks</span></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="List in TodayStockstockList.list">

                        <td><span style="margin: 10px">{{List.product_name}} </span></td>
                        <td><span style="margin: 10px">{{List.farm_name}}</span></td>
                        <td style="text-align: right">

                            <span ng-show="!List.updateMode" style="margin: 10px">{{List.purchase_rate}}</span>
                            <input ng-show="List.updateMode" ng-model="List.purchase_rate" class="">

                        </td>
                        <td style="text-align: right">
                            <span ng-show="!List.updateMode" style="margin: 10px">{{List.quantity | number:2}}</span>

                            <input ng-show="List.updateMode" ng-model="List.quantity" class="">

                        </td>
                        <td style="text-align: right">
                            <span ng-show="!List.updateMode" style="margin: 10px">{{List.return_quantity| number:2}}</span>

                            <input ng-show="List.updateMode" ng-model="List.return_quantity" class="">
                        </td>
                        <td style="text-align: right">
                            <span ng-show="!List.updateMode" style="margin: 10px">{{List.wastage| number:2}}</span>
                            <input ng-show="List.updateMode" ng-model="List.wastage" class="">
                        </td>
                        <td><span style="margin: 10px">{{List.description}}</span></td>
                        <td style="text-align: center">
                            <button style="margin: 5px"  title="Delete" type="button" ng-click="delete_daily_stock(List)" class="btn btn-primary btn-xs"><i style="margin: 2px" class="fa fa-trash "></i></button>
                            <button ng-show="!List.updateMode" style="margin: 5px"  title="Delete" type="button" ng-click="edit_watageStock(List)" class="btn btn-primary btn-xs"><i style="margin: 2px" class="fa fa-edit"></i></button>
                            <button ng-show="List.updateMode" style="margin: 5px"  title="Delete" type="button" ng-click="edit_watageStock_save(List)" class="btn btn-info btn-xs"><i style="margin: 2px" class="fa fa-save"></i></button>
                        </td>
                    </tr>
                    <tr>
                            <th colspan="3">Total</th>
                            <th style="text-align: right;margin: 5px">{{TodayStockstockList.quantity | number:2}}</th>
                            <th style="text-align: right;margin: 5px">{{TodayStockstockList.return_quantity | number:2}}</th>
                            <th style="text-align: right;margin: 5px">{{TodayStockstockList.wastage | number:2}}</th>
                            <th></th>
                            <th></th>
                    </tr>
                    </tbody>
                </table>

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

<style>
    .select2-selection--single {
        height: 33px !important;
    }
</style>
<script>
    $('.select2').select2();
</script>
