<?php

/**
 * This is the model class for table "milk_daily_quallityreport".
 *
 * The followings are the available columns in table 'milk_daily_quallityreport':
 * @property integer $milk_daily_quallityReport_id
 * @property integer $company_branch_id
 * @property string $date
 * @property string $protein
 * @property string $lactose
 * @property string $fat
 * @property string $salt
 * @property string $adulterants
 * @property string $antiboitics
 */
class MilkDailyQuallityreport extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'milk_daily_quallityreport';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_branch_id, date, protein, lactose, fat, salt, adulterants', 'required'),
			array('company_branch_id', 'numerical', 'integerOnly'=>true),
			array('protein, lactose, fat, salt, adulterants, antiboitics', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('milk_daily_quallityReport_id, company_branch_id, date, protein, lactose, fat, salt, adulterants, antiboitics', 'safe', 'on'=>'search'),
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
			'milk_daily_quallityReport_id' => 'Milk Daily Quallity Report',
			'company_branch_id' => 'Company Branch',
			'date' => 'Date',
			'protein' => 'Protein',
			'lactose' => 'Lactose',
			'fat' => 'Fat',
			'salt' => 'Salt',
			'adulterants' => 'Adulterants',
			'antiboitics' => 'Antiboitics',
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

		$criteria->compare('milk_daily_quallityReport_id',$this->milk_daily_quallityReport_id);
		$criteria->compare('company_branch_id',$this->company_branch_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('protein',$this->protein,true);
		$criteria->compare('lactose',$this->lactose,true);
		$criteria->compare('fat',$this->fat,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('adulterants',$this->adulterants,true);
		$criteria->compare('antiboitics',$this->antiboitics,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MilkDailyQuallityreport the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
