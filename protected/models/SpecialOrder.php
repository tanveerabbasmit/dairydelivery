<?php

/**
 * This is the model class for table "special_order".
 *
 * The followings are the available columns in table 'special_order':
 * @property integer $special_order_id
 * @property integer $client_id
 * @property integer $product_id
 * @property integer $quantity
 * @property integer $status_id
 * @property string $requested_on
 * @property string $delivery_on
 * @property integer $preferred_time_id
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property Client $client
 */
class SpecialOrder extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'special_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id, product_id, quantity, status_id, delivery_on, preferred_time_id', 'required'),
			array('client_id, product_id, quantity, status_id, preferred_time_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('special_order_id, client_id, product_id, quantity, status_id, requested_on, delivery_on, preferred_time_id', 'safe', 'on'=>'search'),
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
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
			'client' => array(self::BELONGS_TO, 'Client', 'client_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'special_order_id' => 'Special Order',
			'client_id' => 'Client',
			'product_id' => 'Product',
			'quantity' => 'Quantity',
			'status_id' => 'Status',
			'requested_on' => 'Requested On',
			'delivery_on' => 'Delivery On',
			'preferred_time_id' => 'Preferred Time',
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

		$criteria->compare('special_order_id',$this->special_order_id);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('status_id',$this->status_id);
		$criteria->compare('requested_on',$this->requested_on,true);
		$criteria->compare('delivery_on',$this->delivery_on,true);
		$criteria->compare('preferred_time_id',$this->preferred_time_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SpecialOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
