<?php

/**
 * This is the model class for table "delivery_detail".
 *
 * The followings are the available columns in table 'delivery_detail':
 * @property integer $delivery_detail_id
 * @property integer $delivery_id
 * @property integer $product_id
 * @property string $date
 * @property integer $quantity
 * @property double $amount
 * @property double $adjust_amount
 *
 * The followings are the available model relations:
 * @property Delivery $delivery
 * @property Product $product
 */
class DeliveryDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'delivery_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('delivery_id, product_id, date, quantity, amount', 'required'),
			array('delivery_id, product_id', 'numerical', 'integerOnly'=>true),
			array('amount, adjust_amount', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('delivery_detail_id, delivery_id, product_id, date, quantity, amount, adjust_amount', 'safe', 'on'=>'search'),
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
			'delivery' => array(self::BELONGS_TO, 'Delivery', 'delivery_id'),
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'delivery_detail_id' => 'Delivery Detail',
			'delivery_id' => 'Delivery',
			'product_id' => 'Product',
			'date' => 'Date',
			'quantity' => 'Quantity',
			'amount' => 'Amount',
			'adjust_amount' => 'Adjust Amount',
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

		$criteria->compare('delivery_detail_id',$this->delivery_detail_id);
		$criteria->compare('delivery_id',$this->delivery_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('adjust_amount',$this->adjust_amount);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DeliveryDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
