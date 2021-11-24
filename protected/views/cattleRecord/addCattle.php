

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/addCattle/addCattle-grid.js"></script>

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
    <div ng-controller="manageZone" ng-init='init(<?php echo $date;  ?> ,<?php echo $allow_delete ?>,<?php echo $addData ?>,  <?php echo $cattle_id ?> ,"<?php echo Yii::app()->createAbsoluteUrl('zone/saveNewZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/editZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/deleteRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/search'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Back
                </a>
            </li>
        </ul>
        <div class="col-lg-12" style="padding: 15px">
            <!--{{zoneObject}}-->
            <div class="col-lg-1">
                    <a  href="<?php echo Yii::app()->baseUrl; ?>/cattleRecord/manageCattle"  type="button"   class="btn btn-primary btn-sm"> <i class=""></i> Cattle List</a>
            </div>
            <div class="col-lg-1">
                    <a  href="<?php echo Yii::app()->baseUrl; ?>/cattleRecord/addCattle/0"  type="button"   class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Add New</a>
             </div>

            <div class="col-lg-1">
                <a  href="<?php echo Yii::app()->baseUrl; ?>/cattleProduction/Production"  type="button"   class="btn btn-primary btn-sm"> <i class=""></i> Miilk daily production </a>
            </div>
        </div>
        <?php
        $form = $this->beginWidget(
            'CActiveForm',
            array(
                'id' => 'agreement-form',
                'action'=>Yii::app()->createUrl('//cattleRecord/saveCattle'),
                'htmlOptions' => array('enctype' => 'multipart/form-data'),
                'enableAjaxValidation' => false,
            )
        );
        ?>


            <div class="col-lg-12" style="background-color: 	honeydew">
                <div class="col-lg-5" style="padding: 10px">
                    <span style="font-weight: bold;">Number</span>
                </div>
                <input ng-show="false" type="text" class="form-control"  name="cattle_record_id" ng-model="zoneList.cattle_record_id" >
                <div class="col-lg-5">
                    <input type="text" class="form-control"  name="number" ng-model="zoneList.number" id="serachCustomerBar">

                </div>
            </div>

            <div class="panel-body">
            </div>

            <div class="col-lg-12" style="background-color: 	honeydew">
                <div class="col-lg-5" style="padding: 10px">
                    <span style="font-weight: bold;">Date</span>
                </div>
                <div class="col-lg-5">
                   <input  ng-disabled="cattle_id>0" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="zoneList.create_date"  name="create_date" size="2">
                </div>
            </div>

            <div class="panel-body">
            </div>

            <div class="col-lg-12" style="background-color: 	honeydew">
                <div class="col-lg-5" style="padding: 10px">
                    <span style="font-weight: bold;">Type</span>
                </div>

                <div class="col-lg-5" style="padding: 10px">
                    <label class="radio-inline">
                        <input value="Cow" ng-model="zoneList.type" name="type" type="radio"  checked>Cow
                    </label>
                    <label class="radio-inline">
                        <input value="Buffalo" ng-model="zoneList.type" name="type" type="radio" >Buffalo
                    </label>
                 </div>
            </div>
            <div class="panel-body">
            </div>

            <div class="col-lg-12" style="background-color: 	honeydew">
                <div class="col-lg-5" style="padding: 10px">
                    <span style="font-weight: bold;">Milking</span>
                </div>
                <div class="col-lg-5" style="padding: 10px">
                    <label class="radio-inline">
                        <input value="1" ng-model="zoneList.milking" name="milking" type="radio"  checked>Yes
                    </label>
                    <label class="radio-inline">
                        <input value="0" ng-model="zoneList.milking" name="milking" type="radio" >No
                    </label>
                </div>
            </div>

            <div class="panel-body" ng-show="zoneList.milking==1">
            </div>
            <div class="col-lg-12" style="background-color: honeydew" ng-show="zoneList.milking==1">
                <div class="col-lg-5" style="padding: 10px">
                    <span style="font-weight: bold;">Milking Time</span>
                </div>
                <div class="col-lg-5" style="padding: 10px">
                    <label class="checkbox-inline"><input value="1" name="milking_time_morning" ng-model="zoneList.milking_time_morning" type="checkbox" >Morning</label>
                    <label class="checkbox-inline"><input value="1" name="milking_time_afternoun" ng-model="zoneList.milking_time_afternoun" type="checkbox" >Afternoun</label>
                    <label class="checkbox-inline"><input value="1" name="milking_time_evening" ng-model="zoneList.milking_time_evening" type="checkbox" >Evening</label>
                </div>
            </div>

            <div class="panel-body" >
            </div>

            <div class="col-lg-12" style="background-color: 	honeydew">
                <div class="col-lg-5" style="padding: 10px">
                    <span style="font-weight: bold;">Milking Date</span>
                </div>
                <div class="col-lg-5">
                    <input   class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="zoneList.milking_on_off_date"  name="milking_on_off_date" size="2">
                </div>
            </div>





        <div ng-show="false" class="col-lg-12" style="background-color: 	honeydew">
                <div class="col-lg-5" style="padding: 10px">
                    <span style="font-weight: bold;">Status :</span>
                </div>
                <div class="col-lg-5" style="padding: 10px">
                    <label class="radio-inline">
                        <input value="1" ng-model="zoneList.is_active" name="is_active" type="radio"  checked>Active
                    </label>
                    <label class="radio-inline">
                        <input value="0" ng-model="zoneList.is_active" name="is_active" type="radio" >Inactive
                    </label>
                </div>
            </div>
            <div class="panel-body">
            </div>


            <div class="col-lg-12" style="background-color: 	honeydew">
                <div class="col-lg-5" style="padding: 10px">
                    <span style="font-weight: bold;">Picture</span>
                </div>
                <div class="col-lg-5" width="60px">
                    <span  > <img  ng-click="mouseoveronimg(zone.picture)" style="height: 50px ;width: 50px" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/cattle/{{zoneList.picture}}" alt="" class="loading"></span>
                    <input type="file" class="form-control" id="attechment1" name="picture">
                </div>
            </div>
            <div class="panel-body">
            </div>
            <div class="col-lg-12" style="background-color: honeydew">
                <div class="col-lg-10" >
                    <button ng-show="cattle_id ==0" type="submit" name="action" value="submit"  id="myBtn" class="btn btn-primary btn-sm">Save</button>
                    <button ng-show="cattle_id >0" type="submit" name="action" value="submit"  id="myBtn" class="btn btn-primary btn-sm">Update</button>
                </div>
            </div>
            <div class="panel-body">
            </div>
        <?php $this->endWidget(); ?>

    </div>
</div>

