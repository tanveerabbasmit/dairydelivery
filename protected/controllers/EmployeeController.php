<?php

class EmployeeController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}


    public function actionemployee_list()
    {
        $this->render('employee_list' , array(
            "zoneList"=>vendor_list_data::get_employee_list_all_type(),
            "companyBranchList"=>companyBranchData::getCompanyBranchList(),
        ));
    }

    public function actionsave_employee_save_new(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $employee_id = $data['employee_id'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $responce = [];

        if($employee_id>0){
            $object = Employee::model()->findByPk($employee_id);

            $object->employee_name =$data['employee_name'];
            $object->phone_number =$data['phone_number'];
            $object->notification_alert =$data['notification_alert'];
            $object->status = $data['status'];

            $object->cnic = $data['cnic'];
            $object->address = $data['address'];

            $object->designation = $data['designation'];

            $object->company_id = $company_id;

            if($object->save()){
                $responce['success'] = true;
            }else{
                $responce['success'] = false;
                $responce['message'] = $object->getErrors();
            }
        }else{
            $object = New Employee();

            $object->employee_name =$data['employee_name'];
            $object->phone_number =$data['phone_number'];
            $object->notification_alert =$data['notification_alert'];
            $object->status = $data['status'];
            $object->company_id = $company_id;

            if($object->save()){
                $responce['success'] = true;
            }else{
                $responce['success'] = false;
                $responce['message'] = $object->getErrors();
            }
        }

        echo json_encode($responce);

    }

    public function actionsave_employee_get_vendor_list(){
        $zoneList=vendor_list_data::get_employee_list_all_type();

        echo $zoneList;

    }


}
