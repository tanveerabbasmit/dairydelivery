<?php

/**
 * This is the model class for table "payment_failed".
 *
 * The followings are the available columns in table 'payment_failed':
 * @property integer $payment_failed_id
 * @property integer $client_id
 * @property string $trans_ref_no
 * @property integer $amount_paid
 */
class PaymentFailed extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'payment_failed';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id, trans_ref_no, amount_paid', 'required'),
			array('client_id, amount_paid', 'numerical', 'integerOnly'=>true),
			array('trans_ref_no', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('payment_failed_id, client_id, trans_ref_no, amount_paid', 'safe', 'on'=>'search'),
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
			'payment_failed_id' => 'Payment Failed',
			'client_id' => 'Client',
			'trans_ref_no' => 'Trans Ref No',
			'amount_paid' => 'Amount Paid',
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

		$criteria->compare('payment_failed_id',$this->payment_failed_id);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('trans_ref_no',$this->trans_ref_no,true);
		$criteria->compare('amount_paid',$this->amount_paid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PaymentFailed the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
