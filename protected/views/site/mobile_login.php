<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />

    <meta name="description" content="SEND SMS Service sends sms Messages in bulk. Login now and access 10 Free Credits."/>
    <meta name="keywords" content="free sms credits, sms send, sms messaging, sms services, bulk sms sending, free campaign built,"/>
    <meta name="robots" content="index,follow" />
    <meta name="distribution" content="global" />
    <meta name="rating" content="general" />
    <meta name="google" content="translate" />
    <meta name="copyright" content="2018, SMS SEND" />
    <meta name="revisit-after" content="31 Days" />
    <meta name="expires" content="never" />
    <meta http-equiv="Content-Language" content="en" />

    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pages/login.css" />
    <!-- END PAGE LEVEL STYLES -->
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/custom.css" />
    <link rel="shortcut icon" href="favicon.ico" />

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="/style.css">
    <title>Sign In - Dairy Milk</title>
</head>
<body>
<div id="logreg-forms" style="display: none;">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'login-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>





    <h1 class="h3 mb-3 font-weight-normal" style="margin-top:15px;text-align: center;color: #259dab">Dairy Milk System</h1>
    <h2 class="h3 mb-3 font-weight-normal" style="text-align: center">Welcome! Please sign in</h2>



    <div class="inner-addon left-addon">
        <i class="user_icone fas fa-user-alt"></i>
        <?php echo $form->textField($model,'username',array('class' => 'form-control','placeholder' => 'Enter UserName')); ?>
        <?php echo $form->error($model,'username'); ?>

    </div>

    <div class="col-md-12" style="margin-top: 10px"></div>
    <div class="inner-addon left-addon">
        <i class="user_icone fa fa-lock"></i>
        <?php echo $form->passwordField($model,'password',array('class' => 'form-control','placeholder' => 'Enter Password','id'=>'myInput')); ?>
        <span style="color: green"> <?php echo $form->error($model,'password'); ?></span>
    </div>
    <div class="checkbox">
        <label><input onclick="change_password()" type="checkbox" name="remember">&nbspShow Password</label>
    </div>
    <button class="btn btn-success btn-block" type="submit"><i class="fas fa-sign-in-alt"></i> Login</button>
    <div>
        <a style="float: right" href="http://conformiz.com/"> Forgot Password !!</a>

    </div>

    <div style="margin-bottom: 40px"></div>


    <hr>
    <?php $this->endWidget(); ?>

    <hr>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="/script.js"></script>
</body>
</html>

<style>

    .error{
        color: #b94a48;
    }

    /* sign in FORM */
    #logreg-forms{
        width:412px;
        margin:10vh auto;
        background-color:#f3f3f3;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        transition: all 0.3s cubic-bezier(.25,.8,.25,1);
    }
    #logreg-forms form {
        width: 100%;
        max-width: 410px;
        padding: 15px;
        margin: auto;
    }
    #logreg-forms .form-control:focus { z-index: 2; }
    #logreg-forms .form-signin input[type="email"] {
        margin-bottom: -1px;

    }
    #logreg-forms .form-signin input[type="password"] {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }

    #logreg-forms .social-login{
        width:390px;
        margin:0 auto;
        margin-bottom: 14px;
    }
    #logreg-forms .social-btn{
        font-weight: 100;
        color:white;
        width:190px;
        font-size: 0.9rem;
    }

    #logreg-forms a{
        display: block;
        padding-top:10px;
        color:lightseagreen;
    }

    #logreg-form .lines{
        width:200px;
        border:1px solid red;
    }


    #logreg-forms button[type="submit"]{ margin-top:10px; }

    #logreg-forms .facebook-btn{  background-color:#3C589C; }

    #logreg-forms .google-btn{ background-color: #DF4B3B; }

    #logreg-forms .form-reset, #logreg-forms .form-signup{ display: none; }

    #logreg-forms .form-signup .social-btn{ width:210px; }

    #logreg-forms .form-signup input { margin-bottom: 2px;}

    .form-signup .social-login{
        width:210px !important;
        margin: 0 auto;
    }

    /* Mobile */

    @media screen and (max-width:500px){
        #logreg-forms{
            width:97%;
        }

        #logreg-forms  .social-login{
            width:200px;
            margin:0 auto;
            margin-bottom: 10px;
        }
        #logreg-forms  .social-btn{
            font-size: 1.3rem;
            font-weight: 100;
            color:white;
            width:200px;
            height: 56px;

        }
        #logreg-forms .social-btn:nth-child(1){
            margin-bottom: 5px;
        }
        #logreg-forms .social-btn span{
            display: none;
        }
        #logreg-forms  .facebook-btn:after{
            content:'Facebook';
        }

        #logreg-forms  .google-btn:after{
            content:'Google+';
        }

    }

    .logo {
        width: 247px;
        margin: 0 auto;
        padding: 15px;
        text-align: center;
    }
    body{
        background-color: #ccdceb !important;
    }

    ::placeholder {
        color: #9d9d9d !important;
    }
</style>

<script>

    document.getElementById("logreg-forms").style.display = "block";

    function toggleResetPswd(e){
        e.preventDefault();
        $('#logreg-forms .form-signin').toggle() // display:block or none
        $('#logreg-forms .form-reset').toggle() // display:block or none
    }

    function toggleSignUp(e){
        e.preventDefault();
        $('#logreg-forms .form-signin').toggle(); // display:block or none
        $('#logreg-forms .form-signup').toggle(); // display:block or none
    }

    $(()=>{
        // Login Register Form
        $('#logreg-forms #forgot_pswd').click(toggleResetPswd);
        $('#logreg-forms #cancel_reset').click(toggleResetPswd);
        $('#logreg-forms #btn-signup').click(toggleSignUp);
        $('#logreg-forms #cancel_signup').click(toggleSignUp);
    })



</script>


<style>
    /* enable absolute positioning */
    .inner-addon {
        position: relative;
    }

    /* style icon */
    .inner-addon .user_icone {
        position: absolute;
        padding: 10px;
        pointer-events: none;
        color: #9d9d9d !important;
    }

    /* align icon */
    .left-addon .user_icone  { left:  0px;}
    .right-addon .user_icone { right: 0px;}

    /* add padding  */
    .left-addon input  { padding-left:  30px; }
    .right-addon input { padding-right: 30px; }

    body{
        background: url(https://wallpaperscraft.com/image/cows_field_grass_eating_walking_grazing_40238_2560x1600.jpg);
        background-size: 100% 100%;
        background-repeat: no-repeat;
    }
</style>

<script>
    function change_password() {
        var x = document.getElementById("myInput");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>