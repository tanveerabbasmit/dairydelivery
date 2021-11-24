<?php

/**
 * This is the model class for table "milk_stock_report".
 *
 * The followings are the available columns in table 'milk_stock_report':
 * @property integer $milk_stock_report_id
 * @property integer $carry_forworded
 * @property integer $available_for_sale
 * @property integer $credit_sale
 * @property integer $closing_stock
 * @property string $reason
 */
class MilkStockReport extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'milk_stock_report';
	}

	/**
	 * @return array validation rules for model attributes.
	 */

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
			'milk_stock_report_id' => 'Milk Stock Report',
			'carry_forworded' => 'Carry Forworded',
			'available_for_sale' => 'Available For Sale',
			'credit_sale' => 'Credit Sale',
			'closing_stock' => 'Closing Stock',
			'reason' => 'Reason',
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

		$criteria->compare('milk_stock_report_id',$this->milk_stock_report_id);
		$criteria->compare('carry_forworded',$this->carry_forworded);
		$criteria->compare('available_for_sale',$this->available_for_sale);
		$criteria->compare('credit_sale',$this->credit_sale);
		$criteria->compare('closing_stock',$this->closing_stock);
		$criteria->compare('reason',$this->reason,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MilkStockReport the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
