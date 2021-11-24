

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/manageSpecialOrder/order_hup_list_grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<div  id="testContainer" style="display: none" class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="manageZone" ng-init='init(<?php echo $spcialOrderCount  ?> , <?php echo $data ?> ,  "<?php echo Yii::app()->createAbsoluteUrl('SpecialOrder/nextPageForPagination'); ?>",  "<?php echo Yii::app()->createAbsoluteUrl('SpecialOrder/order_hup_list_today_data'); ?>" ,  "<?php echo Yii::app()->createAbsoluteUrl('SpecialOrder/viewAll'); ?>",  "<?php echo Yii::app()->createAbsoluteUrl('SpecialOrder/nextPagePaginationViewAll'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Special Order
                </a>
            </li>
        </ul>

        <div class="panel-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="input-group">
                        <input  class="form-control btn-xm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="today" >
                        <span class="input-group-addon" ng-click="searchSpicailOrder(today)"><i class="glyphicon glyphicon-search"></i></span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <button class="btn btn-primary btn-ms" ng-click="searchSpicailOrder(today)" ><i class="fa fa-search"></i>  Search</button>

                    <img ng-show="viewAllDataLoader"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">
                </div>
            </div>
            <div style="margin-top:5px;" class="table-responsive">

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




                <table id="customers" >
                    <thead>
                    <tr style="background-color: #F0F8FF">
                        <th><a href="#">Name</a></th>
                        <th><a href="#">Address</a></th>
                        <th><a href="#">Phone NO.</a></th>
                        <th><a href="#">Status</a></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="list in order_list">
                        <td>{{list.client_object.fullname}}</td>
                        <td>
                           <span>{{list.client_object.select_address}}</span>
                           <span ng-show="list.client_object.select_address==''">{{list.client_object.address}}</span>
                        </td>
                        <td>{{list.client_object.cell_no_1}}</td>
                        <td style="text-align: center">
                            <a style="" href="" ng-click="make_delivery_function(list)"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        </td>
                     </tr>
                    </tbody>
                </table>

                <modal title="Order" visible="make_delivery">
                    <form role="form" class="form-group" ng-submit="saveZone(zoneObject)">


                        <div class="col-lg-12 form-group" style="background-color: honeydew">
                            <div class="col-lg-4" style="padding: 10px">
                                <span style="font-weight: bold;font-size: 13px; padding: 8px">Name :</span>
                            </div>
                            <div class="col-lg-8" style="padding: 10px">
                                <span ng-bind="slect_delivery_list.client_object.fullname"></span>

                            </div>
                        </div>

                        <div class="col-lg-12 form-group" style="background-color: honeydew">
                            <div class="col-lg-4" style="padding: 10px">
                                <span style="font-weight: bold;font-size: 13px; padding: 8px">Adress :</span>
                            </div>
                            <div class="col-lg-8" style="padding: 10px">
                                <span ng-bind="slect_delivery_list.client_object.address"></span>

                            </div>
                        </div>
                        <div class="col-lg-12 form-group" style="background-color: honeydew">
                            <div class="col-lg-4" style="padding: 10px">
                                <span style="font-weight: bold;font-size: 13px; padding: 8px">Phone Number :</span>
                            </div>
                            <div class="col-lg-8" style="padding: 10px">
                                <span ng-bind="slect_delivery_list.client_object.cell_no_1"></span>

                            </div>
                        </div>

                        <table id="customers" style="width: 100%">
                            <tr>
                                <th>
                                    <a href=""> Product</a>
                                </th>

                                <th  style="text-align: center">
                                    <a href=""> Quantity</a>
                                </th>

                                <th  style="text-align: center">
                                    <a href=""> Amount</a>
                                </th>
                                <th></th>
                                <th></th>
                            </tr>
                            <tr ng-repeat="list in slect_delivery_list.prouct_object">
                                   <td>
                                        {{list.name}}
                                   </td>
                                   <td style="text-align: center">{{list.quantity}}</td>
                                   <td style="text-align: center">{{list.total_price}}</td>

                                   <td style="text-align: center">

                                       <i ng-show="list.is_delivered=='0'" class="fa fa-eye" aria-hidden="true"></i>
                                       <i ng-show="list.is_delivered=='1'" class="fa fa-truck" aria-hidden="true"></i>

                                   </td>

                                   <td style="text-align: center">
                                       <input type="checkbox" ng-model="list.selected">
                                   </td>
                            </tr>
                        </table>

                        <div class=" form-group" style="margin-top: 10px">

                            <button ng-disabled="loading" type="button" ng-click="deliver_order()" class="btn-success  btn-sm">Deliver</button>
                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </modal>

        </div>
    </div>
</div>

