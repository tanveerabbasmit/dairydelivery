
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/PosDateRangeStockRecived/vendor_bills_grid.js"></script>
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
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $data ?> ,"<?php echo Yii::app()->createAbsoluteUrl('BillFromVendor/base'); ?>")'>
			<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li>
					<a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        Vendor Bills
					</a>
				</li>
			</ul>


			<div class="tab-content">

				<div class=" active" id="tab_1" style="margin: 10px">
					<input style="float: left ;  width: 17% ;margin-bottom: 10px" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
					<button  style="float: left" type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
					<input ng-disabled="false"   style="float: left ; width: 17% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">

					<button  ng-disabled="imageLoading" type="button"  ng-click="select_shop_ledger()" class="btn btn-primary btn-sm"> <i class="fa fa-search" style="margin: 5px"></i> Search</button>
                   <!-- <div class="dropdown" style="f ;margin-left: 5px">
                        <button class="btn btn-info dropdown-toggle btn-sm" type="button" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-download-alt"></span>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li ng-click="printFunction()"><a href="#">Print</a></li>
                            <li><a href="<?php /*echo Yii::app()->baseUrl; */?>/BillFromVendor/vendor_ledger_export?startDate={{startDate}}&endDate={{endDate}}&vendor_id={{vendor_id}}">Export CSV</a></li>
                        </ul>
                    </div>-->

                    <!--<button class="btn btn-info btn-sm" style="margin-left: 5px" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share" style="margin: 5px"></i> Export</button>-->
					<img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                     <!-- {{todayDeliveryproductList}}-->
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
							 <th><a href="#"> #</a></th>

							<th ><a href="#">Vendor</a> </th>

							<th ><a href="#">Bill Amount</a></th>
						</tr>
						</thead>
						<tbody>
						<tr ng-repeat="list in list_data">
                            <td>
                                 {{$index + 1}}
                            </td>

							<td ><span ng-bind="list.vendor_name"></span> </td >
                            <td style="text-align: right"><span ng-bind="list.balance"></span> </td >
						</tr>
                        <tr>
                            <td colspan="2">Total</td>
                            <td style="text-align: right"><span ng-bind="total"></span></td>



                        </tr>

     					</tbody>
					</table>
				</div>
			</div>



            <div  ng-show="false" id="printTalbe">

                <div style="width:100% ">
                    <div style="width: 50%;float: left">
                        <div> <p style="text-align: center;font-weight: bold;font-size:20px;"> {{copany_object.company_name}}</p></div>
                        <div> <p style="text-align: center;font-weight: bold;font-size:12px;"> {{copany_object.phone_number}}</p></div>
                    </div>
                    <div style="width: 50%;float: left">
                        <div style="text-align: center;">
                            <img style="line-height: 60%;width: 50px ;height: 40px" src="<?php echo Yii::app()->theme->baseUrl; ?>/company_logo/{{copany_object.company_logo}}" alt="" class="media-object img-circle">
                        </div>
                    </div>
                </div>
                <div style="width:100%"></div>

                <div style="width: 100%;float: left"> <p style="text-align: center;font-weight: bold;font-size:16px;">Vendor Invoice</p></div>

                <div style="width:100%;float: left">
                     <span style="font-weight: bold;">Vendor :</span>
                    <span> {{selected_vendor}}</span>
                </div>
                <div style="width:100%;float: left">
                     <span style="font-weight: bold;">From :</span>
                     <span> {{startDate}}</span>

                    <span style="font-weight: bold;">To :</span>
                     <span>{{endDate}} </span>
                </div>
                <br>

                <table width="100%" style="border-collapse: collapse; border: 1px solid black;">

                    <tr>
                        <td style=" border: 1px solid black;">#</td>
                        <td style=" border: 1px solid black;">Date</td>
                        <td style=" border: 1px solid black;">Bill From Vendor</td>
                        <td style=" border: 1px solid black;">Item</td>
                        <td style=" border: 1px solid black;">Payment</td>
                        <td style=" border: 1px solid black;">Reference No.</td>
                        <td style=" border: 1px solid black;">Balance</td>

                    </tr>
                    <tr ng-repeat="regularOrderList in list_data">
                        <td style=" border: 1px solid black;">{{$index + 1}}</td>
                        <td style=" border: 1px solid black;"><span ng-bind="regularOrderList.date"></span></td>
                        <td style=" border: 1px solid black;"><span ng-bind="regularOrderList.puchase"></span></td>
                        <td style=" border: 1px solid black;"><span ng-bind="regularOrderList.item_name"></span></td>
                        <td style=" border: 1px solid black;"><span ng-bind="regularOrderList.payment"></span></td>
                        <td style=" border: 1px solid black;"><span ng-bind="regularOrderList.remarks"></span></td>
                        <td style=" border: 1px solid black;"><span ng-bind="regularOrderList.balance"></span></td>

                    </tr>
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
<script>
    $('.select2').select2();
</script>

<style>
    .select2-selection--single {
        height: 33px !important;
    }
</style>