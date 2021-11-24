<?php

/**
 * This is the model class for table "effective_date_interval_schedule".
 *
 * The followings are the available columns in table 'effective_date_interval_schedule':
 * @property integer $effective_date_interval_schedule_id
 * @property integer $client_id
 * @property integer $product_id
 * @property string $start_interval_scheduler
 * @property integer $interval_days
 * @property integer $product_quantity
 */
class EffectiveDateIntervalSchedule extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'effective_date_interval_schedule';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id, product_id, start_interval_scheduler, interval_days, product_quantity', 'required'),
			array('client_id, product_id, interval_days, product_quantity', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('effective_date_interval_schedule_id, client_id, product_id, start_interval_scheduler, interval_days, product_quantity', 'safe', 'on'=>'search'),
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
			'effective_date_interval_schedule_id' => 'Effective Date Interval Schedule',
			'client_id' => 'Client',
			'product_id' => 'Product',
			'start_interval_scheduler' => 'Start Interval Scheduler',
			'interval_days' => 'Interval Days',
			'product_quantity' => 'Product Quantity',
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

		$criteria->compare('effective_date_interval_schedule_id',$this->effective_date_interval_schedule_id);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('start_interval_scheduler',$this->start_interval_scheduler,true);
		$criteria->compare('interval_days',$this->interval_days);
		$criteria->compare('product_quantity',$this->product_quantity);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EffectiveDateIntervalSchedule the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
