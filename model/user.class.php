<?php

class User
{
	protected $id, $name, $email, $hasregistered;

	function __construct( $id, $name, $email, $hasregistered = 1 )
	{
		$this->id 			 = $id;
		$this->name 		 = $name;
		$this->email 		 = $email;
		$this->hasregistered = $hasregistered;
	}

	function __get( $prop ) { return $this->$prop; }
	function __set( $prop, $val ) { $this->$prop = $val; return $this; }
}

?>

