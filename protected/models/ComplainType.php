<?php

/**
 * This is the model class for table "complain_type".
 *
 * The followings are the available columns in table 'complain_type':
 * @property integer $complain_type_id
 * @property integer $company_branch_id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Complain[] $complains
 * @property CompanyBranch $companyBranch
 */
class ComplainType extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'complain_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_branch_id, name', 'required'),
			array('company_branch_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('complain_type_id, company_branch_id, name', 'safe', 'on'=>'search'),
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
			'complains' => array(self::HAS_MANY, 'Complain', 'complain_type_id'),
			'companyBranch' => array(self::BELONGS_TO, 'CompanyBranch', 'company_branch_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'complain_type_id' => 'Complain Type',
			'company_branch_id' => 'Company Branch',
			'name' => 'Name',
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

		$criteria->compare('complain_type_id',$this->complain_type_id);
		$criteria->compare('company_branch_id',$this->company_branch_id);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ComplainType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
