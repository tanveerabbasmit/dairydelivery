<?php

/**
 * This is the model class for table "payment_detail".
 *
 * The followings are the available columns in table 'payment_detail':
 * @property integer $payment_detail_id
 * @property integer $delivery_id
 * @property string $delivery_date
 * @property integer $client_id
 * @property integer $due_amount
 * @property integer $amount_paid
 * @property integer $payment_master_id
 * @property string $payment_date
 */
class PaymentDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */


	public function tableName()
	{
		return 'payment_detail';


	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('delivery_id, delivery_date, client_id, due_amount, amount_paid, payment_master_id, payment_date', 'required'),
			array('delivery_id, client_id, payment_master_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('payment_detail_id, delivery_id, delivery_date, client_id, due_amount, amount_paid, payment_master_id, payment_date', 'safe', 'on'=>'search'),
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
			'payment_detail_id' => 'Payment Detail',
			'delivery_id' => 'Delivery',
			'delivery_date' => 'Delivery Date',
			'client_id' => 'Client',
			'due_amount' => 'Due Amount',
			'amount_paid' => 'Amount Paid',
			'payment_master_id' => 'Payment Master',
			'payment_date' => 'Payment Date',
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

		$criteria->compare('payment_detail_id',$this->payment_detail_id);
		$criteria->compare('delivery_id',$this->delivery_id);
		$criteria->compare('delivery_date',$this->delivery_date,true);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('due_amount',$this->due_amount);
		$criteria->compare('amount_paid',$this->amount_paid);
		$criteria->compare('payment_master_id',$this->payment_master_id);
		$criteria->compare('payment_date',$this->payment_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PaymentDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
