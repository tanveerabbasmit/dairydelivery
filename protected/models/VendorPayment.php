<?php

/**
 * This is the model class for table "vendor_payment".
 *
 * The followings are the available columns in table 'vendor_payment':
 * @property integer $vendor_payment_id
 * @property integer $vendor_id
 * @property integer $amount
 * @property string $action_date
 * @property string $reference_no
 * @property integer $payment_mode
 * @property integer $company_id
 */
class VendorPayment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendor_payment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vendor_id, amount, action_date, reference_no, payment_mode, company_id', 'required'),
			array('vendor_id, amount, payment_mode, company_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vendor_payment_id, vendor_id, amount, action_date, reference_no, payment_mode, company_id', 'safe', 'on'=>'search'),
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
			'vendor_payment_id' => 'Vendor Payment',
			'vendor_id' => 'Vendor',
			'amount' => 'Amount',
			'action_date' => 'Action Date',
			'reference_no' => 'Reference No',
			'payment_mode' => 'Payment Mode',
			'company_id' => 'Company',
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

		$criteria->compare('vendor_payment_id',$this->vendor_payment_id);
		$criteria->compare('vendor_id',$this->vendor_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('action_date',$this->action_date,true);
		$criteria->compare('reference_no',$this->reference_no,true);
		$criteria->compare('payment_mode',$this->payment_mode);
		$criteria->compare('company_id',$this->company_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorPayment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
