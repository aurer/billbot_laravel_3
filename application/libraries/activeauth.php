<?php

class ActiveAuth extends Laravel\Auth\Drivers\Eloquent
{
	public function attempt($arguments = array())
    {
    	$user = User::where( Config::get('auth.username'), '=', $arguments['username'] )->where_active(1)->first();

        if( $user ){
            if( Hash::check( $arguments['password'], $user->password )){
                return $this->login($user->id);
            }
        }
        return false;
    }
    public function retrieve($id)
    {
    	return User::find($id);
    }
}