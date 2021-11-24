<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<title>Dairy Milk System</title>

<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/fontawesome/css/font-awesome.css">
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/weather-icons/css/weather-icons.css">
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/jquery-toggles/toggles-full.css">
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/angularDatePicker/angularjs-datetime-picker.css" rel="stylesheet">

<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/milk.css">
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/modernizr/modernizr.js"></script>



   
  <!-- <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/Hover/hover.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/fontawesome/css/font-awesome.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/weather-icons/css/weather-icons.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/ionicons/css/ionicons.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/jquery-toggles/toggles-full.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/morrisjs/morris.css">

  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/milk.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/main.css">
  <script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/modernizr/modernizr.js"></script>

  <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/milk.js"></script>
  <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/dashboard.js"></script>
  <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/angular.min.js"></script>
  <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/angular-sanitize.js"></script>
  
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/jquery.steps/jquery.steps.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/timepicker/jquery.timepicker.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/timepicker/jquery.timepicker.css"> -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/angular.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/jquery/jquery.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/jquery-ui/jquery-ui.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/bootstrap/js/bootstrap.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/jquery-toggles/toggles.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/angularDatePicker/angularjs-datetime-picker.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/milk.js"></script>
    
</head>

<body>

<header>
  <div class="headerpanel">

    <div class="logopanel">
      <h2><a href="<?php echo Yii::app()->baseUrl; ?>"><?php echo Yii::app()->name; ?></a></h2>
    </div><!-- logopanel -->

    <div class="headerbar">

     

      <div class="header-right">
        <ul class="headermenu">
          <li>
             
          </li>
          <li>
            <div class="btn-group">
              <button type="button" class="btn btn-logged" data-toggle="dropdown">
                    <i class="fa fa-cogs"></i>
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu pull-right">
                <li><a href=""><i class="glyphicon glyphicon-user"></i> My Profile</a></li>
                <li><a href=""><i class="glyphicon glyphicon-cog"></i> Account Settings</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/logout')  ?>"><i class="glyphicon glyphicon-log-out"></i> Log Out</a></li>
              </ul>
            </div>
          </li>

        </ul>
      </div><!-- header-right -->
    </div><!-- headerbar -->
  </div><!-- header-->
</header>

<section>

  <div class="leftpanel">
    <div class="leftpanelinner">

      <!-- ################## LEFT PANEL PROFILE ################## -->

      <div class="media leftpanel-profile">
        <div class="media-left">
          <a href="#">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/avatar.png" alt="" class="media-object img-circle">
          </a>
        </div>
        <div class="media-body">
          <h4 class="media-heading">Admin <a data-toggle="collapse" data-target="#loguserinfo" class="pull-right"><i class="fa fa-angle-down"></i></a></h4>
        </div>
      </div><!-- leftpanel-profile -->

      <div class="leftpanel-userinfo collapse" id="loguserinfo">
        <h5 class="sidebar-title">Address</h5>
        <address>
          4975 Cambridge Road
          Miami Gardens, FL 33056
        </address>
        <h5 class="sidebar-title">Contact</h5>
        <ul class="list-group">
          <li class="list-group-item">
            <label class="pull-left">Email</label>
            <span class="pull-right">me@themepixels.com</span>
          </li>
          <li class="list-group-item">
            <label class="pull-left">Home</label>
            <span class="pull-right">(032) 1234 567</span>
          </li>
          <li class="list-group-item">
            <label class="pull-left">Mobile</label>
            <span class="pull-right">+63012 3456 789</span>
          </li>
          <li class="list-group-item">
            <label class="pull-left">Social</label>
            <div class="social-icons pull-right">
              <a href="#"><i class="fa fa-facebook-official"></i></a>
              <a href="#"><i class="fa fa-twitter"></i></a>
              <a href="#"><i class="fa fa-pinterest"></i></a>
            </div>
          </li>
        </ul>
      </div><!-- leftpanel-userinfo -->

      <ul class="nav nav-tabs nav-justified nav-sidebar">
        <li class="tooltips active" data-toggle="tooltip" title="Main Menu"><a data-toggle="tab" data-target="#mainmenu"><i class="tooltips fa fa-ellipsis-h"></i></a></li>
        <li class="tooltips" data-toggle="tooltip" title="Settings"><a data-toggle="tab" data-target="#settings"><i class="fa fa-cog"></i></a></li>
        <li class="tooltips" data-toggle="tooltip" title="Log Out"><a href="<?php echo Yii::app()->createUrl('site/logout')  ?>"><i class="fa fa-sign-out"></i></a></li>
      </ul>

      <div class="tab-content">

        <!-- ################# MAIN MENU ################### -->

        <div class="tab-pane active" id="mainmenu">
            <?php
            $roleID =  Yii::app()->user->getState('company_branch_id');


            $sql = "SELECT a.action_id, a.action_name, a.action_key , mar.menu_name, m.module_name, m.module_key , mar.icon FROM ACTION AS a
                      LEFT JOIN module_action AS ma USING(action_id)
                      INNER JOIN module_action_role AS mar ON mar.module_action_id= ma.module_action_id
                      LEFT JOIN module AS m ON m.module_id= ma.module_id
                      WHERE mar.role_id=$roleID ";
            $menuList = Yii::app()->db->createCommand($sql)->queryAll();
               $manuArray = array();
                foreach ($menuList as $value){
                    $OneMenuArray = array();
                      $urlArray =array();
                      $urlArray ="'/site/index'";

                     $OneMenuArray['label'] = 'Dashboard';
                     $OneMenuArray['url'] = $urlArray;
                     $OneMenuArray['visible'] = true;
                     $OneMenuArray['icons'] = 'fa-home';
                     $manuArray[] = $OneMenuArray ;
                 }




              $this->widget('zii.widgets.CMenu',array(

              		'htmlOptions' => array( 'class' => 'nav nav-pills nav-stacked nav-clinic' ),
                  'encodeLabel' => false,
              		'activeCssClass'=> 'active',
              		'items'=>array(
                        array('label' => '<i class="fa fa-tachometer"></i>Dashboard', 'url' => array('/site/index'), 'visible' => true, 'icons' => 'fa-home'),
                        array('label' => '<i class="fa fa-user"></i>Daily Stock', 'url' => array('/dailyStock/viewDailyStock'), 'visible' => true),
                        array('label' => '<i class="fa fa-user"></i>Rider Daily Stock', 'url' => array('/riderDailyStock/riderStock'), 'visible' => true),
                        array('label' => '<i class="glyphicon glyphicon-map-marker"></i>Manage Zone', 'url' => array('/zone/manageZone'), 'visible' => true),
                        array('label' => '<i class="fa fa-user"></i>Manage Rider', 'url' => array('/rider/manageRider'), 'visible' => true),
                        array('label' => '<i class="fa fa-user"></i>Manage Customer', 'url' => array('/client/manageClient'), 'visible' => true),
                        array('label' => '<i class="fa fa-user"></i>Manage Product', 'url' => array('/product/manageProduct'), 'visible' => true),
                        array('label' => '<i class="fa fa-user"></i>Complain Type', 'url' => array('/ComplainType/manageComplainType'), 'visible' => true),
                        array('label' => '<i class="fa fa-user"></i>Client Complains', 'url' => array('/Complain/manageComplains'), 'visible' => true),
                        array('label' => '<i class="fa fa-shopping-cart"></i>Special Order', 'url' => array('/SpecialOrder/manageSpecialOrder'), 'visible' => true),
                        array('label' => '<i class="fa fa-building-o"></i>Company', 'url' => array('/company/manageCompany'), 'visible' => true),
              		),







              ));




            echo json_encode($oneArray);
            die();


              ?>
        </div> 

        <!-- #################### SETTINGS ################### -->

        <div class="tab-pane" id="settings">
            <?php $this->widget('zii.widgets.CMenu',array(
            		'htmlOptions' => array( 'class' => 'nav nav-pills nav-stacked nav-clinic' ),
                'encodeLabel' => false,
            		'activeCssClass'=> 'active',
            		'items'=>array(
                    array('label' => '<i class="fa fa-tachometer"></i>Medical History Types', 'url' => array('/MedicalHistory/list'), 'visible' => true, 'icons' => 'fa-home'),
                    array('label' => '<i class="fa fa-tachometer"></i>Question Fields', 'url' => array('/MedicalDetailFields/list'), 'visible' => true, 'icons' => 'fa-home'),
            		),
            )); ?>
        </div> 

      </div><!-- tab-content -->

    </div><!-- leftpanelinner -->
  </div><!-- leftpanel -->

  <div class="mainpanel">

    <div class="contentpanel">

      <div class="row">
			<?php echo $content ?>
      </div><!-- row -->

    </div><!-- contentpanel -->

  </div><!-- mainpanel -->

</section>



<!-- <script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/jquery-ui/jquery-ui.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/bootstrap/js/bootstrap.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/jquery-toggles/toggles.js"></script>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/morrisjs/morris.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/raphael/raphael.js"></script>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/flot/jquery.flot.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/flot/jquery.flot.resize.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/flot-spline/jquery.flot.spline.js"></script>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/jquery-knob/jquery.knob.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/jquery.steps/jquery.steps.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/jquery-validate/jquery.validate.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/jquery-maskedinput/jquery.maskedinput.js"></script> -->




</body>
</html>
