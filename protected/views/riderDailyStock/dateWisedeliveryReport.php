<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/table/table_style.css">

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/dateWisedeliveryReport/dateWisedeliveryReport-grid.js"></script>


<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $selectDate; ?> ,<?php  echo str_replace("'","&#39;", $lableObject); ?>  ,<?php  echo str_replace("'","&#39;", $productList); ?> ,<?php echo str_replace("'","&#39;",$todayData); ?> , <?php echo str_replace("'","&#39;",$riderList); ?>,"<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/getDialyDeliveryCustomer_report2'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveDeliveryFromPortal');?>")'>
		<div class="tabbable">


			<div class="tab-content">
				<div class=" active" id="tab_1" style="margin-bottom: 10px;  margin: 10px">
					<input style="float: left ; width: 15% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
					<button style="float: left" type="button" ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
					<input style="float: left ; width: 15% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">


					<select ng-show="true" style="width: 18%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="product_id" >
						<option value="0">Product</option>
						<option ng-repeat="list in productList" value="{{list.product_id}}">{{list.name}}
						</option>
					</select>

                    <select ng-show="true" style="width: 18%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="selectRiderID" >
                        <option value="0">All Rider</option>
                        <option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}
                        </option>
                    </select>

					<button style="margin-left: 5px;" type="button"  ng-click="selectRiderOnChange(1)" class="btn btn-primary  btn-sm"> <i class="fa fa-search" style="margin: 4px"></i> Search</button>

					<button class="btn btn-primary  btn-sm " style=" margin-left: 5px" onclick="javascript:xport.toCSV('export');"> <i class="fa fa-share"></i> Export</button>

                    <button  ng-click="print_function_medicineusage_report_view()" style="margin-left: 5px;"  type="button" class="btn-sm btn btn-info"><span style="margin: 3px" class=" glyphicon glyphicon-print"></span></button>
					<img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">

					<table id="customers" class="table table-fixed" style="margin-top: 6px;width: 100%">
						<thead>
						<tr style="background-color: #F0F8FF">
							<th class="col-xs-1"><a href="#" style="text-align: center">#</a></th>
							<th class="col-xs-1"><a href="#" style="text-align: center">ID</a></th>
							<th class="col-xs-2"><a href="#" style="text-align: center">Customer Name</a></th>
							<th class="col-xs-2"><a href="#" style="text-align: center">Phone No.</a></th>
							<th class="col-xs-3"><a href="#" style="text-align: center">Address</a></th>
                            <th class="col-xs-1"><a href="#">Rate</a></th>
                            <th class="col-xs-1"><a href="#">Quantity</a></th>
                            <th class="col-xs-1"><a href="#">Amount</a></th>

						</tr>
                       </thead>
						<tbody>

                          <tr ng-repeat="data in todayData">
                              <td class="col-xs-1">{{$index +1}}</td>
							  <td class="col-xs-1">{{data.client_id}}</td>
							  <td class="col-xs-2">
                                  {{data.fullname}}
                                   <br>
                                  ({{data.zone_name}})

                              </td>
							  <td class="col-xs-2"> {{data.cell_no_1}}</td>
							  <td class="col-xs-3">{{data.address}}</td>

							  <td class="col-xs-1" ng-repeat="list in data.productData track by $index" style="text-align: right">{{list | number :2 }}</td>
                           </tr>
						  <tr ng-show="todayData.length>0">
							  <td class="col-xs-1"></td>
							  <td class="col-xs-1"></td>
							  <td class="col-xs-2"></td>
							  <td class="col-xs-2"></td>
							  <td class="col-xs-3"><a href="#">Total</a></td>
                              <td class="col-xs-1" style="text-align: right" ng-repeat="count in end_total_sum"><a href="#">{{count.quantity |  number :2}}</a></td>
						  </tr>
						</tbody>


					</table>


                    <table id="export" ng-show="flase">
                        <thead>
                        <tr style="background-color: #F0F8FF">
                            <th class="col-xs-1"><a href="#" style="text-align: center">#</a></th>
                            <th class="col-xs-1"><a href="#" style="text-align: center">ID</a></th>
                            <th class="col-xs-2"><a href="#" style="text-align: center">Customer Name</a></th>
                            <th class="col-xs-2"><a href="#" style="text-align: center">Phone No.</a></th>
                            <th class="col-xs-3"><a href="#" style="text-align: center">Address</a></th>
                            <th class="col-xs-1"><a href="#">Rate</a></th>
                            <th class="col-xs-1"><a href="#">Quantity</a></th>
                            <th class="col-xs-1"><a href="#">Amount</a></th>

                        </tr>
                        </thead>
                        <tbody>

                        <tr ng-repeat="data in todayData">
                            <td class="col-xs-1">{{$index +1}}</td>
                            <td class="col-xs-1">{{data.client_id}}</td>
                            <td class="col-xs-2">{{data.fullname}}</td>
                            <td class="col-xs-2"> {{data.cell_no_1}}</td>
                            <td class="col-xs-3">{{data.address}}</td>

                            <td class="col-xs-1" ng-repeat="list in data.productData track by $index" style="text-align: right">{{list | number :2 }}</td>
                        </tr>
                        <tr ng-show="todayData.length>0">
                            <td class="col-xs-1"></td>
                            <td class="col-xs-1"></td>
                            <td class="col-xs-2"></td>
                            <td class="col-xs-2"></td>
                            <td class="col-xs-3"><a href="#">Total</a></td>
                            <td class="col-xs-1" style="text-align: right" ng-repeat="count in end_total_sum"><a href="#">{{count.quantity |  number :2}}</a></td>
                        </tr>
                        </tbody>


                    </table>
				</div>
			</div>


           <!-- <table id="customers" style="margin-top: 6px;width: 100%">

                <tr style="background-color: #F0F8FF">
                    <td colspan="5" style="text-align: center"><a href="#" style="text-align: center"> Shop Sale</a></td>
                </tr>


                <tr style="background-color: #F0F8FF">
                    <td><a href="#" style="text-align: center">Sale Man</a></td>
                    <td><a href="#" style="text-align: center">Shop</a></td>
                    <td><a href="#" style="text-align: center">product</a></td>
                    <td><a href="#" style="text-align: center">Quantity</a></td>
                    <td><a href="#" style="text-align: center">Amount</a></td>
                </tr>

                <tbody>
                <tr ng-repeat="data in shop_sale_data">
                    <td>{{data.full_name}}</td>
                    <td>{{data.shop_name}}</td>
                    <td>{{data.name}}</td>
                    <td style="text-align: right"> {{data.quantity | number :2}}</td>
                    <td style="text-align: right"> {{data.total_price| number :2}}</td>
                </tr>

                <tr ng-show="shop_sale_data.length==0">
                    <td>No data found</td>

                </tr>
                </tbody>
            </table>-->


            <!-- {{shop_sale_data}}-->
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

             <div ng-show="false" id="printForm">
                 <table id="customers" style="width: 100%; border-collapse: collapse;border: 1px solid black; ">
                     <thead>
                     <tr >
                         <th style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;">#</th>
                         <th style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;">ID</th>
                         <th style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;">Customer Name</th>
                         <th style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;">Phone No.</th>
                         <th style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;">Address</th>
                         <th style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;"  ng-repeat="list in productList" colspan="3"  style="text-align: center; border-right: 2px solid black;">{{list.name}}</th>

                     </tr>
                     </thead>
                     <tbody>
                     <tr>
                         <td style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;"></td>
                         <td style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;"></td>
                         <td style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;"></td>
                         <td style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;"></td>
                         <td style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;"></td>
                         <td style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;"  ng-repeat="lable in lableObject " style="text-align: center">{{lable.quantity}}</td>
                     </tr>
                     <tr ng-repeat="data in todayData">
                         <td style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;">{{$index +1}}</td>
                         <td style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;">{{data.client_id}}</td>
                         <td style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;"> {{data.fullname}}</td>
                         <td style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;"> {{data.cell_no_1}}</td>
                         <td style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;">{{data.address}}</td>

                         <td style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;" ng-repeat="list in data.productData track by $index" style="text-align: right">{{list | number :2 }}</td>
                     </tr>
                     <tr>
                         <th style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;"></th>
                         <th style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;"></th>
                         <th style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;"></th>
                         <th style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;"></th>
                         <th style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;">Total</th>
                         <th style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;" ng-repeat="count in end_total_sum">{{count.quantity |  number :2}}</th>


                     </tr>
                     </tbody>


                 </table>
                 <table id="customers" style="margin-top: 6px;width: 100%">
                     <thead>
                     <tr style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;">
                         <th colspan="5" style="text-align: center"> Shop Sale</th>
                     </tr>
                     </thead>

                     <thead>
                     <tr style="background-color: #F0F8FF">
                         <th style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;">Sale Man</th>
                         <th style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;">Shop</th>
                         <th style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;">product</th>
                         <th style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;">Quantity</th>
                         <th style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;">Amount</th>
                     </tr>
                     </thead>
                     <tbody>
                     <tr ng-repeat="data in shop_sale_data">
                         <td style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;">{{data.full_name}}</td>
                         <td style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;">{{data.shop_name}}</td>
                         <td style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;">{{data.name}}</td>
                         <td style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;"> {{data.quantity | number :2}}</td>
                         <td style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;"> {{data.total_price| number :2}}</td>
                     </tr>

                     <tr ng-show="shop_sale_data.length==0">
                         <td style="font-size: 12px;font-family: Arial, Helvetica;border-top: 1px solid black;border-right: 0 solid white;">No data found</td>

                     </tr>
                     </tbody>
                 </table>
             </div>
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
