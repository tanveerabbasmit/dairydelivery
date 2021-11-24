<?php

/**
 * This is the model class for table "bill_from_vendor".
 *
 * The followings are the available columns in table 'bill_from_vendor':
 * @property integer $bill_from_vendor_id
 * @property string $action_date
 * @property integer $vendor_id
 * @property integer $item_id
 * @property double $price
 * @property integer $quantity
 * @property double $gross_amount
 * @property integer $tax_amount
 * @property double $discount_amount
 * @property double $net_amount
 */
class BillFromVendor extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bill_from_vendor';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('action_date, vendor_id, item_id, price, quantity, gross_amount, tax_amount, discount_amount, net_amount', 'required'),
			array('vendor_id, item_id, quantity, tax_amount', 'numerical', 'integerOnly'=>true),
			array('price, gross_amount, discount_amount, net_amount', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bill_from_vendor_id, action_date, vendor_id, item_id, price, quantity, gross_amount, tax_amount, discount_amount, net_amount', 'safe', 'on'=>'search'),
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
			'bill_from_vendor_id' => 'Bill From Vendor',
			'action_date' => 'Action Date',
			'vendor_id' => 'Vendor',
			'item_id' => 'Item',
			'price' => 'Price',
			'quantity' => 'Quantity',
			'gross_amount' => 'Gross Amount',
			'tax_amount' => 'Tax Amount',
			'discount_amount' => 'Discount Amount',
			'net_amount' => 'Net Amount',
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

		$criteria->compare('bill_from_vendor_id',$this->bill_from_vendor_id);
		$criteria->compare('action_date',$this->action_date,true);
		$criteria->compare('vendor_id',$this->vendor_id);
		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('price',$this->price);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('gross_amount',$this->gross_amount);
		$criteria->compare('tax_amount',$this->tax_amount);
		$criteria->compare('discount_amount',$this->discount_amount);
		$criteria->compare('net_amount',$this->net_amount);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BillFromVendor the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
