<style>
	.angularjs-datetime-picker{
		z-index: 99999 !important;
	}
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/farmpayment/farm_ledger_grid.js"></script>
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
	<div ng-controller="clintManagemaent" ng-init='init( <?php echo $clientList ?> , "<?php echo Yii::app()->createAbsoluteUrl('index.php/farmPayment/getClientLedgherReport'); ?> " , "<?php echo Yii::app()->createAbsoluteUrl('index.php/client/oneCustomerAmountListallCustomerList');?>")'>

        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css2" rel="stylesheet" id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <!------ Include the above in your HEAD tag ---------->

        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>




		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>
		<ul class="nav nav-tabs nav-tabs-lg">
			<li>
				<a href="#tab_1" data-toggle="tab" aria-expanded="false">
					 Farm Ledger  {{responce.startDate}} --{{responce.endDate}}
				</a>
			</li>
		</ul>

		<div class="panel-body">
			<div class="col-lg-12 row">
                <div class="col-md-2">
                    <select  ng-model="farm_id" id="farm_id_value"  class="form-control select2 input-sm" style="width: 100%; height: 34px !important;" >
                        <option value="0">Select</option>
                        <option value="{{list.farm_id}}" ng-repeat="list in farm_list">{{list.farm_name}}</option>
                    </select>
                </div>
                <!--<select   class="form-control input-sm" ng-model="farm_id" >
                    <option value="0">Select Farm</option>
                     <option value="{{list.farm_id}}" ng-repeat="list in farm_list">{{list.farm_name}}</option>
                </select>-->
                <!--<img ng-show="loadClientLoader" style="margin: 15px;float: left" src="<?php /*echo Yii::app()->theme->baseUrl; */?>/images/loader-transparent.gif" alt="" class="loading">-->
                <div class="col-md-10">
                    <input style="float: left ; width: 30% ; margin-left: 1%" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
                    <button class="btn btn-info btn-sm" style="float: left"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                    <input style="width: 30% ; float: left"  class="form-control input-sm "    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
                    <button class="btn btn-primary input-sm" style="float: left" ng-click="getCustomerLedgerReportFunction()"><i class="fa fa-search" style=""></i> Search</button>


                    <div class="dropdown">
                        <button class="btn btn-info btn-sm dropdown-toggle ">
                            <span style="padding: 4px" class="glyphicon glyphicon-download-alt"></span>
                            <span class="caret"></span>
                        </button>
                        <div class="dropdown-content">
                            <li ng-click="printFunction()" ><a    href="">Print</a></li>
                            <li onclick="javascript:xport.toCSV('customers');"><a href="#">Export CSV</a></li>
                        </div>
                    </div>

                    <!--<div class="dropdown" style="float: left ;margin-left: 5px">
                        <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" >
                            <span style="padding: 4px" class="glyphicon glyphicon-download-alt"></span>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li ng-click="printFunction()" ><a    href="">Print</a></li>
                            <li onclick="javascript:xport.toCSV('customers');"><a href="#">Export CSV</a></li>
                        </ul>
                    </div>-->

                    <!--<a ng-disabled="false" style="margin-left: 5px" class="btn btn-primary btn-sm" href="<?php /*echo Yii::app()->createUrl('client/customerLedgerExport')*/?>?clientID={{clientID}}&startDate={{startDate}}&endDate={{endDate}}"><i class="fa fa-share " style="margin: 5px"></i> Export  </a>-->
                    <img style="margin: 10px" ng-show="reportLoader"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">

                </div>




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


		<table  id="customers" style="margin-top: 6px" >
			<thead>
			<tr style="background-color: #F0F8FF">
                <th><a href="#">#</a></th>
				<th><a href="#">Date</a></th>
				<th><a href="#">DESCRIPTION</a></th>
				<th><a href="#">Product Quantity</a></th>
				<th><a href="#">Rate</a></th>
				<!--<th><a href="#">Purchase Amount</a></th>-->
				<th><a href="#">Purchase Amount</a></th>

				<th><a href="#">Amount Paid</a></th>
                <th><a href="#">Reference No.</a></th>
				<th><a href="#">BALANCE</a></th>

			</tr>
			</thead>
			<tbody>

			<tr ng-repeat="list in responceData | orderBy:sortType:!sortReverse track by $index ">
               <td>{{$index + 1}}</td>
               <td>{{list.date}}</td>

               <td>{{list.discription}}</td>

               <td style="text-align: right">{{list.total_quantity}}</td>
               <td style="text-align: right">{{list.rate}}</td>
             <!--  <td>{{list.total_purchase}}</td>-->

               <!--<td>{{list.reference_number}}</td>-->
               <td style="text-align: right">{{list.stock_recived | number :2}}</td>

               <td style="text-align: right">{{list.paid_amount }}</td>
                <td style="text-align: right">{{list.reference_no}}</td>
               <td style="text-align: right">{{list.balance | number :2}}</td>
			</tr>

            <tr>
                <td></td>
                <th colspan="2"><a href="#">Total</a></th>
                <td style="text-align: right">{{totalDelivery.grad_total_quantity}}</td>
                <td></td>
                <td style="text-align: right">{{totalDelivery.grad_stock_recived}}</td>
                <td style="text-align: right">{{totalDelivery.grad_paid_amount}}</td>
                <td></td>

                <td style="text-align: right"></td>
            </tr>

			<!--<tr ng-show="showOpeningBalance">
                <th></th>
				<th colspan="2"><a href="#"> Total Delivery</a></th>
				<th><a href="">{{totalDelievry}}</a></th>

				<th colspan="2"><a href="#">Total Received</a></th>
				<th style="text-align: right"><a href="#">{{totalRecive | number :2}}</a></th>
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

            <div style="width: 100%;float: left"> <p style="text-align: center;font-weight: bold;font-size:16px;">Farm Invoice</p></div>

            <div style="width:100%;float: left">
                <span style="font-weight: bold;">Farm :</span>
                <span> {{get_farm_name}}</span>
            </div>
            <div style="width:100%;float: left">
                <span style="font-weight: bold;">From :</span>
                <span> {{startDate}}</span>

                <span style="font-weight: bold;">To :</span>
                <span>{{endDate}} </span>
            </div>

            <div style="width:100%;float: left">
                <span style="font-weight: bold;">Contact #:</span>
                <span> {{phone_number}} </span>


            </div>
            <br>

            <table width="100%" style="border-collapse: collapse; border: 1px solid black;">

                <tr>
                    <td style=" border: 1px solid black;">#</td>
                    <td style=" border: 1px solid black;">Date </td>
                    <td style=" border: 1px solid black;">DESCRIPTION</td>
                    <td style=" border: 1px solid black;">Product Quantity</td>
                    <td style=" border: 1px solid black;">Rate</td>
                    <td style=" border: 1px solid black;">Purchase Amount</td>
                   <td style=" border: 1px solid black;">Amount Paid</td>
                    <td style=" border: 1px solid black;">Reference No.</td>
                    <td style=" border: 1px solid black;">BALANCE</td>
                </tr>
                <tr ng-repeat="regularOrderList in responceData">
                    <td style=" border: 1px solid black;">{{$index + 1}}</td>
                    <td style=" border: 1px solid black;"><span ng-bind="regularOrderList.date"></span></td>
                    <td style=" border: 1px solid black;"><span ng-bind="regularOrderList.discription"></span></td>
                    <td style=" border: 1px solid black;"><span ng-bind="regularOrderList.total_quantity"></span></td>
                    <td style=" border: 1px solid black;text-align: right"><span ng-bind="regularOrderList.rate"></span></td>
                    <td style=" border: 1px solid black;text-align: right"><span ng-bind="regularOrderList.stock_recived"></span></td>
                    <td style=" border: 1px solid black;text-align: right"><span ng-bind="regularOrderList.paid_amount"></span></td>
                    <td style=" border: 1px solid black;"><span ng-bind="regularOrderList.reference_no"></span></td>
                    <td style=" border: 1px solid black;"><span ng-bind="regularOrderList.balance"></span></td>
                </tr>
                <tr>
                    <td style=" border: 1px solid black;"></td>
                    <th style=" border: 1px solid black;" colspan="2"><a href="#">Total</a></th>
                    <td  style=" border: 1px solid black;" style="text-align: right">{{totalDelivery.grad_total_quantity}}</td>
                    <td style=" border: 1px solid black;" ></td>
                    <td  style="border: 1px solid black;text-align: right">{{totalDelivery.grad_stock_recived}}</td>
                    <td style="text-align: right;border: 1px solid black">{{totalDelivery.grad_paid_amount}}</td>
                    <td style="border: 1px solid black;  border: 1px solid black;"></td>

                    <td style="text-align: right;border: 1px solid black"></td>
                </tr>

            </table>
        </div>

	</div>




	</div>
</div>

<style>
    .select2-selection--single {
        height: 33px !important;
    }


    <style>
     .dropbtn {

         cursor: pointer;
     }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;

        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 2px 6px !important;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {

    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropbtn {

    }
</style>

</style>
<script>
    $('.select2').select2();
</script>