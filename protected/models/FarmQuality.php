<?php

/**
 * This is the model class for table "farm_quality".
 *
 * The followings are the available columns in table 'farm_quality':
 * @property integer $farm_quality_id
 * @property integer $quality_list_id
 * @property integer $farm_id
 * @property string $date
 * @property integer $quantity_value
 */
class FarmQuality extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'farm_quality';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('quality_list_id', 'required'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('farm_quality_id, quality_list_id, farm_id, date, quantity_value', 'safe', 'on'=>'search'),
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
			'farm_quality_id' => 'Farm Quality',
			'quality_list_id' => 'Quality List',
			'farm_id' => 'Farm',
			'date' => 'Date',
			'quantity_value' => 'Quantity Value',
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

		$criteria->compare('farm_quality_id',$this->farm_quality_id);
		$criteria->compare('quality_list_id',$this->quality_list_id);
		$criteria->compare('farm_id',$this->farm_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('quantity_value',$this->quantity_value);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FarmQuality the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
