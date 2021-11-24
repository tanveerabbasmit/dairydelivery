<?php

/**
 * This is the model class for table "future_rate_list".
 *
 * The followings are the available columns in table 'future_rate_list':
 * @property integer $future_rate_list_id
 * @property integer $client_id
 * @property integer $company_id
 * @property string $start_date
 * @property string $end_date
 * @property double $rate
 */
class FutureRateList extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'future_rate_list';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id, company_id, start_date, end_date, rate', 'required'),
			array('client_id, company_id', 'numerical', 'integerOnly'=>true),
			array('rate', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('future_rate_list_id, client_id, company_id, start_date, end_date, rate', 'safe', 'on'=>'search'),
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
			'future_rate_list_id' => 'Future Rate List',
			'client_id' => 'Client',
			'company_id' => 'Company',
			'start_date' => 'Start Date',
			'end_date' => 'End Date',
			'rate' => 'Rate',
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

		$criteria->compare('future_rate_list_id',$this->future_rate_list_id);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('rate',$this->rate);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FutureRateList the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
