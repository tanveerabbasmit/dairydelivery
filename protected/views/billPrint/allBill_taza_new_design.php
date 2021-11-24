
<style>
    .angularjs-datetime-picker{
        z-index: 99999 !important;
    }
</style>

<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/table/table_style.css">
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
                <input style="width: 15% ; float: left"  class="form-control input-sm "    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
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
            <div  style="page-break-after:always ;"  class="col-lg-12 ridge" id="onepage" ng-repeat="mainList in finalOneObject">
                  <div style="width: 100%">
                      <!--margin: 0 0 0 -20px;-webkit-transform: skew(25deg); -moz-transform: skew(25deg); -o-transform: skew(25deg);-->
                      <div style=" height: auto; background-size: cover; background-repeat: no-repeat; background-position: center; flex-shrink: 0;background-image: url('<?php echo Yii::app()->theme->baseUrl; ?>/bill_new_design/images/Background_1.png'); float: left; width: 85%;height: 75px">
                          <div style="padding-left: 10%">
                              <span style="width: 100%;color: white"><?php echo date('m/d/y') ?>, <?php echo date("h:i A"); ?> </span>
                              <br>
                              <img style="width: 250px ;height: 24px"   src="<?php echo Yii::app()->theme->baseUrl; ?>/bill_new_design/images/invoice-final__new_09.png" alt="" class="loading">
                              <div style="width:250px;margin-top: 2px;border-width: thin;padding: 3px;border-color: white;border-style: solid;border-radius: 18px;">
                                  <span style=" font-family: Arial, sans-serif;color: #FFE4C4">From</span>
                                  <span style=" font-weight: bold; font-family: Arial, sans-serif;color: white">{{startDate | date}}</span>
                                  <span style="margin-left: 5px; font-family: Arial, sans-serif;color: #FFE4C4">To</span>
                                  <span style=" font-weight: bold; font-family: Arial, sans-serif;color: white">{{endDate | date}}</span>
                              </div>
                              <br>
                          </div>

                      </div>

                      <div style="float:left;background-color: white; width: 15%;height: 75px">
                          <img style="width: 100% ;height: 100%" src="<?php echo Yii::app()->theme->baseUrl; ?>/company_logo/<?php echo $company_logo  ?>" alt="" class="">
                      </div>
                  </div>
                <div style="width: 100%">

                        <div style="background-color: white;width: 50%;float: left">
                            <span style="font-weight: bold;color: black">
                                CUSTOMER INFORMATION:
                            </span><br>
                            <span style="color: black;font-family:Poppins-Italic;font-weight: bold;">
                                Name :
                            </span>
                            <span style="color: black;">
                                {{mainList.clientObject.fullname}}
                            </span>

                            <br>
                            <span style="color: black;font-family:Poppins-Italic;font-weight: bold;">
                                Address :
                            </span>
                            <span style="color: black;">
                                  {{mainList.clientObject.address}}
                            </span>

                            <br>
                            <span style="color: black;font-family:Poppins-Italic;font-weight: bold;">
                                Contact Number :
                            </span>
                            <span style="color: black;">
                                 {{mainList.clientObject.cell_no_1}}
                            </span>

                            <br>
                        </div>
                        <div  style="background-color: white;width: 35%;float: left">
                            <img style=""   src="<?php echo Yii::app()->theme->baseUrl; ?>/bill_new_design/images/invoice-final_19.png" alt="" class="loading">
                        </div>
                        <div  style="background-color: white;width: 15%;float: left">
                            <img style="height: 90px"   src="<?php echo Yii::app()->theme->baseUrl; ?>/bill_new_design/images/invoice-final_15.png" alt="" class="loading">
                        </div>

                </div>

                <div style="width: 100%">
                    <table width="100%" width="100%" border="0" cellpadding="3"  style=" border-collapse: collapse;">


                        <tr style="background-color: red;background-color: #69a744;border-top-left-radius: 25px; border-bottom-left-radius: 25px;">
                            <td style="padding: 4px;color: #323232;border-top-left-radius: 20px; border-bottom-left-radius: 20px;border-top-right-radius: 25px; border-bottom-right-radius: 25px;">
                                <span style="width: 33%;float: left">
                                    <span style="margin-left: 25px; font-family:Poppins-Italic;font-weight: bold;">
                                      Security:
                                    </span>
                                    {{mainList.clientObject.security}}
                                </span>

                                <span style="width: 33%;float: left ;text-align: center">
                                    <span style="font-family:Poppins-Italic;font-weight: bold;">
                                       Area :
                                    </span>
                                    {{mainList.clientObject.zone_name}}
                                </span>

                                <span style="width: 32%;float: left;text-align: right">

                                       <span style="font-family:Poppins-Italic;font-weight: bold;">
                                       Due Date :
                                    </span>

                                    {{mainList.due_date}}

                                </span>


                            </td>
                           <!-- <td style="padding: 5px;color: #323232;">


                            </td>-->
                           <!-- <td style="padding: 8px;color: #323232;border-top-right-radius: 25px; border-bottom-right-radius: 25px;">


                            </td>-->
                        </tr>
                    </table>
                </div>
                <div style="width: 100%">
                    <table width="100%">
                        <tr>
                            <td style="color: black;font-family:Poppins-Italic;padding: 7px ; background-color: white">
                                Please find below the detail of our Products supplied to you during
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="width: 100%;">
                    <table   width="100%" border="0" cellpadding="3"  style=" border-collapse: collapse;" >
                        <thead>
                        <tr style="background-color: #d1d2d4">
                            <th style="border-top-left-radius: 10px; border-bottom-left-radius: 10px;padding:3px 3px 3px 3px;border-left: 1px solid white;font-family: Arial, Helvetica;font-size: 10px;">
                                <a style="color: black ;text-decoration: none;font-size: 10px;" href="#" >
                                    #
                                </a>

                            </th>
                            <th style="text-align: center;padding:3px 3px 3px 3px;border-left: 1px solid white;font-family: Arial, Helvetica;width: 50px">
                                <a style="text-decoration: none;font-size: 10px;color: black; " href="#" >
                                    DATE
                                </a>
                            </th>
                            <th style="text-align: center;padding:3px 3px 3px 3px;border-left: 1px solid white;font-family: Arial, Helvetica">
                                <a style="color: black ;text-decoration: none;font-size: 10px;" href="#" >
                                    DESCRIPTION
                                </a>
                            </th>

                            <th style="text-align: center;padding:3px 3px 3px 3px;border-left: 1px solid white;font-family: Arial, Helvetica">
                                <a style="color: black ;text-decoration: none;font-size: 10px;" href="#" >
                                    RATE
                                </a>
                            </th>

                            <th style="text-align: center;padding:3px 3px 3px 3px;border-left: 1px solid white;font-family: Arial, Helvetica">
                                <a style="color: black ;text-decoration: none;font-size: 10px;" href="#" >
                                    REFERENCE NO.
                                </a>
                            </th>
                            <th style="text-align: center;padding:3px 3px 3px 3px;border-left: 1px solid white;font-family: Arial, Helvetica">
                                <a style="color: black ;text-decoration: none;font-size: 10px;" href="#" >
                                    DELIVERY
                                </a>
                            </th>
                            <th style="text-align: center;padding:3px 3px 3px 3px;border-left: 1px solid white;font-family: Arial, Helvetica">
                                <a style="color: black ;text-decoration: none;font-size: 10px;" href="#" >
                                    RECEIVED

                                </a>
                            </th>
                            <th style="border-left: 1px solid white;padding:3px;text-align: center;border-top-right-radius: 10px;border-bottom-right-radius: 10px;padding:3px 3px 3px 3px;font-family: Arial, Helvetica;font-size: 10px;">
                                <a style="font-size: 10px;border-top-right-radius: 25px; border-bottom-right-radius: 25px;color: black ;text-decoration: none;" href="#" >
                                    BALANCE
                                </a>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr style=""  ng-repeat="list in mainList.ledgerData | orderBy:sortType:!sortReverse track by $index ">
                            <td style="border-top-left-radius: 10px; border-bottom-left-radius: 10px;color: black;background-color: {{table_td_color($index)}};padding:3px 3px 3px 3px;border: 0px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{$index + 1}}</td>
                            <td style="color: black;border-left: 1px solid white;background-color: {{table_td_color($index)}};text-align: center;padding:3px 3px 3px 3px;border: 0px solid black;font-family: Arial, Helvetica;;font-size: 10px;width: 100px">{{changeDateFormate(list.date)}}</td>
                            <td style="color: black;border-left: 1px solid white;background-color: {{table_td_color($index)}};text-align: center;padding:3px 3px 3px 3px;border: 0px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{list.discription}}</td>
                            <td style="color: black;border-left: 1px solid white;background-color: {{table_td_color($index)}};text-align: center;padding:3px 3px 3px 3px;border: 0px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{list.rate | number :2}}</td>
                            <td style="color: black;border-left: 1px solid white;background-color: {{table_td_color($index)}};text-align: center;padding:3px 3px 3px 3px;border: 0px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{list.reference_number| number :2}}</td>
                            <td style="color: black;border-left: 1px solid white;background-color: {{table_td_color($index)}};padding:3px 3px 3px 3px;border: 0px solid black;font-family: Arial, Helvetica ;text-align: center;font-size: 10px;">{{list.delivery | number :2}}</td>
                            <td style="color: black;border-left: 1px solid white;background-color: {{table_td_color($index)}};padding:3px 3px 3px 3px;border: 0px solid black ;text-align: center;font-family: Arial, Helvetica;font-size: 10px;">{{list.reciveAmount | number :2}}</td>
                            <td style="color: black;border-top-right-radius: 10px;border-bottom-right-radius: 10px;border-left: 1px solid white;background-color: {{table_td_color($index)}};padding:3px 3px 3px 3px;border: 0px solid black;text-align: center;font-family: Arial, Helvetica;font-size: 10px;">{{list.balance |number :2}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div style="width: 100% ;background-color: white">
                   <div style="width: 40%;float: left;background-color: white">
                       <table style="width: 100%;background-color: white">
                           <tr style="background-color: #f1f1f3">
                               <th colspan="8" style="font-size: 11px;padding:2px 30px 2px 10px;text-align: center;color: black ;border: 0px solid black;text-decoration: none;"><a style="text-decoration:none;color: black ;" href="#">PRODUCT SUMMARY</a> </th>
                           </tr style="background-color: #f1f1f3">
                           <!--summary in mainList.sumery-->
                           <tr style="background-color: #f1f1f3">
                               <th  style="font-size: 10px;color: black;font-family: 'Poppins-Medium'; border: 0px solid black;">Product</th>
                               <td  style="color: black;padding:2px 30px 2px 10px;text-align:right ; border: 0px solid black;font-size: 10px;">{{mainList.sumery[0].product_name}}</td>
                           </tr>
                           <tr style="background-color: #f1f1f3">
                               <th  style="font-size: 10px;color: black;font-family: 'Poppins-Medium'; border: 0px solid black;">Quantity</th>
                               <td  style="color: black;padding:2px 30px 2px 10px;text-align:right ; border: 0px solid black;font-size: 10px;">{{mainList.sumery[0].deliveryQuantity_sum | number :2}}</td>
                           </tr>

                           <tr style="background-color: #f1f1f3">
                               <th  style="font-size: 10px;color: black;font-family: 'Poppins-Medium'; border: 0px solid black;">Amount</th>
                               <td  style="color: black;padding:2px 30px 2px 10px;text-align:right ; border: 0px solid black;font-family: Arial, Helvetica;font-size: 10px;color: #323232">{{mainList.sumery[0].deliverySum | number :2}}</td>
                           </tr>

                       </table>
                   </div>
                   <div style="width:60%;float: left;background-color: white">
                       <table style="width: 100%;background-color: white">

                           <tr>
                               <th  style="font-size: 10px;color: black;font-family: 'Poppins-Medium'; border: 0px solid black;">Current Month</th>
                               <td style="color: #323232">
                                   {{mainList.sumery[0].deliverySum | number :2}}
                               </td>
                           </tr>
                           <tr>
                               <th  style="font-size: 10px;color: black;font-family: 'Poppins-Medium'; border: 0px solid black;">Arrears</th>
                               <td style="color: #323232">
                                   {{mainList.arrearer |number :2}}
                               </td>
                           </tr>
                           <tr>
                               <th  style="font-size: 10px;color: black;font-family: 'Poppins-Medium'; border: 0px solid black;">Collection/Advance</th>
                               <td style="color: #323232">
                                   {{mainList.collection |number :2}}
                               </td>
                           </tr>
                           <tr>
                               <th  style="font-size: 10px;color: black;font-family: 'Poppins-Medium'; border: 0px solid black;">Total Discount</th>
                               <td style="color: #323232">
                                   {{mainList.total_discount |number : 2}}
                               </td>
                           </tr>
                           <tr>
                               <th  style="font-size: 10px;color: black;font-family: 'Poppins-Medium'; border: 0px solid black;">Total payable</th>
                               <td style="color: #323232">
                                   {{mainList.current_balance |number : 2}}
                               </td>
                           </tr>


                       </table>
                   </div>
                </div>

            </div>
        </div>
    </div>
</div>

