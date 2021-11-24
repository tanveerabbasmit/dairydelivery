<style>
    .angularjs-datetime-picker{
        z-index: 99999 !important;
    }
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/allBill/sample_schedule_form_grad.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>



<?php $company_id = Yii::app()->user->getState('company_branch_id');
$company= Company::model()->findByPk(intval($company_id));
$company_name = $company['company_name'];

?>
<div class="panel row" id="testContainer" style="display: none" ng-app="clintManagemaent">
    <div ng-controller="clintManagemaent" ng-init='init(<?php echo $data; ?> , "<?php echo Yii::app()->createAbsoluteUrl('client/getClientLedgherReport'); ?>", "<?php echo Yii::app()->createAbsoluteUrl('client/oneCustomerAmountListallCustomerList'); ?> ", "<?php echo Yii::app()->createAbsoluteUrl('sample_schedule/base_url');?>" )'>

        <div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>message! </strong>
            {{taskMessage}}
        </div>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Sample Schedule Form
                </a>
            </li>
        </ul>

        <div style="margin: 15px">
            <div class="col-lg-12" style="margin-bottom: 50px">

                <div style="float: left;" ng-show="true">
                    <button class="btn btn-default dropdown-toggle btn-sm" ng-click="showDropDownList()" type="button" id="dropdownMenu1" data-toggle="dropdown">{{SelectedCustomer}} <span  class="caret" style="margin: 9px"></span>
                    </button>
                    <ul style="width: 200px ;max-height: 500px ; overflow-x: hidden;overflow-y: scroll;" class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" >
                        <li role="presentation">
                            <div class="input-group input-group-sm search-control"> <span class="input-group-addon">
						<input type="text" class="form-control" placeholder="Search" ng-model="query" id="serachCustomerBar">
                            </div>
                        </li >
                        <li  role="presentation" ng-click="abcd(item)" ng-repeat='item in clientList | filter:query'> <a href="#"> {{item.fullname}} </a>
                        </li>
                    </ul>
                </div>

                <form ng-submit="save_sample_schedule()">
                    <input style="margin-left: 10px;width: 20% ; float: left"  class="form-control input-sm" datetime-picker="" date-only="" date-format="yyyy-MM-dd" type="text" required="" ng-model="action_date" size="2">
                    <input style="margin-left: 10px;width: 20% ; float: left"  class="form-control input-sm"   type="text" required="" ng-model="quantity" size="2" placeholder="Quantity" required>


                    <button type="submit"  class="btn btn-primary input-sm" style="margin-left:10px;float: left" >Save</button>


                    <img style="margin: 10px" ng-show="imageLoader"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">
                </form>


            </div>

            <div  class="col-lg-12" ng-show="address" style="background-color: #FFF8DC;">
                <div style="float: left">
                    <span style="font-weight: bold;">Address :  </span> {{address}}
                </div>

                <div style="float: left ; margin-left: 20px">
                    <span style="font-weight: bold;">Contact Number :  </span> {{cell_no_1}}
                </div>

                <div style="float: left ; margin-left: 20px">
                    <span style="font-weight: bold;">Zone :  </span> {{zone_name}}
                </div>

                <div style="float: left ; margin-left: 20px">
                    <span style="font-weight: bold;">Payment Term  :  </span> {{payment_term}}
                </div>
            </div>


        </div>
    </div>
</div>