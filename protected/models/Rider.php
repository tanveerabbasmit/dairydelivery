<?php

/**
 * This is the model class for table "rider".
 *
 * The followings are the available columns in table 'rider':
 * @property integer $rider_id
 * @property integer $company_branch_id
 * @property integer $user_id
 * @property string $fullname
 * @property string $father_name
 * @property string $email
 * @property string $cnic
 * @property string $cell_no_1
 * @property string $cell_no_2
 * @property string $residence_phone_no
 * @property string $address
 * @property integer $is_active
 * @property integer $is_deleted
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Delivery[] $deliveries
 * @property Receipt[] $receipts
 * @property User $user
 * @property User $createdBy
 * @property User $updatedBy
 * @property CompanyBranch $companyBranch
 * @property RiderDailyStock[] $riderDailyStocks
 * @property RiderZone[] $riderZones
 */
class Rider extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rider';
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
			array('company_branch_id, user_id, is_active, is_deleted, created_by, updated_by', 'numerical', 'integerOnly'=>true),
			array('fullname, father_name, email, cnic, cell_no_1, cell_no_2, residence_phone_no', 'length', 'max'=>255),
			array('address', 'length', 'max'=>512),
			array('updated_at', 'safe'),
			array('userName', 'unique'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rider_id, company_branch_id, user_id, fullname, father_name, email, cnic, cell_no_1, cell_no_2, residence_phone_no, address, is_active, is_deleted, created_by, updated_by, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'deliveries' => array(self::HAS_MANY, 'Delivery', 'rider_id'),
			'receipts' => array(self::HAS_MANY, 'Receipt', 'rider_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'createdBy' => array(self::BELONGS_TO, 'User', 'created_by'),
			'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
			'companyBranch' => array(self::BELONGS_TO, 'CompanyBranch', 'company_branch_id'),
			'riderDailyStocks' => array(self::HAS_MANY, 'RiderDailyStock', 'rider_id'),
			'riderZones' => array(self::HAS_MANY, 'RiderZone', 'rider_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'rider_id' => 'Rider',
			'company_branch_id' => 'Company Branch',
			'user_id' => 'User',
			'fullname' => 'Fullname',
			'father_name' => 'Father Name',
			'email' => 'Email',
			'cnic' => 'Cnic',
			'cell_no_1' => 'Cell No 1',
			'cell_no_2' => 'Cell No 2',
			'residence_phone_no' => 'Residence Phone No',
			'address' => 'Address',
			'is_active' => 'Is Active',
			'is_deleted' => 'Is Deleted',
			'created_by' => 'Created By',
			'updated_by' => 'Updated By',
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

		$criteria->compare('rider_id',$this->rider_id);
		$criteria->compare('company_branch_id',$this->company_branch_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('fullname',$this->fullname,true);
		$criteria->compare('father_name',$this->father_name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('cnic',$this->cnic,true);
		$criteria->compare('cell_no_1',$this->cell_no_1,true);
		$criteria->compare('cell_no_2',$this->cell_no_2,true);
		$criteria->compare('residence_phone_no',$this->residence_phone_no,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('updated_by',$this->updated_by);
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
	 * @return Rider the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
