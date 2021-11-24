<?php

/**
 * This is the model class for table "tag_color".
 *
 * The followings are the available columns in table 'tag_color':
 * @property integer $tag_color_id
 * @property string $tag_color_name
 * @property integer $company_id
 * @property string $tag_color_code
 */
class TagColor extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tag_color';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tag_color_name, company_id, tag_color_code', 'required'),
			array('company_id', 'numerical', 'integerOnly'=>true),
			array('tag_color_name', 'length', 'max'=>60),
			array('tag_color_code', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tag_color_id, tag_color_name, company_id, tag_color_code', 'safe', 'on'=>'search'),
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
			'tag_color_id' => 'Tag Color',
			'tag_color_name' => 'Tag Color Name',
			'company_id' => 'Company',
			'tag_color_code' => 'Tag Color Code',
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

		$criteria->compare('tag_color_id',$this->tag_color_id);
		$criteria->compare('tag_color_name',$this->tag_color_name,true);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('tag_color_code',$this->tag_color_code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TagColor the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
