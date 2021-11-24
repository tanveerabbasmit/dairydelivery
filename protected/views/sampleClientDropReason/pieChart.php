

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/pieChart/pieChart-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>

<?php $allow_delete = crudRole::getCrudrole(42); ?>

<div id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">

    <div ng-controller="manageZone" ng-init='init(<?php echo $start_date ?> ,<?php echo $end_date ?> ,"<?php echo Yii::app()->createAbsoluteUrl('SampleClientDropReason/pieChartData'); ?>","<?php echo Yii::app()->createAbsoluteUrl('SampleClientDropReason/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/deleteRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/search'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Pie Chart
                </a>
            </li>

        </ul>
        <div class="" style="margin: 10px">

            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">

            </script>
            </head>
            <body>

            <input style="float: left ; width: 22% ;margin-bottom: 10px" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="start_date" size="2">
            <button  style="float: left" type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
            <input ng-disabled="false" style="float: left ; width: 22% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="end_date " size="2">

            <button type="button"  ng-click="getChartData()" class="btn btn-primary btn-sm"> <i class="fa fa-search" style="margin: 5px"></i> Search</button>
            <img ng-show="dataLoad" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
           <!-- <div>
                <p style="color: green">No Data Found</p>
            </div>-->
            <div class="row" ng-show="NoDataShow">
                <div class="col-sm-12"> <h3>No Data Found</h3></div>
             </div>

              <div id="piechart" style="width: 900px; height: 550px;"></div>
            <div class="row" ng-show="!NoDataShow">
                <div style="float: left;margin: 5px; " ng-repeat="list in customerList">
                    <a  href="<?php echo Yii::app()->baseUrl; ?>/client/dropCustomerList?start_date={{start_date}}&end_date={{end_date}}&deactive_reason_id={{list.deactive_reason_id}}"  type="button"   class="btn btn-default btn-sm">{{list.reason_button}}</a>
                </div>
            </div>

            </body>


        </div>
    </div>
</div>

