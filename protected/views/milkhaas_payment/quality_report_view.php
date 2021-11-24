

<style>
    body, html {

    }

    .bg {
        /* The image used */
        background-image: url("http://dairypayments.conformiz.com/img/background_picture.png");

        /* Full height */
        height: 100%;

        /* Center and scale the image nicely */
        background-position: center;
        background-repeat: repeat-x;
        background-size: cover;
    }
</style>
<html lang="en"><head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>


<body class="bg">

 <div class="container" >
     <div style="background-color: #9ab082;" class="row">
         <div class="col-sm-12" style="margin-right:40%">
             <h3>QUALITY REPORT</h3>
         </div>
     </div>

        <?php foreach ($final_data as $value){?>

            <div style="background-color: CadetBlue;margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
                <div  class="col-xs-6 col-sm-4" style="font-weight: bold;">Full Name:</div>
                <div  class="col-xs-6 col-sm-4"><?= $value['product_name']; ?></div>
            </div>


            <div style="background-color: #9ab082;margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
                <div  class="col-xs-6 col-sm-4" style="font-weight: bold;">Date:</div>
                <div  class="col-xs-6 col-sm-4"><?= $value['date']; ?></div>
            </div>

            <div style="background-color: #9ab082;margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
                <div  class="col-xs-6 col-sm-4" style="font-weight: bold;">Protein:</div>
                <div  class="col-xs-6 col-sm-4"><?= $value['protein']; ?></div>
            </div>

            <div style="background-color: #9ab082;margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
                <div  class="col-xs-6 col-sm-4" style="font-weight: bold;">Lactose:</div>
                <div  class="col-xs-6 col-sm-4"><?= $value['lactose']; ?></div>
            </div>


            <div style="background-color: #9ab082;margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
                <div  class="col-xs-6 col-sm-4" style="font-weight: bold;">Fat:</div>
                <div  class="col-xs-6 col-sm-4"><?= $value['fat']; ?></div>
            </div>

            <div style="background-color: #9ab082;margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
                <div  class="col-xs-6 col-sm-4" style="font-weight: bold;">Salt:</div>
                <div  class="col-xs-6 col-sm-4"><?= $value['salt']; ?></div>
            </div>

            <div style="background-color: #9ab082;margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
                <div  class="col-xs-6 col-sm-4" style="font-weight: bold;">Density:</div>
                <div  class="col-xs-6 col-sm-4"><?= $value['adulterants']; ?></div>
            </div>


            <div style="background-color: #9ab082;margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
                <div  class="col-xs-6 col-sm-4" style="font-weight: bold;">Antiboitics:</div>
                <div  class="col-xs-6 col-sm-4"><?= $value['antiboitics']; ?></div>
            </div>





        <?php }?>

     <nav class="row" >
         <ul class="pagination pagination-lg">
             <?php foreach ($date_object as $value){ ?>
                 <li class="page-item ">
                     <a class="page-link" href="<?php echo Yii::app()->baseUrl; ?>/Milkhaas_payment/Quality_report_view?today=<?php echo $value; ?>" tabindex="-1"><?php echo $value; ?></a>
                 </li>
             <?php }?>

         </ul>
     </nav>

</div>

</body>
</html>



