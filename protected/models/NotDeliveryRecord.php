<?php

/**
 * This is the model class for table "not_delivery_record".
 *
 * The followings are the available columns in table 'not_delivery_record':
 * @property integer $not_delivery_record_id
 * @property integer $not_delivery_reasonType_id
 * @property integer $client_id
 * @property integer $rider_id
 * @property string $not_delivery_dateTime
 */
class NotDeliveryRecord extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'not_delivery_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('not_delivery_reasonType_id, client_id, rider_id, not_delivery_dateTime', 'required'),
			array('not_delivery_reasonType_id, client_id, rider_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('not_delivery_record_id, not_delivery_reasonType_id, client_id, rider_id, not_delivery_dateTime', 'safe', 'on'=>'search'),
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
			'not_delivery_record_id' => 'Not Delivery Record',
			'not_delivery_reasonType_id' => 'Not Delivery Reason Type',
			'client_id' => 'Client',
			'rider_id' => 'Rider',
			'not_delivery_dateTime' => 'Not Delivery Date Time',
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

		$criteria->compare('not_delivery_record_id',$this->not_delivery_record_id);
		$criteria->compare('not_delivery_reasonType_id',$this->not_delivery_reasonType_id);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('rider_id',$this->rider_id);
		$criteria->compare('not_delivery_dateTime',$this->not_delivery_dateTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NotDeliveryRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
