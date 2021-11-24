<?php
/* @var $this BlockController */
/* @var $model Block */

$this->breadcrumbs=array(
	'Blocks'=>array('index'),
	$model->block_id=>array('view','id'=>$model->block_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Block', 'url'=>array('index')),
	array('label'=>'Create Block', 'url'=>array('create')),
	array('label'=>'View Block', 'url'=>array('view', 'id'=>$model->block_id)),
	array('label'=>'Manage Block', 'url'=>array('admin')),
);
?>

<h1>Update Block <?php echo $model->block_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>