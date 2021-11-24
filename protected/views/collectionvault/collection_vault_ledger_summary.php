<style>
	.angularjs-datetime-picker{
		z-index: 99999 !important;
	}
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/reciveBillReport/collection_vault_ledger_summary_grid.js"></script>
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
	<div ng-controller="clintManagemaent" ng-init='init(<?php echo $data ?> , "<?php echo Yii::app()->createAbsoluteUrl('index.php/collectionvault/collection_vault_ledger_report_list_data'); ?>")'>

		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>
		<ul class="nav nav-tabs nav-tabs-lg">
			<li>
				<a href="#tab_1" data-toggle="tab" aria-expanded="false">
					Vault Ledger
				</a>
			</li>
		</ul>

		<div class="row" style="margin: 10px">
			<div class="col-lg-12">
                <select ng-model="collection_vault_id" class="form-control input-sm" style="float: left ; width: 18% ;margin-right: 10px">
                    <option value="0">All Collection Vault</option>
                    <option ng-repeat="list in collection_vault_list" value="{{list.collection_vault_id}}">{{list.collection_vault_name}}</option>
                </select>



				<input style="float: left ; width: 18% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
				<button class="btn btn-info btn-sm" style="float: left"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
				<input style="width: 18% ; float: left"  class="form-control input-sm "    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
				<button class="btn btn-primary input-sm" style="float: left" ng-click="getCustomerLedgerReportFunction()"><i class="fa fa-search" style=""></i> Search</button>
				<!--<a nh-show="false" ng-disabled="false" style="margin-left: 5px" class="btn btn-primary btn-sm" href="<?php /*echo Yii::app()->createUrl('client/customerLedgerExport')*/?>?clientID={{clientID}}&startDate={{startDate}}&endDate={{endDate}}"><i class="fa fa-share " style="margin: 5px"></i> Export </a>-->
               <!-- <button class="btn btn-primary btn-sm " style="margin-left: 5px" onclick="javascript:xport.toCSV('payment_report');"> <i class="fa fa-share" style="margin: 5px"></i> Export</button>-->
				<img style="margin: 10px" ng-show="reportLoader"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">
			</div>
 		</div>
		<div class="col-lg-12" ng-show="address" style="background-color: #FFF8DC ;">
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
			#payment_report {
				border-collapse: collapse;
				width: 100%;
			}
			#payment_report td, #payment_report th {
				border: 1px solid #ddd;
				padding: 8px;
				color: black;
			}
			#payment_report tr:nth-child(even){background-color: #F8F8FF;}
			#payment_report tr:hover {background-color: #FAFAD2;}
			#payment_report th {
				padding-top: 12px;
				padding-bottom: 12px;
				text-align: left;
				color: white;
			}
		</style>



		<table  id="payment_report" style="margin-top: 6px" >
			<thead>
			<tr style="background-color: #F0F8FF">

				<th><a href="#"></a></th>
				<th><a href="#">Vault</a></th>
                <th><a href="#">Payment</a></th>
                <th><a href="#">Receipt</a></th>

				<th><a href="#">Balance</a></th>

			</tr>
			</thead>

            <tr ng-repeat="list in result">
                <td style="background-color: white">{{$index+1}}</td>
                <td style="text-align:center;background-color: white">
                    <a  href="<?php echo Yii::app()->baseUrl; ?>/collectionvault/collection_vault_ledger?collection_vault_id={{list.collection_vault_id}}&startDate={{startDate}}&endDate={{endDate}}"  type="button"   class="">
                       {{list.collection_vault_name}}
                    </a>
                </td>

                <td style="text-align:center;background-color: white">{{list.total_payment}}</td>
                <td style="text-align:center;background-color: white">{{list.total_receipt}}</td>
                <td style="text-align:center;background-color: white">{{list.balance}}</td>


            </tr>
            <tr>
                <th colspan="2"><a href="#">Total</a> </th>
                <th style="text-align:center;background-color: white"><a href=""> {{total_payment}}</a></th>
                <th style="text-align:center;background-color: white"><a href=""> {{total_receipt}}</a></th>
                <th style="text-align:center;background-color: white"><a href=""> </a></th>
            </tr>


		</table>
		<style>
			.dropdown.dropdown-scroll .dropdown-menu {
				max-height: 200px;
				width: 60px;
				overflow: auto;
			}

	      </style>

	</div>


	</div>
</div>

