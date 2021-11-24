<?php

/**
 * This is the model class for table "interval_scheduler".
 *
 * The followings are the available columns in table 'interval_scheduler':
 * @property integer $interval_scheduler_id
 * @property integer $client_id
 * @property integer $product_id
 * @property integer $interval_days
 * @property integer $product_quantity
 * @property string $start_interval_scheduler
 * @property integer $is_halt
 */
class IntervalScheduler extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'interval_scheduler';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id, product_id, interval_days, product_quantity, start_interval_scheduler, is_halt', 'required'),
			array('client_id, product_id, interval_days, product_quantity, is_halt', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('interval_scheduler_id, client_id, product_id, interval_days, product_quantity, start_interval_scheduler, is_halt', 'safe', 'on'=>'search'),
            array('interval_days', 'numerical', 'min'=>1)
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'interval_scheduler_id' => 'Interval Scheduler',
			'client_id' => 'Client',
			'product_id' => 'Product',
			'interval_days' => 'Interval Days',
			'product_quantity' => 'Product Quantity',
			'start_interval_scheduler' => 'Start Interval Scheduler',
			'is_halt' => 'Is Halt',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('interval_scheduler_id',$this->interval_scheduler_id);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('interval_days',$this->interval_days);
		$criteria->compare('product_quantity',$this->product_quantity);
		$criteria->compare('start_interval_scheduler',$this->start_interval_scheduler,true);
		$criteria->compare('is_halt',$this->is_halt);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return IntervalScheduler the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
