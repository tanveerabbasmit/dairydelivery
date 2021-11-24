<?php

/**
 * This is the model class for table "make_regualr_drop_client".
 *
 * The followings are the available columns in table 'make_regualr_drop_client':
 * @property integer $make_regualr_drop_client_id
 * @property integer $client_id
 * @property integer $drop_or_regular
 * @property integer $sample_client_drop_reason_id
 * @property integer $company_id
 * @property string $date
 */
class MakeRegualrDropClient extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'make_regualr_drop_client';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id, drop_or_regular, sample_client_drop_reason_id, company_id, date', 'required'),
			array('client_id, drop_or_regular, sample_client_drop_reason_id, company_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('make_regualr_drop_client_id, client_id, drop_or_regular, sample_client_drop_reason_id, company_id, date', 'safe', 'on'=>'search'),
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
			'make_regualr_drop_client_id' => 'Make Regualr Drop Client',
			'client_id' => 'Client',
			'drop_or_regular' => 'Drop Or Regular',
			'sample_client_drop_reason_id' => 'Sample Client Drop Reason',
			'company_id' => 'Company',
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

		$criteria->compare('make_regualr_drop_client_id',$this->make_regualr_drop_client_id);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('drop_or_regular',$this->drop_or_regular);
		$criteria->compare('sample_client_drop_reason_id',$this->sample_client_drop_reason_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MakeRegualrDropClient the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
