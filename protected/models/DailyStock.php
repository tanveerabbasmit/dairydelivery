<?php

/**
 * This is the model class for table "daily_stock".
 *
 * The followings are the available columns in table 'daily_stock':
 * @property integer $daily_stock_id
 * @property integer $company_branch_id
 * @property integer $product_id
 * @property string $description
 * @property integer $quantity
 * @property string $date
 * @property integer $return_quantity
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property User $createdBy
 * @property User $updatedBy
 * @property CompanyBranch $companyBranch
 */
class DailyStock extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'daily_stock';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_branch_id', 'required'),
			array('company_branch_id, product_id,  created_by, updated_by', 'numerical', 'integerOnly'=>true),
			array('updated_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('daily_stock_id, company_branch_id, product_id, description, quantity, date, return_quantity, created_by, created_at, updated_by, updated_at', 'safe', 'on'=>'search'),
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
			'createdBy' => array(self::BELONGS_TO, 'User', 'created_by'),
			'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
			'companyBranch' => array(self::BELONGS_TO, 'CompanyBranch', 'company_branch_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'daily_stock_id' => 'Daily Stock',
			'company_branch_id' => 'Company Branch',
			'product_id' => 'Product',
			'description' => 'Description',
			'quantity' => 'Quantity',
			'date' => 'Date',
			'return_quantity' => 'Return Quantity',
			'created_by' => 'Created By',
			'created_at' => 'Created At',
			'updated_by' => 'Updated By',
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

		$criteria->compare('daily_stock_id',$this->daily_stock_id);
		$criteria->compare('company_branch_id',$this->company_branch_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('return_quantity',$this->return_quantity);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_by',$this->updated_by);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DailyStock the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getDailyStockbkp($date=false)
	{
		if (!$date) {
			$date = date("Y-m-d");
		}
		$dailyStockList = array();
		$models = DailyStock::model()->findAllByAttributes(array(
			'company_branch_id' => Yii::app()->user->getState('company_branch_id'),
			'date' => $date,
		));
		foreach ($models as $model) {
			$dailyStockList[] = array(
				'daily_stock_id' => $model->daily_stock_id,
				'product_id' => $model->product_id,
				'quantity' => $model->quantity,
				'description' => $model->description,
				'branch' => $model->companyBranch->name,
				'date' => $model->date,
				'return_quantity' => $model->return_quantity,
				'product_name' => $model->product->name,
			);
		}
		return $dailyStockList;
	}

	public static function getDailyStock($date=false)
	{
		$dailyStockList = array();
		if (!$date) {
			$date = date("Y-m-d");
		}
		$companyBranchId = Yii::app()->user->getState('company_branch_id');
		$sql = "SELECT ds.*,p.`name` as product_name, SUM(ds.`quantity`) AS total_quantity, SUM(ds.`return_quantity`) AS total_return_quantity FROM daily_stock AS ds 
			INNER JOIN product AS p ON (p.`product_id`=ds.`product_id`) 
			WHERE ds.`company_branch_id`=$companyBranchId AND ds.`date`='$date'
			GROUP BY ds.`product_id`
			";

		$dailyStockList = Yii::app()->db->createCommand($sql)->queryAll();		
		
		return $dailyStockList;
	}

	public static function getDailyStockByProductId($productId, $date=false)
	{
		$stockDetailList = array();
		if (!$date) {
			$date = date("Y-m-d");
		}
		$companyBranchId = Yii::app()->user->getState('company_branch_id');
		$sql = "SELECT ds.* FROM daily_stock AS ds 
				INNER JOIN product AS p ON (p.`product_id`=ds.`product_id`) 
				WHERE ds.`company_branch_id`=$companyBranchId AND ds.`date`='$date' AND ds.`product_id`=$productId
			";

		$stockDetailList = Yii::app()->db->createCommand($sql)->queryAll();		
		
		return $stockDetailList;
	}

	
}
