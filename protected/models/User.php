<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $user_id
 * @property string $full_name
 * @property string $user_name
 * @property string $phone_number
 * @property integer $user_role_id
 * @property string $email
 * @property string $password
 * @property integer $is_active
 * @property integer $is_deleted
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Client[] $clients
 * @property Client[] $clients1
 * @property Client[] $clients2
 * @property DailyStock[] $dailyStocks
 * @property DailyStock[] $dailyStocks1
 * @property Product[] $products
 * @property Product[] $products1
 * @property Rider[] $riders
 * @property Rider[] $riders1
 * @property Rider[] $riders2
 * @property Role $userRole
 */
class User extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('full_name, user_name, phone_number, user_role_id, email', 'required'),
			array('user_role_id, is_active, is_deleted', 'numerical', 'integerOnly'=>true),
			array('full_name, user_name, phone_number', 'length', 'max'=>150),
			array('email, password', 'length', 'max'=>255),
			array('updated_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, full_name, user_name, phone_number, user_role_id, email, password, is_active, is_deleted, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'clients' => array(self::HAS_MANY, 'Client', 'user_id'),
			'clients1' => array(self::HAS_MANY, 'Client', 'created_by'),
			'clients2' => array(self::HAS_MANY, 'Client', 'updated_by'),
			'dailyStocks' => array(self::HAS_MANY, 'DailyStock', 'created_by'),
			'dailyStocks1' => array(self::HAS_MANY, 'DailyStock', 'updated_by'),
			'products' => array(self::HAS_MANY, 'Product', 'created_by'),
			'products1' => array(self::HAS_MANY, 'Product', 'updated_by'),
			'riders' => array(self::HAS_MANY, 'Rider', 'user_id'),
			'riders1' => array(self::HAS_MANY, 'Rider', 'created_by'),
			'riders2' => array(self::HAS_MANY, 'Rider', 'updated_by'),
			'userRole' => array(self::BELONGS_TO, 'Role', 'user_role_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'full_name' => 'Full Name',
			'user_name' => 'User Name',
			'phone_number' => 'Phone Number',
			'user_role_id' => 'User Role',
			'email' => 'Email',
			'password' => 'Password',
			'is_active' => 'Is Active',
			'is_deleted' => 'Is Deleted',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('full_name',$this->full_name,true);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('phone_number',$this->phone_number,true);
		$criteria->compare('user_role_id',$this->user_role_id);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
