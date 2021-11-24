<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */

	public function authenticate()
	{


		$user = User::model()->findByAttributes(array('user_name'=>$this->username));

		if ($user===null) {
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		} else if ($user->password !== $this->password ) {
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		} else {
		   
			$this->setState('user_id', $user->user_id);
			$this->setState('role_id', $user->user_role_id);
			$this->setState('company_branch_id', $user->company_id);

			$this->setState('full_name', $user->full_name);
			$this->setState('phone_number', $user->phone_number);
			$this->setState('email', $user->email);
			$this->setState('address', $user->address);
            $this->setState('allow_delete', $user->allow_delete);

            $companyObject =Company::model()->findByPk(intval($user->company_id));

            $this->setState('currency', $companyObject->company_id);

			$this->errorCode=self::ERROR_NONE;
		}
		return !$this->errorCode;
	}
}