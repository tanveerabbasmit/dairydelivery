<?php

/**
 * This is the model class for table "receipt".
 *
 * The followings are the available columns in table 'receipt':
 * @property integer $receipt_id
 * @property integer $client_id
 * @property integer $rider_id
 * @property string $datetime
 * @property double $amount_received
 * @property string $mode
 *
 * The followings are the available model relations:
 * @property Rider $rider
 * @property Client $client
 */
class Receipt extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'receipt';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id, datetime, amount_received, mode', 'required'),
			array('client_id, rider_id', 'numerical', 'integerOnly'=>true),
			array('amount_received', 'numerical'),
			array('mode', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('receipt_id, client_id, rider_id, datetime, amount_received, mode', 'safe', 'on'=>'search'),
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
			'rider' => array(self::BELONGS_TO, 'Rider', 'rider_id'),
			'client' => array(self::BELONGS_TO, 'Client', 'client_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'receipt_id' => 'Receipt',
			'client_id' => 'Client',
			'rider_id' => 'Rider',
			'datetime' => 'Datetime',
			'amount_received' => 'Amount Received',
			'mode' => 'Mode',
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

		$criteria->compare('receipt_id',$this->receipt_id);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('rider_id',$this->rider_id);
		$criteria->compare('datetime',$this->datetime,true);
		$criteria->compare('amount_received',$this->amount_received);
		$criteria->compare('mode',$this->mode,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Receipt the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
