<?php

/**
 * This is the model class for table "delivery".
 *
 * The followings are the available columns in table 'delivery':
 * @property integer $delivery_id
 * @property integer $company_branch_id
 * @property integer $client_id
 * @property integer $rider_id
 * @property string $date
 * @property string $time
 * @property double $tax_percentage
 * @property double $amount_with_tax
 * @property double $tax_amount
 * @property double $amount
 * @property double $discount_percentage
 * @property double $total_amount
 *
 * The followings are the available model relations:
 * @property Rider $rider
 * @property Client $client
 * @property CompanyBranch $companyBranch
 * @property DeliveryDetail[] $deliveryDetails
 */
class Delivery extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'delivery';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_branch_id, client_id, rider_id, date, time, tax_percentage, amount_with_tax, amount, discount_percentage, total_amount', 'required'),
			array('company_branch_id, client_id, rider_id', 'numerical', 'integerOnly'=>true),
			array('tax_percentage, amount_with_tax, tax_amount, amount, discount_percentage, total_amount', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('delivery_id, company_branch_id, client_id, rider_id, date, time, tax_percentage, amount_with_tax, tax_amount, amount, discount_percentage, total_amount', 'safe', 'on'=>'search'),
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
			'rider' => array(self::BELONGS_TO, 'Rider', 'rider_id'),
			'client' => array(self::BELONGS_TO, 'Client', 'client_id'),
			'companyBranch' => array(self::BELONGS_TO, 'CompanyBranch', 'company_branch_id'),
			'deliveryDetails' => array(self::HAS_MANY, 'DeliveryDetail', 'delivery_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'delivery_id' => 'Delivery',
			'company_branch_id' => 'Company Branch',
			'client_id' => 'Client',
			'rider_id' => 'Rider',
			'date' => 'Date',
			'time' => 'Time',
			'tax_percentage' => 'Tax Percentage',
			'amount_with_tax' => 'Amount With Tax',
			'tax_amount' => 'Tax Amount',
			'amount' => 'Amount',
			'discount_percentage' => 'Discount Percentage',
			'total_amount' => 'Total Amount',
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

		$criteria->compare('delivery_id',$this->delivery_id);
		$criteria->compare('company_branch_id',$this->company_branch_id);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('rider_id',$this->rider_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('tax_percentage',$this->tax_percentage);
		$criteria->compare('amount_with_tax',$this->amount_with_tax);
		$criteria->compare('tax_amount',$this->tax_amount);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('discount_percentage',$this->discount_percentage);
		$criteria->compare('total_amount',$this->total_amount);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Delivery the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
