
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/saleGraph_dashbord/saleGraph_dashbord-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<div id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">

    <div ng-controller="manageZone" ng-init='init(<?php echo $id ?> , <?php echo $customerObject ?>  , <?php echo $lable ?> ,"<?php echo Yii::app()->createAbsoluteUrl('dashboard/getCustomerData'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/editZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/deleteRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/search'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Sale Graph
                </a>
            </li>

        </ul>
        <div class="col-lg-12" >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" >
                        <thead>
                        <tr>
                            <th colspan="3"><?php echo $mainHeadig ?> </th>
                            <th style="text-align: right">
                                <a ng-show="id == 1"  href="<?php echo Yii::app()->createUrl('dashboard/saleGraph')?>?id=2"><i class="fa fa-eye"></i>Graph with quantity</a>
                                <a ng-show="id == 2"  href="<?php echo Yii::app()->createUrl('dashboard/saleGraph')?>?id=1"><i class="fa fa-eye"></i>Graph with amount </a>
                            </th>

                        </tr>
                        </thead>
                        <tbod">
                        <tr>
                            <td colspan="4" >
                                <canvas style="background-color: white" id="bar-chart-grouped"></canvas>
                            </td>
                        </tr>


                        </tbody>
                    </table>
                </div>

            </div>

        </div>
        <div >
            <canvas id="bar-chart-grouped"></canvas>
        </div>

    </div>
</div>

