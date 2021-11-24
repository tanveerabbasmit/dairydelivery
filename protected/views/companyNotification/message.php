

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/message/message-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>

<?php $allow_delete = crudRole::getCrudrole(1); ?>

<div id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">



    <div ng-controller="manageZone" ng-init='init(<?php echo date("Y-m-d"); ?> ,"<?php echo Yii::app()->createAbsoluteUrl('companyNotification/base'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Manage Zone
                </a>
            </li>
        </ul>
        <div class="" style="margin: 10px">

            <div class="col-lg-12">
                <div class="col-lg-6" style="background-color: honeydew">

                    <div class="col-lg-4 " style="padding-top: 10px" >
                        <span style="font-weight: bold;">Hide Date :</span>
                    </div>
                    <div class="col-lg-8">
                        <input  class="form-control"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="messageObject.end_date" size="2">
                    </div>
                </div>
                <div class="col-lg-6" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding-top: 10px">
                        <span style="font-weight: bold;">Heading.</span>
                    </div>
                    <div class="col-lg-8">
                        <input  type="text" ng-model="messageObject.heading" class="form-control" required>
                    </div
                </div>
            </div>
            <div class="panel-body">
            </div>
            <div class="col-lg-12 row">
                <div class="col-lg-12" style="background-color: #FFF0F5">

                    <div class="col-lg-12 " style="padding-top: 10px" >
                        <div  class="col-lg-12">
                            <span style="font-weight: bold;">Company :</span>
                        </div>

                    </div>
                    <div class="col-lg-3" ng-repeat="list in company">
                        <div class="col-lg-12" style="margin-top: 3px">
                            <label class="checkbox-inline">
                                <input ng-model="list.check_company" type="checkbox" value="">{{list.company_name}}
                            </label>
                        </div>

                    </div>
                </div>

            </div>
            <div class="panel-body">
            </div>

            <div class="col-lg-12" style="background-color: honeydew">
                <div class="col-lg-2" style="padding-top: 20px">
                    <span style="font-weight: bold;">Message.</span>
                </div>
                <div class="col-lg-8">
                   <textarea rows="4" cols="150" ng-model="messageObject.message"></textarea>
                </div
            </div>
        </div>
        <div class="panel-body">
        </div>
        <div class="col-lg-12 row">
            <div class="col-lg-6" style="background-color: honeydew">
                <div class="col-lg-4 " style="padding-top: 0px" >
                    <span style="font-weight: bold;">Message Type</span>
                </div>
                <div class="col-lg-8">
                    <select class="form-control" ng-model="messageObject.message_type">
                        <option value="success">Success</option>
                        <option value="info">Info</option>

                        <option value="warning">Warning</option>
                        <option value="danger">Danger</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-6 row" style="background-color: ">

                <div class="col-lg-12 row">
                    <div class="col-lg-12">
                        <button style="float: right" type="button" ng-click="saveMessage()" class="btn btn-primary btn-sm"> <i class="fa fa-save" style="margin: 5px"></i> Update</button>
                        <img ng-show="loading" style="float: right;margin: 5px" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                    </div>
                </div
            </div>
        </div>

        <div class="panel-body">
        </div>
    </div>
</div>

