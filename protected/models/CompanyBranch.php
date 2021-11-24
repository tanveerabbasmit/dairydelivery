<?php

/**
 * This is the model class for table "company_branch".
 *
 * The followings are the available columns in table 'company_branch':
 * @property integer $company_branch_id
 * @property integer $company_id
 * @property string $name
 * @property string $address
 * @property integer $is_active
 * @property integer $is_deleted
 * @property integer $created_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Client[] $clients
 * @property Company $company
 * @property ComplainType[] $complainTypes
 * @property DailyStock[] $dailyStocks
 * @property DailyStockExpire[] $dailyStockExpires
 * @property DailyStockExpire[] $dailyStockExpires1
 * @property Delivery[] $deliveries
 * @property Product[] $products
 * @property Rider[] $riders
 * @property Zone[] $zones
 */
class CompanyBranch extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'company_branch';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id, name, address, created_by, created_at', 'required'),
			array('company_id, is_active, is_deleted, created_by', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('company_branch_id, company_id, name, address, is_active, is_deleted, created_by, created_at', 'safe', 'on'=>'search'),
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
			'clients' => array(self::HAS_MANY, 'Client', 'company_branch_id'),
			'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
			'complainTypes' => array(self::HAS_MANY, 'ComplainType', 'company_branch_id'),
			'dailyStocks' => array(self::HAS_MANY, 'DailyStock', 'company_branch_id'),
			'dailyStockExpires' => array(self::HAS_MANY, 'DailyStockExpire', 'company_branch_id'),
			'dailyStockExpires1' => array(self::HAS_MANY, 'DailyStockExpire', 'daily_stock_id'),
			'deliveries' => array(self::HAS_MANY, 'Delivery', 'company_branch_id'),
			'products' => array(self::HAS_MANY, 'Product', 'company_branch_id'),
			'riders' => array(self::HAS_MANY, 'Rider', 'company_branch_id'),
			'zones' => array(self::HAS_MANY, 'Zone', 'company_branch_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'company_branch_id' => 'Company Branch',
			'company_id' => 'Company',
			'name' => 'Name',
			'address' => 'Address',
			'is_active' => 'Is Active',
			'is_deleted' => 'Is Deleted',
			'created_by' => 'Created By',
			'created_at' => 'Created At',
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

		$criteria->compare('company_branch_id',$this->company_branch_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CompanyBranch the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
