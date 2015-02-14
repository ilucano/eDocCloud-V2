<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

use Zizaco\Entrust\HasRole;

class Login extends Eloquent implements UserInterface, RemindableInterface {
    
	    
	use HasRole;
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'logins';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		return $this->remember_token;
	}

	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		return 'remember_token';
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
	
	/**
	 *  Get the users data of logged in session
	 */
    public function getUserData()
	{
		
		return User::where('username','=',$this->username)->first();
	 
	}
	
	/**
	 * Check if logged in user is admin
	 *
	 */
	public function isAdmin()
	{
		
		if(strtolower($this-getUserData()->is_admin) == 'x')
		{
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * Check if logged in user is company admin
	 *
	 */
	public function isCompanyAdmin()
	{
		
		if(strtolower($this-getUserData()->company_admin) == 'x')
		{
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 *
	 */
	public function getCompanyName()
	{
		try {
			$company = Company::where('row_id', '=', $this->getUserData()->fk_empresa)->first();
			return $company->company_name;
		}
		catch(Exception $e) {
			return 'Company Unknown';
		}
	}
	
}
