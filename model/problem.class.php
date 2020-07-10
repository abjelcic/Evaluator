<?php

class Problem
{
	protected $no, $title, $text, $solved;

	function __construct( $no, $title, $text, $solved )
	{
		$this->no 	  = $no;
		$this->title  = $title;
		$this->text   = $text;
		$this->solved = $solved;
    }

	function __get( $prop ) { return $this->$prop; }
	function __set( $prop, $val ) { $this->$prop = $val; return $this; }

}

?>

