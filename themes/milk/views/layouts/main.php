<style>
    @media (min-width: 992px) {
        #all_menu_div {
            display: none;
        }
    }


    @media (max-width: 992px) {
        #all_heading_div {
            display: none;
        }
    }


    .notification {
        background-color: #555;
        color: white;
        text-decoration: none;
    }

    .notification:hover {
        background: red;
    }

    .notification .badge {

        position: absolute;
        top: -10px;
        right: -10px;
        padding: 5px 10px;
        border-radius: 50%;
        background: red;
        color: white;
    }

</style>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Dairy Milk System </title>
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/fontawesome/css/font-awesome.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/weather-icons/css/weather-icons.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/jquery-toggles/toggles-full.css">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/angularDatePicker/angularjs-datetime-picker.css" rel="stylesheet">
    <!--toggle-->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/toggle/angular-toggle-switch-bootstrap-2.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/milk.css">
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/modernizr/modernizr.js"></script>

    <?php

    if(Yii::app()->user->getId()==null) {
        $this->redirect(array('/site/login'));
    }



    ?>


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
    <!--toggle-->
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/toggle/angular-sanitize.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/toggle/angular-toggle-switch.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/toggle/angular-toggle-switch.min.js"></script>


</head>

<body>

<?php

$roleID =  Yii::app()->user->getState('role_id');
$user_id =  Yii::app()->user->getState('user_id');

$company_branch_id =  Yii::app()->user->getState('company_branch_id');

$query = "Select IFNULL(COUNT(c.login_form) ,0) as sumlaptop 
         from client as c
         where c.company_branch_id ='1'  and c.view_by_admin = 1";

$query_Result_laptopsum= Yii::app()->db->createCommand($query)->queryscalar();
$query_Result_laptopsum='';

$user_object =User::model()->findByPk($user_id);
$supper_admin_user  =$user_object['supper_admin_user'];

$company_object = Company::model()->findByPk($company_branch_id);

$order_hub_name = $company_object['order_hub_name'];

if($supper_admin_user>0){
    $sql = "SELECT rm.* from module_action_role as rm 
               ORDER BY rm.order_by   ";
}else{
    $sql = "SELECT mar.* from role_muduleactionrole as rm
                      LEFT JOIN module_action_role as mar ON mar.module_action_role_id = rm.module_action_role_id
                      where rm.role_id = $roleID 
                      ORDER BY mar.order_by ";
}



/*     $sql = "SELECT mar.* from role_muduleactionrole as rm
          LEFT JOIN module_action_role as mar ON mar.module_action_role_id = rm.module_action_role_id
          left join menu_arrange as ma ON ma.module_action_role_id = mar.module_action_role_id and ma.company_id = '$company_branch_id'
            where rm.role_id = $roleID
           order by ma.order_number DESC, mar.module_action_role_id ASC ";*/
// and rm.module_action_role_id !=8 and rm.module_action_role_id !=11
$menuList = Yii::app()->db->createCommand($sql)->queryAll();



$manuArray = array();
$manuArray[] =  array('label' => '<i class="fa fa-tachometer"></i>Dashboard', 'url' => $this->createAbsoluteUrl('/site/index'), 'visible' => true, 'icons' => 'fa-home');
foreach ($menuList as $value){
    if($value['module']){
        $OneMenuArray = array();
        $urlArray =array();
        $urlString= "/".$value['module']."/".$value['action'];
        $urlArray[] =$urlString;

        $OneMenuArray['label'] ="<i class='".$value['icon']."'></i>".$value['menu_name'];
        $OneMenuArray['url'] = $urlArray;
        $OneMenuArray['visible'] = true;
        $OneMenuArray['icons'] = 'fa-home';

        $manuArray[] = $OneMenuArray ;
    }

}

?>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/paymentterm/main_file_grid.js"></script>
<nav class=" headerpanel navbar navbar-default"  >


    <style>

        @media (max-width: 768px){
            #ubove_portion{
                display: none;
            }
        }

    </style>

    <div id="ubove_portion" class="container-fluid" style="background-color: #5f5f5f;height: 30px">



        <ul class="nav navbar-nav">
            <li  style="padding: 5px;height: 28px">

                <a style="margin:0px;padding: 5px;color: #259dab;" class="navbar-brand" href="http://dairydelivery.conformiz.com"><?php echo Yii::app()->name; ?></a>

            </li>

        </ul>

        <ul class="nav navbar-nav navbar-right">


            <style>
                .dropbtn {
                    background-color: #4CAF50;
                    color: white;
                    padding: 16px;
                    font-size: 16px;
                    border: none;
                    cursor: pointer;
                }

                .dropbtn:hover, .dropbtn:focus {
                    background-color: #3e8e41;
                }

                #myInput {
                    box-sizing: border-box;
                    background-image: url('searchicon.png');
                    background-position: 14px 12px;
                    background-repeat: no-repeat;
                    font-size: 16px;
                    padding: 14px 20px 12px 45px;
                    border: none;
                    border-bottom: 1px solid #ddd;
                }

                #myInput:focus {outline: 3px solid #ddd;}

                .dropdown {
                    position: relative;
                    display: inline-block;
                }

                .dropdown-content {
                    display: none;
                    position: absolute;
                    background-color: #f6f6f6;
                    min-width: 230px;
                    overflow: auto;
                    border: 1px solid #ddd;
                    z-index: 1;
                }

                .dropdown-content a {
                    color: black;
                    padding: 12px 16px;
                    text-decoration: none;
                    display: block;
                }

                .dropdown a:hover {background-color: #ddd;}

                .show {display: block;}
            </style>

            <li class="dropdown" style="padding:8px;height: 28px;margin-left: 4px;margin-right: 5px">

                <a href="#" onclick="complain_function(0)" style="text-transform:capitalize;margin:0px;padding: 5px;color: white;" class="notification dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <span> <i class="fa fa-envelope"></i></span>
                    <span id="complain_total" class="badge"></span>
                </a>

                <ul  id='' class="dropdown-menu" style="width: 260px; max-height: 600px;overflow: scroll" >
                    <?php
                      $list = dashbord_graph_data::get_new_complain_list();
                      foreach ($list as $value){
                    ?>
                    <li style="">
                        <div class="col-lg-12" style="border-bottom: 1px dotted #008B8B;padding: 5px;background-color: #F5FFFA">
                            <a href="">
                                <div class="col-lg-2 col-sm-2 col-2 text-center">
                                    <i class="fa fa-envelope" style="font-size:20px;color:#5F9EA0"></i>
                                </div>
                                <div class="col-lg-9 col-sm-9 col-9">
                                    <strong class="text-info"><?=$value['fullname']?></strong>
                                    <br>
                                    <small class="text-warning"><?=$value['name']?></small>
                                    <br>
                                    <small class="text-warning"><?=$value['status_name']?></small>
                                </div>
                            </a>
                        </div>
                    </li>
                    <?php }?>
                </ul>
            </li>

            <li class="dropdown" style="padding:8px;height: 28px;margin-left: 4px;margin-right: 5px">

                <a href="#" onclick="notification_function(0)" style="text-transform:capitalize;margin:0px;padding: 5px;color: white;" class="notification dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <span> <i class="fa fa-user"></i></span>
                    <span id="customer_total" class="badge"></span>
                </a>

                <ul  id='' class="dropdown-menu" style="width: 260px; max-height: 600px;overflow: scroll" >

                    <?php
                        $list  = dashbord_graph_data::get_new_customer_notification(1);
                        foreach ($list as $value){
                    ?>
                      <li style="">
                        <div class="col-lg-12" style="border-bottom: 1px dotted #008B8B;padding: 5px;background-color: #F5FFFA">
                            <a href="">
                                <div class="col-lg-2 col-sm-2 col-2 text-center">
                                    <i class="fa fa-user" style="font-size:24px;color:#5F9EA0"></i>
                                </div>
                                <div class="col-lg-9 col-sm-9 col-9">
                                    <strong class="text-info"><?=$value['fullname']?></strong>
                                    <br>
                                    <small class="text-warning"><?=$value['created_at']?></small>
                                </div>
                            </a>
                        </div>
                    </li>
                    <?php }?>

                </ul>
            </li>

            <li class="dropdown" style="padding: 5px;height: 28px">
                <div class="dropdown" >
                    <a onclick="myFunction()" href="#" style=" color:#259dab;font-size:18px;text-transform:capitalize;margin:0px;padding: 5px;color: white;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span style="color:#259dab"> All Menu</span>
                        <span class="caret"></span></a>
                    <div style="max-height: 500px"  id="myDropdown" class="dropdown-content">
                        <button onclick="myFunction()" style="width: 100%">Close</button>
                        <input autocomplete="false" type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">



                        <?php
                        foreach ($menuList as $value){
                            $modelActionName =$value['module']."/".$value['action'];
                            $achorName = Yii::app()->createUrl($modelActionName);
                            $iconeName = $value['icon'];
                            $linkName = $value['menu_name'];


                            echo "<a href=";
                            echo  $achorName;
                            echo "><i class='$iconeName'></i> &nbsp&nbsp $linkName</a>";


                        }
                        ?>

                    </div>
                </div>

                <script>
                    /* When the user clicks on the button,
                    toggle between hiding and showing the dropdown content */
                    function myFunction() {
                        document.getElementById("myDropdown").classList.toggle("show");
                    }

                    function filterFunction() {
                        var input, filter, ul, li, a, i;
                        input = document.getElementById("myInput");
                        filter = input.value.toUpperCase();
                        div = document.getElementById("myDropdown");
                        a = div.getElementsByTagName("a");
                        for (i = 0; i < a.length; i++) {
                            txtValue = a[i].textContent || a[i].innerText;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                a[i].style.display = "";
                            } else {
                                a[i].style.display = "none";
                            }
                        }
                    }
                </script>


            </li>


            <li class="dropdown" style="padding: 5px;height: 28px">
                <a href="#" style="text-transform:capitalize;margin:0px;padding: 5px;color: white;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    Business Summary
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">

                    <li><a href="<?php echo   Yii::app()->createUrl('company/business_summary_stock')  ?>"><i class="fa fa-bar-chart"></i> &nbsp &nbsp Business Summary Stock</a></li>
                    <li><a href="<?php echo   Yii::app()->createUrl('pos/Business_summary_sale')  ?>"><i class="fa fa-bar-chart"></i>&nbsp &nbsp Sale</a></li>
                    <li><a href="<?php echo   Yii::app()->createUrl('product_purchase_summary/product_purchase_summary_report_view_all')  ?>"><i class="fa fa-bar-chart"></i> &nbsp &nbspPurchase</a></li>
                    <li><a href="<?php echo   Yii::app()->createUrl('BillFromVendor/vendor_bills')  ?>"><i class="fa fa-bar-chart"></i>&nbsp &nbspBills</a></li>
                    <li><a href="<?php echo   Yii::app()->createUrl('ExpenceReport/expenses_summary')  ?>"><i class="fa fa-bar-chart"></i> &nbsp &nbsp Expenses</a></li>
                    <li><a href="<?php echo   Yii::app()->createUrl('profitloss/Profitloss_view')  ?>"><i class="fa fa-bar-chart"></i> &nbsp &nbsp Profit Loss View</a></li>
                    <li><a href="<?php echo   Yii::app()->createUrl('Salespurchasesummary/salespurchasesummary_view')  ?>"><i class="fa fa-bar-chart"></i> &nbsp &nbsp Sales Purchase Summary</a></li>
                    <li><a target="_blank" href="<?php echo   Yii::app()->createUrl('Mobiledesign/Stocksummary_view')  ?>"><i class="fa fa-bar-chart"></i> &nbsp &nbsp Mobile view Stock</a></li>

                </ul>
            </li>


            <li class="dropdown" style="padding: 5px;height: 28px">
                <a href="#" style="text-transform:capitalize;margin:0px;padding: 5px;color: white;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    Export
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo   Yii::app()->createUrl('importMasterData/refreshData/company_wise_export')  ?>"><i class="fa fa-bar-chart"></i> 	&nbsp;Export Client List</a></li>
                    <li><a href="<?php echo   Yii::app()->createUrl('export_payment_master/export_payment_master_view')  ?>"><i class="fa fa-bar-chart"></i> 	&nbsp;Export Payment List</a></li>
                    <li><a href="<?php echo   Yii::app()->createUrl('Riderwisecustomer/riderwisecustomer_list')  ?>"><i class="fa fa-bar-chart"></i> 	&nbsp; Customer Route</a></li>
                    <li><a  target="_blank" href="<?php echo   Yii::app()->createUrl('Riderwisecustomer/customer_schedule ')  ?>"><i class="fa fa-bar-chart"></i> 	&nbsp; Customer Schedule</a></li>
                </ul>
            </li>
            <li class="dropdown" style="padding: 5px;height: 28px">
                <a href="#" style="text-transform:capitalize;margin:0px;padding: 5px;color: white;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    POS
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <?php
                    foreach ($menuList as $value){
                        $modelActionName =$value['module']."/".$value['action'];
                        $achorName = Yii::app()->createUrl($modelActionName);
                        $iconeName = $value['icon'];
                        $linkName = $value['menu_name'];
                        if($value['viewPart']==9){
                            echo "<li>";
                            echo "<a href=";
                            echo  $achorName;
                            echo "><i class='$iconeName'></i> &nbsp&nbsp $linkName</a>";
                            echo "</li>";
                        }
                    }
                    ?>
                    <li><a href="http://ordershub.conformiz.com/index.php?r=product%2Fproduct_view&id=<?php echo $order_hub_name; ?>"><i class="fa fa-credit-card"></i> &nbsp;&nbsp; Orders Hub</a></li>
                </ul>
            </li>
            <li class="dropdown" style="padding: 5px;height: 28px">
                <a href="#" style="text-transform:capitalize;margin:0px;padding: 5px;color: white;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    Sys Admin
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <?php
                    foreach ($menuList as $value){
                        $modelActionName =$value['module']."/".$value['action'];
                        $achorName = Yii::app()->createUrl($modelActionName);
                        $iconeName = $value['icon'];
                        $linkName = $value['menu_name'];
                        if($value['viewPart'] ==10){
                            echo "<li>";
                            echo "<a href=";
                            echo  $achorName;
                            echo "><i class='$iconeName'></i> &nbsp&nbsp $linkName</a>";
                            echo "</li>";
                        }
                    }
                    ?>
                </ul>
            </li>

            <?php
            $company_id = Yii::app()->user->getState('company_branch_id');
            $company_object = Company::model()->findByPk($company_id);
            if($company_object['show_accounting']==1){
                ?>

                <li class="dropdown" style="padding: 5px;height: 28px">
                    <a href="#" style="text-transform:capitalize;margin:0px;padding: 5px;color: white;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Accounting
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo   Yii::app()->createUrl('account/accounting/product_sale')  ?>"><i class="fa fa-credit-card"></i>  Sale voucher</a></li>
                        <li><a href="<?php echo   Yii::app()->createUrl('account/accounting/Receipt_from_customer')  ?>"><i class="fa fa-credit-card"></i>  Payment voucher </a></li>
                        <li><a href="<?php echo   Yii::app()->createUrl('account/accounting/Purchase_voucher')  ?>"><i class="fa fa-credit-card"></i>  Purchase Voucher </a></li>
                        <li><a href="<?php echo   Yii::app()->createUrl('account/Assignaccount/VoucherAccount')  ?>"><i class="fa fa-credit-card"></i> Assign Account </a></li>

                    </ul>
                </li>

            <?php } ?>

            <li class="dropdown" style="padding: 5px;height: 28px">
                <a href="#" style="margin:0px;padding: 5px;color: white;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <?php echo  Yii::app()->user->getState('full_name');  ?>
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">

                    <li><a href="<?php echo Yii::app()->createUrl('companymessage/change_sms_option')?>""><i class="glyphicon glyphicon-cog"></i> Enable/Disable SMS Option <span href="#" class="notification"><span class="badge"><?php echo $query_Result_laptopsum ?></span></span></a></li>
                    <li><a href="<?php echo Yii::app()->createUrl('')?>"><i class="glyphicon glyphicon-cog"></i> Dashbord </a></li>
                    <li><a href="<?php echo Yii::app()->createUrl('site/changePassword')?>"><i class="glyphicon glyphicon-cog"></i> Change Password </a></li>
                    <li><a href=""><i class="glyphicon glyphicon-cog"></i> Account Settings</a></li>
                    <li><a href="<?php echo Yii::app()->createUrl('site/logout')  ?>"><i class="glyphicon glyphicon-log-out"></i> Log Out</a></li>
                </ul>
            </li>



        </ul>
    </div>
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>


        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">

                <li class="dropdown" style="">
                    <a href="#" style="text-transform:capitalize;padding-left:8px;padding-right: 8px;  color: white" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Management<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php
                        foreach ($menuList as $value){
                            $modelActionName =$value['module']."/".$value['action'];
                            $achorName = Yii::app()->createUrl($modelActionName);
                            $iconeName = $value['icon'];
                            $linkName = $value['menu_name'];
                            if($value['viewPart'] ==1){
                                echo "<li>";
                                echo "<a  href=";
                                echo  $achorName;
                                echo "><i class='$iconeName'></i> &nbsp&nbsp$linkName</a>";
                                echo "</li>";
                            }
                        }
                        ?>
                    </ul>



                </li>

                <li class="dropdown">
                    <a href="#" style="text-transform:capitalize;padding-left:8px;padding-right: 8px;color: white" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Ledger Activity<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php
                        foreach ($menuList as $value){
                            $modelActionName =$value['module']."/".$value['action'];
                            $achorName = Yii::app()->createUrl($modelActionName);
                            $iconeName = $value['icon'];
                            $linkName = $value['menu_name'];
                            if($value['viewPart'] ==2){
                                echo "<li>";
                                echo "<a href=";
                                echo  $achorName;
                                echo "><i class='$iconeName'></i> &nbsp&nbsp$linkName</a>";
                                echo "</li>";
                            }
                        }
                        ?>
                    </ul>



                </li>
                <li class="dropdown">
                    <a href="#" style="text-transform:capitalize;padding-left:8px;padding-right: 8px;color: white" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Collection & Recovery<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php
                        foreach ($menuList as $value){
                            $modelActionName =$value['module']."/".$value['action'];
                            $achorName = Yii::app()->createUrl($modelActionName);
                            $iconeName = $value['icon'];
                            $linkName = $value['menu_name'];
                            if($value['viewPart'] ==3){
                                echo "<li>";
                                echo "<a href=";
                                echo  $achorName;
                                echo "><i class='$iconeName'></i> &nbsp&nbsp$linkName</a>";
                                echo "</li>";
                            }
                        }
                        ?>
                    </ul>



                </li>
                <li class="dropdown">
                    <a href="#" style="text-transform:capitalize;padding-left:8px;padding-right: 8px;color: white" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Stock  & Payment<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php
                        foreach ($menuList as $value){
                            $modelActionName =$value['module']."/".$value['action'];
                            $achorName = Yii::app()->createUrl($modelActionName);
                            $iconeName = $value['icon'];
                            $linkName = $value['menu_name'];
                            if($value['viewPart'] ==4){
                                echo "<li>";
                                echo "<a href=";
                                echo  $achorName;
                                echo "><i class='$iconeName'></i> &nbsp&nbsp$linkName</a>";
                                echo "</li>";
                            }
                        }
                        ?>
                    </ul>



                </li>

                <li class="dropdown">
                    <a href="#" style="text-transform:capitalize;padding-left:8px;padding-right: 8px;color: white" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Sale
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php
                        foreach ($menuList as $value){
                            $modelActionName =$value['module']."/".$value['action'];
                            $achorName = Yii::app()->createUrl($modelActionName);
                            $iconeName = $value['icon'];
                            $linkName = $value['menu_name'];
                            if($value['viewPart'] ==5){
                                echo "<li>";
                                echo "<a href=";
                                echo  $achorName;
                                echo "><i class='$iconeName'></i> &nbsp&nbsp$linkName</a>";
                                echo "</li>";
                            }
                        }
                        ?>
                    </ul>



                </li>


                <li class="dropdown">
                    <a href="#" style="text-transform:capitalize;padding-left:8px;padding-right: 8px;color: white" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        QA Management
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php
                        foreach ($menuList as $value){
                            $modelActionName =$value['module']."/".$value['action'];
                            $achorName = Yii::app()->createUrl($modelActionName);
                            $iconeName = $value['icon'];
                            $linkName = $value['menu_name'];
                            if($value['viewPart'] ==6){
                                echo "<li>";
                                echo "<a href=";
                                echo  $achorName;
                                echo "><i class='$iconeName'></i> &nbsp&nbsp$linkName</a>";
                                echo "</li>";
                            }
                        }
                        ?>
                    </ul>



                </li>

                <!-- <li class="dropdown">
                            <a href="#" style="text-transform:capitalize;padding-left:8px;padding-right: 8px;color: white" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                General Purchase & Expenses
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <?php
                /*                                foreach ($menuList as $value){
                                                    $modelActionName =$value['module']."/".$value['action'];
                                                    $achorName = Yii::app()->createUrl($modelActionName);
                                                    $iconeName = $value['icon'];
                                                    $linkName = $value['menu_name'];
                                                    if($value['viewPart'] ==7){
                                                        echo "<li>";
                                                        echo "<a href=";
                                                        echo  $achorName;
                                                        echo "><i class='$iconeName'></i> &nbsp&nbsp$linkName</a>";
                                                        echo "</li>";
                                                    }
                                                }
                                                */?>
                            </ul>



                        </li>-->

                <li class="dropdown">
                    <a href="#" style="text-transform:capitalize;padding-left:8px;padding-right: 8px;color: white" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Marketing Activity
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php
                        foreach ($menuList as $value){
                            $modelActionName =$value['module']."/".$value['action'];
                            $achorName = Yii::app()->createUrl($modelActionName);
                            $iconeName = $value['icon'];
                            $linkName = $value['menu_name'];
                            if($value['viewPart'] ==8){
                                echo "<li>";
                                echo "<a href=";
                                echo  $achorName;
                                echo "><i class='$iconeName'></i> &nbsp&nbsp$linkName</a>";
                                echo "</li>";
                            }
                        }
                        ?>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" style="text-transform:capitalize;padding-left:8px;padding-right: 8px;color: white" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Payment(Bill/Receipt)
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php
                        foreach ($menuList as $value){
                            $modelActionName =$value['module']."/".$value['action'];
                            $achorName = Yii::app()->createUrl($modelActionName);
                            $iconeName = $value['icon'];
                            $linkName = $value['menu_name'];
                            if($value['viewPart'] ==11){
                                echo "<li>";
                                echo "<a href=";
                                echo  $achorName;
                                echo "><i class='$iconeName'></i> &nbsp&nbsp$linkName</a>";
                                echo "</li>";
                            }
                        }
                        ?>
                    </ul>
                </li>





            </ul>


        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>



<section>



    <div class="mainpanel abcd"  style="margin-left: 10px;margin-right: 10px ;margin-top: 20px">

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


<script>
    //var base_url = 'http://localhost/dairydelivery';
    var base_url = 'https://dairydelivery.conformiz.com';
    var page_number =-1;
    var page_number_com =-1;

    // notification_function();
    function notification_function(flag) {


        if(flag>0){
            page_number = page_number+1;
            $("#view_more_id").remove();
        }else {
            $("#new_notification").empty();
            page_number = 0;
        }



        $.get(
            base_url+"/notification/get_new_cutomer_notification",
            {page : page_number},
            function(data) {

                $("#new_notification").append(data);

            }
        );
    }
    function view_more(){
        notification_function(1);
        document.getElementById("new_notification").style.display = 'block';
    }
    get_total_new_customer();
    setInterval(function(){
        get_total_new_customer();
        get_total_new_complain();
    }, 10000);

    function get_total_new_customer(){
        $.get(

            base_url+"/notification/total_new_customer",

            {type :1},
            function(data) {
                document.getElementById("customer_total").innerHTML = data;

            }
        );

    }

    /*complain_function*/

    function complain_function(flag) {

        if(flag>0){
            page_number_com = page_number_com+1;
            $("#view_more_id").remove();
        }else {
            $("#new_notification").empty();
            page_number_com = 0;
        }

        $.get(
            base_url+"/notification/get_new_complain_notification",
            {page : page_number_com},
            function(data) {

                $("#new_complain").append(data);

            }
        );
    }

    function view_more_complain(){

        complain_function(1);
        document.getElementById("new_complain").style.display = 'block';

    }

    function get_total_new_complain(){
        $.get(
            base_url+"/notification/total_new_customer",
            {type :2},
            function(data) {
                document.getElementById("complain_total").innerHTML = data;
            }
        );

    }
</script>
