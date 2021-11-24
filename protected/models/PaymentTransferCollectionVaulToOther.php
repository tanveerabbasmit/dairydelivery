<?php

/**
 * This is the model class for table "payment_transfer_collection_vaul_to_other".
 *
 * The followings are the available columns in table 'payment_transfer_collection_vaul_to_other':
 * @property integer $payment_transfer_collection_vaul_to_other_id
 * @property integer $collection_vault_id_from
 * @property integer $collection_vault_id_to
 * @property integer $amount
 * @property string $action_date
 * @property string $remarks
 */
class PaymentTransferCollectionVaulToOther extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'payment_transfer_collection_vaul_to_other';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('collection_vault_id_from, collection_vault_id_to, amount, action_date, remarks', 'required'),
			array('collection_vault_id_from, collection_vault_id_to, amount', 'numerical', 'integerOnly'=>true),
			array('remarks', 'length', 'max'=>60),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('payment_transfer_collection_vaul_to_other_id, collection_vault_id_from, collection_vault_id_to, amount, action_date, remarks', 'safe', 'on'=>'search'),
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
			'payment_transfer_collection_vaul_to_other_id' => 'Payment Transfer Collection Vaul To Other',
			'collection_vault_id_from' => 'Collection Vault Id From',
			'collection_vault_id_to' => 'Collection Vault Id To',
			'amount' => 'Amount',
			'action_date' => 'Action Date',
			'remarks' => 'Remarks',
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

		$criteria->compare('payment_transfer_collection_vaul_to_other_id',$this->payment_transfer_collection_vaul_to_other_id);
		$criteria->compare('collection_vault_id_from',$this->collection_vault_id_from);
		$criteria->compare('collection_vault_id_to',$this->collection_vault_id_to);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('action_date',$this->action_date,true);
		$criteria->compare('remarks',$this->remarks,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PaymentTransferCollectionVaulToOther the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
