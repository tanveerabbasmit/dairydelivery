<style>
	.angularjs-datetime-picker{
		z-index: 99999 !important;
	}
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/paymentType/paymentType-grid.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div class="panel row" id="testContainer" style="display: none" ng-app="clintManagemaent">
	<div ng-controller="clintManagemaent" ng-init='init(<?php echo $riderList; ?> , <?php echo $clientList ?> , "<?php echo Yii::app()->createAbsoluteUrl('expenceType/oneCustomerAmountListUpdate'); ?>" , "<?php echo Yii::app()->createAbsoluteUrl('client/oneCustomerAmountListallCustomerList'); ?> ")'>

		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>
		<ul class="nav nav-tabs nav-tabs-lg">
			<li>
				<a href="#tab_1" data-toggle="tab" aria-expanded="false">
					Payment
                </a>
			</li>
		</ul>
		<div class="panel-body">
			<div class="col-lg-12">
				<div style="float: left;">
					<button class="btn btn-default dropdown-toggle btn-sm" ng-click="showDropDownList()" type="button" id="dropdownMenu1" data-toggle="dropdown">{{SelectedCustomer}} <span  class="caret" style="margin: 9px"></span>
					</button>
					<ul style="width: 200px ;max-height: 500px ; overflow-x: hidden;overflow-y: scroll;" class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" >
						<li role="presentation">
						   <div class="input-group input-group-sm search-control"> <span class="input-group-addon">
						<input autofocus type="text" class="form-control" placeholder="Search" ng-model="query" id="serachCustomerBar">
						</div>
						</li >
						<li  role="presentation" ng-click="abcd(item)" ng-repeat='item in clientList | filter:query'> <a href="#"> {{item.fullname}} </a>
						</li>
					</ul>
				</div>
                <img ng-show="loadClientLoader" style="margin: 5px;float: left" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">


                <select style="margin-left: 20px; margin-right: 5px; float: left ; width: 18% ;" ng-model="searchPayment.payment_mode" class="form-control input-sm" required="">
                    <option value="0">All</option>
                    <option value="2">cheque</option>
                    <option value="3">Cash</option>
                    <option value="5">Bank Transaction</option>
                    <option value="6">Debit / Credit Card</option>
                </select>

                <select style="float: left ; width: 18% ;" class="form-control input-sm" ng-model="searchPayment.month">
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>


                <select style="float: left ; width: 18% ;" class="form-control input-sm"  ng-model="searchPayment.year">
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

				<button class="btn btn-primary input-sm" style="float: left" ng-click="getAllPaymentList(searchPayment)"><i class="fa fa-search" style=""></i> Search</button>
				<a ng-disabled="false" style="margin-left: 5px" class="btn btn-primary btn-sm" href="<?php echo Yii::app()->createUrl('client/customerLedgerExport')?>?clientID={{clientID}}&startDate={{startDate}}&endDate={{endDate}}"><i class="fa fa-share " style="margin: 5px"></i> Export </a>
                <button class="btn btn-primary btn-sm" style="margin-left: 5px" onclick="javascript:xport.toCSV('payment');"> <i class="fa fa-share" style="margin: 5px"></i> Export</button>
				<img style="margin: 10px" ng-show="reportLoader"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">
			</div>
 		</div>
		<div class="col-lg-12" ng-show="address" style="background-color: #FFF8DC">
			<div style="float: left">
              <span style="font-weight: bold;">Address :  </span> {{address}}
			</div>
			<div style="float: left ; margin-left: 20px">
              <span style="font-weight: bold;">Contact Number :  </span> {{cell_no_1}}
			</div>
			<div style="float: left ; margin-left: 20px">
              <span style="font-weight: bold;">Zone :  </span> {{zone_name}}
			</div>
		</div>
		<style>
			#payment {
				border-collapse: collapse;
				width: 100%;
			}
			#payment td, #payment th {
				border: 1px solid #ddd;
				padding: 8px;
				color: black;
			}
			#payment tr:nth-child(even){background-color: #F8F8FF;}
			#payment tr:hover {background-color: #FAFAD2;}
			#payment th {
				padding-top: 12px;
				padding-bottom: 12px;
				text-align: left;
				color: white;
			}
		</style>
		<table  id="payment" style="margin-top: 6px" >
			<thead>
			<tr style="background-color: #F0F8FF">
				<th>#</th>
				<th><a href="#">Customer</a></th>
				<!--<th><a href="#">Collect By </a></th>-->

				<th><a href="#">Received Date </a></th>
				<th><a href="#">REFERENCE No. </a></th>
				<th><a href="#">Received Amount </a></th>
				<th><a href="#">Mode </a></th>
				<th><a href="#">Paid For </a></th>


				<!--<th>
					<a href="#" ng-click="sortType = 'delivery'; sortReverse = !sortReverse">
						 Received Amount
						<span ng-show="sortType == 'delivery' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
						<span ng-show="sortType == 'delivery' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
					</a>
				</th>-->


                <th></th>
			</tr>
			</thead>
			<tbody>

			<tr ng-repeat="product in paymentList | orderBy:sortType:!sortReverse track by $index ">

                <td>{{$index +1}}</td>
                <td>{{product.fullname}}</td>
               <!-- <td>collect</td>-->
                <td>{{product.date}}</td>
                <td>{{product.reference_number}}</td>
                <td  style="text-align: right">{{product.amount_paid | number }}</td>
                <td>{{product.payment_mode}}</td>
                <td>{{product.bill_month_date | date:'MMMM, yyyy'}}</td>
                <td>
                    <button class="btn-xs"  ng-disabled="allow_delete[2]" ng-click="updatePayment(product ,(product.bill_month_date | date:'MM'),(product.bill_month_date | date:'yyyy') )"><i class="fa fa-edit btn btn-info btn-xs"></i></button>
                </td>
			</tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Total</td>
                <td style="text-align: right">{{total_recived | number}}</td>
                <td colspan="3"></td>
            </tr>

			<!--<tr ng-show="showOpeningBalance">
                <th></th>
				<th><a href="#"></a></th>
				<th ><a href="#"> Total Delivery</a></th>
				<th><a href="">{{totalDelievry}}</a></th>
				<th ></a></th>
				<th ><a href="#">Total Received</a></th>
				<th><a href="#">{{totalRecive}}</a></th>
			</tr>-->
			<!--<tr ng-show="acountSumery.length>0" style="background-color: #E0FFFF">
               <th><a href="#">Product Summary</a></th>
               <th ></th>
               <th ></th>
               <th ></th>
               <th ></th>
               <th ></th>
               <th ></th>
			</tr>
			<tr ng-show="acountSumery.length>0">
				<td></td>
				<td></td>
				<td></td>
				<td >Product</td>
				<td></td>
				<td >Quantity</td>
				<td>Amount</td>
			</tr>
			<tr ng-show="acountSumery.length>0" ng-repeat="product in acountSumery">
				<td colspan="4">{{product.product_name}}</td>
				<td colspan="2">{{product.deliveryQuantity_sum}} {{product.unit}}</td>
				<td>{{product.deliverySum | number}} </td>
			</tr>-->
			</tbody>
		</table>
		<style>
			.dropdown.dropdown-scroll .dropdown-menu {
				max-height: 200px;
				width: 60px;
				overflow: auto;
			}

	      </style>
        <!-- start: add new Zone -->
        <modal title="Update For Month" visible="updateForMonth">
            <form role="form" class="form-group" ">
                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Month :</span>
                    </div>
                    <div class="col-lg-8">

                        <select style="float: left  ;" class="form-control input-sm" ng-model="updateMonth">
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Year :</span>
                    </div>
                    <div class="col-lg-8">
                        <select style="float: left ;  ;" class="form-control input-sm"  ng-model="updateYear">
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
                </div>
                <div class=" form-group ">
                    <button  type="submit"  ng-click="changeFormonth(updateMonth ,updateYear)" class="btn-success  btn-sm">Save</button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form>
        </modal>
    </div>

    <!-- end: add new Zone -->

	</div>
</div>

