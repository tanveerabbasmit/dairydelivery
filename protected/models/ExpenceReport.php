<?php

/**
 * This is the model class for table "expence_report".
 *
 * The followings are the available columns in table 'expence_report':
 * @property integer $expence_record_id
 * @property integer $expenses_type_id
 * @property string $activity
 * @property string $date
 * @property string $remarks
 * @property integer $company_id
 */
class ExpenceReport extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'expence_report';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('expenses_type_id, activity, date, remarks, company_id', 'required'),
			array('expenses_type_id, company_id', 'numerical', 'integerOnly'=>true),
			array('activity', 'length', 'max'=>20),
			array('remarks', 'length', 'max'=>150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('expence_record_id, expenses_type_id, activity, date, remarks, company_id', 'safe', 'on'=>'search'),
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
			'expence_record_id' => 'Expence Record',
			'expenses_type_id' => 'Expenses Type',
			'activity' => 'Activity',
			'date' => 'Date',
			'remarks' => 'Remarks',
			'company_id' => 'Company',
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

		$criteria->compare('expence_record_id',$this->expence_record_id);
		$criteria->compare('expenses_type_id',$this->expenses_type_id);
		$criteria->compare('activity',$this->activity,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('remarks',$this->remarks,true);
		$criteria->compare('company_id',$this->company_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExpenceReport the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
