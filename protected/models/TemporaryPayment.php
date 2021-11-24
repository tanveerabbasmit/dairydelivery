<?php

/**
 * This is the model class for table "temporary_payment".
 *
 * The followings are the available columns in table 'temporary_payment':
 * @property integer $temporary_payment_id
 * @property integer $company_branch_id
 * @property integer $client_id
 * @property integer $amount
 * @property string $reference_number
 * @property integer $payment_status
 */
class TemporaryPayment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'temporary_payment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_branch_id, client_id, amount, reference_number, payment_status', 'required'),
			array('company_branch_id, client_id, amount, payment_status', 'numerical', 'integerOnly'=>true),
			array('reference_number', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('temporary_payment_id, company_branch_id, client_id, amount, reference_number, payment_status', 'safe', 'on'=>'search'),
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
			'temporary_payment_id' => 'Temporary Payment',
			'company_branch_id' => 'Company Branch',
			'client_id' => 'Client',
			'amount' => 'Amount',
			'reference_number' => 'Reference Number',
			'payment_status' => 'Payment Status',
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

		$criteria->compare('temporary_payment_id',$this->temporary_payment_id);
		$criteria->compare('company_branch_id',$this->company_branch_id);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('reference_number',$this->reference_number,true);
		$criteria->compare('payment_status',$this->payment_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TemporaryPayment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
