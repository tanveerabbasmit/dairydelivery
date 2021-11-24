<?php

/**
 * This is the model class for table "new_payment".
 *
 * The followings are the available columns in table 'new_payment':
 * @property integer $new_payment_id
 * @property integer $collection_vault_id
 * @property integer $vendor_type_id
 * @property integer $vendor_id
 * @property string $date
 * @property string $transaction_type
 * @property integer $expence_type
 * @property integer $amount_paid
 * @property string $reference_no
 */
class NewPayment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'new_payment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('collection_vault_id, vendor_type_id, vendor_id, date, transaction_type, expence_type, amount_paid, reference_no', 'required'),
			array('collection_vault_id, vendor_type_id, vendor_id, expence_type, amount_paid', 'numerical', 'integerOnly'=>true),
			array('transaction_type', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('new_payment_id, collection_vault_id, vendor_type_id, vendor_id, date, transaction_type, expence_type, amount_paid, reference_no', 'safe', 'on'=>'search'),
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
			'new_payment_id' => 'New Payment',
			'collection_vault_id' => 'Collection Vault',
			'vendor_type_id' => 'Vendor Type',
			'vendor_id' => 'Vendor',
			'date' => 'Date',
			'transaction_type' => 'Transaction Type',
			'expence_type' => 'Expence Type',
			'amount_paid' => 'Amount Paid',
			'reference_no' => 'Reference No',
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

		$criteria->compare('new_payment_id',$this->new_payment_id);
		$criteria->compare('collection_vault_id',$this->collection_vault_id);
		$criteria->compare('vendor_type_id',$this->vendor_type_id);
		$criteria->compare('vendor_id',$this->vendor_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('transaction_type',$this->transaction_type,true);
		$criteria->compare('expence_type',$this->expence_type);
		$criteria->compare('amount_paid',$this->amount_paid);
		$criteria->compare('reference_no',$this->reference_no,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NewPayment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
