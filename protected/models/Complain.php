<?php

/**
 * This is the model class for table "complain".
 *
 * The followings are the available columns in table 'complain':
 * @property integer $complain_id
 * @property integer $complain_type_id
 * @property integer $client_id
 * @property string $query_text
 * @property integer $status_id
 * @property string $response
 * @property string $created_on
 *
 * The followings are the available model relations:
 * @property ComplainType $complainType
 * @property Client $client
 */
class Complain extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'complain';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('complain_type_id, client_id, status_id', 'required'),
			array('complain_type_id, client_id, status_id', 'numerical', 'integerOnly'=>true),
			array('response', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('complain_id, complain_type_id, client_id, query_text, status_id, response, created_on', 'safe', 'on'=>'search'),
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
			'complainType' => array(self::BELONGS_TO, 'ComplainType', 'complain_type_id'),
			'client' => array(self::BELONGS_TO, 'Client', 'client_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'complain_id' => 'Complain',
			'complain_type_id' => 'Complain Type',
			'client_id' => 'Client',
			'query_text' => 'Query Text',
			'status_id' => 'Status',
			'response' => 'Response',
			'created_on' => 'Created On',
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

		$criteria->compare('complain_id',$this->complain_id);
		$criteria->compare('complain_type_id',$this->complain_type_id);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('query_text',$this->query_text,true);
		$criteria->compare('status_id',$this->status_id);
		$criteria->compare('response',$this->response,true);
		$criteria->compare('created_on',$this->created_on,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Complain the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
