<?php

/**
 * This is the model class for table "rider_delivery_right_option".
 *
 * The followings are the available columns in table 'rider_delivery_right_option':
 * @property integer $rider_delivery_right_option
 * @property integer $rider_id
 * @property integer $user_id
 * @property integer $allow_add
 * @property integer $allow_edit
 * @property string $password_for_edit_delete
 * @property integer $edit_past_days
 * @property integer $allow_delete
 * @property integer $delete_past_days
 */
class RiderDeliveryRightOption extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rider_delivery_right_option';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rider_id, user_id, allow_add, allow_edit', 'required'),
			array('rider_id, user_id, allow_add, allow_edit, edit_past_days, allow_delete, delete_past_days', 'numerical', 'integerOnly'=>true),
			array('password_for_edit_delete', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rider_delivery_right_option, rider_id, user_id, allow_add, allow_edit, password_for_edit_delete, edit_past_days, allow_delete, delete_past_days', 'safe', 'on'=>'search'),
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
			'rider_delivery_right_option' => 'Rider Delivery Right Option',
			'rider_id' => 'Rider',
			'user_id' => 'User',
			'allow_add' => 'Allow Add',
			'allow_edit' => 'Allow Edit',
			'password_for_edit_delete' => 'Password For Edit Delete',
			'edit_past_days' => 'Edit Past Days',
			'allow_delete' => 'Allow Delete',
			'delete_past_days' => 'Delete Past Days',
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

		$criteria->compare('rider_delivery_right_option',$this->rider_delivery_right_option);
		$criteria->compare('rider_id',$this->rider_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('allow_add',$this->allow_add);
		$criteria->compare('allow_edit',$this->allow_edit);
		$criteria->compare('password_for_edit_delete',$this->password_for_edit_delete,true);
		$criteria->compare('edit_past_days',$this->edit_past_days);
		$criteria->compare('allow_delete',$this->allow_delete);
		$criteria->compare('delete_past_days',$this->delete_past_days);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RiderDeliveryRightOption the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
