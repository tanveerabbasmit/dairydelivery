

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/smsPortal/smsPortal.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<?php  $allow_delete = Yii::app()->user->getState('allow_delete'); ?>
<?php  $allow_delete = 1; ?>
<div id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">

    <div ng-controller="manageZone" ng-init='init( <?php echo $allow_delete ?> ,<?php echo $riderList ?> , <?php echo  $zoneList  ?> ,<?php echo $clientList ?> ,"<?php echo Yii::app()->createAbsoluteUrl('zone/SendSMS'); ?>")'>

        <div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>message! </strong>
            {{taskMessage}}
        </div>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Send SMS
                </a>
            </li>
        </ul>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-4" >
                    <div class="input-group">
                      <select class="form-control" ng-model="selectOption" ng-change="change_option(selectOption)">
                         <option value="">Select Option</option>
                         <option value="1">To All Customers</option>
                         <option value="4">Today Delivery Customers</option>
                         <option value="2">Select Zone</option>
                         <option value="3_active">Particular Customer Active</option>
                         <option value="3_inactive">Particular Customer Inactive</option>
                         <option value="5">Particular Rider</option>
                         <option value="6">outstanding balance</option>
                         <option value="7">Color Tag Customer</option>
                      </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 row " style="margin-top: 20px ; margin-bottom: 10px">
                <div style="float: left;" ng-show="selectOption=='3_inactive' || selectOption=='3_active' ">
                    <button class="btn btn-default dropdown-toggle" ng-click="showDropDownList()" type="button" id="dropdownMenu1" data-toggle="dropdown"><span>{{SelectedCustomer}}</span> <span  class="caret"></span>
                    </button>
                    <ul style="width: 200px ;max-height: 500px ; overflow-x: hidden;overflow-y: scroll;" class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" >
                        <li role="presentation">
                            <div class="input-group input-group-sm search-control"> <span class="input-group-addon">
                    <span ></span>
                    </span>
                                <input autofocus type="text" class="form-control" placeholder="Search" ng-model="query" id="serachCustomerBar">
                            </div>
                        </li >
                        <li  role="presentation" ng-click="selectedClient(item)" ng-repeat='item in clientList | filter:query'> <a href="#"> {{item.fullname}} </a>
                        </li>
                    </ul>
                    <img ng-show="selectClientLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                </div>
                <div class="col-lg-12 row " ng-show="selectOption==2">

                    <div class="col-lg-4" ng-repeat="zone in zoneList">

                        <label class="checkbox-inline" >
                            <input type="checkbox" ng-model="zone.is_selected">{{zone.name}}
                        </label>
                    </div>
                </div>
                <div style="float: left;" ng-show="selectOption=='7' ">
                    <div class="col-lg-12 row ">
                        <select style="" class="form-control" ng-model="tag_color_id" >
                            <option value="-1">Select</option>
                            <option ng-repeat="list in clientList_color_tag" value="{{list.tag_color_id}}">{{list.fullname}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-12 row " ng-show="selectOption==5 || selectOption==6">
                    <div class="col-lg-4">

                        <select style="" class="form-control" ng-model="selectRiderID" >
                            <option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <table class="table table-striped nomargin" style="margin-top: 10px">
                <thead>
                <tr>
                    <th width="40%"><textarea rows="4" cols="50" ng-model="messageText"></textarea></th>
                    <th>
                        <button  ng-disabled=" allow_delete =='0'" type="button"  ng-disabled="senedSMS_disabled" ng-click="sendSms()" class="btn btn-primary btn-ms"> <i class="fa fa-mobile"></i> Send</button>
                        <img ng-show="senedSMS_disabled" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                    </th>
                </tr>

                </thead>
            </table>
        </div>
        <!-- start: add new Zone -->
        <modal title="Add New Zone" visible="showAddNewZone">
            <form role="form" class="form-group" ng-submit="saveZone(zoneObject)">
                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Name :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.name" class="form-control"   required/>
                    </div>
                </div>
                <div ng-show="false" class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
                    <div class="col-lg-4">
                          <span style="font-weight: bold;font-size: 13px; padding: 8px">Company Branch :</span>
                    </div>
                    <div class="col-lg-8" style="padding: 10px">
                        <!--<input type="text"   ng-model="zoneObject.companyBranch"  class="form-control" required/>-->
                        <select ng-model="zoneObject.companyBranch" class="form-control">
                             <option value="">Select</option>
                            <option ng-repeat="company in companyBranchList" value="{{company.company_branch_id}}">
                                {{company.name}}
                           </option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Status :</span>
                    </div>
                    <div class="col-lg-8" style="padding: 10px">
                        <label class="radio-inline">
                            <input ng-model="zoneObject.is_active"   type="radio" name="status" value="1" required>
                            <span  class="label label-default">Active</span>
                        </label>
                        <label class="radio-inline">
                            <input ng-model="zoneObject.is_active" type="radio" name="status" value="0" required>
                            <span  class="label label-primary">Inactive</span>
                        </label>
                    </div>
                </div>
                <div class=" form-group ">
                    <button type="submit" class="btn-success  btn-sm">Save</button>
                    <button type="button" ng-click="setReset()" class="btn-success  btn-sm">Reset</button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form>
        </modal>


    </div>
</div>

