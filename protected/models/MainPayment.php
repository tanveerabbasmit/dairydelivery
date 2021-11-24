<?php

/**
 * This is the model class for table "zone".
 *
 * The followings are the available columns in table 'zone':
 * @property integer $zone_id
 * @property integer $company_branch_id
 * @property string $name
 * @property integer $is_active
 * @property integer $is_deleted
 *
 * The followings are the available model relations:
 * @property Client[] $clients
 * @property RiderZone[] $riderZones
 * @property CompanyBranch $companyBranch
 */
class MainPayment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'main_payment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type,pay_to_party_id,payment_type_id,company_id,date,head,payment_mode,	amount_paid,	reference_no', 'required'),

		);
	}


}
