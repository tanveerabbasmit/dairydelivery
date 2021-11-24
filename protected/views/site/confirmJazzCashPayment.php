
<?php
date_default_timezone_set("Asia/Karachi");
$post = file_get_contents("php://input");
$data = $_REQUEST;
  var_dump($data);
echo "<br>";
$company_id = $data['ppmpf_2'];
 $clientObject = Client::model()->findByPk(intval($client_id));
  echo   "<h1>".$clientObject['fullname']."</h1>";

  $companyObject =Company::model()->findByPk(intval($company_id));
echo   "<h1>".$companyObject['company_name']."</h1>";
?>

<style>

    .container {
        background-color: #6495ED ;
        height: 100%;
    }

    hr.star-light,
    hr.star-primary {
        margin: 25px auto 30px;
        padding: 0;
        max-width: 250px;
        border: 0;
        border-top: solid 5px;
        text-align: center;
    }

    hr.star-light:after,
    hr.star-primary:after {

        display: inline-block;
        position: relative;
        top: -.8em;
        padding: 0 .25em;

        font-size: 2em;
    }

    hr.star-light {
        border-color: #fff;
    }

    hr.star-light:after {
        color: #fff;
        background-color: #18bc9c;
    }

    hr.star-primary {
        border-color: #2c3e50;
    }

    hr.star-primary:after {
        color: #2c3e50;
        background-color: #18bc9c;
    }

</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body style="background-color: ##FFEBCD">
<div class="container">
    <div style="background-color: #708090" class="row">
        <h3 style="color: white; text-align: center">THANKS</h3>
    </div>
    <div class="row">

        <div class="col-md-12">

          <hr class="star-light">

            <h3 style="color: white; text-align: center">


                <?php


                    if($data['pp_ResponseCode']=='000'){
                        echo ' <h3 style="color: white; text-align: center"> Your transaction has been processed successfully</h3>';
                    }else{
                        echo '<h3 style="color: white; text-align: center">Your transaction has been declined </h3><h5 style="color: white; text-align: center">
                             Reason:'.$data['pp_ResponseMessage'].'</h5>';
                    }

                ?>


        </div>

        <div class="col-md-12">
           <hr class="star-primary">
        </div>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>