<?php

/**
 * This is the model class for table "bad_debt_amount".
 *
 * The followings are the available columns in table 'bad_debt_amount':
 * @property integer $bad_debt_amount_id
 * @property integer $amount
 * @property string $reference_no
 * @property string $date
 * @property integer $client_id
 * @property integer $company_id
 */
class BadDebtAmount extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bad_debt_amount';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('amount, reference_no, date, client_id, company_id', 'required'),
			array('amount, client_id, company_id', 'numerical', 'integerOnly'=>true),
			array('reference_no', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bad_debt_amount_id, amount, reference_no, date, client_id, company_id', 'safe', 'on'=>'search'),
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
			'bad_debt_amount_id' => 'Bad Debt Amount',
			'amount' => 'Amount',
			'reference_no' => 'Reference No',
			'date' => 'Date',
			'client_id' => 'Client',
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

		$criteria->compare('bad_debt_amount_id',$this->bad_debt_amount_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('reference_no',$this->reference_no,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('company_id',$this->company_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BadDebtAmount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
