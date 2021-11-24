<?php

/**
 * This is the model class for table "rider_daily_stock".
 *
 * The followings are the available columns in table 'rider_daily_stock':
 * @property integer $rider_daily_stock_id
 * @property integer $rider_id
 * @property integer $product_id
 * @property string $date
 * @property integer $quantity
 * @property integer $return_quantity
 *
 * The followings are the available model relations:
 * @property Rider $rider
 * @property Product $product
 */
class RiderDailyStock extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rider_daily_stock';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rider_id, product_id, date, quantity', 'required'),
			array('rider_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rider_daily_stock_id, rider_id, product_id, date, quantity, return_quantity', 'safe', 'on'=>'search'),
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
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'rider_daily_stock_id' => 'Rider Daily Stock',
			'rider_id' => 'Rider',
			'product_id' => 'Product',
			'date' => 'Date',
			'quantity' => 'Quantity',
			'return_quantity' => 'Return Quantity',
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

		$criteria->compare('rider_daily_stock_id',$this->rider_daily_stock_id);
		$criteria->compare('rider_id',$this->rider_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('return_quantity',$this->return_quantity);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RiderDailyStock the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getDailyRiderStock($date=false)
	{
		$dailyStockList = array();
		if (!$date) {
			$date = date("Y-m-d");
		}
		$companyBranchId = Yii::app()->user->getState('company_branch_id');
		$sql = "SELECT rds.`rider_id`,r.`fullname`, r.`email`,r.`cell_no_1`,SUM(quantity) AS total_allocated_stock FROM rider_daily_stock AS rds 
			INNER JOIN rider AS r ON (r.`rider_id`=rds.`rider_id`)
			WHERE r.`company_branch_id`=$companyBranchId AND rds.`date`='$date'
			GROUP BY rds.`rider_id`";

		$dailyStockList = Yii::app()->db->createCommand($sql)->queryAll();		
		
		return $dailyStockList;
	}

	public static function getRiderDailyStockDetails($riderId, $date=false)
	{
		$riderStockDetails = array();
		if (!$date) {
			$date = date("Y-m-d");
		}
		$models = self::model()->findAllByAttributes(array('rider_id'=>$riderId, 'date'=>$date));
		foreach ($models as $model) {
			$riderStockDetails[] = array(
				'date' => $model->date,
				'product_id' => $model->product_id,
				'quantity' => $model->quantity,
				'return_quantity' => $model->return_quantity,
				'rider_daily_stock_id' => $model->rider_daily_stock_id,
				'rider_id' => $model->rider_id,
				'product_name' => $model->product->name,
				'updateMode' => false,
			);
		}
		return $riderStockDetails;
	}

	public static function getRiderList(){
	   $rider = Rider::model()->findAll();
        $riderList = array();
        foreach($rider as $value){
            $riderList[] = $value->attributes;
        }
         return $riderList ;

    }
}
