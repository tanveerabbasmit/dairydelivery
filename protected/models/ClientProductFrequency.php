<?php

/**
 * This is the model class for table "client_product_frequency".
 *
 * The followings are the available columns in table 'client_product_frequency':
 * @property integer $client_product_frequency
 * @property integer $client_id
 * @property integer $frequency_id
 * @property integer $product_id
 * @property string $quantity
 * @property integer $total_rate
 *
 * The followings are the available model relations:
 * @property Frequency $frequency
 * @property Client $client
 * @property Product $product
 */
class ClientProductFrequency extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'client_product_frequency';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id, frequency_id, product_id, total_rate', 'required'),
			array('client_id, frequency_id, product_id, total_rate', 'numerical', 'integerOnly'=>true),
			array('quantity', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('client_product_frequency, client_id, frequency_id, product_id, quantity, total_rate', 'safe', 'on'=>'search'),
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
			'frequency' => array(self::BELONGS_TO, 'Frequency', 'frequency_id'),
			'client' => array(self::BELONGS_TO, 'Client', 'client_id'),
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'client_product_frequency' => 'Client Product Frequency',
			'client_id' => 'Client',
			'frequency_id' => 'Frequency',
			'product_id' => 'Product',
			'quantity' => 'Quantity',
			'total_rate' => 'Total Rate',
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

		$criteria->compare('client_product_frequency',$this->client_product_frequency);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('frequency_id',$this->frequency_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('quantity',$this->quantity,true);
		$criteria->compare('total_rate',$this->total_rate);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClientProductFrequency the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
