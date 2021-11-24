<?php

/**
 * This is the model class for table "recive_payment_by_admin".
 *
 * The followings are the available columns in table 'recive_payment_by_admin':
 * @property integer $recive_payment_by_admin_id
 * @property integer $rider_id
 * @property integer $user_id
 * @property integer $submit_amount
 * @property string $date
 */
class RecivePaymentByAdmin extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'recive_payment_by_admin';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rider_id, user_id, submit_amount, date', 'required'),
			array('rider_id, user_id, submit_amount', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('recive_payment_by_admin_id, rider_id, user_id, submit_amount, date', 'safe', 'on'=>'search'),
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
			'recive_payment_by_admin_id' => 'Recive Payment By Admin',
			'rider_id' => 'Rider',
			'user_id' => 'User',
			'submit_amount' => 'Submit Amount',
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

		$criteria->compare('recive_payment_by_admin_id',$this->recive_payment_by_admin_id);
		$criteria->compare('rider_id',$this->rider_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('submit_amount',$this->submit_amount);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RecivePaymentByAdmin the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
