<?php

/**
 * This is the model class for table "payment_master".
 *
 * The followings are the available columns in table 'payment_master':
 * @property integer $payment_master_id
 * @property string $date
 * @property string $time
 * @property string $payment_mode
 * @property integer $amount_paid
 * @property string $remarks
 * @property string $reference_number
 */
class PaymentMaster extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'payment_master';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, time, payment_mode, amount_paid, remarks', 'required'),

			array('payment_mode, remarks, reference_number', 'length', 'max'=>150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('payment_master_id, date, time, payment_mode, amount_paid, remarks, reference_number', 'safe', 'on'=>'search'),
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
			'payment_master_id' => 'Payment Master',
			'date' => 'Date',
			'time' => 'Time',
			'payment_mode' => 'Payment Mode',
			'amount_paid' => 'Amount Paid',
			'remarks' => 'Remarks',
			'reference_number' => 'Reference Number',
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

		$criteria->compare('payment_master_id',$this->payment_master_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('payment_mode',$this->payment_mode,true);
		$criteria->compare('amount_paid',$this->amount_paid);
		$criteria->compare('remarks',$this->remarks,true);
		$criteria->compare('reference_number',$this->reference_number,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PaymentMaster the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
