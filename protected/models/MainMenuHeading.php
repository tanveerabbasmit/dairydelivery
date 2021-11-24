<?php

/**
 * This is the model class for table "main_menu_heading".
 *
 * The followings are the available columns in table 'main_menu_heading':
 * @property integer $main_menu_heading_id
 * @property integer $user_id
 * @property integer $module_action_role_id
 */
class MainMenuHeading extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'main_menu_heading';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, module_action_role_id', 'required'),
			array('user_id, module_action_role_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('main_menu_heading_id, user_id, module_action_role_id', 'safe', 'on'=>'search'),
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
			'main_menu_heading_id' => 'Main Menu Heading',
			'user_id' => 'User',
			'module_action_role_id' => 'Module Action Role',
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

		$criteria->compare('main_menu_heading_id',$this->main_menu_heading_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('module_action_role_id',$this->module_action_role_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MainMenuHeading the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
