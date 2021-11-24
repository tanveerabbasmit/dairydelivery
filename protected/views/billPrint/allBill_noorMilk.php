<style>
	.angularjs-datetime-picker{
		z-index: 99999 !important;
	}
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/allBill/allBill_noomilk-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>
<?php $company_id = Yii::app()->user->getState('company_branch_id');
     $company= Company::model()->findByPk(intval($company_id));
    $company_name = $company['company_name'];
?>
<div class="panel row" id="testContainer" style="display: none" ng-app="clintManagemaent">
	<div ng-controller="clintManagemaent" ng-init='init(<?php echo json_encode($company_name); ?> , <?php echo $company_id ?> , <?php echo $riderList ?> , <?php echo $clientList ?> , "<?php echo Yii::app()->createAbsoluteUrl('client/getClientLedgherReport'); ?>", "<?php echo Yii::app()->createAbsoluteUrl('client/oneCustomerAmountListallCustomerList'); ?> " )'>
		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>
		<ul class="nav nav-tabs nav-tabs-lg">
			<li>
				<a href="#tab_1" data-toggle="tab" aria-expanded="false">
					 Customer Ledger
				</a>
			</li>
		</ul>
		<div style="margin: 15px">
			<div class="col-lg-12">
				<div style="float: left;" ng-show="true">
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
				<select style="width: 200px; float: left;margin-bottom: 10px; margin-left: 2px;" class="form-control input-sm" ng-change="changeRiderFunction()" ng-model="riderClientObject" >
					<option value="0">Select Rider</option>
					<option ng-repeat="list in riderList" value="{{list.cleintList}}">{{list.fullname}}
					</option>
				</select>
                <!--<select style="width: 180px; float: left;margin-bottom: 10px; margin-left: 2px;" class="form-control input-sm" ng-change="changeRiderFunction()" ng-model="payment_term_id" >
                    <option value="0">Payment Term</option>
                    <option value="{{list.payment_term_id}}" ng-repeat="list in payment_term_list"> {{list.payment_term_name}}</option>
                </select>-->

				<input style="float: left ; width: 18% ; margin-left: 1%" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
				<button class="btn btn-info btn-sm" style="float: left"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
				<input style="width: 18% ; float: left"  class="form-control input-sm "    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
				<button ng-disabled="imageLoader" class="btn btn-primary input-sm" style="float: left" ng-click="getCustomerLedgerReportFunction()"><i class="fa fa-search" style=""></i> Search</button>
				<button ng-disabled="imageLoader" class="btn btn-primary input-sm" style="float: left ; margin-left: 5px" ng-click="printFunction()"><i class="fa fa-print" style=""></i> Print</button>

				<img style="margin: 10px" ng-show="imageLoader"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">
			</div>

			<div class="col-lg-12" ng-show="showprograssBar">
				<div class="progress  ">
					<div class="progress-bar progress-bar-success" role="progressbar"  aria-valuemin="0"  style="width:{{loadPerCentage}}%">
						{{loadPerCentage | number:0}}% Complete (Load Data)
					</div>
				</div>
			</div>
 		</div>
		<div  class="col-lg-12" ng-show="address" style="background-color: #FFF8DC; margin-top: 10px">
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
		<div class="col-lg-12" ng-show="false" style="background-color: #FFF8DC ;margin-top: 10px ">
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
		<div class="col-lg-12" ng-show="true" style="margin-top: 10px ">
		</div>
		<style>
			#customers {
				border-collapse: collapse;
				width: 100%;
				margin-top: 10px;
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

			@media print
			{
				table { page-break-after:auto }
				tr    { page-break-inside:avoid; page-break-after:auto }
				td    { page-break-inside:avoid; page-break-after:auto }
				thead { display:table-header-group }
				tfoot { display:table-footer-group }
			}
			.ridge {border-style: ridge; margin-bottom: 20px}

		</style>
		<?php
		$company_id = Yii::app()->user->getState('company_branch_id');
		$company = Company::model()->findByPk(intval($company_id));
		$company_logo =  $company['company_logo'];
		$due_date =  $company['due_date'];


		$monthNum  =Date("m");
		$dateObj   = DateTime::createFromFormat('!m', $monthNum);
		$monthName = $dateObj->format('F'); // March
		$year = Date("Y");
		?>

		<div id="printTable">

			<div   class="col-lg-12 ridge" id="onepage" ng-repeat="mainList in finalOneObject">
				<div   style="page-break-after:always ; width: 100%;margin-top: 10px">

                       <!-- <div style="width: 100%">
                            <div style="margin-left: 50px;margin-right: 50px">
                                <div style="width: 60% ; float: left">
                                    <table style="background-color: white">
                                        <tr>
                                            <th style="text-align: left">
                                                <span style="font-family: Arial, Helvetica;text-align: center ;">FAX</span>
                                            </th>
                                        </tr>

                                        <tr>
                                            <th style="text-align: left">
                                                <span style="font-family: Arial, Helvetica;text-align: center ;">QUETTA:+92-81-2841111</span>
                                            </th>
                                        </tr>

                                        <tr>
                                            <th style="text-align: left">
                                                <span style="font-family: Arial, Helvetica;text-align: center ;">Lahore:+92-42-37588858</span>
                                            </th>
                                        </tr>


                                    </table>
                                </div>
                                <div style="width: 40% ; float: left">
                                    <table style="background-color: white ;float: right" >
                                        <tr>
                                            <th colspan="4" style="font-family: Arial, Helvetica;text-align: right ;border-bottom: 0px solid black">Phone</th>
                                        </tr>

                                        <tr>
                                            <th colspan="4" style="font-family: Arial, Helvetica;text-align: right ;border-bottom: 0px solid black">
                                                OFFICE:+92-81-2822651
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="4" style="font-family: Arial, Helvetica;text-align: right ;border-bottom: 0px solid black">2822652</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div style="width: 100% ; float: left ;margin-right: 60px;margin-left: 60px;margin-top: 10px">
                                <table style="background-color: white;width: 100% ;text-align: center">
                                    <tr>
                                        <td>
                                            <img style="width: 100px ;height: 90px" src="<?php /*echo Yii::app()->theme->baseUrl; */?>/company_logo/<?php /*echo $company_logo  */?>" alt="" class="media-object img-circle">
                                        </td>
                                        <td style="text-align: center">
                                            <table style="background-color: white;">
                                                <tr>
                                                    <th style="text-align: center">PAK NATIONAL DAIRIES</th>
                                                </tr>
                                                <tr>
                                                    <th style="border: solid 1px black;"></th>
                                                </tr>
                                                <tr>
                                                    <td>HEAD OFFICE-M.A JNNAH ROAD,CANTT QUETTA,PAKISTAN</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div style="margin-left: 50px;margin-right: 50px;">
                                <div style="width: 100% ; float: left">
                                    <div style="text-align: right">
                                        <span style="font-weight: bold;">Date:</span>
                                        <span><?php /*echo date("d-m-Y"); */?></span>
                                    </div>
                                </div>
                            </div>
                        </div>-->


                       <!--<div style="">
                        <table style="background-color: white;width: 100%">

                            <tr>
                                <th style="text-align: center">
                                    <span style="font-family: Arial, Helvetica;text-align: center ;">QUETTA:+92-81-2841111</span>
                                </th>
                            </tr>

                            <tr>
                                <th style="text-align: center">
                                    <span style="font-family: Arial, Helvetica;text-align: center ;">Lahore:+92-42-37588858</span>
                                </th>
                            </tr>
                        </table>
                       </div>-->


                    <div style="margin-top: 100px; margin-left: 50px;margin-right: 50px;">
                           <table style="border: 1px solid; width: 100%;background-color: white">
                               <tr style="">
                                   <th style="padding: 5px"><span >CUSTOMER NAME: </span></th>
                                   <td >{{mainList.clientObject.fullname}} </td>
                                   <th ><span sty>CUSTOMER ID:<span></th>
                                   <td>{{mainList.clientObject.client_id}}</td>

                               </tr>
                               <tr>
                                   <th style="padding: 5px"><span style="">ADDRESS:</span> </th>
                                   <td>{{mainList.clientObject.address}}</td>
                                   <th><span >BILLING PERIOD:</span> </th>
                                   <td>{{startDate |date : "dd-MM-yy" }} to {{endDate |date : "dd-MM-yy" }} </td>
                               </tr>
                               <tr ng-show="mainList.clientObject.notification_alert_allow_user==1 && mainList.company_object.notification_alert_allow==1">
                                   <th><span style="margin: 10px">Security:</span> </th>
                                   <td style="text-align: left">{{mainList.clientObject.security | number}} </td>
                               </tr>
                           </table>
                       </div>
                    <div style="margin-left: 50px;margin-right: 50px;">

                        <table border="1px" width="100%" style="margin-top:5px;border-collapse: collapse;border: 1px solid black; ">
                            <tr>
                                <th style="border: 1px solid"  height="30px" >
                                    <span style="margin: 10px">Product<span>
                                </th>
                                <th style="border: 1px solid" height="30px" >
                                    <span style="margin: 10px">Quantity(Liters)<span>
                                </th>
                                <th style="border: 1px solid" height="30px" >
                                    <span style="margin: 10px">Price/Liter<span>
                                </th>
                                <th style="border: 1px solid" height="30px">
                                </th>
                                <th style="border: 1px solid" height="30px" >
                                    <span style="margin: 10px">Total Amount <span>
                                </th>
                            </tr>
                            <tr>
                                <th rowspan="5" height="30px" style="border: 1px solid">
                                    <span style="margin: 10px">‘NOOR’ 100%PURE COW MILK<span>
                                </th>
                                <td style="text-align: right;margin:10px" ><span style="margin-top:20px">{{mainList.sumery[0].deliveryQuantity_sum  |number  :2}}</span></td>

                                <td style="text-align: right;margin:10px">{{mainList.sumery[0].deliverySum/mainList.sumery[0].deliveryQuantity_sum}} </td>
                                <td >  </td>
                                <td style="text-align: right;margin:10px">{{mainList.sumery[0].deliverySum |number :2}} </td>
                            </tr>
                             <tr>
                                 <td></td>
                                 <td></td>
                                 <td>ARREARS</td>
                                 <td style="text-align: right">{{mainList.openingTotalBalance_first}}</td>
                             </tr>
                             <tr>
                                <td></td>
                                <td></td>
                                <td>RECEIVED</td>
                                <td style="text-align: right">{{mainList.todayPaymentSum_total}}</td>
                            </tr>

                            <tr>
                                <td></td>
                                <td></td>
                                <td>BALANCE</td>
                                <td style="text-align: right">{{mainList.current_balance |number :2}}</td>
                            </tr>
                        </table>
                        <table   width="100%" border="1" cellpadding="3" id="customers" style="page-break-after:always ; border-collapse: collapse;" >
                            <thead>

                            <tr style="background-color: #F0F8FF">
                                <th style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">#</th>
                                <th style="border: 1px solid black;font-family: Arial, Helvetica;width: 50px">
                                    <a style="text-decoration: none;font-size: 10px;color: black; " href="#" ng-click="sortType = 'date'; sortReverse = !sortReverse">
                                        Date
                                    </a>
                                </th>
                                <th style="border: 1px solid black;font-family: Arial, Helvetica">

                                    <a style="color: black ;text-decoration: none;font-size: 10px;" href="#" ng-click="sortType = 'discription'; sortReverse = !sortReverse">
                                        DESCRIPTION
                                    </a>
                                </th>
                                <th style="border: 1px solid black;font-family: Arial, Helvetica">
                                    <a style="color: black ;text-decoration: none;font-size: 10px;" href="#" ng-click="sortType = 'discription'; sortReverse = !sortReverse">
                                        Quantity
                                    </a>
                                </th>
                                <th style="border: 1px solid black;font-family: Arial, Helvetica">

                                    <a style="color: black ;text-decoration: none;font-size: 10px;" href="#" ng-click="sortType = 'reference_number'; sortReverse = !sortReverse">
                                        REFERENCE No.
                                    </a>
                                </th>
                                <th style="border: 1px solid black;font-family: Arial, Helvetica">
                                    <a style="color: black ;text-decoration: none;font-size: 10px;" href="#" ng-click="sortType = 'delivery'; sortReverse = !sortReverse">
                                        DELIVERY
                                    </a>
                                </th>
                                <th style="border: 1px solid black;font-family: Arial, Helvetica">
                                    <a style="color: black ;text-decoration: none;font-size: 10px;" href="#" ng-click="sortType = 'reciveAmount'; sortReverse = !sortReverse">
                                        RECEIVED

                                    </a>
                                </th>
                                <th style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">


                                    <a style="color: black ;text-decoration: none;" href="#" ng-click="sortType = 'balance'; sortReverse = !sortReverse">
                                        BALANCE

                                    </a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr  ng-repeat="list in mainList.ledgerData | orderBy:sortType:!sortReverse track by $index ">
                                <td style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{$index + 1}}</td>
                                <td style="border: 1px solid black;font-family: Arial, Helvetica;;font-size: 10px;width: 100px">{{changeDateFormate(list.date)}}</td>
                                <td style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{list.discription}}</td>
                                <td style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{list.rate | number :2}}</td>
                                <td style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{list.reference_number}}</td>
                                <td style="border: 1px solid black;font-family: Arial, Helvetica ;text-align: right;font-size: 10px;">{{list.delivery | number :2}}</td>
                                <td style="border: 1px solid black ;text-align: right;font-family: Arial, Helvetica;font-size: 10px;">{{list.reciveAmount | number : 2}}</td>
                                <td style="border: 1px solid black;text-align: right;font-family: Arial, Helvetica;font-size: 10px;">{{list.balance |number : 2}}</td>
                            </tr>


                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
		</div>

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

