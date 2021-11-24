<?php

/**
 * This is the model class for table "role_muduleactionrole".
 *
 * The followings are the available columns in table 'role_muduleactionrole':
 * @property integer $role_muduleActionRole
 * @property integer $role_id
 * @property integer $module_action_role_id
 */
class RoleMuduleactionrole extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'role_muduleactionrole';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('role_id, module_action_role_id', 'required'),
			array('role_id, module_action_role_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('role_muduleActionRole, role_id, module_action_role_id', 'safe', 'on'=>'search'),
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
			'role_muduleActionRole' => 'Role Mudule Action Role',
			'role_id' => 'Role',
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

		$criteria->compare('role_muduleActionRole',$this->role_muduleActionRole);
		$criteria->compare('role_id',$this->role_id);
		$criteria->compare('module_action_role_id',$this->module_action_role_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RoleMuduleactionrole the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
