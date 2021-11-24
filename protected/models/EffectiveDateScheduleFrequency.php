<?php

/**
 * This is the model class for table "effective_date_schedule_frequency".
 *
 * The followings are the available columns in table 'effective_date_schedule_frequency':
 * @property integer $effective_date_schedule_frequency_id
 * @property integer $effective_date_schedule_id
 * @property integer $frequency_id
 * @property integer $quantity
 */
class EffectiveDateScheduleFrequency extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'effective_date_schedule_frequency';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('effective_date_schedule_id, frequency_id, quantity', 'required'),
			array('effective_date_schedule_id, frequency_id, quantity', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('effective_date_schedule_frequency_id, effective_date_schedule_id, frequency_id, quantity', 'safe', 'on'=>'search'),
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
			'effective_date_schedule_frequency_id' => 'Effective Date Schedule Frequency',
			'effective_date_schedule_id' => 'Effective Date Schedule',
			'frequency_id' => 'Frequency',
			'quantity' => 'Quantity',
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

		$criteria->compare('effective_date_schedule_frequency_id',$this->effective_date_schedule_frequency_id);
		$criteria->compare('effective_date_schedule_id',$this->effective_date_schedule_id);
		$criteria->compare('frequency_id',$this->frequency_id);
		$criteria->compare('quantity',$this->quantity);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EffectiveDateScheduleFrequency the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
