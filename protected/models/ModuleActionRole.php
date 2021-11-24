<?php

/**
 * This is the model class for table "module_action_role".
 *
 * The followings are the available columns in table 'module_action_role':
 * @property integer $module_action_role_id
 * @property integer $module_action_id
 * @property integer $role_id
 * @property string $menu_name
 * @property string $icon
 * @property string $module
 * @property string $action
 * @property integer $viewPart
 */
class ModuleActionRole extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'module_action_role';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('module_action_id, role_id, menu_name, icon, module, action, viewPart', 'required'),
			array('module_action_id, role_id, viewPart', 'numerical', 'integerOnly'=>true),
			array('menu_name', 'length', 'max'=>120),
			array('icon, module, action', 'length', 'max'=>150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('module_action_role_id, module_action_id, role_id, menu_name, icon, module, action, viewPart', 'safe', 'on'=>'search'),
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
			'module_action_role_id' => 'Module Action Role',
			'module_action_id' => 'Module Action',
			'role_id' => 'Role',
			'menu_name' => 'Menu Name',
			'icon' => 'Icon',
			'module' => 'Module',
			'action' => 'Action',
			'viewPart' => 'View Part',
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

		$criteria->compare('module_action_role_id',$this->module_action_role_id);
		$criteria->compare('module_action_id',$this->module_action_id);
		$criteria->compare('role_id',$this->role_id);
		$criteria->compare('menu_name',$this->menu_name,true);
		$criteria->compare('icon',$this->icon,true);
		$criteria->compare('module',$this->module,true);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('viewPart',$this->viewPart);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ModuleActionRole the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
