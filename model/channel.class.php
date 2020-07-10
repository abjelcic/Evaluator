<?php

require_once __DIR__ . '/../model/chatservice.class.php';

class Channel
{
	protected $id, $id_creator, $title;
    protected $Messages;

	function __construct( $id, $id_creator, $title )
	{
		$this->id 		  = $id;
		$this->id_creator = $id_creator;
		$this->title 	  = $title;
		
		$this->Messages  = null;
    }

	function __get( $prop ) { return $this->$prop; }
	function __set( $prop, $val ) { $this->$prop = $val; return $this; }

    public function fillMessages()
    {
		$this->Messages = ChatService::GetMessagesByChannelId( $this->id );
    }
}

?>

