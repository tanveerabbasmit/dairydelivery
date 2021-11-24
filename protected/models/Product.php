<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $product_id
 * @property integer $company_branch_id
 * @property string $name
 * @property double $price
 * @property string $unit
 * @property integer $is_active
 * @property integer $is_deleted
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property ClientProductFrequency[] $clientProductFrequencies
 * @property DailyStock[] $dailyStocks
 * @property DeliveryDetail[] $deliveryDetails
 * @property User $createdBy
 * @property User $updatedBy
 * @property CompanyBranch $companyBranch
 * @property RiderDailyStock[] $riderDailyStocks
 * @property SpecificRequest[] $specificRequests
 */
class Product extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_branch_id, name, price, unit', 'required'),
			array('company_branch_id, is_deleted, created_by, updated_by', 'numerical', 'integerOnly'=>true),
			array('price', 'numerical'),
			array('name', 'length', 'max'=>255),
			array('unit', 'length', 'max'=>100),
			array('updated_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('product_id, company_branch_id, name, price, unit, is_active, is_deleted, created_by, updated_by, created_at, updated_at', 'safe', 'on'=>'search'),
            [['image'], 'default', 'value'=>'noImage.jpg'],
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
			'clientProductFrequencies' => array(self::HAS_MANY, 'ClientProductFrequency', 'product_id'),
			'dailyStocks' => array(self::HAS_MANY, 'DailyStock', 'product_id'),
			'deliveryDetails' => array(self::HAS_MANY, 'DeliveryDetail', 'product_id'),
			'createdBy' => array(self::BELONGS_TO, 'User', 'created_by'),
			'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
			'companyBranch' => array(self::BELONGS_TO, 'CompanyBranch', 'company_branch_id'),
			'riderDailyStocks' => array(self::HAS_MANY, 'RiderDailyStock', 'product_id'),
			'specificRequests' => array(self::HAS_MANY, 'SpecificRequest', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'product_id' => 'Product',
			'company_branch_id' => 'Company Branch',
			'name' => 'Name',
			'price' => 'Price',
			'unit' => 'Unit',
			'is_active' => 'Is Active',
			'is_deleted' => 'Is Deleted',
			'created_by' => 'Created By',
			'updated_by' => 'Updated By',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
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

		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('company_branch_id',$this->company_branch_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('unit',$this->unit,true);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('updated_by',$this->updated_by);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Product the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
