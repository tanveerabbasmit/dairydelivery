<?php

/**
 * This is the model class for table "pos".
 *
 * The followings are the available columns in table 'pos':
 * @property integer $pos_id
 * @property integer $user_id
 * @property integer $company_id
 * @property double $unit_price
 * @property double $quantity
 * @property integer $total_price
 * @property integer $received_amount
 * @property string $invoice
 * @property string $date
 * @property string $time
 */
class Pos extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, company_id, unit_price, quantity, total_price, received_amount, invoice, date, time', 'required'),
			array('user_id, company_id, total_price, received_amount', 'numerical', 'integerOnly'=>true),
			array('unit_price, quantity', 'numerical'),
			array('invoice', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pos_id, user_id, company_id, unit_price, quantity, total_price, received_amount, invoice, date, time', 'safe', 'on'=>'search'),
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
			'pos_id' => 'Pos',
			'user_id' => 'User',
			'company_id' => 'Company',
			'unit_price' => 'Unit Price',
			'quantity' => 'Quantity',
			'total_price' => 'Total Price',
			'received_amount' => 'Received Amount',
			'invoice' => 'Invoice',
			'date' => 'Date',
			'time' => 'Time',
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

		$criteria->compare('pos_id',$this->pos_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('unit_price',$this->unit_price);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('total_price',$this->total_price);
		$criteria->compare('received_amount',$this->received_amount);
		$criteria->compare('invoice',$this->invoice,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('time',$this->time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
