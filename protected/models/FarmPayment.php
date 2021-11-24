<?php

/**
 * This is the model class for table "farm_payment".
 *
 * The followings are the available columns in table 'farm_payment':
 * @property integer $farm_payment_id
 * @property integer $farm_id
 * @property double $amount
 * @property string $action_date
 * @property string $remarks
 * @property string $reference_no
 */
class FarmPayment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'farm_payment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('farm_id, amount, action_date,  reference_no', 'required'),
			array('farm_id', 'numerical', 'integerOnly'=>true),
			array('amount', 'numerical'),
			array('remarks, reference_no', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('farm_payment_id, farm_id, amount, action_date, remarks, reference_no', 'safe', 'on'=>'search'),
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
			'farm_payment_id' => 'Farm Payment',
			'farm_id' => 'Farm',
			'amount' => 'Amount',
			'action_date' => 'Action Date',
			'remarks' => 'Remarks',
			'reference_no' => 'Reference No',
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

		$criteria->compare('farm_payment_id',$this->farm_payment_id);
		$criteria->compare('farm_id',$this->farm_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('action_date',$this->action_date,true);
		$criteria->compare('remarks',$this->remarks,true);
		$criteria->compare('reference_no',$this->reference_no,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FarmPayment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
