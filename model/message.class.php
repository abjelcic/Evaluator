<?php

require_once __DIR__ . '/../model/chatservice.class.php';

class Message
{
	protected $id, $sender, $id_channel, $date, $content, $thumbs_up;

	function __construct( $id, $sender, $id_channel, $date, $content, $thumbs_up = 0 )
	{
		$this->id 		  = $id;
		$this->sender     = $sender;
		$this->id_channel = $id_channel;
        $this->date       = $date;
		$this->content    = $content;
		$this->thumbs_up  = $thumbs_up;
	}

	function __get( $prop ) { return $this->$prop; }
	function __set( $prop, $val ) { $this->$prop = $val; return $this; }

	public function channelName()
	{
		$channel = ChatService::GetChannelById( $this->id_channel );
		if( $channel !== null )
			return $channel->title;
		else
			return 'Non-existing channel id!';
	}
}

?>

