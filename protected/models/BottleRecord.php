<?php

/**
 * This is the model class for table "bottle_record".
 *
 * The followings are the available columns in table 'bottle_record':
 * @property integer $bottle_record_id
 * @property integer $client_id
 * @property integer $company_id
 * @property integer $rider_id
 * @property integer $broken
 * @property integer $perfect
 * @property string $date
 * @property string $time
 */
class BottleRecord extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bottle_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id, company_id, rider_id, broken, perfect, date, time', 'required'),
			array('client_id, company_id, rider_id, broken, perfect', 'numerical', 'integerOnly'=>true),
			array('time', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bottle_record_id, client_id, company_id, rider_id, broken, perfect, date, time', 'safe', 'on'=>'search'),
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
			'bottle_record_id' => 'Bottle Record',
			'client_id' => 'Client',
			'company_id' => 'Company',
			'rider_id' => 'Rider',
			'broken' => 'Broken',
			'perfect' => 'Perfect',
			'date' => 'Date',
			'time' => 'Time',
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

		$criteria->compare('bottle_record_id',$this->bottle_record_id);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('rider_id',$this->rider_id);
		$criteria->compare('broken',$this->broken);
		$criteria->compare('perfect',$this->perfect);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('time',$this->time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BottleRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
