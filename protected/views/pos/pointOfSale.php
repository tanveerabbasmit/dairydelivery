<style>
    #snackbar {
        visibility: hidden;
        min-width: 250px;

        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 2px;
        padding: 16px;
        position: fixed;
        z-index: 1;
        left: 50%;
        top: 60px;
        font-size: 17px;
    }

    #snackbar.show {
        visibility: visible;
        -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
        animation: fadein 0.5s, fadeout 0.5s 2.5s;
    }

    @-webkit-keyframes fadein {
        from {top: 0; opacity: 0;}
        to {top: 30px; opacity: 1;}
    }

    @keyframes fadein {
        from {top: 0; opacity: 0;}
        to {top: 30px; opacity: 1;}
    }

    @-webkit-keyframes fadeout {
        from {top: 30px; opacity: 1;}
        to {top: 0; opacity: 0;}
    }

    @keyframes fadeout {
        from {top: 30px; opacity: 1;}
        to {top: 0; opacity: 0;}
    }
</style>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/pointOfSale/pointOfSale-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>

<?php $allow_delete = crudRole::getCrudrole(4); ?>

<div id="testContainer" style="display: none" class="panel row" ng-app="productGrid">
    <div ng-controller="manageProduct" ng-init='init(<?php echo $data  ?> ,<?php echo $allow_delete  ?> ,  <?php echo $productCount ?> , <?php echo $productList ?> ,"<?php echo Yii::app()->createAbsoluteUrl('pos/saveNewProduct_pos'); ?>","<?php echo Yii::app()->createAbsoluteUrl('pos/editProduct'); ?>","<?php echo Yii::app()->createAbsoluteUrl('product/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('product/searchProduct'); ?>","<?php echo Yii::app()->createAbsoluteUrl('pos/checkAlredyExistProduct'); ?>","<?php echo Yii::app()->createAbsoluteUrl('pos/nextPageForpagination'); ?>")'>

        <div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>message! </strong>
            {{taskMessage}}
        </div>


        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Point Of Sale
                </a>
            </li>

        </ul>
         <!--{{productList}}-->
        <div class="table-responsive" style="margin-top: 20px">

            <ul class="breadcrumb" style="margin-left: 10px;margin-right: 10px;">
                <li><a href="<?php echo Yii::app()->baseUrl; ?>/PosShop/crudPosShop">Pos Shop</a></li>
                <li><a href="<?php echo Yii::app()->baseUrl; ?>/pos/PosDateRang">Date Range Sale Report</a></li>
                <li><a href="<?php echo Yii::app()->baseUrl; ?>/PosStockReceived/PosStockTransfered">Stock Transfer To Shop</a></li>
            </ul>



            <div class="dataTables_length  col-md-8" id="datatable_products_length">
                <input type="text"   placeholder="Search Item " ng-change="selectProductfunction(product)" ng-model="product" id="txt_ide" list="ide" class="form-control" style="width: 35% ; float: left"/>
                <datalist id="ide">
                    <option  ng-repeat="person in productList" value="{{person.name}}"  ng-click="testFunction2()" />
                </datalist>
               <!-- <input type="text"  placeholder="Scan Barcode" class="form-control" ng-change="barcodeSearch(ItemSearchByBarcode)" ng-model="ItemSearchByBarcode" style="width: 45% ;  float: left; margin-left: 10%" >-->

                <div style="width: 35%;float: left;margin-left: 20px">
                    <input style="" type="text" class="form-control  ng-pristine ng-valid ng-empty ng-touched" ng-model="search" ng-change="changeSearchBar(search)" ng-disabled="isLoading" id="search-dealer" placeholder="Search Invice">

                </div>
                <span class="input-group-btn">
                    <button style="box-shadow: inset 0 0 0 5px #DCDCDC;float: left" class="btn btn-default" ng-disabled="isLoading" ng-click="searchInvoiceFunction(search)" type="button"><i class="fa fa-search"></i>
                </button>
                </span>



                <div class="dataTables_length  col-md-12" style="height: 18px"></div>

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

                <div class="col-md-12 row" style="margin-bottom: 10px">
                    <div style="float: left; margin-left: 10px" class="img-thumbnail" class="col-md-2" ng-repeat="product in  productList">
                        <a href="" ng-click="selectProductfunction(product.name)">
                            <div style="text-align: center">
                                <img    ng-click="change_image(product)" style="text-align: center;height: 50px ;width: 50px;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/product/{{product.image}}" alt="" class="loading">
                            </div>
                            <div style="text-align: center">
                                <h7 style="text-align: center">{{product.name}}</h7>
                            </div>
                        </a>
                    </div>
                </div>

                <table id="customers">
                    <thead>
                    <tr style="background-color: #ececec;">
                        <th>
                            <a href="#" style="font-family: Arial, Helvetica, sans-serif;">Item Name</a>
                        </th>

                        <th>
                            <a href="#" style="font-family: Arial, Helvetica, sans-serif;">Price</a>
                        </th>
                        <th>
                            <a href="#" style="font-family: Arial, Helvetica, sans-serif;"> Quantity</a>
                        </th>
                        <th>
                            <a href="#" style="font-family: Arial, Helvetica, sans-serif;"> Total</a>
                        </th>

                        <th>
                            <a href="#" style="font-family: Arial, Helvetica, sans-serif;"> Remove</a>
                        </th>

                    </tr>
                    </thead>
                    <tbody>

                    <tr ng-repeat="product in  saleValueItem">
                        <td >
                            <span style="font-size: 12px"style="font-family: Arial, Helvetica, sans-serif;" >{{product.name}} </span>
                        </td>

                        <td >
                            <input type="text"  ng-change="selectPriceForSale(product)"  ng-model="product.price" ng-disabled ="true"  style="width: 120px">
                        </td>

                        <td >
                            <input type="text"  ng-change="findTotalCount_first_function(product,1)"  ng-model="product.quantity" ng-disabled ="false"  style="width: 120px">
                        </td>

                        <td >
                            <input type="text" ng-model="product.total_price" ng-change="findTotalCount_first_function(product,2)">
                           
                        </td>
                        <td>
                            <button type="button" ng-click="removeSaleButton(product)" title="REMOVE" class="btn btn-success btn-circle btn-sm"><i class="fa fa-minus"></i></button>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <div class="col-md-4" style="background-color: #FFF8DC;margin-bottom: 20px">

                <div class="note note-success" style="height:420px ">
                    <h5 class="block" style="font-weight: bold; color: #7e0f93"><i class="fa fa-calculator" aria-hidden="true"></i>&nbspCalculations</h5>
                    <span ng-show="invoiceShow" style="font-weight: bold;"> Invice : &nbsp {{inviceNo}}  <br></span>
                   <!-- <span style="font-weight: bold;"> Sub Total :</span>  &nbsp  <input  type="text" class="form-control" ng-disabled="true" ng-model="totalSalePriceOfAllItem | number:0"> <br>-->
                    <span style="font-weight: bold;"> Received : </span>  &nbsp  <input  ng-change="recivedAmountFunction(receivedAmount)" type="text" class="form-control"  ng-model="receivedAmount"><br>
                    <span style="font-weight: bold;"> Customer : </span>  &nbsp  <input ng-change="change_customer(customer_name)" type="text" class="form-control"  ng-model="customer_name"><br>
                    <span ng-show="customer_name !=''"><span style="font-weight: bold;"> Discount : </span>  &nbsp  <input  ng-change="recivedAmountFunction(receivedAmount)" type="text" class="form-control"  ng-model="discount_amount"><br></span>

                    <div class="col-md-12 " style="background-color: white;">
                        <div class="col-md-12 " style="height: 10px"></div>
                             <span style="font-size: 30px;" style="font-family: Arial, Helvetica, sans-serif;">Sub Total :</span>
                             <span style="color: red;font-size: 32px ;float: right;margin-right: 50px">{{totalSalePriceOfAllItem}}</span>
                              <hr  style="background-color: black">
                            <span style="font-size: 30px" style="font-family: Arial, Helvetica, sans-serif;">Amount Returned :</span>
                            <span ng-show="returnAmount>0" style="font-family: Arial, Helvetica, sans-serif;color: red;font-size: 32px ;float: right;margin-right: 50px">{{returnAmount}}</span>
                        <div class="col-md-12 " style="height: 10px"></div>
                    </div>
                    <!--<span style="font-weight: bold;">  Amount Returned :</span> &nbsp-->

                    <!--<span style="color: red;font-size: 24px">{{returnAmount}}</span>-->
                    <div class="col-md-12 " style="height: 10px"></div>
                    <input  ng-show="false" type="text" class="form-control"  ng-model="returnAmount"> <br>
                    <button style="font-family: Arial, Helvetica, sans-serif;" type="button"  ng-disabled="submitDisabled" ng-click="saleItemFunction(saleValueItem)" class="btn btn-success btn-sm">Submit</button>
                    <button style="font-family: Arial, Helvetica, sans-serif;" type="button"  ng-disabled="" ng-click="resetSubmit(saleValueItem)" class="btn btn-success btn-sm">Reset</button>
                    <button style="font-family: Arial, Helvetica, sans-serif;" type="button"  ng-disabled="!invoiceShow" ng-click="printInvoiceRepot()" class="btn btn-success btn-sm">Print</button>

                </div>
            </div>
        </div>


        <!--INvoiceREport-->
        <div id="printInvoice" ng-show="false">

            <?php date_default_timezone_set("Asia/Karachi"); ?>
            <h3 style=" line-height: 40%;text-align: center"> <span style=" font-family: Arial, Helvetica, sans-serif;">
                    <img style="width: 105px ;height: 65px" src="<?php echo Yii::app()->theme->baseUrl; ?>/company_logo/{{company_logo}}" alt="" class="media-object img-circle">
                </span>
            </h3>

            <h4 style=" line-height: 40%;text-align: center"> <span style=" font-family: Arial, Helvetica, sans-serif;">Sale Invoice</span></h4>
            <h5 style=" line-height: 40%;text-align: center"> <span style=" font-family: Arial, Helvetica, sans-serif;">{{subdomain}}</span></h5>
            <h6 style=" line-height: 40%;text-align: center"> <span style=" font-family: Arial, Helvetica, sans-serif;"><?php echo date("d-M-Y")." ".date("h:i:a"); ?> </span></h6>

            <div style="width:100% ">
                <div style="float: left;font-family: Arial, Helvetica, sans-serif;">Inv# {{inviceNo}}   </div>
                <div style="float: left; margin-left: 20px; font-family: Arial, Helvetica, sans-serif;"> {{responceDataSaleItem.Date}}  </div>
                <div style="float: left; margin-left: 20px; font-family: Arial, Helvetica, sans-serif;"> {{responceDataSaleItem.time}}  </div>
            </div>

            <br>
            <hr  style="height: 2px; background-color: black">
            <table width="100%">
                <tr>
                    <td style=" font-family: Arial, Helvetica, sans-serif;">Item Name</td>
                    <td style="text-align: center; font-family: Arial, Helvetica, sans-serif;">Qty</td>
                    <td style="text-align: center; font-family: Arial, Helvetica, sans-serif;">Price</td>
                    <td style="text-align: center;; font-family: Arial, Helvetica, sans-serif;" >Total</td>
                </tr>
                <tr ng-repeat="name in  saleValueItem">

                    <td style=" font-family: Arial, Helvetica, sans-serif;font-size: 12px;">{{name.name}}</td>
                    <td style="text-align: center; font-family: Arial, Helvetica, sans-serif;font-size: 12px;">{{name.quantity}}</td>
                    <td style="text-align: center; font-family: Arial, Helvetica, sans-serif;font-size: 12px;">{{name.price}}</td>
                    <td style="text-align: center;font-family: Arial, Helvetica, sans-serif;font-size: 12px;" >{{name.total_price}}</td>
                </tr>
            </table>
            <hr  style="height: 2px;background-color: black ">

            <table width="100%">
                <tr>
                    <td style="text-align: left; font-family: Arial, Helvetica, sans-serif;" >Payment Mode :Cash </td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td colspan="3" style=" font-family: Arial, Helvetica, sans-serif;">Sub Total : </td>
                    <td style="text-align: right; font-family: Arial, Helvetica, sans-serif;">{{totalSalePriceOfAllItem | number:0}}</td>
                </tr>
                <tr>
                    <td colspan="3" style=" font-family: Arial, Helvetica, sans-serif;">Cash Paid:  </td>
                    <td style="text-align: right; font-family: Arial, Helvetica, sans-serif;">{{receivedAmount | number:0}}</td>
                </tr>

                <tr id="customer_name">
                    <td colspan="3" style=" font-family: Arial, Helvetica, sans-serif;">Customer Name:  </td>
                    <td style="text-align: right; font-family: Arial, Helvetica, sans-serif;">{{customer_name}}</td>
                </tr>

                <tr  id="Discount_name">
                    <td colspan="3" style=" font-family: Arial, Helvetica, sans-serif;">Discount:  </td>
                    <td style="text-align: right; font-family: Arial, Helvetica, sans-serif;">{{discount_amount}}</td>
                </tr>


            </table>
            <hr  style="background-color: black ">
            <table width="100%">
                <tr>
                    <td  style=" font-family: Arial, Helvetica, sans-serif;" colspan="3">Customer Balance : </td>
                    <td style="text-align: right; font-family: Arial, Helvetica, sans-serif;">{{returnAmount | number:0}}</td>
                </tr>

            </table>
            <hr  style="background-color: black ">
            <hr  style="background-color: black ">

            <div style="width:100% " ng-show="false">
                <div style="text-align:center; font-family: Arial, Helvetica, sans-serif;font-size: 12px;">
                   {{line_1}}
                </div>


                <br>
                <div style="text-align: center; font-family: Arial, Helvetica, sans-serif;"> {{line_2}} </div>
                <div style="text-align: center; font-family: Arial, Helvetica, sans-serif;"> {{line_3}}</div>
                <br>
                <div style="text-align: center; font-family: Arial, Helvetica, sans-serif;"> {{line_4}}</div>

            </div>

        </div>



        <!--INvoiceREport-->

    </div>
</div>

