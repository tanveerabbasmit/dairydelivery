<style>
    .modal{
    //  display: block !important; /* I added this to see the modal, you don't need this */
    }

    /* Important part */
    .modal-dialog{
        overflow-y: initial !important
    }
    .modal-body{
        max-height: 600px;
        overflow-y: auto;
    }


</style>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/manageClientComplain/manageClientComplain-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>

<?php $allow_delete = crudRole::getCrudrole(6); ?>

<div  id="testContainer" style="display: none" class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="manageZone" ng-init='init( <?php  echo $allow_delete ?> ,  <?php  echo 'clientComplainCount' ?> , <?php echo $statusList ?> , <?php echo 'clientComplainList' ?> ,"<?php echo Yii::app()->createAbsoluteUrl('Complain/saveStatus'); ?>" ,"<?php echo Yii::app()->createAbsoluteUrl('Complain/nextPageForpagination'); ?>","<?php echo Yii::app()->createAbsoluteUrl('Complain/searchComplain'); ?>" ,"<?php echo Yii::app()->createAbsoluteUrl('Complain/totalComplainOfOneCustomer'); ?>" , "<?php echo Yii::app()->createAbsoluteUrl('Complain/getComplainType') ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    {{selectedTypeComplain}} &nbsp&nbsp&nbsp  {{clientComplainCount}}
                </a>
            </li>
        </ul>
        <div class="panel-body">
           
            <div class="row">
               <div class="col-lg-3">
                    <select style="width: 90%; float: left" class="form-control" ng-model="complainType" ng-change="changeComplaintype(1)">
                        <option value="1">Complains</option>
                        <option value="2">Suggestions</option>
                    </select>


                </div>
                <div class="col-lg-3">

                    <select class="form-control" ng-model="status_id"  ng-change="changeComplaintype(1)">
                        <option value="0">Select Status</option>
                        <option value="{{list.status_id}}" ng-repeat="list in statusList">{{list.status_name}}</option>
                    </select>

                </div>
                <div class="col-lg-3">
                    <img  ng-show="imageDataLoader" style="float: left ;margin: 5px" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                </div>
            </div>

            <div style="margin-top:5px;" class="table-responsive">
                <table class="table table-striped nomargin">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th ng-show="complainType=='1'">Complain Type</th>
                        <th width="50%" ng-show="complainType=='0'" >Description</th>
                        <th ng-show="complainType=='1'" >Status</th>
                        <th ng-show="complainType=='1'" style="text-align: center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="complain in clientComplainList track by $index">
                        <td>{{1+$index + (curPage - 1)*10 }}</td>
                        <td>{{complain.complain_id}}_{{complain.fullname}}</td>
                        <td >{{changeDateFormate(complain.created_on)}}</td>
                        <td ng-show="complainType=='0'">{{complain.query_text}}</td>
                        <td ng-show="complainType=='1'">{{complain.name}}</td>
                        <td ng-show="complainType=='1'"> {{complain.status_name}}</td>
                        <td ng-show="complainType=='1'">
                            <ul class="table-options">
                                <li><a href="" ng-click="complainDetail(complain)"  title="Complain Detail"><i class="fa fa-eye btn btn-info btn-md"> view</i> </a></li>
                            </ul>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div  ng-show="hideAndShowPagination"  class="pagination pagination-centered">
                    <button type="button" class="btn btn-sm btn-info prev" title="Prev" ng-disabled="curPage == 1" ng-click="changeComplaintype(curPage =1)"> First </button>
                    <button type="button" class="btn btn-sm btn-info prev" title="Prev" ng-disabled="curPage == 1" ng-click="changeComplaintype( curPage =curPage - 1)"> &lt; PREV</button>
                    <span>Page {{curPage}} of {{totalPages}}</span>
                    <button type="button" class="btn btn-sm btn-info next" ng-disabled="curPage >= totalPages" ng-click="changeComplaintype(curPage = curPage + 1)"> NEXT &gt;</button>
                    <button type="button" class="btn btn-sm btn-info next" ng-disabled="curPage >= totalPages" ng-click="changeComplaintype(curPage  = totalPages)">Last</button>
                </div>

            </div><!-- table-responsive -->
        </div>

        <!-- start: add new Zone -->

        <!-- end: add new Zone -->
        <!-- start: add new Zone -->

        <modal title="Complain Detail" visible="showEditZone">
            <form role="form" ng-submit="saveStatus(ComplainObject)">


                <div class="col-lg-12 form-group">
                    <div class="col-lg-4">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Customer :</span>
                    </div>

                    <div class="col-lg-8">
                       {{ComplainObject.fullname}}
                    </div>
                </div>

                <div class="col-lg-12 form-group">
                    <div class="col-lg-4">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Date :</span>
                    </div>
                    <div class="col-lg-8">
                        {{changeDateFormate(ComplainObject.created_on)}}
                    </div>
                </div>
                <div class="col-lg-12 form-group">
                    <div class="col-lg-4">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px"> Complain Type :</span>
                    </div>
                    <div class="col-lg-8">
                       {{ComplainObject.name}}
                    </div>
                </div>
                <div class="col-lg-12 form-group">
                    <div class="col-lg-4">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Description :</span>
                    </div>
                    <div class="col-lg-8">
                       {{ComplainObject.query_text}}
                    </div>
                </div>
                <div class="col-lg-12 form-group">
                    <div class="col-lg-4">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Status :</span>
                    </div>
                    <div class="col-lg-8">
                        <select class="form-control" ng-model="ComplainObject.status_id">
                            <option ng-repeat="status in  statusList" value="{{status.status_id}}">{{status.status_name}}</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-12 form-group">
                    <div class="col-lg-4">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Remarks :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="ComplainObject.response" class="form-control"   required/>
                   </div>
                </div>

            <div class="col-lg-12 form-group">
                    <div class="col-lg-6">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Total complain of this customer :</span>
                    </div>
                    <div class="col-lg-3">
                        {{countComplain}}
                   </div>

                  <div class="col-lg-3">
                      <button  type="button" ng-click="showAllComplain()" class="btn btn-primary btn-sm">View All</button>
                   </div>
              </div>
                <button ng-disabled="allow_delete[3]" type="submit" class="btn btn-primary btn-sm">Submit</button>
                <button type="button" ng-click="okComplainDetail()" class="btn btn-default btn-sm">OK</button>

                <table style="margin-top: 10px" ng-show="viewresultComplain" class="table table-striped nomargin">
                    <thead>
                    <tr>

                        <th>Date</th>
                        <th>Description</th>
                        <th>Complain Type</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr ng-repeat="complain in resultComplain ">

                        <td>{{complain.created_on}}</td>
                        <td>{{complain.query_text}}</td>
                        <td>{{complain.name}}</td>
                        <td>{{complain.status_name}}</td>
                        <td>{{complain.response}}</td>

                    </tr>

                    </tbody>
                </table>


            </form>
        </modal>

        <!-- end: add new Zone -->
    </div>
</div>

