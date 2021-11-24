<?php

/**
 * This is the model class for table "crud_option_history".
 *
 * The followings are the available columns in table 'crud_option_history':
 * @property integer $crud_option_history_id
 * @property string $action_name
 * @property string $data_befour_action
 * @property integer $user_id
 * @property string $action_date
 * @property string $selected_date
 * @property string $action_time
 * @property integer $company_id
 * @property string $modify_table_name
 * @property integer $modify_id
 * @property integer $client_id
 * @property string $new_value
 */
class CrudOptionHistory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'crud_option_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('action_name, data_befour_action, user_id, action_date, selected_date, action_time, company_id, modify_table_name, modify_id, client_id, new_value', 'required'),
			array('user_id, company_id, modify_id, client_id', 'numerical', 'integerOnly'=>true),
			array('action_name', 'length', 'max'=>100),
			array('action_time, modify_table_name, new_value', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('crud_option_history_id, action_name, data_befour_action, user_id, action_date, selected_date, action_time, company_id, modify_table_name, modify_id, client_id, new_value', 'safe', 'on'=>'search'),
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
			'crud_option_history_id' => 'Crud Option History',
			'action_name' => 'Action Name',
			'data_befour_action' => 'Data Befour Action',
			'user_id' => 'User',
			'action_date' => 'Action Date',
			'selected_date' => 'Selected Date',
			'action_time' => 'Action Time',
			'company_id' => 'Company',
			'modify_table_name' => 'Modify Table Name',
			'modify_id' => 'Modify',
			'client_id' => 'Client',
			'new_value' => 'New Value',
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

		$criteria->compare('crud_option_history_id',$this->crud_option_history_id);
		$criteria->compare('action_name',$this->action_name,true);
		$criteria->compare('data_befour_action',$this->data_befour_action,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('action_date',$this->action_date,true);
		$criteria->compare('selected_date',$this->selected_date,true);
		$criteria->compare('action_time',$this->action_time,true);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('modify_table_name',$this->modify_table_name,true);
		$criteria->compare('modify_id',$this->modify_id);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('new_value',$this->new_value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CrudOptionHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
