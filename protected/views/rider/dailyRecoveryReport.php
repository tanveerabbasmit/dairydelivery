
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/table/table_style.css">

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/dailyRecoveryReport/dailyRecoveryReport-grid.js"></script>
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
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $rider_user_list ?> ,<?php echo $company_id ?> , <?php echo $riderList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('rider/getDialyRecovery'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('rider/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('rider/saveDeliveryFromPortal');?>")'>
		<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li style="padding: 5px">

						Daily Recovery Report

				</li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					<input style="float: left ; width: 11% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
                    <button style="float: left" type="button" ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
					<input style="float: left ; width: 11% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
                  <!--  <button class="btn btn-info btn-sm" style="float: left"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                    <input style="float: left ; width: 20% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">-->
					<select ng-change="rider_name_by_id_function(selectRiderID)" style="width: 11%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="selectRiderID" >
						<option value="0">All Rider</option>
						<option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}
						</option>
					</select>

                    <select style="width: 11%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="payment_mode" >
                        <option value="0">All Mode</option>
                        <option value="2">cheque</option>
                        <option value="3">Cash</option>
                        <option value="5">Bank Transaction</option>
                        <option value="6">Card Transaction</option>
					</select>

                    <select style="width: 11%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="payment_type" >
                          <option value="0">Payment </option>
                          <option value="1">Bad Debt</option>
                    </select>


                    <select style="width: 11%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="enter_by" >
                        <option value="">Enter by All</option>
                        <option value="{{list}}" ng-repeat="list in rider_user_list track by $index">{{list}}</option>
                    </select>



					<button type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-primary btn-sm"> <i style="margin: 4px" class="fa fa-search"></i> Search</button>

					<!--<a ng-show="false" class="btn btn-primary btn-sm " href="<?php /*echo Yii::app()->createUrl('riderDailyStock/exportDialyDeliveryCustomer')*/?>?date={{todate}}&riderID={{selectRiderID}}"><i style="margin: 4px" class="fa fa-share"></i> Export 20 </a>-->

                   <!-- <button ng-show="false" class="btn btn-info btn-sm" style="float: left; margin-left: 5px" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share"></i> Export 2</button>

                    <button class="btn btn-primary btn-sm " style="margin-left: 2px" onclick="javascript:xport.toCSV('customers');">  Export</button>-->

                    <a  style="margin-left: 5px" class="btn btn-primary btn-sm" href="<?php echo Yii::app()->createUrl('rider/getDialyRecovery_export')?>?selectRiderID={{selectRiderID}}&startDate={{startDate}}&endDate={{endDate}}&payment_mode={{payment_mode}}&enter_by={{enter_by}}&payment_type={{payment_type}}"><i class="fa fa-share " ></i> Export  </a>

                    <button class="btn btn-primary btn-sm " ng-click="printFunction()" style="margin-left: 2px">Print</button>



					<img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">



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

					<table class="table table-fixed" id="customers">
						<thead>
						<tr>
							<th style="padding: 0px;font-size: 10px;width: 4%"><a href="">#</a></th>
							<th style="padding: 0px;font-size: 10px;width: 5%"><a href="">ID</a></th>
                            <th  style="padding: 0px;font-size: 10px;width: 15%"><a href="">Customer Name</a></th>
                            <th style="padding: 0px;font-size: 10px;width: 22%"> Address</a></th>
                            <th style="padding: 0px;font-size: 10px;width: 8%"><a href="">Entered By(Rider/User)</a></th>
                            <th style="padding: 0px;font-size: 10px;width: 8%"><a href="">Date</a></th>
                            <th style="padding: 0px;font-size: 10px;width: 8%"><a href=""> Mode of payment</a></th>
                            <th style="padding: 0px;font-size: 10px;width: 10%"><a href="">Refrence No.</a></th>
                            <th style="padding: 0px;font-size: 10px;width: 8%"><a href="">Gross Amount</a></th>
                            <th style="padding: 0px;font-size: 10px;width: 5%"><a href="">Discount</a></th>
                            <th style="padding: 0px;font-size: 10px;width: 7%"><a href="">Net Amount</a></th>

                           <!-- <th style="padding: 0px;font-size: 10px;width: 4%"></th>-->
						</tr>
						</thead>
						<tbody>

						<tr ng-repeat="List in customerList  track by $index">
							<td style="width: 4%">{{$index + 1}}</td>
							<td style="width: 5%">{{List.client_id}}</td>
							<td style="width: 15%">
                                     <div style="height: 20px">
                                         <span ng-bind="List.fullname"></span><br>
                                         <span ng-bind="List.cell_no_1"></span>
                                     </div>

                            </td >
							<td style="width: 22%"><span ng-bind="List.address"></span></td>
							<td style="width: 8%">
                                <span ng-bind="List.payment_user_name"></span>
                                <span ng-bind="List.payment_rider_name"></span>
                            </td>
                            <td style="width: 8%"><span ng-bind="List.date"></span></td>
							<td style="width: 8%"><span ng-bind="List.payment_mode"></span></td>
							<td style="width: 10%"><span ng-bind="List.reference_number"></span></td>
							<td style="width: 8%"> <span ng-bind="List.amountpaid | number "></span></td>
							<td style="width: 5%">

                                    <span ng-bind="List.discount_amount "></span>

                            </td>
							<td style="text-align: center;width: 7%"> <span ng-bind="List.net_amount | number"></span></td>

                           <!-- <td style="width: 4%">
                                <span ng-bind="List.discount_list_string "></span>

                            </td>-->
						</tr>
						<tr>
							<td style="width: 4%;"></td>
							<td colspan="7" style="width: 76%"> <a href=""> Total </a></td>
							<td style="text-align: center;width: 8%" ><a href="#">{{count |number}} </a> </td>
                            <td style="text-align: center;width: 5%"><a href="#">{{totol_discount |number}}</a></td>
                            <td style="text-align: center;width: 7%"><a href="#">{{totol_net |number}}</a></td>
                          <!--  <td style="width: 4%" colspan=""></td>-->

                        </tr>
						</tbody>


					</table>
				</div>
			</div>

            <div  ng-show="true" id="printTalbe">






                <div style="width: 100%;float: left"> <p style="text-align: center;font-weight: bold;font-size:16px;">Daily Recovery Report</p></div>
                <div class="tab-pane active" id="tab_1">
                    <span style="font-weight: bold;"> Start Date : </span> {{startDate}}
                    <span style="font-weight: bold;"> End Date : </span>{{endDate}}
                    <span style="font-weight: bold;"> Rider : </span>{{full_name_rider_print}}
                    <span style="font-weight: bold;"> Payment :</span> {{payment_mode_name}}


                </div>


                <table width="100%" style="border-collapse: collapse; border: 1px solid black;">

                    <tr>
                        <td style=" border: 1px solid black;">#</td>
                        <td style=" border: 1px solid black;">ID</td>
                        <td style=" border: 1px solid black;">Customer name</td>
                        <td style=" border: 1px solid black;">Address</td>
                        <td style=" border: 1px solid black;">Entered by</td>
                        <td style=" border: 1px solid black;">Date</td>
                        <td style=" border: 1px solid black;">Mode of payment</td>
                        <td style=" border: 1px solid black;">Refrence no.</td>
                        <td style=" border: 1px solid black;">Gross amount</td>
                        <td style=" border: 1px solid black;">Discount</td>
                        <td style=" border: 1px solid black;">Net amount</td>


                    </tr>
                    <tr ng-repeat="List in customerList">
                        <td style=" border: 1px solid black;">{{$index + 1}}</td>
                        <td style=" border: 1px solid black;">{{List.client_id}}</td>
                        <td style=" border: 1px solid black;">
                            <span ng-bind="List.fullname"></span>
                            <span ng-bind="List.cell_no_1"></span>
                        </td >
                        <td style=" border: 1px solid black;"><span ng-bind="List.address"></span></td>
                        <td style=" border: 1px solid black;">
                            <span ng-bind="List.payment_user_name"></span>
                            <span ng-bind="List.payment_rider_name"></span>
                        </td>
                        <td style=" border: 1px solid black;"><span ng-bind="List.date"></span></td>
                        <td style=" border: 1px solid black;"><span ng-bind="List.payment_mode"></span></td>
                        <td style=" border: 1px solid black;"><span ng-bind="List.reference_number"></span></td>
                        <td style=" border: 1px solid black;"> <span ng-bind="List.amountpaid | number "></span></td>
                        <td style=" border: 1px solid black;">
                            <span ng-bind="List.discount_amount "></span>
                        </td>
                        <td  style=" border: 1px solid black;"> <span ng-bind="List.net_amount | number"></span></td>

                    </tr>
                    <tr>
                        <td style=" border: 1px solid black;"></td>
                        <td colspan="7" style=" border: 1px solid black;"> <a href=""> Total </a></td>
                        <td style=" border: 1px solid black;" ><a href="#">{{count |number}} </a> </td>
                        <td style=" border: 1px solid black;"><a href="#">{{totol_discount |number}}</a></td>
                        <td style=" border: 1px solid black;"><a href="#">{{totol_net |number}}</a></td>
                        <!--  <td style="width: 4%" colspan=""></td>-->

                    </tr>
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
