<?php

/**
 * This is the model class for table "client_scheduler_type".
 *
 * The followings are the available columns in table 'client_scheduler_type':
 * @property integer $client_scheduler_type
 * @property integer $scheduler_type
 * @property integer $client_id
 * @property integer $product_id
 */
class ClientSchedulerType extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'client_scheduler_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('scheduler_type, client_id, product_id', 'required'),
			array('scheduler_type, client_id, product_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('client_scheduler_type, scheduler_type, client_id, product_id', 'safe', 'on'=>'search'),
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
			'client_scheduler_type' => 'Client Scheduler Type',
			'scheduler_type' => 'Scheduler Type',
			'client_id' => 'Client',
			'product_id' => 'Product',
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

		$criteria->compare('client_scheduler_type',$this->client_scheduler_type);
		$criteria->compare('scheduler_type',$this->scheduler_type);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('product_id',$this->product_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClientSchedulerType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
