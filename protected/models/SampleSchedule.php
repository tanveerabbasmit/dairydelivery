<?php

/**
 * This is the model class for table "sample_schedule".
 *
 * The followings are the available columns in table 'sample_schedule':
 * @property integer $sample_schedule_id
 * @property integer $client_id
 * @property integer $company_id
 * @property string $deliver_date
 * @property integer $quantity
 * @property string $created_date
 */
class SampleSchedule extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sample_schedule';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id, company_id, deliver_date, quantity, created_date', 'required'),
			array('client_id, company_id, quantity', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sample_schedule_id, client_id, company_id, deliver_date, quantity, created_date', 'safe', 'on'=>'search'),
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
			'sample_schedule_id' => 'Sample Schedule',
			'client_id' => 'Client',
			'company_id' => 'Company',
			'deliver_date' => 'Deliver Date',
			'quantity' => 'Quantity',
			'created_date' => 'Created Date',
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

		$criteria->compare('sample_schedule_id',$this->sample_schedule_id);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('deliver_date',$this->deliver_date,true);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('created_date',$this->created_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SampleSchedule the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
