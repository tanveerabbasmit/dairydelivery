<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/table/taza_farm_payment_style.css">
    <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet'>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>


<body style="background-image: linear-gradient(288deg, #36b34b 0%, #1cad68 30%, #01a685 100%);">

<div class="container">
    <div style="padding: 10px">
        <section id="contact">
            <div class="container  panel-default" style="color: <?php echo $data['color']; ?> ;">
                <h3 style="" class="text-center text-uppercase">Message</h3>
                <div class="row" style="">
                    <div class="col-sm-12 col-md-12 col-lg-12 my-5">
                        <div class="card border-0">
                            <div class="card-body text-center">
                                <i style="color: <?php echo $data['color']; ?>" class="<?php echo $data['icone']; ?> fa-5x mb-3" aria-hidden="true"></i>
                                <h4 style="color: white" class="text-uppercase mb-5"><?php echo $data['message']; ?></h4>
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