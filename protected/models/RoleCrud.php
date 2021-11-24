<?php

/**
 * This is the model class for table "role_crud".
 *
 * The followings are the available columns in table 'role_crud':
 * @property integer $role_crud_id
 * @property integer $role_id
 * @property integer $crud_id
 * @property integer $module_action_role_id
 */
class RoleCrud extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'role_crud';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('role_id, crud_id, module_action_role_id', 'required'),
			array('role_id, crud_id, module_action_role_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('role_crud_id, role_id, crud_id, module_action_role_id', 'safe', 'on'=>'search'),
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
			'role_crud_id' => 'Role Crud',
			'role_id' => 'Role',
			'crud_id' => 'Crud',
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

		$criteria->compare('role_crud_id',$this->role_crud_id);
		$criteria->compare('role_id',$this->role_id);
		$criteria->compare('crud_id',$this->crud_id);
		$criteria->compare('module_action_role_id',$this->module_action_role_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RoleCrud the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
