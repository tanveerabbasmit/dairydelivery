<?php
/* @var $this Compnay_messageController */

$this->breadcrumbs=array(
	'Compnay Message'=>array('/compnay_message'),
	'Change_sms_option',
);
?>



<div style="background-color: white;padding: 30px">

     <h2>
         <span style=" font-weight: bold;"> <?php echo $data['message'];  ?></php> </span><a href="<?php echo Yii::app()->createUrl('companymessage/change_sms_option')?>?flage=<?php echo $data['option']  ?>">Click Me</a> For Change
         <i style="margin-left: 10px;color: blue" class="<?php echo $data['icone'] ?>" aria-hidden="true"></i>
     </h2>
</div>



