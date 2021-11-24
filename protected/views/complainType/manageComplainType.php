

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/manageComplainType/manageComplainType-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>

<?php $allow_delete = crudRole::getCrudrole(5); ?>

<div  id="testContainer" style="display: none" class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="manageZone" ng-init='init(<?php echo $allow_delete  ?> , <?php echo str_replace("'","&#39;",$ComplainTypeList ); ?>   ,"<?php echo Yii::app()->createAbsoluteUrl('ComplainType/saveNewComplian'); ?>" , "<?php echo Yii::app()->createAbsoluteUrl('ComplainType/EditComplain'); ?>", "<?php echo Yii::app()->createAbsoluteUrl('ComplainType/deleteComplainType'); ?>")'>
        <div class="panel-heading">
            <h4 class="panel-title">Complain Type</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="btn-demo">
                        <button ng-disabled=" allow_delete[1]" class="btn btn-primary btn-sm" ng-click="addnewZone()" ><i class="fa fa-plus"></i> Add New Complain</button>
                    </div>
                </div>
                <div class="col-lg-4">
                </div>
                <div class="col-lg-4">
                    <div class="input-group">
                        <input  class="form-control" placeholder="Search Complain" ng-change="searchBarOnzero(searchBar)"  type="text" required ng-model="searchBar" size="2">
                        <span class="input-group-addon" ng-click="searchZone(searchBar)"><i class="glyphicon glyphicon-search"></i></span>
                    </div>
                </div>
            </div>
            <div style="margin-top:5px;" class="table-responsive">

                <table class="table table-striped nomargin">
                    <thead>
                    <tr>
                        <th style="width: 70%">Name</th>

                        <th style="width: 30%">Action</th>
                    </tr>
                    </thead>
                    <tbody>


                    <tr ng-repeat="complain in ComplainTypeList | filter:search:stric">
                        <td>{{complain.name}}</td>


                        <td>
                            <button ng-disabled=" allow_delete[3]" ng-click="editZone(complain)"  class="btn btn-sm btn-default next btn-xs" > <i class="fa fa-edit "></i> </button>
                            <button ng-disabled=" allow_delete[2]" ng-click="complainTypeDelate(complain)"  class="btn btn-sm btn-info next btn-xs" > <i class="fa fa-trash "></i></button>

                        </td>
                    </tr>
                    </tbody>
                </table>



            </div><!-- table-responsive -->
        </div>


        <!-- start: add new Zone -->


        <modal title="Add New Complain" visible="showAddNewZone">
            <form role="form" class="form-group" ng-submit="saveZone(zoneObject)">
                <div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Name :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.name" class="form-control"   required/>
                    </div>
                </div>


                <div class=" form-group ">

                    <button type="submit" class="btn-success  btn-sm">Save</button>
                    <button type="button" ng-click="setReset()" class="btn-success  btn-sm">Reset</button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form>
        </modal>

        <!-- end: add new Zone -->

        <!-- start: add new Zone -->


        <modal title="Update Complain" visible="showEditZone">

            <form role="form" class="form-group" ng-submit="editZoneFunction(zoneObject)">
                <div class="col-lg-12 form-group">
                    <div class="col-lg-4">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Name :</span>
                    </div>

                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.name" class="form-control"   required/>
                    </div>

                </div>


                <div class=" form-group ">

                    <button type="submit" class="btn-success  btn-sm">Save</button>
                    <button type="button" ng-click="setReset()" class="btn-success  btn-sm">Reset</button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form>
        </modal>

        <!-- end: add new Zone -->
    </div>
</div>

