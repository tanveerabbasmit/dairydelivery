<?php

/**
 * This is the model class for table "cattle_production".
 *
 * The followings are the available columns in table 'cattle_production':
 * @property integer $cattle_production_id
 * @property integer $cattle_record_id
 * @property double $morning
 * @property double $afternoun
 * @property double $evenining
 * @property string $date
 */
class CattleProduction extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cattle_production';
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
			array('cattle_record_id', 'numerical', 'integerOnly'=>true),
			array('morning, afternoun, evenining', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cattle_production_id, cattle_record_id, morning, afternoun, evenining, date', 'safe', 'on'=>'search'),
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
			'cattle_production_id' => 'Cattle Production',
			'cattle_record_id' => 'Cattle Record',
			'morning' => 'Morning',
			'afternoun' => 'Afternoun',
			'evenining' => 'Evenining',
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

		$criteria->compare('cattle_production_id',$this->cattle_production_id);
		$criteria->compare('cattle_record_id',$this->cattle_record_id);
		$criteria->compare('morning',$this->morning);
		$criteria->compare('afternoun',$this->afternoun);
		$criteria->compare('evenining',$this->evenining);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CattleProduction the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
