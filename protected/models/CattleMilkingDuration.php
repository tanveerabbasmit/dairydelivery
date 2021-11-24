<?php

/**
 * This is the model class for table "cattle_milking_duration".
 *
 * The followings are the available columns in table 'cattle_milking_duration':
 * @property integer $cattle_milking_duration_id
 * @property integer $cattle_record_id
 * @property string $milking_on_date
 * @property integer $milking_on_active
 * @property string $milking_off_date
 * @property integer $milking_off_active
 */
class CattleMilkingDuration extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cattle_milking_duration';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cattle_record_id', 'required'),
			array('cattle_record_id, milking_on_active, milking_off_active', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cattle_milking_duration_id, cattle_record_id, milking_on_date, milking_on_active, milking_off_date, milking_off_active', 'safe', 'on'=>'search'),
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
			'cattle_milking_duration_id' => 'Cattle Milking Duration',
			'cattle_record_id' => 'Cattle Record',
			'milking_on_date' => 'Milking On Date',
			'milking_on_active' => 'Milking On Active',
			'milking_off_date' => 'Milking Off Date',
			'milking_off_active' => 'Milking Off Active',
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

		$criteria->compare('cattle_milking_duration_id',$this->cattle_milking_duration_id);
		$criteria->compare('cattle_record_id',$this->cattle_record_id);
		$criteria->compare('milking_on_date',$this->milking_on_date,true);
		$criteria->compare('milking_on_active',$this->milking_on_active);
		$criteria->compare('milking_off_date',$this->milking_off_date,true);
		$criteria->compare('milking_off_active',$this->milking_off_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CattleMilkingDuration the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
