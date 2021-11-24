<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/table/taza_farm_payment_style.css">
<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet'>
<style>

</style>
<html lang="en">

<head>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>


<body style="background:transparent">




<div class="container " >
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
                            <p>
                                 <?php if($data['color']=='red'){ ?>
                                   <a href="<?php echo $data['url'].'&ru='.$ru; ?>" style="color:red"  class="btn btn-secondary btn-lg btn-block">Try Again </a>
                                <?php }else{ ?>

                                         <?php if(empty($ru)){ ?>
                                             <a href="javascript:history.back(5)" style="color:white"  class="btn btn-secondary
                                              btn-lg btn-block">Back</a>
                                         <?php }else{ ?>
                                             <a href="<?php echo $ru ?>" style="color:white"  class="btn btn-secondary
                                                  btn-lg btn-block">Back</a>
                                         <?php } ?>

                                <?php } ?>
                            </p>
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



