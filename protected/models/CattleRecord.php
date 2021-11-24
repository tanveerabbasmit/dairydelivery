<?php

/**
 * This is the model class for table "cattle_record".
 *
 * The followings are the available columns in table 'cattle_record':
 * @property integer $cattle_record_id
 * @property string $number
 * @property string $type
 * @property string $milking
 * @property integer $milking_time_morning
 * @property integer $milking_time_afternoun
 * @property integer $milking_time_evening
 * @property string $picture
 * @property integer $company_id
 */
class CattleRecord extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cattle_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id', 'required'),
			array('milking_time_morning, milking_time_afternoun, milking_time_evening, company_id', 'numerical', 'integerOnly'=>true),
			array('number, type, milking, picture', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cattle_record_id, number, type, milking, milking_time_morning, milking_time_afternoun, milking_time_evening, picture, company_id', 'safe', 'on'=>'search'),
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
			'cattle_record_id' => 'Cattle Record',
			'number' => 'Number',
			'type' => 'Type',
			'milking' => 'Milking',
			'milking_time_morning' => 'Milking Time Morning',
			'milking_time_afternoun' => 'Milking Time Afternoun',
			'milking_time_evening' => 'Milking Time Evening',
			'picture' => 'Picture',
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

		$criteria->compare('cattle_record_id',$this->cattle_record_id);
		$criteria->compare('number',$this->number,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('milking',$this->milking,true);
		$criteria->compare('milking_time_morning',$this->milking_time_morning);
		$criteria->compare('milking_time_afternoun',$this->milking_time_afternoun);
		$criteria->compare('milking_time_evening',$this->milking_time_evening);
		$criteria->compare('picture',$this->picture,true);
		$criteria->compare('company_id',$this->company_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CattleRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
