<?php

/**
 * This is the model class for table "change_scheduler_record".
 *
 * The followings are the available columns in table 'change_scheduler_record':
 * @property integer $change_scheduler_record_id
 * @property integer $company_id
 * @property integer $client_id
 * @property integer $change_form
 * @property string $date
 */
class ChangeSchedulerRecord extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'change_scheduler_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id, client_id, change_form, date', 'required'),
			array('company_id, client_id, change_form', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('change_scheduler_record_id, company_id, client_id, change_form, date', 'safe', 'on'=>'search'),
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
			'change_scheduler_record_id' => 'Change Scheduler Record',
			'company_id' => 'Company',
			'client_id' => 'Client',
			'change_form' => 'Change Form',
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

		$criteria->compare('change_scheduler_record_id',$this->change_scheduler_record_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('change_form',$this->change_form);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ChangeSchedulerRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
