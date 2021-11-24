<style>
    .angularjs-datetime-picker{
        z-index: 99999 !important;
    }
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/allBill/allBill-grid.js"></script>

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
    <div ng-controller="clintManagemaent" ng-init='init(<?php echo $payment_term_list ?> ,<?php echo json_encode($company_name); ?> , <?php echo $company_id ?> , <?php echo $riderList ?> , <?php echo $clientList ?> , "<?php echo Yii::app()->createAbsoluteUrl('client/getClientLedgherReport'); ?>", "<?php echo Yii::app()->createAbsoluteUrl('client/oneCustomerAmountListallCustomerList'); ?> " )'>

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
						<input  autofocus type="text" class="form-control" placeholder="Search" ng-model="query" id="serachCustomerBar">
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

                <select style="width: 180px; float: left;margin-bottom: 10px; margin-left: 2px;" class="form-control input-sm" ng-change="changeRiderFunction()" ng-model="riderClientObject" >
                    <option value="0">Payment Term</option>
                    <option value="{{list.cleintList}}" ng-repeat="list in payment_term_list"> {{list.payment_term_name}}</option>
                </select>
                <input style="float: left ; width: 15% ; margin-left: 1%" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
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
                padding: 5px;
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
                <div   style=" ; width: 100%;">

                    <div style="width: 100% ;margin-top: 15px" >
                        <div style="width: 100% ;" >
                            <!--<div style="width: 10% ; float: left">
                                <img style="width: 50px ;height: 40px" src="<?php /*echo Yii::app()->theme->baseUrl; */?>/company_logo/<?php /*echo $company_logo  */?>" alt="" class="media-object img-circle">
                            </div>-->

                            <div style="width: 65% ; float: left">
                                <div> <p style="line-height: 60%;text-align: center;font-weight: bold;font-size:20px;margin-top: 13px">SALE INVOICE</div>
                                <table style="background-color: white">
                                    <tr>
                                        <th style="text-align: left;">
                                            <span style="font-family: Arial, Helvetica;text-align: center ;">Shop # 9, Sector AA, Phase 4 ,DHA,Lahore</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left;">
                                            <span style="font-family: Arial, Helvetica;text-align: center ;">0310-000-7235(RAEJ)</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;">
                                            <span style="font-family: Arial, Helvetica;text-align: center ;"><span style="font-weight: bold;">Date :</span>{{mainList.today_date| date : "dd.MMM.yy" }}</span>
                                        </td>
                                    </tr>
                                    <!--<tr>
                                        <td style="text-align: left;">
                                            <span style="font-family: Arial, Helvetica;text-align: center ;"><span style="font-weight: bold;">Serial No. :</span>20-Apr-19</span>
                                        </td>
                                    </tr>-->

                                </table>

                            </div>

                            <div style="width: 35% ; float: left ;text-align: right ">

                                <div style="">
                                   <img style="width: 100px ;height: 70px; float: right" src="<?php echo Yii::app()->theme->baseUrl; ?>/company_logo/<?php echo $company_logo  ?>" alt="" class="media-object img-circle">
                                </div>

                            </div>
                            <div style="width: 100% ;background-color: black;height: 2px;float: left" >

                            </div>

                        </div>

                        <div style="width: 100% ;clear:both;height: 0px" ></div>
                        <div style="width: 100% ;clear:both;" >
                            <div style="width: 100% ;">
                                <table style="background-color: white; width: 100%">
                                    <tr>
                                        <td style="font-family: Arial, Helvetica;text-align: left;padding-top: 1px;border-bottom: 1px solid #ddd;" colspan="2"><span style="font-weight: bold;"> Customer Name :</span> {{mainList.clientObject.fullname}}</td>

                                    </tr>
                                    <tr>
                                        <td style="font-family: Arial, Helvetica;text-align: left;padding-top: 10px;border-bottom: 1px solid #ddd;" colspan="2"><span style="font-weight: bold;">Address :</span><span  style="font-family: Arial, Helvetica;margin-left: 10px">{{mainList.clientObject.address}}</span> </td>
                                    </tr>

                                    <tr>
                                        <td style="font-family: Arial, Helvetica;text-align: left;padding-top: 10px;width: 100px ;border-bottom: 1px solid #ddd;"><span style="font-weight: bold;">Date From :</span></td>
                                        <td style="font-family: Arial, Helvetica;text-align: left;padding-top: 10px;border-bottom: 1px solid #ddd;">{{startDate| date : "dd.MMM.yy" }}</td>
                                    </tr>
                                    <tr>
                                        <th style="font-family: Arial, Helvetica;text-align: left;padding-top: 10px;width: 100px ;border-bottom: 1px solid #ddd;"><span style="font-weight: bold;">Date To: </span></th>
                                        <td style="font-family: Arial, Helvetica;text-align: left;padding-top: 10px;border-bottom: 1px solid #ddd;">{{endDate | date : "dd.MMM.yy" }}</td>
                                    </tr>
                                </table>

                            </div>

                        </div>
                        <div>
                             <div>
                                    <!--<div style="font-family: Arial, Helvetica;width: 100% ;clear:both;font-size: 12px;">
                                        Please find below the detail of our Products supplied to you during  <span style=" font-weight: bold;">{{startDate | date}}</span> and  <span style=" font-weight: bold;">{{endDate | date}}</span>
                                    </div>-->
                                    <!-- page-break-after:always;-->
                                    <!-- {{chek_even_odd($index)}}-->
                                    <table   width="100%" border="1" cellpadding="3" id="customers" style="border: 0px solid white;page-break-after:always;  border-collapse:collapse;">


                                        <tr style="background-color: #F0F8FF">

                                            <td style="border-bottom: 1px solid black;font-family: Arial, Helvetica;width: 50px;text-align: center">
                                                <a style="font-weight: bold;text-decoration: none;font-size: 15px;color: black; " href="#" ng-click="sortType = 'date'; sortReverse = !sortReverse">
                                                   Item
                                                </a>
                                            </td>
                                            <td style="border-bottom: 1px solid black;font-family: Arial, Helvetica;text-align: center">

                                                <a style="font-weight: bold;color: black ;text-decoration: none;font-size: 15px;" href="#" ng-click="sortType = 'discription'; sortReverse = !sortReverse">
                                                    Qaunatity
                                                </a>

                                            </td>

                                            <th style="border-bottom: 1px solid black;font-family: Arial, Helvetica;text-align: center">

                                                <a style="font-weight: bold;color: black ;text-decoration: none;font-size: 15px;" href="#" ng-click="sortType = 'discription'; sortReverse = !sortReverse">
                                                   Price
                                                </a>

                                            </th>

                                            <th style="font-weight: bold;border-bottom: 1px solid black;font-family: Arial, Helvetica;text-align: center">

                                                <a style="color: black ;text-decoration: none;font-size: 15px;" href="#" ng-click="sortType = 'reference_number'; sortReverse = !sortReverse">
                                                    Line Total
                                                </a>
                                            </th>
                                        </tr>

                                        <tbody>

                                        <tr  ng-repeat="list in mainList.ledgerData">
                                            <td style="width:300px;border: 1px solid black;font-family: Arial, Helvetica;font-size: 14px;">{{list.name}}</td>
                                            <td style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 14px; text-align: center">{{list.quantity | number:2}}</td>
                                            <td style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 14px;text-align: center">Rs. {{list.price | number:2}}</td>
                                            <td style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 14px;text-align: right">Rs. {{list.amount | number:2}}</td>
                                        </tr>
                                        <tr>
                                            <th   style="font-weight: bold;color: black;text-align: left;border: 0px solid black;font-family: Arial, Helvetica;font-size: 13px;">

                                            </th>
                                            <th   style="font-weight: bold;color: black;text-align: left;border: 0px solid black;font-family: Arial, Helvetica;font-size: 13px;">

                                            </th>
                                            <th   style="font-weight: bold;color: black;text-align: left;border: 0px solid black;font-family: Arial, Helvetica;font-size: 13px;">

                                            </th>

                                            <th   style="text-align: right;font-weight: bold;color: black;border: 0px solid black;font-family: Arial, Helvetica;font-size: 13px;">
                                                <span style="font-size: 130%;">Total Current Balance :</span>  &nbsp &nbspRs. {{mainList.total_delivery_amount | number:2 }}
                                            </th>

                                        </tr>
                                        <tr>
                                            <th   style="font-weight: bold;color: black;text-align: left;border: 0px solid black;font-family: Arial, Helvetica;font-size: 13px;">

                                            </th>
                                            <th   style="font-weight: bold;color: black;text-align: left;border: 0px solid black;font-family: Arial, Helvetica;font-size: 13px;">

                                            </th>
                                            <th   style="font-weight: bold;color: black;text-align: left;border: 0px solid black;font-family: Arial, Helvetica;font-size: 13px;">

                                            </th>

                                            <th   style="text-align: right;font-weight: bold;color: black;border: 0px solid black;font-family: Arial, Helvetica;font-size: 13px;">
                                                <span style="font-size: 130%;">Balance :</span>  &nbsp &nbspRs. {{mainList.arrearer | number:2 }}
                                            </th>

                                        </tr>

                                        <tr style="background-color: BlanchedAlmond">
                                            <th   style="font-weight: bold;color: black;text-align: left;border: 0px solid black;font-family: Arial, Helvetica;font-size: 13px;">

                                            </th>
                                            <th   style="font-weight: bold;color: black;text-align: left;border: 0px solid black;font-family: Arial, Helvetica;font-size: 13px;">

                                            </th>
                                            <th   style="font-weight: bold;color: black;text-align: left;border: 0px solid black;font-family: Arial, Helvetica;font-size: 13px;">

                                            </th>

                                            <th   style="text-align: right;font-weight: bold;color: SlateBlue;border: 0px solid black;font-family: Arial, Helvetica;font-size: 13px;">
                                                <span style="font-size: 130%;">Total Payable :</span>  &nbsp &nbspRs. {{(mainList.arrearer + mainList.total_delivery_amount) | number:2 }}
                                            </th>

                                        </tr>


                                       <!-- <tr>
                                            <th  colspan="3" style="font-weight: bold;color: black;text-align: right;border: 0px solid black;font-family: Arial, Helvetica;font-size: 10px;">
                                                Balance
                                            </th>
                                            <td style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 14px;text-align: right">Rs. {{mainList.arrearer | number:2 }}</td>
                                        </tr>
                                        <tr>
                                            <th  colspan="3" style="font-weight: bold;color: black;text-align: right;border: 0px solid black;font-family: Arial, Helvetica;font-size: 10px;">
                                                Total
                                            </th>
                                            <td style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 14px;text-align: right">Rs. {{(mainList.arrearer + mainList.total_delivery_amount) | number:2 }}</td>
                                        </tr>

                                        <tr>
                                            <th  colspan="3" style="font-weight: bold;color: black;text-align: right;border: 0px solid black;font-family: Arial, Helvetica;font-size: 10px;">
                                                Amount Recieved
                                            </th>
                                            <td style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 14px;text-align: right"></td>
                                        </tr>-->



                                        <tr style="border: 0px solid white;">
                                            <td colspan="4" style="text-align: center;border: 0px solid white;">
                                                Thank you for being our valued customer.
                                            </td>
                                        </tr>
                                        <tr style="border: 0px solid white;">
                                            <td colspan="4" style="text-align: center;border: 0px solid white;">
                                                * To be signed and filled by rider after payment received.
                                            </td>
                                        </tr>

                                        <tr style="border: 0px solid white;">
                                            <td colspan="4" style="text-align: center;border: 0px solid white;">
                                                NTN: 5322757, Bank Account: RAEJ GROUP, Faysal Bank, PK04FAYS3149301900229100
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>


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

