<?php


class OtherIncomeSource extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'other_income_source';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{

		return array(
			array('other_income_source_name, company_id', 'required'),

		);
	}
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
