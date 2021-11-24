

<style>
    body, html {

    }

    .bg {
        /* The image used */
        background-image: url("https://dairypayments.conformiz.com/img/background_picture.png");

        /* Full height */
        height: 100%;

        /* Center and scale the image nicely */
        background-position: center;
        background-repeat: repeat-x;
        background-size: cover;



    }
</style>
<html lang="en"><head>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>


<body class="bg">




<div class="container" >
    <section id="contact">
        <div class="container panel panel-default" style="color: <?php echo $data['color']; ?> ;background-color: Gainsboro">
            <h3 style="" class="text-center text-uppercase">Success</h3>

            <div class="row" style="background-color: #9ab082;">
                <div class="col-sm-12 col-md-12 col-lg-12 my-5">
                    <div class="card border-0">
                        <div class="card-body text-center">
                            <i style="color: <?php echo $data['color']; ?>" class="<?php echo $data['icone']; ?> fa-5x mb-3" aria-hidden="true"></i>
                            <h4 class="text-uppercase mb-5"><?php echo $data['message']; ?></h4>
                            <!--<p>
                                <button style="color:<?php /*echo $data['color']; */?>" type="button" class="btn btn-secondary btn-lg btn-block"></button>
                            </p>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



</body>
</html>



