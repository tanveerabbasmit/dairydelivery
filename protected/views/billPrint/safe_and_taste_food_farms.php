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
                padding-top: 11px;
                padding-bottom: 11px;
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
                <div   style="page-break-after:always ; width: 100%;">

                    <div   style="width: 100%;">

                        <div style="width: 50%;float: left">
                            <div style="text-align: center">
                            <span style="margin-bottom : 1px;text-align: center; font-family: Arial">
                                    <span style="font-weight: bold;">
                                        Safe & Taste Food Farms Pvt.LTd Lahore
                                    </span>
                            </span>
                            </div>

                            <div style="text-align: center">

                            <div style="text-align: center"> <span style="margin-bottom : 1px;text-align: center; font-family: Arial">
                                <span style="font-weight: bold;">Helpline#: </span> <span  style="font-family: Arial, Helvetica;margin-left: 10px ;font-size: 12px;">{{mainList.company_object.phone_number}}</span>
                                </span>
                            </div>
                            </div>
                        </div>

                        <div style="width: 50%;float: left">
                            <div>
                                <img style="margin-left: 46%;text-align: center;width: 70px ;height: 60px" src="<?php echo Yii::app()->theme->baseUrl; ?>/company_logo/<?php echo $company_logo  ?>" alt="" class="media-object img-circle">
                            </div>
                            <!--<div style="text-align: center"> <span style="line-height: 80%;text-align: center;font-weight: bold;font-size:20px;">{{company_name}} </></div>-->
                            <div style="text-align: center"> <span style="line-height: 80%;text-align: center;font-weight: bold;font-size:20px;">{{mainList.company_object.address}}</div>
                        </div>


                    </div>
                    <div   style="width: 100%;">

                        <div   style="width: 100%;float: left;">

                            <div style=""> <span style="margin-top: 5px; margin-bottom : 1px;text-align: center; font-family: Arial">
                                    <span style="font-weight: bold;">Customer Name : </span> <span  style="font-family: Arial, Helvetica;margin-left: 10px ;font-size: 18px;">{{mainList.clientObject.fullname}}({{mainList.clientObject.cell_no_1}})</span>
                                </span>
                            </div>
                            
                            <div style=""> <span style="margin-bottom : 1px;text-align: center; font-family: Arial">

                             <span style="font-family: Arial, Helvetica;">From <span style=" font-weight: bold;">{{startDate | date}}</span> To <span style=" font-weight: bold;">{{endDate | date}}</span></span>
                            </div>



                            <div style=""> <span style="margin-top: 5px;margin-bottom : 1px;text-align: center; font-family: Arial">
                                    <span style="font-weight: bold;">Address : </span> <span  style="font-family: Arial, Helvetica;margin-left: 10px ;font-size: 15px;">{{mainList.clientObject.address}}</span>
                                </span>
                            </div>

                            <div style=""> <span style="margin-bottom : 1px;text-align: center; font-family: Arial">
                                    <span style="font-weight: bold;">Zone : </span> <span  style="font-family: Arial, Helvetica;margin-left: 10px ;font-size: 15px;">{{mainList.clientObject.zone_name}}</span>
                                </span>
                            </div>

                            <div style=""> <span style="margin-bottom : 1px;text-align: center; font-family: Arial">
                                    <span style="font-weight: bold;">Category : </span> <span  style="font-family: Arial, Helvetica;margin-left: 10px ;font-size: 15px;">{{mainList.clientObject.category_name}}</span>
                            </div>
                            </span>

                        </div>

                    </div>


                    <div style="width: 100% ;" >

                        <div>
                            <div style="width: 100% ;clear:both;height: 0px" ></div>
                            <div style="width: 100% ;clear:both;" >
                                <div>
                                    <table   width="100%" border="1" cellpadding="3" id="customers" style=" border-collapse: collapse;" >
                                        <thead>

                                        <tr style="background-color: #F0F8FF">

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
                                                    Rate
                                                </a>

                                            </th>


                                            <th style="border: 1px solid black;font-family: Arial, Helvetica">
                                                <a style="color: black ;text-decoration: none;font-size: 10px;" href="#" ng-click="sortType = 'delivery'; sortReverse = !sortReverse">
                                                  Bill
                                                </a>
                                            </th>
                                            <th style="border: 1px solid black;font-family: Arial, Helvetica">
                                                <a style="color: black ;text-decoration: none;font-size: 10px;" href="#" ng-click="sortType = 'reciveAmount'; sortReverse = !sortReverse">
                                                 Paid

                                                </a>
                                            </th>
                                            <th style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">


                                                <a style="color: black ;text-decoration: none;" href="#" ng-click="sortType = 'balance'; sortReverse = !sortReverse">
                                                    BALANCE

                                                </a>
                                            </th>
                                        </tr>
                                        </thead>

                                        <tr  ng-repeat="list in mainList.ledgerData | orderBy:sortType:!sortReverse track by $index ">
                                            <td style="border: 1px solid black;font-family: Arial, Helvetica;;font-size: 10px;width: 100px">{{list.just_date}}</td>
                                            <td style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{list.discription}}</td>
                                            <td style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{list.rate | number:0}}</td>
                                            <td style="border: 1px solid black;font-family: Arial, Helvetica ;text-align: right;font-size: 10px;">{{list.delivery | number :0}}</td>
                                            <td style="border: 1px solid black ;text-align: right;font-family: Arial, Helvetica;font-size: 10px;">{{list.reciveAmount | number:0}}</td>
                                            <td style="border: 1px solid black;text-align: right;font-family: Arial, Helvetica;font-size: 10px;">{{list.balance |number:0}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 11px;border: 1px solid black;font-family: Arial, Helvetica;;font-size: 10px;width: 100px" colspan="3">Total</td>
                                            <td style="border: 1px solid black;font-family: Arial, Helvetica;;font-size: 10px;width: 100px;text-align: right">{{mainList.total_summary_product |  number :2}}</td>
                                            <td style="border: 1px solid black;font-family: Arial, Helvetica;;font-size: 10px;width: 100px;text-align: right">{{mainList.total_paid_payment_duration_this_date |number :2}}</td>
                                            <td style="border: 1px solid black;font-family: Arial, Helvetica;;font-size: 10px;width: 100px;text-align: right"></td>
                                        </tr>

                                       <!-- <tr>
                                            <th colspan="6" style="padding-top: 15px;color: black ;border: 1px solid black;font-family: Arial, Helvetica;font-size: 11px;text-decoration: none;"><a style="color: black ;" href="#">Product Summary  </a> </th>
                                        </tr>
                                        <tr>

                                            <th colspan="4" style="text-decoration: none;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;"><a style="color: black ;text-decoration: none;" href="#">Product</a></th>
                                            <th colspan="1" style="text-decoration: none;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;"><a style="color: black ;text-decoration: none;" href="#">Quantity</a></th>
                                            <th colspan="1" style="text-decoration: none;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;"><a style="color: black ;text-decoration: none;" href="#">Amount</a></th>
                                        </tr>


                                        <tr ng-repeat="summary in mainList.sumery">

                                            <td colspan="4" style=" border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{summary.product_name }}</td>
                                            <td colspan="1" style="text-align:right ; border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{summary.deliveryQuantity_sum | number :2}}</td>
                                            <td colspan="1" style="text-align: right ; border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{summary.deliverySum |  number :2}}</td>
                                        </tr>

                                        <tr>
                                            <th colspan="5" style="text-decoration: none;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;"><a style="color: black ;text-decoration: none;" href="#">Current Bill</a></th>

                                            <td colspan="1" style="text-align: right ; border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{mainList.total_summary_product |  number :2}}</td>
                                        </tr>

                                        <tr>

                                            <th colspan="3" style="text-decoration: none;color: black;border: 1px solid black;font-family: Arial, Helvetica;;font-size: 10px;">Opening Balance</th>
                                            <td colspan="3" style="font-family: Arial, Helvetica;border: 1px solid black ; text-align: right;font-size: 13px;" >{{mainList.opening_balance_month | number :2}}</td>

                                        </tr>

                                        <tr>
                                            <th colspan="3" style="text-decoration: none;color: black;border: 1px solid black;font-family: Arial, Helvetica;;font-size: 10px;">Current Bill</th>
                                            <td colspan="3" style="font-family: Arial, Helvetica;border: 1px solid black ; text-align: right;font-size: 13px;" >{{mainList.total_summary_product |  number :2}}</td>
                                        </tr>

                                        <tr>
                                            <th colspan="3" style="text-decoration: none;color: black;border: 1px solid black;font-family: Arial, Helvetica;;font-size: 10px;">Collection/Advance</th>
                                            <td colspan="3" style="font-family: Arial, Helvetica;border: 1px solid black ; text-align: right;font-size: 13px;" >{{mainList.total_paid_payment_duration_this_date |number :2}}</td>
                                        </tr>

                                        <tr>
                                            <td colspan="3" style="text-decoration: none;color: black;border: 1px solid black;font-family: Arial, Helvetica;;font-size: 10px;">Payable Balance</td>
                                            <td style="font-family: Arial, Helvetica;border: 1px solid black ; text-align: right;font-size: 13px;" colspan="3" >{{mainList.current_balance |number : 2}}</td>
                                        </tr>-->
                                    </table>

                                    <div style="width: 50%; float: left;">
                                        <table width="100%" border="1" cellpadding="3" id="customers" style=" border-collapse: collapse;margin-right: 5px" >
                                            <tr>
                                                <th colspan="3" style="border: 1px solid black;color:black;text-align: center;text-decoration: none;font-family: Arial, Helvetica;font-size: 17px;" >
                                                    Product Summary:
                                                </th>
                                            </tr>
                                            <tr>
                                                <th  style="color:black;text-align: center;text-decoration: none;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;" >
                                                    Product
                                                </th>
                                                <th  style="color:black;text-align: center;text-decoration: none;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;" >
                                                    Quantity
                                                </th>

                                                <th  style="color:black;text-align: center;text-decoration: none;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;" >
                                                    Amount
                                                </th>
                                            </tr>
                                            <tr ng-repeat="summary in mainList.sumery">
                                                <td  style="color: black ; border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{summary.product_name }}</td>
                                                <td  style="color: black ;text-align:right ; border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{summary.deliveryQuantity_sum | number :2}}</td>
                                                <td  style="color: black ;text-align: right ; border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{summary.deliverySum |  number :2}}</td>
                                            </tr>
                                            <tr>
                                                <th style=" border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px; color: #0c0c0c" colspan="2">Total</th>
                                                <td style="text-align: right ; border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;" >{{mainList.total_summary_product |  number :2}}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div style="width: 50%; float: left;">
                                        <table width="98%" border="1" cellpadding="3" id="customers" style=" border-collapse: collapse;margin-left: 2%" >
                                            <tr>
                                                <th colspan="2" style="border: 1px solid black;color:black;text-align: center;text-decoration: none;font-family: Arial, Helvetica;font-size: 17px;" >
                                                    Bill Summary
                                                </th>
                                            </tr>
                                            <tr>
                                                <th  style="color:black;text-align: left;text-decoration: none;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;" >
                                                    Opening Balance
                                                </th>
                                                <td style="color:black;text-align: right;text-decoration: none;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;" >
                                                    {{mainList.opening_balance_month |number :2}}
                                                </td>

                                            </tr>
                                            <tr>
                                                <th  style="color:black;text-align: lrft;text-decoration: none;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;" >
                                                    Current Bill
                                                </th>
                                                <td style="color:black;text-align: right;text-decoration: none;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;" >
                                                    {{mainList.total_summary_product |  number :2}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th  style="color:black;text-align: left;text-decoration: none;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;" >
                                                    Total payable
                                                </th>
                                                <td style="color:black;text-align: right;text-decoration: none;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;" >
                                                    {{mainList.total_payable_amount | number:2}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th  style="color:black;text-align: left;text-decoration: none;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;" >
                                                    Paid
                                                </th>
                                                <td style="color:black;text-align: right;text-decoration: none;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">
                                                    {{mainList.total_paid_payment_duration_this_date | number : 2}}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th  style="color:black;text-align: left;text-decoration: none;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;" >
                                                    Payable Balance
                                                </th>
                                                <td style="color:black;text-align: right;text-decoration: none;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">
                                                    {{mainList.current_balance |number : 2}}
                                                </td>
                                            </tr>

                                        </table>
                                    </div>

                                    <table   width="100%" border="1" cellpadding="3" id="customers" style="page-break-after:always ; border-collapse: collapse;" >

                                        <tr style="border: 1px solid white;">
                                            <td colspan="6" style="border: 1px solid white;direction: rtl;text-decoration: none;color: black;font-family: Arial, Helvetica;;font-size: 13px;">
                                                <div   style="width: 100%;">
                                                    <div style="">
                                                        <span style="margin-bottom : 1px; font-family: Arial">
                                                            <span style="font-weight: bold;">

                                                                <ol style="direction: rtl">
                                                                  <li style="">ٹھنڈے دودھ کو پیکٹ کے اندر گرم  ہونے سے بچائیں۔ کیونکہ ہم نے خرابی سے بچانے کیلئے اس میں کوئی مصنوعی اجزا شامل نہیں کيے۔ لہذا دودھ موصول ہونے کے بعد ،1 گھنٹے کے اندر ابال لیں۔ اس دوران دودھ خراب ہونے کی صورت میں ہمیں اطلاع کریں۔ 1 گھنٹے کے بعد کمپنی ذمہ دار نہیں ہو گی۔</li>
                                                                  <li style="">یکم سے 15 تاریخ والے بل کو اسی ماہ کی 18 تاریخ تک، جبکہ، 16 سے 30 تاریخ والے بل کو اگلے ماہ کی 5 تاریخ تک ہر صورت جمع کروائیں تاکہ سپلائی میں تعطیل کی زحمت سے بچا جائے۔</li>
                                                                  <li style="">ہم روزانہ آپکو ڈلیوری ایس ایم ایس کرتے ہیں۔ ایس ایم ایس موصول نہ ہونے کی صورت میں اسی دن رابطہ کریں اور اگر ایس ایم ایس میں موجود لیٹرز اور موصول ہونے والے دودھ میں فرق ہو تو اسی دن اطلاع کریں۔ بعد میں کمپنی کیلئےآپ کوکلیم دینا ممکن نہ ہو گا۔</li>
                                                                </ol>
                                                            </span>
                                                        </span>
                                                    </div>
                                                    <div style="text-align: center">
                                                        <span style="margin-bottom : 1px;text-align: center; font-family: Arial">
                                                            <span style="font-weight: bold;direction: rtl">
                                                              ضروری اعلان:
                                                            </span>
                                                        </span>
                                                    </div>

                                                    <div style="text-align: center">
                                                        <span style="margin-bottom : 1px;text-align: center; font-family: Arial">
                                                            <span style="font-weight: bold;direction: rtl">
                                                              یکم ستمبر 2021 سے دودھ کی قیمت 130 روپے فی لیٹر ھو گی۔
                                                            </span>
                                                        </span>
                                                    </div>

                                                </div>
                                            </td>

                                        </tr>


                                        </tbody>
                                    </table>





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

                </div>
            </div>
        </div>
    </div>