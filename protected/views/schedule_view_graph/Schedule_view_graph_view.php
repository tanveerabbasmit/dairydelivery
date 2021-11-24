
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js//viewPageJs/reconcileStock/schedule_view_graph_view_grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo json_encode($data); ?>)'>
        <div id='calendar'></div>
        <div style='clear:both'></div>

    </div>
</div>



<script type="text/javascript">
    $(function () {
        $('#searchDate').datepicker({
            format: "yyyy-mm-dd"
        });
        $('#stockDate').datepicker({
            format: "yyyy-mm-dd"
        });
    });
</script>

<style type="text/css">
    .angularjs-datetime-picker {
        z-index: 99999 !important;
    }
</style>
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/table/graph.css">

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/table/graph_script.js"></script>