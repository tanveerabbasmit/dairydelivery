<?php

/**
 * This is the model class for table "company_notification".
 *
 * The followings are the available columns in table 'company_notification':
 * @property integer $company_notification_id
 * @property integer $company_id
 * @property string $heading
 * @property string $message
 * @property string $end_date
 * @property string $created_date
 */
class CompanyNotification extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'company_notification';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id, heading, message, end_date, created_date', 'required'),
			array('company_id', 'numerical', 'integerOnly'=>true),
			array('heading', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('company_notification_id, company_id, heading, message, end_date, created_date', 'safe', 'on'=>'search'),
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
			'company_notification_id' => 'Company Notification',
			'company_id' => 'Company',
			'heading' => 'Heading',
			'message' => 'Message',
			'end_date' => 'End Date',
			'created_date' => 'Created Date',
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

		$criteria->compare('company_notification_id',$this->company_notification_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('heading',$this->heading,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('created_date',$this->created_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CompanyNotification the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
