<?php

class User extends Eloquent
{
	public function bill()
	{
		return $this->has_many('Bill');
	}
}