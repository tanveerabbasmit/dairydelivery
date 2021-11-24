<?php

/**
 * This is the model class for table "discount_list".
 *
 * The followings are the available columns in table 'discount_list':
 * @property integer $discount_list_id
 * @property integer $discount_type_id
 * @property integer $percentage
 * @property integer $percentage_amount
 * @property integer $total_discount_amount
 * @property integer $payment_master_id
 */
class DiscountList extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'discount_list';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('discount_type_id, percentage, percentage_amount, total_discount_amount, payment_master_id', 'required'),
			array('discount_type_id, percentage, percentage_amount, total_discount_amount, payment_master_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('discount_list_id, discount_type_id, percentage, percentage_amount, total_discount_amount, payment_master_id', 'safe', 'on'=>'search'),
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
			'discount_list_id' => 'Discount List',
			'discount_type_id' => 'Discount Type',
			'percentage' => 'Percentage',
			'percentage_amount' => 'Percentage Amount',
			'total_discount_amount' => 'Total Discount Amount',
			'payment_master_id' => 'Payment Master',
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

		$criteria->compare('discount_list_id',$this->discount_list_id);
		$criteria->compare('discount_type_id',$this->discount_type_id);
		$criteria->compare('percentage',$this->percentage);
		$criteria->compare('percentage_amount',$this->percentage_amount);
		$criteria->compare('total_discount_amount',$this->total_discount_amount);
		$criteria->compare('payment_master_id',$this->payment_master_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DiscountList the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
