<?php

/**
 * This is the model class for table "daily_stock_expire".
 *
 * The followings are the available columns in table 'daily_stock_expire':
 * @property integer $daily_stock_expire_id
 * @property integer $company_branch_id
 * @property integer $daily_stock_id
 * @property integer $quantity
 * @property string $date
 *
 * The followings are the available model relations:
 * @property CompanyBranch $companyBranch
 * @property CompanyBranch $dailyStock
 */
class DailyStockExpire extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'daily_stock_expire';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_branch_id, daily_stock_id, quantity, date', 'required'),
			array('company_branch_id, daily_stock_id, quantity', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('daily_stock_expire_id, company_branch_id, daily_stock_id, quantity, date', 'safe', 'on'=>'search'),
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
			'companyBranch' => array(self::BELONGS_TO, 'CompanyBranch', 'company_branch_id'),
			'dailyStock' => array(self::BELONGS_TO, 'CompanyBranch', 'daily_stock_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'daily_stock_expire_id' => 'Daily Stock Expire',
			'company_branch_id' => 'Company Branch',
			'daily_stock_id' => 'Daily Stock',
			'quantity' => 'Quantity',
			'date' => 'Date',
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

		$criteria->compare('daily_stock_expire_id',$this->daily_stock_expire_id);
		$criteria->compare('company_branch_id',$this->company_branch_id);
		$criteria->compare('daily_stock_id',$this->daily_stock_id);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DailyStockExpire the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
