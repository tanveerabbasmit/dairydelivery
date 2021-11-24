<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="<?= Yii::app()->theme->baseUrl ?>/js/table/taza_farm_payment_style.css">
    <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet'>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>


<body style="background-image: linear-gradient(288deg, #36b34b 0%, #1cad68 30%, #01a685 100%);">

<div class="container">
    <div style="padding: 10px">
        <section id="contact">
            <div class="container  panel-default" >
                <h3 class="text-center text-uppercase">Quality Report</h3>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 my-5">
                        <div class="card border-0">
                            <div class="card-body text-center">
                                 <table class="table" style="width: 100%;margin-top: 5px">
                                     <tr>
                                         <th style="color: white;">#</th>
                                         <th style="color: white;">Quality</th>
                                         <th style="color: white;text-align: center">Value</th>
                                     </tr>
                                     <?php forEach($data as $key=>$value ){ ?>
                                         <tr>

                                             <td style="color: white;"><?=$key+1?></td>
                                             <td style="color: white;"><?=$value['quality_name']?></td>

                                             <td style="color: white;text-align: center"><?=$value['quantity_value']?></td>
                                         </tr>
                                     <?php } ?>
                                 </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

</body>

</html>
