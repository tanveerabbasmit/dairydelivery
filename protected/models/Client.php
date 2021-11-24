<?php

/**
 * This is the model class for table "client".
 *
 * The followings are the available columns in table 'client':
 * @property integer $client_id
 * @property integer $user_id
 * @property integer $company_branch_id
 * @property integer $zone_id
 * @property string $fullname
 * @property string $userName
 * @property string $password
 * @property string $father_or_husband_name
 * @property string $date_of_birth
 * @property string $email
 * @property string $cnic
 * @property string $cell_no_1
 * @property string $cell_no_2
 * @property string $residence_phone_no
 * @property string $city
 * @property string $area
 * @property string $address
 * @property integer $is_active
 * @property integer $is_deleted
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_at
 * @property string $updated_at
 * @property integer $payment_term
 * @property integer $rout_order
 * @property integer $daily_delivery_sms
 * @property integer $alert_new_product
 *
 * The followings are the available model relations:
 * @property User $user
 * @property CompanyBranch $companyBranch
 * @property Zone $zone
 * @property User $createdBy
 * @property User $updatedBy
 * @property ClientProductFrequency[] $clientProductFrequencies
 * @property Complain[] $complains
 * @property Delivery[] $deliveries
 * @property Receipt[] $receipts
 */
class Client extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'client';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('', 'required'),
			array('user_id, company_branch_id, zone_id, is_active, is_deleted, created_by, updated_by, payment_term, rout_order, daily_delivery_sms, alert_new_product', 'numerical', 'integerOnly'=>true),
			array('fullname, father_or_husband_name, email, cnic, cell_no_1, cell_no_2, residence_phone_no, city, area', 'length', 'max'=>255),
			array('userName, password', 'length', 'max'=>150),
			array('address', 'length', 'max'=>512),
			array('updated_at', 'safe'),


			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('client_id, user_id, company_branch_id, zone_id, fullname, userName, password, father_or_husband_name, date_of_birth, email, cnic, cell_no_1, cell_no_2, residence_phone_no, city, area, address, is_active, is_deleted, created_by, updated_by, created_at, updated_at, payment_term, rout_order, daily_delivery_sms, alert_new_product', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'companyBranch' => array(self::BELONGS_TO, 'CompanyBranch', 'company_branch_id'),
			'zone' => array(self::BELONGS_TO, 'Zone', 'zone_id'),
			'createdBy' => array(self::BELONGS_TO, 'User', 'created_by'),
			'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
			'clientProductFrequencies' => array(self::HAS_MANY, 'ClientProductFrequency', 'client_id'),
			'complains' => array(self::HAS_MANY, 'Complain', 'client_id'),
			'deliveries' => array(self::HAS_MANY, 'Delivery', 'client_id'),
			'receipts' => array(self::HAS_MANY, 'Receipt', 'client_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'client_id' => 'Client',
			'user_id' => 'User',
			'company_branch_id' => 'Company Branch',
			'zone_id' => 'Zone',
			'fullname' => 'Fullname',
			'userName' => 'User Name',
			'password' => 'Password',
			'father_or_husband_name' => 'Father Or Husband Name',
			'date_of_birth' => 'Date Of Birth',
			'email' => 'Email',
			'cnic' => 'Cnic',
			'cell_no_1' => 'Cell No 1',
			'cell_no_2' => 'Cell No 2',
			'residence_phone_no' => 'Residence Phone No',
			'city' => 'City',
			'area' => 'Area',
			'address' => 'Address',
			'is_active' => 'Is Active',
			'is_deleted' => 'Is Deleted',
			'created_by' => 'Created By',
			'updated_by' => 'Updated By',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'payment_term' => 'Payment Term',
			'rout_order' => 'Rout Order',
			'daily_delivery_sms' => 'Daily Delivery Sms',
			'alert_new_product' => 'Alert New Product',
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

		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('company_branch_id',$this->company_branch_id);
		$criteria->compare('zone_id',$this->zone_id);
		$criteria->compare('fullname',$this->fullname,true);
		$criteria->compare('userName',$this->userName,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('father_or_husband_name',$this->father_or_husband_name,true);
		$criteria->compare('date_of_birth',$this->date_of_birth,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('cnic',$this->cnic,true);
		$criteria->compare('cell_no_1',$this->cell_no_1,true);
		$criteria->compare('cell_no_2',$this->cell_no_2,true);
		$criteria->compare('residence_phone_no',$this->residence_phone_no,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('area',$this->area,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('updated_by',$this->updated_by);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->compare('payment_term',$this->payment_term);
		$criteria->compare('rout_order',$this->rout_order);
		$criteria->compare('daily_delivery_sms',$this->daily_delivery_sms);
		$criteria->compare('alert_new_product',$this->alert_new_product);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Client the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
