<?php
/* @var $this BlockController */
/* @var $data Block */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('block_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->block_id), array('view', 'id'=>$data->block_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('block_name')); ?>:</b>
	<?php echo CHtml::encode($data->block_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('company_id')); ?>:</b>
	<?php echo CHtml::encode($data->company_id); ?>
	<br />


</div>