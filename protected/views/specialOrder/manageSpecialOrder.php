

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/manageSpecialOrder/manageSpecialOrder-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<div  id="testContainer" style="display: none" class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="manageZone" ng-init='init(<?php echo $spcialOrderCount  ?> , <?php echo $data ?> ,  "<?php echo Yii::app()->createAbsoluteUrl('SpecialOrder/nextPageForPagination'); ?>",  "<?php echo Yii::app()->createAbsoluteUrl('SpecialOrder/searchDeliveryDate'); ?>" ,  "<?php echo Yii::app()->createAbsoluteUrl('SpecialOrder/viewAll'); ?>",  "<?php echo Yii::app()->createAbsoluteUrl('SpecialOrder/nextPagePaginationViewAll'); ?>")'>
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
                    <button class="btn btn-primary btn-ms" ng-click="viewAllDataFunction()" ><i class="fa fa-eye"></i>  View All</button>
                    <a  href="<?php echo Yii::app()->baseUrl; ?>/index.php/SpecialOrder/createNewSpacialOrder"  type="button"   class="btn btn-primary"> <i class=""></i> create  Spacial Order </a>
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

                <table id="customers">
                    <thead>
                    <tr style="background-color: #F0F8FF">
                        <th><a href="#">#</a></th>
                        <th><a href="#">Customer</a></th>
                        <th><a href="#">Product</a></th>
                        <th><a href="#"> Quantity</a></th>
                        <th><a href="#">Requested On</a></th>
                        <th><a href="#">From date</a></th>
                        <th><a href="#">To date</a>  </th>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="zone in spcialOrderList | filter:search:stric track by $index">
                        <td>{{1+$index + (curPage)*10 }}</td>
                        <td>{{zone.fullname}}</td>
                        <td>{{zone.name}}</td>
                        <td style="text-align: right">{{zone.quantity | number:2}}</td>
                        <td>{{changeDateFormate(zone.requested_on)}}</td>
                        <td>{{changeDateFormateOnlyDate(zone.start_date)}}</td>
                        <td>{{changeDateFormateOnlyDate(zone.end_date)}}</td>
                        <td>

                            <a  href="<?php echo Yii::app()->baseUrl; ?>/index.php/SpecialOrder/manageSpecialOrder?special_order_id={{zone.special_order_id}}"  type="button"   class="btn btn-primary"> <i class="fa fa-trash "></i> </a>
                        </td>
                     </tr>
                    </tbody>
                </table>
                <div  ng-show="today != ''"  class="pagination pagination-centered">
                    <button type="button" class="btn btn-sm btn-info prev" title="Prev" ng-disabled="curPage == 0" ng-click="nextPagePagination(curPage =0 )"> First </button>
                    <button type="button" class="btn btn-sm btn-info prev" title="Prev" ng-disabled="curPage == 0" ng-click="nextPagePagination( curPage =curPage - 1)"> &lt; PREV</button>
                    <span>Page {{curPage + 1}} of {{totalPages}}</span>
                    <button type="button" class="btn btn-sm btn-info next" ng-disabled="curPage >= totalPages - 1" ng-click="nextPagePagination(curPage = curPage + 1)"> NEXT &gt;</button>
                    <button type="button" class="btn btn-sm btn-info next" ng-disabled="curPage >= totalPages - 1" ng-click="nextPagePagination(curPage  = totalPages - 1)">Last</button>
                </div>
                <div  ng-show="today == ''"  class="pagination pagination-centered">
                    <button type="button" class="btn btn-sm btn-info prev" title="Prev" ng-disabled="curPage == 0" ng-click="nextPagePaginationViewAll(curPage =0 )"> First </button>
                    <button type="button" class="btn btn-sm btn-info prev" title="Prev" ng-disabled="curPage == 0" ng-click="nextPagePaginationViewAll( curPage =curPage - 1)"> &lt; PREV</button>
                    <span>Page {{curPage + 1}} of {{totalPages}}</span>
                    <button type="button" class="btn btn-sm btn-info next" ng-disabled="curPage >= totalPages - 1" ng-click="nextPagePaginationViewAll(curPage = curPage + 1)"> NEXT &gt;</button>
                    <button type="button" class="btn btn-sm btn-info next" ng-disabled="curPage >= totalPages - 1" ng-click="nextPagePaginationViewAll(curPage  = totalPages - 1)">Last</button>
                </div>
            </div><!-- table-responsive -->
        </div>
    </div>
</div>

