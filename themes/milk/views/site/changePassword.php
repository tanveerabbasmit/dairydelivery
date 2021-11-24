<!DOCTYPE html>
<html lang="en">
<head>

  <title>Sign In - Dairy Milk</title>

  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/fontawesome/css/font-awesome.css">

  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/milk.css">

  <script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/modernizr/modernizr.js"></script>
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/html5shiv/html5shiv.js"></script>
  <script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/respond/respond.src.js"></script>
  <![endif]-->
</head>

<body class="signwrapper">

  <div class="sign-overlay"></div>
  <div class="signpanel"></div>

  <div class="panel signin">
    <div class="panel-heading">
      <h1>Dairy Milk System</h1>
      <h4 class="panel-title">Welcome! Change Password.</h4>
    </div>
    <div class="panel-body">
      
      <!--
      <form action="">
        <div class="form-group mb10">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input type="text" name="username" class="form-control" placeholder="Enter Username">
          </div>
        </div>
        <div class="form-group nomargin">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <input type="text" name="password" class="form-control" placeholder="Enter Password">
          </div>
        </div>
        <div><a href="" class="forgot">Forgot password?</a></div>
        <div class="form-group">
          <button class="btn btn-success btn-clinic btn-block">Sign In</button>
        </div>
      </form>
        -->
        
        
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
    'action'=>Yii::app()->createUrl('site/changedPassword'),
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

    

    
    
        <div class="form-group mb10">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
		<?php echo $form->textField($model,'oldpassword',array('class' => 'form-control','placeholder' => 'Enter Old Password')); ?>
          </div>
          <?php echo $form->error($model,'oldpassword'); ?>
        </div>
    
        <div class="form-group mb10">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
		<?php echo $form->passwordField($model,'newpassword',array('class' => 'form-control','placeholder' => 'Enter New Password')); ?>
          </div>
          <?php echo $form->error($model,'newpassword'); ?>
        </div>

	<div class="form-group mb10">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>
    
	<div class="form-group">
		<?php echo CHtml::submitButton('Sign In',array('class' => 'btn btn-success btn-clinic btn-block')); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
        
     
        
      <hr class="invisible">
      <!--
      <div class="form-group">
        <a href="signup.html" class="btn btn-default btn-clinic btn-stroke btn-stroke-thin btn-block btn-sign">Not a member? Sign up now!</a>
      </div>
      -->
    </div>
  </div><!-- panel -->

</body>
</html>
